<template>
    <div class="auth-page" :style="{ backgroundImage: 'url(' + heroBg + ')' }">
        <div class="auth-overlay">
            <div class="auth-container">
                <div class="auth-card">
                    <div class="auth-header">
                        <router-link to="/" class="auth-logo">Heritage <span>Archivist</span></router-link>
                        <h2>Chào mừng trở lại</h2>
                        <p>Tiếp tục hành trình kết nối với cội nguồn của bạn</p>
                    </div>
                    <form @submit.prevent="handleLogin" class="auth-form">
                        <div class="form-group">
                            <label>Email</label>
                            <div class="input-wrapper">
                                <i class="bx bx-user"></i>
                                <input type="email" v-model="loginData.email" placeholder="Nhập email của bạn" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Mật khẩu</label>
                            <div class="input-wrapper">
                                <i class="bx bx-lock-alt"></i>
                                <input type="password" v-model="loginData.mat_khau" placeholder="Nhập mật khẩu" required>
                            </div>
                        </div>
                        <div class="form-options">
                            <label class="remember-me">
                                <input type="checkbox"> <span>Ghi nhớ đăng nhập</span>
                            </label>
                            <router-link to="/forgot-password" class="forgot-password">Quên mật khẩu?</router-link>
                        </div>
                        <button type="submit" class="btn-auth" :disabled="isLoading">
                            <span v-if="!isLoading">ĐĂNG NHẬP</span>
                            <span v-else><i class="bx bx-loader-alt bx-spin"></i> ĐANG XỬ LÝ...</span>
                        </button>
                    </form>
                    <div class="auth-footer">
                        <p>Chưa có tài khoản? <router-link to="/register">Đăng ký ngay</router-link></p>
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
    name: 'Login',
    data() {
        return {
            heroBg,
            loginData: {
                email: '',
                mat_khau: ''
            },
            isLoading: false
        }
    },
    methods: {
        handleLogin() {
            this.isLoading = true;
            axios.post('http://127.0.0.1:8000/api/login', this.loginData)
                .then(res => {
                    if (res.data.status) {
                        toastr.success(res.data.message);
                        localStorage.setItem('access_token', res.data.access_token);
                        localStorage.setItem('user', JSON.stringify(res.data.user));
                        this.$router.push('/');
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
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600;700&display=swap');

.auth-page {
    height: 100vh;
    background-size: cover;
    background-position: center;
    position: relative;
    font-family: 'Inter', sans-serif;
}

.auth-overlay {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
}

.auth-container {
    width: 100%;
    max-width: 450px;
    padding: 20px;
}

.auth-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    padding: 50px 40px;
    color: #fff;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
}

.auth-header {
    text-align: center;
    margin-bottom: 40px;
}

.auth-logo {
    font-family: 'Playfair Display', serif;
    font-size: 28px;
    font-weight: 700;
    color: #fff;
    text-decoration: none;
    margin-bottom: 20px;
    display: block;
}

.auth-logo span {
    color: #d4af37;
    font-weight: 400;
    font-style: italic;
}

.auth-header h2 {
    font-family: 'Playfair Display', serif;
    font-size: 32px;
    margin-bottom: 10px;
}

.auth-header p {
    color: #aaa;
    font-size: 14px;
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 10px;
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
    font-size: 20px;
}

.input-wrapper input {
    width: 100%;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    padding: 15px 15px 15px 50px;
    border-radius: 10px;
    color: #fff;
    font-size: 15px;
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

.form-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    font-size: 13px;
}

.remember-me {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    color: #aaa;
}

.forgot-password {
    color: #d4af37;
    text-decoration: none;
}

.btn-auth {
    width: 100%;
    background: #d4af37;
    color: #000;
    border: none;
    padding: 18px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 2px;
    cursor: pointer;
    transition: all 0.3s;
    margin-bottom: 25px;
}

.btn-auth:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.btn-auth:hover:not(:disabled) {
    background: #fff;
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(212, 175, 55, 0.2);
}

.auth-footer {
    text-align: center;
    font-size: 14px;
    color: #aaa;
}

.auth-footer a {
    color: #d4af37;
    text-decoration: none;
    font-weight: 600;
}

.auth-footer a:hover {
    text-decoration: underline;
}
</style>
