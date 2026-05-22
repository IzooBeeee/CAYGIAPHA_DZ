<?php

use App\Http\Controllers\Api\AdminUserController as LegacyAdminUserController;
use App\Http\Controllers\Api\AuthController as LegacyAuthController;
use App\Http\Controllers\Api\FamilyController as LegacyFamilyController;
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
use App\Http\Controllers\Api\GiaPha\YeuCauChinhSuaController;
use App\Http\Controllers\Api\MarriageController as LegacyMarriageController;
use App\Http\Controllers\Api\PersonController as LegacyPersonController;
use App\Http\Controllers\Api\TreeController as LegacyTreeController;
use Illuminate\Support\Facades\Route;

// ===========================
// CÔNG KHAI - Không cần đăng nhập
// ===========================
Route::post('/dang-ky', [AuthController::class, 'register']);
Route::post('/dang-nhap', [AuthController::class, 'login']);
Route::post('/register', [LegacyAuthController::class, 'register']);
Route::post('/login', [LegacyAuthController::class, 'login']);
Route::get('/check-token', [AuthController::class, 'checkToken']);
Route::get('/bai-viet-cong-khai/data', [BaiVietController::class, 'getDataCongKhai']);
Route::get('/cay-gia-pha-cong-khai/data', [CayGiaPhaController::class, 'getDataCongKhai']);
Route::get('/thanh-vien-gia-pha-cong-khai/{id}', [ThanhVienGiaPhaController::class, 'showCongKhai']);
Route::get('/su-kien-cong-khai/data', [SuKienDongHoController::class, 'getDataCongKhai']);
Route::post('/su-kien-cong-khai/{id}/dang-ky', [SuKienDongHoController::class, 'dangKyThamGiaCongKhai']);
Route::get('/mo-phan-cong-khai/data', [MoPhanController::class, 'getDataCongKhai']);
Route::post('/dong-gop-cong-khai', [YeuCauChinhSuaController::class, 'storeCongKhai']);
Route::get('/cay-gia-pha-chia-se/{ma}', [CayGiaPhaChiaSeController::class, 'hienThi']);

