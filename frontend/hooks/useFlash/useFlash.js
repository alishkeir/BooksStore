import { useCallback } from 'react';
import { useSelector, useDispatch } from 'react-redux';
import { updateFlashDraft } from '@store/modules/system';

export default function useFlash() {
  let dispatch = useDispatch();
  let flash = useSelector((store) => store.system.flash);

  let setFlash = useCallback((key, value) => {
    dispatch(updateFlashDraft({ ...flash, [key]: value }));
  });

  return [flash, setFlash];
}
