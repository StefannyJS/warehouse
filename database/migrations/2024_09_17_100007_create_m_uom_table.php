<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMUomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_uom', function (Blueprint $table) {
            $table->bigIncrements('id_uom'); // Primary Key with auto increment
            $table->string('description', 150); // Description field
            $table->integer('status')->default(1); // Status field
            $table->string('created_by', 50); // Created by field
            $table->timestamps(); // created_at and updated_at fields (auto-handled by Laravel)
            $table->string('updated_by', 50)->nullable(); // Updated by field
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_uom');
    }
}
