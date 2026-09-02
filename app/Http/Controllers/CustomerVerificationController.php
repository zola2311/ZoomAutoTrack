<?php



namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CustomerVerificationController extends Controller
{
    public function notice(): View|RedirectResponse
    {
        if (Auth::guard('customer')->user()->hasVerifiedEmail()) {
            return redirect()->route('portal.dashboard');
        }

        return view('portal.auth.verify-email');
    }

    public function verify(Request $request): RedirectResponse
    {
        $customer = Auth::guard('customer')->user();

        if (! hash_equals((string) $request->route('id'), (string) $customer->getKey())
            || ! hash_equals(sha1($customer->getEmailForVerification()), (string) $request->route('hash'))) {
            abort(403, 'Invalid verification link.');
        }

        if (! $customer->hasVerifiedEmail()) {
            $customer->markEmailAsVerified();
        }

        return redirect()->route('portal.dashboard')->with('status', 'Email verified — welcome!');
    }

    public function resend(Request $request): RedirectResponse
    {
        $customer = Auth::guard('customer')->user();

        if ($customer->hasVerifiedEmail()) {
            return redirect()->route('portal.dashboard');
        }

        $customer->sendEmailVerificationNotification();

        return back()->with('status', 'Verification link sent.');
    }
}
