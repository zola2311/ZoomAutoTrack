<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PaymentProofController extends Controller
{
    public function show(Payment $payment): StreamedResponse|Response
    {
        // Must be logged in — Laravel's auth middleware already
        // blocks guests before this method runs (see route file).

        if (! $payment->proof_path) {
            abort(404);
        }

        $disk = \Illuminate\Support\Facades\Storage::disk('public_uploads');

        if (! $disk->exists($payment->proof_path)) {
            abort(404);
        }

        return $disk->response($payment->proof_path);
    }
}
