import { lazy, Suspense, useMemo } from 'react';
import { useDashboard } from './DashboardContext';
import DashboardMemberList from './DashboardMemberList';
import { Person } from '../types';

const ApiFamilyTree = lazy(() => import('./ApiFamilyTree'));

interface DashboardViewsProps {
  persons: Person[];
  canEdit?: boolean;
  treeData?: any;
}

export default function DashboardViews({
  persons,
  canEdit = false,
  treeData,
}: DashboardViewsProps) {
  const { view: currentView } = useDashboard();

  return (
    <>
      <main className="flex-1 overflow-auto bg-stone-50/50 flex flex-col">
        {currentView === 'list' && (
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full relative z-10">
            <DashboardMemberList initialPersons={persons} canEdit={canEdit} />
          </div>
        )}

        <div className="flex-1 w-full relative z-10">
          {currentView === 'tree' && treeData && (
            <Suspense fallback={<div className="p-8 text-center text-stone-500">Đang tải sơ đồ cây...</div>}>
              <ApiFamilyTree data={treeData} />
            </Suspense>
          )}
          {currentView === 'tree' && !treeData && (
            <div className="flex items-center justify-center h-64 text-stone-400">
              Chưa có dữ liệu cây gia phả.
            </div>
          )}
        </div>
      </main>
    </>
  );
}
