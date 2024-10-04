<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('m_material', function (Blueprint $table) {
        $table->dropColumn('unit_of_measure');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('m_material', function (Blueprint $table) {
        $table->string('unit_of_measure', 10)->nullable();
    });
}
};
