<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hon_nhans', function (Blueprint $table) {
            $table->id();
            $table->integer('id_chong')->nullable();
            $table->integer('id_vo')->nullable();
            $table->date('ngay_ket_hon')->nullable();
            $table->date('ngay_ly_hon')->nullable();
            $table->enum('trang_thai', ['dang_ket_hon', 'da_ly_hon', 'goa_vo_chong'])->default('dang_ket_hon');
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hon_nhans');
    }
};
