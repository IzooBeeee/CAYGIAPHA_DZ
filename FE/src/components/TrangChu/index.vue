<template>
    <div class="landing-page">
        <!-- Hero Section -->
        <section class="hero" :style="{ backgroundImage: 'url(' + heroBg + ')' }">
            <div class="hero-overlay">
                <div class="container">
                    <div class="hero-content">
                        <span class="subtitle">HÀNH TRÌNH TÌM LẠI CỘI NGUỒN</span>
                        <h1>Kết nối với Tổ tiên qua từng nén nhang</h1>
                        <p>Dòng họ không chỉ là tên gọi, đó là quá khứ, là tâm hồn và là khởi nguồn của mỗi con người.
                            Hãy cùng Heritage Archivist gìn giữ những giá trị thiêng liêng ngay hôm nay.</p>
                        <div class="hero-btns">
                            <button class="btn-primary">BẮT ĐẦU GIA PHẢ</button>
                            <button class="btn-outline">TÌM HIỂU THÊM</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section class="about-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="about-img-container">
                            <img :src="aboutPhoto" alt="Tìm lại cội nguồn" class="img-fluid about-img">
                            <div class="img-caption">"Gia phả là chìa khóa mở cánh cửa vào thế giới của tổ tiên."</div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="about-text">
                            <div class="section-line"></div>
                            <h2>Bạn có bao giờ đứng trước bàn thờ và tự hỏi: "Cụ là ai?"</h2>
                            <p>Khoảnh khắc thắp nén nhang cho tổ tiên là lúc sợi dây truyền thống rạng lên mạnh mẽ nhất.
                                Nhưng sẽ thật đáng tiếc khi chúng ta không biết rõ về cuộc đời, sự nghiệp hay ý chí của
                                người đang ngự trên linh vị.</p>

                            <ul class="feature-list">
                                <li>
                                    <i class="bx bx-history"></i>
                                    <div>
                                        <strong>Xác định niên trình lịch sử</strong>
                                        <p>Tra cứu thông tin chính xác về niên bản gia phả dòng họ.</p>
                                    </div>
                                </li>
                                <li>
                                    <i class="bx bx-group"></i>
                                    <div>
                                        <strong>Kết nối mối quan hệ</strong>
                                        <p>Hiểu rõ các tầng lớp, thứ bậc và vai trò của từng thành viên trong phả hệ.</p>
                                    </div>
                                </li>
                                <li>
                                    <i class="bx bx-book-content"></i>
                                    <div>
                                        <strong>Chuẩn mực xưng hô</strong>
                                        <p>Hướng dẫn cách xưng hô, thờ tự đúng chuẩn sau theo truyền thống Việt Nam.</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stream Section -->
        <section class="stream-section">
            <div class="container text-center">
                <h2>Dòng Chảy Huyết Thống</h2>
                <p class="section-desc">Trực quan hóa gia phả bằng công nghệ hiện đại, giữ trọn nét tinh hoa văn hóa lâu đời.</p>

                <div class="tree-visual">
                    <div class="node principal">
                        <img :src="ancestorSample" alt="Ancestor" class="avatar">
                        <div class="node-info">
                            <strong>Nguyễn Văn Phong</strong>
                            <span>Đời thứ 1 (Thủy tổ)</span>
                        </div>
                    </div>
                    <div class="tree-lines"></div>
                    <div class="children-nodes">
                        <div class="node">
                            <div class="node-info">
                                <strong>Nguyễn Văn Minh</strong>
                                <span>Chi trưởng - Đời 2</span>
                            </div>
                        </div>
                        <div class="node active">
                            <div class="node-info">
                                <strong>Nguyễn Thị Mai</strong>
                                <span>Chi thứ - Đời 2</span>
                            </div>
                            <span class="badge-status">HIỆN TẠI</span>
                        </div>
                        <div class="node">
                            <div class="node-info">
                                <strong>Nguyễn Văn An</strong>
                                <span>Chi thứ - Đời 2</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Grid -->
        <section class="features-grid">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="card-icon"><i class="bx bx-data"></i></div>
                            <h3>Số hóa gia phả</h3>
                            <p>Chuyển đổi những cuốn sổ cũ kỹ, mờ nhạt thành cây gia phả số hóa sống động, dễ dàng truy cập và bảo mật vĩnh viễn.</p>
                            <a href="#" class="learn-more">Bắt đầu số hóa <i class="bx bx-right-arrow-alt"></i></a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="card-icon"><i class="bx bx-book"></i></div>
                            <h3>Từ điển xưng hô</h3>
                            <p>Hướng dẫn chi tiết cách gọi tên, xưng danh vị trí chính xác của từng cá nhân trong dòng họ nội ngoại.</p>
                            <a href="#" class="learn-more">Tra cứu ngay <i class="bx bx-right-arrow-alt"></i></a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="card-icon"><i class="bx bx-archive"></i></div>
                            <h3>Kho lưu trữ ký ức</h3>
                            <p>Nơi lưu giữ những câu chuyện, video và hình ảnh chưa từng kể về những người đã khuất.</p>
                            <a href="#" class="learn-more">Khám phá ký ức <i class="bx bx-right-arrow-alt"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonial Section -->
        <section class="testimonial-section" :style="{ backgroundImage: 'url(' + testimonialBg + ')' }">
            <div class="testimonial-overlay">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="quote-content">
                                <i class="bx bxs-quote-left quote-icon"></i>
                                <blockquote>
                                    "Nhờ Heritage Archivist, lần đầu tiên tôi thắp nén nhang cho cụ cố với một cảm giác hoàn toàn khác. Tôi biết cụ đã từng là một nhà giáo tận tụy, biết cụ thích hoa mai, và biết chính xác mình phải gọi cụ là gì. Ứng dụng này đã mang lại linh hồn cho những cái tên trên tấm bia đá."
                                </blockquote>
                                <div class="quote-author">
                                    <img :src="ancestorSample" alt="User" class="author-img">
                                    <div>
                                        <strong>Lê Minh Tuấn</strong>
                                        <span>Kiến trúc sư - Hà Nội</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta-section">
            <div class="container text-center">
                <h2>Sẵn sàng viết tiếp câu chuyện của dòng họ?</h2>
                <p>Bắt đầu miễn phí ngay hôm nay và bảo tồn di sản cho thế hệ mai sau.</p>
                <div class="cta-btns">
                    <button class="btn-dark">KHỞI TẠO GIA PHẢ NGAY</button>
                    <button class="btn-white">GỬI LỜI MỜI GIA ĐÌNH</button>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
