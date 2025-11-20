@section('css')
    @include('layouts.datatables_css')
@endsection

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Name</th>
            <th>Purpose</th>
            <th>Date</th>
            <th>PDF</th>
        </tr>
    </thead>
    <tbody>
   
    @foreach ($letters as $letter)
        <tr>
            <td>{{ $letter->name }}</td>
            <td>{{ $letter->purpose }}</td>
            <td>{{ $letter->date }}</td>
            <td>
                <a href="{{ asset('storage/'.$letter->file_path) }}" target="_blank" class="btn btn-sm btn-success">View PDF</a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@section('scripts')
    @include('layouts.datatables_js')
@endsection
