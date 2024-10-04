<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMStorageTable extends Migration
{
    public function up()
    {
        Schema::create('m_storage', function (Blueprint $table) {
            $table->bigIncrements('id_storage');
            $table->string('code', 20)->unique();
            $table->string('location', 150);
            $table->string('description', 150);
            $table->integer('status')->default(1); // Status (1 untuk aktif, 0 untuk tidak aktif)
            $table->string('created_by', 50);
            $table->timestamps();
            $table->string('updated_by', 50)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_storage');
    }
}