import heroBg from '@/assets/images/hero_bg.png';
import testimonialBg from '@/assets/images/testimonial_bg.png';
import aboutPhoto from '@/assets/images/about_photo.png';
import ancestorSample from '@/assets/images/ancestor_sample.png';

export default {
    name: 'TrangChu',
    data() {
        return {
            heroBg,
            testimonialBg,
            aboutPhoto,
            ancestorSample
        }
    }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap');

.landing-page {
    font-family: 'Inter', sans-serif;
    color: #1a1a1a;
    overflow-x: hidden;
    background-color: #fff;
    margin-top: -30px;
}

h1, h2, h3, blockquote {
    font-family: 'Playfair Display', serif;
}

section {
    padding: 100px 0;
}

.container {
    max-width: 1200px;
}

.hero {
    height: 90vh;
    background-size: cover;
    background-position: center;
    position: relative;
    display: flex;
    align-items: center;
}

.hero-overlay {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: linear-gradient(to right, rgba(0, 0, 0, 0.85) 0%, rgba(0, 0, 0, 0.4) 60%, rgba(0, 0, 0, 0.2) 100%);
    display: flex;
    align-items: center;
}

.hero-content {
    max-width: 750px;
    color: #fff;
    text-align: left;
}

.subtitle {
    display: block;
    font-size: 14px;
    letter-spacing: 4px;
    margin-bottom: 25px;
    color: #d4af37;
    font-weight: 600;
}

.hero-content h1 {
    font-size: 68px;
    line-height: 1.1;
    margin-bottom: 35px;
    font-weight: 700;
}

.hero-content p {
    font-size: 19px;
    line-height: 1.7;
    margin-bottom: 45px;
    opacity: 0.9;
}

.hero-btns {
    display: flex;
    gap: 20px;
}

.btn-primary {
    background-color: #1e3a1e;
    color: #fff;
    border: none;
    padding: 18px 40px;
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 1.5px;
    cursor: pointer;
    transition: all 0.4s;
}

.btn-primary:hover {
    background-color: #2a522a;
    transform: translateY(-3px);
}

.btn-outline {
    background: transparent;
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.4);
    padding: 18px 40px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.4s;
}

