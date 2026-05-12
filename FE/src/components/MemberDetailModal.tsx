import { Person } from "@/types";
import { AnimatePresence, motion } from "framer-motion";
import { AlertCircle, ArrowLeft, Edit2, X } from "lucide-react";
import { useCallback, useEffect, useState } from "react";
import api from "../utils/api";
import { useDashboard } from "./DashboardContext";
import { useUser } from "./UserProvider";
import MemberForm from "./MemberForm";
import DefaultAvatar from "./DefaultAvatar";
import { FemaleIcon, MaleIcon } from "./GenderIcons";

function formatDate(d: string | null) {
  if (!d) return null;
  try {
    return new Date(d).toLocaleDateString("vi-VN", {
      year: "numeric",
      month: "long",
      day: "numeric",
    });
  } catch {
    return d;
  }
}

function DetailView({ person, onEdit, canEdit }: { person: Person; onEdit: () => void; canEdit: boolean }) {
  const isDeceased = !!person.death_date;
  const avatarBg = person.gender === "male" ? "bg-sky-400" : person.gender === "female" ? "bg-rose-400" : "bg-stone-400";

  return (
    <div className="p-6 sm:p-8 space-y-6">
      {/* Header */}
      <div className="flex items-start gap-5">
        <div className={`size-20 rounded-2xl flex items-center justify-center text-white text-2xl font-bold shadow-lg overflow-hidden shrink-0 ${avatarBg}`}>
          {person.avatar ? (
            <img src={person.avatar} alt={person.full_name} className="w-full h-full object-cover" />
          ) : (
            <DefaultAvatar gender={person.gender} size={40} />
          )}
        </div>
        <div className="flex-1 min-w-0">
          <h2 className="text-2xl font-serif font-bold text-stone-900 truncate">{person.full_name}</h2>
          <div className="flex items-center gap-2 mt-1.5">
            <span className={`inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full ${
              person.gender === "male" ? "bg-sky-100 text-sky-700" : person.gender === "female" ? "bg-rose-100 text-rose-700" : "bg-stone-100 text-stone-600"
            }`}>
              {person.gender === "male" ? <><MaleIcon className="size-3" /> Nam</> : person.gender === "female" ? <><FemaleIcon className="size-3" /> Nữ</> : "Khác"}
            </span>
            {isDeceased && (
              <span className="text-xs font-medium px-2.5 py-1 rounded-full bg-stone-100 text-stone-500">Đã mất</span>
            )}
          </div>
          {canEdit && (
            <button onClick={onEdit} className="mt-3 flex items-center gap-1.5 px-4 py-2 bg-amber-100 text-amber-800 rounded-xl text-sm font-semibold hover:bg-amber-200 transition-colors">
              <Edit2 className="size-3.5" /> Chỉnh sửa
            </button>
          )}
        </div>
      </div>

      {/* Info Grid */}
      <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
        {person.birth_date && (
          <InfoItem label="Ngày sinh" value={formatDate(person.birth_date)} />
        )}
        {person.death_date && (
          <InfoItem label="Ngày mất" value={formatDate(person.death_date)} />
        )}
        {person.birth_place && (
          <InfoItem label="Quê quán" value={person.birth_place} />
        )}
      </div>

      {/* Biography */}
      {person.biography && (
        <div className="pt-4 border-t border-stone-100">
          <h3 className="text-sm font-bold text-stone-500 uppercase tracking-wider mb-2">Tiểu sử</h3>
          <p className="text-stone-700 leading-relaxed whitespace-pre-wrap">{person.biography}</p>
        </div>
      )}
    </div>
  );
}

function InfoItem({ label, value }: { label: string; value: string | null }) {
  if (!value) return null;
  return (
    <div className="p-4 bg-stone-50 rounded-xl border border-stone-100">
      <p className="text-[11px] font-bold text-stone-400 uppercase tracking-wider mb-1">{label}</p>
      <p className="text-sm font-medium text-stone-800">{value}</p>
    </div>
  );
}

