
    <div class="form-group col-sm-6 {{ $errors->has('client_id') ? 'has-error' : '' }}">
        {!! Form::label('client_id', 'Client:') !!}
        {!! Form::select('client_id', $clients->pluck('first_name', 'id'), old('client_id'), [
            'class' => 'form-control select2',
            'placeholder' => 'Select Client'
        ]) !!}
        {!! $errors->first('client_id', '<span class="help-block">:message</span>') !!}
    </div>

    {{-- Invoice Number --}}
    <div class="form-group col-sm-6 {{ $errors->has('invoice_no') ? 'has-error' : '' }}">
        {!! Form::label('invoice_no', 'Invoice Number:') !!}
        {!! Form::text('invoice_no', old('invoice_no', $invoiceNo ?? null), [
            'class' => 'form-control',
            'placeholder' => 'INV-001'
        ]) !!}
        {!! $errors->first('invoice_no', '<span class="help-block">:message</span>') !!}
    </div>

    {{-- Our Ref --}}
    <div class="form-group col-sm-6 {{ $errors->has('our_ref') ? 'has-error' : '' }}">
        {!! Form::label('our_ref', 'Our Ref:') !!}
        {!! Form::text('our_ref', old('our_ref'), [
            'class' => 'form-control',
            'placeholder' => 'e.g. 0074'
        ]) !!}
        {!! $errors->first('our_ref', '<span class="help-block">:message</span>') !!}
    </div>

    {{-- Invoice Date --}}
    <div class="form-group col-sm-6 {{ $errors->has('invoice_date') ? 'has-error' : '' }}">
        {!! Form::label('invoice_date', 'Invoice Date:') !!}
        {!! Form::date('invoice_date', old('invoice_date', now()->toDateString()), [
            'class' => 'form-control'
        ]) !!}
        {!! $errors->first('invoice_date', '<span class="help-block">:message</span>') !!}
    </div>

    {{-- Items Table --}}
    <div class="row">
        <div class="col-sm-12">
            <h4>Invoice Items</h4>
            <table class="table table-bordered" id="items-table">
                <thead style="background-color: #f5f5f5;">
                    <tr>
                        <th width="5%">Sr.</th>
                        <th>Description of Work</th>
                        <th width="18%">Fees (£)</th>
                        <th width="8%">Action</th>
                    </tr>
                </thead>
                <tbody id="items-body">
                    <tr>
                        <td class="sr-num text-center">1</td>
                        <td>
                            <input type="text"
                                class="form-control desc-input"
                                name="items[0][description]"
                                placeholder="Enter description..."
                                value="{{ old('items.0.description') }}">
                                {{-- required nahi --}}
                        </td>
                        <td>
                            <input type="number"
                                class="form-control fee-input"
                                name="items[0][fees]"
                                placeholder="0.00"
                                step="0.01"
                                min="0"
                                value="{{ old('items.0.fees') }}">
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm btn-remove">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <button type="button" class="btn btn-default btn-sm" id="btn-add-row">
                <i class="fa fa-plus"></i> Add Item
            </button>
        </div>
    </div>

    {{-- Totals --}}
    <div class="row" style="margin-top: 20px;">
        <div class="col-sm-4 col-sm-offset-8">
            <table class="table table-bordered">
                <tr>
                    <td><strong>Subtotal</strong></td>
                    <td id="subtotal-display" class="text-right">£0.00</td>
                </tr>
                <tr>
                    <td><strong>VAT (£)</strong></td>
                    <td>
                        <input type="number"
                               name="vat"
                               id="vat"
                               class="form-control"
                               value="{{ old('vat', '0.00') }}"
                               step="0.01"
                               min="0"
                               placeholder="0.00">
                    </td>
                </tr>
                <tr class="active">
                    <td><strong>Total Due</strong></td>
                    <td class="text-right">
                        <strong id="total-display">£0.00</strong>
                        <input type="hidden" name="total_due" id="total_due">
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Buttons --}}
    <div class="row">
        <div class="col-sm-12">
            {!! Form::submit('Save Invoice', ['class' => 'btn btn-primary']) !!}
            <a href="{{ route('invoices.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>
    $(document).ready(function () {

        let rowCount = 1;

        // ----- Total Calculate -----
        function calculateTotal() {
            let subtotal = 0;

            $('.fee-input').each(function () {
                subtotal += parseFloat($(this).val()) || 0;
            });

            // VAT 20%
            const vat = (20 / 100) * subtotal;

            // Total
            const total = subtotal + vat;

            // Set VAT input value
            $('#vat').val(vat.toFixed(2));

            // Display values
            $('#subtotal-display').text('£' + subtotal.toFixed(2));
            $('#total-display').text('£' + total.toFixed(2));

            // Hidden input
            $('#total_due').val(total.toFixed(2));
        }

        // ----- Serial Numbers Update -----
        function updateSerialNumbers() {
            $('#items-body tr').each(function (i) {
                $(this).find('.sr-num').text(i + 1);
                // Input names reindex
                $(this).find('input').each(function () {
                    const name = $(this).attr('name');
                    if (name) {
                        $(this).attr('name', name.replace(/items\[\d+\]/, 'items[' + i + ']'));
                    }
                });
            });
        }

        // ----- Add Row -----
        // Add row - required bilkul nahi
        $('#btn-add-row').on('click', function () {
            const row = `
                <tr>
                    <td class="sr-num text-center">${rowCount + 1}</td>
                    <td>
                        <input type="text"
                            class="form-control desc-input"
                            name="items[${rowCount}][description]"
                            placeholder="Enter description...">
                    </td>
                    <td>
                        <input type="number"
                            class="form-control fee-input"
                            name="items[${rowCount}][fees]"
                            placeholder="0.00"
                            step="0.01"
                            min="0">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm btn-remove">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>`;
            $('#items-body').append(row);
            rowCount++;
            calculateTotal();
        });

        // ----- Remove Row -----
        $(document).on('click', '.btn-remove', function () {
            if ($('#items-body tr').length <= 1) {
                alert('Kam az kam ek item zaroori hai!');
                return;
            }
            $(this).closest('tr').fadeOut(150, function () {
                $(this).remove();
                updateSerialNumbers();
                calculateTotal();
            });
        });

        // ----- Auto Calculate on Input -----
        $(document).on('input', '.fee-input, #vat', function () {
            calculateTotal();
        });

        // ----- Page load par calculate -----
        calculateTotal();

    });
    </script>
