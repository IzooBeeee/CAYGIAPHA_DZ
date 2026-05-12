<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Family;
use App\Models\Person;
use App\Models\Marriage;
use App\Models\User;
use App\Models\Role;

class FamilyTreeSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy user admin
        $admin = User::where('email', 'admin@gmail.com')->first();
        if (!$admin) {
            $this->command->error('Không tìm thấy admin@gmail.com. Hãy chạy RoleSeeder trước.');
            return;
        }

        // Xoá dữ liệu cũ
        Marriage::query()->delete();
        Person::query()->delete();
        Family::query()->delete();

        // ═══════════════════════════════════════════════
        // TẠO GIA PHẢ HỌ NGUYỄN
        // ═══════════════════════════════════════════════
        $family = Family::create([
            'user_id'     => $admin->id,
            'name'        => 'Gia Phả Họ Nguyễn - Chi Nhất',
            'description' => 'Gia phả dòng họ Nguyễn gốc Hà Nam, 5 đời nối tiếp từ năm 1850 đến nay.',
            'is_public'   => true,
        ]);

        $fid = $family->id;

        // ─── ĐỜI 1: Cụ tổ ───────────────────────────────
        $to = $this->person($fid, 'Nguyễn Văn Tổ', 'male', '1855-03-10', null, 'Hà Nam, Việt Nam', 'Cụ tổ dòng họ Nguyễn chi nhất. Làm nghề nông, hiền lành, đức độ. Mất năm 1930.');
        $toVo = $this->person($fid, 'Trần Thị Hiền', 'female', '1860-06-15', '1932-02-20', 'Ninh Bình, Việt Nam', 'Vợ cụ tổ. Người phụ nữ đảm đang, tần tảo nuôi dưỡng con cái.');

        $this->marriage($fid, $to->id, $toVo->id, '1878-01-01');

        // ─── ĐỜI 2: Con cái cụ tổ ────────────────────────
        // Con trai cả
        $truong = $this->person($fid, 'Nguyễn Văn Trưởng', 'male', '1882-05-20', '1960-11-05', 'Hà Nam', 'Con trai cả. Học chữ Nho, làm thầy đồ trong làng. Người có học vấn nhất vùng thời bấy giờ.', $to->id, $toVo->id);
        $truongVo = $this->person($fid, 'Lê Thị Hoa', 'female', '1885-09-12', '1965-03-18', 'Nam Định', 'Vợ của Nguyễn Văn Trưởng. Con gái nhà buôn vải ở Nam Định.');

        // Con trai thứ
        $thu = $this->person($fid, 'Nguyễn Văn Thứ', 'male', '1885-08-14', '1965-04-22', 'Hà Nam', 'Con trai thứ hai. Làm ruộng, chăm chỉ cần cù. Có 3 người con.', $to->id, $toVo->id);
        $thuVo = $this->person($fid, 'Phạm Thị Lan', 'female', '1888-12-01', '1970-07-30', 'Hà Nam', 'Vợ của Nguyễn Văn Thứ. Con gái trong làng, cùng quê.');

        // Con gái
        $gai1 = $this->person($fid, 'Nguyễn Thị Nguyệt', 'female', '1890-02-28', '1975-08-10', 'Hà Nam', 'Con gái út của cụ tổ. Lấy chồng làng bên.', $to->id, $toVo->id);

        $this->marriage($fid, $truong->id, $truongVo->id, '1905-02-15');
        $this->marriage($fid, $thu->id, $thuVo->id, '1908-11-20');

        // ─── ĐỜI 3: Cháu nội (con của Trưởng) ───────────────
        $duc = $this->person($fid, 'Nguyễn Văn Đức', 'male', '1910-04-15', null, 'Hà Nam', 'Con trai cả của Nguyễn Văn Trưởng. Tham gia kháng chiến chống Pháp. Được tặng huân chương.', $truong->id, $truongVo->id);
        $ducVo = $this->person($fid, 'Vũ Thị Mai', 'female', '1913-07-22', '1998-12-01', 'Thái Bình', 'Vợ của Nguyễn Văn Đức. Con gái gia đình cách mạng Thái Bình.');

        $nghia = $this->person($fid, 'Nguyễn Văn Nghĩa', 'male', '1913-09-30', '1945-09-02', 'Hà Nam', 'Con trai thứ hai. Hy sinh trong cuộc Cách mạng tháng Tám 1945. Liệt sĩ.', $truong->id, $truongVo->id);

        $hanh = $this->person($fid, 'Nguyễn Thị Hạnh', 'female', '1916-01-10', '2005-06-30', 'Hà Nam', 'Con gái cả. Lấy chồng ở Hà Nội, làm nghề dệt vải.', $truong->id, $truongVo->id);

        $tuan = $this->person($fid, 'Nguyễn Văn Tuấn', 'male', '1920-11-05', '2010-03-22', 'Hà Nam', 'Con trai út. Di cư vào Sài Gòn năm 1954. Làm thương nhân.', $truong->id, $truongVo->id);
        $tuanVo = $this->person($fid, 'Hoàng Thị Diệu', 'female', '1924-05-18', '2015-09-14', 'Hà Nội', 'Vợ của Nguyễn Văn Tuấn. Gặp nhau ở Hà Nội trước khi di cư vào Nam.');

        $this->marriage($fid, $duc->id, $ducVo->id, '1935-01-20');
        $this->marriage($fid, $tuan->id, $tuanVo->id, '1948-08-15');

        // Cháu nội (con của Thứ)
        $binh = $this->person($fid, 'Nguyễn Văn Bình', 'male', '1915-06-08', '1990-11-11', 'Hà Nam', 'Con trai đầu lòng của Nguyễn Văn Thứ. Làm giáo viên cấp 2.', $thu->id, $thuVo->id);
        $binhVo = $this->person($fid, 'Đặng Thị Xuân', 'female', '1918-03-25', '1995-04-05', 'Hà Nam', 'Vợ của Nguyễn Văn Bình.');

        $cuong = $this->person($fid, 'Nguyễn Văn Cường', 'male', '1918-12-20', '2000-02-14', 'Hà Nam', 'Con thứ hai của Nguyễn Văn Thứ. Bộ đội, phục viên năm 1975.', $thu->id, $thuVo->id);
        $cuongVo = $this->person($fid, 'Trịnh Thị Bích', 'female', '1922-08-30', '2008-07-19', 'Hưng Yên', 'Vợ của Nguyễn Văn Cường. Gặp nhau trong kháng chiến.');

        $this->marriage($fid, $binh->id, $binhVo->id, '1940-02-10');
        $this->marriage($fid, $cuong->id, $cuongVo->id, '1950-05-01');

        // ─── ĐỜI 4: Chút ─────────────────────────────────────
        // Con của Đức
        $hung = $this->person($fid, 'Nguyễn Văn Hùng', 'male', '1940-07-04', null, 'Hà Nội', 'Con trai cả của Nguyễn Văn Đức. Kỹ sư xây dựng, từng tham gia xây dựng cầu Thăng Long.', $duc->id, $ducVo->id);
        $hungVo = $this->person($fid, 'Lưu Thị Thu Hà', 'female', '1944-11-20', null, 'Hà Nội', 'Vợ của Nguyễn Văn Hùng. Giáo viên trường THPT Chu Văn An.');

        $dung = $this->person($fid, 'Nguyễn Thị Dung', 'female', '1943-03-18', null, 'Hà Nội', 'Con gái của Nguyễn Văn Đức. Bác sĩ, công tác tại Bệnh viện Bạch Mai.', $duc->id, $ducVo->id);

        $kien = $this->person($fid, 'Nguyễn Văn Kiên', 'male', '1948-09-15', null, 'Hà Nội', 'Con trai thứ của Nguyễn Văn Đức. Công an nhân dân, nghỉ hưu năm 2005.', $duc->id, $ducVo->id);
        $kienVo = $this->person($fid, 'Ngô Thị Phương', 'female', '1952-04-28', null, 'Bắc Ninh', 'Vợ của Nguyễn Văn Kiên. Kế toán nhà nước.');

        $this->marriage($fid, $hung->id, $hungVo->id, '1968-01-28');
        $this->marriage($fid, $kien->id, $kienVo->id, '1975-05-30');

        // Con của Tuấn (ở Sài Gòn)
        $long = $this->person($fid, 'Nguyễn Văn Long', 'male', '1952-01-15', null, 'TP. Hồ Chí Minh', 'Con trai cả của Nguyễn Văn Tuấn, sinh ở Sài Gòn. Doanh nhân, sở hữu công ty vật liệu xây dựng.', $tuan->id, $tuanVo->id);
        $longVo = $this->person($fid, 'Phan Thị Yến', 'female', '1955-08-22', null, 'TP. Hồ Chí Minh', 'Vợ của Nguyễn Văn Long. Chủ tiệm may thời trang ở Quận 1.');

        $linh = $this->person($fid, 'Nguyễn Thị Linh', 'female', '1955-06-30', null, 'TP. Hồ Chí Minh', 'Con gái của Nguyễn Văn Tuấn. Giảng viên Đại học Kinh tế TP.HCM.', $tuan->id, $tuanVo->id);

        $this->marriage($fid, $long->id, $longVo->id, '1980-02-14');

        // Con của Bình
        $thanh = $this->person($fid, 'Nguyễn Văn Thành', 'male', '1945-05-19', null, 'Hà Nam', 'Con của Nguyễn Văn Bình. Làm nông nghiệp, trưởng thôn 15 năm.', $binh->id, $binhVo->id);
        $thanhVo = $this->person($fid, 'Đinh Thị Mơ', 'female', '1948-09-01', null, 'Hà Nam', 'Vợ của Nguyễn Văn Thành.');

        $this->marriage($fid, $thanh->id, $thanhVo->id, '1970-08-20');

        // Con của Cường
        $quang = $this->person($fid, 'Nguyễn Văn Quang', 'male', '1955-12-25', null, 'Hà Nội', 'Con của Nguyễn Văn Cường. Làm kế toán tại công ty nhà nước, về hưu sớm để kinh doanh.', $cuong->id, $cuongVo->id);
        $quangVo = $this->person($fid, 'Bùi Thị Hương', 'female', '1958-04-14', null, 'Hà Nội', 'Vợ của Nguyễn Văn Quang. Chủ cửa hàng tạp hoá.');

        $this->marriage($fid, $quang->id, $quangVo->id, '1982-10-10');

        // ─── ĐỜI 5: Chít ─────────────────────────────────────
        // Con của Hùng
        $nam = $this->person($fid, 'Nguyễn Văn Nam', 'male', '1970-04-30', null, 'Hà Nội', 'Con trai của Nguyễn Văn Hùng. Lập trình viên tại FPT Software. Đam mê công nghệ và du lịch.', $hung->id, $hungVo->id);
        $namVo = $this->person($fid, 'Đỗ Thị Hà Linh', 'female', '1973-08-05', null, 'Hà Nội', 'Vợ của Nguyễn Văn Nam. Thiết kế đồ họa, làm freelancer.');

        $huong = $this->person($fid, 'Nguyễn Thị Hương', 'female', '1974-12-18', null, 'Hà Nội', 'Con gái của Nguyễn Văn Hùng. Tiến sĩ ngôn ngữ học, giảng viên Đại học Quốc gia Hà Nội.', $hung->id, $hungVo->id);

        $this->marriage($fid, $nam->id, $namVo->id, '2000-01-15');

        // Con của Kiên
        $minh = $this->person($fid, 'Nguyễn Văn Minh', 'male', '1978-02-28', null, 'Hà Nội', 'Con trai của Nguyễn Văn Kiên. Bác sĩ phẫu thuật, công tác tại Bệnh viện Việt Đức. Tốt nghiệp chuyên tu Nhật Bản.', $kien->id, $kienVo->id);
        $minhVo = $this->person($fid, 'Lê Thị Phúc', 'female', '1980-07-11', null, 'Hà Nội', 'Vợ của Nguyễn Văn Minh. Dược sĩ bệnh viện.');

        $thuy = $this->person($fid, 'Nguyễn Thị Thủy', 'female', '1981-10-05', null, 'Hà Nội', 'Con gái của Nguyễn Văn Kiên. Kiến trúc sư tại công ty Nhật.', $kien->id, $kienVo->id);

        $this->marriage($fid, $minh->id, $minhVo->id, '2008-11-01');

        // Con của Long (TP.HCM)
        $tung = $this->person($fid, 'Nguyễn Văn Tùng', 'male', '1982-06-06', null, 'TP. Hồ Chí Minh', 'Con trai của Nguyễn Văn Long. MBA tại Hoa Kỳ, quản lý điều hành công ty gia đình.', $long->id, $longVo->id);
        $tungVo = $this->person($fid, 'Trần Thị Mỹ Duyên', 'female', '1985-03-20', null, 'TP. Hồ Chí Minh', 'Vợ của Nguyễn Văn Tùng. Giám đốc marketing, từng học tại Úc.');

        $tram = $this->person($fid, 'Nguyễn Thị Trâm', 'female', '1985-09-14', null, 'TP. Hồ Chí Minh', 'Con gái của Nguyễn Văn Long. Bác sĩ nha khoa, phòng khám riêng tại Quận 3.', $long->id, $longVo->id);

        $this->marriage($fid, $tung->id, $tungVo->id, '2012-04-28');

        // Con của Thành
        $son = $this->person($fid, 'Nguyễn Văn Sơn', 'male', '1975-11-20', null, 'Hà Nam', 'Con của Nguyễn Văn Thành. Kỹ sư nông nghiệp, chuyên canh tác lúa hữu cơ.', $thanh->id, $thanhVo->id);
        $sonVo = $this->person($fid, 'Nguyễn Thị Vân', 'female', '1978-05-08', null, 'Hà Nam', 'Vợ của Nguyễn Văn Sơn.');

        $this->marriage($fid, $son->id, $sonVo->id, '2002-06-12');

        // Con của Quang
        $tai = $this->person($fid, 'Nguyễn Văn Tài', 'male', '1985-04-17', null, 'Hà Nội', 'Con của Nguyễn Văn Quang. Lập trình viên game, startup riêng về game mobile.', $quang->id, $quangVo->id);
        $trang = $this->person($fid, 'Nguyễn Thị Trang', 'female', '1988-01-22', null, 'Hà Nội', 'Con gái của Nguyễn Văn Quang. Chuyên viên ngân hàng, CFA.', $quang->id, $quangVo->id);

        // ─── ĐỜI 5+: Cháu chít (con của Nam) ─────────────────
        $bao = $this->person($fid, 'Nguyễn Văn Bảo', 'male', '2002-08-15', null, 'Hà Nội', 'Cháu chít. Con trai đầu lòng của Nguyễn Văn Nam. Đang học Đại học Bách Khoa Hà Nội, ngành CNTT.', $nam->id, $namVo->id);
        $an = $this->person($fid, 'Nguyễn Thị An', 'female', '2005-02-10', null, 'Hà Nội', 'Cháu chít. Con gái của Nguyễn Văn Nam. Học sinh THPT, giỏi văn và nghệ thuật.', $nam->id, $namVo->id);

        // Con của Minh
        $loc = $this->person($fid, 'Nguyễn Văn Lộc', 'male', '2010-03-05', null, 'Hà Nội', 'Cháu chít. Con trai của Nguyễn Văn Minh. Học sinh tiểu học, thần đồng toán học.', $minh->id, $minhVo->id);

        // Con của Tùng (TP.HCM)
        $khanh = $this->person($fid, 'Nguyễn Trần Khánh', 'male', '2014-07-20', null, 'TP. Hồ Chí Minh', 'Cháu chít. Con trai của Nguyễn Văn Tùng. Học trường quốc tế, song ngữ Việt-Anh.', $tung->id, $tungVo->id);
        $vy = $this->person($fid, 'Nguyễn Trần Mỹ Vy', 'female', '2017-04-12', null, 'TP. Hồ Chí Minh', 'Cháu chít. Con gái của Nguyễn Văn Tùng.', $tung->id, $tungVo->id);

        // Con của Sơn
        $phuc = $this->person($fid, 'Nguyễn Văn Phúc', 'male', '2005-09-18', null, 'Hà Nam', 'Con của Nguyễn Văn Sơn. Học sinh, đam mê nông nghiệp công nghệ cao.', $son->id, $sonVo->id);

        $this->command->info("✅ Đã tạo gia phả '{$family->name}' với " . Person::count() . " thành viên và " . Marriage::count() . " hôn nhân.");
    }

    private function person(
        int $familyId,
        string $fullName,
        string $gender,
        ?string $birthDate,
        ?string $deathDate,
        ?string $birthPlace = null,
        ?string $biography = null,
        ?int $fatherId = null,
        ?int $motherId = null
    ): Person {
        return Person::create([
            'family_id'  => $familyId,
            'full_name'  => $fullName,
            'gender'     => $gender,
            'birth_date' => $birthDate,
            'death_date' => $deathDate,
            'birth_place' => $birthPlace,
            'biography'  => $biography,
            'father_id'  => $fatherId,
            'mother_id'  => $motherId,
        ]);
    }

    private function marriage(int $familyId, int $husbandId, int $wifeId, string $marriedDate): Marriage
    {
        return Marriage::create([
            'family_id'   => $familyId,
            'husband_id'  => $husbandId,
            'wife_id'     => $wifeId,
            'married_date' => $marriedDate,
            'status'      => 'married',
        ]);
    }
}
