<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chi_nhanhs', function (Blueprint $table) {
            $table->id();
            $table->string("ten_chi");
            $table->text("mo_ta")->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('chi_nhanhs');
    }
};
