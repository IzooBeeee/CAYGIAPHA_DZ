<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('con_nuois', function (Blueprint $table) {
            $table->id();
            $table->foreignId("cha_me_id")->constrained("thanh_viens")->cascadeOnDelete();
            $table->foreignId("con_id")->constrained("thanh_viens")->cascadeOnDelete();
            $table->text("ghi_chu")->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('con_nuois');
    }
};
