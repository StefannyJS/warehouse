<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class CellController extends Controller
{
    public function index($storage_id)
    {
        $storage = DB::table('m_storage')->where('id_storage', $storage_id)->first();

        if (!$storage) {
            return redirect()->route('storages.index')->withErrors('Storage not found.');
        }

        return view('cells.index', compact('storage'));
    }

    public function getData($storage_id)
    {
        $cells = DB::table('cells')->where('id_storage', $storage_id)->get();

        return DataTables::of($cells)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                return $row->status == 1
                    ? '<div class="flex justify-center items-center"><i class="fas fa-check-circle text-green-500"></i></div>'
                    : '<div class="flex justify-center items-center"><i class="fas fa-times-circle text-red-500"></i></div>';
            })
            ->addColumn('actions', function ($row) {
                return '
                    <a href="javascript:void(0)" data-id="'.$row->id_cell.'" class="btn btn-edit">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="javascript:void(0)" class="btn btn-delete" data-id="'.$row->id_cell.'">
                        <i class="fas fa-trash-alt"></i> Delete
                    </a>
                ';
            })
            ->rawColumns(['status', 'actions'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:20',
            'description' => 'required|string|max:100',
            'status' => 'required|integer',
            'id_storage' => 'required|exists:m_storage,id_storage'
        ]);

        $data = [
            'code' => $request->code,
            'description' => $request->description,
            'status' => $request->status,
            'id_storage' => $request->id_storage,
            'created_by' => Auth::user()->name,
            'created_at' => now(),
        ];

        if ($request->id_cell) {
            // Update cell
            DB::table('cells')
                ->where('id_cell', $request->id_cell)
                ->update($data);
        } else {
            // Create new cell
            DB::table('cells')->insert($data);
        }

        return response()->json(['success' => 'Cell saved successfully.']);
    }

    public function edit($storageId, $cellId)
    {
        $cell = DB::table('cells')
            ->where('id_cell', $cellId)
            ->where('id_storage', $storageId)
            ->first();

        if (!$cell) {
            return response()->json(['error' => 'Cell not found.'], 404);
        }

        return response()->json($cell);
    }

    public function update(Request $request, $storageId, $id)
    {
        $request->validate([
            'code' => 'required|string|max:20',
            'description' => 'required|string|max:100',
            'status' => 'required|integer|in:0,1',
        ]);

        $data = [
            'code' => $request->code,
            'description' => $request->description,
            'status' => $request->status,
            'updated_by' => Auth::user()->name,
            'updated_at' => now(),
        ];

        DB::table('cells')
            ->where('id_cell', $id)
            ->where('id_storage', $storageId)
            ->update($data);

        return response()->json(['success' => 'Cell updated successfully.']);
    }

    public function destroy($storage_id, $id)
{
    try {
        // Tambahkan storage_id ke query jika diperlukan
        $cell = DB::table('cells')->where('id_cell', $id)->where('id_storage', $storage_id)->first();

        if (!$cell) {
            return response()->json(['error' => 'Cell not found.'], 404);
        }

        DB::table('cells')->where('id_cell', $id)->where('id_storage', $storage_id)->update(['status' => 0]);

        return response()->json(['success' => 'Cell has been successfully deactivated.']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to deactivate cell: ' . $e->getMessage()], 500);
    }
}
}
