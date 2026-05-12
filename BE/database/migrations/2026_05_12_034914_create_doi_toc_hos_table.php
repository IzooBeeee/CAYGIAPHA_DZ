<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('doi_toc_hos', function (Blueprint $table) {
            $table->id();
            $table->integer("so_doi");
            $table->string("ten_doi");
            $table->text("mo_ta")->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('doi_toc_hos');
    }
};
