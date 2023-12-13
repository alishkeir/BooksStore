import { useEffect, useState, useCallback } from 'react';
import { ResizeObserver } from 'resize-observer';
import throttle from 'lodash/throttle';

export function useResizeObserver(container, timeout = 300) {
  let [listerWidth, setListerWidth] = useState(0);

  // Handel observer callback
  let handleObserver = useCallback(
    throttle(
      (entries) => {
        setListerWidth(entries[0].contentRect.width);
      },
      timeout,
      { leading: true, trailing: true },
    ),
  );

  // Observing container width
  useEffect(() => {
    if (!container.current) return;

    let resizeObserver = new ResizeObserver(handleObserver);

    resizeObserver.observe(container.current);

    return () => {
      resizeObserver.disconnect();
      resizeObserver = null;
    };
  }, [container]);

  return listerWidth;
}
