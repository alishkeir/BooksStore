import { produce } from 'immer';

let defaultState = {
  host: "WTF",
};

export default produce((draftState = defaultState, action) => {
  switch (action.type) {
    case 'url/HOST':
      draftState.host = action.payload;
      break;
    default:
      return draftState;
  }
});

export function setHost(payload) {
  return { type: 'url/HOST', payload };
}

