<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameMaterialsTableToMMaterial extends Migration
{
    public function up()
    {
        Schema::rename('materials', 'm_material');
    }

    public function down()
    {
        Schema::rename('m_material', 'materials');
    }
}
