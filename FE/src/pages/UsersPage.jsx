import React from 'react';
import AdminUserList from '../components/AdminUserList';

export default function UsersPage() {
  return (
    <div className="p-4 sm:p-8 max-w-7xl mx-auto w-full">
      <div className="mb-8">
        <h1 className="text-2xl sm:text-3xl font-serif font-bold text-stone-800">
          Quản lý người dùng
        </h1>
        <p className="text-stone-500 mt-1">
          Xem danh sách và quản lý quyền hạn của các thành viên trong hệ thống.
        </p>
      </div>

      <div className="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
        <AdminUserList />
      </div>
    </div>
  );
}
