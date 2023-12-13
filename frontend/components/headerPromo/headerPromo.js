import { useEffect, useState } from 'react';
import useUser from '@hooks/useUser/useUser';
import { Content, HeaderPromoWrapper, IconWrapper, TruckIcon } from '@components/headerPromo/headerPromo.styled';

export default function HeaderPromo() {
  let defaultState = {
    color: 'eggWhite',
    text: '',
  };
  let { actualUser } = useUser();
  let [bannerOpts, setBannerOpts] = useState(defaultState);

  useEffect(() => {
    let cart = null;

    if (actualUser?.customer?.cart?.free_shipping) {
      cart = actualUser?.customer?.cart;

      if (cart.cart_items.length === 0 && !!cart.free_shipping?.show) {
        setBannerOpts({
          color: 'pattensBlue',
          text: cart.free_shipping?.message,
        });
      } else if (cart.cart_items.length !== 0 && !!cart.free_shipping?.free === false) {
        setBannerOpts({
          color: 'eggWhite',
          text: cart.free_shipping?.message,
        });
      } else if (cart.cart_items.length !== 0 && !!cart.free_shipping?.free === true) {
        setBannerOpts({
          color: 'harp',
          text: cart.free_shipping?.message,
        });
      }
    }
  }, [actualUser]);
  return (
    <>
      {actualUser?.customer?.cart?.free_shipping?.show && (
        <HeaderPromoWrapper bannerOpts={bannerOpts}>
          <IconWrapper>
            <TruckIcon type="delivery-truck" iconWidth="28px" iconColor="black"></TruckIcon>
          </IconWrapper>
          <Content dangerouslySetInnerHTML={{ __html: bannerOpts.text }}></Content>
        </HeaderPromoWrapper>
      )}
    </>
  );
}
