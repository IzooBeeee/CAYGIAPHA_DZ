<?php

namespace App\Http\Controllers\Api\GiaPha;

use App\Http\Controllers\Controller;
use App\Http\Requests\GiaPha\LuuNhanhHoRequest;
use App\Http\Requests\GiaPha\XoaNhanhHoRequest;
use App\Models\NhanhHo;
use App\Models\NhatKyHoatDong;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NhanhHoController extends Controller
{
    public function getData()
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login) {
            return response()->json(['status' => 0, 'message' => 'Bạn cần đăng nhập hệ thống!'], 401);
        }

        $query = NhanhHo::with(['phaHe', 'nguoiGoc', 'truongNhanhHienTai', 'nguoiQuanLy', 'nhanhCha']);
        if ($login->vai_tro === 'truong_nhanh') {
            $query->whereIn('id', $login->nhanhHosQuanLy()->pluck('id'));
        }

        return response()->json(['status' => 1, 'data' => $query->orderByDesc('id')->get()]);
    }

    public function store(LuuNhanhHoRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login || $login->vai_tro !== 'quan_tri_vien') {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thực hiện chức năng này!'], 403);
        }

        $data = $request->validated();
        $nhanhHo = NhanhHo::create($data);
        $this->ghiLog($request, 'them', $nhanhHo, null, $nhanhHo->toArray());

        return response()->json(['status' => 1, 'message' => 'Thêm mới nhánh họ thành công!', 'data' => $nhanhHo]);
    }

    public function update(LuuNhanhHoRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login || $login->vai_tro !== 'quan_tri_vien') {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thực hiện chức năng này!'], 403);
        }

        $nhanhHo = NhanhHo::find($request->id);
        if (! $nhanhHo) {
            return response()->json(['status' => 0, 'message' => 'Không tìm thấy nhánh họ!'], 404);
        }

        $old = $nhanhHo->toArray();
        $nhanhHo->update($request->validated());
        $this->ghiLog($request, 'sua', $nhanhHo, $old, $nhanhHo->fresh()->toArray());

        return response()->json(['status' => 1, 'message' => 'Cập nhật nhánh họ thành công!', 'data' => $nhanhHo]);
    }

    public function destroy(XoaNhanhHoRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login || $login->vai_tro !== 'quan_tri_vien') {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thực hiện chức năng này!'], 403);
        }

        $nhanhHo = NhanhHo::find($request->id);
        if (! $nhanhHo) {
            return response()->json(['status' => 0, 'message' => 'Không tìm thấy nhánh họ!'], 404);
        }

        $old = $nhanhHo->toArray();
        $nhanhHo->delete();
        $this->ghiLog($request, 'xoa', $nhanhHo, $old, null);

        return response()->json(['status' => 1, 'message' => 'Xóa nhánh họ thành công!']);
    }

    private function ghiLog(Request $request, string $hanhDong, NhanhHo $nhanhHo, ?array $cu, ?array $moi): void
    {
        NhatKyHoatDong::create([
            'id_nguoi_dung' => Auth::guard('sanctum')->id(),
            'hanh_dong' => $hanhDong,
            'ten_bang' => 'nhanh_hos',
            'id_ban_ghi' => $nhanhHo->id,
            'du_lieu_cu' => $cu,
            'du_lieu_moi' => $moi,
            'dia_chi_ip' => $request->ip(),
            'trinh_duyet' => $request->userAgent(),
        ]);
    }
}
