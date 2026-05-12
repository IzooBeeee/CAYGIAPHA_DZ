<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vo_chongs', function (Blueprint $table) {
            $table->id();
            $table->foreignId("chong_id")->constrained("thanh_viens")->cascadeOnDelete();
            $table->foreignId("vo_id")->constrained("thanh_viens")->cascadeOnDelete();
            $table->date("ngay_cuoi")->nullable();
            $table->enum("trang_thai", ["Đang sống", "Ly hôn", "Qua đời"])->default("Đang sống");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vo_chongs');
    }
};
