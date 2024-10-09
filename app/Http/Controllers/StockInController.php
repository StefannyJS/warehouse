<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StockInController extends Controller
{
    public function index()
    {
        // Generate new Stock In No
        $newStockInNo = $this->generateStockInNo();
    
        // Pass the generated Stock In No to the view
        return view('stock_in.index', compact('newStockInNo'));
    }

    public function data(Request $request)
    {
        $stock_in = DB::table('t_stock_in')
            ->join('m_material', 't_stock_in.id_material', '=', 'm_material.id_material')
            ->join('m_uom', 't_stock_in.id_uom', '=', 'm_uom.id_uom')
            ->join('m_department', 't_stock_in.id_department', '=', 'm_department.id_department')
            ->join('m_storage', 't_stock_in.id_storage', '=', 'm_storage.id_storage')
            ->join('cells', 't_stock_in.id_cell', '=', 'cells.id_cell')
            ->select([
                't_stock_in.id_stock_in',
                't_stock_in.stock_in_no',
                'm_material.description as material_description',
                't_stock_in.stock_in_qty',
                'm_uom.description as uom_description',
                'm_department.description as department_description',
                'm_storage.description as storage_description',
                'cells.description as cell_description',
                't_stock_in.created_at',
                't_stock_in.updated_at',
            ]);

        return DataTables::of($stock_in)
            ->addIndexColumn() // Auto number untuk kolom No
            ->addColumn('status', function ($row) {
                return $row->status == 1
                    ? '<div class="flex justify-center items-center"><i class="fas fa-check-circle text-green-500"></i></div>'
                    : '<div class="flex justify-center items-center"><i class="fas fa-times-circle text-red-500"></i></div>';
            })
            ->addColumn('actions', function ($row) {
                return '
                    <a href="javascript:void(0)" data-id="'.$row->id_stock_in.'" class="btn btn-edit">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="javascript:void(0)" class="btn btn-delete" data-id="'.$row->id_stock_in.'">
                        <i class="fas fa-trash-alt"></i> Delete
                    </a>
                ';
            })
            ->rawColumns(['actions', 'status'])
            ->make(true);
    }

    public function create()
    {
        // Generate Stock In No
        $newStockInNo = $this->generateStockInNo();
        
        // Ambil daftar material yang statusnya 1 (aktif) untuk dropdown
        $materials = DB::table('m_material')->where('status', 1)->get();

        return view('stock_in.create', compact('newStockInNo', 'materials'));
    }

    // Fungsi untuk auto-generate Stock In No berdasarkan logika database
    public function generateStockInNo()
    {
        // Ambil tanggal hari ini dalam format YYMMDD
        $today = Carbon::now()->format('ymd');

        // Cari stock_in_no terakhir yang dimulai dengan tanggal hari ini
        $latestStockIn = DB::table('t_stock_in')
                            ->where('stock_in_no', 'like', $today . '%')
                            ->orderBy('stock_in_no', 'desc')
                            ->first();

        if ($latestStockIn) {
            // Ambil nomor urut dari record terakhir dan tambah 1
            $lastSequentialNumber = intval(substr($latestStockIn->stock_in_no, -2));
            $newSequentialNumber = str_pad($lastSequentialNumber + 1, 2, '0', STR_PAD_LEFT);
        } else {
            // Jika belum ada entri hari ini, mulai dari 01
            $newSequentialNumber = '01';
        }

        // Gabungkan tanggal dengan nomor urut baru
        $newStockInNo = $today . $newSequentialNumber;

        return $newStockInNo;
    }

    public function generateStockInNoApi()
    {
        $newStockInNo = $this->generateStockInNo();
        return response()->json(['newStockInNo' => $newStockInNo]);
    }

    // Fungsi untuk menyimpan data Stock In baru ke database
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'stock_in_no' => 'required|string',
            'id_material' => 'required|integer',
            'stock_in_qty' => 'required|integer',
        ]);

        // Simpan ke database
        DB::table('t_stock_in')->insert([
            'stock_in_no' => $validatedData['stock_in_no'],
            'id_material' => $validatedData['id_material'],
            'stock_in_qty' => $validatedData['stock_in_qty'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('stock-in.index')->with('success', 'Stock In entry has been created successfully.');
    }
}
