import { produce } from 'immer';
import { Cookies } from 'react-cookie';

let defaultState = {
  authChecking: true,
  user: null,
  guest: null,
};

export default produce((draftState = defaultState, action) => {
  switch (action.type) {
    case 'user/UPDATE_AUTH_CHECKING':
      draftState.authChecking = action.payload;
      break;
    case 'user/CREATE_GUEST_USER':
      draftState.guest = {
        type: 'guest',
        token: action.payload.token,
        customer: {
          preorder: {
            preorder_items: [],
          },
          cart: action.payload.cart
            ? action.payload.cart
            : {
              cart_items: [],
            },
        },
      };
      break;
    case 'user/UPDATE_USER_DATA':
      {
        draftState.user = action.payload && { ...action.payload, type: 'user' };
        // check if there is a temporary affiliate code to use it to track this user
        const cookies = new Cookies();
        let temporaryAffiliateCode = cookies.get('temporary_affiliate_code');
        let trackPeriodInDays = draftState.user?.customer?.affiliate_settings?.affiliate_track_period;
        if (temporaryAffiliateCode && trackPeriodInDays) {
          // if there is a temporary code, remove it 
          // and set a cookie to track this user's orders for the set period from settings
          cookies.remove('temporary_affiliate_code');
          let trackPeriodInSecs = trackPeriodInDays * 60 * 60 * 24;
          // don't set an affiliate code for the user if there is an already existing one
          let currentUserAffiliateCode = cookies.get('affiliate_code_' + draftState.user.customer.id);
          if (!currentUserAffiliateCode) {
            cookies.set('affiliate_code_' + draftState.user.customer.id, temporaryAffiliateCode, { path: '/', maxAge: trackPeriodInSecs });
          }
        }
      }
      break;
    case 'user/UPDATE_USER_PREORDER':
      draftState.user.customer.preorder = action.payload;
      break;
    case 'user/UPDATE_GUEST_DATA':
      draftState.guest = action.payload && { ...action.payload, type: 'guest' };
      break;
    default:
      return draftState;
  }
});

export function updateUserData(payload) {
  return { type: 'user/UPDATE_USER_DATA', payload };
}

export function updateUserPreorder(payload) {
  return { type: 'user/UPDATE_USER_PREORDER', payload };
}

export function updateGuestData(payload) {
  return { type: 'user/UPDATE_GUEST_DATA', payload };
}

export function createGuestUser(payload) {
  return { type: 'user/CREATE_GUEST_USER', payload };
}

export function updateAuthChecking(payload) {
  return { type: 'user/UPDATE_AUTH_CHECKING', payload };
}
