import { Person } from '../types';
import { useDashboard } from './DashboardContext';
import DefaultAvatar from './DefaultAvatar';
import { FemaleIcon, MaleIcon } from './GenderIcons';
import { Trash2 } from 'lucide-react';
import api from '../utils/api';

interface PersonCardProps {
  person: Person;
  canEdit?: boolean;
  onDeleted?: (id: number) => void;
}

const getAvatarBg = (gender: string | null) => {
  if (gender === 'male') return 'bg-sky-400';
  if (gender === 'female') return 'bg-rose-400';
  return 'bg-stone-400';
};

const getGenderStyle = (gender: string | null) => {
  if (gender === 'male') return 'bg-sky-100 text-sky-600';
  if (gender === 'female') return 'bg-rose-100 text-rose-600';
  return 'bg-stone-100 text-stone-600';
};

export default function PersonCard({ person, canEdit, onDeleted }: PersonCardProps) {
  const { setMemberModalId } = useDashboard();
  const isDeceased = !!person.death_date;

  const handleDelete = async (e: React.MouseEvent) => {
    e.stopPropagation();
    if (!window.confirm(`Bạn có chắc chắn muốn xoá ${person.full_name} khỏi gia phả?`)) return;
    
    try {
      await api.delete(`/people/${person.id}`);
      if (onDeleted) onDeleted(person.id);
    } catch {
      alert('Lỗi khi xoá thành viên.');
    }
  };

  return (
    <div className="relative group">
      <button
        onClick={() => setMemberModalId(person.id)}
        className={`block relative bg-white/60 p-4 rounded-2xl shadow-sm border border-stone-200/60 hover:border-amber-300 hover:shadow-md hover:bg-white/90 transition-all duration-300 overflow-hidden w-full text-left
          ${isDeceased ? 'opacity-80' : ''}`}
      >
        <div className="flex items-center space-x-4 relative z-10">
          <div className="relative">
            <div
              className={`size-14 sm:size-16 rounded-full flex items-center justify-center text-xl font-bold text-white overflow-hidden shrink-0 shadow-lg ring-2 ring-white transition-transform duration-300 group-hover:scale-105
              ${getAvatarBg(person.gender)}`}
            >
              {person.avatar ? (
                <img
                  src={person.avatar}
                  alt={person.full_name}
                  className="h-full w-full object-cover"
                />
              ) : (
                <DefaultAvatar gender={person.gender} size={32} />
              )}
            </div>
            <div
              className={`absolute bottom-0 right-0 size-5 rounded-full ring-2 ring-white shadow-sm flex items-center justify-center ${getGenderStyle(person.gender)}`}
            >
              {person.gender === 'male' ? (
                <MaleIcon className="size-5" />
              ) : person.gender === 'female' ? (
                <FemaleIcon className="size-5" />
              ) : null}
            </div>
          </div>

          <div className="flex-1 min-w-0">
            <h3 className="text-base text-left sm:text-lg font-bold text-stone-900 group-hover:text-amber-700 transition-colors truncate mb-1">
              {person.full_name}
            </h3>
            <p className="text-xs font-medium text-stone-500 truncate flex items-center gap-1.5">
              <span className="truncate">
                {person.birth_date ? new Date(person.birth_date).getFullYear() : '...'}
                {isDeceased && ` → ${person.death_date ? new Date(person.death_date).getFullYear() : '...'}`}
              </span>
            </p>
            <div className="flex flex-wrap items-center gap-1.5 shrink-0 mt-2">
              {person.generation != null && (
                <span className="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60 uppercase tracking-widest">
                  Đời thứ {person.generation}
                </span>
              )}
            </div>
          </div>
        </div>
      </button>

      {canEdit && (
        <button
          onClick={handleDelete}
          className="absolute top-2 right-2 p-2 bg-white/80 text-stone-400 hover:text-red-600 hover:bg-red-50 rounded-full opacity-0 group-hover:opacity-100 transition-all z-20 shadow-sm border border-stone-100"
          title="Xoá thành viên"
        >
          <Trash2 size={16} />
        </button>
      )}
    </div>
  );
}
