import { useRef, useState, useCallback, RefObject } from 'react';

interface PanZoomHandlers {
  handleMouseDown: (e: React.MouseEvent) => void;
  handleMouseMove: (e: React.MouseEvent) => void;
  handleMouseUpOrLeave: () => void;
  handleClickCapture: (e: React.MouseEvent) => void;
  handleZoomIn: () => void;
  handleZoomOut: () => void;
  handleResetZoom: () => void;
}

interface PanZoomState {
  scale: number;
  isPressed: boolean;
  isDragging: boolean;
  handlers: PanZoomHandlers;
}

export function usePanZoom(containerRef: RefObject<HTMLDivElement>): PanZoomState {
  const [scale, setScale] = useState(1);
  const [isPressed, setIsPressed] = useState(false);
  const [isDragging, setIsDragging] = useState(false);
  const lastPos = useRef({ x: 0, y: 0 });
  const dragStarted = useRef(false);

  const handleMouseDown = useCallback((e: React.MouseEvent) => {
    if (e.button !== 0) return;
    setIsPressed(true);
    dragStarted.current = false;
    lastPos.current = { x: e.clientX, y: e.clientY };
  }, []);

  const handleMouseMove = useCallback((e: React.MouseEvent) => {
    if (!isPressed) return;
    const dx = e.clientX - lastPos.current.x;
    const dy = e.clientY - lastPos.current.y;
    if (Math.abs(dx) > 3 || Math.abs(dy) > 3) {
      dragStarted.current = true;
      setIsDragging(true);
    }
    if (dragStarted.current && containerRef.current) {
      containerRef.current.scrollLeft -= dx;
      containerRef.current.scrollTop -= dy;
    }
    lastPos.current = { x: e.clientX, y: e.clientY };
  }, [isPressed, containerRef]);

  const handleMouseUpOrLeave = useCallback(() => {
    setIsPressed(false);
    setTimeout(() => setIsDragging(false), 50);
  }, []);

  const handleClickCapture = useCallback((e: React.MouseEvent) => {
    if (dragStarted.current) {
      e.stopPropagation();
    }
  }, []);

  const handleZoomIn = useCallback(() => setScale(s => Math.min(s + 0.1, 2)), []);
  const handleZoomOut = useCallback(() => setScale(s => Math.max(s - 0.1, 0.3)), []);
  const handleResetZoom = useCallback(() => setScale(1), []);

  return {
    scale,
    isPressed,
    isDragging,
    handlers: {
      handleMouseDown,
      handleMouseMove,
      handleMouseUpOrLeave,
      handleClickCapture,
      handleZoomIn,
      handleZoomOut,
      handleResetZoom,
    },
  };
}
