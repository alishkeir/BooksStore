import { produce } from 'immer';

let defaultState = {
  steps: {
    billing: {
      type: 'private',
      user_selected_address: null,
      valid: false,
      inputs: {
        id: '',
        last_name: '',
        first_name: '',
        business_name: '',
        vat_number: '',
        city: '',
        zip_code: '',
        address: '',
        comment: '',
        country_id: '', // :number
        entity_type: '', // private, business
      },
      errors: {
        id: '',
        last_name: '',
        first_name: '',
        business_name: '',
        vat_number: '',
        city: '',
        zip_code: '',
        address: '',
        comment: '',
        country_id: '', // :number
        entity_type: '', // private, business
      },
    },
    shipping: {
      type: null, // home, shop, box
      valid: false,
      types: {
        shop: {
          selected_shop: null,
        },
        box: {
          selected_box: null,
        },
        home: {
          user_selected_address: null,
          inputs: {
            id: '',
            last_name: '',
            first_name: '',
            business_name: '',
            vat_number: '',
            city: '',
            zip_code: '',
            address: '',
            comment: '',
            country_id: '',
          },
          errors: {
            last_name: '',
            first_name: '',
            business_name: '',
            vat_number: '',
            city: '',
            zip_code: '',
            address: '',
            comment: '',
            country_id: '',
            user_address_id: '',
          },
        },
        dpd: {
          user_selected_address: null,
          inputs: {
            id: '',
            last_name: '',
            first_name: '',
            business_name: '',
            vat_number: '',
            city: '',
            zip_code: '',
            address: '',
            comment: '',
            country_id: '',
          },
          errors: {
            last_name: '',
            first_name: '',
            business_name: '',
            vat_number: '',
            city: '',
            zip_code: '',
            address: '',
            comment: '',
            country_id: '',
            user_address_id: '',
          },
        },
        sameday: {
          user_selected_address: null,
          inputs: {
            id: '',
            last_name: '',
            first_name: '',
            business_name: '',
            vat_number: '',
            city: '',
            zip_code: '',
            address: '',
            comment: '',
            country_id: '',
          },
          errors: {
            last_name: '',
            first_name: '',
            business_name: '',
            vat_number: '',
            city: '',
            zip_code: '',
            address: '',
            comment: '',
            country_id: '',
            user_address_id: '',
          },
        },
      },
    },
    summary: {
      payment_methods: [],
      valid: false,
      comment: '',
      phone: '',
      payment_method: 'card', // card, transfer, cash_on_delivery
    },
  },
};

