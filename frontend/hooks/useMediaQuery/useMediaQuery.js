import { useState, useEffect } from 'react';

export default function useMediaQuery(mediaQueryString) {
  let [isHappening, setIsHappening] = useState(false);

  useEffect(() => {
    var mediaQueryList = window.matchMedia(mediaQueryString);

    // First time check
    if (mediaQueryList.matches) screenTest(mediaQueryList);

    // Watch for change
    // mediaQueryList.addEventListener('change', screenTest);

    if (mediaQueryList?.addEventListener) {
      mediaQueryList.addEventListener('change', screenTest);
    } else {
      mediaQueryList.addListener(screenTest);
    }

    return () => {
      // Remove listener
      if (mediaQueryList?.removeEventListener) {
        mediaQueryList.removeEventListener('change', screenTest);
      } else {
        mediaQueryList.removeListener(screenTest);
      }
    };
  }, []);

  function screenTest(e) {
    if (e.matches) {
      setIsHappening(true);
    } else {
      setIsHappening(false);
    }
  }

  return isHappening;
}
