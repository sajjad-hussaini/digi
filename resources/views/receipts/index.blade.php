@extends('layouts.app')
@section('title', 'Receipts')
@section('content')
<div style="padding:20px;">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2 style="font-size:20px; font-weight:bold;">All Receipts</h2>
        <a href="{{ route('receipts.create') }}"
           style="padding:8px 18px; background:#1565C0; color:#fff; text-decoration:none; border-radius:3px; font-size:13px;">
            + Manual Receipt
        </a>
    </div>

    {{-- Filters --}}
    <form method="GET" style="display:flex; gap:10px; margin-bottom:18px; flex-wrap:wrap;">
        <select name="client_id" style="padding:6px 10px; border:1px solid #ccc; border-radius:3px; font-size:13px;">
            <option value="">All Clients</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                    {{ $client->first_name }}
                </option>
            @endforeach
        </select>
        <input type="date" name="from" value="{{ request('from') }}"
               style="padding:6px 10px; border:1px solid #ccc; border-radius:3px; font-size:13px;">
        <input type="date" name="to" value="{{ request('to') }}"
               style="padding:6px 10px; border:1px solid #ccc; border-radius:3px; font-size:13px;">
        <button type="submit"
                style="padding:6px 16px; background:#555; color:#fff; border:none; cursor:pointer; border-radius:3px; font-size:13px;">
            Filter
        </button>
        <a href="{{ route('receipts.index') }}"
           style="padding:6px 14px; color:#555; text-decoration:none; font-size:13px; line-height:2;">
            Clear
        </a>
    </form>

    {{-- Table --}}
    <table style="width:100%; border-collapse:collapse; font-size:13px;">
        <thead>
            <tr style="background:#f5f5f5; border-bottom:2px solid #1565C0;">
                <th style="padding:10px 12px; text-align:left;">Receipt No.</th>
                <th style="padding:10px 12px; text-align:left;">Client</th>
                <th style="padding:10px 12px; text-align:left;">Invoice Ref</th>
                <th style="padding:10px 12px; text-align:left;">Date</th>
                <th style="padding:10px 12px; text-align:left;">Method</th>
                <th style="padding:10px 12px; text-align:right;">Amount</th>
                <th style="padding:10px 12px; text-align:center;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($receipts as $receipt)
            <tr style="border-bottom:1px solid #eee;">
                <td style="padding:9px 12px;">
                    {{ str_pad($receipt->receipt_number, 4, '0', STR_PAD_LEFT) }}
                </td>
                <td style="padding:9px 12px;">{{ $receipt->client->name }}</td>
                <td style="padding:9px 12px;">
                    {{ str_pad($receipt->invoice->invoice_number ?? '-', 4, '0', STR_PAD_LEFT) }}
                </td>
                <td style="padding:9px 12px;">
                    {{ \Carbon\Carbon::parse($receipt->payment_date)->format('d/m/Y') }}
                </td>
                <td style="padding:9px 12px; text-transform:capitalize;">
                    {{ str_replace('_', ' ', $receipt->payment_method) }}
                </td>
                <td style="padding:9px 12px; text-align:right; font-weight:bold;">
                    £{{ number_format($receipt->amount_paid, 2) }}
                </td>
                <td style="padding:9px 12px; text-align:center;">
                    <a href="{{ route('receipts.show', $receipt) }}"
                       style="color:#1565C0; text-decoration:none; margin-right:10px; font-size:12px;">
                        View
                    </a>
                    <a href="{{ route('receipts.pdf', $receipt) }}"
                       style="color:#2e7d32; text-decoration:none; font-size:12px;">
                        PDF
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="padding:20px; text-align:center; color:#999;">
                    No receipts found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div style="margin-top:16px;">
        {{ $receipts->withQueryString()->links() }}
    </div>

</div>
@endsection

