import { produce } from 'immer';

let defaultState = {
  sidebarOpen: false,
  sidebarType: '',
  sidebarData: '',
  overlayOpen: false,
  overlayType: '',
  overlayData: '',
  categories: [],
  redirectAfterLogin: null,
};

export default produce((draftState = defaultState, action) => {
  switch (action.type) {
    case 'ui/UPDATE_SIDEBAR':
      draftState.sidebarOpen = action.payload.open ? action.payload.open : false;
      draftState.sidebarType = action.payload.type ? action.payload.type : '';
      draftState.sidebarData = action.payload.data ? action.payload.data : '';
      break;
    case 'ui/UPDATE_OVERLAY':
      draftState.overlayOpen = action.payload.open ? action.payload.open : false;
      draftState.overlayType = action.payload.type ? action.payload.type : '';
      draftState.overlayData = action.payload.data ? action.payload.data : '';
      break;
    case 'ui/UPDATE_CATEGORIES':
      draftState.categories = action.payload;
      break;
    case 'ui/UPDATE_REDIRECT_AFTER_LOGIN':
      draftState.redirectAfterLogin = action.payload;
      break;

    default:
      return draftState;
  }
});

export function updateSidebar(payload) {
  return { type: 'ui/UPDATE_SIDEBAR', payload };
}
export function updateOverlay(payload) {
  return { type: 'ui/UPDATE_OVERLAY', payload };
}
export function updateCategories(payload) {
  return { type: 'ui/UPDATE_CATEGORIES', payload };
}
export function updateRedirectAfterLogin(payload) {
  return { type: 'ui/UPDATE_REDIRECT_AFTER_LOGIN', payload };
}
