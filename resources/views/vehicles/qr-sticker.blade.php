<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>QR Sticker — {{ $vehicle->plate_number }}</title>
    <style>
        @page { size: 60mm 40mm; margin: 0; }
        * { box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
            background: #f2f2f2;
        }
        .toolbar {
            padding: 16px;
            text-align: center;
        }
        .toolbar button {
            font-size: 14px;
            padding: 8px 18px;
            border-radius: 6px;
            border: 1px solid #0C447C;
            background: #0C447C;
            color: #fff;
            cursor: pointer;
        }
        .sticker {
            width: 60mm;
            height: 40mm;
            margin: 0 auto 24px;
            background: #fff;
            border: 1px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6mm;
            padding: 3mm;
        }
        .sticker .qr {
            flex-shrink: 0;
            line-height: 0;
        }
        .sticker .qr svg, .sticker .qr img {
            width: 30mm;
            height: 30mm;
        }
        .sticker .info {
            font-size: 3mm;
            line-height: 1.4;
        }
        .sticker .plate {
            font-weight: bold;
            font-size: 4mm;
            margin-bottom: 1.5mm;
        }
        .sticker .brand {
            color: #666;
            margin-top: 1.5mm;
        }

        @media print {
            .toolbar { display: none; }
            body { background: #fff; }
            .sticker { border: none; margin: 0; }
        }
    </style>
</head>
<body>
<div class="toolbar">
    <button onclick="window.print()">Print sticker</button>
</div>

<div class="sticker">
    <div class="qr">
        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($vehicle->passportUrl()) !!}
    </div>
    <div class="info">
        <div class="plate">{{ $vehicle->plate_number }}</div>
        <div>{{ $vehicle->make }} {{ $vehicle->model }}</div>
        <div class="brand">Scan for full service history</div>
    </div>
</div>
</body>
</html>
