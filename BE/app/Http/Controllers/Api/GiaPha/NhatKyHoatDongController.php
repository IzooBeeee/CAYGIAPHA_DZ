<?php

namespace App\Http\Controllers\Api\GiaPha;

use App\Http\Controllers\Controller;
use App\Models\NhatKyHoatDong;
use Illuminate\Support\Facades\Auth;

class NhatKyHoatDongController extends Controller
{
    public function getData()
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login || $login->vai_tro !== 'quan_tri_vien') {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thực hiện chức năng này!'], 403);
        }

        return response()->json([
            'status' => 1,
            'data' => NhatKyHoatDong::with('nguoiDung')->orderByDesc('id')->get(),
        ]);
    }
}
