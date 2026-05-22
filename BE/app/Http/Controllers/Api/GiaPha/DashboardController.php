<?php

namespace App\Http\Controllers\Api\GiaPha;

use App\Http\Controllers\Controller;
use App\Models\BaiViet;
use App\Models\HonNhan;
use App\Models\NguoiDung;
use App\Models\NhanhHo;
use App\Models\NhatKyHoatDong;
use App\Models\SuKienDongHo;
use App\Models\TepTinTuLieu;
use App\Models\ThanhVienGiaPha;
use App\Models\ThongBao;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function getData()
    {
        $login = Auth::guard('sanctum')->user();

        if (! $login) {
            return response()->json([
                'status' => 0,
                'message' => 'Bạn cần đăng nhập hệ thống!',
            ], 401);
        }

        // Dashboard Trưởng nhánh: thống kê dữ liệu trong nhánh mình quản lý
        if ($login->vai_tro === 'truong_nhanh') {
            $idsNhanh = $login->nhanhHosQuanLy()->pluck('id');

            return response()->json([
                'status' => 1,
                'data' => [
                    'so_thanh_vien' => ThanhVienGiaPha::whereIn('id_nhanh_ho', $idsNhanh)->count(),
                    'so_hon_nhan'   => HonNhan::whereHas('chong', fn ($q) => $q->whereIn('id_nhanh_ho', $idsNhanh))
                        ->orWhereHas('vo', fn ($q) => $q->whereIn('id_nhanh_ho', $idsNhanh))
                        ->count(),
                    'so_bai_viet'   => BaiViet::whereIn('id_nhanh_ho', $idsNhanh)->count(),
                    'so_su_kien'    => SuKienDongHo::whereIn('id_nhanh_ho', $idsNhanh)->count(),
                    'so_tu_lieu'    => TepTinTuLieu::whereIn('id_nhanh_ho', $idsNhanh)->count(),
                    'thong_bao_chua_doc' => ThongBao::where('id_nguoi_nhan', $login->id)->where('da_doc', false)->count(),
                    'nhanh_quan_ly' => $login->nhanhHosQuanLy()->with('phaHe')->get(),
                ],
            ]);
        }

        // Dashboard Thành viên dòng họ: xem thông tin cá nhân và hoạt động
        if ($login->vai_tro === 'thanh_vien') {
            return response()->json([
                'status' => 1,
                'data' => [
                    'thong_tin_ca_nhan'  => $login->thanhVienGiaPha,
                    'thong_bao_chua_doc' => ThongBao::where('id_nguoi_nhan', $login->id)->where('da_doc', false)->count(),
                    'hoat_dong_gan_day'  => NhatKyHoatDong::where('id_nguoi_dung', $login->id)->latest()->take(10)->get(),
                ],
            ]);
        }

        // Dashboard Admin: toàn bộ thống kê hệ thống
        return response()->json([
            'status' => 1,
            'data' => [
                'tong_thanh_vien'    => ThanhVienGiaPha::count(),
                'tong_nhanh_ho'      => NhanhHo::count(),
                'tong_tai_khoan'     => NguoiDung::count(),
                'tong_hon_nhan'      => HonNhan::count(),
                'tong_bai_viet'      => BaiViet::count(),
                'tong_su_kien'       => SuKienDongHo::count(),
                'tong_tu_lieu'       => TepTinTuLieu::count(),
                'thong_bao_chua_doc' => ThongBao::where('da_doc', false)->count(),
            ],
        ]);
    }
}
