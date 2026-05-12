<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('su_kiens', function (Blueprint $table) {
            $table->id();
            $table->string("tieu_de");
            $table->text("noi_dung")->nullable();
            $table->date("ngay_to_chuc");
            $table->string("dia_diem")->nullable();
            $table->enum("loai", ["Giỗ tổ", "Họp họ", "Cưới hỏi", "Tang lễ"]);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('su_kiens');
    }
};
