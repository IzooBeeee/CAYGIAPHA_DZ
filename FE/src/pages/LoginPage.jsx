import React, { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { ShieldCheck } from 'lucide-react';
import api from '../utils/api';
import config from '../config';

export default function LoginPage() {
  const navigate = useNavigate();
  const [email, setEmail] = useState(config.exampleEmail || 'admin@gmail.com');
  const [password, setPassword] = useState(config.examplePassword || '123456');
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  const handleLogin = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');

    try {
      const response = await api.post('/login', { email, password });
      
      // Save the token and user data
      localStorage.setItem('token', response.data.token);
      localStorage.setItem('user', JSON.stringify(response.data.user));

      // Redirect to dashboard
      navigate('/dashboard');
    } catch (err) {
      setError(err.response?.data?.message || 'Đăng nhập thất bại. Vui lòng thử lại.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-stone-50 flex items-center justify-center p-4">
      <div className="max-w-md w-full bg-white rounded-3xl shadow-xl p-8 border border-stone-100">
        <div className="flex flex-col items-center mb-8">
          <div className="w-16 h-16 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center mb-4">
            <ShieldCheck size={32} />
          </div>
          <h1 className="text-2xl font-bold text-stone-800 font-serif text-center">
            Đăng nhập {config.siteName}
          </h1>
          <p className="text-stone-500 mt-2 text-center text-sm">
            Vui lòng đăng nhập để truy cập vào cây gia phả của bạn
          </p>
        </div>

        {error && (
          <div className="mb-6 p-4 bg-red-50 text-red-600 text-sm rounded-xl border border-red-100">
            {error}
          </div>
        )}

        <form onSubmit={handleLogin} className="space-y-5">
          <div>
            <label className="block text-sm font-medium text-stone-700 mb-1">
              Email
            </label>
            <input
              type="email"
              required
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              className="w-full px-4 py-3 rounded-xl border border-stone-200 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors bg-stone-50 focus:bg-white"
              placeholder="Nhập email của bạn"
            />
          </div>

          <div>
            <label className="block text-sm font-medium text-stone-700 mb-1">
              Mật khẩu
            </label>
            <input
              type="password"
              required
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              className="w-full px-4 py-3 rounded-xl border border-stone-200 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors bg-stone-50 focus:bg-white"
              placeholder="Nhập mật khẩu"
            />
          </div>

          <button
            type="submit"
            disabled={loading}
            className="w-full py-3 px-4 bg-stone-900 hover:bg-stone-800 text-white font-medium rounded-xl transition-colors disabled:opacity-70 flex justify-center items-center"
          >
            {loading ? (
              <span className="inline-block w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
            ) : (
              'Đăng nhập'
            )}
          </button>
        </form>

        <div className="mt-8 pt-6 border-t border-stone-100 text-center space-y-3">
          <p className="text-sm text-stone-500">
            Chưa có tài khoản?{' '}
            <Link to="/register" className="text-amber-600 hover:text-amber-700 font-semibold">
              Đăng ký ngay
            </Link>
          </p>
          <p className="text-xs text-stone-400">
            Tài khoản dùng thử:{' '}
            <span className="font-semibold text-stone-600">admin@gmail.com</span> / <span className="font-semibold text-stone-600">123456</span>
          </p>
        </div>
      </div>
    </div>
  );
}
