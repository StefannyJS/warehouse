<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class DepartmentController extends Controller
{
    public function index()
    {
        return view('departments.index');
    }

public function getData()
{
    $departments = DB::table('m_department')->select(['id_department', 'code', 'description', 'status']);

    return DataTables::of($departments)
        ->addIndexColumn()
        ->addColumn('status', function ($row) {
            return $row->status == 1
                ? '<div class="flex justify-center items-center"><i class="fas fa-check-circle text-green-500"></i></div>'
                : '<div class="flex justify-center items-center"><i class="fas fa-times-circle text-red-500"></i></div>';
        })
        ->addColumn('actions', function ($row) {
            return '
                <a href="/departments/' . $row->id_department . '/edit" class="btn btn-edit flex items-center space-x-2">
                    <i class="fas fa-edit"></i>
                    <span>Edit</span>
                </a>
                <button onclick="disableRecord(\'/departments/' . $row->id_department . '\', \'#departments-table\')" class="btn btn-delete flex items-center space-x-2">
                    <i class="fas fa-trash-alt"></i>
                    <span>Delete</span>
                </button>';
        })
        ->rawColumns(['status', 'actions']) // Ensure HTML is not escaped
        ->make(true);
}


    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:m_department',
            'description' => 'required|string|max:100|unique:m_department',
            'status' => 'required|integer',
        ]);

        DB::table('m_department')->insert([
            'code' => $request->code,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('departments.index')->with('success', 'Department created successfully.');
    }

public function show($id)
{
    $department = DB::table('m_department')->where('id_department', $id)->first();
    return view('departments.show', compact('department'));
}

public function edit($id)
{
    $department = DB::table('m_department')->where('id_department', $id)->first();
    return view('departments.edit', compact('department'));
}
    
public function update(Request $request, $id)
{
    $request->validate([
        'code' => 'required|max:20|unique:m_department,code,' . $id . ',id_department',
        'description' => 'required|string|max:100|unique:m_department,description,' . $id . ',id_department',
        'status' => 'required|integer|in:0,1',
    ]);

    try {
        DB::table('m_department')->where('id_department', $id)->update([
            'code' => $request->code,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => 'An error occurred while updating department: ' . $e->getMessage()]);
    }
}

public function destroy($id)
{
    try {
        $department = DB::table('m_department')->where('id_department', $id)->first();

        if (!$department) {
            return response()->json(['error' => 'Department not found.'], 404);
        }

        DB::table('m_department')->where('id_department', $id)->update(['status' => 0]);

        return response()->json(['success' => 'Department has been successfully deactivated.']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to deactivate department: ' . $e->getMessage()], 500);
    }
}
}
