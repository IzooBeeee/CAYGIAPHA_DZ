<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('nhat_ky_hoat_dongs', function (Blueprint $table) {
            $table->id();
            $table->foreignId("nguoi_dung_id")->constrained("nguoi_dungs")->cascadeOnDelete();
            $table->string("hanh_dong");
            $table->timestamp("thoi_gian")->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nhat_ky_hoat_dongs');
    }
};