// ===========================
// TẤT CẢ ĐÃ ĐĂNG NHẬP (auth:sanctum)
// ===========================
Route::middleware('auth:sanctum')->group(function () {

    // --- Auth ---
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/dang-xuat', [AuthController::class, 'dangXuat']);
    Route::post('/logout', [LegacyAuthController::class, 'logout']);

    // --- Legacy API compatibility for older FE components ---
    Route::apiResource('families', LegacyFamilyController::class);
    Route::get('/families/{familyId}/people', [LegacyPersonController::class, 'index']);
    Route::post('/families/{familyId}/people', [LegacyPersonController::class, 'store']);
    Route::get('/families/{familyId}/marriages', [LegacyFamilyController::class, 'marriages']);
    Route::post('/families/{familyId}/marriages', [LegacyMarriageController::class, 'store']);
    Route::get('/families/{familyId}/tree', [LegacyTreeController::class, 'show']);
    Route::get('/people/{id}', [LegacyPersonController::class, 'show']);
    Route::put('/people/{id}', [LegacyPersonController::class, 'update']);
    Route::delete('/people/{id}', [LegacyPersonController::class, 'destroy']);
    Route::delete('/marriages/{id}', [LegacyMarriageController::class, 'destroy']);
    Route::get('/admin/users', [LegacyAdminUserController::class, 'index']);
    Route::patch('/admin/users/{id}', [LegacyAdminUserController::class, 'update']);
    Route::delete('/admin/users/{id}', [LegacyAdminUserController::class, 'destroy']);

    // --- Dashboard ---
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
    Route::get('/yeu-cau-chinh-sua/data', [YeuCauChinhSuaController::class, 'getData']);
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
    // ===========================
    Route::prefix('admin')->middleware('quanTriVienMiddle')->group(function () {

        // Tài khoản người dùng
        Route::get('/nguoi-dung/data', [NguoiDungController::class, 'getData']);
        Route::get('/nguoi-dung/search', [NguoiDungController::class, 'search']);
        Route::post('/nguoi-dung/luu', [NguoiDungController::class, 'store']);
        Route::post('/nguoi-dung/cap-nhat', [NguoiDungController::class, 'update']);
        Route::post('/nguoi-dung/xoa', [NguoiDungController::class, 'destroy']);
        Route::post('/nguoi-dung/create', [NguoiDungController::class, 'store']);
        Route::post('/nguoi-dung/update', [NguoiDungController::class, 'update']);
        Route::post('/nguoi-dung/delete', [NguoiDungController::class, 'destroy']);
        Route::post('/nguoi-dung/change-status', [NguoiDungController::class, 'changeStatus']);

        // Phả hệ
        Route::get('/pha-he/data', [PhaHeController::class, 'getData']);
        Route::post('/pha-he/luu', [PhaHeController::class, 'store']);
        Route::post('/pha-he/cap-nhat', [PhaHeController::class, 'update']);
        Route::post('/pha-he/xoa', [PhaHeController::class, 'destroy']);
        Route::post('/pha-he/create', [PhaHeController::class, 'store']);
        Route::post('/pha-he/update', [PhaHeController::class, 'update']);
        Route::post('/pha-he/delete', [PhaHeController::class, 'destroy']);

        // Nhánh họ
        Route::get('/nhanh-ho/data', [NhanhHoController::class, 'getData']);
        Route::post('/nhanh-ho/luu', [NhanhHoController::class, 'store']);
        Route::post('/nhanh-ho/cap-nhat', [NhanhHoController::class, 'update']);
        Route::post('/nhanh-ho/xoa', [NhanhHoController::class, 'destroy']);
        Route::post('/nhanh-ho/create', [NhanhHoController::class, 'store']);
        Route::post('/nhanh-ho/update', [NhanhHoController::class, 'update']);
        Route::post('/nhanh-ho/delete', [NhanhHoController::class, 'destroy']);

        // Thành viên gia phả
        Route::get('/thanh-vien-gia-pha/data', [ThanhVienGiaPhaController::class, 'getData']);
        Route::post('/thanh-vien-gia-pha/luu', [ThanhVienGiaPhaController::class, 'store']);
        Route::post('/thanh-vien-gia-pha/cap-nhat', [ThanhVienGiaPhaController::class, 'update']);
        Route::post('/thanh-vien-gia-pha/xoa', [ThanhVienGiaPhaController::class, 'destroy']);
        Route::post('/thanh-vien-gia-pha/khoi-phuc', [ThanhVienGiaPhaController::class, 'restore']);
        Route::post('/thanh-vien-gia-pha/create', [ThanhVienGiaPhaController::class, 'store']);
        Route::post('/thanh-vien-gia-pha/update', [ThanhVienGiaPhaController::class, 'update']);
        Route::post('/thanh-vien-gia-pha/delete', [ThanhVienGiaPhaController::class, 'destroy']);
        Route::post('/thanh-vien-gia-pha/restore', [ThanhVienGiaPhaController::class, 'restore']);

        // Hôn nhân
        Route::get('/hon-nhan/data', [HonNhanController::class, 'getData']);
        Route::post('/hon-nhan/luu', [HonNhanController::class, 'store']);
        Route::post('/hon-nhan/cap-nhat', [HonNhanController::class, 'update']);
        Route::post('/hon-nhan/xoa', [HonNhanController::class, 'destroy']);
        Route::post('/hon-nhan/create', [HonNhanController::class, 'store']);
        Route::post('/hon-nhan/update', [HonNhanController::class, 'update']);
        Route::post('/hon-nhan/delete', [HonNhanController::class, 'destroy']);

        // Yêu cầu chỉnh sửa
        Route::get('/yeu-cau-chinh-sua/data', [YeuCauChinhSuaController::class, 'getData']);
        Route::post('/yeu-cau-chinh-sua/duyet', [YeuCauChinhSuaController::class, 'duyet']);
        Route::post('/yeu-cau-chinh-sua/tu-choi', [YeuCauChinhSuaController::class, 'tuChoi']);

        // Bài viết
        Route::get('/bai-viet/data', [BaiVietController::class, 'getData']);
        Route::post('/bai-viet/luu', [BaiVietController::class, 'store']);
        Route::post('/bai-viet/cap-nhat', [BaiVietController::class, 'update']);
        Route::post('/bai-viet/xoa', [BaiVietController::class, 'destroy']);
        Route::post('/bai-viet/create', [BaiVietController::class, 'store']);
        Route::post('/bai-viet/update', [BaiVietController::class, 'update']);
        Route::post('/bai-viet/delete', [BaiVietController::class, 'destroy']);

        // Bình luận
        Route::get('/binh-luan/data', [BinhLuanController::class, 'getData']);
        Route::post('/binh-luan/xoa', [BinhLuanController::class, 'destroy']);
        Route::post('/binh-luan/delete', [BinhLuanController::class, 'destroy']);

        // Sự kiện
        Route::get('/su-kien/data', [SuKienDongHoController::class, 'getData']);
        Route::post('/su-kien/luu', [SuKienDongHoController::class, 'store']);
        Route::post('/su-kien/cap-nhat', [SuKienDongHoController::class, 'update']);
        Route::post('/su-kien/xoa', [SuKienDongHoController::class, 'destroy']);
        Route::post('/su-kien/create', [SuKienDongHoController::class, 'store']);
        Route::post('/su-kien/update', [SuKienDongHoController::class, 'update']);
        Route::post('/su-kien/delete', [SuKienDongHoController::class, 'destroy']);

        // Tài liệu
        Route::get('/tep-tin/data', [TepTinTuLieuController::class, 'getData']);
        Route::get('/tep-tin-tu-lieu/data', [TepTinTuLieuController::class, 'getData']);
        Route::post('/tep-tin/luu', [TepTinTuLieuController::class, 'store']);
        Route::post('/tep-tin-tu-lieu/luu', [TepTinTuLieuController::class, 'store']);
        Route::post('/tep-tin/xoa', [TepTinTuLieuController::class, 'destroy']);
        Route::post('/tep-tin-tu-lieu/xoa', [TepTinTuLieuController::class, 'destroy']);
        Route::post('/tep-tin/create', [TepTinTuLieuController::class, 'store']);
        Route::post('/tep-tin/delete', [TepTinTuLieuController::class, 'destroy']);

        // Mộ phần
        Route::get('/mo-phan/data', [MoPhanController::class, 'getData']);
        Route::post('/mo-phan/luu', [MoPhanController::class, 'store']);
        Route::post('/mo-phan/cap-nhat', [MoPhanController::class, 'update']);
        Route::post('/mo-phan/xoa', [MoPhanController::class, 'destroy']);
        Route::post('/mo-phan/create', [MoPhanController::class, 'store']);
        Route::post('/mo-phan/update', [MoPhanController::class, 'update']);
        Route::post('/mo-phan/delete', [MoPhanController::class, 'destroy']);

        // Thông báo
        Route::get('/thong-bao/data', [ThongBaoController::class, 'getData']);
        Route::post('/thong-bao/da-doc', [ThongBaoController::class, 'danhDauDaDoc']);

        // Chia sẻ cây gia phả
        Route::get('/cay-gia-pha-chia-se/data', [CayGiaPhaChiaSeController::class, 'getData']);
        Route::post('/cay-gia-pha-chia-se/luu', [CayGiaPhaChiaSeController::class, 'store']);
        Route::post('/cay-gia-pha-chia-se/xoa', [CayGiaPhaChiaSeController::class, 'destroy']);
        Route::post('/cay-gia-pha-chia-se/create', [CayGiaPhaChiaSeController::class, 'store']);
        Route::post('/cay-gia-pha-chia-se/delete', [CayGiaPhaChiaSeController::class, 'destroy']);

        // Lịch sử phả hệ
        Route::get('/lich-su-pha-he/data', [LichSuPhaHeController::class, 'getData']);
        Route::post('/lich-su-pha-he/luu', [LichSuPhaHeController::class, 'store']);
        Route::post('/lich-su-pha-he/cap-nhat', [LichSuPhaHeController::class, 'update']);
        Route::post('/lich-su-pha-he/xoa', [LichSuPhaHeController::class, 'destroy']);
        Route::post('/lich-su-pha-he/create', [LichSuPhaHeController::class, 'store']);
        Route::post('/lich-su-pha-he/update', [LichSuPhaHeController::class, 'update']);
        Route::post('/lich-su-pha-he/delete', [LichSuPhaHeController::class, 'destroy']);

        // Nhật ký hoạt động
        Route::get('/nhat-ky/data', [NhatKyHoatDongController::class, 'getData']);
    });

    // ===========================
    // TRƯỞNG NHÁNH
    // ===========================
    Route::prefix('truong-nhanh')->middleware('truongNhanhMiddle')->group(function () {

        // Thành viên trong nhánh
        Route::post('/thanh-vien/create', [ThanhVienGiaPhaController::class, 'store']);
        Route::post('/thanh-vien/update', [ThanhVienGiaPhaController::class, 'update']);
        Route::post('/thanh-vien/delete', [ThanhVienGiaPhaController::class, 'destroy']);

        // Yêu cầu chỉnh sửa
        Route::post('/yeu-cau-chinh-sua/duyet', [YeuCauChinhSuaController::class, 'duyet']);
        Route::post('/yeu-cau-chinh-sua/tu-choi', [YeuCauChinhSuaController::class, 'tuChoi']);

        // Bài viết
        Route::post('/bai-viet/create', [BaiVietController::class, 'store']);
        Route::post('/bai-viet/update', [BaiVietController::class, 'update']);
        Route::post('/bai-viet/delete', [BaiVietController::class, 'destroy']);

        // Sự kiện
        Route::post('/su-kien/create', [SuKienDongHoController::class, 'store']);
        Route::post('/su-kien/update', [SuKienDongHoController::class, 'update']);
        Route::post('/su-kien/delete', [SuKienDongHoController::class, 'destroy']);

        // Tài liệu
        Route::post('/tep-tin/create', [TepTinTuLieuController::class, 'store']);
        Route::post('/tep-tin/delete', [TepTinTuLieuController::class, 'destroy']);

        // Thông báo
        Route::post('/thong-bao/da-doc', [ThongBaoController::class, 'danhDauDaDoc']);

        // Hôn nhân
        Route::post('/hon-nhan/create', [HonNhanController::class, 'store']);
        Route::post('/hon-nhan/update', [HonNhanController::class, 'update']);
        Route::post('/hon-nhan/delete', [HonNhanController::class, 'destroy']);
    });

    // ===========================
    // THÀNH VIÊN
    // ===========================
    Route::prefix('thanh-vien')->middleware('thanhVienMiddle')->group(function () {

        // Yêu cầu chỉnh sửa
        Route::post('/yeu-cau-chinh-sua/luu', [YeuCauChinhSuaController::class, 'store']);
        Route::post('/yeu-cau-chinh-sua/create', [YeuCauChinhSuaController::class, 'store']);

        // Thông báo
        Route::post('/thong-bao/da-doc', [ThongBaoController::class, 'danhDauDaDoc']);

        // Bình luận
        Route::post('/binh-luan/luu', [BinhLuanController::class, 'store']);
        Route::post('/binh-luan/create', [BinhLuanController::class, 'store']);

        // Tài liệu
        Route::post('/tep-tin/create', [TepTinTuLieuController::class, 'store']);
    });
});
