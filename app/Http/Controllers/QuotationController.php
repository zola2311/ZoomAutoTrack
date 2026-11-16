<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Media;
use App\Models\Quotation;
use App\Models\User;
use App\Models\Vehicle;
use App\Notifications\NewQuotationStaffAlert;
use App\Notifications\QuotationReceivedCustomerConfirmation;
use Filament\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class QuotationController extends Controller
{
    /**
     * Show the public quotation & price inquiry questionnaire.
     */
    public function create()
    {
        $customer = null;
        $customerVehicles = collect();

        if (auth('customer')->check()) {
            $customer = auth('customer')->user();
            $customerVehicles = Vehicle::where('customer_id', $customer->id)
                ->orderBy('make')
                ->get();
        }

        $serviceCategories = [
            'Engine & Diagnostics'       => 'Check Engine Light, abnormal noises, misfiring, hesitation, loss of power, overheating.',
            'Brakes & ABS'               => 'Squealing / grinding sounds, soft brake pedal, ABS warning light, rotor wear, pad replacement.',
            'Suspension & Steering'      => 'Vibrations, knocking over bumps, car pulling to one side, loose steering, shock absorbers.',
            'Transmission & Drivetrain'  => 'Rough gear shifting, slipping gears, transmission fluid leak, clutch issues, differential noise.',
            'Electrical, Battery & AC'   => 'Air conditioning blowing warm air, dead battery, alternator, starter motor, electrical shorts.',
            'Bodywork & Collision'       => 'Accident damage, dent repair, bumper replacement, paint scratch, panel beating.',
            'Pre-Purchase Inspection'    => 'Comprehensive mechanical, electrical, and chassis inspection prior to buying a used car.',
            'Other / Problem Not Listed' => 'Describe your specific defect or custom repair requirement below.',
        ];

        $commonSymptoms = [
            'Check Engine / Warning light active on dash',
            'Squeaking or grinding noise during braking',
            'Knocking or ticking sound from engine',
            'Fluid puddle or leak under vehicle (oil, coolant, ATF)',
            'Vibration or steering wheel wobble at speed',
            'Engine hesitation, sluggish acceleration, or stalling',
            'Air conditioner blowing warm or foul-smelling air',
            'Unusual exhaust smoke (white, blue, or heavy black)',
            'Vehicle pulls strongly to the left or right',
            'Visible exterior dent, scratch, or collision damage',
            'Other symptom not listed above',
        ];

        return view('site.quotation', compact('customer', 'customerVehicles', 'serviceCategories', 'commonSymptoms'));
    }

    /**
     * Store the submitted quotation inquiry and defect photos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Customer Info
            'customer_name'    => ['required', 'string', 'max:150'],
            'phone'            => ['required', 'string', 'max:30'],
            'email'            => ['required', 'email', 'max:150'],
            'city'             => ['nullable', 'string', 'max:100'],

            // Vehicle Info
            'vehicle_id'       => ['nullable', 'integer', 'exists:vehicles,id'],
            'make'             => ['required', 'string', 'max:60'],
            'model'            => ['required', 'string', 'max:60'],
            'year'             => ['nullable', 'integer', 'min:1960', 'max:' . (date('Y') + 1)],
            'plate_number'     => ['nullable', 'string', 'max:30'],
            'mileage'          => ['nullable', 'integer', 'min:0'],
            'transmission'     => ['nullable', 'string', 'in:Automatic,Manual,CVT,Other'],
            'fuel_type'        => ['nullable', 'string', 'in:Petrol,Diesel,Hybrid,Electric'],
            'vin'              => ['nullable', 'string', 'max:50'],

            // Questionnaire Details
            'service_category'    => ['required', 'string', 'max:100'],
            'other_category_text' => ['nullable', 'string', 'max:200'],
            'symptoms'            => ['nullable', 'array'],
            'symptoms.*'          => ['string', 'max:150'],
            'other_symptom_text'  => ['nullable', 'string', 'max:200'],
            'description'         => ['nullable', 'string', 'max:5000'],
            'drivable_status'     => ['required', 'in:drivable,drivable_with_care,not_drivable'],
            'urgency'             => ['required', 'in:urgent_asap,within_week,flexible'],
            'preferred_date'      => ['nullable', 'date', 'after_or_equal:today'],

            // Voice Note Audio (Microphone recording or upload)
            'voice_note'          => ['nullable', 'file', 'mimes:mp3,wav,ogg,m4a,webm,aac,flac,mp4,weba', 'max:25600'],

            // Multiple Defect Photos Upload
            'photos'              => ['nullable', 'array', 'max:15'],
            'photos.*'            => ['file', 'image', 'mimes:jpeg,png,jpg,webp,heic', 'max:15360'], // Max 15MB per image
        ], [
            'photos.*.max'        => 'Each uploaded photo must not exceed 15 megabytes.',
            'photos.*.image'      => 'Uploaded defect photos must be valid images (JPG, PNG, WEBP).',
            'voice_note.mimes'    => 'Voice note must be a valid audio recording (MP3, WAV, WEBM, M4A, OGG).',
            'voice_note.max'      => 'Voice note recording cannot exceed 25 megabytes.',
        ]);

        DB::beginTransaction();
        try {
            // Check if customer exists in the system or is authenticated
            $customerId = null;
            if (auth('customer')->check()) {
                $customerId = auth('customer')->id();
            } else {
                $matchedCustomer = Customer::where('email', $validated['email'])
                    ->orWhere('phone', $validated['phone'])
                    ->first();
                if ($matchedCustomer) {
                    $customerId = $matchedCustomer->id;
                }
            }

            // Process Category & Custom Other Category
            $finalCategory = $validated['service_category'];
            if ($finalCategory === 'Other / Problem Not Listed' && !empty($request->other_category_text)) {
                $finalCategory = 'Other: ' . trim($request->other_category_text);
            }

            // Process Symptoms & Custom Other Symptom
            $finalSymptoms = $validated['symptoms'] ?? [];
            if (!empty($request->other_symptom_text)) {
                $customSymptom = 'Other: ' . trim($request->other_symptom_text);
                if (!in_array($customSymptom, $finalSymptoms)) {
                    $finalSymptoms[] = $customSymptom;
                }
            }

            // Create Quotation Record
            $quotation = Quotation::create([
                'branch_id'        => 1, // Zoom EV Bole HQ
                'customer_id'      => $customerId,
                'vehicle_id'       => $validated['vehicle_id'] ?? null,
                'customer_name'    => $validated['customer_name'],
                'phone'            => $validated['phone'],
                'email'            => $validated['email'],
                'city'             => $validated['city'] ?? 'Addis Ababa',
                'make'             => $validated['make'],
                'model'            => $validated['model'],
                'year'             => $validated['year'] ?? null,
                'plate_number'     => $validated['plate_number'] ?? null,
                'mileage'          => $validated['mileage'] ?? null,
                'transmission'     => $validated['transmission'] ?? null,
                'fuel_type'        => $validated['fuel_type'] ?? null,
                'vin'              => $validated['vin'] ?? null,
                'service_category' => $finalCategory,
                'symptoms'         => $finalSymptoms,
                'description'      => $validated['description'] ?? null,
                'drivable_status'  => $validated['drivable_status'],
                'urgency'          => $validated['urgency'],
                'preferred_date'   => $validated['preferred_date'] ?? null,
                'status'           => 'pending',
            ]);

            // Handle voice note audio file / recording
            if ($request->hasFile('voice_note')) {
                $audioFile = $request->file('voice_note');
                $extension = $audioFile->getClientOriginalExtension() ?: 'webm';
                $audioFilename = 'voicenote_' . uniqid() . '.' . $extension;
                $audioDestination = public_path('uploads/quotations/voice_notes');

                if (!is_dir($audioDestination)) {
                    mkdir($audioDestination, 0755, true);
                }

                $audioFile->move($audioDestination, $audioFilename);
                $audioRelativePath = 'uploads/quotations/voice_notes/' . $audioFilename;

                $quotation->update([
                    'voice_note_path' => $audioRelativePath,
                ]);

                $quotation->media()->create([
                    'collection'    => 'voice_notes',
                    'disk'          => 'public_uploads',
                    'path'          => 'quotations/voice_notes/' . $audioFilename,
                    'mime_type'     => $audioFile->getClientMimeType() ?: 'audio/webm',
                    'size'          => filesize($audioDestination . '/' . $audioFilename),
                    'original_name' => $audioFile->getClientOriginalName() ?: 'voice_recording.webm',
                    'uploaded_by'   => auth()->id(),
                ]);
            }

            // Handle multiple defect photo uploads into media table
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photoFile) {
                    $disk = 'public_uploads';
                    $filename = 'defect_' . uniqid() . '.' . $photoFile->getClientOriginalExtension();
                    $destinationPath = public_path('uploads/quotations');

                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                    }

                    $photoFile->move($destinationPath, $filename);
                    $relativePath = 'quotations/' . $filename;

                    $quotation->media()->create([
                        'collection'    => 'defect_photos',
                        'disk'          => $disk,
                        'path'          => $relativePath,
                        'mime_type'     => $photoFile->getClientMimeType(),
                        'size'          => filesize($destinationPath . '/' . $filename),
                        'original_name' => $photoFile->getClientOriginalName(),
                        'uploaded_by'   => auth()->id(),
                    ]);
                }
            }

            DB::commit();

            // 1. Dispatch Instant Filament Bell Icon Notification to staff (Admin, Manager, Service Advisors)
            try {
                $staffUsers = User::whereHas('roles', function ($q) {
                    $q->whereIn('name', ['admin', 'manager', 'service_advisor']);
                })->where('is_active', true)->get();

                if ($staffUsers->isNotEmpty()) {
                    FilamentNotification::make()
                        ->title("New Quotation Request: #{$quotation->quotation_number}")
                        ->icon('heroicon-o-calculator')
                        ->iconColor('warning')
                        ->body("{$quotation->customer_name} requested estimate for {$quotation->vehicle_display_name}. Category: {$quotation->service_category}" . ($quotation->hasVoiceNote() ? ' 🎙️ (Voice Note Attached)' : ''))
                        ->actions([
                            Action::make('view')
                                ->button()
                                ->url("/admin/quotations/{$quotation->id}"),
                        ])
                        ->sendToDatabase($staffUsers);
                }
            } catch (\Throwable $e) {
                Log::warning('Error creating staff database notification for quotation: ' . $e->getMessage());
            }

            // 2. Dispatch Email to Workshop Administration
            try {
                $adminEmail = config('mail.admin_notification_email') ?: 'zola2311@gmail.com';
                Notification::route('mail', $adminEmail)->notify(new NewQuotationStaffAlert($quotation));
            } catch (\Throwable $e) {
                Log::warning('Error sending admin quotation email: ' . $e->getMessage());
            }

            // 3. Dispatch Polite Email Confirmation to Customer
            try {
                Notification::route('mail', $quotation->email)->notify(new QuotationReceivedCustomerConfirmation($quotation));
            } catch (\Throwable $e) {
                Log::warning('Error sending customer quotation confirmation email: ' . $e->getMessage());
            }

            return redirect()->route('quotation.confirmation', $quotation->quotation_number)
                ->with('success', 'Your price quotation request has been submitted successfully.');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Quotation submission error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'There was an issue processing your request: ' . $e->getMessage());
        }
    }

    /**
     * Show quotation confirmation page with reference number.
     */
    public function confirmation(string $quotationNumber)
    {
        $quotation = Quotation::where('quotation_number', $quotationNumber)
            ->with(['defectPhotos', 'voiceNotes'])
            ->firstOrFail();

        return view('site.quotation-confirmation', compact('quotation'));
    }

    /**
     * Download Official Quotation PDF.
     */
    public function downloadPdf(string $quotationNumber)
    {
        $quotation = Quotation::where('quotation_number', $quotationNumber)
            ->with(['branch', 'quotedBy'])
            ->firstOrFail();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.quotation-estimate', compact('quotation'));
        return $pdf->download("Zoom_EV_Quotation_{$quotation->quotation_number}.pdf");
    }
}
