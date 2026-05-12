import React, { useState } from 'react';

const genderColor = (gender) => {
  if (gender === 'male') return { bg: 'bg-sky-500', light: 'bg-sky-50', border: 'border-sky-200', text: 'text-sky-700' };
  if (gender === 'female') return { bg: 'bg-rose-400', light: 'bg-rose-50', border: 'border-rose-200', text: 'text-rose-700' };
  return { bg: 'bg-stone-400', light: 'bg-stone-50', border: 'border-stone-200', text: 'text-stone-600' };
};

const PersonNode = ({ person, isSpouse = false }) => {
  const [hovered, setHovered] = useState(false);
  const colors = genderColor(person.gender);
  const isDead = !!person.death_date;
  const initials = person.full_name.split(' ').slice(-2).map(w => w[0]).join('');
  const years = person.birth_date
    ? `${person.birth_date}${isDead ? ` – ${person.death_date}` : ''}`
    : '';

  return (
    <div
      className="relative group"
      onMouseEnter={() => setHovered(true)}
      onMouseLeave={() => setHovered(false)}
    >
      {/* Card */}
      <div className={`
        flex flex-col items-center gap-2 px-4 py-3 rounded-2xl border-2 transition-all duration-200 cursor-pointer
        ${isSpouse ? `${colors.light} ${colors.border} shadow-sm` : 'bg-white border-stone-200 shadow-md hover:shadow-xl hover:-translate-y-0.5'}
        ${isDead ? 'opacity-70' : ''}
        w-[148px]
      `}>
        {/* Avatar */}
        <div className={`w-12 h-12 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md ring-2 ring-white ${colors.bg}`}>
          {person.avatar
            ? <img src={person.avatar} alt={person.full_name} className="w-full h-full rounded-full object-cover" />
            : initials
          }
        </div>

        {/* Name */}
        <div className="text-center">
          <p className={`font-bold text-xs leading-tight text-stone-800 line-clamp-2`}>
            {person.full_name}
          </p>
          {years && (
            <p className="text-[10px] text-stone-400 mt-0.5">{years}</p>
          )}
        </div>

        {/* Gender badge */}
        <span className={`text-[9px] font-bold uppercase tracking-widest px-2 py-0.5 rounded-full ${colors.light} ${colors.text} border ${colors.border}`}>
          {person.gender === 'male' ? 'Nam' : person.gender === 'female' ? 'Nữ' : '—'}
          {isDead ? ' · Đã mất' : ''}
        </span>
      </div>

      {/* Tooltip */}
      {hovered && person.biography && (
        <div className="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 z-50 w-64 bg-stone-900 text-white text-xs rounded-xl p-3 shadow-2xl pointer-events-none">
          <p className="font-semibold mb-1">{person.full_name}</p>
          <p className="text-stone-300 leading-relaxed line-clamp-4">{person.biography}</p>
          {person.birth_place && (
            <p className="text-stone-400 mt-1">📍 {person.birth_place}</p>
          )}
          <div className="absolute bottom-[-6px] left-1/2 -translate-x-1/2 w-3 h-3 bg-stone-900 rotate-45"></div>
        </div>
      )}
    </div>
  );
};

const CoupleNode = ({ person, spouses }) => (
  <div className="flex items-center gap-0">
    <PersonNode person={person} />
    {spouses && spouses.map((spouse, i) => (
      <React.Fragment key={spouse.id}>
        {/* Marriage link */}
        <div className="flex items-center">
          <div className="w-6 h-0.5 bg-amber-400"></div>
          <div className="w-5 h-5 rounded-full bg-amber-100 border-2 border-amber-400 flex items-center justify-center">
            <span className="text-[8px] text-amber-600 font-bold">♥</span>
          </div>
          <div className="w-6 h-0.5 bg-amber-400"></div>
        </div>
        <PersonNode person={spouse} isSpouse />
      </React.Fragment>
    ))}
  </div>
);

