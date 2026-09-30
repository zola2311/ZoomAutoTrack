<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        return view('portal.profile', [
            'customer' => Auth::guard('customer')->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $customer = Auth::guard('customer')->user();

        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:20',
                Rule::unique('customers', 'phone')
                    ->ignore($customer->id)
                    ->whereNull('deleted_at'),
            ],
            'email' => ['required', 'email', 'max:150',
                Rule::unique('customers', 'email')
                    ->ignore($customer->id)
                    ->whereNull('deleted_at'),
            ],
            'current_password' => ['nullable', 'string'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        // Password change — only if current_password provided and correct.
        if ($request->filled('current_password')) {
            if (! \Illuminate\Support\Facades\Hash::check($request->current_password, $customer->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }

            if ($request->filled('password')) {
                $customer->password = $data['password'];
            }
        }

        $customer->full_name = $data['full_name'];
        $customer->phone = $data['phone'];
        $customer->email = $data['email'];
        $customer->save();

        return back()->with('status', 'Profile updated successfully.');
    }
}
