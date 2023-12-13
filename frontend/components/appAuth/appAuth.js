import { useEffect, useRef } from 'react';
import { useMutation } from 'react-query';
import { useRouter } from 'next/router';
import { useDispatch } from 'react-redux';
import useRequest from '@hooks/useRequest/useRequest';
import { handleApiRequest, getResponseById } from '@libs/api';
import { updateUserData, updateGuestData, createGuestUser, updateAuthChecking } from '@store/modules/user';
import settingsVars from "@vars/settingsVars";
import url from "@libs/url";

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    'auth-check-token': {
      method: 'POST',
      path: '/check-token',
      ref: 'check-token',
      request_id: 'auth-check-token',
      body: {},
    },
    'auth-get-cart': {
      method: 'POST',
      path: '/carts',
      ref: 'get',
      request_id: 'auth-get-cart',
      body: {
        guest_token: null,
      },
    },
  },
};

export default function AppAuth() {
  let settings = settingsVars.get(url.getHost());

  let firstLoad = useRef(true);
  let dispatch = useDispatch();
  let router = useRouter();

  let authCheckTokenQuery = useMutation('auth-check-token', (requestUpdateBuild) => handleApiRequest(requestUpdateBuild));
  let authGetCartQuery = useMutation('auth-get-cart', (requestUpdateBuild) => handleApiRequest(requestUpdateBuild));

  let authCheckTokenRequest = useRequest(requestTemplates, authCheckTokenQuery);
  let authGetCartRequest = useRequest(requestTemplates, authGetCartQuery);

  function checkUserToken() {
    return new Promise((resolve) => {
      let tokenUserStorage = localStorage.getItem(`${settings.key}-user-token`);

      try {
        if (!tokenUserStorage) throw new Error('No token');

        let tokenObj = JSON.parse(tokenUserStorage);

        // Invalid token data
        if (!tokenObj.valid_until || !tokenObj.token) throw new Error('Invalid token');

        // Token expired, we delete
        if (new Date(tokenObj.valid_until).valueOf() < Date.now()) throw new Error('Expired token');

        authCheckTokenRequest.addRequest('auth-check-token');

        authCheckTokenRequest.modifyHeaders((currentHeader) => {
          currentHeader['Authorization'] = `Bearer ${tokenObj.token}`;
        });

        authCheckTokenRequest.commit({
          onSettled: (data) => {
            let loginResponse = getResponseById(data, 'auth-check-token');

            if (loginResponse?.success) {
              // Login success
              // Adding user to store
              dispatch(updateUserData(loginResponse.body));
              resolve(true);
            } else {
              // Login error
              localStorage.removeItem(`${settings.key}-user-token`);
              dispatch(updateUserData(null));
              resolve(false);
            }
          },
        });
      } catch (error) {
        localStorage.removeItem(`${settings.key}-user-token`);
        dispatch(updateUserData(null));

        resolve(false);
      }
    });
  }

  function checkGuestToken() {
    return new Promise((resolve) => {
      // Checking for guest token
      let tokenGuestStorage = localStorage.getItem(`${settings.key}-guest-token`);

      try {
        if (!tokenGuestStorage) throw new Error('No token');

        let tokenObj = JSON.parse(tokenGuestStorage);

        // Invalid token data
        if (!tokenObj.valid_until || !tokenObj.token) throw new Error('Invalid token');

        // Token expired, we delete
        if (new Date(tokenObj.valid_until).valueOf() < Date.now()) throw new Error('Expired token');

        // Token is OK
        // Now we get the Guest cart
        authGetCartRequest.addRequest('auth-get-cart');

        authGetCartRequest.modifyRequest('auth-get-cart', (currentRequest) => {
          currentRequest.body.guest_token = tokenObj.token;
        });

        authGetCartRequest.commit({
          onSettled: (data) => {
            let authGetCartResponse = getResponseById(data, 'auth-get-cart');

            if (authGetCartResponse?.success) {
              // Login success
              // Adding guest to store
              dispatch(createGuestUser({ cart: authGetCartResponse.body, token: authGetCartResponse.body.user.guest_token }));
              resolve(true);
            } else {
              // Login error
              localStorage.removeItem(`${settings.key}-guest-token`);
              dispatch(updateGuestData(null));
              resolve(false);
            }
          },
        });
      } catch (error) {
        localStorage.removeItem(`${settings.key}-guest-token`);
        dispatch(updateGuestData(null));
        resolve(false);
      }
    });
  }

  // On route change we might recheck
  useEffect(() => {
    if (firstLoad.current) return;

    if (router.asPath.includes('profil')) {
      checkUserToken().catch((error) => console.log(error));
    }
  }, [router.asPath]);

  // Initial auth check
  useEffect(() => {
    checkUserToken()
      .then((result) => {
        return !result ? checkGuestToken() : true;
      })
      .then(() => {
        dispatch(updateAuthChecking(false));
      })
      .catch((error) => console.log(error));
  }, []);

  useEffect(() => {
    firstLoad.current = false;
  }, []);

  return null;
}
