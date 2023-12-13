import { useCallback, useEffect } from 'react';
import { useSelector } from 'react-redux';

import events from '@libs/events';

export default function AppAction() {
  let flash = useSelector((store) => store.system.flash);

  // Action hash callback
  let getHashAction = useCallback((hash) => {
    let action = '';

    if (hash.includes('action:')) {
      let actionMatch = hash.match(/\|?action:([a-z]+)\|?/);

      //console.log(actionMatch);
      if (actionMatch?.[1]) {
        action = actionMatch[1];
      }
    }

    return action;
  });

  // Watching actions in URL hash
  useEffect(() => {
    let handleHashChange = () => {
      let cleanHash = window.location.hash.replace('#', '');

      if (!cleanHash) return;

      cleanHash = decodeURIComponent(cleanHash);

      let hashAction = getHashAction(cleanHash);
      if (hashAction) {
        events.emit(`action:${hashAction}`, cleanHash);
        setTimeout(() => {
          window.location.hash = '';
        },4000);
      }
    };

    handleHashChange();

    window.addEventListener('hashchange', handleHashChange, false);

    return () => {
      window.removeEventListener('hashchange', handleHashChange, false);
    };
  }, []);

  // Watching actions in flash
  useEffect(() => {
    if (!flash.action) return;

    let hashAction = getHashAction(flash.action);

    if (hashAction) {
      events.emit(`action:${hashAction}`, flash.action);
    }
  }, [flash.action]);

  return null;
}
