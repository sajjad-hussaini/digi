{{-- resources/views/invoices/template.blade.php --}}
<!DOCTYPE html>
<html>
<head>
<style>
  body { font-family: Arial, sans-serif; font-size: 12px; }
  .header { display: flex; justify-content: space-between; margin-bottom: 20px; }
  .client-info { width: 45%; padding-top: 20px;}
  .company-info { width: 45%; text-align: right; font-size: 11px; }
  .invoice-meta { background: #f0f0f0; padding: 8px; display: flex; 
                  justify-content: space-between; border: 1px solid #ccc; }
  .ref-box { background: yellow; padding: 3px 8px; font-weight: bold; }
  .notice { background: yellow; text-align: center; font-size: 11px; 
            padding: 4px; margin: 5px 0; }
  table { width: 100%; border-collapse: collapse; margin-top: 10px; }
  th { background: #ddd; border: 1px solid #999; padding: 6px; text-align: left; }
  td { border: 1px solid #ccc; padding: 6px; vertical-align: top; }
  .total-row td { font-weight: bold; }
  .payment-info { margin-top: 10px; border: 1px solid #ccc; 
                  padding: 8px; font-size: 11px; }
</style>
</head>
<body>

{{-- Header --}}
<div class="header">
  <div class="client-info">
    <strong>{{ $invoice->client->first_name }} {{ $invoice->client->sir_name }}</strong><br>
    {{ $invoice->client->email }}<br>
    {{ $invoice->client->address }}<br>
    {{ $invoice->client->city }}<br>
    {{ $invoice->client->country }}
  </div>
  <div class="company-info">
    <img src="{{ asset('images/logo_imigration_law.png') }}" height="50"><br>
    1st Floor, 236 St. Helens Road | Bolton | BL3 4EE<br>
    Ph: 07777528028<br>
    Email: qaimkhalifen@yahoo.com<br>
    www.silkline.co.uk
  </div>
</div>

{{-- Invoice Meta --}}
<div class="invoice-meta">
  <span><strong>INVOICE No:</strong> {{ $invoice->invoice_no }}</span>
  <span><strong>Date:</strong> {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d F Y') }}</span>
  <span class="ref-box"><strong>Our Ref: {{ $invoice->our_ref }}</strong></span>
</div>

<div class="notice">Please quote on all correspondence</div>

{{-- Items Table --}}
<table>
  <thead>
    <tr>
      <th width="5%">Sr. No.</th>
      <th width="75%">Description of Work</th>
      <th width="20%">Fees</th>
    </tr>
  </thead>
  <tbody>
    @foreach($invoice->items as $item)
    <tr>
      <td>{{ $item->sr_no }}</td>
      <td>{{ $item->description }}</td>
      <td>£{{ number_format($item->fees, 2) }}</td>
    </tr>
    @endforeach

    {{-- Empty rows for spacing --}}
    @for($i = count($invoice->items); $i < 8; $i++)
    <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
    @endfor

    <tr>
      <td colspan="2" align="right"><strong>VAT</strong></td>
      <td>£{{ number_format($invoice->vat, 2) }}</td>
    </tr>
    <tr class="total-row">
      <td colspan="2" align="right"><strong>Total Due</strong></td>
      <td><strong>£{{ number_format($invoice->total_due, 2) }}</strong></td>
    </tr>
  </tbody>
</table>

{{-- Payment Info --}}
<div class="payment-info">
  Please make payment to <strong>UK Immigration Law</strong> at 
  (Bank: <strong>ANNA Bank</strong>, Account no: <strong>75841370</strong> 
  Sort Code: <strong>04-03-70</strong>)
</div>
<script>
function printInvoice() {
    window.print();
}
</script>
</body>
</html>