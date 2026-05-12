<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tai_lieus', function (Blueprint $table) {
            $table->id();
            $table->string("tieu_de");
            $table->string("file_path");
            $table->text("mo_ta")->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tai_lieus');
    }
};
