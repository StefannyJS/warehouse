<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialController extends Controller
{
    public function index()
    {
        return view('materials.index');
    }

    public function data(Request $request)
    {
        $materials = DB::table('m_material')
            ->leftJoin('m_uom', 'm_material.id_uom', '=', 'm_uom.id_uom')
            ->select([
                'm_material.id_material',
                'm_material.code',
                'm_material.description',
                'm_uom.description AS uom_description',
                'm_material.status',
                'm_material.created_by',
                'm_material.updated_by',
            ]);

            return DataTables::of($materials)
            ->filterColumn('uom_description', function($query, $keyword) {
                $query->whereRaw('LOWER(m_uom.description) LIKE ?', ["%{$keyword}%"]);
            })
            ->rawColumns(['actions', 'status'])
            ->addIndexColumn()
            ->make(true);
    }
    

    public function create()
    {
        try {
            $uoms = DB::table('m_uom')->where('status', 1)->get();
            return view('materials.create', compact('uoms'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred while fetching UOM data: ' . $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:m_material|max:20',
            'description' => 'required|max:150',
            'unit_of_measure' => 'required|exists:m_uom,id_uom',
            'status' => 'required|integer|in:0,1',
        ]);

        try {
            DB::table('m_material')->insert([
                'code' => $request->code,
                'description' => $request->description,
                'id_uom' => $request->unit_of_measure,
                'status' => $request->status,
                'created_by' => Auth::user()->name,
                'updated_by' => Auth::user()->name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('materials.index')->with('success', 'Material created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred while creating material: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        try {
            $material = DB::table('m_material')->where('id_material', $id)->first();

            if (!$material) {
                return redirect()->route('materials.index')->withErrors(['error' => 'Material not found']);
            }

            $uoms = DB::table('m_uom')->where('status', 1)->get();

            return view('materials.edit', [
                'material' => $material,
                'uoms' => $uoms,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred while fetching data: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|max:20|unique:m_material,code,' . $id . ',id_material',
            'description' => 'required|max:150',
            'unit_of_measure' => 'required|exists:m_uom,id_uom',
            'status' => 'required|integer|in:0,1',
        ]);

        try {
            DB::table('m_material')->where('id_material', $id)->update([
                'code' => $request->code,
                'description' => $request->description,
                'id_uom' => $request->unit_of_measure,
                'status' => $request->status,
                'updated_by' => Auth::user()->name,
                'updated_at' => now(),
            ]);

            return redirect()->route('materials.index')->with('success', 'Material updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred while updating material: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
{
    try {
        // Cari material berdasarkan id
        $material = DB::table('m_material')->where('id_material', $id)->first();

        if (!$material) {
            return response()->json(['error' => 'Material tidak ditemukan.'], 404);
        }

        DB::table('m_material')
            ->where('id_material', $id)
            ->update(['status' => 0]);

        return response()->json(['success' => 'Material has been successfully deactivated.']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to deactivate material: ' . $e->getMessage()], 500);
    }
}

}
