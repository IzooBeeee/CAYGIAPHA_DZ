<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('thong_baos', function (Blueprint $table) {
            $table->id();
            $table->string("tieu_de");
            $table->text("noi_dung")->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('thong_baos');
    }
};
