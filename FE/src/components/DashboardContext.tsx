import { useSearchParams } from 'react-router-dom';
import { createContext, useContext, useEffect, useState, ReactNode } from 'react';

export type ViewMode = 'tree' | 'list' | 'mindmap' | 'bubblemap';

interface DashboardState {
  memberModalId: number | null;
  setMemberModalId: (id: number | null) => void;
  showCreateMember: boolean;
  setShowCreateMember: (show: boolean) => void;
  showAvatar: boolean;
  setShowAvatar: (show: boolean) => void;
  view: ViewMode;
  setView: (view: ViewMode) => void;
  rootId: number | null;
  setRootId: (id: number | null) => void;
}

export const DashboardContext = createContext<DashboardState | undefined>(undefined);

export function DashboardProvider({ children }: { children: ReactNode }) {
  const [searchParams] = useSearchParams();

  const [memberModalId, setMemberModalIdState] = useState<number | null>(null);
  const [showCreateMember, setShowCreateMember] = useState(false);
  const [showAvatar, setShowAvatarState] = useState<boolean>(
    () => searchParams.get('avatar') !== 'hide'
  );
  const [view, setViewState] = useState<ViewMode>(
    () => (searchParams.get('view') as ViewMode | null) ?? 'list'
  );
  const [rootId, setRootIdState] = useState<number | null>(
    () => searchParams.get('rootId') ? Number(searchParams.get('rootId')) : null
  );

  const setMemberModalId = (id: number | null) => {
    setMemberModalIdState(id);
    const newUrl = new URL(window.location.href);
    if (id) newUrl.searchParams.set('memberModalId', String(id));
    else newUrl.searchParams.delete('memberModalId');
    window.history.replaceState(null, '', newUrl.toString());
  };

  const setShowAvatar = (show: boolean) => {
    setShowAvatarState(show);
    const newUrl = new URL(window.location.href);
    if (!show) newUrl.searchParams.set('avatar', 'hide');
    else newUrl.searchParams.delete('avatar');
    window.history.replaceState(null, '', newUrl.toString());
  };

  const setView = (v: ViewMode) => {
    setViewState(v);
    const newUrl = new URL(window.location.href);
    newUrl.searchParams.set('view', v);
    window.history.replaceState(null, '', newUrl.toString());
  };

  const setRootId = (id: number | null) => {
    setRootIdState(id);
    const newUrl = new URL(window.location.href);
    if (id) newUrl.searchParams.set('rootId', String(id));
    else newUrl.searchParams.delete('rootId');
    window.history.replaceState(null, '', newUrl.toString());
  };

  return (
    <DashboardContext.Provider
      value={{
        memberModalId,
        setMemberModalId,
        showCreateMember,
        setShowCreateMember,
        showAvatar,
        setShowAvatar,
        view,
        setView,
        rootId,
        setRootId,
      }}
    >
      {children}
    </DashboardContext.Provider>
  );
}

export function useDashboard(): DashboardState {
  const context = useContext(DashboardContext);
  if (context === undefined) {
    return {
      memberModalId: null,
      setMemberModalId: () => {},
      showCreateMember: false,
      setShowCreateMember: () => {},
      showAvatar: true,
      setShowAvatar: () => {},
      view: 'list',
      setView: () => {},
      rootId: null,
      setRootId: () => {},
    };
  }
  return context;
}
