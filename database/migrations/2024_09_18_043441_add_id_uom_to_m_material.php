<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdUomToMMaterial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_material', function (Blueprint $table) {
            // Menambahkan kolom id_uom
            $table->unsignedBigInteger('id_uom')->nullable()->after('updated_by');

            // Menambahkan foreign key
            $table->foreign('id_uom')->references('id_uom')->on('m_uom')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_material', function (Blueprint $table) {
            // Menghapus foreign key
            $table->dropForeign(['id_uom']);
            
            // Menghapus kolom id_uom
            $table->dropColumn('id_uom');
        });
    }
}
