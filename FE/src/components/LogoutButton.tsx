import { LogOut } from 'lucide-react';
import { useNavigate } from 'react-router-dom';
import { useState } from 'react';
import api from '../utils/api';

export default function LogoutButton() {
  const navigate = useNavigate();
  const [isLoggingOut, setIsLoggingOut] = useState(false);

  const handleLogout = async () => {
    setIsLoggingOut(true);
    try {
      await api.post('/logout');
    } catch {
      // Even if API fails, clear local storage
    } finally {
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      navigate('/login');
    }
  };

  return (
    <button
      onClick={handleLogout}
      disabled={isLoggingOut}
      className="flex items-center gap-2 w-full px-4 py-2.5 text-sm font-medium text-stone-700 hover:text-rose-700 hover:bg-rose-50 transition-colors"
    >
      <LogOut className="size-4" />
      {isLoggingOut ? 'Đang xử lý...' : 'Đăng xuất'}
    </button>
  );
}
