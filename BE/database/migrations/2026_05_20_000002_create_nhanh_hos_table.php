<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nhanh_hos', function (Blueprint $table) {
            $table->id();
            $table->integer('id_pha_he');
            $table->string('ten_nhanh');
            $table->text('mo_ta')->nullable();
            $table->integer('id_nguoi_goc')->nullable();
            $table->integer('id_truong_nhanh_hien_tai')->nullable();
            $table->integer('id_nguoi_quan_ly')->nullable();
            $table->integer('id_nhanh_cha')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nhanh_hos');
    }
};
