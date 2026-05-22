<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('thong_baos', function (Blueprint $table) {
            $table->id();
            $table->integer('id_nguoi_gui')->nullable();
            $table->integer('id_nguoi_nhan')->nullable();
            $table->integer('id_nhanh_ho')->nullable();
            $table->string('tieu_de');
            $table->text('noi_dung')->nullable();
            $table->enum('loai_thong_bao', ['he_thong', 'su_kien', 'bai_viet', 'khac'])->default('he_thong');
            $table->boolean('da_doc')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thong_baos');
    }
};
