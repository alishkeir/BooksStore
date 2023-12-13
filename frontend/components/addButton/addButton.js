import { useEffect, useState, useCallback } from 'react';
import { useDispatch } from 'react-redux';
import { updateOverlay, updateSidebar } from '@store/modules/ui';
import ImageCheck from '@assets/images/icons/check-path.svg';
import ImageEx from '@assets/images/icons/ex-thin.svg';
import ImagePlus from '@assets/images/icons/plus.svg';
import ImageHeart from '@assets/images/icons/icon-heart.svg';
import theme from '@vars/theme';
import useItem from '@hooks/useItem/useItem';
import useUser from '@hooks/useUser/useUser';
import { analytics } from '@libs/analytics';
import { event as fbqEvent } from '@libs/fbpixel';
import {
  AddButtonWrapper,
  AferTextWrapper,
  CheckLayer,
  ExLayer,
  ImageCheckWrapper,
  ImageHeartWrapper,
  TextImage,
  TextImagePlus,
  TextImageHeart,
  TextLayer,
  TextWrapper,
} from '@components/addButton/addButton.styled';
import { yuspify } from '@libs/yuspify';

export default function AddButton(props) {
  let {
    type,
    isCartPrice = false,
    text,
    afterText,
    fontSize,
    textIcon,
    buttonHeight,
    buttonWidth,
    disabled,
    itemObj,
    itemId,
    inCart: inCartProp = false,
  } = props;

  let dispatch = useDispatch();
  let { actualUser, userAddCart, userHasInCart, userAddPreorder, userHasPreordered } = useUser();
  let {
    itemAddRemoteCart,
    itemRemoveRemoteCart,
    itemAddRemotePreorder,
    itemRemoveRemotePreorder,
    itemAddRemoteWishlist,
    itemRemoveRemoteWishlist,
    itemAddRemoteAuthor,
    itemRemoveRemoteAuthor,
  } = useItem(actualUser);

  let [config, setConfig] = useState({});
  let [hover, setHover] = useState(false);
  let [firstHover, setFirstHover] = useState(true);
  let [deletable, setDeletable] = useState(false);
  let [inCart, setInCart] = useState(inCartProp);

  let analyticsGaItemObj = itemObj ? { id: itemObj.id, name: itemObj.title, price: itemObj.price } : { id: itemId };
  let analyticsFbItemObj = itemObj
    ? { content_ids: String(itemObj.id), content_name: itemObj.title, value: Number(itemObj.price), content_type: 'product', currency: 'HUF' }
    : { content_ids: String(itemId) };

  let handleButtonClick = useCallback(() => {
    if (type === 'preorder') {
      // User
      if (actualUser && actualUser.type === 'user') {
        if (userHasPreordered(itemId)) {
          itemRemoveRemotePreorder(itemId, (response) => {
            if (response?.success) {
              userAddPreorder(response.body);
            }
          });
        } else {
          window?.gtag('event', 'kattintas', {
            event_category: 'elojegyzes',
            event_label: 'bejelentkezett',
          });

          itemAddRemotePreorder(itemId, (response) => {
            if (response?.success) {
              analytics.addToPreorder();
              userAddPreorder(response.body);
            }
            window?.gtag('event', 'kattintas', {
              event_category: 'elojegyzes',
              event_label: 'bejelentkezett',
            });
          });
        }
      }
      // No user
      else {
        dispatch(updateOverlay({ open: true, type: 'preorderSignup', data: { itemId } }));
        window?.gtag('event', 'kattintas', {
          event_category: 'elojegyzes',
          event_label: 'nem_bejelentkezett',
        });
      }
    } else if (type === 'cart') {
      if (!actualUser) {
        itemAddRemoteCart(itemId, 1, !!isCartPrice, (response) => {
          if (response?.success) {
            userAddCart(response.body, response.body.user.guest_token);
            yuspify.addToCart(itemObj.id, itemObj.price);
            analytics.addToCart(analyticsGaItemObj);
            fbqEvent('AddToCart', analyticsFbItemObj);
          }
        });
      } else {
        if (userHasInCart(itemId)) {
          itemRemoveRemoteCart(itemId, (response) => {
            if (response?.success) {
              userAddCart(response.body);
              analytics.removeFromCart(analyticsGaItemObj);
              yuspify.removeFromCart(itemObj.id, itemObj.price);
            }
          });
        } else {
          itemAddRemoteCart(itemId, 1, !!isCartPrice, (response) => {
            if (response?.success) {
              userAddCart(response.body);
              yuspify.addToCart(itemObj.id, itemObj.price);
              analytics.addToCart(analyticsGaItemObj);
              fbqEvent('AddToCart', analyticsFbItemObj);
            }
          });
        }
      }
    } else if (type === 'wishlist') {
      // User
      if (actualUser && actualUser.type === 'user') {
        if (inCart) {
          itemRemoveRemoteWishlist(itemId, (response) => {
            if (response?.success) {
              setInCart(false);
              yuspify.RemoveFromFavorites(itemId)
            }
          });
        } else {
          itemAddRemoteWishlist(itemId, (response) => {
            if (response?.success) {
              setInCart(true);
              yuspify.addToFavorites(itemId)
            }
          });
        }
      }
      // No user
      else {
        dispatch(updateSidebar({ open: true, type: 'login' }));
      }
    } else if (type === 'author') {
      if (actualUser && actualUser.type === 'user') {
        if (inCart) {
          itemRemoveRemoteAuthor(itemId, (response) => {
            if (response?.success) {
              setInCart(false);
            }
          });
        } else {
          itemAddRemoteAuthor(itemId, (response) => {
            if (response?.success) {
              setInCart(true);
            }
          });
        }
      }
      // No user
      else {
        dispatch(updateSidebar({ open: true, type: 'login' }));
      }
    }
  }, [actualUser, inCart]);

  useEffect(() => {
    if (type === 'wishlist' || type === 'author') {
      if (inCart !== inCartProp) setInCart(inCartProp);
    }
  }, [inCartProp]);

  useEffect(() => {
    // Button values are read from user store object
    if (type === 'cart' || type === 'preorder') {
      if (actualUser) {
        if ((type === 'cart' && userHasInCart(itemId)) || (type === 'preorder' && userHasPreordered(itemId))) {
          !inCart && setInCart(true);
          !deletable && setDeletable(true);
        } else {
          inCart && setInCart(false);
          deletable && setDeletable(false);
          !firstHover && setFirstHover(true);
        }
      } else {
        inCart && setInCart(false);
      }
    }
  }, [actualUser]);

  // Button config
  useEffect(() => {
    let buttonTheme;
    let newConfig = {};

    switch (type) {
      case 'cart':
        buttonTheme = 'primary';
        break;
      case 'author':
        buttonTheme = 'secondary';
        break;
      case 'wishlist':
        buttonTheme = 'secondary';
        break;
      case 'preorder':
        buttonTheme = 'preorder';
        break;

      default:
        buttonTheme = 'primary';
        break;
    }

    // Defaults
    newConfig.buttonHeight = buttonHeight ? buttonHeight : '40px';
    newConfig.buttonWidth = buttonWidth ? buttonWidth : null;
    newConfig.fontSize = fontSize ? fontSize : '16 px';

    newConfig.borderBorderColor = theme.button.primary;

    newConfig.checkBackgroundColor = theme.button.primary;
    newConfig.checkBackgroundColorHover = theme.button.primary;
    newConfig.checkTextColor = 'white';
    newConfig.checkIconColor = theme.button.primary;

    newConfig.exBackgroundColor = theme.button.primary;
    newConfig.exBackgroundColorHover = theme.button.primary;
    newConfig.exTextColor = 'white';
    newConfig.exIconColor = theme.button.primary;

    // states
    newConfig.hover = hover;
    newConfig.deletable = deletable;
    newConfig.inCart = inCart;

    // Theme
    if (buttonTheme === 'primary') {
      newConfig.textBackgroundColor = theme.button.primary;
      newConfig.textBackgroundColorHover = theme.button.primaryHover;
      newConfig.textBorderColor = 'transparent';
      newConfig.textBorderColorHover = 'transparent';
      newConfig.textTextColor = 'white';
      newConfig.textIconColor = 'white';

      newConfig.checkBackgroundColor = 'transparent';
      newConfig.checkBackgroundColorHover = theme.button.secondaryHover;
      newConfig.checkBorderColor = theme.button.primary;
      newConfig.checkBorderColorHover = theme.button.primaryHover;
      newConfig.checkTextColor = theme.button.primary;
      newConfig.checkIconColor = theme.button.primary;

      newConfig.exBackgroundColor = theme.button.primary;
      newConfig.exBackgroundColorHover = theme.button.primaryHover;
      newConfig.exBorderColor = theme.button.primary;
      newConfig.exBorderColorHover = theme.button.primaryHover;
      newConfig.exTextColor = 'white';
      newConfig.exIconColor = 'white';

      // Disabled
      if (disabled) {
        newConfig.buttonDisabled = true;
        newConfig.borderColor = theme.button.inactive;
        newConfig.backgroundColor = theme.button.inactive;
        newConfig.backgroundColorHover = theme.button.inactive;
        newConfig.textColor = 'white';
      }
    } else if (buttonTheme === 'secondary') {
      newConfig.textBackgroundColor = 'transparent';
      newConfig.textBackgroundColorHover = theme.button.secondaryHover;
      newConfig.textBorderColor = theme.button.tertiary;
      newConfig.textBorderColorHover = theme.button.tertiaryHover;
      newConfig.textTextColor = theme.button.tertiary;
      newConfig.textIconColor = theme.button.tertiary;

      newConfig.checkBackgroundColor = 'transparent';
      newConfig.checkBackgroundColorHover = theme.button.secondaryHover;
      newConfig.checkBorderColor = theme.button.tertiary;
      newConfig.checkBorderColorHover = theme.button.tertiaryHover;
      newConfig.checkTextColor = theme.button.tertiary;
      newConfig.checkIconColor = theme.button.tertiary;

      newConfig.exBackgroundColor = theme.button.tertiary;
      newConfig.exBackgroundColorHover = theme.button.tertiaryHover;
      newConfig.exBorderColor = theme.button.tertiary;
      newConfig.exBorderColorHover = theme.button.tertiaryHover;
      newConfig.exTextColor = 'white';
      newConfig.exIconColor = 'white';

      // Disabled
      if (disabled) {
        newConfig.buttonDisabled = true;
        newConfig.borderColor = theme.button.inactive;
        newConfig.backgroundColor = 'white';
        newConfig.backgroundColorHover = 'white';
        newConfig.iconColor = theme.button.inactive;
      }
    } else if (buttonTheme === 'preorder') {
      newConfig.textBackgroundColor = theme.button.preorder;
      newConfig.textBackgroundColorHover = theme.button.preorderHover;
      newConfig.textBorderColor = 'transparent';
      newConfig.textBorderColorHover = 'transparent';
      newConfig.textTextColor = 'white';
      newConfig.textIconColor = 'white';

      newConfig.checkBackgroundColor = 'transparent';
      newConfig.checkBackgroundColorHover = theme.button.secondaryHover;
      newConfig.checkBorderColor = theme.button.preorder;
      newConfig.checkBorderColorHover = theme.button.preorderHover;
      newConfig.checkTextColor = theme.button.preorder;
      newConfig.checkIconColor = theme.button.preorder;

      newConfig.exBackgroundColor = theme.button.preorder;
      newConfig.exBackgroundColorHover = theme.button.preorderHover;
      newConfig.exBorderColor = theme.button.preorder;
      newConfig.exBorderColorHover = theme.button.preorderHover;
      newConfig.exTextColor = 'white';
      newConfig.exIconColor = 'white';

      // Disabled
      if (disabled) {
        newConfig.buttonDisabled = true;
        newConfig.borderColor = theme.button.inactive;
        newConfig.backgroundColor = theme.button.inactive;
        newConfig.backgroundColorHover = theme.button.inactive;
        newConfig.textColor = 'white';
      }
    }

    setConfig(newConfig);
  }, [inCart, hover, disabled]);

  return (
    <AddButtonWrapper config={config} onMouseEnter={handleButtonMouseEnter} onMouseLeave={handleButtonMouseLeave} onClick={handleButtonClick}>
      {hover && !firstHover && deletable && inCart && (
        <ExLayer>
          <ImageEx></ImageEx>
        </ExLayer>
      )}
      {inCart && (
        <CheckLayer>
          {textIcon === 'heart' ? (
            <ImageHeartWrapper config={config}>
              <ImageHeart />
            </ImageHeartWrapper>
          ) : (
            <ImageCheckWrapper>
              <ImageCheck />
            </ImageCheckWrapper>
          )}

          {afterText && <AferTextWrapper>{afterText}</AferTextWrapper>}
        </CheckLayer>
      )}
      {!inCart && (
        <TextLayer>
          {textIcon && (
            <TextImage>
              {textIcon === 'heart' && (
                <TextImageHeart>
                  <ImageHeart />
                </TextImageHeart>
              )}
              {textIcon === 'plus' && (
                <TextImagePlus>
                  <ImagePlus />
                </TextImagePlus>
              )}
            </TextImage>
          )}
          {text && <TextWrapper>{text}</TextWrapper>}
        </TextLayer>
      )}
    </AddButtonWrapper>
  );

  function handleButtonMouseLeave() {
    setHover(false);
    if (inCart) firstHover && setFirstHover(false);
    if (inCart) setDeletable(true);
  }

  function handleButtonMouseEnter() {
    setHover(true);
    if (!inCart) {
      !firstHover && setFirstHover(true);
    } else {
      firstHover && setFirstHover(false);
    }
  }
}
