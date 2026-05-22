<?php

namespace Database\Seeders;

use App\Models\ThanhVienGiaPha;
use Illuminate\Database\Seeder;

class ThanhVienGiaPhaSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        ThanhVienGiaPha::insert([
            $this->thanhVien(GiaPhaDemoIds::TV_TO, GiaPhaDemoIds::NHANH_GOC, 'Nguyễn Văn Tổ', 'nam', '1920-01-01', 1, null, null, '1995-03-10', false, 'Thủy tổ của gia phả họ Nguyễn Văn, người đặt nền móng cho dòng họ tại Đà Nẵng.', null, null, $now),
            $this->thanhVien(GiaPhaDemoIds::TV_TRAN_THI_GOC, GiaPhaDemoIds::NHANH_GOC, 'Trần Thị Gốc', 'nu', '1925-05-12', 1, null, null, '2000-07-20', false, null, null, null, $now),
            $this->thanhVien(GiaPhaDemoIds::TV_AN, GiaPhaDemoIds::NHANH_AN, 'Nguyễn Văn An', 'nam', '1950-02-15', 2, GiaPhaDemoIds::TV_TO, GiaPhaDemoIds::TV_TRAN_THI_GOC, '2020-09-18', false, null, null, null, $now),
            $this->thanhVien(GiaPhaDemoIds::TV_BINH, GiaPhaDemoIds::NHANH_BINH, 'Nguyễn Văn Bình', 'nam', '1955-04-20', 2, GiaPhaDemoIds::TV_TO, GiaPhaDemoIds::TV_TRAN_THI_GOC, null, true, null, null, null, $now),
            $this->thanhVien(GiaPhaDemoIds::TV_HOA, GiaPhaDemoIds::NHANH_GOC, 'Nguyễn Thị Hoa', 'nu', '1960-08-08', 2, GiaPhaDemoIds::TV_TO, GiaPhaDemoIds::TV_TRAN_THI_GOC, null, true, null, null, null, $now),
            $this->thanhVien(GiaPhaDemoIds::TV_LE_THI_MAI, GiaPhaDemoIds::NHANH_AN, 'Lê Thị Mai', 'nu', '1952-06-12', 2, null, null, null, true, null, null, null, $now),
            $this->thanhVien(GiaPhaDemoIds::TV_PHAM_THI_DUNG, GiaPhaDemoIds::NHANH_BINH, 'Phạm Thị Dung', 'nu', '1958-11-03', 2, null, null, null, true, null, null, null, $now),
            $this->thanhVien(GiaPhaDemoIds::TV_CUONG, GiaPhaDemoIds::NHANH_AN, 'Nguyễn Văn Cường', 'nam', '1975-03-05', 3, GiaPhaDemoIds::TV_AN, GiaPhaDemoIds::TV_LE_THI_MAI, null, true, null, null, GiaPhaDemoIds::USER_CUONG, $now),
            $this->thanhVien(GiaPhaDemoIds::TV_LAN, GiaPhaDemoIds::NHANH_AN, 'Nguyễn Thị Lan', 'nu', '1978-07-21', 3, GiaPhaDemoIds::TV_AN, GiaPhaDemoIds::TV_LE_THI_MAI, null, true, null, null, GiaPhaDemoIds::USER_CUONG, $now),
            $this->thanhVien(GiaPhaDemoIds::TV_HUNG, GiaPhaDemoIds::NHANH_BINH, 'Nguyễn Văn Hùng', 'nam', '1980-02-10', 3, GiaPhaDemoIds::TV_BINH, GiaPhaDemoIds::TV_PHAM_THI_DUNG, null, true, null, null, GiaPhaDemoIds::USER_HUNG, $now),
            $this->thanhVien(GiaPhaDemoIds::TV_HAI, GiaPhaDemoIds::NHANH_BINH, 'Nguyễn Văn Hải', 'nam', '1983-09-14', 3, GiaPhaDemoIds::TV_BINH, GiaPhaDemoIds::TV_PHAM_THI_DUNG, null, true, null, null, GiaPhaDemoIds::USER_HUNG, $now),
            $this->thanhVien(GiaPhaDemoIds::TV_VO_THI_THU, GiaPhaDemoIds::NHANH_AN, 'Võ Thị Thu', 'nu', '1976-04-18', 3, null, null, null, true, null, 'Vợ thứ nhất của Nguyễn Văn Cường, đã ly hôn năm 2005.', null, $now),
            $this->thanhVien(GiaPhaDemoIds::TV_DANG_THI_HANH, GiaPhaDemoIds::NHANH_AN, 'Đặng Thị Hạnh', 'nu', '1982-12-11', 3, null, null, null, true, null, 'Vợ thứ hai của Nguyễn Văn Cường.', null, $now),
            $this->thanhVien(GiaPhaDemoIds::TV_TRAN_VAN_MINH, GiaPhaDemoIds::NHANH_AN, 'Trần Văn Minh', 'nam', '1976-01-09', 3, null, null, null, true, null, 'Chồng thứ nhất của Nguyễn Thị Lan, đã ly hôn năm 2008.', null, $now),
            $this->thanhVien(GiaPhaDemoIds::TV_HOANG_VAN_PHUC, GiaPhaDemoIds::NHANH_AN, 'Hoàng Văn Phúc', 'nam', '1980-10-22', 3, null, null, null, true, null, 'Chồng thứ hai của Nguyễn Thị Lan.', null, $now),
            $this->thanhVien(GiaPhaDemoIds::TV_BUI_THI_TRANG, GiaPhaDemoIds::NHANH_BINH, 'Bùi Thị Trang', 'nu', '1982-05-30', 3, null, null, null, true, null, null, null, $now),
            $this->thanhVien(GiaPhaDemoIds::TV_DUC, GiaPhaDemoIds::NHANH_AN, 'Nguyễn Văn Đức', 'nam', '1998-06-02', 4, GiaPhaDemoIds::TV_CUONG, GiaPhaDemoIds::TV_VO_THI_THU, null, true, null, null, GiaPhaDemoIds::USER_CUONG, $now, '0905000001'),
            $this->thanhVien(GiaPhaDemoIds::TV_NGOC, GiaPhaDemoIds::NHANH_AN, 'Nguyễn Thị Ngọc', 'nu', '2010-09-09', 4, GiaPhaDemoIds::TV_CUONG, GiaPhaDemoIds::TV_DANG_THI_HANH, null, true, null, null, GiaPhaDemoIds::USER_CUONG, $now),
            $this->thanhVien(GiaPhaDemoIds::TV_TRAN_THI_MY, GiaPhaDemoIds::NHANH_AN, 'Trần Thị Mỹ', 'nu', '2000-03-16', 4, GiaPhaDemoIds::TV_TRAN_VAN_MINH, GiaPhaDemoIds::TV_LAN, null, true, null, null, GiaPhaDemoIds::USER_CUONG, $now),
            $this->thanhVien(GiaPhaDemoIds::TV_HOANG_VAN_NAM, GiaPhaDemoIds::NHANH_AN, 'Hoàng Văn Nam', 'nam', '2012-12-24', 4, GiaPhaDemoIds::TV_HOANG_VAN_PHUC, GiaPhaDemoIds::TV_LAN, null, true, null, null, GiaPhaDemoIds::USER_CUONG, $now),
            $this->thanhVien(GiaPhaDemoIds::TV_KHOA, GiaPhaDemoIds::NHANH_BINH, 'Nguyễn Văn Khoa', 'nam', '2005-01-27', 4, GiaPhaDemoIds::TV_HUNG, GiaPhaDemoIds::TV_BUI_THI_TRANG, null, true, null, null, GiaPhaDemoIds::USER_HUNG, $now),
            $this->thanhVien(GiaPhaDemoIds::TV_LINH, GiaPhaDemoIds::NHANH_BINH, 'Nguyễn Thị Linh', 'nu', '2008-08-19', 4, GiaPhaDemoIds::TV_HUNG, GiaPhaDemoIds::TV_BUI_THI_TRANG, null, true, null, null, GiaPhaDemoIds::USER_HUNG, $now),
            $this->thanhVien(GiaPhaDemoIds::TV_LE_THI_YEN, GiaPhaDemoIds::NHANH_AN, 'Lê Thị Yến', 'nu', '1999-04-04', 4, null, null, null, true, null, 'Vợ của Nguyễn Văn Đức.', GiaPhaDemoIds::USER_CUONG, $now),
            $this->thanhVien(GiaPhaDemoIds::TV_QUAN, GiaPhaDemoIds::NHANH_AN, 'Nguyễn Minh Quân', 'nam', '2025-01-12', 5, GiaPhaDemoIds::TV_DUC, GiaPhaDemoIds::TV_LE_THI_YEN, null, true, null, null, GiaPhaDemoIds::USER_CUONG, $now),
            $this->thanhVien(25, 4, 'Trần Văn Thủy', 'nam', '1915-03-01', 1, null, null, '1988-10-12', false, 'Thủy tổ của gia phả họ Trần Văn.', null, null, $now),
            $this->thanhVien(26, 4, 'Nguyễn Thị Hòa', 'nu', '1920-08-17', 1, null, null, '1992-05-03', false, null, null, null, $now),
            $this->thanhVien(27, 5, 'Trần Văn Khải', 'nam', '1942-01-20', 2, 25, 26, '2016-11-20', false, null, null, null, $now),
            $this->thanhVien(28, 6, 'Trần Văn Lộc', 'nam', '1948-09-12', 2, 25, 26, null, true, null, null, null, $now),
            $this->thanhVien(29, 4, 'Trần Thị Hương', 'nu', '1953-04-22', 2, 25, 26, null, true, null, null, null, $now),
            $this->thanhVien(30, 5, 'Mai Thị Cúc', 'nu', '1945-07-18', 2, null, null, null, true, null, 'Vợ của Trần Văn Khải.', null, $now),
            $this->thanhVien(31, 5, 'Trần Văn Dũng', 'nam', '1970-02-08', 3, 27, 30, null, true, null, null, null, $now),
            $this->thanhVien(32, 5, 'Trần Thị Thu', 'nu', '1974-12-02', 3, 27, 30, null, true, null, null, null, $now),
            $this->thanhVien(33, 6, 'Phạm Thị Lan', 'nu', '1951-05-09', 2, null, null, null, true, null, 'Vợ của Trần Văn Lộc.', null, $now),
            $this->thanhVien(34, 6, 'Trần Văn Minh', 'nam', '1978-06-16', 3, 28, 33, null, true, null, null, null, $now),
            $this->thanhVien(35, 5, 'Trần Hoàng Nam', 'nam', '2000-01-30', 4, 31, null, null, true, null, null, null, $now),
            $this->thanhVien(36, 6, 'Trần Ngọc Anh', 'nu', '2008-10-11', 4, 34, null, null, true, null, null, null, $now),
            $this->thanhVien(37, 7, 'Lê Thị Xuân', 'nu', '1922-02-11', 1, null, null, '2001-01-01', false, 'Người gốc của gia phả họ Lê Thị.', null, null, $now),
            $this->thanhVien(38, 7, 'Hoàng Văn Tâm', 'nam', '1920-06-19', 1, null, null, '1998-04-14', false, null, null, null, $now),
            $this->thanhVien(39, 8, 'Lê Thị Sen', 'nu', '1949-03-05', 2, 38, 37, null, true, null, null, null, $now),
            $this->thanhVien(40, 7, 'Lê Thị Đào', 'nu', '1952-09-23', 2, 38, 37, null, true, null, null, null, $now),
            $this->thanhVien(41, 8, 'Ngô Văn Phúc', 'nam', '1947-12-09', 2, null, null, null, true, null, 'Chồng của Lê Thị Sen.', null, $now),
            $this->thanhVien(42, 8, 'Lê Minh Châu', 'nu', '1976-04-18', 3, 41, 39, null, true, null, null, null, $now),
            $this->thanhVien(43, 8, 'Lê Minh Hà', 'nu', '1980-11-07', 3, 41, 39, null, true, null, null, null, $now),
            $this->thanhVien(44, 7, 'Đỗ Văn Huy', 'nam', '1975-01-13', 3, null, 40, null, true, null, null, null, $now),
            $this->thanhVien(45, 8, 'Lê Bảo Ngọc', 'nu', '2003-05-24', 4, null, 42, null, true, null, null, null, $now),
            $this->thanhVien(46, 7, 'Lê Gia Bảo', 'nam', '2015-08-09', 4, 44, null, null, true, null, null, null, $now),
            $this->thanhVien(47, 9, 'Phạm Đình Khang', 'nam', '1910-10-10', 1, null, null, '1980-02-02', false, 'Thủy tổ của gia phả họ Phạm Đình.', null, null, $now),
            $this->thanhVien(48, 9, 'Võ Thị Lý', 'nu', '1918-12-01', 1, null, null, '1990-08-08', false, null, null, null, $now),
            $this->thanhVien(49, 10, 'Phạm Đình Sơn', 'nam', '1940-04-04', 2, 47, 48, null, true, null, null, null, $now),
            $this->thanhVien(50, 9, 'Phạm Thị Tuyết', 'nu', '1944-07-14', 2, 47, 48, null, true, null, null, null, $now),
            $this->thanhVien(51, 10, 'Đinh Thị Nga', 'nu', '1946-09-29', 2, null, null, null, true, null, 'Vợ của Phạm Đình Sơn.', null, $now),
            $this->thanhVien(52, 10, 'Phạm Đình Long', 'nam', '1968-03-17', 3, 49, 51, null, true, null, null, null, $now),
            $this->thanhVien(53, 10, 'Phạm Thị Mai', 'nu', '1972-05-25', 3, 49, 51, null, true, null, null, null, $now),
            $this->thanhVien(54, 9, 'Bùi Văn Quý', 'nam', '1969-01-01', 3, null, 50, null, true, null, null, null, $now),
            $this->thanhVien(55, 10, 'Phạm Hoàng Gia', 'nam', '1995-06-06', 4, 52, null, null, true, null, null, null, $now),
            $this->thanhVien(56, 10, 'Phạm Minh Anh', 'nu', '2000-11-11', 4, 52, null, null, true, null, null, null, $now),
            $this->thanhVien(57, 9, 'Bùi Phạm An', 'nam', '2006-02-19', 4, 54, null, null, true, null, null, null, $now),
            $this->thanhVien(58, 10, 'Phạm An Nhiên', 'nu', '2024-04-20', 5, 55, null, null, true, null, null, null, $now),
        ]);
    }

    private function thanhVien(
        int $id,
        int $idNhanhHo,
        string $hoTen,
        string $gioiTinh,
        string $ngaySinh,
        int $doiThu,
        ?int $idCha,
        ?int $idMe,
        ?string $ngayMat,
        bool $conSong,
        ?string $tieuSu,
        ?string $ghiChu,
        ?int $idNguoiCapNhat,
        $now,
        ?string $soDienThoai = null
    ): array {
        return [
            'id' => $id,
            'id_nhanh_ho' => $idNhanhHo,
            'ho_ten' => $hoTen,
            'ten_khac' => null,
            'gioi_tinh' => $gioiTinh,
            'ngay_sinh' => $ngaySinh,
            'ngay_mat' => $ngayMat,
            'con_song' => $conSong,
            'noi_sinh' => 'Đà Nẵng',
            'que_quan' => 'Đà Nẵng',
            'dia_chi_hien_tai' => 'Đà Nẵng',
            'so_dien_thoai' => $soDienThoai,
            'anh_dai_dien' => null,
            'doi_thu' => $doiThu,
            'id_cha' => $idCha,
            'id_me' => $idMe,
            'tieu_su' => $tieuSu,
            'ghi_chu' => $ghiChu,
            'id_nguoi_tao' => GiaPhaDemoIds::USER_ADMIN,
            'id_nguoi_cap_nhat' => $idNguoiCapNhat ?? GiaPhaDemoIds::USER_ADMIN,
            'deleted_at' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }
}
