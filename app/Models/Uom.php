<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Uom extends Model
{
    // Menentukan nama tabel dan primary key
    protected $table = 'm_uom';
    protected $primaryKey = 'id_uom';

    // Fungsi untuk mendapatkan semua material yang berhubungan dengan UOM ini
    public function getMaterialsByUom($id_uom)
    {
        return DB::table('m_material')
            ->where('id_uom', $id_uom)
            ->get();
    }
}
