<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Department
{
    protected $table = 'm_department';
    protected $primaryKey = 'id_department';

    public function all()
    {
        return DB::table($this->table)->get();
    }

    public function find($id)
    {
        return DB::table($this->table)->where($this->primaryKey, $id)->first();
    }

    public function create(array $data)
    {
        return DB::table($this->table)->insert($data);
    }

    public function update($id, array $data)
    {
        return DB::table($this->table)
            ->where($this->primaryKey, $id)
            ->update($data);
    }

    public function delete($id)
    {
        return DB::table($this->table)
            ->where($this->primaryKey, $id)
            ->delete();
    }
}
