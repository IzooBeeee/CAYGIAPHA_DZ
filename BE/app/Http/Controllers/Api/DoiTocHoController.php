<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DoiTocHo;
use Illuminate\Http\Request;
use Exception;

class DoiTocHoController extends Controller
{
    public function getData()
    {
        try {
            $data = DoiTocHo::all();
            return response()->json([
                'status'  => true,
                'message' => 'Lấy dữ liệu thành công!',
                'data'    => $data,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $data = $request->all();
            if ('DoiTocHo' === 'NguoiDung' && isset($data['mat_khau'])) {
                $data['mat_khau'] = bcrypt($data['mat_khau']);
            }
            $item = DoiTocHo::create($data);
            return response()->json([
                'status'  => true,
                'message' => 'Tạo mới thành công!',
                'data'    => $item
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Lỗi khi tạo mới: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $item = DoiTocHo::findOrFail($request->id);
            $data = $request->all();
            if ('DoiTocHo' === 'NguoiDung' && isset($data['mat_khau'])) {
                $data['mat_khau'] = bcrypt($data['mat_khau']);
            }
            $item->update($data);
            return response()->json([
                'status'  => true,
                'message' => 'Cập nhật thành công!',
                'data'    => $item
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Lỗi khi cập nhật: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $item = DoiTocHo::findOrFail($request->id);
            $item->delete();
            return response()->json([
                'status'  => true,
                'message' => 'Xóa thành công!',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Lỗi khi xóa: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function status(Request $request)
    {
        try {
            $item = DoiTocHo::findOrFail($request->id);
            
            if ('DoiTocHo' === 'ThanhVien') {
                $item->trang_thai = $item->trang_thai == 'Còn sống' ? 'Đã mất' : 'Còn sống';
            } elseif (isset($item->trang_thai)) {
                $item->trang_thai = $item->trang_thai == 'Hoạt động' ? 'Khóa' : 'Hoạt động';
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Model này không hỗ trợ trạng thái!',
                ]);
            }
            
            $item->save();
            return response()->json([
                'status'  => true,
                'message' => 'Cập nhật trạng thái thành công!',
                'trang_thai' => $item->trang_thai
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Lỗi cập nhật trạng thái: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function search(Request $request)
    {
        try {
            $query = $request->value;
            $data = DoiTocHo::where('ten_doi', 'like', '%' . $query . '%')->get();
            return response()->json([
                'status'  => true,
                'message' => 'Tìm thấy ' . count($data) . ' kết quả',
                'data'    => $data,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Lỗi khi tìm kiếm: ' . $e->getMessage(),
            ], 500);
        }
    }
}
