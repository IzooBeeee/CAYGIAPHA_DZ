import React, { useEffect, useState } from 'react';
import api from '../utils/api';
import DataImportExport from '../components/DataImportExport';
import { Database, ShieldCheck, AlertTriangle } from 'lucide-react';

export default function DataPage() {
  const [families, setFamilies] = useState([]);
  const [selectedFamilyId, setSelectedFamilyId] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    api.get('/families')
      .then(res => {
        setFamilies(res.data);
        if (res.data.length > 0) {
          setSelectedFamilyId(res.data[0].id);
        }
      })
      .catch(() => setError('Không thể tải danh sách gia phả.'))
      .finally(() => setLoading(false));
  }, []);

  return (
    <div className="p-4 sm:p-8 max-w-7xl mx-auto w-full">
      <div className="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
        <div>
          <h1 className="text-2xl sm:text-3xl font-serif font-bold text-stone-800">
            Sao lưu & Phục hồi
          </h1>
          <p className="text-stone-500 mt-1">
            Quản lý dữ liệu gia phả của bạn bằng cách xuất hoặc nhập tệp tin JSON.
          </p>
        </div>

        {families.length > 1 && (
          <select
            value={selectedFamilyId ?? ''}
            onChange={e => setSelectedFamilyId(Number(e.target.value))}
            className="appearance-none bg-white text-stone-700 px-4 py-2.5 rounded-xl border border-stone-200 shadow-sm focus:outline-none focus:border-amber-400 font-medium text-sm"
          >
            {families.map(f => (
              <option key={f.id} value={f.id}>{f.name}</option>
            ))}
          </select>
        )}
      </div>

      {loading ? (
        <div className="flex items-center justify-center py-24 text-stone-400">
          <div className="w-8 h-8 border-3 border-amber-500 border-t-transparent rounded-full animate-spin mr-3"></div>
          Đang tải...
        </div>
      ) : families.length === 0 ? (
        <div className="flex flex-col items-center justify-center py-24 bg-white rounded-2xl border border-stone-200 border-dashed">
          <div className="text-5xl mb-4">🌿</div>
          <h3 className="font-bold text-stone-700 mb-1">Chưa có gia phả nào</h3>
          <p className="text-stone-400 text-sm">Hãy tạo gia phả trước khi thực hiện sao lưu.</p>
        </div>
      ) : (
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <div className="lg:col-span-2 space-y-6">
            <div className="bg-white rounded-2xl border border-stone-200 shadow-sm p-6 sm:p-8">
              <div className="flex items-center gap-3 mb-6">
                <div className="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                  <Database size={22} />
                </div>
                <div>
                  <h2 className="text-lg font-bold text-stone-800">Công cụ dữ liệu</h2>
                  <p className="text-xs text-stone-500">
                    Gia phả: <span className="font-semibold text-stone-700">
                      {families.find(f => f.id === selectedFamilyId)?.name}
                    </span>
                  </p>
                </div>
              </div>
              
              <DataImportExport familyId={selectedFamilyId} />
            </div>

            <div className="bg-amber-50 border border-amber-100 rounded-2xl p-6 flex items-start gap-4">
              <div className="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-amber-500 shadow-sm shrink-0">
                <AlertTriangle size={20} />
              </div>
              <div>
                <h3 className="font-bold text-amber-900 text-sm mb-1">Lưu ý quan trọng khi nhập dữ liệu</h3>
                <p className="text-amber-800/80 text-xs leading-relaxed">
                  Việc nhập dữ liệu từ tệp tin JSON sẽ bổ sung thành viên mới vào gia phả hiện tại. 
                  Vui lòng đảm bảo tệp JSON có định dạng đúng được xuất từ hệ thống này.
                </p>
              </div>
            </div>
          </div>

          <div className="space-y-6">
            <div className="bg-white rounded-2xl border border-stone-200 shadow-sm p-6">
              <h3 className="font-bold text-stone-800 mb-4 flex items-center gap-2">
                <ShieldCheck size={18} className="text-emerald-500" />
                An toàn dữ liệu
              </h3>
              <ul className="space-y-4">
                <li className="flex gap-3">
                  <div className="w-1.5 h-1.5 rounded-full bg-emerald-400 mt-1.5 shrink-0" />
                  <p className="text-xs text-stone-500 leading-relaxed">
                    Dữ liệu của bạn được lưu trữ an toàn trên máy chủ riêng biệt.
                  </p>
                </li>
                <li className="flex gap-3">
                  <div className="w-1.5 h-1.5 rounded-full bg-emerald-400 mt-1.5 shrink-0" />
                  <p className="text-xs text-stone-500 leading-relaxed">
                    Chúng tôi khuyến khích sao lưu định kỳ hàng tháng để bảo vệ cây gia phả của bạn.
                  </p>
                </li>
                <li className="flex gap-3">
                  <div className="w-1.5 h-1.5 rounded-full bg-emerald-400 mt-1.5 shrink-0" />
                  <p className="text-xs text-stone-500 leading-relaxed">
                    Tệp tin xuất ra có định dạng JSON chuẩn.
                  </p>
                </li>
              </ul>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
