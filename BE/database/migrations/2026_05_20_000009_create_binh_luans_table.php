<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('binh_luans', function (Blueprint $table) {
            $table->id();
            $table->integer('id_nguoi_dung');
            $table->integer('id_bai_viet');
            $table->text('noi_dung');
            $table->enum('trang_thai', ['hien_thi', 'an'])->default('hien_thi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('binh_luans');
    }
};
