<?php

namespace App\Http\Middleware;

use App\Models\NguoiDung;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class QuanTriVienMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('sanctum')->user();

        if ($user && $user instanceof NguoiDung && $user->vai_tro === 'quan_tri_vien' && $user->trang_thai === 'hoat_dong') {
            return $next($request);
        }

        return response()->json([
            'message' => 'Bạn cần đăng nhập bằng tài khoản quản trị viên để thực hiện chức năng này',
        ], 401);
    }
}
