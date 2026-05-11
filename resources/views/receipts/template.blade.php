<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #222;
            padding: 30px 40px;
        }

        /* ===== TOP HEADER ===== */
        .top-header {
            width: 100%;
            margin-bottom: 15px;
        }

        .receipt-title {
            font-size: 28px;
            font-weight: bold;
            color: #1a56a0;
            letter-spacing: 2px;
        }

        .logo {
            height: 60px;
        }

        /* ===== META INFO (top right) ===== */
        .meta-table {
            width: 100%;
            margin-bottom: 12px;
        }

        .meta-table td {
            border: none;
            padding: 2px 0;
            font-size: 12px;
        }

        .meta-label {
            text-align: right;
            color: #555;
            padding-right: 8px;
            width: 90px;
        }

        .meta-value {
            border-bottom: 1px dotted #aaa;
            padding-left: 5px;
            min-width: 100px;
        }

        /* ===== AMOUNT BOX ===== */
        .amount-box {
            border: 1px solid #888;
            background: #e8e8e8;
            padding: 5px 14px;
            font-size: 13px;
            font-weight: bold;
            display: inline-block;
        }

        /* ===== INFO ROWS ===== */
        .info-table {
            width: 100%;
            margin: 12px 0;
            border-collapse: collapse;
        }

        .info-table td {
            border: none;
            padding: 7px 0;
            border-bottom: 1px solid #ddd;
            font-size: 12px;
        }

        .info-label {
            color: #333;
            font-weight: bold;
            width: 120px;
            vertical-align: top;
        }

        .info-value {
            border-bottom: 1px solid #aaa;
            padding-left: 8px;
            vertical-align: top;
            line-height: 1.6;
        }

        /* ===== BOTTOM SECTION ===== */
        .bottom-table {
            width: 100%;
            margin-top: 20px;
        }

        .bottom-table td {
            border: none;
            padding: 5px 0;
            font-size: 12px;
            vertical-align: top;
        }

        .received-label {
            font-weight: bold;
            width: 90px;
            color: #333;
        }

        .received-value {
            border-bottom: 1px solid #aaa;
            padding-left: 5px;
        }

        /* ===== PAYMENT METHOD ===== */
        .payment-methods {
            border-left: 1px solid #ccc;
            padding-left: 20px;
        }

        .payment-row {
            display: table-row;
            margin-bottom: 5px;
            font-size: 12px;
            line-height: 2;
        }

        .checkbox {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 1px solid #555;
            text-align: center;
            line-height: 13px;
            font-size: 11px;
            margin-right: 6px;
            vertical-align: middle;
        }

        .checkbox.checked {
            font-weight: bold;
        }

        /* ===== BORDER ===== */
        .page-border {
            border: 2px solid #1a56a0;
            padding: 20px 25px;
        }
    </style>
</head>
<body>
<div class="page-border">

    {{-- ===== TOP HEADER ===== --}}
    <table class="top-header" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width: 70px; vertical-align: middle;">
                <img src="{{ public_path('images/logo_imigration_law.png') }}" class="logo">
            </td>
            <td style="vertical-align: middle; padding-left: 10px;">
                <span class="receipt-title">RECEIPT</span>
            </td>
            <td style="vertical-align: top; text-align: right;">
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="meta-label">Receipt No.</td>
                        <td class="meta-value">{{ $receipt->receipt_no }}</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Ref No.</td>
                        <td class="meta-value">{{ $receipt->ref_no }}</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Date</td>
                        <td class="meta-value">
                            {{ \Carbon\Carbon::parse($receipt->date)->format('n/j/Y') }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <hr style="border: 0.5px solid #ccc; margin: 10px 0;">

    {{-- ===== AMOUNT BOX ===== --}}
    <div style="text-align: right; margin-bottom: 12px;">
        <span class="amount-box">
            Amount &nbsp; £{{ number_format($receipt->amount, 2) }}
        </span>
    </div>

    {{-- ===== INFO ROWS ===== --}}
    <table class="info-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="info-label">Received From</td>
            <td class="info-value">
                {{ $receipt->client->first_name }} {{ $receipt->client->last_name ?? '' }}
            </td>
        </tr>
        <tr>
            <td class="info-label">Amount</td>
            <td class="info-value">{{ $receipt->amount_in_words }}</td>
        </tr>
        <tr>
            <td class="info-label">For Payment of</td>
            <td class="info-value">{{ $receipt->for_payment_of }}</td>
        </tr>
    </table>

    <hr style="border: 0.5px solid #ccc; margin: 15px 0;">

    {{-- ===== BOTTOM SECTION ===== --}}
    <table class="bottom-table" cellpadding="0" cellspacing="0">
        <tr>
            {{-- Left: Received By --}}
            <td style="width: 55%; vertical-align: top;">
                <table cellpadding="0" cellspacing="4">
                    <tr>
                        <td class="received-label">Received By</td>
                        <td class="received-value">{{ $receipt->received_by }}</td>
                    </tr>
                    <tr>
                        <td class="received-label">Name</td>
                        <td class="received-value">UK Immigration Law</td>
                    </tr>
                    <tr>
                        <td class="received-label">Address</td>
                        <td class="received-value">
                            1st Floor, 236 St. Helens Road Bolton BL3 4EB
                        </td>
                    </tr>
                    <tr>
                        <td class="received-label">Phone</td>
                        <td class="received-value">077 773 28 28</td>
                    </tr>
                </table>
            </td>

            {{-- Right: Payment Method --}}
            <td style="width: 45%; vertical-align: top; padding-left: 20px; border-left: 1px solid #ccc;">
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="padding: 4px 0; font-size: 12px; line-height: 2;">
                            <span class="checkbox {{ $receipt->paid_by == 'cash' ? 'checked' : '' }}">
                                {{ $receipt->paid_by == 'cash' ? '✓' : '&nbsp;' }}
                            </span>
                            Cash
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; font-size: 12px; line-height: 2;">
                            <span class="checkbox {{ $receipt->paid_by == 'cheque' ? 'checked' : '' }}">
                                {{ $receipt->paid_by == 'cheque' ? '✓' : '&nbsp;' }}
                            </span>
                            Check No.
                            @if($receipt->paid_by == 'cheque' && $receipt->cheque_no)
                                <span style="border-bottom: 1px solid #aaa; padding: 0 20px;">
                                    {{ $receipt->cheque_no }}
                                </span>
                            @else
                                <span style="border-bottom: 1px solid #aaa; padding: 0 40px;">&nbsp;</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; font-size: 12px; line-height: 2;">
                            <span class="checkbox {{ $receipt->paid_by == 'money_order' ? 'checked' : '' }}">
                                {{ $receipt->paid_by == 'money_order' ? '✓' : '&nbsp;' }}
                            </span>
                            Money Order
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; font-size: 12px; line-height: 2;">
                            <span class="checkbox {{ $receipt->paid_by == 'bacs' ? 'checked' : '' }}">
                                {{ $receipt->paid_by == 'bacs' ? '✓' : '&nbsp;' }}
                            </span>
                            BACS
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</div>
</body>
</html>