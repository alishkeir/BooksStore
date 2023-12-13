import { useState, useEffect, useRef, useCallback } from 'react';
import throttle from 'lodash/throttle';

export default function useMediaQuery() {
  let previousSetState = useRef();
  let previousScrollPosition = useRef(getCurrentOffset());
  let [scrollDirection, setScrollDirection] = useState();

  let handleScroll = useCallback(
    throttle(
      () => {
        let currentScrollPosition = window.pageYOffset;

        if (currentScrollPosition > previousScrollPosition.current) {
          if (previousSetState.current !== 'down') {
            previousSetState.current = 'down';
            setScrollDirection('down');
          }
        } else if (currentScrollPosition < previousScrollPosition.current) {
          if (previousSetState.current !== 'up') {
            previousSetState.current = 'up';
            setScrollDirection('up');
          }
        }

        previousScrollPosition.current = currentScrollPosition;
      },
      300,
      { leading: false, trailing: true },
    ),
  );

  useEffect(() => {
    document.addEventListener('scroll', handleScroll);

    return () => {
      // Remove listener
      document.removeEventListener('scroll', handleScroll);
    };
  }, []);

  function getCurrentOffset() {
    if (process.browser) {
      return window.pageYOffset;
    } else {
      return 0;
    }
  }

  return scrollDirection;
}
