import { AnimatePresence, motion } from 'framer-motion';
import { Trash } from 'lucide-react';
import { useEffect, useState } from 'react';
import api from '../utils/api';

interface AdminUser {
  id: number;
  name: string;
  email: string;
  role: string | null;
  is_active: boolean;
  created_at: string;
}

interface Notification {
  message: string;
  type: 'success' | 'error' | 'info';
}

export default function AdminUserList() {
  const [users, setUsers] = useState<AdminUser[]>([]);
  const [loading, setLoading] = useState(true);
  const [loadingId, setLoadingId] = useState<number | null>(null);
  const [notification, setNotification] = useState<Notification | null>(null);
  const [isCreateModalOpen, setIsCreateModalOpen] = useState(false);
  const [isCreating, setIsCreating] = useState(false);

  const currentUser = JSON.parse(localStorage.getItem('user') || '{}');

  useEffect(() => {
    api.get('/admin/users').then(res => setUsers(res.data)).catch(console.error).finally(() => setLoading(false));
  }, []);

  const showNotification = (message: string, type: 'success' | 'error' | 'info' = 'info') => {
    setNotification({ message, type });
    setTimeout(() => setNotification(null), 5000);
  };

  const handleStatusChange = async (userId: number, newStatus: boolean) => {
    try {
      setLoadingId(userId);
      await api.patch(`/admin/users/${userId}`, { is_active: newStatus });
      setUsers(users.map(u => u.id === userId ? { ...u, is_active: newStatus } : u));
      showNotification(`Đã ${newStatus ? 'duyệt' : 'khoá'} người dùng thành công.`, 'success');
    } catch {
      showNotification('Lỗi khi cập nhật trạng thái.', 'error');
    } finally {
      setLoadingId(null);
    }
  };

  const handleDelete = async (userId: number) => {
    if (!confirm('Bạn có chắc chắn muốn xóa user này?')) return;
    try {
      setLoadingId(userId);
      await api.delete(`/admin/users/${userId}`);
      setUsers(users.filter(u => u.id !== userId));
      showNotification('Đã xóa người dùng thành công.', 'success');
    } catch {
      showNotification('Lỗi khi xoá người dùng.', 'error');
    } finally {
      setLoadingId(null);
    }
  };

  const handleCreateUser = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    setIsCreating(true);
    const formData = new FormData(e.currentTarget);
    try {
      const res = await api.post('/register', {
        name: formData.get('name'),
        email: formData.get('email'),
        password: formData.get('password'),
        password_confirmation: formData.get('password'),
      });
      setUsers([...users, res.data.user]);
      showNotification('Tạo người dùng thành công!', 'success');
      setIsCreateModalOpen(false);
    } catch (err: any) {
      showNotification(err.response?.data?.message || 'Lỗi khi tạo user.', 'error');
    } finally {
      setIsCreating(false);
    }
  };

  if (loading) return <div className="p-8 text-center text-stone-500">Đang tải danh sách người dùng...</div>;

  return (
    <div className="space-y-6 relative">
      <AnimatePresence>
        {notification && (
          <motion.div
            initial={{ opacity: 0, y: -20 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: -20 }}
            className={`fixed top-6 left-1/2 -translate-x-1/2 z-50 px-6 py-3 rounded-xl shadow-lg border flex items-center gap-3 min-w-[320px] ${
              notification.type === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-800'
              : notification.type === 'error' ? 'bg-red-50 border-red-200 text-red-800'
              : 'bg-amber-50 border-amber-200 text-amber-800'
            }`}
          >
            <p className="text-sm font-medium">{notification.message}</p>
          </motion.div>
        )}
      </AnimatePresence>

      <div className="flex justify-end">
        <button
          onClick={() => setIsCreateModalOpen(true)}
          className="inline-flex items-center gap-2 px-4 py-2.5 bg-stone-900 text-white font-medium rounded-xl hover:bg-stone-800 transition-colors text-sm"
        >
          <svg className="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" />
          </svg>
          Thêm người dùng
        </button>
      </div>

      <div className="bg-white/60 rounded-2xl shadow-sm border border-stone-200/60 overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left text-sm">
            <thead className="uppercase tracking-wider border-b border-stone-200/60 bg-stone-50/50">
              <tr>
                <th className="px-6 py-4 text-stone-500 font-semibold text-xs">Email</th>
                <th className="px-6 py-4 text-stone-500 font-semibold text-xs">Tên</th>
                <th className="px-6 py-4 text-stone-500 font-semibold text-xs">Vai trò</th>
                <th className="px-6 py-4 text-stone-500 font-semibold text-xs">Trạng thái</th>
                <th className="px-6 py-4 text-stone-500 font-semibold text-xs">Ngày tạo</th>
                <th className="px-6 py-4 text-stone-500 font-semibold text-xs text-right">Thao tác</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-stone-100">
              {users.map(user => (
                <tr key={user.id} className="hover:bg-stone-50/80 transition-colors">
                  <td className="px-6 py-4 font-medium text-stone-900">{user.email}</td>
                  <td className="px-6 py-4 text-stone-600">{user.name}</td>
                  <td className="px-6 py-4">
                    <span className={`inline-flex items-center px-2 py-1 rounded-md text-xs font-medium ${
                      user.role === 'admin' ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-stone-100 text-stone-600 border border-stone-200'
                    }`}>
                      {user.role ?? 'N/A'}
                    </span>
                  </td>
                  <td className="px-6 py-4">
                    <button
                      disabled={loadingId === user.id || user.id === currentUser.id}
                      onClick={() => handleStatusChange(user.id, !user.is_active)}
                      className={`inline-flex items-center px-2 py-1 rounded-md text-xs font-medium transition-colors cursor-pointer ${
                        user.is_active ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-stone-100 text-stone-800 border border-stone-200'
                      } disabled:opacity-50`}
                    >
                      {user.is_active ? 'Đã duyệt' : 'Chờ duyệt'}
                    </button>
                  </td>
                  <td className="px-6 py-4 text-stone-500">{new Date(user.created_at).toLocaleDateString('vi-VN')}</td>
                  <td className="px-6 py-4 text-right">
                    {user.id !== currentUser.id ? (
                      <button
                        title="Xoá người dùng"
                        disabled={loadingId === user.id}
                        onClick={() => handleDelete(user.id)}
                        className="p-1.5 text-stone-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors disabled:opacity-50"
                      >
                        <Trash className="size-4" />
                      </button>
                    ) : (
                      <span className="text-stone-400 italic text-xs">Bạn</span>
                    )}
                  </td>
                </tr>
              ))}
              {users.length === 0 && (
                <tr>
                  <td colSpan={6} className="px-6 py-8 text-center text-stone-500">Không tìm thấy người dùng nào.</td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      </div>

      {/* Create User Modal */}
      {isCreateModalOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-stone-900/40 backdrop-blur-sm">
          <div className="bg-white rounded-2xl shadow-2xl border border-stone-200/60 w-full max-w-md overflow-hidden">
            <div className="px-6 py-5 border-b border-stone-100 flex justify-between items-center bg-stone-50/50">
              <h3 className="text-xl font-serif font-bold text-stone-800">Tạo Người Dùng Mới</h3>
              <button onClick={() => setIsCreateModalOpen(false)} className="text-stone-400 hover:text-stone-600 size-8 flex items-center justify-center hover:bg-stone-100 rounded-full">
                <svg className="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            <form onSubmit={handleCreateUser} className="p-6 space-y-4">
              <div>
                <label className="block text-sm font-medium text-stone-700 mb-1">Họ tên *</label>
                <input type="text" name="name" required className="w-full px-3 py-2 bg-white text-stone-900 border border-stone-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-amber-500" placeholder="Nguyễn Văn A" />
              </div>
              <div>
                <label className="block text-sm font-medium text-stone-700 mb-1">Email *</label>
                <input type="email" name="email" required className="w-full px-3 py-2 bg-white text-stone-900 border border-stone-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-amber-500" placeholder="email@example.com" />
              </div>
              <div>
                <label className="block text-sm font-medium text-stone-700 mb-1">Mật khẩu *</label>
                <input type="password" name="password" required minLength={8} className="w-full px-3 py-2 bg-white text-stone-900 border border-stone-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-amber-500" placeholder="Ít nhất 8 ký tự" />
              </div>
              <div className="flex justify-end gap-3 pt-2">
                <button type="button" onClick={() => setIsCreateModalOpen(false)} className="px-4 py-2 text-stone-700 bg-stone-100 rounded-xl hover:bg-stone-200 text-sm font-medium">Hủy</button>
                <button type="submit" disabled={isCreating} className="px-4 py-2 bg-stone-900 text-white rounded-xl hover:bg-stone-800 text-sm font-medium disabled:opacity-60">
                  {isCreating ? 'Đang tạo...' : 'Tạo người dùng'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
