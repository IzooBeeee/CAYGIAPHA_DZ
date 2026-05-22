<?php

use App\Http\Controllers\Api\GiaPha\AuthController;
use App\Http\Controllers\Api\GiaPha\BaiVietController;
use App\Http\Controllers\Api\GiaPha\BinhLuanController;
use App\Http\Controllers\Api\GiaPha\CayGiaPhaChiaSeController;
use App\Http\Controllers\Api\GiaPha\CayGiaPhaController;
use App\Http\Controllers\Api\GiaPha\DashboardController;
use App\Http\Controllers\Api\GiaPha\HonNhanController;
use App\Http\Controllers\Api\GiaPha\LichSuPhaHeController;
use App\Http\Controllers\Api\GiaPha\MoPhanController;
use App\Http\Controllers\Api\GiaPha\NguoiDungController;
use App\Http\Controllers\Api\GiaPha\NhanhHoController;
use App\Http\Controllers\Api\GiaPha\NhatKyHoatDongController;
use App\Http\Controllers\Api\GiaPha\PhaHeController;
use App\Http\Controllers\Api\GiaPha\SuKienDongHoController;
use App\Http\Controllers\Api\GiaPha\TepTinTuLieuController;
use App\Http\Controllers\Api\GiaPha\ThanhVienGiaPhaController;
use App\Http\Controllers\Api\GiaPha\ThongBaoController;
use Illuminate\Support\Facades\Route;

// ===========================
// CÔNG KHAI - Không cần đăng nhập (Khách vãng lai)
// ===========================
Route::post('/dang-ky', [AuthController::class, 'register']);
Route::post('/dang-nhap', [AuthController::class, 'login']);
Route::get('/check-token', [AuthController::class, 'checkToken']);

// Dữ liệu công khai
Route::get('/bai-viet-cong-khai/data', [BaiVietController::class, 'getDataCongKhai']);
Route::get('/cay-gia-pha-cong-khai/data', [CayGiaPhaController::class, 'getDataCongKhai']);
Route::get('/thanh-vien-gia-pha-cong-khai/{id}', [ThanhVienGiaPhaController::class, 'showCongKhai']);
Route::get('/su-kien-cong-khai/data', [SuKienDongHoController::class, 'getDataCongKhai']);
Route::post('/su-kien-cong-khai/{id}/dang-ky', [SuKienDongHoController::class, 'dangKyThamGiaCongKhai']);
Route::get('/mo-phan-cong-khai/data', [MoPhanController::class, 'getDataCongKhai']);
Route::get('/cay-gia-pha-chia-se/{ma}', [CayGiaPhaChiaSeController::class, 'hienThi']);

