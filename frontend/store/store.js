import { createStore, combineReducers } from 'redux';
import { composeWithDevTools } from 'redux-devtools-extension';
import systemReducer from 'store/modules/system';
import userReducer from 'store/modules/user';
import uiReducer from 'store/modules/ui';
import settingsReducer from 'store/modules/settings';
import checkoutReducer from 'store/modules/checkout';
import urlReducer from 'store/modules/url';

const composedEnhancer = composeWithDevTools();

export default createStore(
  combineReducers({
    system: systemReducer,
    user: userReducer,
    ui: uiReducer,
    settings: settingsReducer,
    checkout: checkoutReducer,
    url: urlReducer
  }),
  composedEnhancer,
);