const TreeNode = ({ node, depth = 0 }) => {
  const [collapsed, setCollapsed] = useState(false);
  const hasChildren = node.children && node.children.length > 0;

  return (
    <div className="flex flex-col items-center">
      {/* This person + spouses */}
      <div className="relative">
        <CoupleNode person={node} spouses={node.spouses || []} />

        {/* Collapse toggle */}
        {hasChildren && (
          <button
            onClick={() => setCollapsed(!collapsed)}
            className="absolute -bottom-3 left-1/2 -translate-x-1/2 z-20 w-6 h-6 rounded-full bg-white border-2 border-stone-300 hover:border-amber-400 flex items-center justify-center shadow text-stone-500 hover:text-amber-600 transition-all"
          >
            <span className="text-[10px] font-bold">{collapsed ? '+' : '−'}</span>
          </button>
        )}
      </div>

      {/* Children */}
      {hasChildren && !collapsed && (
        <>
          {/* Vertical line down */}
          <div className="w-0.5 h-8 bg-stone-300 mt-3"></div>

          {/* Horizontal bar connecting children */}
          <div className="relative flex items-start justify-center gap-0">
            {node.children.length > 1 && (
              <div
                className="absolute top-0 h-0.5 bg-stone-300"
                style={{ left: '50%', right: '50%', transform: 'none', width: '100%', left: 0 }}
              ></div>
            )}

            <div className="flex gap-8 relative">
              {/* Top horizontal connector */}
              {node.children.length > 1 && (
                <div
                  className="absolute top-0 left-[calc(50%_-_0.5px)] right-0 h-0.5 bg-stone-200 -translate-y-0.5"
                  style={{
                    left: `calc(50% / ${node.children.length})`,
                    right: `calc(50% / ${node.children.length})`,
                  }}
                />
              )}

              {node.children.map((child, idx) => (
                <div key={child.id} className="flex flex-col items-center">
                  {/* Vertical line up for each child */}
                  <div className="w-0.5 h-8 bg-stone-300"></div>
                  <TreeNode node={child} depth={depth + 1} />
                </div>
              ))}
            </div>
          </div>
        </>
      )}
    </div>
  );
};

export default function ApiFamilyTree({ data }) {
  if (!data) {
    return (
      <div className="flex items-center justify-center h-64 text-stone-400">
        <div className="text-center">
          <div className="text-4xl mb-3">🌳</div>
          <p>Chưa có dữ liệu cây gia phả</p>
        </div>
      </div>
    );
  }

  // Support both old format (data is tree directly) and new format (data.tree)
  const treeRoot = data.tree || data;
  const family = data.family;
  const totalMembers = data.total_members;

  return (
    <div className="w-full h-full overflow-auto bg-gradient-to-br from-stone-50 via-amber-50/30 to-stone-50">
      {/* Header */}
      {family && (
        <div className="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-stone-200 px-6 py-3 flex items-center justify-between">
          <div>
            <h2 className="font-serif font-bold text-stone-800 text-lg">{family.name}</h2>
            {family.description && (
              <p className="text-xs text-stone-500 mt-0.5 max-w-xl truncate">{family.description}</p>
            )}
          </div>
          {totalMembers && (
            <div className="flex items-center gap-2 px-3 py-1.5 bg-amber-50 border border-amber-200 rounded-full">
              <span className="text-amber-600 font-bold text-sm">{totalMembers}</span>
              <span className="text-amber-600 text-xs">thành viên</span>
            </div>
          )}
        </div>
      )}

      {/* Tree */}
      <div className="p-8 sm:p-12 min-w-max">
        {/* Generation labels guide */}
        <div className="flex items-center gap-4 mb-8 text-xs text-stone-400">
          <span className="flex items-center gap-1.5">
            <span className="w-3 h-3 rounded-full bg-sky-400 inline-block"></span> Nam
          </span>
          <span className="flex items-center gap-1.5">
            <span className="w-3 h-3 rounded-full bg-rose-400 inline-block"></span> Nữ
          </span>
          <span className="flex items-center gap-1.5">
            <span className="w-4 h-0.5 bg-amber-400 inline-block"></span>
            <span className="w-4 h-4 rounded-full border-2 border-amber-400 flex items-center justify-center text-amber-500 text-[8px] font-bold">♥</span>
            <span className="w-4 h-0.5 bg-amber-400 inline-block"></span>
            Hôn nhân
          </span>
          <span className="ml-2 text-stone-300">|</span>
          <span>Nhấn vào [-] để thu gọn nhánh</span>
        </div>

        <TreeNode node={treeRoot} depth={0} />
      </div>
    </div>
  );
}
