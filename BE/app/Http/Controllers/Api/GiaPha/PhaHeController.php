<?php

namespace App\Http\Controllers\Api\GiaPha;

use App\Http\Controllers\Controller;
use App\Http\Requests\GiaPha\LuuPhaHeRequest;
use App\Http\Requests\GiaPha\XoaPhaHeRequest;
use App\Models\LichSuPhaHe;
use App\Models\NhanhHo;
use App\Models\NhatKyHoatDong;
use App\Models\PhaHe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PhaHeController extends Controller
{
    private function checkAdmin()
    {
        $login = Auth::guard('sanctum')->user();

        if (! $login) {
            return response()->json(['status' => 0, 'message' => 'Bạn cần đăng nhập hệ thống!'], 401);
        }

        if ($login->vai_tro !== 'quan_tri_vien') {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thực hiện chức năng này!'], 403);
        }

        return null;
    }

    public function getData()
    {
        if (! Auth::guard('sanctum')->user()) {
            return response()->json(['status' => 0, 'message' => 'Bạn cần đăng nhập hệ thống!'], 401);
        }

        return response()->json(['status' => 1, 'data' => PhaHe::with(['nguoiSangLap', 'nhanhHos'])->orderByDesc('id')->get()]);
    }

    public function store(LuuPhaHeRequest $request)
    {
        if ($error = $this->checkAdmin()) {
            return $error;
        }

        $phaHe = PhaHe::create($request->validated());
        NhanhHo::create([
            'id_pha_he' => $phaHe->id,
            'ten_nhanh' => $this->tenNhanhGoc($phaHe->ten_pha_he),
            'mo_ta' => 'Nhánh gốc được tạo tự động khi tạo phả hệ.',
        ]);
        $phaHe->load(['nguoiSangLap', 'nhanhHos']);
        $this->ghiLog($request, 'them', $phaHe, null, $phaHe->toArray());

        return response()->json(['status' => 1, 'message' => 'Thêm mới phả hệ thành công!', 'data' => $phaHe]);
    }

    public function update(LuuPhaHeRequest $request)
    {
        if ($error = $this->checkAdmin()) {
            return $error;
        }

        $phaHe = PhaHe::find($request->id);
        if (! $phaHe) {
            return response()->json(['status' => 0, 'message' => 'Không tìm thấy phả hệ!'], 404);
        }

        $old = $phaHe->toArray();
        $phaHe->update($request->validated());
        $this->ghiLog($request, 'sua', $phaHe, $old, $phaHe->fresh()->toArray());

        return response()->json(['status' => 1, 'message' => 'Cập nhật phả hệ thành công!', 'data' => $phaHe]);
    }

    public function destroy(XoaPhaHeRequest $request)
    {
        if ($error = $this->checkAdmin()) {
            return $error;
        }

        $phaHe = PhaHe::find($request->id);
        if (! $phaHe) {
            return response()->json(['status' => 0, 'message' => 'Không tìm thấy phả hệ!'], 404);
        }

        $old = $phaHe->toArray();
        $phaHe->delete();
        $this->ghiLog($request, 'xoa', $phaHe, $old, null);

        return response()->json(['status' => 1, 'message' => 'Xóa phả hệ thành công!']);
    }

    private function tenNhanhGoc(string $tenPhaHe): string
    {
        $tenDongHo = trim(str_replace('Gia phả', '', $tenPhaHe));

        return 'Nhánh gốc'.($tenDongHo ? ' '.$tenDongHo : '');
    }

    private function ghiLog(Request $request, string $hanhDong, PhaHe $phaHe, ?array $cu, ?array $moi): void
    {
        NhatKyHoatDong::create([
            'id_nguoi_dung' => Auth::guard('sanctum')->id(),
            'hanh_dong' => $hanhDong,
            'ten_bang' => 'pha_hes',
            'id_ban_ghi' => $phaHe->id,
            'du_lieu_cu' => $cu,
            'du_lieu_moi' => $moi,
            'dia_chi_ip' => $request->ip(),
            'trinh_duyet' => $request->userAgent(),
        ]);
    }
}
