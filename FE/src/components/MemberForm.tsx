import { Person } from "@/types";
import { Loader2, MapPin, User } from "lucide-react";
import { useState } from "react";
import api from "../utils/api";
import { useDashboard } from "./DashboardContext";

interface MemberFormProps {
  initialData?: Person | null;
  onSuccess?: () => void;
  onCancel?: () => void;
}

export default function MemberForm({ initialData, onSuccess, onCancel }: MemberFormProps) {
  const { rootId } = useDashboard();
  const isEditing = !!initialData;

  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const [fullName, setFullName] = useState(initialData?.full_name || "");
  const [gender, setGender] = useState(initialData?.gender || "male");
  const [birthDate, setBirthDate] = useState(initialData?.birth_date?.split("T")[0] || "");
  const [deathDate, setDeathDate] = useState(initialData?.death_date?.split("T")[0] || "");
  const [birthPlace, setBirthPlace] = useState(initialData?.birth_place || "");
  const [biography, setBiography] = useState(initialData?.biography || "");

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!fullName.trim()) return;

    setLoading(true);
    setError(null);

    const payload = {
      full_name: fullName.trim(),
      gender: gender || null,
      birth_date: birthDate || null,
      death_date: deathDate || null,
      birth_place: birthPlace || null,
      biography: biography || null,
      father_id: initialData?.father_id || null,
      mother_id: initialData?.mother_id || null,
    };

    try {
      if (isEditing) {
        await api.put(`/people/${initialData.id}`, payload);
      } else {
        const familyId = initialData?.family_id || rootId;
        if (!familyId) {
          setError("Không tìm thấy gia phả. Vui lòng quay lại trang thành viên.");
          setLoading(false);
          return;
        }
        await api.post(`/families/${familyId}/people`, payload);
      }
      onSuccess?.();
    } catch (err: any) {
      const msgs = err.response?.data?.errors;
      if (msgs) {
        setError(Object.values(msgs).flat().join(", "));
      } else {
        setError(err.response?.data?.message || "Lỗi khi lưu thông tin.");
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-6">
      {error && (
        <div className="p-4 bg-red-50 text-red-600 text-sm rounded-xl border border-red-100">
          {error}
        </div>
      )}

      {/* Name & Gender */}
      <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div className="space-y-1.5">
          <label className="text-xs font-bold text-stone-500 uppercase">Họ và tên <span className="text-red-500">*</span></label>
          <div className="relative">
            <User className="absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 size-4" />
            <input
              type="text"
              required
              value={fullName}
              onChange={(e) => setFullName(e.target.value)}
              className="w-full pl-11 pr-4 py-3 rounded-xl border border-stone-200 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-400 text-sm font-medium bg-stone-50 focus:bg-white transition-all"
              placeholder="VD: Nguyễn Văn A"
            />
          </div>
        </div>

        <div className="space-y-1.5">
          <label className="text-xs font-bold text-stone-500 uppercase">Giới tính</label>
          <div className="grid grid-cols-3 gap-2">
            {(["male", "female", "other"] as const).map((g) => (
              <button
                key={g}
                type="button"
                onClick={() => setGender(g)}
                className={`py-3 rounded-xl text-xs font-bold transition-all border ${
                  gender === g
                    ? "bg-stone-900 text-white border-stone-900"
                    : "bg-white text-stone-500 border-stone-200 hover:border-stone-400"
                }`}
              >
                {g === "male" ? "NAM" : g === "female" ? "NỮ" : "KHÁC"}
              </button>
            ))}
          </div>
        </div>
      </div>

      {/* Dates */}
      <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div className="space-y-1.5">
          <label className="text-xs font-bold text-stone-500 uppercase">Ngày sinh</label>
          <input
            type="date"
            value={birthDate}
            onChange={(e) => setBirthDate(e.target.value)}
            className="w-full px-4 py-3 rounded-xl border border-stone-200 focus:outline-none focus:border-amber-400 text-sm font-medium bg-stone-50 focus:bg-white transition-all"
          />
        </div>
        <div className="space-y-1.5">
          <label className="text-xs font-bold text-stone-500 uppercase">Ngày mất</label>
          <input
            type="date"
            value={deathDate}
            onChange={(e) => setDeathDate(e.target.value)}
            className="w-full px-4 py-3 rounded-xl border border-stone-200 focus:outline-none focus:border-amber-400 text-sm font-medium bg-stone-50 focus:bg-white transition-all"
          />
        </div>
      </div>

      {/* Birth Place */}
      <div className="space-y-1.5">
        <label className="text-xs font-bold text-stone-500 uppercase">Quê quán</label>
        <div className="relative">
          <MapPin className="absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 size-4" />
          <input
            type="text"
            value={birthPlace}
            onChange={(e) => setBirthPlace(e.target.value)}
            className="w-full pl-11 pr-4 py-3 rounded-xl border border-stone-200 focus:outline-none focus:border-amber-400 text-sm font-medium bg-stone-50 focus:bg-white transition-all"
            placeholder="VD: Hà Nội, Việt Nam"
          />
        </div>
      </div>

      {/* Biography */}
      <div className="space-y-1.5">
        <label className="text-xs font-bold text-stone-500 uppercase">Tiểu sử</label>
        <textarea
          value={biography}
          onChange={(e) => setBiography(e.target.value)}
          rows={3}
          className="w-full px-4 py-3 rounded-xl border border-stone-200 focus:outline-none focus:border-amber-400 text-sm font-medium bg-stone-50 focus:bg-white transition-all resize-none"
          placeholder="Ghi chú về cuộc đời..."
        />
      </div>

      {/* Actions */}
      <div className="flex items-center justify-end gap-3 pt-4 border-t border-stone-100">
        <button
          type="button"
          onClick={onCancel}
          className="px-5 py-2.5 text-stone-600 hover:text-stone-900 font-medium text-sm transition-colors"
        >
          Hủy
        </button>
        <button
          type="submit"
          disabled={loading}
          className="px-6 py-2.5 bg-stone-900 text-white hover:bg-stone-800 rounded-xl text-sm font-bold transition-all disabled:opacity-60 flex items-center gap-2"
        >
          {loading && <Loader2 className="size-4 animate-spin" />}
          {isEditing ? "Lưu thay đổi" : "Thêm thành viên"}
        </button>
      </div>
    </form>
  );
}
