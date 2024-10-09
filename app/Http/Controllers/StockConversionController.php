<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StockConversionController extends Controller
{
    public function index()
    {
        // Logic untuk menampilkan view stok masuk (Stock In)
        return view('stock_conversion.index');
    }
}