export default function MemberDetailModal() {
  const { memberModalId: memberId, setMemberModalId, showCreateMember, setShowCreateMember } = useDashboard();
  const { isAdmin } = useUser();
  const [isOpen, setIsOpen] = useState(false);
  const [isEditing, setIsEditing] = useState(false);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [person, setPerson] = useState<Person | null>(null);

  const closeModal = () => {
    setMemberModalId(null);
    setShowCreateMember(false);
    setIsEditing(false);
  };

  const fetchData = useCallback(async (id: number) => {
    setLoading(true);
    setError(null);
    try {
      const res = await api.get(`/people/${id}`);
      setPerson(res.data);
    } catch {
      setError("Không thể tải thông tin thành viên.");
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    if (memberId) {
      setIsOpen(true);
      setIsEditing(false);
      fetchData(memberId);
    } else if (showCreateMember) {
      setIsOpen(true);
      setIsEditing(true);
      setPerson(null);
      setError(null);
    } else {
      setIsOpen(false);
      setTimeout(() => {
        setPerson(null);
        setError(null);
        setIsEditing(false);
      }, 300);
    }
  }, [memberId, showCreateMember, fetchData]);

  useEffect(() => {
    if (isOpen) {
      document.body.style.overflow = "hidden";
    } else {
      document.body.style.overflow = "unset";
    }
    return () => { document.body.style.overflow = "unset"; };
  }, [isOpen]);

  const handleSaved = () => {
    setIsEditing(false);
    if (memberId) {
      fetchData(memberId);
    } else {
      closeModal();
      window.location.reload();
    }
  };

  return (
    <AnimatePresence>
      {isOpen && (
        <motion.div
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          exit={{ opacity: 0 }}
          className="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 bg-stone-900/40 backdrop-blur-sm"
        >
          {!isEditing && <div className="absolute inset-0" onClick={closeModal} />}

          <motion.div
            initial={{ scale: 0.96, opacity: 0, y: 15 }}
            animate={{ scale: 1, opacity: 1, y: 0 }}
            exit={{ scale: 0.96, opacity: 0, y: 15 }}
            transition={{ duration: 0.25, ease: "easeOut" }}
            className="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col border border-stone-200"
          >
            {/* Close button */}
            <div className="absolute top-4 right-4 z-20 flex items-center gap-2">
              {isEditing && person && (
                <button onClick={() => setIsEditing(false)} className="flex items-center gap-1.5 px-3 py-2 bg-stone-100 text-stone-700 rounded-full hover:bg-stone-200 text-sm font-medium transition-colors">
                  <ArrowLeft className="size-4" /> Quay lại
                </button>
              )}
              <button onClick={closeModal} className="size-10 flex items-center justify-center bg-stone-100 text-stone-600 rounded-full hover:bg-stone-200 transition-colors">
                <X className="size-5" />
              </button>
            </div>

            <div className="flex-1 overflow-y-auto">
              {loading ? (
                <div className="flex items-center justify-center min-h-[400px] flex-col gap-4">
                  <div className="size-10 border-4 border-amber-600 border-t-transparent rounded-full animate-spin" />
                  <p className="text-stone-500 font-medium">Đang tải...</p>
                </div>
              ) : error ? (
                <div className="flex items-center justify-center min-h-[400px] flex-col gap-4 p-8 text-center">
                  <div className="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center">
                    <AlertCircle className="size-8" />
                  </div>
                  <p className="text-red-600 font-medium">{error}</p>
                  <button onClick={closeModal} className="px-6 py-2.5 bg-stone-100 hover:bg-stone-200 text-stone-700 font-semibold rounded-full transition-colors">
                    Đóng
                  </button>
                </div>
              ) : isEditing ? (
                <div className="p-6 sm:p-8 pt-14">
                  <h2 className="text-xl font-serif font-bold text-stone-800 mb-6">
                    {person ? "Chỉnh sửa thành viên" : "Thêm thành viên mới"}
                  </h2>
                  <MemberForm initialData={person} onSuccess={handleSaved} onCancel={person ? () => setIsEditing(false) : closeModal} />
                </div>
              ) : person ? (
                <DetailView person={person} onEdit={() => setIsEditing(true)} canEdit={isAdmin} />
              ) : null}
            </div>
          </motion.div>
        </motion.div>
      )}
    </AnimatePresence>
  );
}
