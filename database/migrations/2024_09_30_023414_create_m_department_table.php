<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMDepartmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_department', function (Blueprint $table) {
            $table->id('id_department'); // Kolom ID dengan nama id_department
            $table->string('code', 20)->unique();
            $table->string('description', 100); // Nama departemen
            $table->integer('status')->default(1); // Status (1 untuk aktif, 0 untuk tidak aktif)
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_department');
    }
}

