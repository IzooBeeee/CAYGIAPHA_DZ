<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bai_viets', function (Blueprint $table) {
            $table->id();
            $table->integer('id_nguoi_dung')->nullable();
            $table->integer('id_nhanh_ho')->nullable();
            $table->string('tieu_de');
            $table->string('duong_dan')->unique();
            $table->longText('noi_dung')->nullable();
            $table->string('anh_dai_dien')->nullable();
            $table->enum('trang_thai', ['ban_nhap', 'cong_khai', 'an'])->default('ban_nhap');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bai_viets');
    }
};