.btn-outline:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: #fff;
    transform: translateY(-3px);
}

.about-img-container {
    position: relative;
    padding-right: 30px;
}

.about-img {
    border-radius: 4px;
    box-shadow: 30px 30px 80px rgba(0, 0, 0, 0.08);
    max-width: 85%;
}

.img-caption {
    margin-top: 25px;
    font-style: italic;
    font-size: 16px;
    color: #777;
    border-left: 3px solid #d4af37;
    padding-left: 20px;
}

.section-line {
    width: 60px;
    height: 3px;
    background-color: #d4af37;
    margin-bottom: 35px;
}

.about-text h2 {
    font-size: 46px;
    line-height: 1.25;
    margin-bottom: 35px;
}

.feature-list {
    list-style: none;
    padding: 0;
}

.feature-list li {
    display: flex;
    gap: 25px;
    margin-bottom: 35px;
}

.feature-list i {
    font-size: 28px;
    color: #d4af37;
}

.stream-section {
    background: linear-gradient(135deg, #fdfcfb 0%, #e2d1c3 100%);
}

.tree-visual {
    padding: 50px 0;
}

.node {
    display: inline-flex;
    background: #fff;
    padding: 18px 28px;
    border-radius: 12px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.06);
    align-items: center;
    gap: 18px;
}

.node.principal {
    border: 2px solid #d4af37;
}

.avatar {
    width: 55px;
    height: 55px;
    border-radius: 50%;
    object-fit: cover;
}

.tree-lines {
    height: 70px;
    width: 2px;
    background: linear-gradient(to bottom, #d4af37, transparent);
    margin: 0 auto;
}

.children-nodes {
    display: flex;
    justify-content: center;
    gap: 40px;
}

.node.active {
    background-color: #1e3a1e;
    color: #fff;
}

.badge-status {
    position: absolute;
    top: -12px; right: -12px;
    background-color: #d4af37;
    color: #000;
    font-size: 11px;
    padding: 5px 10px;
    border-radius: 6px;
}

.feature-card {
    padding: 50px 40px;
    background: #fff;
    border: 1px solid #f0f0f0;
    transition: all 0.4s;
    height: 100%;
}

.feature-card:hover {
    transform: translateY(-15px);
    border-color: #d4af37;
}

.card-icon {
    width: 70px; height: 70px;
    background: #fdf8ef;
    color: #d4af37;
    display: flex;
    align-items: center; justify-content: center;
    font-size: 34px;
    border-radius: 16px;
    margin-bottom: 30px;
}

.testimonial-section {
    height: 700px;
    background-size: cover;
    background-position: center;
    position: relative;
}

.testimonial-overlay {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.6);
    display: flex;
    align-items: center;
}

blockquote {
    font-size: 36px;
    color: #fff;
    line-height: 1.5;
    margin-bottom: 50px;
}

.quote-author {
    display: flex;
    align-items: center;
    gap: 20px;
}

.author-img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #d4af37;
}

.quote-author strong {
    display: block;
    color: #fff;
    font-size: 18px;
}

.quote-author span {
    color: #d4af37;
    font-size: 14px;
}

.cta-section {
    padding: 120px 0;
}

.btn-dark {
    background-color: #1a1a1a;
    color: #fff;
    padding: 20px 45px;
    border: none;
    font-weight: 700;
    cursor: pointer;
}

.btn-white {
    background-color: #fff;
    color: #1a1a1a;
    border: 1px solid #ccc;
    padding: 20px 45px;
    font-weight: 700;
    cursor: pointer;
}

@media (max-width: 991px) {
    .children-nodes { flex-direction: column; align-items: center; }
    .tree-lines { display: none; }
}
</style>