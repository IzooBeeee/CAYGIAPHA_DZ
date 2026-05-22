<?php

namespace App\Http\Controllers\Api\GiaPha;

use App\Http\Controllers\Controller;
use App\Models\BaiViet;
use App\Models\NguoiDung;
use App\Models\NhanhHo;
use App\Models\SuKienDongHo;
use App\Models\ThanhVienGiaPha;
use App\Models\ThongBao;
use App\Models\YeuCauChinhSua;
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

        if ($login->vai_tro === 'truong_nhanh') {
            $idsNhanh = $login->nhanhHosQuanLy()->pluck('id');

            return response()->json([
                'status' => 1,
                'data' => [
                    'so_thanh_vien' => ThanhVienGiaPha::whereIn('id_nhanh_ho', $idsNhanh)->count(),
                    'yeu_cau_cho_duyet' => YeuCauChinhSua::whereHas('thanhVienGiaPha', function ($query) use ($idsNhanh) {
                        $query->whereIn('id_nhanh_ho', $idsNhanh);
                    })->where('trang_thai', 'cho_duyet')->count(),
                    'so_bai_viet' => BaiViet::whereIn('id_nhanh_ho', $idsNhanh)->count(),
                    'so_su_kien' => SuKienDongHo::whereIn('id_nhanh_ho', $idsNhanh)->count(),
                    'thong_bao_chua_doc' => ThongBao::where('id_nguoi_nhan', $login->id)->where('da_doc', false)->count(),
                ],
            ]);
        }

        if ($login->vai_tro === 'thanh_vien') {
            return response()->json([
                'status' => 1,
                'data' => [
                    'thong_tin_ca_nhan' => $login->thanhVienGiaPha,
                    'thong_bao_moi' => ThongBao::where('id_nguoi_nhan', $login->id)->where('da_doc', false)->count(),
                    'thong_bao_chua_doc' => ThongBao::where('id_nguoi_nhan', $login->id)->where('da_doc', false)->count(),
                    'yeu_cau_da_gui' => YeuCauChinhSua::where('id_nguoi_gui', $login->id)->latest()->take(10)->get(),
                ],
            ]);
        }

        return response()->json([
            'status' => 1,
            'data' => [
                'tong_thanh_vien' => ThanhVienGiaPha::count(),
                'tong_nhanh_ho' => NhanhHo::count(),
                'tong_tai_khoan' => NguoiDung::count(),
                'yeu_cau_cho_duyet' => YeuCauChinhSua::where('trang_thai', 'cho_duyet')->count(),
                'tong_bai_viet' => BaiViet::count(),
                'tong_su_kien' => SuKienDongHo::count(),
                'thong_bao_chua_doc' => ThongBao::where('da_doc', false)->count(),
            ],
        ]);
    }
}
