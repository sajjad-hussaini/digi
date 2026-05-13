{{-- resources/views/receipts/receipt.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ str_pad($receipt->receipt_number, 4, '0', STR_PAD_LEFT) }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #000;
            background: #fff;
            padding: 30px;
        }

        .receipt-wrapper {
            max-width: 700px;
            margin: 0 auto;
            border: 1.5px solid #000;
            padding: 20px 25px 30px;
        }

        /* ===== HEADER ===== */
        .header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 18px;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-section img {
            width: 65px;
            height: 65px;
            object-fit: contain;
        }

        .receipt-title {
            text-align: center;
            flex: 1;
        }

        .receipt-title h1 {
            font-size: 32px;
            font-weight: bold;
            color: #1565C0;
            letter-spacing: 2px;
            margin-top: 8px;
        }

        .meta-section {
            text-align: right;
            min-width: 200px;
        }

        .meta-row {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 4px;
            gap: 5px;
        }

        .meta-label {
            font-size: 13px;
            color: #000;
        }

        .meta-value {
            border-bottom: 1px solid #000;
            min-width: 90px;
            padding-bottom: 1px;
            font-size: 13px;
            text-align: left;
        }

        /* ===== AMOUNT BOX ===== */
        .amount-row {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 12px;
        }

        .amount-box {
            display: flex;
            align-items: center;
            border: 1px solid #000;
        }

        .amount-label {
            background: #c8c8c8;
            border-right: 1px solid #000;
            padding: 5px 12px;
            font-weight: bold;
            font-size: 13px;
        }

        .amount-value {
            padding: 5px 14px;
            font-weight: bold;
            font-size: 14px;
            min-width: 100px;
        }

        /* ===== DIVIDER ===== */
        .divider {
            border-top: 1.5px solid #1565C0;
            margin-bottom: 12px;
        }

        /* ===== DETAIL ROWS ===== */
        .detail-section {
            margin-bottom: 10px;
        }

        .detail-row {
            display: flex;
            align-items: baseline;
            margin-bottom: 6px;
        }

        .detail-label {
            font-weight: bold;
            min-width: 135px;
            font-size: 13px;
            flex-shrink: 0;
        }

        .detail-value {
            border-bottom: 1px solid #000;
            flex: 1;
            padding-bottom: 1px;
            font-size: 13px;
        }

        /* For Payment Of - multiline */
        .payment-of-row {
            display: flex;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .payment-of-value {
            border-bottom: 1px solid #000;
            flex: 1;
            padding-bottom: 1px;
            font-size: 13px;
            line-height: 1.6;
        }

        /* ===== FOOTER SECTION ===== */
        .divider-bottom {
            border-top: 1.5px solid #1565C0;
            margin-bottom: 14px;
        }

        .footer-grid {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        /* Left: Received By + Name + Address + Phone */
        .footer-left {
            flex: 1;
        }

        .footer-row {
            display: flex;
            align-items: baseline;
            margin-bottom: 6px;
        }

        .footer-label {
            font-weight: bold;
            min-width: 75px;
            font-size: 13px;
            flex-shrink: 0;
        }

        .footer-value {
            border-bottom: 1px solid #000;
            flex: 1;
            padding-bottom: 1px;
            font-size: 13px;
        }

        /* Right: Payment method checkboxes */
        .footer-right {
            min-width: 170px;
            padding-left: 20px;
        }

        .payment-methods {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .payment-method-row {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }

        .paid-by-label {
            font-weight: bold;
            margin-bottom: 6px;
            font-size: 13px;
        }

        .checkbox {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 14px;
            height: 14px;
            border: 1px solid #000;
            font-size: 11px;
            flex-shrink: 0;
        }

        .checkbox.checked {
            font-weight: bold;
        }

        /* Check No. inline */
        .check-row {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .check-no-line {
            border-bottom: 1px solid #000;
            flex: 1;
            min-width: 60px;
        }

        /* Print only */
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

{{-- Print / PDF Buttons (hidden on print) --}}
<div class="no-print" style="max-width:700px; margin:0 auto 15px; display:flex; gap:10px;">
    <button onclick="window.print()"
        style="padding:8px 20px; background:#1565C0; color:#fff; border:none; cursor:pointer; font-size:13px; border-radius:3px;">
        🖨️ Print
    </button>
    <a href="{{ route('receipts.pdf', $receipt->id) }}"
        style="padding:8px 20px; background:#2e7d32; color:#fff; text-decoration:none; font-size:13px; border-radius:3px;">
        📄 Download PDF
    </a>
    <a href="{{ route('receipts.index') }}"
        style="padding:8px 20px; background:#555; color:#fff; text-decoration:none; font-size:13px; border-radius:3px;">
        ← Back
    </a>
</div>

{{-- ============================================================ --}}
{{--  RECEIPT BOX                                                  --}}
{{-- ============================================================ --}}
<div class="receipt-wrapper">

    {{-- HEADER --}}
    <div class="header">

        {{-- Logo --}}
        <div class="logo-section">
            @if(file_exists(public_path('images/logo_imigration_law.png')))
                <img src="{{ asset('images/logo_imigration_law.png') }}" alt="Logo">
            @else
                {{-- Fallback placeholder --}}
                <div style="width:65px;height:65px;border:1px solid #ccc;display:flex;align-items:center;
                            justify-content:center;font-size:9px;color:#666;text-align:center;">
                    LOGO
                </div>
            @endif
        </div>

        {{-- Title --}}
        <div class="receipt-title">
            <h1>RECEIPT</h1>
        </div>

        {{-- Receipt No / Ref / Date --}}
        <div class="meta-section">
            <div class="meta-row">
                <span class="meta-label">Receipt No.</span>
                <span class="meta-value">{{ str_pad($receipt->receipt_number, 4, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="meta-row">
                <span class="meta-label">Ref No.</span>
                <span class="meta-value">{{ str_pad($receipt->ref_number ?? $receipt->invoice->invoice_number, 4, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="meta-row">
                <span class="meta-label">Date</span>
                <span class="meta-value">{{ \Carbon\Carbon::parse($receipt->payment_date)->format('n/j/Y') }}</span>
            </div>
        </div>
    </div>

    {{-- Amount Box (right aligned) --}}
    <div class="amount-row">
        <div class="amount-box">
            <span class="amount-label">Amount</span>
            <span class="amount-value">£{{ number_format($receipt->amount_paid, 2) }}</span>
        </div>
    </div>

    {{-- Blue divider --}}
    <div class="divider"></div>

    {{-- Received From --}}
    <div class="detail-section">
        <div class="detail-row">
            <span class="detail-label">Received From</span>
            <span class="detail-value">{{ $receipt->client->title ?? '' }} {{ $receipt->client->name }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Amount</span>
            <span class="detail-value">{{ $receipt->amount_in_words }}</span>
        </div>
    </div>

    {{-- For Payment Of --}}
    <div class="payment-of-row">
        <span class="detail-label" style="padding-top:2px;">For Payment of</span>
        <span class="payment-of-value">{{ $receipt->payment_for }}</span>
    </div>

    {{-- Blue divider --}}
    <div class="divider-bottom"></div>

    {{-- Footer: Received By + Payment Method --}}
    <div class="footer-grid">

        {{-- Left --}}
        <div class="footer-left">
            <div class="footer-row">
                <span class="footer-label">Received By</span>
                <span class="footer-value">{{ 'Mohamad Salim Kureshi' }}</span>
            </div>
            <div class="footer-row">
                <span class="footer-label">Name</span>
                <span class="footer-value">
                    <span class="name-box">{{ config('receipt.firm_name', 'UK Immigration Law') }}</span>
                </span>
            </div>
            <div class="footer-row">
                <span class="footer-label">Address</span>
                <span class="footer-value">{{ config('receipt.address', '1st Floor, 236 St. Helens Road Bolton BL3 4EB') }}</span>
            </div>
            <div class="footer-row">
                <span class="footer-label">Phone</span>
                <span class="footer-value">{{ config('receipt.phone', '077 773 28 28') }}</span>
            </div>
        </div>

        {{-- Right: Payment Methods --}}
        <div class="footer-right">
            <div class="paid-by-label">Paid by</div>
            <div class="payment-methods">

                {{-- Cash --}}
                <div class="payment-method-row">
                    <span class="checkbox {{ $receipt->payment_method === 'cash' ? 'checked' : '' }}">
                        {{ $receipt->payment_method === 'cash' ? '✓' : '' }}
                    </span>
                    Cash
                </div>

                {{-- Cheque --}}
                <div class="payment-method-row check-row">
                    <span class="checkbox {{ $receipt->payment_method === 'cheque' ? 'checked' : '' }}">
                        {{ $receipt->payment_method === 'cheque' ? '✓' : '' }}
                    </span>
                    Check No.
                    <span class="check-no-line">
                        {{ $receipt->payment_method === 'cheque' ? ($receipt->cheque_number ?? '') : '' }}
                    </span>
                </div>

                {{-- Money Order --}}
                <div class="payment-method-row">
                    <span class="checkbox {{ $receipt->payment_method === 'money_order' ? 'checked' : '' }}">
                        {{ $receipt->payment_method === 'money_order' ? '✓' : '' }}
                    </span>
                    Money Order
                </div>

                {{-- BACS --}}
                <div class="payment-method-row">
                    <span class="checkbox {{ $receipt->payment_method === 'bacs' ? 'checked' : '' }}">
                        {{ $receipt->payment_method === 'bacs' ? '✓' : '' }}
                    </span>
                    BACS
                </div>

            </div>
        </div>
    </div>

</div>{{-- end receipt-wrapper --}}

</body>
</html>