<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTStockInTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_stock_in', function (Blueprint $table) {
            $table->id('id_stock_in'); // Primary key untuk tabel ini
            $table->string('stock_in_no', 100)->unique(); // Kolom untuk Stock In No yang auto-generate
            $table->foreignId('id_material')->constrained('m_material', 'id_material')->onDelete('cascade'); // Foreign key untuk id_material
            $table->foreignId('id_uom')->constrained('m_uom', 'id_uom')->onDelete('cascade'); // Foreign key untuk id_uom
            $table->foreignId('id_storage')->constrained('m_storage', 'id_storage')->onDelete('cascade'); // Foreign key untuk id_storage
            $table->foreignId('id_cell')->constrained('cells', 'id_cell')->onDelete('cascade'); // Foreign key untuk id_cell
            $table->foreignId('id_department')->constrained('m_department', 'id_department')->onDelete('cascade'); // Foreign key untuk id_department
            $table->date('stock_in_date'); // Tanggal stock in
            $table->integer('stock_in_qty'); // Jumlah stock in
            $table->string('stock_in_remark')->nullable(); // Keterangan stock in
            $table->integer('status'); // Status
            $table->string('created_by', 50); // Siapa yang membuat
            $table->timestamps(); // Kolom created_at dan updated_at
            $table->string('updated_by', 50)->nullable(); // Siapa yang mengupdate
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_stock_in'); // Menghapus tabel t_stock_in
    }
}
