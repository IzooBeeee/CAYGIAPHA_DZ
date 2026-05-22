<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('su_kien_dong_hos', function (Blueprint $table) {
            $table->id();
            $table->integer('id_nhanh_ho')->nullable();
            $table->string('tieu_de');
            $table->text('mo_ta')->nullable();
            $table->dateTime('thoi_gian')->nullable();
            $table->string('dia_diem')->nullable();
            $table->enum('loai_su_kien', ['gio_chap', 'hop_ho', 'dam_cuoi', 'sinh_nhat', 'khac'])->default('khac');
            $table->integer('id_nguoi_tao')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('su_kien_dong_hos');
    }
};
