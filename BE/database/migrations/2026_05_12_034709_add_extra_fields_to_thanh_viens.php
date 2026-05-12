<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('thanh_viens', function (Blueprint $table) {
            $table->string('loai_quan_he')->default('Chính')->after('doi_thu');
            $table->unsignedBigInteger('spouse_of_id')->nullable()->after('loai_quan_he');
        });
    }

    public function down()
    {
        Schema::table('thanh_viens', function (Blueprint $table) {
            $table->dropColumn(['loai_quan_he', 'spouse_of_id']);
        });
    }
};
