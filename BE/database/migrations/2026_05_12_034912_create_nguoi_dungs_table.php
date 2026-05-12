<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('nguoi_dungs', function (Blueprint $table) {
            $table->id();
            $table->string("ho_ten");
            $table->string("email")->unique();
            $table->string("mat_khau");
            $table->string("so_dien_thoai")->nullable();
            $table->string("avatar")->nullable();
            $table->enum("vai_tro", ["Admin", "Thành viên"])->default("Thành viên");
            $table->enum("trang_thai", ["Hoạt động", "Khóa"])->default("Hoạt động");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nguoi_dungs');
    }
};
