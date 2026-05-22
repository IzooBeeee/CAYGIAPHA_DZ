<?php

namespace App\Http\Controllers\Api\GiaPha;

use App\Http\Controllers\Controller;
use App\Http\Requests\GiaPha\LuuSuKienDongHoRequest;
use App\Http\Requests\GiaPha\XoaSuKienDongHoRequest;
use App\Models\NhatKyHoatDong;
use App\Models\SuKienDongHo;
use App\Models\ThongBao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuKienDongHoController extends Controller
{
    public function getDataCongKhai()
    {
        return response()->json([
            'status' => 1,
            'data' => SuKienDongHo::with(['nhanhHo'])
                ->orderByDesc('thoi_gian')
                ->get(),
        ]);
    }

    public function dangKyThamGiaCongKhai(Request $request, int $id)
    {
        $suKien = SuKienDongHo::find($id);
        if (! $suKien) {
            return response()->json(['status' => 0, 'message' => 'Không tìm thấy sự kiện!'], 404);
        }

        $data = $request->validate([
            'ho_ten' => 'required|string|max:255',
            'so_dien_thoai' => 'required|string|max:30',
            'so_nguoi_tham_du' => 'required|string|max:20',
            'nhanh_ho' => 'nullable|string|max:255',
        ]);

        ThongBao::create([
            'id_nhanh_ho' => $suKien->id_nhanh_ho,
            'tieu_de' => 'Đăng ký tham gia sự kiện: '.$suKien->tieu_de,
            'noi_dung' => json_encode($data, JSON_UNESCAPED_UNICODE),
            'loai_thong_bao' => 'su_kien',
        ]);

        return response()->json([
            'status' => 1,
            'message' => 'Đăng ký tham gia sự kiện thành công!',
        ]);
    }

    public function getData()
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login) {
            return response()->json(['status' => 0, 'message' => 'Bạn cần đăng nhập hệ thống!'], 401);
        }

        $query = SuKienDongHo::with(['nhanhHo', 'nguoiTao'])->orderByDesc('thoi_gian');
        if ($login->vai_tro === 'truong_nhanh') {
            $query->whereIn('id_nhanh_ho', $login->nhanhHosQuanLy()->pluck('id'));
        }

        return response()->json(['status' => 1, 'data' => $query->get()]);
    }

    public function store(LuuSuKienDongHoRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login || ! in_array($login->vai_tro, ['quan_tri_vien', 'truong_nhanh'], true)) {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thực hiện chức năng này!'], 403);
        }

        // Kiểm tra trưởng nhánh chỉ thêm sự kiện trong nhánh mình
        $nhanhHoId = $request->id_nhanh_ho;
        if ($login->vai_tro === 'truong_nhanh') {
            if (! $nhanhHoId || ! $login->nhanhHosQuanLy()->where('id', $nhanhHoId)->exists()) {
                return response()->json(['status' => 0, 'message' => 'Bạn không được thêm sự kiện vào nhánh khác!'], 403);
            }
        }

        $data = $request->validated();
        $data['id_nguoi_tao'] = $login->id;
        $suKien = SuKienDongHo::create($data);
        $this->ghiLog($request, 'them', $suKien, null, $suKien->toArray());

        // Tạo thông báo sự kiện mới
        ThongBao::create([
            'id_nguoi_gui' => $login->id,
            'id_nhanh_ho' => $suKien->id_nhanh_ho,
            'tieu_de' => 'Sự kiện mới: '.$suKien->tieu_de,
            'noi_dung' => $suKien->mo_ta,
            'loai_thong_bao' => 'su_kien',
        ]);

        return response()->json(['status' => 1, 'message' => 'Thêm mới sự kiện thành công!', 'data' => $suKien]);
    }

    public function update(LuuSuKienDongHoRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login || ! in_array($login->vai_tro, ['quan_tri_vien', 'truong_nhanh'], true)) {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thực hiện chức năng này!'], 403);
        }

        $suKien = SuKienDongHo::find($request->id);
        if (! $suKien) {
            return response()->json(['status' => 0, 'message' => 'Không tìm thấy sự kiện!'], 404);
        }

        // Kiểm tra trưởng nhánh chỉ sửa sự kiện trong nhánh mình
        if ($login->vai_tro === 'truong_nhanh') {
            if (! $login->nhanhHosQuanLy()->where('id', $suKien->id_nhanh_ho)->exists()) {
                return response()->json(['status' => 0, 'message' => 'Bạn không được sửa sự kiện nhánh khác!'], 403);
            }
        }

        $old = $suKien->toArray();
        $suKien->update($request->validated());
        $this->ghiLog($request, 'sua', $suKien, $old, $suKien->fresh()->toArray());

        return response()->json(['status' => 1, 'message' => 'Cập nhật sự kiện thành công!', 'data' => $suKien]);
    }

    public function destroy(XoaSuKienDongHoRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login || ! in_array($login->vai_tro, ['quan_tri_vien', 'truong_nhanh'], true)) {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thực hiện chức năng này!'], 403);
        }

        $suKien = SuKienDongHo::find($request->id);
        if (! $suKien) {
            return response()->json(['status' => 0, 'message' => 'Không tìm thấy sự kiện!'], 404);
        }

        // Kiểm tra trưởng nhánh chỉ xóa sự kiện trong nhánh mình
        if ($login->vai_tro === 'truong_nhanh') {
            if (! $login->nhanhHosQuanLy()->where('id', $suKien->id_nhanh_ho)->exists()) {
                return response()->json(['status' => 0, 'message' => 'Bạn không được xóa sự kiện nhánh khác!'], 403);
            }
        }

        $old = $suKien->toArray();
        $suKien->delete();
        $this->ghiLog($request, 'xoa', $suKien, $old, null);

        return response()->json(['status' => 1, 'message' => 'Xóa sự kiện thành công!']);
    }

    private function ghiLog(Request $request, string $hanhDong, SuKienDongHo $suKien, ?array $cu, ?array $moi): void
    {
        NhatKyHoatDong::create([
            'id_nguoi_dung' => Auth::guard('sanctum')->id(),
            'hanh_dong' => $hanhDong,
            'ten_bang' => 'su_kien_dong_hos',
            'id_ban_ghi' => $suKien->id,
            'du_lieu_cu' => $cu,
            'du_lieu_moi' => $moi,
            'dia_chi_ip' => $request->ip(),
            'trinh_duyet' => $request->userAgent(),
        ]);
    }
}
