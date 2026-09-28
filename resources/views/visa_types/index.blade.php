@extends('layouts.app')
@section('title', 'Visa Types')
@section('content')
<section class="content-header"><h1>Visa Types <small>Manage client dropdown options</small></h1></section>
<div class="content">
    <div class="box box-primary"><div class="box-body">
        <p>Add the visa types your team uses. These appear in Client Add, Client Edit and template forms.</p>
        @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('visa-types.store') }}" class="row">
            @csrf
            <div class="form-group col-sm-8">
                <label for="visa-type-name">Visa type name</label>
                <input id="visa-type-name" name="name" value="{{ old('name') }}" class="form-control" maxlength="100" placeholder="e.g. Family Visa" required>
            </div>
            <div class="col-sm-4"><button class="btn btn-primary" style="margin-top:25px" type="submit"><i class="fa fa-plus" aria-hidden="true"></i> Add Visa Type</button></div>
        </form>
        <div class="table-responsive"><table class="table table-striped">
            <thead><tr><th>Visa type</th><th class="text-right">Action</th></tr></thead>
            <tbody>
            @forelse ($visaTypes as $visaType)
                <tr><td>{{ $visaType->name }}</td><td class="text-right">
                    <form method="POST" action="{{ route('visa-types.destroy', $visaType) }}">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" aria-label="Remove {{ $visaType->name }}"><i class="fa fa-trash" aria-hidden="true"></i> Remove</button>
                    </form>
                </td></tr>
            @empty
                <tr><td colspan="2">No visa types yet. Add one above to make it available for new clients.</td></tr>
            @endforelse
            </tbody>
        </table></div>
        <p class="text-muted">Removing a type hides it from new selections. Existing clients and templates retain their saved type.</p>
    </div></div>
</div>
@endsection
