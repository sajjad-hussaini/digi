<?php
namespace App\Http\Controllers;

use App\VisaType;
use Illuminate\Http\Request;

class VisaTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:update clients');
    }

    public function index()
    {
        return view('visa_types.index', ['visaTypes' => VisaType::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:100', 'unique:visa_types,name']]);
        VisaType::create($data);
        return redirect()->route('visa-types.index')->with('success', 'Visa type added successfully.');
    }

    public function destroy(VisaType $visaType)
    {
        $visaType->delete();
        return redirect()->route('visa-types.index')->with('success', 'Visa type removed. Existing clients and templates keep their saved type.');
    }
}
