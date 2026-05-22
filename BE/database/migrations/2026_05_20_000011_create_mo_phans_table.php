<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mo_phans', function (Blueprint $table) {
            $table->id();
            $table->integer('id_thanh_vien_gia_pha');
            $table->string('dia_chi_mo')->nullable();
            $table->decimal('toa_do_lat', 10, 7)->nullable();
            $table->decimal('toa_do_lng', 10, 7)->nullable();
            $table->date('ngay_an_tang')->nullable();
            $table->string('hinh_anh')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mo_phans');
    }
};
