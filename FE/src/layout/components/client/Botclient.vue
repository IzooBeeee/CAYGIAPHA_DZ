<template>
  <footer class="bot-client">
    <div class="container">
      <div class="footer-top">
        <div class="row">
          <div class="col-lg-4">
            <div class="footer-brand">
              <h2 class="footer-logo">Heritage <span>Archivist</span></h2>
              <p>Gìn giữ di sản dòng họ bằng công nghệ hiện đại. Chúng tôi tin rằng mỗi gia đình là một mảnh ghép quan trọng của lịch sử.</p>
              <div class="social-links">
                <a href="#"><i class="bx bxl-facebook"></i></a>
                <a href="#"><i class="bx bxl-youtube"></i></a>
                <a href="#"><i class="bx bxl-instagram"></i></a>
              </div>
            </div>
          </div>
          <div class="col-lg-2 offset-lg-1">
            <div class="footer-links">
              <h3>Khám Phá</h3>
              <ul>
                <li><router-link to="/gia-pha">Cây Gia Phả</router-link></li>
                <li><router-link to="/dong-ho">Danh Sách Dòng Họ</router-link></li>
                <li><router-link to="/su-kien">Sự Kiện Sắp Tới</router-link></li>
              </ul>
            </div>
          </div>
          <div class="col-lg-2">
            <div class="footer-links">
              <h3>Hỗ Trợ</h3>
              <ul>
                <li><router-link to="/huong-dan">Hướng Dẫn</router-link></li>
                <li><router-link to="/bao-mat">Chính Sách Bảo Mật</router-link></li>
                <li><router-link to="/lien-he">Liên Hệ</router-link></li>
              </ul>
            </div>
          </div>
          <div class="col-lg-3">
            <div class="footer-contact">
              <h3>Bản Tin</h3>
              <p>Đăng ký để nhận thông tin mới nhất về lịch sử và văn hóa dòng họ.</p>
              <div class="newsletter-form">
                <input type="email" placeholder="Email của bạn">
                <button><i class="bx bx-paper-plane"></i></button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <div class="row align-items-center">
          <div class="col-md-6">
            <p class="copyright">&copy; 2024 Heritage Archivist. Tất cả quyền được bảo lưu.</p>
          </div>
          <div class="col-md-6 text-md-end">
            <p class="made-by">Thiết kế bởi <span class="accent">Antigravity Team</span></p>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  name: 'Botclient'
}
</script>

<style scoped>
.bot-client {
  background-color: #1a1a1a;
  color: #fff;
  padding: 80px 0 30px;
}

.footer-top {
  margin-bottom: 60px;
}

.footer-logo {
  font-family: 'Playfair Display', serif;
  font-size: 28px;
  font-weight: 700;
  margin-bottom: 25px;
}

.footer-logo span {
  color: #d4af37;
  font-weight: 400;
  font-style: italic;
}

.footer-brand p {
  color: #999;
  line-height: 1.8;
  margin-bottom: 30px;
  font-size: 15px;
}

.social-links {
  display: flex;
  gap: 15px;
}

.social-links a {
  width: 40px;
  height: 40px;
  background: rgba(255,255,255,0.05);
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  border-radius: 50%;
  text-decoration: none;
  transition: all 0.3s;
}

.social-links a:hover {
  background: #d4af37;
  color: #000;
  transform: translateY(-3px);
}

.footer-links h3, .footer-contact h3 {
  font-size: 18px;
  font-weight: 700;
  margin-bottom: 25px;
  color: #d4af37;
}

.footer-links ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.footer-links li {
  margin-bottom: 12px;
}

.footer-links a {
  color: #999;
  text-decoration: none;
  font-size: 15px;
  transition: color 0.3s;
}

.footer-links a:hover {
  color: #fff;
  padding-left: 5px;
}

.footer-contact p {
  color: #999;
  font-size: 15px;
  margin-bottom: 20px;
}

.newsletter-form {
  display: flex;
  background: rgba(255,255,255,0.05);
  padding: 5px;
  border-radius: 4px;
}

.newsletter-form input {
  background: transparent;
  border: none;
  padding: 10px 15px;
  color: #fff;
  flex: 1;
  font-size: 14px;
}

.newsletter-form input:focus {
  outline: none;
}

.newsletter-form button {
  background: #d4af37;
  border: none;
  color: #000;
  width: 40px;
  height: 40px;
  border-radius: 4px;
  cursor: pointer;
  transition: background 0.3s;
}

.newsletter-form button:hover {
  background: #fff;
}

.footer-bottom {
  border-top: 1px solid rgba(255,255,255,0.05);
  padding-top: 30px;
}

.copyright, .made-by {
  font-size: 13px;
  color: #666;
  margin: 0;
}

.accent {
  color: #d4af37;
  font-weight: 600;
}
</style>
