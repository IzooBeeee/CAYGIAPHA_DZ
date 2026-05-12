<template>
    <div class="auth-page" :style="{ backgroundImage: 'url(' + heroBg + ')' }">
        <div class="auth-overlay">
            <div class="auth-container">
                <div class="auth-card">
                    <div class="auth-header">
                        <router-link to="/" class="auth-logo">Heritage <span>Archivist</span></router-link>
                        <h2>Tham gia cộng đồng</h2>
                        <p>Bắt đầu hành trình gìn giữ di sản cho thế hệ mai sau</p>
                    </div>
                    <form @submit.prevent="handleRegister" class="auth-form">
                        <div class="form-group">
                            <label>Họ và tên</label>
                            <div class="input-wrapper">
                                <i class="bx bx-user-circle"></i>
                                <input type="text" v-model="registerData.ho_ten" placeholder="Nhập họ và tên của bạn" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Địa chỉ Email</label>
                            <div class="input-wrapper">
                                <i class="bx bx-envelope"></i>
                                <input type="email" v-model="registerData.email" placeholder="example@email.com" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <div class="input-wrapper">
                                <i class="bx bx-phone"></i>
                                <input type="text" v-model="registerData.so_dien_thoai" placeholder="Nhập số điện thoại" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Mật khẩu</label>
                            <div class="input-wrapper">
                                <i class="bx bx-lock-alt"></i>
                                <input type="password" v-model="registerData.mat_khau" placeholder="Tối thiểu 8 ký tự" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Xác nhận mật khẩu</label>
                            <div class="input-wrapper">
                                <i class="bx bx-check-shield"></i>
                                <input type="password" v-model="registerData.re_mat_khau" placeholder="Nhập lại mật khẩu" required>
                            </div>
                        </div>
                        <div class="form-terms">
                            <label class="checkbox-container">
                                <input type="checkbox" required>
                                <span class="label-text">Tôi đồng ý với các <a href="#">Điều khoản</a> và <a href="#">Chính sách bảo mật</a></span>
                            </label>
                        </div>
                        <button type="submit" class="btn-auth" :disabled="isLoading">
                            <span v-if="!isLoading">ĐĂNG KÝ TÀI KHOẢN</span>
                            <span v-else><i class="bx bx-loader-alt bx-spin"></i> ĐANG XỬ LÝ...</span>
                        </button>
                    </form>
                    <div class="auth-footer">
                        <p>Đã có tài khoản? <router-link to="/login">Đăng nhập ngay</router-link></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import heroBg from '@/assets/images/hero_bg.png';
import axios from 'axios';
import toastr from 'toastr';

export default {
    name: 'Register',
    data() {
        return {
            heroBg,
            registerData: {
                ho_ten: '',
                email: '',
                so_dien_thoai: '',
                mat_khau: '',
                re_mat_khau: ''
            },
            isLoading: false
        }
    },
    methods: {
        handleRegister() {
            if (this.registerData.mat_khau !== this.registerData.re_mat_khau) {
                toastr.error('Mật khẩu xác nhận không khớp!');
                return;
            }

            this.isLoading = true;
            axios.post('http://127.0.0.1:8000/api/register', this.registerData)
                .then(res => {
                    if (res.data.status) {
                        toastr.success(res.data.message);
                        this.$router.push('/login');
                    }
                })
                .catch(err => {
                    const msg = err.response?.data?.message || 'Có lỗi xảy ra, vui lòng thử lại!';
                    toastr.error(msg);
                })
                .finally(() => {
                    this.isLoading = false;
                });
        }
    }
}
</script>

<style scoped>
/* Copy styles from Login.vue or use shared styles */
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600;700&display=swap');

.auth-page {
    min-height: 100vh;
    padding: 60px 0;
    background-size: cover;
    background-position: center;
    position: relative;
    font-family: 'Inter', sans-serif;
    display: flex;
    align-items: center;
}

.auth-overlay {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0, 0, 0, 0.7);
}

.auth-container {
    width: 100%;
    max-width: 500px;
    margin: 0 auto;
    padding: 20px;
    position: relative;
    z-index: 1;
}

.auth-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    padding: 40px;
    color: #fff;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
}

.auth-header {
    text-align: center;
    margin-bottom: 30px;
}

.auth-logo {
    font-family: 'Playfair Display', serif;
    font-size: 28px;
    font-weight: 700;
    color: #fff;
    text-decoration: none;
    margin-bottom: 15px;
    display: block;
}

.auth-logo span {
    color: #d4af37;
    font-weight: 400;
    font-style: italic;
}

.auth-header h2 {
    font-family: 'Playfair Display', serif;
    font-size: 28px;
    margin-bottom: 8px;
}

.auth-header p {
    color: #aaa;
    font-size: 13px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 8px;
    color: #d4af37;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.input-wrapper {
    position: relative;
}

.input-wrapper i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #d4af37;
    font-size: 18px;
}

.input-wrapper input {
    width: 100%;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    padding: 12px 15px 12px 45px;
    border-radius: 8px;
    color: #fff;
    font-size: 14px;
    transition: all 0.3s;
}

.input-wrapper input::placeholder {
    color: #fff;
    opacity: 0.8;
}

.input-wrapper input:focus {
    outline: none;
    border-color: #d4af37;
    background: rgba(255, 255, 255, 0.1);
}

.form-terms {
    margin-bottom: 25px;
}

.checkbox-container {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    cursor: pointer;
    font-size: 12px;
    color: #aaa;
}

.checkbox-container a {
    color: #d4af37;
    text-decoration: none;
}

.btn-auth {
    width: 100%;
    background: #d4af37;
    color: #000;
    border: none;
    padding: 15px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 1px;
    cursor: pointer;
    transition: all 0.3s;
    margin-bottom: 20px;
}

.btn-auth:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.btn-auth:hover:not(:disabled) {
    background: #fff;
    transform: translateY(-2px);
}

.auth-footer {
    text-align: center;
    font-size: 13px;
    color: #aaa;
}

.auth-footer a {
    color: #d4af37;
    text-decoration: none;
    font-weight: 600;
}
</style>
