<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('thanh_viens', function (Blueprint $table) {
            $table->id();
            $table->string("ho_ten");
            $table->string("ten_goi")->nullable();
            $table->enum("gioi_tinh", ["Nam", "Nữ", "Khác"]);
            $table->date("ngay_sinh")->nullable();
            $table->date("ngay_mat")->nullable();
            $table->string("noi_sinh")->nullable();
            $table->string("nghe_nghiep")->nullable();
            $table->string("avatar")->nullable();
            $table->integer("doi_thu")->nullable();
            $table->foreignId("chi_nhanh_id")->nullable()->constrained("chi_nhanhs")->nullOnDelete();
            $table->foreignId("cha_id")->nullable()->constrained("thanh_viens")->nullOnDelete();
            $table->foreignId("me_id")->nullable()->constrained("thanh_viens")->nullOnDelete();
            $table->text("ghi_chu")->nullable();
            $table->enum("trang_thai", ["Còn sống", "Đã mất"])->default("Còn sống");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('thanh_viens');
    }
};
