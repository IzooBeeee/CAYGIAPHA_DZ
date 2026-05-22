<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phan_quyens', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('id_chuc_vu');
            $table->unsignedBigInteger('id_chuc_nang');
            $table->boolean('co_quyen')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phan_quyens');
    }
};
