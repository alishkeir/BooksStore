import { produce } from 'immer';

let defaultState = {
  scrollPosition: 0,
  scrollDirection: '',
  windowWidth: 0,
  windowHeight: 0,
  flashDraft: {}, // We prepare flash for route change
  flash: {}, // Pass data between route changes
};

export default produce((draftState = defaultState, action) => {
  switch (action.type) {
    case 'system/UPDATE_SCROLL_POSITION':
      draftState.scrollPosition = action.payload;
      break;
    case 'system/UPDATE_SCROLL_DIRECTION':
      draftState.scrollDirection = action.payload;
      break;
    case 'system/UPDATE_WINDOW_WIDTH':
      draftState.windowWidth = action.payload;
      break;
    case 'system/UPDATE_WINDOW_HEIGHT':
      draftState.windowHeight = action.payload;
      break;
    case 'system/UPDATE_FLASH_DRAFT':
      draftState.flashDraft = action.payload;
      break;
    case 'system/UPDATE_FLASH':
      draftState.flash = action.payload;
      break;

    default:
      return draftState;
  }
});

export function updateScrollPosition(payload) {
  return { type: 'system/UPDATE_SCROLL_POSITION', payload };
}

export function updateScrollDirection(payload) {
  return { type: 'system/UPDATE_SCROLL_DIRECTION', payload };
}

export function updateWindowWidth(payload) {
  return { type: 'system/UPDATE_WINDOW_WIDTH', payload };
}

export function updateWindowHeight(payload) {
  return { type: 'system/UPDATE_WINDOW_HEIGHT', payload };
}

export function updateFlashDraft(payload) {
  return { type: 'system/UPDATE_FLASH_DRAFT', payload };
}

export function updateFlash(payload) {
  return { type: 'system/UPDATE_FLASH', payload };
}
