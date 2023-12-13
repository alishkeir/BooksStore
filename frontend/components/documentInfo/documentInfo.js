import { useEffect, useRef } from 'react';
import { useDispatch } from 'react-redux';
import throttle from 'lodash/throttle';
import { updateScrollPosition, updateScrollDirection, updateWindowWidth, updateWindowHeight } from '@store/modules/system';

export default function DocumentInfo() {
  let previousWindowInnerWidth = useRef();
  let previousWindowInnerHeight = useRef();
  let previousScrollDirection = useRef();
  let previousScrollPosition = useRef(getCurrentOffset());

  let dispatch = useDispatch();

  // Scroll callback
  let handleScroll = throttle(
    () => {
      let currentScrollPosition = window.pageYOffset;

      dispatch(updateScrollPosition(currentScrollPosition));

      if (currentScrollPosition > previousScrollPosition.current) {
        if (previousScrollDirection.current !== 'down') {
          dispatch(updateScrollDirection('down'));
        }
        previousScrollDirection.current = 'down';
      } else if (currentScrollPosition < previousScrollPosition.current) {
        if (previousScrollDirection.current !== 'up') {
          dispatch(updateScrollDirection('up'));
        }
        previousScrollDirection.current = 'up';
      }

      previousScrollPosition.current = currentScrollPosition;
    },
    300,
    { leading: false, trailing: true },
  );

  // Window size callback
  let handleWindowSize = throttle(
    () => {
      let windowInnerWidth = window.innerWidth;
      let windowInnerHeight = window.innerHeight;

      if (previousWindowInnerWidth.current !== windowInnerWidth) {
        dispatch(updateWindowWidth(windowInnerWidth));
      }

      if (previousWindowInnerHeight.current !== windowInnerHeight) {
        dispatch(updateWindowHeight(windowInnerHeight));
      }

      previousWindowInnerWidth.current = windowInnerWidth;
      previousWindowInnerHeight.current = windowInnerHeight;
    },
    300,
    { leading: false, trailing: true },
  );

  // Watching scroll
  useEffect(() => {
    document.addEventListener('scroll', handleScroll);

    return () => {
      // Remove listener
      document.removeEventListener('scroll', handleScroll);
    };
  }, []);

  // Watching window sizes
  useEffect(() => {
    // Initial set
    let windowInnerWidth = window.innerWidth;
    let windowInnerHeight = window.innerHeight;
    dispatch(updateWindowWidth(windowInnerWidth));
    dispatch(updateWindowHeight(windowInnerHeight));

    window.addEventListener('resize', handleWindowSize);

    return () => {
      // Remove listener
      window.removeEventListener('resize', handleWindowSize);
    };
  }, []);

  return null;

  function getCurrentOffset() {
    if (process.browser) {
      return window.pageYOffset;
    } else {
      return 0;
    }
  }
}
