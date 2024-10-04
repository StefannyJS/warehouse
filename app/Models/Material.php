<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Material extends Model
{
    use HasFactory;

    protected $table = 'm_material';

    protected $primaryKey = 'id_material';

    public function uom()
    {
        return $this->belongsTo(Uom::class, 'id_uom', 'id_uom');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Method untuk mendapatkan data material beserta UOM dan nama pengguna menggunakan SQL native.
     *
     * @param string $parameter1 Nilai parameter untuk field 'code' di tabel 'm_material'
     * @param int $parameter2 Nilai parameter untuk field 'status' di tabel 'm_material'
     * @return array Hasil query berupa daftar material dengan deskripsi UOM dan nama pengguna
     */
    public static function getMaterialsWithUom($parameter1, $parameter2)
    {

        $query = "
            SELECT m_material.*, m_uom.description AS uom_description
            FROM m_material
            LEFT JOIN m_uom ON m_material.id_uom = m_uom.id_uom
            WHERE m_material.code = :parameter1
            AND m_material.status = :parameter2
        ";

        $results = DB::select($query, [
            'parameter1' => $parameter1,
            'parameter2' => $parameter2,
        ]);

        return $results;
    }
}
