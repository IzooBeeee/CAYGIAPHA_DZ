<?php

namespace App\Http\Controllers\Api\GiaPha;

use App\Http\Controllers\Controller;
use App\Http\Requests\GiaPha\LuuHonNhanRequest;
use App\Http\Requests\GiaPha\XoaHonNhanRequest;
use App\Models\HonNhan;
use App\Models\NhatKyHoatDong;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HonNhanController extends Controller
{
    public function getData()
    {
        if (! Auth::guard('sanctum')->user()) {
            return response()->json(['status' => 0, 'message' => 'Bạn cần đăng nhập hệ thống!'], 401);
        }

        return response()->json(['status' => 1, 'data' => HonNhan::with(['chong', 'vo'])->orderByDesc('id')->get()]);
    }

    public function store(LuuHonNhanRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login || ! in_array($login->vai_tro, ['quan_tri_vien', 'truong_nhanh'], true)) {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thực hiện chức năng này!'], 403);
        }

        // Kiểm tra trưởng nhánh có quyền với nhánh của vợ/chồng không
        if ($login->vai_tro === 'truong_nhanh') {
            $honNhanData = $request->validated();
            $nhanhHoIds = [];
            if (! empty($honNhanData['id_chong'])) {
                $thanhVien = \App\Models\ThanhVienGiaPha::find($honNhanData['id_chong']);
                if ($thanhVien && $thanhVien->id_nhanh_ho) {
                    $nhanhHoIds[] = $thanhVien->id_nhanh_ho;
                }
            }
            if (! empty($honNhanData['id_vo'])) {
                $thanhVien = \App\Models\ThanhVienGiaPha::find($honNhanData['id_vo']);
                if ($thanhVien && $thanhVien->id_nhanh_ho) {
                    $nhanhHoIds[] = $thanhVien->id_nhanh_ho;
                }
            }
            if (! empty($honNhanData['id_nhanh_ho'])) {
                $nhanhHoIds[] = $honNhanData['id_nhanh_ho'];
            }
            $nhanhHoIds = array_unique($nhanhHoIds);
            foreach ($nhanhHoIds as $nhanhHoId) {
                if (! $login->nhanhHosQuanLy()->where('id', $nhanhHoId)->exists()) {
                    return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thêm hôn nhân trong nhánh này!'], 403);
                }
            }
        }

        $honNhan = HonNhan::create($request->validated());
        $honNhan->load(['chong', 'vo']);
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

        // Kiểm tra trưởng nhánh có quyền
        if ($login->vai_tro === 'truong_nhanh') {
            $nhanhHoIds = [];
            $chong = $honNhan->chong;
            $vo = $honNhan->vo;
            if ($chong && $chong->id_nhanh_ho) {
                $nhanhHoIds[] = $chong->id_nhanh_ho;
            }
            if ($vo && $vo->id_nhanh_ho) {
                $nhanhHoIds[] = $vo->id_nhanh_ho;
            }
            if ($honNhan->id_nhanh_ho) {
                $nhanhHoIds[] = $honNhan->id_nhanh_ho;
            }
            $nhanhHoIds = array_unique($nhanhHoIds);
            foreach ($nhanhHoIds as $nhanhHoId) {
                if (! $login->nhanhHosQuanLy()->where('id', $nhanhHoId)->exists()) {
                    return response()->json(['status' => 0, 'message' => 'Bạn không có quyền sửa hôn nhân trong nhánh này!'], 403);
                }
            }
        }

        $old = $honNhan->toArray();
        $honNhan->update($request->validated());
        $honNhan->load(['chong', 'vo']);
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

        // Kiểm tra trưởng nhánh có quyền
        if ($login->vai_tro === 'truong_nhanh') {
            $nhanhHoIds = [];
            $chong = $honNhan->chong;
            $vo = $honNhan->vo;
            if ($chong && $chong->id_nhanh_ho) {
                $nhanhHoIds[] = $chong->id_nhanh_ho;
            }
            if ($vo && $vo->id_nhanh_ho) {
                $nhanhHoIds[] = $vo->id_nhanh_ho;
            }
            if ($honNhan->id_nhanh_ho) {
                $nhanhHoIds[] = $honNhan->id_nhanh_ho;
            }
            $nhanhHoIds = array_unique($nhanhHoIds);
            foreach ($nhanhHoIds as $nhanhHoId) {
                if (! $login->nhanhHosQuanLy()->where('id', $nhanhHoId)->exists()) {
                    return response()->json(['status' => 0, 'message' => 'Bạn không có quyền xóa hôn nhân trong nhánh này!'], 403);
                }
            }
        }

        $old = $honNhan->toArray();
        $honNhan->delete();
        $this->ghiLog($request, 'xoa', $honNhan, $old, null);

        return response()->json(['status' => 1, 'message' => 'Xóa hôn nhân thành công!']);
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
