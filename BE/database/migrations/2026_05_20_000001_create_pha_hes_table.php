<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pha_hes', function (Blueprint $table) {
            $table->id();
            $table->string('ten_pha_he');
            $table->text('mo_ta')->nullable();
            $table->integer('id_nguoi_sang_lap')->nullable();
            $table->integer('doi_hien_tai')->nullable();
            $table->enum('trang_thai', ['hoat_dong', 'an'])->default('hoat_dong');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pha_hes');
    }
};
