<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Print')</title>
    <style>
        @page { size: A4; margin: 15mm; }
        * { box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #1a1a1a;
            margin: 0;
            padding: 20px;
        }
        .toolbar { text-align: center; margin-bottom: 20px; }
        .toolbar button {
            font-size: 14px;
            padding: 8px 18px;
            border-radius: 6px;
            border: 1px solid #0C447C;
            background: #0C447C;
            color: #fff;
            cursor: pointer;
        }
        .letterhead {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #0C447C;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .letterhead .brand { font-size: 20px; font-weight: bold; color: #0C447C; }
        .letterhead .branch-info { font-size: 12px; color: #555; margin-top: 4px; }
        .letterhead .doc-meta { text-align: right; font-size: 12px; color: #555; }
        .letterhead .doc-title { font-size: 18px; font-weight: bold; color: #1a1a1a; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th, td { padding: 6px 8px; font-size: 13px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f5f5f5; }
        .text-end { text-align: right; }
        .totals { width: 260px; margin-left: auto; }
        .totals td { border: none; padding: 3px 8px; }
        .totals .grand { font-weight: bold; font-size: 15px; border-top: 2px solid #333; }

        @media print {
            .toolbar { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
<div class="toolbar">
    <button onclick="window.print()">Print</button>
</div>

@yield('content')
</body>
</html>
