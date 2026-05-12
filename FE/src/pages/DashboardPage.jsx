import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import {
  Network, Users, Database, GitMerge,
  ArrowRight, TreePine, CalendarDays,
  TrendingUp, Star, Clock, ChevronRight,
} from 'lucide-react';
import { getTodayLunar } from '../utils/dateHelpers';
import { useUser } from '../components/UserProvider';
import api from '../utils/api';

export default function DashboardPage() {
  const { user, isAdmin } = useUser();
  const lunar = getTodayLunar();
  const [stats, setStats] = useState({ families: 0, people: 0, marriages: 0 });
  const [families, setFamilies] = useState([]);
  const [loadingStats, setLoadingStats] = useState(true);

  useEffect(() => {
    api.get('/families').then(res => {
      setFamilies(res.data);
      setStats(prev => ({ ...prev, families: res.data.length }));
      if (res.data.length > 0) {
        // Load first family's people count
        api.get(`/families/${res.data[0].id}/people`).then(pr => {
          setStats(prev => ({ ...prev, people: pr.data.length }));
        }).catch(() => {});
      }
    }).catch(() => {}).finally(() => setLoadingStats(false));
  }, []);

  const statCards = [
    {
      label: 'Gia phả', value: stats.families,
      icon: <TreePine size={20} />, color: 'text-amber-600', bg: 'bg-amber-50', border: 'border-amber-100',
    },
    {
      label: 'Thành viên', value: stats.people,
      icon: <Users size={20} />, color: 'text-sky-600', bg: 'bg-sky-50', border: 'border-sky-100',
    },
    {
      label: 'Hôn nhân', value: stats.marriages,
      icon: <Star size={20} />, color: 'text-rose-500', bg: 'bg-rose-50', border: 'border-rose-100',
    },
  ];

  const quickLinks = [
    {
      title: 'Danh sách thành viên',
      desc: 'Xem, thêm và quản lý thông tin từng thành viên',
      href: '/dashboard/members',
      icon: <Users size={22} />,
      accent: 'from-amber-500 to-orange-500',
      bg: 'bg-amber-50 hover:bg-amber-100',
      border: 'border-amber-200',
      text: 'text-amber-700',
    },
    {
      title: 'Cây gia phả',
      desc: 'Trực quan hoá sơ đồ phả hệ qua các thế hệ',
      href: '/tree/auto',
      icon: <Network size={22} />,
      accent: 'from-sky-500 to-blue-600',
      bg: 'bg-sky-50 hover:bg-sky-100',
      border: 'border-sky-200',
      text: 'text-sky-700',
    },
    {
      title: 'Tra cứu danh xưng',
      desc: 'Tìm cách gọi đúng theo quan hệ họ hàng',
      href: '/dashboard/kinship',
      icon: <GitMerge size={22} />,
      accent: 'from-violet-500 to-purple-600',
      bg: 'bg-violet-50 hover:bg-violet-100',
      border: 'border-violet-200',
      text: 'text-violet-700',
    },
    {
      title: 'Sao lưu & Phục hồi',
      desc: 'Xuất / Nhập dữ liệu gia phả dạng JSON',
      href: '/dashboard/data',
      icon: <Database size={22} />,
      accent: 'from-teal-500 to-emerald-600',
      bg: 'bg-teal-50 hover:bg-teal-100',
      border: 'border-teal-200',
      text: 'text-teal-700',
    },
  ];

  return (
    <div className="min-h-screen bg-[#fafaf8]">
      {/* ─── Hero Header ───────────────────────────────── */}
      <div className="relative overflow-hidden bg-white border-b border-stone-100">
        {/* Background decoration */}
        <div className="absolute inset-0 pointer-events-none">
          <div className="absolute -top-24 -right-24 w-96 h-96 bg-amber-100/60 rounded-full blur-3xl" />
          <div className="absolute top-10 -left-16 w-72 h-72 bg-sky-100/40 rounded-full blur-3xl" />
        </div>

        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
          <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            {/* Greeting */}
            <div>
              <div className="flex items-center gap-2 mb-2">
                <span className="text-2xl">👋</span>
                <p className="text-stone-500 text-sm font-medium">
                  Xin chào,{' '}
                  <span className="text-stone-800 font-semibold">{user?.name ?? user?.email}</span>
                </p>
              </div>
              <h1 className="text-3xl sm:text-4xl font-serif font-bold text-stone-900 leading-tight">
                Bảng điều khiển
              </h1>
              <p className="text-stone-500 mt-2 text-sm sm:text-base max-w-lg">
                Quản lý và lưu giữ ký ức gia đình qua từng thế hệ
              </p>
            </div>

            {/* Lunar date card */}
            <div className="flex items-center gap-4 bg-gradient-to-br from-stone-800 to-stone-900 text-white rounded-2xl px-6 py-4 shadow-lg min-w-[220px]">
              <div className="p-2.5 bg-white/10 rounded-xl">
                <CalendarDays size={24} className="text-amber-300" />
              </div>
              <div>
                <p className="text-white/60 text-[10px] font-semibold uppercase tracking-widest">Hôm nay</p>
                <p className="font-bold text-sm mt-0.5">{lunar.solarStr}</p>
                <p className="text-amber-300 text-xs mt-0.5">
                  {lunar.lunarDayStr} — {lunar.lunarYear}
                </p>
              </div>
            </div>
          </div>

          {/* Stats row */}
          <div className="mt-8 grid grid-cols-3 gap-4">
            {statCards.map((s, i) => (
              <div key={i} className={`flex items-center gap-3 px-4 py-3.5 rounded-2xl border ${s.bg} ${s.border}`}>
                <div className={`p-2 rounded-xl bg-white shadow-sm ${s.color}`}>{s.icon}</div>
                <div>
                  <p className="text-2xl font-bold text-stone-800">
                    {loadingStats ? '…' : s.value}
                  </p>
                  <p className="text-xs text-stone-500 font-medium">{s.label}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-10">

        {/* ─── Quick Actions ──────────────────────────── */}
        <section>
          <div className="flex items-center justify-between mb-5">
            <h2 className="text-lg font-bold text-stone-800 font-serif">Truy cập nhanh</h2>
          </div>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {quickLinks.map(link => (
              <Link
                key={link.href}
                to={link.href}
                className={`group flex flex-col gap-4 p-5 rounded-2xl border transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md ${link.bg} ${link.border}`}
              >
                <div className={`w-10 h-10 rounded-xl flex items-center justify-center bg-gradient-to-br ${link.accent} text-white shadow-sm`}>
                  {link.icon}
                </div>
                <div>
                  <h3 className={`font-bold text-sm mb-1 ${link.text}`}>{link.title}</h3>
                  <p className="text-xs text-stone-500 leading-relaxed">{link.desc}</p>
                </div>
                <div className={`flex items-center gap-1 text-xs font-semibold ${link.text} mt-auto`}>
                  Mở ngay <ArrowRight size={12} className="group-hover:translate-x-1 transition-transform" />
                </div>
              </Link>
            ))}
          </div>
        </section>

        {/* ─── Families list ──────────────────────────── */}
        <section>
          <div className="flex items-center justify-between mb-5">
            <h2 className="text-lg font-bold text-stone-800 font-serif">Gia phả của bạn</h2>
            <div className="flex items-center gap-4">
              <Link to="/dashboard/members" className="text-sm text-stone-500 hover:text-stone-800 font-medium hidden sm:flex items-center gap-1">
                Xem tất cả <ChevronRight size={14} />
              </Link>
              <button 
                onClick={() => window.location.href = '/dashboard/members'}
                className="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-xl transition-colors shadow-sm"
              >
                + Tạo gia phả mới
              </button>
            </div>
          </div>

          {loadingStats ? (
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
              {[1, 2].map(i => (
                <div key={i} className="h-32 rounded-2xl bg-stone-100 animate-pulse" />
              ))}
            </div>
          ) : families.length === 0 ? (
            <div className="flex flex-col items-center justify-center py-16 bg-white rounded-2xl border border-stone-200 border-dashed">
              <div className="text-5xl mb-3">🌿</div>
              <h3 className="font-bold text-stone-700 mb-1">Chưa có gia phả nào</h3>
              <p className="text-stone-400 text-sm mb-4">Hãy tạo gia phả đầu tiên để bắt đầu lưu giữ ký ức</p>
              <Link to="/dashboard/members" className="px-5 py-2.5 bg-stone-900 text-white rounded-xl text-sm font-medium hover:bg-stone-800 transition-colors">
                Bắt đầu ngay
              </Link>
            </div>
          ) : (
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
              {families.map(fam => (
                <div key={fam.id} className="group relative bg-white rounded-2xl border border-stone-200 p-6 hover:shadow-md hover:border-amber-300 transition-all duration-200 overflow-hidden">
                  {/* Decorative */}
                  <div className="absolute top-0 right-0 w-32 h-32 bg-amber-50 rounded-bl-[100px] opacity-0 group-hover:opacity-100 transition-opacity" />

                  <div className="relative z-10">
                    <div className="flex items-start justify-between mb-3">
                      <div className="p-2.5 bg-amber-100 rounded-xl text-amber-600">
                        <TreePine size={20} />
                      </div>
                      <span className={`text-xs font-semibold px-2.5 py-1 rounded-full ${fam.is_public ? 'bg-emerald-100 text-emerald-700' : 'bg-stone-100 text-stone-500'}`}>
                        {fam.is_public ? 'Công khai' : 'Riêng tư'}
                      </span>
                    </div>

                    <h3 className="font-bold text-stone-800 text-lg mb-1 group-hover:text-amber-700 transition-colors font-serif">
                      {fam.name}
                    </h3>
                    {fam.description && (
                      <p className="text-stone-500 text-sm leading-relaxed line-clamp-2 mb-4">{fam.description}</p>
                    )}

                    <div className="flex gap-2 mt-2">
                      <Link
                        to={`/tree/${fam.id}`}
                        className="flex items-center gap-1.5 px-3 py-1.5 bg-stone-900 text-white rounded-lg text-xs font-semibold hover:bg-stone-800 transition-colors"
                      >
                        <Network size={12} /> Xem cây
                      </Link>
                      <Link
                        to="/dashboard/members"
                        className="flex items-center gap-1.5 px-3 py-1.5 bg-amber-100 text-amber-700 rounded-lg text-xs font-semibold hover:bg-amber-200 transition-colors"
                      >
                        <Users size={12} /> Thành viên
                      </Link>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          )}
        </section>

        {/* ─── Admin section ──────────────────────────── */}
        {isAdmin && (
          <section>
            <div className="flex items-center gap-3 mb-5">
              <div className="h-px flex-1 bg-rose-100" />
              <span className="text-xs font-bold text-rose-500 uppercase tracking-widest px-2">Quản trị viên</span>
              <div className="h-px flex-1 bg-rose-100" />
            </div>
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <Link
                to="/dashboard/users"
                className="group flex items-center gap-4 p-5 bg-white border border-rose-100 hover:border-rose-300 rounded-2xl transition-all hover:shadow-md hover:-translate-y-0.5"
              >
                <div className="p-3 bg-rose-50 rounded-xl text-rose-500 group-hover:bg-rose-100 transition-colors">
                  <Users size={22} />
                </div>
                <div className="flex-1">
                  <p className="font-bold text-stone-800 group-hover:text-rose-700 transition-colors">Quản lý người dùng</p>
                  <p className="text-sm text-stone-400">Phê duyệt tài khoản, phân quyền</p>
                </div>
                <ChevronRight size={18} className="text-stone-300 group-hover:text-rose-400 group-hover:translate-x-1 transition-all" />
              </Link>

              <Link
                to="/dashboard/data"
                className="group flex items-center gap-4 p-5 bg-white border border-teal-100 hover:border-teal-300 rounded-2xl transition-all hover:shadow-md hover:-translate-y-0.5"
              >
                <div className="p-3 bg-teal-50 rounded-xl text-teal-600 group-hover:bg-teal-100 transition-colors">
                  <Database size={22} />
                </div>
                <div className="flex-1">
                  <p className="font-bold text-stone-800 group-hover:text-teal-700 transition-colors">Sao lưu hệ thống</p>
                  <p className="text-sm text-stone-400">Xuất / nhập toàn bộ dữ liệu</p>
                </div>
                <ChevronRight size={18} className="text-stone-300 group-hover:text-teal-400 group-hover:translate-x-1 transition-all" />
              </Link>
            </div>
          </section>
        )}

        {/* ─── Footer tip ─────────────────────────────── */}
        <div className="flex items-start gap-3 p-4 bg-amber-50 border border-amber-100 rounded-2xl text-sm">
          <span className="text-2xl shrink-0">💡</span>
          <p className="text-amber-800 leading-relaxed">
            <strong>Mẹo:</strong> Vào trang <strong>"Thành viên gia phả"</strong> để thêm người thân. Sau đó nhấn <strong>"Xem cây"</strong> để xem sơ đồ phả hệ trực quan theo thế hệ.
          </p>
        </div>
      </div>
    </div>
  );
}
