<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nhan_viens', function (Blueprint $table): void {
            $table->id();
            $table->string('email')->unique();
            $table->string('ho_va_ten');
            $table->string('mat_khau');
            $table->string('so_dien_thoai')->nullable();
            $table->string('dia_chi');
            $table->date('ngay_sinh');
            $table->string('avatar')->nullable();
            $table->integer('tinh_trang')->default(1);
            $table->unsignedBigInteger('id_chuc_vu')->nullable();
            $table->boolean('is_master')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nhan_viens');
    }
};
