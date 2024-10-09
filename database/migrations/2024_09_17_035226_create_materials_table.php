<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialsTable extends Migration
{
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id('id_material');
            $table->string('code', 20)->unique();
            $table->string('description', 150);
            $table->string('unit_of_measure', 10);
            $table->integer('status')->default(1); // 1 = Active, 0 = Deleted
            $table->string('created_by', 50);
            $table->timestamps(0);
            $table->string('updated_by', 50)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('materials');
    }
}
