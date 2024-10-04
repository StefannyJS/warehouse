<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCellsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cells', function (Blueprint $table) {
            $table->bigIncrements('id_cell');
            $table->unsignedBigInteger('id_storage');
            $table->string('code', 20);
            $table->string('description', 150);
            $table->string('created_by', 50);
            $table->timestamps();
            $table->string('updated_by', 50)->nullable();

            $table->foreign('id_storage')->references('id_storage')->on('m_storage')->onDelete('cascade');
        });
    }

      /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('cells');
    }
};
