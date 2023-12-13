import Link from 'next/link';
import Icon from '../icon/icon';
import { HeaderIconCartComponent, CartNumber } from './headerIconCart.styled';
import colors from '@vars/colors';

export default function HeaderIconCart(props) {
  let { count } = props;

  return (
    <HeaderIconCartComponent disabled={!!parseInt(count) > 0}>
      <Link href={`${parseInt(count) > 0 ? '/kosar' : '/'}`} passHref>

        <Icon color={`${parseInt(count) > 0 ? colors.mineShaft : colors.mischka}`} type="cart" iconWidth="23px" iconHeight="23px"></Icon>
        {parseInt(count) > 0 && <CartNumber>{count}</CartNumber>}

      </Link>
    </HeaderIconCartComponent>
  );
}
