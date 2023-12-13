import Link from 'next/link';
import { ListItemImage, ImageWrapper, ListItemImageType, ProfileListImageComponent } from '@components/profileListImage/profileListImage.styled';
import OptimizedImage from '@components/Images/OptimizedImage';

export default function ProfileListImage({ slug, cover, type }) {
  return (
    <ProfileListImageComponent>
      <ListItemImage>
        <Link href={`/konyv/${slug}`} passHref>

          <ImageWrapper>
            <OptimizedImage src={cover} layout="intrinsic" width={120} height={120} objectFit="contain" objectPosition="" alt=""></OptimizedImage>
          </ImageWrapper>

        </Link>
      </ListItemImage>
      {type === 1 && <ListItemImageType>e-könyv</ListItemImageType>}
    </ProfileListImageComponent>
  );
}
