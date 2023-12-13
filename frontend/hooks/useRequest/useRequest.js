import { useRef, useEffect } from 'react';
import { Request } from '@libs/api';

export default function useRequest(requestTemplates, query) {
  let request = useRef(new Request(requestTemplates));

  // On request commit we refetch
  useEffect(() => {
    let commitHandler = (events) => {
      if (query.refetch) {
        query.refetch();
      } else if (query.mutate) {
        query.mutate(request.current.build(), events);
      }
    };

    request.current.events.on('commit', (e) => commitHandler(e));

    return () => {
      request.current.events.off('commit');
    };
  }, []);

  return request.current;
}
