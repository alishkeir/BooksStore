import { useDispatch, useSelector } from 'react-redux';
import { addDays, formatISO } from 'date-fns/fp';
import { updateUserData, updateGuestData, createGuestUser } from '@store/modules/user';
import _cloneDeep from 'lodash/cloneDeep';

export default function useUser() {
  let dispatch = useDispatch();
  let authChecking = useSelector((store) => store.user.authChecking);
  let actualUser = useSelector((store) => (store.user.user ? store.user.user : store.user.guest ? store.user.guest : null));

  /**
   *
   * @param {string} itemArray Name of the array in user object to check
   * @param {number} itemId Id of item to check if exists
   * @returns {undefined}
   */
  function userHasItem(itemArray, itemId) {
    if (!actualUser) return false;

    let customerArray = actualUser.customer[itemArray][`${itemArray}_items`];

    let hasItem = customerArray?.find((customerItem) => customerItem.id === itemId);
    return hasItem ? true : false;
  }

  /**
   *
   * @param {string} itemType Name of the array in user object to add item to
   * @param {number} itemToAdd Id of the item to add to user object
   * @returns {undefined}
   */
  function addItems(itemType, itemsToAdd) {
    let userDataClone = _cloneDeep(actualUser);
    userDataClone.customer[itemType] = itemsToAdd;

    if (userDataClone.type === 'user') {
      dispatch(updateUserData(userDataClone));
    } else if (userDataClone.type === 'guest') {
      dispatch(updateGuestData(userDataClone));
    }
  }

  function userCreateGuest(cart, token) {
    dispatch(createGuestUser({ cart, token }));

    // Saving guest token
    localStorage.setItem(
      `${settings.key}-guest-token`,
      JSON.stringify({
        token: token,
        valid_until: formatISO(addDays(7, new Date())),
      }),
    );
  }

  function userAddCart(cart, token) {
    if (!actualUser) {
      userCreateGuest(cart, token);
    } else {
      addItems('cart', cart);
    }
  }

  function userHasInCart(cartId) {
    return userHasItem('cart', cartId);
  }

  function userAddPreorder(preorderToAdd) {
    addItems('preorder', preorderToAdd);
  }

  function userHasPreordered(preorderId) {
    return userHasItem('preorder', preorderId);
  }

  return {
    authChecking,
    actualUser,
    userAddCart,
    userHasInCart,
    userAddPreorder,
    userHasPreordered,
  };
}
