import { useEffect, useCallback, useRef } from 'react';
import { useRouter } from 'next/router';
import { useSelector, useDispatch } from 'react-redux';
import { updateFlashDraft, updateFlash } from '@store/modules/system';

export default function AppFlash() {
  let flashDraftRef = useRef();
  let dispatch = useDispatch();
  let router = useRouter();
  let flashDraft = useSelector((store) => store.system.flashDraft);

  flashDraftRef.current = flashDraft;

  let handleRouterChange = useCallback(() => {
    if (Object.keys(flashDraftRef.current).length > 0) {
      dispatch(updateFlash({ ...flashDraftRef.current }));
      dispatch(updateFlashDraft({}));
      dispatch(updateFlash({}));
    }
  }, [flashDraftRef.current]);

  useEffect(() => {
    router.events.on('routeChangeComplete', handleRouterChange);

    return () => {
      router.events.off('routeChangeComplete', handleRouterChange);
    };
  }, []);

  return null;
}
