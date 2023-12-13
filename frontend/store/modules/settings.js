import { produce } from 'immer';
import settingsVars from '@vars/settingsVars';

let defaultState = settingsVars.get();

export default produce((draftState = defaultState, action) => {
  switch (action.type) {
    case 'settings/UPDATE_SETTINGS':
      draftState = action.payload;
      return draftState;
    default:
      return draftState;
  }
});

export function updateSettings(payload) {
  return { type: 'settings/UPDATE_SETTINGS', payload };
}
