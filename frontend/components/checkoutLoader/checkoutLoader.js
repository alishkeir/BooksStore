import dynamic from 'next/dynamic';
import { CheckoutLoaderComponent, LoaderWrapper, Text } from '@components/checkoutLoader/checkoutLoader.styled';
import colors from '@vars/colors';

let LoaderIcon = dynamic(() => import('react-spinners/BeatLoader'));

export default function CheckoutLoader({ children }) {
  return (
    <CheckoutLoaderComponent>
      <LoaderWrapper>
        <LoaderIcon color={colors.monza}></LoaderIcon>
      </LoaderWrapper>
      <Text>{children}</Text>
    </CheckoutLoaderComponent>
  );
}
