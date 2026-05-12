<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('nha_tho_hos', function (Blueprint $table) {
            $table->id();
            $table->string("ten_nha_tho");
            $table->text("dia_chi")->nullable();
            $table->string("hinh_anh")->nullable();
            $table->text("mo_ta")->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nha_tho_hos');
    }
};
