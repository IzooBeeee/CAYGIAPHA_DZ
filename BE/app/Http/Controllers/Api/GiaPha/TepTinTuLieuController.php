<?php

namespace App\Http\Controllers\Api\GiaPha;

use App\Http\Controllers\Controller;
use App\Http\Requests\GiaPha\LuuTepTinTuLieuRequest;
use App\Http\Requests\GiaPha\XoaTepTinTuLieuRequest;
use App\Models\NhatKyHoatDong;
use App\Models\TepTinTuLieu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TepTinTuLieuController extends Controller
{
    public function getData(Request $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login) {
            return response()->json(['status' => 0, 'message' => 'Bạn cần đăng nhập hệ thống!'], 401);
        }

        $query = TepTinTuLieu::with(['thanhVienGiaPha', 'nhanhHo', 'nguoiTaiLen'])->orderByDesc('id');
        if ($request->id_thanh_vien_gia_pha) {
            $query->where('id_thanh_vien_gia_pha', $request->id_thanh_vien_gia_pha);
        }
        if ($request->id_nhanh_ho) {
            $query->where('id_nhanh_ho', $request->id_nhanh_ho);
        }

        return response()->json(['status' => 1, 'data' => $query->get()]);
    }

    public function store(LuuTepTinTuLieuRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login) {
            return response()->json(['status' => 0, 'message' => 'Bạn cần đăng nhập hệ thống!'], 401);
        }

        // Thành viên thường chỉ được upload tài liệu, không kiểm tra nhánh
        $data = $request->validated();
        $data['id_nguoi_tai_len'] = $login->id;
        if ($request->hasFile('duong_dan_tep')) {
            $data['duong_dan_tep'] = $request->file('duong_dan_tep')->store('tu-lieu', 'public');
        }
        $tepTin = TepTinTuLieu::create($data);
        $this->ghiLog($request, 'them', $tepTin, null, $tepTin->toArray());

        return response()->json(['status' => 1, 'message' => 'Tải tư liệu thành công!', 'data' => $tepTin]);
    }

    public function destroy(XoaTepTinTuLieuRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login || ! in_array($login->vai_tro, ['quan_tri_vien', 'truong_nhanh'], true)) {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thực hiện chức năng này!'], 403);
        }

        $tepTin = TepTinTuLieu::find($request->id);
        if (! $tepTin) {
            return response()->json(['status' => 0, 'message' => 'Không tìm thấy tài liệu!'], 404);
        }

        // Kiểm tra trưởng nhánh chỉ xóa tài liệu trong nhánh mình
        if ($login->vai_tro === 'truong_nhanh') {
            $coQuyen = false;
            if ($tepTin->id_nhanh_ho && $login->nhanhHosQuanLy()->where('id', $tepTin->id_nhanh_ho)->exists()) {
                $coQuyen = true;
            }
            if (! $coQuyen) {
                return response()->json(['status' => 0, 'message' => 'Bạn không được xóa tài liệu nhánh khác!'], 403);
            }
        }

        $old = $tepTin->toArray();
        $tepTin->delete();
        $this->ghiLog($request, 'xoa', $tepTin, $old, null);

        return response()->json(['status' => 1, 'message' => 'Xóa tư liệu thành công!']);
    }

    private function ghiLog(Request $request, string $hanhDong, TepTinTuLieu $tepTin, ?array $cu, ?array $moi): void
    {
        NhatKyHoatDong::create([
            'id_nguoi_dung' => Auth::guard('sanctum')->id(),
            'hanh_dong' => $hanhDong,
            'ten_bang' => 'tep_tin_tu_lieus',
            'id_ban_ghi' => $tepTin->id,
            'du_lieu_cu' => $cu,
            'du_lieu_moi' => $moi,
            'dia_chi_ip' => $request->ip(),
            'trinh_duyet' => $request->userAgent(),
        ]);
    }
}
