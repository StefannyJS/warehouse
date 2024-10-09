<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUniqueConstraintFromCodeInCellsTable extends Migration
{
    public function up()
    {
        Schema::table('cells', function (Blueprint $table) {
            //$table->dropUnique('unique_code_in_storage'); // Hapus unique constraint dengan nama yang benar
        });
    }

    public function down()
    {
        Schema::table('cells', function (Blueprint $table) {
            //$table->unique('code', 'unique_code_in_storage'); // Tambahkan kembali unique constraint pada kolom 'code'
        });
    }
}
