<?php

namespace App\Http\Controllers;

use App\Models\Coa;
use Illuminate\Http\Request;

class CoaController extends Controller
{
    public function index()
    {
        $coas = Coa::all();
        return view('coa.index', compact('coas'));
    }

    public function create()
    {
        return view('coa.create');
    }

    public function store(Request $request)
    {
        Coa::create($request->all());
        return redirect('/coa');
    }

    public function edit($id)
    {
        $coa = Coa::findOrFail($id);
        return view('coa.edit', compact('coa'));
    }

    public function update(Request $request, $id)
    {
        $coa = Coa::findOrFail($id);
        $coa->update($request->all());
        return redirect('/coa');
    }

    public function destroy($id)
    {
        Coa::destroy($id);
        return redirect('/coa');
    }
}