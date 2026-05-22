<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cay_gia_pha_chia_ses', function (Blueprint $table) {
            $table->id();
            $table->integer('id_nguoi_tao')->nullable();
            $table->integer('id_nhanh_ho')->nullable();
            $table->string('ma_chia_se')->unique();
            $table->enum('pham_vi', ['cong_khai', 'rieng_tu', 'co_mat_khau'])->default('rieng_tu');
            $table->string('mat_khau')->nullable();
            $table->timestamp('ngay_het_han')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cay_gia_pha_chia_ses');
    }
};
