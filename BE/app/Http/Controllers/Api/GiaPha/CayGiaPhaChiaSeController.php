<?php

namespace App\Http\Controllers\Api\GiaPha;

use App\Http\Controllers\Controller;
use App\Http\Requests\GiaPha\LuuCayGiaPhaChiaSeRequest;
use App\Http\Requests\GiaPha\XoaCayGiaPhaChiaSeRequest;
use App\Models\CayGiaPhaChiaSe;
use App\Models\NhatKyHoatDong;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CayGiaPhaChiaSeController extends Controller
{
    public function getData()
    {
        if (! Auth::guard('sanctum')->user()) {
            return response()->json(['status' => 0, 'message' => 'Bạn cần đăng nhập hệ thống!'], 401);
        }

        $query = CayGiaPhaChiaSe::with(['nguoiTao', 'nhanhHo'])->orderByDesc('id');
        $login = Auth::guard('sanctum')->user();
        if ($login->vai_tro !== 'quan_tri_vien') {
            $query->where('id_nguoi_tao', $login->id);
        }

        return response()->json(['status' => 1, 'data' => $query->get()]);
    }

    public function store(LuuCayGiaPhaChiaSeRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login || ! in_array($login->vai_tro, ['quan_tri_vien', 'truong_nhanh'], true)) {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thực hiện chức năng này!'], 403);
        }

        $data = $request->validated();
        $data['id_nguoi_tao'] = $login->id;
        $data['ma_chia_se'] = Str::random(16);
        if (! empty($data['mat_khau'])) {
            $data['mat_khau'] = bcrypt($data['mat_khau']);
        } else {
            unset($data['mat_khau']);
        }
        $chiaSe = CayGiaPhaChiaSe::create($data);
        $this->ghiLog($request, 'them', $chiaSe, null, $chiaSe->toArray());

        return response()->json(['status' => 1, 'message' => 'Tạo link chia sẻ thành công!', 'data' => $chiaSe]);
    }

    public function hienThi(Request $request, string $ma)
    {
        $chiaSe = CayGiaPhaChiaSe::with('nhanhHo')->where('ma_chia_se', $ma)->first();

        if (! $chiaSe) {
            return response()->json(['status' => 0, 'message' => 'Link chia sẻ không tồn tại!'], 404);
        }

        if ($chiaSe->ngay_het_han && $chiaSe->ngay_het_han->isPast()) {
            return response()->json(['status' => 0, 'message' => 'Link chia sẻ đã hết hạn!'], 410);
        }

        if ($chiaSe->pham_vi === 'co_mat_khau') {
            if (! $request->mat_khau || ! password_verify($request->mat_khau, $chiaSe->mat_khau)) {
                return response()->json(['status' => 0, 'message' => 'Vui lòng nhập mật khẩu!'], 403);
            }
        }

        if ($chiaSe->pham_vi === 'rieng_tu') {
            if (! Auth::guard('sanctum')->check()) {
                return response()->json(['status' => 0, 'message' => 'Bạn cần đăng nhập để xem nội dung này!'], 401);
            }
        }

        return response()->json([
            'status' => 1,
            'data' => [
                'nhanh_ho' => $chiaSe->nhanhHo,
                'cay_gia_pha' => app(CayGiaPhaController::class)->layCayGiaPha($chiaSe->id_nhanh_ho),
                'pham_vi' => $chiaSe->pham_vi,
            ],
        ]);
    }

    public function destroy(XoaCayGiaPhaChiaSeRequest $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login || ! in_array($login->vai_tro, ['quan_tri_vien', 'truong_nhanh'], true)) {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền thực hiện chức năng này!'], 403);
        }

        $chiaSe = CayGiaPhaChiaSe::find($request->id);
        if (! $chiaSe) {
            return response()->json(['status' => 0, 'message' => 'Không tìm thấy link chia sẻ!'], 404);
        }

        // Trưởng nhánh chỉ xóa được link của mình
        if ($login->vai_tro === 'truong_nhanh' && $chiaSe->id_nguoi_tao !== $login->id) {
            return response()->json(['status' => 0, 'message' => 'Bạn không có quyền xóa link chia sẻ này!'], 403);
        }

        $old = $chiaSe->toArray();
        $chiaSe->delete();
        $this->ghiLog($request, 'xoa', $chiaSe, $old, null);

        return response()->json(['status' => 1, 'message' => 'Xóa link chia sẻ thành công!']);
    }

    private function ghiLog(Request $request, string $hanhDong, CayGiaPhaChiaSe $chiaSe, ?array $cu, ?array $moi): void
    {
        NhatKyHoatDong::create([
            'id_nguoi_dung' => Auth::guard('sanctum')->id(),
            'hanh_dong' => $hanhDong,
            'ten_bang' => 'cay_gia_pha_chia_ses',
            'id_ban_ghi' => $chiaSe->id,
            'du_lieu_cu' => $cu,
            'du_lieu_moi' => $moi,
            'dia_chi_ip' => $request->ip(),
            'trinh_duyet' => $request->userAgent(),
        ]);
    }
}
