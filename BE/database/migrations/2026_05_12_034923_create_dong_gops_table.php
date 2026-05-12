<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dong_gops', function (Blueprint $table) {
            $table->id();
            $table->foreignId("nguoi_dung_id")->constrained("nguoi_dungs")->cascadeOnDelete();
            $table->text("noi_dung");
            $table->enum("trang_thai", ["Chờ duyệt", "Đã duyệt"])->default("Chờ duyệt");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dong_gops');
    }
};