export default produce((draftState = defaultState, action) => {
  switch (action.type) {
    case 'checkout/UPDATE_CHECKOUT':
      return action.payload;
    case 'checkout/RESET_CHECKOUT':
      return defaultState;

    case 'checkout/UPDATE_BILLING_TYPE':
      draftState.steps.billing.type = action.payload;
      break;
    case 'checkout/UPDATE_BILLING_USER_SELECTED_ADDRESS':
      draftState.steps.billing.user_selected_address = action.payload;
      break;

    case 'checkout/UPDATE_BILLING_INPUT':
      draftState.steps.billing.inputs[action.payload.key] = action.payload.value;
      break;
    case 'checkout/UPDATE_BILLING_INPUTS':
      draftState.steps.billing.inputs = action.payload;
      break;
    case 'checkout/RESET_BILLING_INPUTS':
      draftState.steps.billing.inputs = defaultState.steps.billing.inputs;
      break;
    case 'checkout/UPDATE_BILLING_ERROR':
      draftState.steps.billing.errors[action.payload.key] = action.payload.value;
      break;
    case 'checkout/UPDATE_BILLING_ERRORS':
      draftState.steps.billing.errors = action.payload;
      break;
    case 'checkout/RESET_BILLING_ERRORS':
      draftState.steps.billing.errors = defaultState.steps.billing.errors;
      break;
    case 'checkout/UPDATE_BILLING_VALID':
      draftState.steps.billing.valid = action.payload;
      break;

    case 'checkout/UPDATE_SHIPPING_TYPE':
      draftState.steps.shipping.type = action.payload;
      break;
    case 'checkout/UPDATE_SHIPPING_USER_SELECTED_ADDRESS':
      draftState.steps.shipping.types.home.user_selected_address = action.payload;
      draftState.steps.shipping.types.dpd.user_selected_address = action.payload;
      draftState.steps.shipping.types.sameday.user_selected_address = action.payload;
      draftState.steps.shipping.types.shop.selected_shop = null;
      draftState.steps.shipping.types.box.selected_box = null;
      break;
    case 'checkout/UPDATE_SHIPPING_SELECTED_SHOP':
      draftState.steps.shipping.types.home.user_selected_address = null;
      draftState.steps.shipping.types.dpd.user_selected_address = null;
      draftState.steps.shipping.types.sameday.user_selected_address = null;
      draftState.steps.shipping.types.shop.selected_shop = action.payload;
      draftState.steps.shipping.types.box.selected_box = null;
      break;
    case 'checkout/UPDATE_SHIPPING_SELECTED_BOX':
      draftState.steps.shipping.types.home.user_selected_address = null;
      draftState.steps.shipping.types.dpd.user_selected_address = null;
      draftState.steps.shipping.types.sameday.user_selected_address = null;
      draftState.steps.shipping.types.shop.selected_shop = null;
      draftState.steps.shipping.types.box.selected_box = action.payload;
      break;
    case 'checkout/UPDATE_SHIPPING_BOX':
      draftState.steps.shipping.types.box = action.payload;
      break;

    case 'checkout/UPDATE_SHIPPING_HOME_INPUT':
      draftState.steps.shipping.types.home.inputs[action.payload.key] = action.payload.value;
      draftState.steps.shipping.types.dpd.inputs[action.payload.key] = action.payload.value;
      draftState.steps.shipping.types.sameday.inputs[action.payload.key] = action.payload.value;
      break;
    case 'checkout/UPDATE_SHIPPING_HOME_INPUTS':
      draftState.steps.shipping.types.home.inputs = action.payload;
      draftState.steps.shipping.types.dpd.inputs = action.payload;
      draftState.steps.shipping.types.sameday.inputs = action.payload;
      break;
    case 'checkout/RESET_SHIPPING_HOME_INPUTS':
      draftState.steps.shipping.types.home.inputs = defaultState.steps.shipping.types.home.inputs;
      draftState.steps.shipping.types.dpd.inputs = defaultState.steps.shipping.types.dpd.inputs;
      draftState.steps.shipping.types.sameday.inputs = defaultState.steps.shipping.types.sameday.inputs;
      break;

    case 'checkout/UPDATE_SHIPPING_HOME_ERROR':
      draftState.steps.shipping.types.home.errors[action.payload.key] = action.payload.value;
      draftState.steps.shipping.types.dpd.errors[action.payload.key] = action.payload.value;
      draftState.steps.shipping.types.sameday.errors[action.payload.key] = action.payload.value;
      break;
    case 'checkout/UPDATE_SHIPPING_HOME_ERRORS':
      draftState.steps.shipping.types.home.errors = action.payload;
      draftState.steps.shipping.types.dpd.errors = action.payload;
      draftState.steps.shipping.types.sameday.errors = action.payload;
      break;
    case 'checkout/RESET_SHIPPING_HOME_ERRORS':
      draftState.steps.shipping.types.home.errors = defaultState.steps.shipping.types.home.errors;
      draftState.steps.shipping.types.dpd.errors = defaultState.steps.shipping.types.dpd.errors;
      draftState.steps.shipping.types.sameday.errors = defaultState.steps.shipping.types.sameday.errors;
      break;
    case 'checkout/UPDATE_SHIPPING_VALID':
      draftState.steps.shipping.valid = action.payload;
      break;

    // Steps.summary
    case 'checkout/UPDATE_SUMMARY_PAYMENT_METHODS':
      draftState.steps.summary.payment_methods = action.payload;
      break;
    case 'checkout/UPDATE_SUMMARY_PAYMENT_METHOD':
      draftState.steps.summary.payment_method = action.payload;
      break;
    case 'checkout/UPDATE_SUMMARY_VALID':
      draftState.steps.summary.valid = action.payload;
      break;

    // Summary comment
    case 'checkout/UPDATE_SUMMARY_COMMENT':
      draftState.steps.summary.comment = action.payload;
      break;

    // Summary phone
    case 'checkout/UPDATE_SUMMARY_PHONE':
      draftState.steps.summary.phone = action.payload;
      break;

    default:
      return draftState;
  }
});

