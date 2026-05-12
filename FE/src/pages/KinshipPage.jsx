import React, { useEffect, useState } from 'react';
import api from '../utils/api';
import KinshipFinder from '../components/KinshipFinder';
import { GitMerge, Users, AlertCircle } from 'lucide-react';

export default function KinshipPage() {
  const [families, setFamilies] = useState([]);
  const [selectedFamilyId, setSelectedFamilyId] = useState(null);
  const [people, setPeople] = useState([]);
  const [relationships, setRelationships] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  // Load families
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

  // Load people and marriages when family changes
  useEffect(() => {
    if (!selectedFamilyId) return;
    setLoading(true);
    
    Promise.all([
      api.get(`/families/${selectedFamilyId}/people`),
      api.get(`/families/${selectedFamilyId}/marriages`)
    ])
      .then(([peopleRes, marriagesRes]) => {
        // Map API person to the format expected by KinshipFinder
        const mappedPeople = peopleRes.data.map(p => ({
          id: String(p.id),
          full_name: p.full_name,
          gender: p.gender || 'other',
          birth_year: p.birth_date ? new Date(p.birth_date).getFullYear() : null,
          birth_order: p.birth_order || 1,
          generation: p.generation || null,
          is_in_law: false,
          avatar_url: p.avatar,
        }));

        // Build relationships array (children and marriages)
        const rels = [];
        
        // 1. Biological children relationships
        peopleRes.data.forEach(p => {
          if (p.father_id) {
            rels.push({
              type: 'biological_child',
              person_a: String(p.father_id),
              person_b: String(p.id)
            });
          }
          if (p.mother_id) {
            rels.push({
              type: 'biological_child',
              person_a: String(p.mother_id),
              person_b: String(p.id)
            });
          }
        });

        // 2. Marriage relationships
        marriagesRes.data.forEach(m => {
          if (m.husband_id && m.wife_id) {
            rels.push({
              type: 'marriage',
              person_a: String(m.husband_id),
              person_b: String(m.wife_id)
            });
          }
        });

        setPeople(mappedPeople);
        setRelationships(rels);
      })
      .catch(() => setError('Không thể tải dữ liệu phả hệ.'))
      .finally(() => setLoading(false));
  }, [selectedFamilyId]);

  return (
    <div className="p-4 sm:p-8 max-w-7xl mx-auto w-full">
      <div className="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
        <div>
          <h1 className="text-2xl sm:text-3xl font-serif font-bold text-stone-800">
            Tra cứu danh xưng
          </h1>
          <p className="text-stone-500 mt-1">
            Xác định cách gọi tên đúng giữa hai thành viên bất kỳ trong dòng họ.
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
        <div className="flex flex-col items-center justify-center py-24 text-stone-400">
          <div className="w-10 h-10 border-3 border-amber-500 border-t-transparent rounded-full animate-spin mb-4"></div>
          <p className="text-sm font-medium">Đang chuẩn bị dữ liệu phả hệ...</p>
        </div>
      ) : error ? (
        <div className="p-6 bg-red-50 border border-red-100 rounded-2xl flex items-center gap-4 text-red-700">
          <AlertCircle size={24} />
          <p className="font-medium">{error}</p>
        </div>
      ) : families.length === 0 ? (
        <div className="flex flex-col items-center justify-center py-24 bg-white rounded-2xl border border-stone-200 border-dashed">
          <div className="text-5xl mb-4">🌿</div>
          <h3 className="font-bold text-stone-700 mb-1">Chưa có gia phả nào</h3>
          <p className="text-stone-400 text-sm">Bạn cần tạo gia phả và thêm thành viên trước khi sử dụng tính năng này.</p>
        </div>
      ) : (
        <div className="grid grid-cols-1 gap-8">
          <KinshipFinder 
            persons={people} 
            relationships={relationships} 
          />
        </div>
      )}
    </div>
  );
}
