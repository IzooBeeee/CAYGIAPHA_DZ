import PersonCard from './PersonCard';
import { Person } from '../types';
import { ArrowUpDown, Filter, Plus, Search } from 'lucide-react';
import { useMemo, useState, useEffect } from 'react';
import { useDashboard } from './DashboardContext';

export default function DashboardMemberList({
  initialPersons,
  canEdit = false,
}: {
  initialPersons: Person[];
  canEdit?: boolean;
}) {
  const { setShowCreateMember } = useDashboard();
  const [searchTerm, setSearchTerm] = useState('');
  const [sortOption, setSortOption] = useState('birth_asc');
  const [filterOption, setFilterOption] = useState('all');
  const [localPersons, setLocalPersons] = useState<Person[]>(initialPersons);

  useEffect(() => {
    setLocalPersons(initialPersons);
  }, [initialPersons]);

  const handleDeleted = (id: number) => {
    setLocalPersons(prev => prev.filter(p => p.id !== id));
  };

  const filteredPersons = useMemo(() => {
    return localPersons.filter((person) => {
      const matchesSearch = person.full_name.toLowerCase().includes(searchTerm.toLowerCase());

      let matchesFilter = true;
      switch (filterOption) {
        case 'male':
          matchesFilter = person.gender === 'male';
          break;
        case 'female':
          matchesFilter = person.gender === 'female';
          break;
        case 'deceased':
          matchesFilter = !!person.death_date;
          break;
        default:
          matchesFilter = true;
      }

      return matchesSearch && matchesFilter;
    });
  }, [localPersons, searchTerm, filterOption]);

  const sortedPersons = useMemo(() => {
    return [...filteredPersons].sort((a, b) => {
      switch (sortOption) {
        case 'birth_asc':
          return (a.birth_date || '9999').localeCompare(b.birth_date || '9999');
        case 'birth_desc':
          return (b.birth_date || '0').localeCompare(a.birth_date || '0');
        case 'name_asc':
          return a.full_name.localeCompare(b.full_name, 'vi');
        case 'name_desc':
          return b.full_name.localeCompare(a.full_name, 'vi');
        default:
          return 0;
      }
    });
  }, [filteredPersons, sortOption]);

  return (
    <>
      <div className="mb-8 relative">
        <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white/60 backdrop-blur-xl p-4 sm:p-5 rounded-2xl shadow-sm border border-stone-200/60 relative z-10 w-full">
          <div className="flex flex-col sm:flex-row gap-4 w-full sm:w-auto flex-1">
            {/* Search */}
            <div className="relative flex-1 max-w-sm group">
              <Search className="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 text-stone-400 group-focus-within:text-amber-500 transition-colors" />
              <input
                type="text"
                placeholder="Tìm kiếm thành viên..."
                className="bg-white/90 text-stone-900 w-full pl-10 pr-4 py-2.5 rounded-xl border border-stone-200/80 shadow-sm placeholder-stone-400 focus:outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-500/20 transition-all"
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
              />
            </div>

            <div className="flex gap-2 sm:gap-3 w-full sm:w-auto items-center">
              {/* Filter */}
              <div className="relative w-full sm:w-auto">
                <Filter className="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-stone-400 pointer-events-none" />
                <select
                  className="appearance-none bg-white/90 text-stone-700 w-full sm:w-40 pl-9 pr-8 py-2.5 rounded-xl border border-stone-200/80 shadow-sm focus:outline-none focus:border-amber-400 font-medium text-sm transition-all"
                  value={filterOption}
                  onChange={(e) => setFilterOption(e.target.value)}
                >
                  <option value="all">Tất cả</option>
                  <option value="male">Nam</option>
                  <option value="female">Nữ</option>
                  <option value="deceased">Đã mất</option>
                </select>
              </div>

              {/* Sort */}
              <div className="relative w-full sm:w-auto">
                <ArrowUpDown className="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-stone-400 pointer-events-none" />
                <select
                  className="appearance-none bg-white/90 text-stone-700 w-full sm:w-52 pl-9 pr-8 py-2.5 rounded-xl border border-stone-200/80 shadow-sm focus:outline-none focus:border-amber-400 font-medium text-sm transition-all"
                  value={sortOption}
                  onChange={(e) => setSortOption(e.target.value)}
                >
                  <option value="birth_asc">Năm sinh (Tăng dần)</option>
                  <option value="birth_desc">Năm sinh (Giảm dần)</option>
                  <option value="name_asc">Tên (A-Z)</option>
                  <option value="name_desc">Tên (Z-A)</option>
                </select>
              </div>
            </div>
          </div>

          {canEdit && (
            <button
              onClick={() => setShowCreateMember(true)}
              className="inline-flex items-center gap-2 px-4 py-2.5 bg-stone-900 text-white font-medium rounded-xl hover:bg-stone-800 transition-colors text-sm"
            >
              <Plus className="size-4" strokeWidth={2.5} />
              Thêm thành viên
            </button>
          )}
        </div>
      </div>

      {sortedPersons.length > 0 ? (
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          {sortedPersons.map((person) => (
            <PersonCard 
              key={person.id} 
              person={person} 
              canEdit={canEdit}
              onDeleted={handleDeleted}
            />
          ))}
        </div>
      ) : (
        <div className="text-center py-12 text-stone-400 italic">
          {localPersons.length > 0 ? 'Không tìm thấy thành viên phù hợp.' : 'Chưa có thành viên nào. Hãy thêm thành viên đầu tiên.'}
        </div>
      )}
    </>
  );
}
