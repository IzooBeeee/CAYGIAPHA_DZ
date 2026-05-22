<?php

namespace App\Http\Controllers\Api\GiaPha;

use App\Http\Controllers\Controller;
use App\Http\Requests\GiaPha\LuuMoPhanRequest;
use App\Http\Requests\GiaPha\XoaMoPhanRequest;
use App\Models\MoPhan;
use App\Models\NhatKyHoatDong;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MoPhanController extends Controller
{
    public function getDataCongKhai()
    {
        return response()->json([
            'status' => 1,
            'data' => MoPhan::with('thanhVienGiaPha')->orderByDesc('id')->get(),
        ]);
    }

    public function getData()
    {
        if (! Auth::guard('sanctum')->user()) {
            return response()->json(['status' => 0, 'message' => 'Bạn cần đăng nhập hệ thống!'], 401);
        }

        return response()->json(['status' => 1, 'data' => MoPhan::with('thanhVienGiaPha')->orderByDesc('id')->get()]);
    }

    public function store(LuuMoPhanRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login || ! in_array($login->vai_tro, ['quan_tri_vien', 'truong_nhanh'], true)) {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thực hiện chức năng này!'], 403);
        }

        $data = $request->validated();
        if ($request->hasFile('hinh_anh')) {
            $data['hinh_anh'] = $request->file('hinh_anh')->store('mo-phan', 'public');
        }
        $moPhan = MoPhan::create($data);
        $this->ghiLog($request, 'them', $moPhan, null, $moPhan->toArray());

        return response()->json(['status' => 1, 'message' => 'Thêm mộ phần thành công!', 'data' => $moPhan]);
    }

    public function update(LuuMoPhanRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login || ! in_array($login->vai_tro, ['quan_tri_vien', 'truong_nhanh'], true)) {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thực hiện chức năng này!'], 403);
        }

        $moPhan = MoPhan::find($request->id);
        if (! $moPhan) {
            return response()->json(['status' => 0, 'message' => 'Không tìm thấy mộ phần!'], 404);
        }

        $old = $moPhan->toArray();
        $data = $request->validated();
        if ($request->hasFile('hinh_anh')) {
            $data['hinh_anh'] = $request->file('hinh_anh')->store('mo-phan', 'public');
        }
        $moPhan->update($data);
        $this->ghiLog($request, 'sua', $moPhan, $old, $moPhan->fresh()->toArray());

        return response()->json(['status' => 1, 'message' => 'Cập nhật mộ phần thành công!', 'data' => $moPhan]);
    }

    public function destroy(XoaMoPhanRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login || ! in_array($login->vai_tro, ['quan_tri_vien', 'truong_nhanh'], true)) {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thực hiện chức năng này!'], 403);
        }

        $moPhan = MoPhan::find($request->id);
        if (! $moPhan) {
            return response()->json(['status' => 0, 'message' => 'Không tìm thấy mộ phần!'], 404);
        }

        $old = $moPhan->toArray();
        $moPhan->delete();
        $this->ghiLog($request, 'xoa', $moPhan, $old, null);

        return response()->json(['status' => 1, 'message' => 'Xóa mộ phần thành công!']);
    }

    private function ghiLog(Request $request, string $hanhDong, MoPhan $moPhan, ?array $cu, ?array $moi): void
    {
        NhatKyHoatDong::create([
            'id_nguoi_dung' => Auth::guard('sanctum')->id(),
            'hanh_dong' => $hanhDong,
            'ten_bang' => 'mo_phans',
            'id_ban_ghi' => $moPhan->id,
            'du_lieu_cu' => $cu,
            'du_lieu_moi' => $moi,
            'dia_chi_ip' => $request->ip(),
            'trinh_duyet' => $request->userAgent(),
        ]);
    }
}
