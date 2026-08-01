<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class VoiceRecordingController extends Controller
{
    public function store(Request $request)
    {
        try {
            if (!$request->hasFile('audio')) {
                return response()->json([
                    'success' => false,
                    'error' => 'No audio file uploaded'
                ], 400);
            }

            $file = $request->file('audio');
            $filename = Str::ulid() . '.webm';

            // Save relative to the public_uploads disk root (public_path('uploads'))
            $file->move(public_path('uploads/voice-notes'), $filename);

            $path = 'voice-notes/' . $filename;  // ← no "uploads/" prefix

            Log::info('Voice saved', ['path' => $path]);

            return response()->json([
                'success' => true,
                'path' => $path,
                'url' => asset('uploads/' . $path),  // ← the URL still needs "uploads/" since that's the public web path
            ]);

        } catch (\Exception $e) {
            Log::error('Voice recording failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

}
