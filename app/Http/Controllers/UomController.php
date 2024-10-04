<?php

namespace App\Http\Controllers;

use App\Models\Uom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class UomController extends Controller
{
    public function index()
    {
    return view('uoms.index');
    }

    public function data(Request $request)
    {
        $uoms = DB::table('m_uom')
            ->select([
                'm_uom.id_uom',
                'm_uom.description',
                'm_uom.status',
                'm_uom.created_by',
                'm_uom.updated_by'
            ]);

            return DataTables::of($uoms)
            ->addIndexColumn()
            ->make(true);
    }

    public function create()
    {
        return view('uoms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|max:150|unique:m_uom,description',
        ]);
 
        DB::table('m_uom')->insert([
            'description' => $request->description,
            'status' => 1, // Default aktif
            'created_by' => Auth::user()->name,
            'created_at' => now(),
        ]);

        return redirect()->route('uoms.index')->with('success', 'UOM created successfully.');
    }

    public function edit($id)
{
    $uom = DB::table('m_uom')->where('id_uom', $id)->first();
    if (!$uom) {
        return redirect()->back()->withErrors(['error' => 'UOM not found']);
    }    

    return view('uoms.edit', compact('uom'));
}


public function update(Request $request, $id)
{
    $request->validate([
        'description' => 'required|string|max:150|unique:m_uom,description,' . $id . ',id_uom',
        'status' => 'required|boolean',
    ]);

    $uom = Uom::find($id);
    $uom->description = $request->description;
    $uom->status = $request->status;
    $uom->save();

    return redirect()->route('uoms.index')->with('success', 'UOM updated succesfully.');
}

    public function destroy($id_uom)
    {
        $update = DB::table('m_uom')->where('id_uom', $id_uom)->update([
            'status' => 0,
            'updated_by' => Auth::user()->name,
            'updated_at' => now(),
        ]);

        if ($update) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false], 500);
        }
    }
}
