import { Outlet, useNavigate } from 'react-router-dom';
import { useEffect } from 'react';
import DashboardHeader from '../components/DashboardHeader';
import MemberDetailModal from '../components/MemberDetailModal';
import { UserProvider } from '../components/UserProvider';
import { DashboardProvider } from '../components/DashboardContext';

function DashboardLayoutInner() {
  const navigate = useNavigate();

  useEffect(() => {
    const token = localStorage.getItem('token');
    if (!token) {
      navigate('/login');
    }
  }, [navigate]);

  return (
    <div className="min-h-screen bg-[#fafaf8] flex flex-col">
      <DashboardHeader />
      <div className="flex-1">
        <Outlet />
      </div>
      <MemberDetailModal />
    </div>
  );
}

export default function DashboardLayout() {
  return (
    <UserProvider>
      <DashboardProvider>
        <DashboardLayoutInner />
      </DashboardProvider>
    </UserProvider>
  );
}
