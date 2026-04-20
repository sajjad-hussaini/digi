{{-- resources/views/invoices/template.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_no }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            padding: 30px;
        }

        /* ===== HEADER ===== */
        .header {
            width: 100%;
            margin-bottom: 25px;
        }

        .header-table {
            width: 100%;
        }

        .client-info {
            font-size: 12px;
            line-height: 1.8;
        }

        .client-info strong {
            font-size: 13px;
        }

        .company-info {
            text-align: right;
            font-size: 11px;
            line-height: 1.8;
            color: #444;
        }

        .company-logo {
            height: 50px;
            margin-bottom: 5px;
        }

        /* ===== INVOICE META BAR ===== */
        .meta-bar {
            width: 100%;
            background: #f0f0f0;
            border: 1px solid #ccc;
            padding: 8px 12px;
            margin-bottom: 0;
        }

        .meta-bar-table {
            width: 100%;
        }

        .meta-bar-table td {
            border: none;
            padding: 0;
            font-size: 12px;
        }

        .ref-box {
            background: #fff3cd;
            color: #856404;
            padding: 3px 10px;
            font-weight: bold;
            font-size: 11px;
            border-radius: 3px;
        }

        /* ===== NOTICE ===== */
        .notice {
            background: #fff3cd;
            color: #856404;
            text-align: center;
            font-size: 11px;
            padding: 5px;
            margin-bottom: 10px;
            font-weight: 500;
        }

        /* ===== ITEMS TABLE ===== */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .items-table th {
            background: #e8e8e8;
            border: 1px solid #bbb;
            padding: 8px 10px;
            text-align: left;
            font-size: 11px;
            font-weight: bold;
        }

        .items-table th.text-right,
        .items-table td.text-right {
            text-align: right;
        }

        .items-table th.text-center,
        .items-table td.text-center {
            text-align: center;
        }

        .items-table td {
            border: 1px solid #ccc;
            padding: 8px 10px;
            font-size: 12px;
            vertical-align: top;
        }

        .items-table tr.empty-row td {
            height: 28px;
        }

        .items-table tr.total-row td {
            background: #f5f5f5;
            font-weight: bold;
        }

        .items-table tr.grand-total td {
            background: #ebebeb;
            font-size: 13px;
            font-weight: bold;
        }

        /* ===== PAYMENT INFO ===== */
        .payment-info {
            margin-top: 15px;
            border-left: 3px solid #4a90d9;
            padding: 10px 14px;
            background: #f0f7ff;
            font-size: 11px;
            color: #444;
            line-height: 1.7;
        }

        /* ===== FOOTER ===== */
        .footer {
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            text-align: center;
            font-size: 10px;
            color: #999;
        }

        /* ===== WATERMARK (agar paid ho) ===== */
        .watermark {
            position: fixed;
            top: 35%;
            left: 15%;
            font-size: 80px;
            font-weight: bold;
            color: rgba(0, 180, 0, 0.10);
            transform: rotate(-35deg);
            z-index: -1;
            letter-spacing: 5px;
        }
    </style>
</head>
<body>

{{-- Paid watermark --}}
@if($invoice->status == 'paid')
    <div class="watermark">PAID</div>
@endif

{{-- ===== HEADER ===== --}}
<table class="header-table" cellpadding="0" cellspacing="0">
    <tr>
        <td style="width: 50%; vertical-align: top;">
            <div class="client-info">
                <strong>{{ $invoice->client->first_name }} {{ $invoice->client->last_name ?? '' }}</strong><br>
                @if($invoice->client->street)
                    {{ $invoice->client->street }}<br>
                @endif
                @if($invoice->client->city)
                    {{ $invoice->client->city }}<br>
                @endif
                @if($invoice->client->country)
                    {{ $invoice->client->country }}
                @endif
            </div>
        </td>
        <td style="width: 50%; vertical-align: top; text-align: right;">
            <div class="company-info">
                <img src="{{ public_path('images/logo_imigration_law.png') }}" 
                     class="company-logo"><br>
                1st Floor, 236 St. Helens Road | Bolton | BL3 4EE<br>
                Ph: 07777528028<br>
                Email: qaimkhalifen@yahoo.com<br>
                www.silkline.co.uk
            </div>
        </td>
    </tr>
</table>

<br>

{{-- ===== INVOICE META BAR ===== --}}
<table class="meta-bar-table" cellpadding="0" cellspacing="0" 
       style="background: #f0f0f0; border: 1px solid #ccc; padding: 8px 12px;">
    <tr>
        <td style="border:none; padding: 6px 10px;">
            <strong>INVOICE No:</strong> {{ $invoice->invoice_no }}
        </td>
        <td style="border:none; padding: 6px 10px; text-align: center;">
            <strong>Date:</strong> 
            {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d F Y') }}
        </td>
        <td style="border:none; padding: 6px 10px; text-align: right;">
            <span class="ref-box">Our Ref: {{ $invoice->our_ref }}</span>
        </td>
    </tr>
</table>

{{-- ===== NOTICE ===== --}}
<div class="notice">
    Please quote on all correspondence
</div>

{{-- ===== ITEMS TABLE ===== --}}
<table class="items-table" cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th class="text-center" style="width: 6%;">Sr. No.</th>
            <th style="width: 74%;">Description of Work</th>
            <th class="text-right" style="width: 20%;">Fees</th>
        </tr>
    </thead>
    <tbody>

        {{-- Real items --}}
        @foreach($invoice->items as $item)
        <tr>
            <td class="text-center">{{ $item->sr_no }}</td>
            <td>{{ $item->description }}</td>
            <td class="text-right">£{{ number_format($item->fees, 2) }}</td>
        </tr>
        @endforeach

        {{-- Empty rows for spacing (total 8 rows dikhao) --}}
        @for($i = count($invoice->items); $i < 8; $i++)
        <tr class="empty-row">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        @endfor

        {{-- VAT --}}
        <tr class="total-row">
            <td colspan="2" style="text-align: right; border: 1px solid #ccc;">
                <strong>VAT</strong>
            </td>
            <td class="text-right">£{{ number_format($invoice->vat, 2) }}</td>
        </tr>

        {{-- Total Due --}}
        <tr class="grand-total">
            <td colspan="2" style="text-align: right; border: 1px solid #ccc;">
                <strong>Total Due</strong>
            </td>
            <td class="text-right">
                <strong>£{{ number_format($invoice->total_due, 2) }}</strong>
            </td>
        </tr>

    </tbody>
</table>

{{-- ===== PAYMENT INFO ===== --}}
<div class="payment-info">
    Please make payment to <strong>UK Immigration Law</strong> at
    (Bank: <strong>ANNA Bank</strong>,
    Account no: <strong>75841370</strong>
    Sort Code: <strong>04-03-70</strong>)
</div>

{{-- ===== FOOTER ===== --}}
<div class="footer">
    &copy; {{ date('Y') }} UK Immigration Law &mdash; www.silkline.co.uk
    &nbsp;|&nbsp; 
    Generated on {{ now()->format('d M Y, h:i A') }}
</div>

</body>
</html>