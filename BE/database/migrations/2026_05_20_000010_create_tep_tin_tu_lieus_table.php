<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tep_tin_tu_lieus', function (Blueprint $table) {
            $table->id();
            $table->integer('id_thanh_vien_gia_pha')->nullable();
            $table->integer('id_nhanh_ho')->nullable();
            $table->integer('id_nguoi_tai_len')->nullable();
            $table->string('ten_tep');
            $table->string('duong_dan_tep');
            $table->enum('loai_tep', ['anh', 'pdf', 'word', 'video', 'khac'])->default('khac');
            $table->text('mo_ta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tep_tin_tu_lieus');
    }
};
