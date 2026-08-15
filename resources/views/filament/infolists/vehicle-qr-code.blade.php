@php($url = $getRecord()->passportUrl())
@if($url)
    <div style="background:#fff;padding:8px;display:inline-block;border-radius:8px;">
        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(140)->generate($url) !!}
    </div>
@else
    <span class="text-sm text-gray-400">QR code will appear once saved</span>
@endif
