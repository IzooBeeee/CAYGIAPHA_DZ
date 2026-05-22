<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nguoi_dungs', function (Blueprint $table) {
            $table->id();
            $table->string('ho_ten');
            $table->string('email')->unique();
            $table->string('mat_khau');
            $table->enum('vai_tro', ['thanh_vien', 'truong_nhanh', 'quan_tri_vien'])->default('thanh_vien');
            $table->enum('trang_thai', ['hoat_dong', 'bi_khoa'])->default('hoat_dong');
            $table->integer('id_thanh_vien_gia_pha')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nguoi_dungs');
    }
};
