@php
    $path = $getState();
    $url = null;

    if ($path) {
        // If path starts with 'payment-proofs/', add 'uploads/'
        if (str_starts_with($path, 'payment-proofs/')) {
            $url = asset('uploads/' . $path);
        }
        // If path starts with 'uploads/', use directly
        elseif (str_starts_with($path, 'uploads/')) {
            $url = asset($path);
        }
        // If path is just a filename, assume it's in payment-proofs
        elseif (!str_contains($path, '/')) {
            $url = asset('uploads/payment-proofs/' . $path);
        }
        else {
            $url = asset('uploads/' . $path);
        }
    }
@endphp

@if ($url)
    <img src="{{ $url }}"
         alt="Payment Proof"
         style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px; border: 1px solid #e5e7eb;"
         onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=\'text-gray-400\'>❌</span>'">
@else
    <span class="text-gray-400">—</span>
@endif
