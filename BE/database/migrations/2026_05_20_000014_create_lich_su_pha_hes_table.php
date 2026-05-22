<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lich_su_pha_hes', function (Blueprint $table) {
            $table->id();
            $table->integer('id_pha_he');
            $table->string('tieu_de');
            $table->longText('noi_dung')->nullable();
            $table->date('moc_thoi_gian')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lich_su_pha_hes');
    }
};
