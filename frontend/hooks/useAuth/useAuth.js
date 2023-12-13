import { useEffect } from 'react';
import { useDispatch } from 'react-redux';
import { useQuery } from 'react-query';
import { Request } from '@libs/api';
import { updateUserData } from '@store/modules/user';
import { useSelector } from 'react-redux';
import { getSiteCode } from '@libs/site';
import settingsVars from "@vars/settingsVars";
import url from "@libs/url";

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    logout: {
      method: 'POST',
      path: '/logout',
      ref: 'logout',
      request_id: 'logout',
      body: {},
    },
  },
};

let request = new Request(requestTemplates);
request.addRequest('logout');

export function useLogout() {
  let dispatch = useDispatch();
  let user = useSelector((store) => store.user.user);

  let logoutQuery = useQuery('logout', () => handleLogout(request.build()), {
    enabled: false,
    refetchOnWindowFocus: false,
    refetchOnMount: false,
    staleTime: 0,
    onSettled: () => {
      let settings = settingsVars.get(url.getHost());
      // Removing token
      localStorage.removeItem(`${settings.key}-user-token`);

      // Removing user to store
      dispatch(updateUserData(null));
    },
  });

  // On request commit we refetch
  useEffect(() => {
    let commitHandler = () => {
      logoutQuery.refetch();
    };

    request.events.on('commit', commitHandler);

    return () => {
      request.events.off('commit', commitHandler);
    };
  }, []);

  function logout() {
    if (!user) return;

    request.modifyHeaders((headerObject) => {
      headerObject['Authorization'] = `Bearer ${user.token}`;
    });

    request.commit();
  }

  return logout;
}

function handleLogout(request) {
  return fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/v1/${getSiteCode(settings.key)}/composite`, {
    method: 'POST',
    headers: request.headers,
    body: JSON.stringify(request.body),
  })
    .then((response) => {
      if (!response.ok) throw new Error(`API response: ${response.status}`);
      return response.json();
    })
    .then((data) => data)
    .catch((error) => console.log(error));
}