// ===========================
// TẤT CẢ ĐÃ ĐĂNG NHẬP (auth:sanctum)
// ===========================
Route::middleware('auth:sanctum')->group(function () {

    // --- Auth ---
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/dang-xuat', [AuthController::class, 'dangXuat']);

    // --- Dashboard (phân quyền trong controller) ---
    Route::get('/dashboard/data', [DashboardController::class, 'getData']);

    // --- Cây gia phả ---
    Route::get('/cay-gia-pha/data', [CayGiaPhaController::class, 'getData']);
    Route::post('/cay-gia-pha/nguoi-than', [CayGiaPhaController::class, 'nguoiThan']);

    // ===========================
    // LẤY DỮ LIỆU - tất cả user đã login
    // Controller tự phân quyền lọc theo vai trò
    // ===========================
    Route::get('/nhanh-ho/data', [NhanhHoController::class, 'getData']);
    Route::get('/thanh-vien-gia-pha/data', [ThanhVienGiaPhaController::class, 'getData']);
    Route::get('/pha-he/data', [PhaHeController::class, 'getData']);
    Route::get('/hon-nhan/data', [HonNhanController::class, 'getData']);
    Route::get('/bai-viet/data', [BaiVietController::class, 'getData']);
    Route::get('/su-kien/data', [SuKienDongHoController::class, 'getData']);
    Route::get('/tep-tin/data', [TepTinTuLieuController::class, 'getData']);
    Route::get('/mo-phan/data', [MoPhanController::class, 'getData']);
    Route::get('/thong-bao/data', [ThongBaoController::class, 'getData']);
    Route::get('/cay-gia-pha-chia-se/data', [CayGiaPhaChiaSeController::class, 'getData']);
    Route::get('/lich-su-pha-he/data', [LichSuPhaHeController::class, 'getData']);
    Route::get('/nhat-ky/data', [NhatKyHoatDongController::class, 'getData']);
    Route::get('/binh-luan/data', [BinhLuanController::class, 'getData']);

    // ===========================
    // ADMIN - QUẢN TRỊ VIÊN
    // Middleware: chỉ quan_tri_vien mới vào được
    // ===========================
    Route::prefix('admin')->middleware('quanTriVienMiddle')->group(function () {

        // Tài khoản người dùng
        Route::get('/nguoi-dung/data', [NguoiDungController::class, 'getData']);
        Route::get('/nguoi-dung/search', [NguoiDungController::class, 'search']);
        Route::post('/nguoi-dung/luu', [NguoiDungController::class, 'store']);
        Route::post('/nguoi-dung/cap-nhat', [NguoiDungController::class, 'update']);
        Route::post('/nguoi-dung/xoa', [NguoiDungController::class, 'destroy']);
        Route::post('/nguoi-dung/change-status', [NguoiDungController::class, 'changeStatus']);

        // Phả hệ
        Route::get('/pha-he/data', [PhaHeController::class, 'getData']);
        Route::post('/pha-he/luu', [PhaHeController::class, 'store']);
        Route::post('/pha-he/cap-nhat', [PhaHeController::class, 'update']);
        Route::post('/pha-he/xoa', [PhaHeController::class, 'destroy']);

        // Nhánh họ
        Route::get('/nhanh-ho/data', [NhanhHoController::class, 'getData']);
        Route::post('/nhanh-ho/luu', [NhanhHoController::class, 'store']);
        Route::post('/nhanh-ho/cap-nhat', [NhanhHoController::class, 'update']);
        Route::post('/nhanh-ho/xoa', [NhanhHoController::class, 'destroy']);

        // Thành viên gia phả
        Route::get('/thanh-vien-gia-pha/data', [ThanhVienGiaPhaController::class, 'getData']);
        Route::post('/thanh-vien-gia-pha/luu', [ThanhVienGiaPhaController::class, 'store']);
        Route::post('/thanh-vien-gia-pha/cap-nhat', [ThanhVienGiaPhaController::class, 'update']);
        Route::post('/thanh-vien-gia-pha/xoa', [ThanhVienGiaPhaController::class, 'destroy']);
        Route::post('/thanh-vien-gia-pha/khoi-phuc', [ThanhVienGiaPhaController::class, 'restore']);

        // Hôn nhân
        Route::get('/hon-nhan/data', [HonNhanController::class, 'getData']);
        Route::post('/hon-nhan/luu', [HonNhanController::class, 'store']);
        Route::post('/hon-nhan/cap-nhat', [HonNhanController::class, 'update']);
        Route::post('/hon-nhan/xoa', [HonNhanController::class, 'destroy']);

        // Bài viết
        Route::get('/bai-viet/data', [BaiVietController::class, 'getData']);
        Route::post('/bai-viet/luu', [BaiVietController::class, 'store']);
        Route::post('/bai-viet/cap-nhat', [BaiVietController::class, 'update']);
        Route::post('/bai-viet/xoa', [BaiVietController::class, 'destroy']);

        // Bình luận
        Route::get('/binh-luan/data', [BinhLuanController::class, 'getData']);
        Route::post('/binh-luan/xoa', [BinhLuanController::class, 'destroy']);

        // Sự kiện
        Route::get('/su-kien/data', [SuKienDongHoController::class, 'getData']);
        Route::post('/su-kien/luu', [SuKienDongHoController::class, 'store']);
        Route::post('/su-kien/cap-nhat', [SuKienDongHoController::class, 'update']);
        Route::post('/su-kien/xoa', [SuKienDongHoController::class, 'destroy']);

        // Tài liệu
        Route::get('/tep-tin/data', [TepTinTuLieuController::class, 'getData']);
        Route::post('/tep-tin/luu', [TepTinTuLieuController::class, 'store']);
        Route::post('/tep-tin/xoa', [TepTinTuLieuController::class, 'destroy']);

        // Mộ phần
        Route::get('/mo-phan/data', [MoPhanController::class, 'getData']);
        Route::post('/mo-phan/luu', [MoPhanController::class, 'store']);
        Route::post('/mo-phan/cap-nhat', [MoPhanController::class, 'update']);
        Route::post('/mo-phan/xoa', [MoPhanController::class, 'destroy']);

        // Thông báo
        Route::get('/thong-bao/data', [ThongBaoController::class, 'getData']);
        Route::post('/thong-bao/da-doc', [ThongBaoController::class, 'danhDauDaDoc']);

        // Chia sẻ cây gia phả
        Route::get('/cay-gia-pha-chia-se/data', [CayGiaPhaChiaSeController::class, 'getData']);
        Route::post('/cay-gia-pha-chia-se/luu', [CayGiaPhaChiaSeController::class, 'store']);
        Route::post('/cay-gia-pha-chia-se/xoa', [CayGiaPhaChiaSeController::class, 'destroy']);

        // Lịch sử phả hệ
        Route::get('/lich-su-pha-he/data', [LichSuPhaHeController::class, 'getData']);
        Route::post('/lich-su-pha-he/luu', [LichSuPhaHeController::class, 'store']);
        Route::post('/lich-su-pha-he/cap-nhat', [LichSuPhaHeController::class, 'update']);
        Route::post('/lich-su-pha-he/xoa', [LichSuPhaHeController::class, 'destroy']);

        // Nhật ký hoạt động
        Route::get('/nhat-ky/data', [NhatKyHoatDongController::class, 'getData']);
    });

    // ===========================
    // TRƯỞNG NHÁNH
    // Middleware: truong_nhanh hoặc quan_tri_vien
    // Thao tác trực tiếp, không qua yêu cầu chờ duyệt
    // ===========================
    Route::prefix('truong-nhanh')->middleware('truongNhanhMiddle')->group(function () {

        // Thành viên trong nhánh - dùng storeTruongNhanh/updateTruongNhanh/destroyTruongNhanh
        // để có logic kiểm tra giới hạn nhánh
        Route::post('/thanh-vien/luu', [ThanhVienGiaPhaController::class, 'storeTruongNhanh']);
        Route::post('/thanh-vien/cap-nhat', [ThanhVienGiaPhaController::class, 'updateTruongNhanh']);
        Route::post('/thanh-vien/xoa', [ThanhVienGiaPhaController::class, 'destroyTruongNhanh']);

        // Hôn nhân trong nhánh
        Route::post('/hon-nhan/luu', [HonNhanController::class, 'store']);
        Route::post('/hon-nhan/cap-nhat', [HonNhanController::class, 'update']);
        Route::post('/hon-nhan/xoa', [HonNhanController::class, 'destroy']);

        // Bài viết trong nhánh
        Route::post('/bai-viet/luu', [BaiVietController::class, 'store']);
        Route::post('/bai-viet/cap-nhat', [BaiVietController::class, 'update']);
        Route::post('/bai-viet/xoa', [BaiVietController::class, 'destroy']);

        // Sự kiện trong nhánh
        Route::post('/su-kien/luu', [SuKienDongHoController::class, 'store']);
        Route::post('/su-kien/cap-nhat', [SuKienDongHoController::class, 'update']);
        Route::post('/su-kien/xoa', [SuKienDongHoController::class, 'destroy']);

        // Tài liệu trong nhánh
        Route::post('/tep-tin/luu', [TepTinTuLieuController::class, 'store']);
        Route::post('/tep-tin/xoa', [TepTinTuLieuController::class, 'destroy']);

        // Thông báo
        Route::post('/thong-bao/da-doc', [ThongBaoController::class, 'danhDauDaDoc']);
    });

    // ===========================
    // THÀNH VIÊN DÒNG HỌ
    // Middleware: thanh_vien, truong_nhanh hoặc quan_tri_vien
    // Thao tác trực tiếp trong phạm vi quyền
    // ===========================
    Route::prefix('thanh-vien')->middleware('thanhVienMiddle')->group(function () {

        // Cập nhật thông tin cá nhân (chỉ bản ghi của mình)
        Route::post('/ca-nhan/cap-nhat', [ThanhVienGiaPhaController::class, 'updateCaNhan']);

        // Thông báo
        Route::post('/thong-bao/da-doc', [ThongBaoController::class, 'danhDauDaDoc']);

        // Bình luận
        Route::post('/binh-luan/luu', [BinhLuanController::class, 'store']);

        // Tài liệu - thành viên có thể upload tư liệu
        Route::post('/tep-tin/luu', [TepTinTuLieuController::class, 'store']);
    });
});
