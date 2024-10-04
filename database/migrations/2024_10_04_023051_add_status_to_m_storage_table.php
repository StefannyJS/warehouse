<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToMStorageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_storage', function (Blueprint $table) {
            // Menambahkan kolom status dengan default 1 (aktif)
            $table->integer('status')->default(1)->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_storage', function (Blueprint $table) {
            // Menghapus kolom status
            $table->dropColumn('status');
        });
    }
}