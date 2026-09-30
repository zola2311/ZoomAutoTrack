<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormSubmitted;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('site.contact');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        Mail::to(config('mail.contact_address', 'info@autotrack.et'))
            ->send(new ContactFormSubmitted(
                $data['name'],
                $data['email'],
                $data['phone'] ?? null,
                $data['message'],
            ));

        return back()->with('status', 'Thanks — your message has been sent. We\'ll get back to you within one business day.');
    }
}
