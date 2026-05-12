import React, { useEffect, useState } from 'react';
import { useParams, Link, useNavigate } from 'react-router-dom';
import { ArrowLeft, Users } from 'lucide-react';
import api from '../utils/api';
import ApiFamilyTree from '../components/ApiFamilyTree';

export default function TreeViewerPage() {
  const { familyId } = useParams();
  const navigate = useNavigate();
  const [treeData, setTreeData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

    useEffect(() => {
    // If no familyId, auto-redirect to first family
    if (!familyId || familyId === 'undefined') {
      api.get('/families')
        .then(res => {
          if (res.data.length > 0) {
            navigate(`/tree/${res.data[0].id}`, { replace: true });
          } else {
            setError('Bạn chưa có gia phả nào. Hãy tạo gia phả trước.');
            setLoading(false);
          }
        })
        .catch(() => {
          setError('Không thể tải danh sách gia phả.');
          setLoading(false);
        });
      return;
    }

    api.get(`/families/${familyId}/tree`)
      .then(res => {
        setTreeData(res.data);
        // Redirect from "auto" to actual family ID in URL
        if (familyId === 'auto' && res.data.family?.id) {
          navigate(`/tree/${res.data.family.id}`, { replace: true });
        }
      })
      .catch(err => {
        if (err.response?.status === 403 && familyId === 'auto') {
          setError('Bạn chưa có gia phả nào. Hãy tạo gia phả trước.');
        } else {
          setError(err.response?.data?.message || 'Lỗi tải dữ liệu cây gia phả');
        }
      })
      .finally(() => setLoading(false));
  }, [familyId, navigate]);

  return (
    <div className="h-screen flex flex-col bg-stone-50">
      {/* Top nav bar */}
      <div className="bg-white border-b border-stone-200 shadow-sm px-4 sm:px-6 py-3 flex items-center justify-between z-20 shrink-0">
        <div className="flex items-center gap-3">
          <Link
            to="/dashboard"
            className="flex items-center gap-1.5 text-stone-500 hover:text-stone-800 transition-colors text-sm font-medium"
          >
            <ArrowLeft size={16} />
            Trang chủ
          </Link>
          <span className="text-stone-300">|</span>
          <span className="text-stone-700 font-semibold font-serif text-sm sm:text-base">
            🌳 Sơ đồ gia phả
          </span>
        </div>

        <Link
          to="/dashboard/members"
          className="flex items-center gap-2 px-4 py-2 bg-stone-900 text-white rounded-xl text-sm font-medium hover:bg-stone-800 transition-colors"
        >
          <Users size={15} />
          Danh sách thành viên
        </Link>
      </div>

      {/* Content */}
      <div className="flex-1 overflow-hidden">
        {loading && (
          <div className="flex items-center justify-center h-full text-stone-400">
            <div className="flex flex-col items-center gap-4">
              <div className="w-10 h-10 border-3 border-amber-500 border-t-transparent rounded-full animate-spin"></div>
              <p className="text-sm font-medium">Đang dựng cây gia phả...</p>
            </div>
          </div>
        )}

        {error && !loading && (
          <div className="flex items-center justify-center h-full">
            <div className="text-center max-w-md p-8">
              <div className="text-5xl mb-4">🌿</div>
              <h2 className="text-xl font-bold text-stone-700 mb-2">Không thể tải cây gia phả</h2>
              <p className="text-stone-500 text-sm mb-6">{error}</p>
              <Link to="/dashboard/members" className="px-6 py-3 bg-stone-900 text-white rounded-xl font-medium hover:bg-stone-800 transition-colors">
                Quay về danh sách thành viên
              </Link>
            </div>
          </div>
        )}

        {!loading && !error && treeData && (
          <ApiFamilyTree data={treeData} />
        )}
      </div>
    </div>
  );
}
