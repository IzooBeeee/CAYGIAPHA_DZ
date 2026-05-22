<?php

namespace App\Http\Controllers\Api\GiaPha;

use App\Http\Controllers\Controller;
use App\Http\Requests\GiaPha\LuuBinhLuanRequest;
use App\Http\Requests\GiaPha\XoaBinhLuanRequest;
use App\Models\BinhLuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BinhLuanController extends Controller
{
    public function getData(Request $request)
    {
        if (! Auth::guard('sanctum')->user()) {
            return response()->json(['status' => 0, 'message' => 'Ban can dang nhap he thong!'], 401);
        }

        $query = BinhLuan::with(['nguoiDung', 'baiViet'])->orderByDesc('id');
        if ($request->id_bai_viet) {
            $query->where('id_bai_viet', $request->id_bai_viet);
        }

        return response()->json(['status' => 1, 'data' => $query->get()]);
    }

    public function store(LuuBinhLuanRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login) {
            return response()->json(['status' => 0, 'message' => 'Ban can dang nhap he thong!'], 401);
        }

        $data = $request->validated();
        $data['id_nguoi_dung'] = $login->id;
        $binhLuan = BinhLuan::create($data);

        return response()->json(['status' => 1, 'message' => 'Gui binh luan thanh cong!', 'data' => $binhLuan]);
    }

    public function destroy(XoaBinhLuanRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login) {
            return response()->json(['status' => 0, 'message' => 'Ban can dang nhap he thong!'], 401);
        }

        $binhLuan = BinhLuan::find($request->id);
        if ($binhLuan->id_nguoi_dung !== $login->id && $login->vai_tro !== 'quan_tri_vien') {
            return response()->json(['status' => 0, 'message' => 'Ban khong co quyen xoa binh luan nay!'], 403);
        }

        $binhLuan->delete();

        return response()->json(['status' => 1, 'message' => 'Xoa binh luan thanh cong!']);
    }
}
