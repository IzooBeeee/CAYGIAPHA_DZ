<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tham_gia_su_kiens', function (Blueprint $table) {
            $table->id();
            $table->foreignId("su_kien_id")->constrained("su_kiens")->cascadeOnDelete();
            $table->foreignId("thanh_vien_id")->constrained("thanh_viens")->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tham_gia_su_kiens');
    }
};
