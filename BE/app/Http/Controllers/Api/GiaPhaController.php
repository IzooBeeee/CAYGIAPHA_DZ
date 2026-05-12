<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ThanhVien;
use Illuminate\Http\Request;

class GiaPhaController extends Controller
{
    public function getData()
    {
        $data = ThanhVien::all();
        return response()->json([
            'status' => true,
            'data'   => $data,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        // Xử lý logic cơ bản
        $thanhVien = ThanhVien::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Đã thêm thành viên mới thành công!',
        ]);
    }

    public function update(Request $request)
    {
        $thanhVien = ThanhVien::find($request->id);
        if ($thanhVien) {
            $thanhVien->update($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Đã cập nhật thông tin thành công!',
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Không tìm thấy thành viên!',
        ], 404);
    }

    public function destroy($id)
    {
        $thanhVien = ThanhVien::find($id);
        if ($thanhVien) {
            $thanhVien->delete();
            return response()->json([
                'status' => true,
                'message' => 'Đã xóa thành viên!',
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Không tìm thấy thành viên!',
        ], 404);
    }
}
