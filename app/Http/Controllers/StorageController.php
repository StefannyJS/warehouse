<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class StorageController extends Controller
{
    public function index()
    {
        return view('storages.index');
    }

    public function getData()
    {
        $storages = DB::table('m_storage')->select('id_storage', 'code', 'location', 'description', 'status')->get();

        return DataTables::of($storages)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                return $row->status == 1
                    ? '<div class="flex justify-center items-center"><i class="fas fa-check-circle text-green-500"></i></div>'
                    : '<div class="flex justify-center items-center"><i class="fas fa-times-circle text-red-500"></i></div>';
            })
            ->addColumn('actions', function ($row) {
                return '
                    <a href="/storages/' . $row->id_storage . '/edit" class="btn btn-edit flex items-center space-x-2">
                        <i class="fas fa-edit"></i><span>Edit</span>
                    </a>
                    <button onclick="disableRecord(\'/storages/' . $row->id_storage . '\', \'#storages-table\')" class="btn btn-delete flex items-center space-x-2">
                        <i class="fas fa-trash-alt"></i><span>Delete</span>
                    </button>
                    <a href="/storages/' . $row->id_storage . '/cells" class="btn btn-cells flex items-center space-x-2 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-box-open"></i><span>Cells</span>
                    </a>';
            })
            ->rawColumns(['status', 'actions'])
            ->make(true);
    }

    public function create()
    {
        return view('storages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:m_storage',
            'location' => 'required|string|max:100',
            'description' => 'required|string|max:100',
            'status' => 'required|integer',
        ]);

        DB::table('m_storage')->insert([
            'code' => $request->code,
            'location' => $request->location,
            'description' => $request->description,
            'status' => $request->status,
            'created_by' => Auth::user()->name,
            'updated_by' => Auth::user()->name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('storages.index')->with('success', 'Storage created successfully.');
    }

    public function show(string $id)
    {
        $storage = DB::table('m_storage')->where('id_storage', $id)->first();

        if (!$storage) {
            return redirect()->route('storages.index')->with('error', 'Storage not found.');
        }

        return view('storages.show', ['storage' => $storage]);
    }

    public function edit(string $id)
    {
        $storage = DB::table('m_storage')->where('id_storage', $id)->first();

        if (!$storage) {
            return redirect()->route('storages.index')->with('error', 'Storage not found.');
        }

        return view('storages.edit', ['storage' => $storage]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'code' => 'required|max:20|unique:m_storage,code,' . $id . ',id_storage',
            'location' => 'required|string|max:100',
            'description' => 'required|string|max:100',
            'status' => 'required|integer|in:0,1',
        ]);

        try {
            DB::table('m_storage')->where('id_storage', $id)->update([
                'code' => $request->code,
                'location' => $request->location,
                'description' => $request->description,
                'status' => $request->status,
                'updated_by' => Auth::user()->name,
                'updated_at' => now(),
            ]);

            return redirect()->route('storages.index')->with('success', 'Storage updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred while updating storage: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
    try {
        $storage = DB::table('m_storage')->where('id_storage', $id)->first();

        if (!$storage) {
            return response()->json(['error' => 'Storage not found.'], 404);
        }

        DB::table('m_storage')
            ->where('id_storage', $id)
            ->update(['status' => 0]);

        return response()->json(['success' => 'Storage has been successfully deactivated.']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to deactivate storage: ' . $e->getMessage()], 500);
    }
    }
}
