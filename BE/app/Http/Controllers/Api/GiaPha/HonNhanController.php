<?php

namespace App\Http\Controllers\Api\GiaPha;

use App\Http\Controllers\Controller;
use App\Http\Requests\GiaPha\LuuHonNhanRequest;
use App\Http\Requests\GiaPha\XoaHonNhanRequest;
use App\Models\HonNhan;
use App\Models\NhatKyHoatDong;
use App\Models\ThanhVienGiaPha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HonNhanController extends Controller
{
    public function getData()
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login) {
            return response()->json(['status' => 0, 'message' => 'Bạn cần đăng nhập hệ thống!'], 401);
        }

        $query = HonNhan::with(['chong.nhanhHo', 'vo.nhanhHo'])->orderByDesc('id');

        // Trưởng nhánh chỉ xem hôn nhân liên quan đến nhánh mình
        if ($login->vai_tro === 'truong_nhanh') {
            $idsNhanh = $login->nhanhHosQuanLy()->pluck('id');
            $query->where(function ($q) use ($idsNhanh) {
                $q->whereHas('chong', fn ($s) => $s->whereIn('id_nhanh_ho', $idsNhanh))
                    ->orWhereHas('vo', fn ($s) => $s->whereIn('id_nhanh_ho', $idsNhanh));
            });
        }

        return response()->json(['status' => 1, 'data' => $query->get()]);
    }

    public function store(LuuHonNhanRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login || ! in_array($login->vai_tro, ['quan_tri_vien', 'truong_nhanh'], true)) {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thực hiện chức năng này!'], 403);
        }

        // Trưởng nhánh: kiểm tra ít nhất một bên (chồng hoặc vợ) thuộc nhánh mình
        if ($login->vai_tro === 'truong_nhanh') {
            $this->kiemTraQuyenHonNhan($login, $request->id_chong, $request->id_vo);
            // Nếu không có quyền thì kiemTraQuyenHonNhan sẽ throw exception → trả 403
            if (! $this->coQuyenHonNhan($login, $request->id_chong, $request->id_vo)) {
                return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thêm hôn nhân này. Ít nhất một bên phải thuộc nhánh bạn quản lý!'], 403);
            }
        }

        $honNhan = HonNhan::create($request->validated());
        $honNhan->load(['chong.nhanhHo', 'vo.nhanhHo']);
        $this->ghiLog($request, 'them', $honNhan, null, $honNhan->toArray());

        return response()->json(['status' => 1, 'message' => 'Thêm quan hệ hôn nhân thành công!', 'data' => $honNhan]);
    }

    public function update(LuuHonNhanRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login || ! in_array($login->vai_tro, ['quan_tri_vien', 'truong_nhanh'], true)) {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thực hiện chức năng này!'], 403);
        }

        $honNhan = HonNhan::find($request->id);
        if (! $honNhan) {
            return response()->json(['status' => 0, 'message' => 'Không tìm thấy quan hệ hôn nhân!'], 404);
        }

        // Trưởng nhánh: kiểm tra qua vợ hoặc chồng
        if ($login->vai_tro === 'truong_nhanh') {
            if (! $this->coQuyenHonNhan($login, $honNhan->id_chong, $honNhan->id_vo)) {
                return response()->json(['status' => 0, 'message' => 'Bạn không có quyền sửa hôn nhân này!'], 403);
            }
        }

        $old = $honNhan->toArray();
        $honNhan->update($request->validated());
        $honNhan->load(['chong.nhanhHo', 'vo.nhanhHo']);
        $this->ghiLog($request, 'sua', $honNhan, $old, $honNhan->fresh()->toArray());

        return response()->json(['status' => 1, 'message' => 'Cập nhật hôn nhân thành công!', 'data' => $honNhan]);
    }

    public function destroy(XoaHonNhanRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login || ! in_array($login->vai_tro, ['quan_tri_vien', 'truong_nhanh'], true)) {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thực hiện chức năng này!'], 403);
        }

        $honNhan = HonNhan::find($request->id);
        if (! $honNhan) {
            return response()->json(['status' => 0, 'message' => 'Không tìm thấy quan hệ hôn nhân!'], 404);
        }

        // Trưởng nhánh: kiểm tra qua vợ hoặc chồng
        if ($login->vai_tro === 'truong_nhanh') {
            if (! $this->coQuyenHonNhan($login, $honNhan->id_chong, $honNhan->id_vo)) {
                return response()->json(['status' => 0, 'message' => 'Bạn không có quyền xóa hôn nhân này!'], 403);
            }
        }

        $old = $honNhan->toArray();
        $honNhan->delete();
        $this->ghiLog($request, 'xoa', $honNhan, $old, null);

        return response()->json(['status' => 1, 'message' => 'Xóa hôn nhân thành công!']);
    }

    /**
     * Kiểm tra trưởng nhánh có quyền với hôn nhân không.
     * Trưởng nhánh có quyền nếu ít nhất một bên (chồng hoặc vợ) thuộc nhánh mình.
     */
    private function coQuyenHonNhan($login, ?int $idChong, ?int $idVo): bool
    {
        $idsNhanh = $login->nhanhHosQuanLy()->pluck('id');

        if ($idChong) {
            $chong = ThanhVienGiaPha::find($idChong);
            if ($chong && $chong->id_nhanh_ho && $idsNhanh->contains($chong->id_nhanh_ho)) {
                return true;
            }
        }

        if ($idVo) {
            $vo = ThanhVienGiaPha::find($idVo);
            if ($vo && $vo->id_nhanh_ho && $idsNhanh->contains($vo->id_nhanh_ho)) {
                return true;
            }
        }

        return false;
    }

    private function ghiLog(Request $request, string $hanhDong, HonNhan $honNhan, ?array $cu, ?array $moi): void
    {
        NhatKyHoatDong::create([
            'id_nguoi_dung' => Auth::guard('sanctum')->id(),
            'hanh_dong' => $hanhDong,
            'ten_bang' => 'hon_nhans',
            'id_ban_ghi' => $honNhan->id,
            'du_lieu_cu' => $cu,
            'du_lieu_moi' => $moi,
            'dia_chi_ip' => $request->ip(),
            'trinh_duyet' => $request->userAgent(),
        ]);
    }
}
