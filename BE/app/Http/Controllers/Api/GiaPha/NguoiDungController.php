<?php

namespace App\Http\Controllers\Api\GiaPha;

use App\Http\Controllers\Controller;
use App\Http\Requests\GiaPha\ChangeStatusNguoiDungRequest;
use App\Http\Requests\GiaPha\LuuNguoiDungRequest;
use App\Http\Requests\GiaPha\XoaNguoiDungRequest;
use App\Models\NguoiDung;
use App\Models\NhatKyHoatDong;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NguoiDungController extends Controller
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
        if ($error = $this->checkAdmin()) {
            return $error;
        }

        return response()->json([
            'status' => 1,
            'data' => NguoiDung::with('thanhVienGiaPha')->orderByDesc('id')->get(),
        ]);
    }

    public function search(Request $request)
    {
        if ($error = $this->checkAdmin()) {
            return $error;
        }

        $noiDungTim = '%'.$request->noi_dung_tim.'%';

        return response()->json([
            'status' => 1,
            'data' => NguoiDung::where('ho_ten', 'like', $noiDungTim)->orWhere('email', 'like', $noiDungTim)->get(),
        ]);
    }

    public function store(LuuNguoiDungRequest $request)
    {
        if ($error = $this->checkAdmin()) {
            return $error;
        }

        $data = $request->validated();
        $nguoiDung = NguoiDung::create($data);
        $this->ghiLog($request, 'them', $nguoiDung, null, $nguoiDung->toArray());

        return response()->json(['status' => 1, 'message' => 'Thêm mới tài khoản thành công!', 'data' => $nguoiDung]);
    }

    public function update(LuuNguoiDungRequest $request)
    {
        if ($error = $this->checkAdmin()) {
            return $error;
        }

        $nguoiDung = NguoiDung::find($request->id);
        if (! $nguoiDung) {
            return response()->json(['status' => 0, 'message' => 'Không tìm thấy tài khoản!'], 404);
        }

        $old = $nguoiDung->toArray();
        $data = $request->validated();

        if (blank($data['mat_khau'] ?? null)) {
            unset($data['mat_khau']);
        }

        $nguoiDung->update($data);
        $this->ghiLog($request, 'sua', $nguoiDung, $old, $nguoiDung->fresh()->toArray());

        return response()->json(['status' => 1, 'message' => 'Cập nhật tài khoản thành công!', 'data' => $nguoiDung]);
    }

    public function destroy(XoaNguoiDungRequest $request)
    {
        if ($error = $this->checkAdmin()) {
            return $error;
        }

        $nguoiDung = NguoiDung::find($request->id);
        if (! $nguoiDung) {
            return response()->json(['status' => 0, 'message' => 'Không tìm thấy tài khoản!'], 404);
        }

        if ((int) $request->id === (int) Auth::guard('sanctum')->id()) {
            return response()->json(['status' => 0, 'message' => 'Không được xóa tài khoản đang đăng nhập!']);
        }

        $old = $nguoiDung->toArray();
        $nguoiDung->delete();
        $this->ghiLog($request, 'xoa', $nguoiDung, $old, null);

        return response()->json(['status' => 1, 'message' => 'Xóa tài khoản thành công!']);
    }

    public function changeStatus(ChangeStatusNguoiDungRequest $request)
    {
        if ($error = $this->checkAdmin()) {
            return $error;
        }

        $nguoiDung = NguoiDung::find($request->id);
        if (! $nguoiDung) {
            return response()->json(['status' => 0, 'message' => 'Không tìm thấy tài khoản!'], 404);
        }

        $old = $nguoiDung->toArray();
        $nguoiDung->trang_thai = $nguoiDung->trang_thai === 'hoat_dong' ? 'bi_khoa' : 'hoat_dong';
        $nguoiDung->save();
        $this->ghiLog($request, 'doi_trang_thai', $nguoiDung, $old, $nguoiDung->toArray());

        return response()->json(['status' => 1, 'message' => 'Cập nhật trạng thái tài khoản thành công!', 'data' => $nguoiDung]);
    }

    private function ghiLog(Request $request, string $hanhDong, NguoiDung $nguoiDung, ?array $cu, ?array $moi): void
    {
        NhatKyHoatDong::create([
            'id_nguoi_dung' => Auth::guard('sanctum')->id(),
            'hanh_dong' => $hanhDong,
            'ten_bang' => 'nguoi_dungs',
            'id_ban_ghi' => $nguoiDung->id,
            'du_lieu_cu' => $cu,
            'du_lieu_moi' => $moi,
            'dia_chi_ip' => $request->ip(),
            'trinh_duyet' => $request->userAgent(),
        ]);
    }
}
