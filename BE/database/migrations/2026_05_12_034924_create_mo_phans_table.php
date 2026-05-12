<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mo_phans', function (Blueprint $table) {
            $table->id();
            $table->foreignId("thanh_vien_id")->constrained("thanh_viens")->cascadeOnDelete();
            $table->string("ten_mo")->nullable();
            $table->text("dia_chi")->nullable();
            $table->string("kinh_do")->nullable();
            $table->string("vi_do")->nullable();
            $table->string("hinh_anh")->nullable();
            $table->text("ghi_chu")->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mo_phans');
    }
};
