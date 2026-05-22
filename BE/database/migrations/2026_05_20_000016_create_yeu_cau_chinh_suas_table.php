<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration tạo bảng yeu_cau_chinh_suas.
 *
 * LƯU Ý: Bảng này được giữ lại trong database nhưng KHÔNG được sử dụng
 * trong logic hệ thống. Hệ thống Cây Gia Phả không có cơ chế
 * "gửi yêu cầu → chờ admin duyệt".
 *
 * Người dùng có quyền thì thao tác trực tiếp.
 *
 * @deprecated Không sử dụng trong hệ thống.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('yeu_cau_chinh_suas', function (Blueprint $table) {
            $table->id();
            $table->integer('id_nguoi_gui')->nullable();
            $table->integer('id_thanh_vien_gia_pha')->nullable();
            $table->string('loai_yeu_cau')->nullable();
            $table->json('du_lieu_cu')->nullable();
            $table->json('du_lieu_moi')->nullable();
            $table->string('trang_thai')->default('cho_duyet');
            $table->integer('id_nguoi_duyet')->nullable();
            $table->timestamp('thoi_gian_duyet')->nullable();
            $table->text('ly_do')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('yeu_cau_chinh_suas');
    }
};
