import React, { useEffect, useState } from 'react';
import api from '../utils/api';
import DashboardMemberList from '../components/DashboardMemberList';
import { useDashboard } from '../components/DashboardContext';
import AddPersonModal from '../components/AddPersonModal';

function CreateFamilyButton({ onCreated }) {
  const [loading, setLoading] = useState(false);
  const [name, setName] = useState('');
  const [show, setShow] = useState(false);

  const handleCreate = async (e) => {
    e.preventDefault();
    setLoading(true);
    try {
      const res = await api.post('/families', { name });
      onCreated(res.data);
    } catch {
      alert('Lỗi khi tạo gia phả.');
    } finally {
      setLoading(false);
    }
  };

  if (!show) {
    return (
      <button
        onClick={() => setShow(true)}
        className="px-6 py-3 bg-stone-900 text-white rounded-xl font-medium hover:bg-stone-800 transition-colors"
      >
        Tạo gia phả mới
      </button>
    );
  }

  return (
    <form onSubmit={handleCreate} className="flex flex-wrap gap-3 items-center">
      <input
        type="text"
        required
        value={name}
        onChange={e => setName(e.target.value)}
        placeholder="Tên gia phả (vd: Họ Nguyễn)"
        className="px-4 py-2.5 rounded-xl border border-stone-200 focus:outline-none focus:border-amber-400 text-sm"
      />
      <button type="submit" disabled={loading}
        className="px-4 py-2.5 bg-stone-900 text-white rounded-xl font-medium hover:bg-stone-800 text-sm disabled:opacity-60">
        {loading ? 'Đang tạo...' : 'Tạo'}
      </button>
      <button type="button" onClick={() => setShow(false)}
        className="px-4 py-2.5 text-stone-600 hover:text-stone-900 text-sm">
        Hủy
      </button>
    </form>
  );
}

export default function MembersPage() {
  const [persons, setPersons] = useState([]);
  const [families, setFamilies] = useState([]);
  const [selectedFamilyId, setSelectedFamilyId] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const { showCreateMember, setShowCreateMember } = useDashboard();

  // Load danh sách gia phả của user
  useEffect(() => {
    api.get('/families')
      .then(res => {
        setFamilies(res.data);
        if (res.data.length > 0) {
          setSelectedFamilyId(res.data[0].id);
        } else {
          setLoading(false);
        }
      })
      .catch(() => {
        setError('Không thể tải danh sách gia phả.');
        setLoading(false);
      });
  }, []);

  // Load thành viên khi chọn gia phả
  useEffect(() => {
    if (!selectedFamilyId) return;
    setLoading(true);
    api.get(`/families/${selectedFamilyId}/people`)
      .then(res => setPersons(res.data))
      .catch(() => setError('Không thể tải danh sách thành viên.'))
      .finally(() => setLoading(false));
  }, [selectedFamilyId]);

  const handlePersonAdded = (newPerson) => {
    setPersons(prev => [newPerson, ...prev]);
    setShowCreateMember(false);
  };

  return (
    <div className="p-4 sm:p-8 max-w-7xl mx-auto w-full">
      {/* Header */}
      <div className="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
        <div>
          <h1 className="text-2xl sm:text-3xl font-serif font-bold text-stone-800">
            Danh sách thành viên
          </h1>
          <p className="text-stone-500 mt-1">
            {selectedFamilyId && families.length > 0
              ? families.find(f => f.id === selectedFamilyId)?.name
              : 'Quản lý và cập nhật thông tin dòng họ'}
          </p>
        </div>

        {/* Family selector & Create button */}
        <div className="flex items-center gap-3">
          {families.length > 0 && (
            <select
              value={selectedFamilyId ?? ''}
              onChange={e => setSelectedFamilyId(Number(e.target.value))}
              className="appearance-none bg-white text-stone-700 px-4 py-2.5 rounded-xl border border-stone-200 shadow-sm focus:outline-none focus:border-amber-400 font-medium text-sm min-w-[150px]"
            >
              {families.map(f => (
                <option key={f.id} value={f.id}>{f.name}</option>
              ))}
            </select>
          )}
          <CreateFamilyButton
            onCreated={(fam) => { 
              setFamilies(prev => [...prev, fam]); 
              setSelectedFamilyId(fam.id); 
            }}
          />
        </div>
      </div>

      {/* No family state */}
      {!loading && families.length === 0 && !error && (
        <div className="flex flex-col items-center justify-center py-24 gap-4 text-center">
          <div className="w-16 h-16 rounded-2xl bg-amber-50 border border-amber-200 flex items-center justify-center text-3xl">
            🌿
          </div>
          <h2 className="text-xl font-bold text-stone-700">Chưa có gia phả nào</h2>
          <p className="text-stone-500 max-w-sm">Bạn chưa tạo gia phả nào. Hãy tạo gia phả đầu tiên để bắt đầu.</p>
          <CreateFamilyButton
            onCreated={(fam) => { setFamilies([fam]); setSelectedFamilyId(fam.id); }}
          />
        </div>
      )}

      {/* Error */}
      {error && (
        <div className="p-4 mb-6 bg-red-50 text-red-600 text-sm rounded-xl border border-red-100">
          {error}
        </div>
      )}

      {/* Loading */}
      {loading && (
        <div className="flex items-center justify-center py-24 text-stone-400">
          <div className="flex items-center gap-3">
            <div className="w-5 h-5 border-2 border-amber-500 border-t-transparent rounded-full animate-spin"></div>
            Đang tải...
          </div>
        </div>
      )}

      {/* Member list */}
      {!loading && selectedFamilyId && (
        <DashboardMemberList
          initialPersons={persons}
          canEdit={true}
        />
      )}

      {/* Modal thêm thành viên */}
      {showCreateMember && selectedFamilyId && (
        <AddPersonModal
          familyId={selectedFamilyId}
          onClose={() => setShowCreateMember(false)}
          onSaved={handlePersonAdded}
        />
      )}
    </div>
  );
}
