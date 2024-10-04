<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; // Import the Model class
use Illuminate\Support\Facades\DB;

class Cell extends Model
{
    // Specify the table name if it differs from the plural form of the model name
    protected $table = 'cells'; 
    protected $primaryKey = 'id_cell'; // Specify the primary key if it is different from 'id'

    // Mengambil semua sel berdasarkan id_storage
    public static function getAllCells($storageId)
    {
        return DB::select('SELECT * FROM cells WHERE id_storage = ?', [$storageId]);
    }

    // Menyimpan sel baru
    public static function createCell($data)
    {
        return DB::insert('INSERT INTO cells (code, description, id_storage, status) VALUES (?, ?, ?, ?)', [
            $data['code'],
            $data['description'],
            $data['id_storage'],
            $data['status'], // Menambahkan status
        ]);
    }

    // Mengambil data sel berdasarkan id
    public static function getCellById($id)
    {
        return DB::select('SELECT * FROM cells WHERE id_cell = ?', [$id]);
    }

    // Mengupdate sel berdasarkan id
    public static function updateCell($id, $data)
    {
        return DB::update('UPDATE cells SET code = ?, description = ?, status = ? WHERE id_cell = ?', [
            $data['code'],
            $data['description'],
            $data['status'], // Menambahkan status
            $id
        ]);
    }

    // Menghapus sel berdasarkan id
    public static function deleteCell($id)
    {
        return DB::update('UPDATE cells SET status = ? WHERE id_cell = ?', [0, $id]); // Mengubah status menjadi 0
    }
}
