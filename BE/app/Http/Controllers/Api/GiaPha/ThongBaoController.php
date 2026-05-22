<?php

namespace App\Http\Controllers\Api\GiaPha;

use App\Http\Controllers\Controller;
use App\Http\Requests\GiaPha\DanhDauThongBaoRequest;
use App\Models\ThongBao;
use Illuminate\Support\Facades\Auth;

class ThongBaoController extends Controller
{
    public function getData()
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login) {
            return response()->json(['status' => 0, 'message' => 'Bạn cần đăng nhập hệ thống!'], 401);
        }

        $query = ThongBao::with(['nguoiGui', 'nguoiNhan', 'nhanhHo'])->orderByDesc('id');
        if ($login->vai_tro !== 'quan_tri_vien') {
            $query->where('id_nguoi_nhan', $login->id);
        }

        return response()->json(['status' => 1, 'data' => $query->get()]);
    }

    public function danhDauDaDoc(DanhDauThongBaoRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login) {
            return response()->json(['status' => 0, 'message' => 'Bạn cần đăng nhập hệ thống!'], 401);
        }

        $thongBao = ThongBao::find($request->id);
        if ($thongBao->id_nguoi_nhan !== $login->id && $login->vai_tro !== 'quan_tri_vien') {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thực hiện chức năng này!'], 403);
        }
        $thongBao->update(['da_doc' => true]);

        return response()->json(['status' => 1, 'message' => 'Đã đánh dấu thông báo đã đọc!']);
    }
}
