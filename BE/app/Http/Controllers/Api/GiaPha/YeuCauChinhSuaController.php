<?php

namespace App\Http\Controllers\Api\GiaPha;

use App\Http\Controllers\Controller;
use App\Http\Requests\GiaPha\DuyetYeuCauRequest;
use App\Http\Requests\GiaPha\LuuYeuCauChinhSuaRequest;
use App\Http\Requests\GiaPha\TuChoiYeuCauRequest;
use App\Models\NguoiDung;
use App\Models\NhatKyHoatDong;
use App\Models\ThanhVienGiaPha;
use App\Models\ThongBao;
use App\Models\YeuCauChinhSua;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class YeuCauChinhSuaController extends Controller
{
    public function storeCongKhai(Request $request)
    {
        $data = $request->validate([
            'ho_ten_nguoi_gui' => 'required|string|max:255',
            'so_dien_thoai' => 'required|string|max:30',
            'nhanh_ho' => 'nullable|string|max:255',
            'loai_yeu_cau' => 'required|in:them_thanh_vien,cap_nhat_thanh_vien,cap_nhat_quan_he',
            'du_lieu_moi' => 'required|array',
            'du_lieu_cu' => 'nullable|array',
        ]);

        $nguoiGui = NguoiDung::firstOrCreate(
            ['email' => 'dong-gop-cong-khai@gia-pha.local'],
            [
                'ho_ten' => 'Đóng góp công khai',
                'mat_khau' => bin2hex(random_bytes(16)),
                'vai_tro' => 'thanh_vien',
                'trang_thai' => 'hoat_dong',
            ]
        );

        $duLieuMoi = $data['du_lieu_moi'];
        $duLieuMoi['nguoi_dong_gop'] = [
            'ho_ten' => $data['ho_ten_nguoi_gui'],
            'so_dien_thoai' => $data['so_dien_thoai'],
            'nhanh_ho' => $data['nhanh_ho'] ?? null,
        ];

        $yeuCau = YeuCauChinhSua::create([
            'id_nguoi_gui' => $nguoiGui->id,
            'id_thanh_vien_gia_pha' => $request->id_thanh_vien_gia_pha,
            'loai_yeu_cau' => $data['loai_yeu_cau'],
            'du_lieu_cu' => $data['du_lieu_cu'] ?? null,
            'du_lieu_moi' => $duLieuMoi,
            'trang_thai' => 'cho_duyet',
        ]);

        ThongBao::create([
            'id_nguoi_gui' => $nguoiGui->id,
            'tieu_de' => 'Có đóng góp gia phả công khai mới',
            'noi_dung' => $data['ho_ten_nguoi_gui'].' gửi yêu cầu '.$data['loai_yeu_cau'],
            'loai_thong_bao' => 'yeu_cau_chinh_sua',
        ]);

        return response()->json([
            'status' => 1,
            'message' => 'Gửi đóng góp thành công, vui lòng chờ ban quản trị phê duyệt!',
            'data' => $yeuCau,
        ]);
    }

    public function getData()
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login) {
            return response()->json(['status' => 0, 'message' => 'Bạn cần đăng nhập hệ thống!'], 401);
        }

        $query = YeuCauChinhSua::with(['nguoiGui', 'thanhVienGiaPha', 'nguoiDuyet']);
        if ($login->vai_tro === 'thanh_vien') {
            $query->where('id_nguoi_gui', $login->id);
        }
        if ($login->vai_tro === 'truong_nhanh') {
            $idsNhanh = $login->nhanhHosQuanLy()->pluck('id');
            $query->whereHas('thanhVienGiaPha', fn ($q) => $q->whereIn('id_nhanh_ho', $idsNhanh));
        }

        return response()->json(['status' => 1, 'data' => $query->orderByDesc('id')->get()]);
    }

    public function store(LuuYeuCauChinhSuaRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login) {
            return response()->json(['status' => 0, 'message' => 'Bạn cần đăng nhập hệ thống!'], 401);
        }

        $yeuCau = YeuCauChinhSua::create([
            'id_nguoi_gui' => $login->id,
            'id_thanh_vien_gia_pha' => $request->id_thanh_vien_gia_pha,
            'loai_yeu_cau' => $request->loai_yeu_cau,
            'du_lieu_cu' => $request->du_lieu_cu,
            'du_lieu_moi' => $request->du_lieu_moi,
            'trang_thai' => 'cho_duyet',
        ]);

        return response()->json(['status' => 1, 'message' => 'Gửi yêu cầu chỉnh sửa thành công!', 'data' => $yeuCau]);
    }

    public function duyet(DuyetYeuCauRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login || ! in_array($login->vai_tro, ['quan_tri_vien', 'truong_nhanh'], true)) {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thực hiện chức năng này!'], 403);
        }

        $yeuCau = YeuCauChinhSua::with('thanhVienGiaPha')->find($request->id);

        DB::transaction(function () use ($request, $login, $yeuCau) {
            if ($yeuCau->loai_yeu_cau === 'them_thanh_vien') {
                ThanhVienGiaPha::create($yeuCau->du_lieu_moi ?? []);
            } elseif ($yeuCau->loai_yeu_cau === 'cap_nhat_thanh_vien' && $yeuCau->thanhVienGiaPha) {
                $yeuCau->thanhVienGiaPha->update($yeuCau->du_lieu_moi ?? []);
            } elseif ($yeuCau->loai_yeu_cau === 'xoa_thanh_vien' && $yeuCau->thanhVienGiaPha) {
                $yeuCau->thanhVienGiaPha->delete();
            }

            $yeuCau->update(['trang_thai' => 'da_duyet', 'id_nguoi_duyet' => $login->id, 'thoi_gian_duyet' => now()]);
            $this->taoThongBao($login->id, $yeuCau->id_nguoi_gui, 'Yêu cầu chỉnh sửa đã được duyệt');
            $this->ghiLog($request, 'duyet', $yeuCau);
        });

        return response()->json(['status' => 1, 'message' => 'Duyệt yêu cầu thành công!']);
    }

    public function tuChoi(TuChoiYeuCauRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login || ! in_array($login->vai_tro, ['quan_tri_vien', 'truong_nhanh'], true)) {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thực hiện chức năng này!'], 403);
        }

        $yeuCau = YeuCauChinhSua::find($request->id);
        $yeuCau->update(['trang_thai' => 'tu_choi', 'id_nguoi_duyet' => $login->id, 'thoi_gian_duyet' => now(), 'ly_do' => $request->ly_do]);
        $this->taoThongBao($login->id, $yeuCau->id_nguoi_gui, 'Yêu cầu chỉnh sửa bị từ chối', $request->ly_do);
        $this->ghiLog($request, 'tu_choi', $yeuCau);

        return response()->json(['status' => 1, 'message' => 'Từ chối yêu cầu thành công!']);
    }

    private function taoThongBao(int $idNguoiGui, int $idNguoiNhan, string $tieuDe, ?string $noiDung = null): void
    {
        ThongBao::create(['id_nguoi_gui' => $idNguoiGui, 'id_nguoi_nhan' => $idNguoiNhan, 'tieu_de' => $tieuDe, 'noi_dung' => $noiDung, 'loai_thong_bao' => 'yeu_cau_chinh_sua']);
    }

    private function ghiLog(Request $request, string $hanhDong, YeuCauChinhSua $yeuCau): void
    {
        NhatKyHoatDong::create(['id_nguoi_dung' => Auth::guard('sanctum')->id(), 'hanh_dong' => $hanhDong, 'ten_bang' => 'yeu_cau_chinh_suas', 'id_ban_ghi' => $yeuCau->id, 'du_lieu_cu' => $yeuCau->du_lieu_cu, 'du_lieu_moi' => $yeuCau->du_lieu_moi, 'dia_chi_ip' => $request->ip(), 'trinh_duyet' => $request->userAgent()]);
    }
}
