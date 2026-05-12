<template>
    <div class="auth-page" :style="{ backgroundImage: 'url(' + heroBg + ')' }">
        <div class="auth-overlay">
            <div class="auth-container">
                <div class="auth-card">
                    <div class="auth-header">
                        <router-link to="/" class="auth-logo">Heritage <span>Archivist</span></router-link>
                        <h2>Đặt lại mật khẩu</h2>
                        <p>Vui lòng nhập mã xác nhận từ email và mật khẩu mới</p>
                    </div>
                    <form @submit.prevent="handleReset" class="auth-form">
                        <div class="form-group">
                            <label>Mã xác nhận</label>
                            <div class="input-wrapper">
                                <i class="bx bx-key"></i>
                                <input type="text" v-model="resetData.code" placeholder="Nhập mã 6 ký tự" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Mật khẩu mới</label>
                            <div class="input-wrapper">
                                <i class="bx bx-lock-alt"></i>
                                <input type="password" v-model="resetData.mat_khau" placeholder="Nhập mật khẩu mới" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Xác nhận mật khẩu</label>
                            <div class="input-wrapper">
                                <i class="bx bx-check-shield"></i>
                                <input type="password" v-model="resetData.re_mat_khau" placeholder="Nhập lại mật khẩu mới" required>
                            </div>
                        </div>
                        <button type="submit" class="btn-auth" :disabled="isLoading">
                            <span v-if="!isLoading">XÁC NHẬN ĐỔI MẬT KHẨU</span>
                            <span v-else><i class="bx bx-loader-alt bx-spin"></i> ĐANG XỬ LÝ...</span>
                        </button>
                    </form>
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
    name: 'ResetPassword',
    data() {
        return {
            heroBg,
            resetData: {
                email: this.$route.query.email || '',
                code: '',
                mat_khau: '',
                re_mat_khau: ''
            },
            isLoading: false
        }
    },
    methods: {
        handleReset() {
            if (this.resetData.mat_khau !== this.resetData.re_mat_khau) {
                toastr.error('Mật khẩu xác nhận không khớp!');
                return;
            }

            this.isLoading = true;
            axios.post('http://127.0.0.1:8000/api/reset-password', this.resetData)
                .then(res => {
                    if (res.data.status) {
                        toastr.success(res.data.message);
                        this.$router.push('/login');
                    }
                })
                .catch(err => {
                    const msg = err.response?.data?.message || 'Có lỗi xảy ra!';
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
/* Reuse styles from Login.vue */
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
    font-size: 32px;
    margin-bottom: 10px;
}

.auth-header p {
    color: #aaa;
    font-size: 14px;
}

.form-group {
    margin-bottom: 20px;
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
}

.input-wrapper input::placeholder {
    color: #fff;
    opacity: 0.8;
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
    margin-bottom: 25px;
}

.btn-auth:disabled { opacity: 0.7; }
</style>
