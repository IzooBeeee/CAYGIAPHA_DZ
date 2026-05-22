<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nhat_ky_hoat_dongs', function (Blueprint $table) {
            $table->id();
            $table->integer('id_nguoi_dung')->nullable();
            $table->enum('hanh_dong', ['them', 'sua', 'xoa', 'dang_nhap', 'dang_xuat', 'sua_ca_nhan', 'khoi_phuc']);
            $table->string('ten_bang');
            $table->integer('id_ban_ghi')->nullable();
            $table->json('du_lieu_cu')->nullable();
            $table->json('du_lieu_moi')->nullable();
            $table->ipAddress('dia_chi_ip')->nullable();
            $table->string('trinh_duyet')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nhat_ky_hoat_dongs');
    }
};
