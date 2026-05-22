<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('thanh_vien_gia_phas', function (Blueprint $table) {
            $table->id();
            $table->integer('id_nhanh_ho')->nullable();
            $table->string('ho_ten');
            $table->string('ten_khac')->nullable();
            $table->enum('gioi_tinh', ['nam', 'nu', 'khac']);
            $table->date('ngay_sinh')->nullable();
            $table->date('ngay_mat')->nullable();
            $table->boolean('con_song')->default(true);
            $table->string('noi_sinh')->nullable();
            $table->string('que_quan')->nullable();
            $table->string('dia_chi_hien_tai')->nullable();
            $table->string('so_dien_thoai')->nullable();
            $table->string('anh_dai_dien')->nullable();
            $table->integer('doi_thu')->nullable();
            $table->integer('id_cha')->nullable();
            $table->integer('id_me')->nullable();
            $table->longText('tieu_su')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->integer('id_nguoi_tao')->nullable();
            $table->integer('id_nguoi_cap_nhat')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thanh_vien_gia_phas');
    }
};
