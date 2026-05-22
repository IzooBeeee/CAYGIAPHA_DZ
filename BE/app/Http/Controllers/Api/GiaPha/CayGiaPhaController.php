<?php

namespace App\Http\Controllers\Api\GiaPha;

use App\Http\Controllers\Controller;
use App\Http\Requests\GiaPha\NguoiThanRequest;
use App\Models\ThanhVienGiaPha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CayGiaPhaController extends Controller
{
    public function getData(Request $request)
    {
        $login = Auth::guard('sanctum')->user();
        if (! $login) {
            return response()->json(['status' => 0, 'message' => 'Bạn cần đăng nhập hệ thống!'], 401);
        }

        return response()->json([
            'status' => 1,
            'data' => $this->layCayGiaPha($request->id_nhanh_ho),
        ]);
    }

    public function getDataCongKhai(Request $request)
    {
        return response()->json([
            'status' => 1,
            'data' => $this->layCayGiaPha($request->id_nhanh_ho),
        ]);
    }

    public function nguoiThan(NguoiThanRequest $request)
    {
        return response()->json([
            'status' => 1,
            'data' => [
                'to_tien' => $this->layToTien($request->id),
                'hau_due' => $this->layHauDue($request->id),
                'anh_chi_em' => $this->layAnhChiEm($request->id),
            ],
        ]);
    }

    private function layCayGiaPha(?int $idNhanhHo = null)
    {
        $thanhViens = ThanhVienGiaPha::with(['honNhanChong.vo', 'honNhanVo.chong', 'moPhan'])
            ->when($idNhanhHo, fn ($query) => $query->where('id_nhanh_ho', $idNhanhHo))
            ->orderBy('doi_thu')
            ->get();

        $nhomCon = $thanhViens->groupBy(fn ($thanhVien) => $thanhVien->id_cha ?: $thanhVien->id_me ?: 0);

        return $thanhViens->whereNull('id_cha')->whereNull('id_me')->values()
            ->map(fn ($thanhVien) => $this->dinhDangNut($thanhVien, $nhomCon));
    }

    private function dinhDangNut($thanhVien, $nhomCon): array
    {
        return [
            'id' => $thanhVien->id,
            'ho_ten' => $thanhVien->ho_ten,
            'gioi_tinh' => $thanhVien->gioi_tinh,
            'doi_thu' => $thanhVien->doi_thu,
            'con_song' => $thanhVien->con_song,
            'ngay_mat' => optional($thanhVien->ngay_mat)->format('d/m/Y'),
            'vo_chong' => $thanhVien->honNhanChong->pluck('vo')->merge($thanhVien->honNhanVo->pluck('chong'))->filter()->values(),
            'mo_phan' => $thanhVien->moPhan,
            'con_cai' => $nhomCon->get($thanhVien->id, collect())->unique('id')->values()
                ->map(fn ($con) => $this->dinhDangNut($con, $nhomCon)),
        ];
    }

    private function layToTien(int $idThanhVien)
    {
        $thanhVien = ThanhVienGiaPha::with(['cha', 'me'])->find($idThanhVien);
        $data = collect();

        foreach (['cha', 'me'] as $quanHe) {
            if ($thanhVien->{$quanHe}) {
                $data->push($thanhVien->{$quanHe});
                $data = $data->merge($this->layToTien($thanhVien->{$quanHe}->id));
            }
        }

        return $data->unique('id')->values();
    }

    private function layHauDue(int $idThanhVien)
    {
        $conCai = ThanhVienGiaPha::where('id_cha', $idThanhVien)->orWhere('id_me', $idThanhVien)->get();

        return $conCai->flatMap(fn ($con) => collect([$con])->merge($this->layHauDue($con->id)))->unique('id')->values();
    }

    private function layAnhChiEm(int $idThanhVien)
    {
        $thanhVien = ThanhVienGiaPha::find($idThanhVien);

        return ThanhVienGiaPha::where('id', '!=', $idThanhVien)
            ->where(function ($query) use ($thanhVien) {
                $query->when($thanhVien->id_cha, fn ($q) => $q->orWhere('id_cha', $thanhVien->id_cha))
                    ->when($thanhVien->id_me, fn ($q) => $q->orWhere('id_me', $thanhVien->id_me));
            })
            ->get();
    }
}
