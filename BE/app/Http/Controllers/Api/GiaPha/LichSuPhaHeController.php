<?php

namespace App\Http\Controllers\Api\GiaPha;

use App\Http\Controllers\Controller;
use App\Http\Requests\GiaPha\LuuLichSuPhaHeRequest;
use App\Http\Requests\GiaPha\XoaLichSuPhaHeRequest;
use App\Models\LichSuPhaHe;
use App\Models\NhatKyHoatDong;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LichSuPhaHeController extends Controller
{
    public function getData()
    {
        if (! Auth::guard('sanctum')->user()) {
            return response()->json(['status' => 0, 'message' => 'Bạn cần đăng nhập hệ thống!'], 401);
        }

        $query = LichSuPhaHe::with('phaHe')->orderByDesc('moc_thoi_gian');
        $login = Auth::guard('sanctum')->user();
        if ($login->vai_tro === 'truong_nhanh') {
            $idsNhanh = $login->nhanhHosQuanLy()->pluck('id');
            $query->whereHas('phaHe', function ($q) use ($idsNhanh) {
                $q->whereHas('nhanhHos', function ($q2) use ($idsNhanh) {
                    $q2->whereIn('nhanh_hos.id', $idsNhanh);
                });
            });
        }

        return response()->json(['status' => 1, 'data' => $query->get()]);
    }

    public function store(LuuLichSuPhaHeRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login || ! in_array($login->vai_tro, ['quan_tri_vien', 'truong_nhanh'], true)) {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thực hiện chức năng này!'], 403);
        }

        $lichSu = LichSuPhaHe::create($request->validated());
        $this->ghiLog($request, 'them', $lichSu, null, $lichSu->toArray());

        return response()->json(['status' => 1, 'message' => 'Thêm lịch sử phả hệ thành công!', 'data' => $lichSu]);
    }

    public function update(LuuLichSuPhaHeRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login || ! in_array($login->vai_tro, ['quan_tri_vien', 'truong_nhanh'], true)) {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thực hiện chức năng này!'], 403);
        }

        $lichSu = LichSuPhaHe::find($request->id);
        if (! $lichSu) {
            return response()->json(['status' => 0, 'message' => 'Không tìm thấy lịch sử phả hệ!'], 404);
        }

        $old = $lichSu->toArray();
        $lichSu->update($request->validated());
        $this->ghiLog($request, 'sua', $lichSu, $old, $lichSu->fresh()->toArray());

        return response()->json(['status' => 1, 'message' => 'Cập nhật lịch sử phả hệ thành công!', 'data' => $lichSu]);
    }

    public function destroy(XoaLichSuPhaHeRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login || ! in_array($login->vai_tro, ['quan_tri_vien', 'truong_nhanh'], true)) {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thực hiện chức năng này!'], 403);
        }

        $lichSu = LichSuPhaHe::find($request->id);
        if (! $lichSu) {
            return response()->json(['status' => 0, 'message' => 'Không tìm thấy lịch sử phả hệ!'], 404);
        }

        $old = $lichSu->toArray();
        $lichSu->delete();
        $this->ghiLog($request, 'xoa', $lichSu, $old, null);

        return response()->json(['status' => 1, 'message' => 'Xóa lịch sử phả hệ thành công!']);
    }

    private function ghiLog(Request $request, string $hanhDong, LichSuPhaHe $lichSu, ?array $cu, ?array $moi): void
    {
        NhatKyHoatDong::create([
            'id_nguoi_dung' => Auth::guard('sanctum')->id(),
            'hanh_dong' => $hanhDong,
            'ten_bang' => 'lich_su_pha_hes',
            'id_ban_ghi' => $lichSu->id,
            'du_lieu_cu' => $cu,
            'du_lieu_moi' => $moi,
            'dia_chi_ip' => $request->ip(),
            'trinh_duyet' => $request->userAgent(),
        ]);
    }
}