export let billingErrorsDefault = { ...defaultState.steps.billing.errors };
export let shippingHomeErrorsDefault = { ...defaultState.steps.shipping.types.home.errors };

export function updateCheckout(payload) {
  return { type: 'checkout/UPDATE_CHECKOUT', payload };
}
export function resetCheckout() {
  return { type: 'checkout/RESET_CHECKOUT' };
}

export function updateBillingType(payload) {
  return { type: 'checkout/UPDATE_BILLING_TYPE', payload };
}
export function updateBillingUserSelectedAddress(payload) {
  return { type: 'checkout/UPDATE_BILLING_USER_SELECTED_ADDRESS', payload };
}

export function updateBillingInput(payload) {
  return { type: 'checkout/UPDATE_BILLING_INPUT', payload };
}
export function updateBillingInputs(payload) {
  return { type: 'checkout/UPDATE_BILLING_INPUTS', payload };
}
export function resetBillingInputs() {
  return { type: 'checkout/RESET_BILLING_INPUTS' };
}
export function updateBillingError(payload) {
  return { type: 'checkout/UPDATE_BILLING_ERROR', payload };
}
export function updateBillingErrors(payload) {
  return { type: 'checkout/UPDATE_BILLING_ERRORS', payload };
}
export function resetBillingErrors() {
  return { type: 'checkout/RESET_BILLING_ERRORS' };
}
export function updateBillingValid(payload) {
  return { type: 'checkout/UPDATE_BILLING_VALID', payload };
}

export function updateShippingType(payload) {
  return { type: 'checkout/UPDATE_SHIPPING_TYPE', payload };
}
export function updateShippingUserSelectedAddress(payload) {
  return { type: 'checkout/UPDATE_SHIPPING_USER_SELECTED_ADDRESS', payload };
}
export function updateShippingSelectedShop(payload) {
  return { type: 'checkout/UPDATE_SHIPPING_SELECTED_SHOP', payload };
}
export function updateShippingSelectedBox(payload) {
  return { type: 'checkout/UPDATE_SHIPPING_SELECTED_BOX', payload };
}
export function updateShippingBox(payload) {
  return { type: 'checkout/UPDATE_SHIPPING_BOX', payload };
}

export function updateShippingHomeInput(payload) {
  return { type: 'checkout/UPDATE_SHIPPING_HOME_INPUT', payload };
}
export function updateShippingHomeInputs(payload) {
  return { type: 'checkout/UPDATE_SHIPPING_HOME_INPUTS', payload };
}
export function resetShippingHomeInputs() {
  return { type: 'checkout/RESET_SHIPPING_HOME_INPUTS' };
}

export function updateShippingHomeError(payload) {
  return { type: 'checkout/UPDATE_SHIPPING_HOME_ERROR', payload };
}
export function updateShippingHomeErrors(payload) {
  return { type: 'checkout/UPDATE_SHIPPING_HOME_ERRORS', payload };
}
export function resetShippingHomeErrors() {
  return { type: 'checkout/RESET_SHIPPING_HOME_ERRORS' };
}
export function updateShippingValid(payload) {
  return { type: 'checkout/UPDATE_SHIPPING_VALID', payload };
}

export function updateSummaryPaymentMethods(payload) {
  return { type: 'checkout/UPDATE_SUMMARY_PAYMENT_METHODS', payload };
}
export function updateSummaryPaymentMethod(payload) {
  return { type: 'checkout/UPDATE_SUMMARY_PAYMENT_METHOD', payload };
}
export function updateSummaryValid(payload) {
  return { type: 'checkout/UPDATE_SUMMARY_VALID', payload };
}

// Summary comment
export function updateSummaryComment(payload) {
  return { type: 'checkout/UPDATE_SUMMARY_COMMENT', payload };
}

// Summary phone
export function updateSummaryPhone(payload) {
  return { type: 'checkout/UPDATE_SUMMARY_PHONE', payload };
}
