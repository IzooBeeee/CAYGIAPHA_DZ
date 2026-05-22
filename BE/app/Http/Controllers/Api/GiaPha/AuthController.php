<?php

namespace App\Http\Controllers\Api\GiaPha;

use App\Http\Controllers\Controller;
use App\Http\Requests\GiaPha\DangKyRequest;
use App\Http\Requests\GiaPha\DangNhapRequest;
use App\Models\NguoiDung;
use App\Models\NhatKyHoatDong;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(DangKyRequest $request)
    {
        $nguoiDung = NguoiDung::create([
            'ho_ten' => $request->ho_ten,
            'email' => $request->email,
            'mat_khau' => $request->mat_khau,
            'vai_tro' => NguoiDung::count() === 0 ? 'quan_tri_vien' : 'thanh_vien',
            'trang_thai' => 'hoat_dong',
        ]);

        return response()->json([
            'status' => 1,
            'message' => 'Đăng ký thành công!',
            'token' => $nguoiDung->createToken('token_nguoi_dung')->plainTextToken,
            'data' => $nguoiDung,
        ]);
    }

    public function login(DangNhapRequest $request)
    {
        $nguoiDung = NguoiDung::where('email', $request->email)->first();

        if (! $nguoiDung || ! Hash::check($request->mat_khau, $nguoiDung->mat_khau)) {
            return response()->json([
                'status' => 0,
                'message' => 'Tài khoản hoặc mật khẩu không đúng.',
            ]);
        }

        if ($nguoiDung->trang_thai === 'bi_khoa') {
            return response()->json([
                'status' => 0,
                'message' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên!',
            ]);
        }

        NhatKyHoatDong::create([
            'id_nguoi_dung' => $nguoiDung->id,
            'hanh_dong' => 'dang_nhap',
            'ten_bang' => 'nguoi_dungs',
            'id_ban_ghi' => $nguoiDung->id,
            'dia_chi_ip' => $request->ip(),
            'trinh_duyet' => $request->userAgent(),
        ]);

        return response()->json([
            'status' => 1,
            'message' => 'Đăng nhập thành công!',
            'token' => $nguoiDung->createToken('token_nguoi_dung')->plainTextToken,
            'data' => $nguoiDung,
        ]);
    }

    public function checkToken()
    {
        $login = Auth::guard('sanctum')->user();

        if ($login) {
            return response()->json([
                'status' => 1,
                'ho_ten' => $login->ho_ten,
                'email' => $login->email,
                'vai_tro' => $login->vai_tro,
            ]);
        }

        return response()->json([
            'status' => 0,
            'message' => 'Bạn cần đăng nhập hệ thống!',
        ], 401);
    }

    public function profile()
    {
        $login = Auth::guard('sanctum')->user();

        if (! $login) {
            return response()->json([
                'status' => 0,
                'message' => 'Bạn cần đăng nhập hệ thống!',
            ], 401);
        }

        return response()->json([
            'status' => 1,
            'data' => $login->load('thanhVienGiaPha', 'nhanhHosQuanLy'),
        ]);
    }

    public function dangXuat()
    {
        $login = Auth::guard('sanctum')->user();

        if ($login && $login->currentAccessToken()) {
            $login->currentAccessToken()->delete();

            return response()->json([
                'status' => 1,
                'message' => 'Đăng xuất thành công!',
            ]);
        }

        return response()->json([
            'status' => 0,
            'message' => 'Có lỗi xảy ra.',
        ]);
    }
}
