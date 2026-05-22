<?php

namespace App\Http\Controllers\Api\GiaPha;

use App\Http\Controllers\Controller;
use App\Http\Requests\GiaPha\LuuBaiVietRequest;
use App\Http\Requests\GiaPha\XoaBaiVietRequest;
use App\Models\BaiViet;
use App\Models\NhatKyHoatDong;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BaiVietController extends Controller
{
    public function getData()
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login) {
            return response()->json(['status' => 0, 'message' => 'Bạn cần đăng nhập hệ thống!'], 401);
        }

        $query = BaiViet::with(['nguoiDung', 'nhanhHo'])->orderByDesc('id');
        if ($login->vai_tro === 'truong_nhanh') {
            $query->whereIn('id_nhanh_ho', $login->nhanhHosQuanLy()->pluck('id'));
        }

        return response()->json(['status' => 1, 'data' => $query->get()]);
    }

    public function getDataCongKhai()
    {
        return response()->json(['status' => 1, 'data' => BaiViet::where('trang_thai', 'cong_khai')->latest()->get()]);
    }

    public function store(LuuBaiVietRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login || ! in_array($login->vai_tro, ['quan_tri_vien', 'truong_nhanh'], true)) {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thực hiện chức năng này!'], 403);
        }

        // Kiểm tra trưởng nhánh chỉ thêm bài viết trong nhánh mình
        $nhanhHoId = $request->id_nhanh_ho;
        if ($login->vai_tro === 'truong_nhanh') {
            if (! $nhanhHoId || ! $login->nhanhHosQuanLy()->where('id', $nhanhHoId)->exists()) {
                return response()->json(['status' => 0, 'message' => 'Bạn không được thêm bài viết vào nhánh khác!'], 403);
            }
        }

        $data = $request->validated();
        $data['id_nguoi_dung'] = $login->id;
        $data['duong_dan'] = $data['duong_dan'] ?? Str::slug($data['tieu_de']);
        if ($request->hasFile('anh_dai_dien')) {
            $data['anh_dai_dien'] = $request->file('anh_dai_dien')->store('bai-viet', 'public');
        }
        $baiViet = BaiViet::create($data);
        $this->ghiLog($request, 'them', $baiViet, null, $baiViet->toArray());

        return response()->json(['status' => 1, 'message' => 'Thêm mới bài viết thành công!', 'data' => $baiViet]);
    }

    public function update(LuuBaiVietRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login || ! in_array($login->vai_tro, ['quan_tri_vien', 'truong_nhanh'], true)) {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thực hiện chức năng này!'], 403);
        }

        $baiViet = BaiViet::find($request->id);
        if (! $baiViet) {
            return response()->json(['status' => 0, 'message' => 'Không tìm thấy bài viết!'], 404);
        }

        // Kiểm tra trưởng nhánh chỉ sửa bài viết trong nhánh mình
        if ($login->vai_tro === 'truong_nhanh') {
            if (! $login->nhanhHosQuanLy()->where('id', $baiViet->id_nhanh_ho)->exists()) {
                return response()->json(['status' => 0, 'message' => 'Bạn không được sửa bài viết nhánh khác!'], 403);
            }
        }

        $old = $baiViet->toArray();
        $data = $request->validated();
        $data['duong_dan'] = $data['duong_dan'] ?? Str::slug($data['tieu_de']);
        if ($request->hasFile('anh_dai_dien')) {
            $data['anh_dai_dien'] = $request->file('anh_dai_dien')->store('bai-viet', 'public');
        }
        $baiViet->update($data);
        $this->ghiLog($request, 'sua', $baiViet, $old, $baiViet->fresh()->toArray());

        return response()->json(['status' => 1, 'message' => 'Cập nhật bài viết thành công!', 'data' => $baiViet]);
    }

    public function destroy(XoaBaiVietRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login || ! in_array($login->vai_tro, ['quan_tri_vien', 'truong_nhanh'], true)) {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thực hiện chức năng này!'], 403);
        }

        $baiViet = BaiViet::find($request->id);
        if (! $baiViet) {
            return response()->json(['status' => 0, 'message' => 'Không tìm thấy bài viết!'], 404);
        }

        // Kiểm tra trưởng nhánh chỉ xóa bài viết trong nhánh mình
        if ($login->vai_tro === 'truong_nhanh') {
            if (! $login->nhanhHosQuanLy()->where('id', $baiViet->id_nhanh_ho)->exists()) {
                return response()->json(['status' => 0, 'message' => 'Bạn không được xóa bài viết nhánh khác!'], 403);
            }
        }

        $old = $baiViet->toArray();
        $baiViet->delete();
        $this->ghiLog($request, 'xoa', $baiViet, $old, null);

        return response()->json(['status' => 1, 'message' => 'Xóa bài viết thành công!']);
    }

    private function ghiLog(Request $request, string $hanhDong, BaiViet $baiViet, ?array $cu, ?array $moi): void
    {
        NhatKyHoatDong::create([
            'id_nguoi_dung' => Auth::guard('sanctum')->id(),
            'hanh_dong' => $hanhDong,
            'ten_bang' => 'bai_viets',
            'id_ban_ghi' => $baiViet->id,
            'du_lieu_cu' => $cu,
            'du_lieu_moi' => $moi,
            'dia_chi_ip' => $request->ip(),
            'trinh_duyet' => $request->userAgent(),
        ]);
    }
}
