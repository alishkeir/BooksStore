import { MainStoreMapComponent } from './mainStoreMap.styled';
import OptimizedImage from '@components/Images/OptimizedImage';

export default function MainStoreMap({ banner, ...rest }) {
  return (
    <MainStoreMapComponent>
      {banner?.cover && <OptimizedImage src={banner.cover} layout="intrinsic" height="400" width="491" {...rest} alt=""></OptimizedImage>}
    </MainStoreMapComponent>
  );
}
