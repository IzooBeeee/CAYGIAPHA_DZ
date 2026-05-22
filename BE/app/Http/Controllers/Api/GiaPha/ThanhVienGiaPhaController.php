<?php

namespace App\Http\Controllers\Api\GiaPha;

use App\Http\Controllers\Controller;
use App\Http\Requests\GiaPha\KhoiPhucThanhVienGiaPhaRequest;
use App\Http\Requests\GiaPha\LuuThanhVienGiaPhaRequest;
use App\Http\Requests\GiaPha\XoaThanhVienGiaPhaRequest;
use App\Models\NhatKyHoatDong;
use App\Models\ThanhVienGiaPha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThanhVienGiaPhaController extends Controller
{
    public function getData(Request $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login) {
            return response()->json(['status' => 0, 'message' => 'Bạn cần đăng nhập hệ thống!'], 401);
        }

        $query = ThanhVienGiaPha::with(['nhanhHo.phaHe', 'cha', 'me', 'honNhanChong.vo', 'honNhanVo.chong', 'moPhan']);

        if ($login->vai_tro === 'truong_nhanh') {
            $query->whereIn('id_nhanh_ho', $login->nhanhHosQuanLy()->pluck('id'));
        }

        if ($request->id_nhanh_ho) {
            $query->where('id_nhanh_ho', $request->id_nhanh_ho);
        }

        if ($request->id_pha_he) {
            $query->whereHas('nhanhHo', function ($nhanhHoQuery) use ($request) {
                $nhanhHoQuery->where('id_pha_he', $request->id_pha_he);
            });
        }

        return response()->json(['status' => 1, 'data' => $query->orderBy('doi_thu')->orderBy('ho_ten')->get()]);
    }

    public function showCongKhai($id)
    {
        $thanhVien = ThanhVienGiaPha::with(['nhanhHo', 'cha', 'me'])->find($id);

        if (! $thanhVien) {
            return response()->json(['status' => 0, 'message' => 'Không tìm thấy thành viên!'], 404);
        }

        return response()->json(['status' => 1, 'data' => $thanhVien]);
    }

    public function store(LuuThanhVienGiaPhaRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login || $login->vai_tro !== 'quan_tri_vien') {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thực hiện chức năng này!'], 403);
        }

        $data = $request->validated();
        if ($request->hasFile('anh_dai_dien')) {
            $data['anh_dai_dien'] = $request->file('anh_dai_dien')->store('thanh-vien-gia-pha', 'public');
        }
        $data['con_song'] = $request->boolean('con_song', true);
        $data['id_nguoi_tao'] = $login->id;

        $thanhVien = ThanhVienGiaPha::create($data);
        $thanhVien->load(['nhanhHo.phaHe', 'cha', 'me']);
        $this->ghiLog($request, 'them', $thanhVien, null, $thanhVien->toArray());

        return response()->json(['status' => 1, 'message' => 'Thêm mới thành viên gia phả thành công!', 'data' => $thanhVien]);
    }

    public function update(LuuThanhVienGiaPhaRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login || $login->vai_tro !== 'quan_tri_vien') {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thực hiện chức năng này!'], 403);
        }

        $thanhVien = ThanhVienGiaPha::find($request->id);
        if (! $thanhVien) {
            return response()->json(['status' => 0, 'message' => 'Không tìm thấy thành viên!'], 404);
        }

        if ($this->kiemTraQuanHeVongLap($thanhVien->id, $request->id_cha, $request->id_me)) {
            return response()->json(['status' => 0, 'message' => 'Không được tạo quan hệ vòng lặp cha con!']);
        }

        $old = $thanhVien->toArray();
        $data = $request->validated();
        if ($request->hasFile('anh_dai_dien')) {
            $data['anh_dai_dien'] = $request->file('anh_dai_dien')->store('thanh-vien-gia-pha', 'public');
        }
        $data['con_song'] = $request->boolean('con_song', true);
        $data['id_nguoi_cap_nhat'] = $login->id;
        $thanhVien->update($data);
        $thanhVien->load(['nhanhHo.phaHe', 'cha', 'me']);
        $this->ghiLog($request, 'sua', $thanhVien, $old, $thanhVien->fresh()->toArray());

        return response()->json(['status' => 1, 'message' => 'Cập nhật thành viên gia phả thành công!', 'data' => $thanhVien]);
    }

    public function destroy(XoaThanhVienGiaPhaRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login || $login->vai_tro !== 'quan_tri_vien') {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thực hiện chức năng này!'], 403);
        }

        $thanhVien = ThanhVienGiaPha::find($request->id);
        if (! $thanhVien) {
            return response()->json(['status' => 0, 'message' => 'Không tìm thấy thành viên!'], 404);
        }

        $old = $thanhVien->toArray();
        $thanhVien->delete();
        $this->ghiLog($request, 'xoa', $thanhVien, $old, null);

        return response()->json(['status' => 1, 'message' => 'Xóa mềm thành viên thành công!']);
    }

    public function restore(KhoiPhucThanhVienGiaPhaRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login || $login->vai_tro !== 'quan_tri_vien') {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thực hiện chức năng này!'], 403);
        }

        $thanhVien = ThanhVienGiaPha::withTrashed()->find($request->id);
        if (! $thanhVien) {
            return response()->json(['status' => 0, 'message' => 'Không tìm thấy thành viên!'], 404);
        }

        $thanhVien->restore();
        $this->ghiLog($request, 'khoi_phuc', $thanhVien, null, $thanhVien->toArray());

        return response()->json(['status' => 1, 'message' => 'Khôi phục thành viên thành công!', 'data' => $thanhVien]);
    }

    // Trưởng nhánh - Thêm thành viên trong nhánh
    public function storeTruongNhanh(LuuThanhVienGiaPhaRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login || ! in_array($login->vai_tro, ['truong_nhanh', 'quan_tri_vien'], true)) {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thực hiện chức năng này!'], 403);
        }

        $nhanhHoId = $request->id_nhanh_ho;
        if ($login->vai_tro === 'truong_nhanh') {
            if (! $nhanhHoId || ! $login->nhanhHosQuanLy()->where('id', $nhanhHoId)->exists()) {
                return response()->json(['status' => 0, 'message' => 'Bạn không được thêm thành viên vào nhánh khác!'], 403);
            }
        }

        $data = $request->validated();
        if ($request->hasFile('anh_dai_dien')) {
            $data['anh_dai_dien'] = $request->file('anh_dai_dien')->store('thanh-vien-gia-pha', 'public');
        }
        $data['con_song'] = $request->boolean('con_song', true);
        $data['id_nguoi_tao'] = $login->id;

        $thanhVien = ThanhVienGiaPha::create($data);
        $thanhVien->load(['nhanhHo.phaHe', 'cha', 'me']);
        $this->ghiLog($request, 'them', $thanhVien, null, $thanhVien->toArray());

        return response()->json(['status' => 1, 'message' => 'Thêm mới thành viên gia phả thành công!', 'data' => $thanhVien]);
    }

    // Trưởng nhánh - Cập nhật thành viên trong nhánh
    public function updateTruongNhanh(LuuThanhVienGiaPhaRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login || ! in_array($login->vai_tro, ['truong_nhanh', 'quan_tri_vien'], true)) {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thực hiện chức năng này!'], 403);
        }

        $thanhVien = ThanhVienGiaPha::find($request->id);
        if (! $thanhVien) {
            return response()->json(['status' => 0, 'message' => 'Không tìm thấy thành viên!'], 404);
        }

        if ($login->vai_tro === 'truong_nhanh') {
            if (! $login->nhanhHosQuanLy()->where('id', $thanhVien->id_nhanh_ho)->exists()) {
                return response()->json(['status' => 0, 'message' => 'Bạn không được sửa thành viên nhánh khác!'], 403);
            }
        }

        if ($this->kiemTraQuanHeVongLap($thanhVien->id, $request->id_cha, $request->id_me)) {
            return response()->json(['status' => 0, 'message' => 'Không được tạo quan hệ vòng lặp cha con!']);
        }

        $old = $thanhVien->toArray();
        $data = $request->validated();
        if ($request->hasFile('anh_dai_dien')) {
            $data['anh_dai_dien'] = $request->file('anh_dai_dien')->store('thanh-vien-gia-pha', 'public');
        }
        $data['con_song'] = $request->boolean('con_song', true);
        $data['id_nguoi_cap_nhat'] = $login->id;
        $thanhVien->update($data);
        $thanhVien->load(['nhanhHo.phaHe', 'cha', 'me']);
        $this->ghiLog($request, 'sua', $thanhVien, $old, $thanhVien->fresh()->toArray());

        return response()->json(['status' => 1, 'message' => 'Cập nhật thành viên gia phả thành công!', 'data' => $thanhVien]);
    }

    // Trưởng nhánh - Xóa thành viên trong nhánh
    public function destroyTruongNhanh(XoaThanhVienGiaPhaRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login || ! in_array($login->vai_tro, ['truong_nhanh', 'quan_tri_vien'], true)) {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thực hiện chức năng này!'], 403);
        }

        $thanhVien = ThanhVienGiaPha::find($request->id);
        if (! $thanhVien) {
            return response()->json(['status' => 0, 'message' => 'Không tìm thấy thành viên!'], 404);
        }

        if ($login->vai_tro === 'truong_nhanh') {
            if (! $login->nhanhHosQuanLy()->where('id', $thanhVien->id_nhanh_ho)->exists()) {
                return response()->json(['status' => 0, 'message' => 'Bạn không được xóa thành viên nhánh khác!'], 403);
            }
        }

        $old = $thanhVien->toArray();
        $thanhVien->delete();
        $this->ghiLog($request, 'xoa', $thanhVien, $old, null);

        return response()->json(['status' => 1, 'message' => 'Xóa mềm thành viên thành công!']);
    }

    // Thành viên - Cập nhật thông tin cá nhân
    public function updateCaNhan(Request $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login) {
            return response()->json(['status' => 0, 'message' => 'Bạn cần đăng nhập hệ thống!'], 401);
        }

        if (! $login->id_thanh_vien_gia_pha) {
            return response()->json(['status' => 0, 'message' => 'Tài khoản chưa liên kết với thành viên gia phả!'], 403);
        }

        $thanhVien = ThanhVienGiaPha::find($login->id_thanh_vien_gia_pha);
        if (! $thanhVien) {
            return response()->json(['status' => 0, 'message' => 'Không tìm thấy thành viên!'], 404);
        }

        $request->validate([
            'ho_ten' => 'sometimes|required|string|max:255',
            'ngay_sinh' => 'nullable|date',
            'ngay_mat' => 'nullable|date|after_or_equal:ngay_sinh',
            'noi_sinh' => 'nullable|string|max:255',
            'que_quan' => 'nullable|string|max:255',
            'dia_chi_hien_tai' => 'nullable|string|max:255',
            'so_dien_thoai' => 'nullable|string|max:30',
            'tieu_su' => 'nullable|string',
            'ghi_chu' => 'nullable|string',
            'anh_dai_dien' => 'nullable|image|max:2048',
        ]);

        $old = $thanhVien->toArray();
        $data = $request->only([
            'ho_ten', 'ngay_sinh', 'ngay_mat', 'noi_sinh', 'que_quan',
            'dia_chi_hien_tai', 'so_dien_thoai', 'tieu_su', 'ghi_chu'
        ]);

        if ($request->hasFile('anh_dai_dien')) {
            $data['anh_dai_dien'] = $request->file('anh_dai_dien')->store('thanh-vien-gia-pha', 'public');
        }

        $data['id_nguoi_cap_nhat'] = $login->id;
        $thanhVien->update($data);
        $this->ghiLog($request, 'sua_ca_nhan', $thanhVien, $old, $thanhVien->fresh()->toArray());

        return response()->json(['status' => 1, 'message' => 'Cập nhật thông tin cá nhân thành công!', 'data' => $thanhVien]);
    }

    private function kiemTraQuanHeVongLap(int $idThanhVien, ?int $idChaMoi, ?int $idMeMoi): bool
    {
        if (! $idChaMoi && ! $idMeMoi) {
            return false;
        }

        $hauDue = $this->layHauDueIds($idThanhVien);

        return in_array($idChaMoi, $hauDue, true) || in_array($idMeMoi, $hauDue, true);
    }

    private function layHauDueIds(int $idThanhVien): array
    {
        $ids = ThanhVienGiaPha::where('id_cha', $idThanhVien)->orWhere('id_me', $idThanhVien)->pluck('id')->all();
        foreach ($ids as $id) {
            $ids = array_merge($ids, $this->layHauDueIds($id));
        }

        return array_unique($ids);
    }

    private function ghiLog(Request $request, string $hanhDong, ThanhVienGiaPha $thanhVien, ?array $cu, ?array $moi): void
    {
        NhatKyHoatDong::create([
            'id_nguoi_dung' => Auth::guard('sanctum')->id(),
            'hanh_dong' => $hanhDong,
            'ten_bang' => 'thanh_vien_gia_phas',
            'id_ban_ghi' => $thanhVien->id,
            'du_lieu_cu' => $cu,
            'du_lieu_moi' => $moi,
            'dia_chi_ip' => $request->ip(),
            'trinh_duyet' => $request->userAgent(),
        ]);
    }
}
