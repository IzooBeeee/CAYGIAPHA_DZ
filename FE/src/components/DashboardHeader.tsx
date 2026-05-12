import { AnimatePresence, motion } from 'framer-motion';
import {
  BarChart2, ChevronDown, Database, GitMerge,
  Home, Network, TreePine, UserCircle, Users, Menu, X,
} from 'lucide-react';
import { Link, useLocation } from 'react-router-dom';
import { useEffect, useRef, useState } from 'react';
import LogoutButton from './LogoutButton';
import { useUser } from './UserProvider';
import config from '../config';

const navLinks = [
  { to: '/dashboard', label: 'Tổng quan', icon: <Home size={15} /> },
  { to: '/dashboard/members', label: 'Thành viên', icon: <Users size={15} /> },
  { to: '/tree/auto', label: 'Cây gia phả', icon: <Network size={15} /> },
];

export default function DashboardHeader() {
  const { user, isAdmin } = useUser();
  const location = useLocation();
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const [isUserOpen, setIsUserOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);
  const userRef = useRef<HTMLDivElement>(null);

  const initials = user?.name
    ? user.name.split(' ').slice(-1)[0]?.[0]?.toUpperCase() ?? user.email?.[0]?.toUpperCase()
    : user?.email?.[0]?.toUpperCase() ?? '?';

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 4);
    window.addEventListener('scroll', onScroll);
    return () => window.removeEventListener('scroll', onScroll);
  }, []);

  useEffect(() => {
    const handleClick = (e: MouseEvent) => {
      if (userRef.current && !userRef.current.contains(e.target as Node)) {
        setIsUserOpen(false);
      }
    };
    document.addEventListener('mousedown', handleClick);
    return () => document.removeEventListener('mousedown', handleClick);
  }, []);

  const isActive = (to: string) => {
    if (to === '/dashboard') return location.pathname === '/dashboard';
    return location.pathname.startsWith(to.replace('/auto', ''));
  };

  return (
    <header className={`sticky top-0 z-40 transition-all duration-200 ${scrolled ? 'bg-white shadow-md' : 'bg-white/95 backdrop-blur-sm border-b border-stone-100'}`}>
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between gap-4">

        {/* Logo */}
        <Link to="/dashboard" className="flex items-center gap-2 shrink-0 group">
          <div className="w-8 h-8 rounded-lg overflow-hidden border border-stone-200/60 shadow-sm">
            <img src="/icon.png" alt="Logo" className="w-full h-full object-contain" />
          </div>
          <span className="font-serif font-bold text-stone-800 group-hover:text-amber-700 transition-colors hidden sm:block text-lg">
            {config.siteName}
          </span>
        </Link>

        {/* Desktop Nav */}
        <nav className="hidden md:flex items-center gap-1">
          {navLinks.map(link => (
            <Link
              key={link.to}
              to={link.to}
              className={`flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-sm font-medium transition-all duration-150
                ${isActive(link.to)
                  ? 'bg-amber-100 text-amber-700'
                  : 'text-stone-600 hover:bg-stone-100 hover:text-stone-900'
                }`}
            >
              {link.icon}
              {link.label}
            </Link>
          ))}
        </nav>

        {/* Right: User menu */}
        <div className="flex items-center gap-2">
          {/* Mobile menu toggle */}
          <button
            onClick={() => setIsMenuOpen(!isMenuOpen)}
            className="md:hidden p-2 rounded-xl hover:bg-stone-100 text-stone-600 transition-colors"
          >
            {isMenuOpen ? <X size={20} /> : <Menu size={20} />}
          </button>

          {/* User dropdown */}
          <div className="relative" ref={userRef}>
            <button
              onClick={() => setIsUserOpen(!isUserOpen)}
              className="flex items-center gap-2 pl-1 pr-3 py-1 rounded-full hover:bg-stone-100 transition-all border border-transparent hover:border-stone-200"
            >
              <div className="w-8 h-8 rounded-full bg-gradient-to-br from-amber-400 to-orange-400 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                {initials}
              </div>
              <span className="text-sm font-medium text-stone-700 hidden sm:block max-w-[120px] truncate">
                {user?.name || user?.email}
              </span>
              <ChevronDown size={14} className={`text-stone-400 transition-transform ${isUserOpen ? 'rotate-180' : ''}`} />
            </button>

            <AnimatePresence>
              {isUserOpen && (
                <motion.div
                  initial={{ opacity: 0, y: 8, scale: 0.96 }}
                  animate={{ opacity: 1, y: 0, scale: 1 }}
                  exit={{ opacity: 0, y: 8, scale: 0.96 }}
                  transition={{ duration: 0.15 }}
                  className="absolute right-0 mt-2 w-60 bg-white rounded-2xl shadow-xl border border-stone-200 py-1.5 z-50 overflow-hidden"
                >
                  {/* User info */}
                  <div className="px-4 py-3 border-b border-stone-100">
                    <div className="flex items-center gap-3">
                      <div className="w-10 h-10 rounded-full bg-gradient-to-br from-amber-400 to-orange-400 text-white flex items-center justify-center font-bold text-sm shrink-0">
                        {initials}
                      </div>
                      <div className="min-w-0">
                        {user?.name && <p className="text-sm font-semibold text-stone-800 truncate">{user.name}</p>}
                        <p className="text-xs text-stone-400 truncate">{user?.email}</p>
                        {isAdmin && (
                          <span className="inline-block mt-0.5 text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">
                            Admin
                          </span>
                        )}
                      </div>
                    </div>
                  </div>

                  <div className="py-1">
                    {navLinks.map(link => (
                      <Link
                        key={link.to}
                        to={link.to}
                        onClick={() => setIsUserOpen(false)}
                        className="flex items-center gap-2.5 px-4 py-2.5 text-sm text-stone-700 hover:bg-stone-50 hover:text-amber-700 transition-colors"
                      >
                        {link.icon} {link.label}
                      </Link>
                    ))}

                    {isAdmin && (
                      <>
                        <div className="h-px bg-stone-100 mx-3 my-1" />
                        <p className="px-4 py-1.5 text-[10px] font-bold text-rose-400 uppercase tracking-wider">Quản trị</p>
                        <Link
                          to="/dashboard/users"
                          onClick={() => setIsUserOpen(false)}
                          className="flex items-center gap-2.5 px-4 py-2.5 text-sm text-stone-700 hover:bg-rose-50 hover:text-rose-700 transition-colors"
                        >
                          <Users size={14} /> Quản lý người dùng
                        </Link>
                        <Link
                          to="/dashboard/data"
                          onClick={() => setIsUserOpen(false)}
                          className="flex items-center gap-2.5 px-4 py-2.5 text-sm text-stone-700 hover:bg-teal-50 hover:text-teal-700 transition-colors"
                        >
                          <Database size={14} /> Sao lưu & Phục hồi
                        </Link>
                      </>
                    )}
                  </div>

                  <div className="border-t border-stone-100 pt-1">
                    <LogoutButton />
                  </div>
                </motion.div>
              )}
            </AnimatePresence>
          </div>
        </div>
      </div>

      {/* Mobile nav dropdown */}
      <AnimatePresence>
        {isMenuOpen && (
          <motion.div
            initial={{ opacity: 0, height: 0 }}
            animate={{ opacity: 1, height: 'auto' }}
            exit={{ opacity: 0, height: 0 }}
            className="md:hidden border-t border-stone-100 bg-white overflow-hidden"
          >
            <nav className="px-4 py-3 flex flex-col gap-1">
              {navLinks.map(link => (
                <Link
                  key={link.to}
                  to={link.to}
                  onClick={() => setIsMenuOpen(false)}
                  className={`flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors
                    ${isActive(link.to) ? 'bg-amber-100 text-amber-700' : 'text-stone-700 hover:bg-stone-100'}`}
                >
                  {link.icon} {link.label}
                </Link>
              ))}
            </nav>
          </motion.div>
        )}
      </AnimatePresence>
    </header>
  );
}
