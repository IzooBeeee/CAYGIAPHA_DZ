<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hinh_anhs', function (Blueprint $table) {
            $table->id();
            $table->foreignId("thanh_vien_id")->nullable()->constrained("thanh_viens")->cascadeOnDelete();
            $table->string("duong_dan");
            $table->text("mo_ta")->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hinh_anhs');
    }
};
