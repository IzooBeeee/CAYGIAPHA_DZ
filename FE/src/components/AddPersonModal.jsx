import React, { useEffect, useState } from 'react';
import { X, User, Heart, Baby, MapPin, AlignLeft, Calendar, Clock, Link as LinkIcon, Users } from 'lucide-react';
import api from '../utils/api';

export default function AddPersonModal({ familyId, onClose, onSaved }) {
  const [form, setForm] = useState({
    full_name: '',
    gender: 'male',
    birth_date: '',
    death_date: '',
    birth_place: '',
    biography: '',
    parent_marriage_id: '', // Selected marriage for parents
    spouse_id: '', // Selected spouse for marriage
  });
  const [loading, setLoading] = useState(false);
  const [fetching, setFetching] = useState(true);
  const [people, setPeople] = useState([]);
  const [marriages, setMarriages] = useState([]);
  const [error, setError] = useState('');

  useEffect(() => {
    const fetchData = async () => {
      try {
        const [peopleRes, marriagesRes] = await Promise.all([
          api.get(`/families/${familyId}/people`),
          api.get(`/families/${familyId}/marriages`)
        ]);
        setPeople(peopleRes.data);
        setMarriages(marriagesRes.data);
      } catch (err) {
        setError('Không thể tải dữ liệu gia đình.');
      } finally {
        setFetching(false);
      }
    };
    fetchData();
  }, [familyId]);

  const handleChange = (e) => {
    setForm(prev => ({ ...prev, [e.target.name]: e.target.value }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setLoading(true);
    try {
      // Find selected parents from marriage
      let fatherId = null;
      let motherId = null;
      if (form.parent_marriage_id) {
        const marriage = marriages.find(m => m.id === Number(form.parent_marriage_id));
        if (marriage) {
          fatherId = marriage.husband_id;
          motherId = marriage.wife_id;
        }
      }

      // 1. Create the person
      const payload = {
        full_name: form.full_name,
        gender: form.gender || null,
        birth_date: form.birth_date || null,
        death_date: form.death_date || null,
        birth_place: form.birth_place || null,
        biography: form.biography || null,
        father_id: fatherId,
        mother_id: motherId,
      };
      const res = await api.post(`/families/${familyId}/people`, payload);
      const newPerson = res.data;

      // 2. If spouse is selected, create marriage record
      if (form.spouse_id) {
        const spouseId = Number(form.spouse_id);
        const marriagePayload = {
          husband_id: form.gender === 'male' ? newPerson.id : spouseId,
          wife_id: form.gender === 'female' ? newPerson.id : spouseId,
          status: 'married',
        };
        await api.post(`/families/${familyId}/marriages`, marriagePayload);
      }

      onSaved(newPerson);
    } catch (err) {
      const msgs = err.response?.data?.errors;
      if (msgs) {
        setError(Object.values(msgs).flat().join(', '));
      } else {
        setError(err.response?.data?.message || 'Lỗi khi thêm thành viên.');
      }
    } finally {
      setLoading(false);
    }
  };

  const potentialSpouses = people.filter(p => {
    if (form.gender === 'male') return p.gender === 'female' || !p.gender;
    if (form.gender === 'female') return p.gender === 'male' || !p.gender;
    return true;
  });

  return (
    <div className="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-stone-900/60 backdrop-blur-md">
      <div className="bg-white rounded-[2.5rem] shadow-2xl border border-stone-200 w-full max-w-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
        {/* Header */}
        <div className="flex items-center justify-between px-10 py-8 border-b border-stone-100 bg-gradient-to-br from-stone-50 via-white to-amber-50/20">
          <div className="flex items-center gap-5">
            <div className="w-14 h-14 rounded-2xl bg-stone-900 text-white flex items-center justify-center shadow-xl shadow-stone-200 rotate-3 group-hover:rotate-0 transition-transform">
              <User size={28} />
            </div>
            <div>
              <h2 className="text-2xl font-bold text-stone-800 font-serif tracking-tight">Thêm thành viên</h2>
              <p className="text-xs text-stone-400 mt-1 font-medium uppercase tracking-wider">Genealogy Management System</p>
            </div>
          </div>
          <button
            onClick={onClose}
            className="w-12 h-12 rounded-full hover:bg-stone-100 flex items-center justify-center text-stone-400 hover:text-stone-700 transition-all active:scale-90 border border-transparent hover:border-stone-200"
          >
            <X size={24} />
          </button>
        </div>

        {/* Form Body */}
        <form onSubmit={handleSubmit} className="p-10 space-y-8 max-h-[70vh] overflow-y-auto custom-scrollbar bg-white">
          {error && (
            <div className="p-5 bg-red-50 text-red-600 text-sm rounded-2xl border border-red-100 flex items-center gap-4 animate-shake">
              <div className="w-2.5 h-2.5 rounded-full bg-red-500 shadow-[0_0_10px_rgba(239,68,68,0.5)]" />
              {error}
            </div>
          )}

          {/* Section: Identity */}
          <div className="space-y-6">
            <div className="flex items-center gap-3 mb-2">
              <div className="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center text-amber-600">
                <AlignLeft size={16} />
              </div>
              <h3 className="text-sm font-bold text-stone-700 uppercase tracking-widest">Thông tin định danh</h3>
            </div>
            
            <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
              <div className="space-y-2">
                <label className="text-xs font-bold text-stone-500 ml-1 uppercase">Họ và tên <span className="text-red-500">*</span></label>
                <input
                  type="text"
                  name="full_name"
                  required
                  value={form.full_name}
                  onChange={handleChange}
                  className="w-full px-6 py-4 rounded-2xl border border-stone-200 focus:outline-none focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all bg-stone-50/50 focus:bg-white text-sm font-medium"
                  placeholder="VD: Nguyễn Văn A"
                />
              </div>

              <div className="space-y-2">
                <label className="text-xs font-bold text-stone-500 ml-1 uppercase">Giới tính</label>
                <div className="grid grid-cols-3 gap-3">
                  {['male', 'female', 'other'].map(g => (
                    <button
                      key={g}
                      type="button"
                      onClick={() => setForm(f => ({ ...f, gender: g }))}
                      className={`py-4 rounded-2xl text-[11px] font-bold transition-all border shadow-sm ${
                        form.gender === g 
                          ? 'bg-stone-900 text-white border-stone-900 shadow-stone-200' 
                          : 'bg-white text-stone-500 border-stone-200 hover:border-stone-400'
                      }`}
                    >
                      {g === 'male' ? 'NAM' : g === 'female' ? 'NỮ' : 'KHÁC'}
                    </button>
                  ))}
                </div>
              </div>
            </div>
          </div>

          {/* Section: Timeline */}
          <div className="p-8 bg-stone-50/50 rounded-[2.5rem] border border-stone-100 space-y-6">
            <div className="flex items-center gap-3 mb-2">
              <div className="w-8 h-8 rounded-lg bg-sky-100 flex items-center justify-center text-sky-600">
                <Calendar size={16} />
              </div>
              <h3 className="text-sm font-bold text-stone-700 uppercase tracking-widest">Dòng thời gian</h3>
            </div>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
              <div className="space-y-2">
                <label className="text-xs font-bold text-stone-500 ml-1 uppercase">Ngày sinh</label>
                <input
                  type="date"
                  name="birth_date"
                  value={form.birth_date}
                  onChange={handleChange}
                  className="w-full px-6 py-4 rounded-2xl border border-stone-200 focus:outline-none focus:border-amber-500 transition-all bg-white text-sm font-medium"
                />
              </div>
              <div className="space-y-2">
                <label className="text-xs font-bold text-stone-500 ml-1 uppercase">Ngày mất</label>
                <input
                  type="date"
                  name="death_date"
                  value={form.death_date}
                  onChange={handleChange}
                  className="w-full px-6 py-4 rounded-2xl border border-stone-200 focus:outline-none focus:border-amber-500 transition-all bg-white text-sm font-medium"
                />
              </div>
            </div>
            <div className="space-y-2">
              <label className="text-xs font-bold text-stone-500 ml-1 uppercase">Quê quán / Nơi sinh</label>
              <div className="relative">
                <MapPin className="absolute left-5 top-1/2 -translate-y-1/2 text-stone-400" size={18} />
                <input
                  type="text"
                  name="birth_place"
                  value={form.birth_place}
                  onChange={handleChange}
                  className="w-full pl-12 pr-6 py-4 rounded-2xl border border-stone-200 focus:outline-none focus:border-amber-500 transition-all bg-white text-sm font-medium"
                  placeholder="VD: Hà Nội, Việt Nam"
                />
              </div>
            </div>
          </div>

          {/* Section: Relationships (The "Logical" part) */}
          <div className="p-8 bg-amber-50/40 rounded-[2.5rem] border border-amber-100 space-y-8">
            <div className="flex items-center gap-3 mb-2">
              <div className="w-8 h-8 rounded-lg bg-amber-500 flex items-center justify-center text-white">
                <Users size={16} />
              </div>
              <h3 className="text-sm font-bold text-amber-800 uppercase tracking-widest">Quan hệ huyết thống & Hôn nhân</h3>
            </div>

            {/* Parent Selection (Based on Couples) */}
            <div className="space-y-2">
              <label className="flex items-center justify-between text-xs font-bold text-amber-700 ml-1 uppercase">
                <span>Gia đình Cha & Mẹ</span>
                <span className="text-[10px] lowercase font-medium opacity-60">* Con của cặp đôi nào?</span>
              </label>
              <select
                name="parent_marriage_id"
                value={form.parent_marriage_id}
                onChange={handleChange}
                className="w-full px-6 py-4 rounded-2xl border border-amber-200 focus:outline-none focus:border-amber-500 transition-all bg-white text-sm font-medium appearance-none shadow-sm"
              >
                <option value="">-- Thành viên đời đầu (Không chọn cha mẹ) --</option>
                {marriages.map(m => (
                  <option key={m.id} value={m.id}>
                    GĐ: {m.husband?.full_name || '...'} ❤️ {m.wife?.full_name || '...'}
                  </option>
                ))}
              </select>
              <p className="text-[10px] text-amber-600/70 ml-1 italic">
                💡 Theo logic gia phả: Cha + Mẹ (vợ chồng) thì mới có con.
              </p>
            </div>

            {/* Spouse Selection */}
            <div className="space-y-2">
              <label className="flex items-center justify-between text-xs font-bold text-amber-700 ml-1 uppercase">
                <span>Vợ / Chồng hiện tại</span>
                <span className="text-[10px] lowercase font-medium opacity-60">* Đã kết hôn với ai?</span>
              </label>
              <div className="relative">
                <LinkIcon className="absolute left-5 top-1/2 -translate-y-1/2 text-rose-400" size={18} />
                <select
                  name="spouse_id"
                  value={form.spouse_id}
                  onChange={handleChange}
                  className="w-full pl-12 pr-6 py-4 rounded-2xl border border-amber-200 focus:outline-none focus:border-amber-500 transition-all bg-white text-sm font-medium appearance-none shadow-sm"
                >
                  <option value="">-- Chưa có vợ/chồng --</option>
                  {potentialSpouses.map(p => (
                    <option key={p.id} value={p.id}>{p.full_name}</option>
                  ))}
                </select>
              </div>
              <p className="text-[10px] text-amber-600/70 ml-1 italic">
                💡 Con có vợ/chồng thì sau này mới có thể thêm con cho họ.
              </p>
            </div>
          </div>

          {/* Section: Bio */}
          <div className="space-y-3">
            <label className="text-xs font-bold text-stone-500 ml-1 uppercase tracking-widest">Tiểu sử cuộc đời</label>
            <textarea
              name="biography"
              value={form.biography}
              onChange={handleChange}
              rows={4}
              className="w-full px-6 py-5 rounded-[2rem] border border-stone-200 focus:outline-none focus:border-amber-500 transition-all bg-stone-50/30 focus:bg-white text-sm font-medium resize-none placeholder:text-stone-300 shadow-inner"
              placeholder="Ghi chú lại những cột mốc quan trọng..."
            />
          </div>
        </form>

        {/* Footer */}
        <div className="flex items-center justify-end gap-5 px-10 py-8 border-t border-stone-100 bg-stone-50/50">
          <button
            type="button"
            onClick={onClose}
            className="px-6 py-3 text-stone-400 hover:text-stone-800 font-bold text-sm transition-colors uppercase tracking-widest"
          >
            Hủy bỏ
          </button>
          <button
            onClick={handleSubmit}
            disabled={loading}
            className="px-10 py-4 bg-stone-900 text-white hover:bg-black rounded-2xl text-sm font-bold transition-all shadow-2xl shadow-stone-200 active:scale-95 disabled:opacity-60 flex items-center gap-3"
          >
            {loading ? (
              <div className="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
            ) : (
              <Users size={18} />
            )}
            {loading ? 'ĐANG LƯU...' : 'XÁC NHẬN THÊM'}
          </button>
        </div>
      </div>
    </div>
  );
}
