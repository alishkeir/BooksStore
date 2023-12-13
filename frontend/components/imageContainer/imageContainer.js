import Image from 'next/image';
import Link from 'next/link';
import {
  Address,
  AddressWrapper,
  City,
  ImageContainer,
  ImageTitle,
  ImageWrapper,
  LocationtIcon,
  RowWrapper,
  Separator,
  SubtitleWrapper,
  ShopImageWrapper
} from '@components/imageContainer/imageContainer.styled';
import useMediaQuery from '@hooks/useMediaQuery/useMediaQuery';
import breakpoints from '@vars/breakpoints';

function getDivideArray(array, parts) {
  let result = [];
  for (let i = parts; i > 0; i--) {
    result.push(array.splice(0, Math.ceil(array.length / i)));
  }
  return result;
}

export default function ImagesContainer(props) {
  let { images = [], order = [0, 2, 0, 2, 0, 2] } = props;
  let imageRows = [images];
  let isMinMd = useMediaQuery(`(min-width: ${breakpoints.min.md})`);

  if (images.length) {
    imageRows = getDivideArray(images, images.length / 3);
  }
  return <>
    {imageRows.map((row, rowIndex) => {
      return (
        <ImageContainer isMinMd={isMinMd} key={rowIndex}>
          {row.map((image, imageIndex) => (
            <RowWrapper key={imageIndex} title={image.title} hasFilter={!!image.hasFilter}>
              {order[rowIndex] === imageIndex && isMinMd ? (
                <ImageWrapper>
                  {image.title && <ImageTitle>{image.title}</ImageTitle>}
                  <Link href={`/akcio/${image.slug ? image.slug : ''}`} passHref>

                    <Image loading="lazy" src={image.src.xl} width={604} height={340} layout="intrinsic" alt=""></Image>

                  </Link>
                </ImageWrapper>
              ) : (
                <ImageWrapper
                  onClick={() => {
                    image.handler && image.handler(image.shop);
                  }}
                >
                  {image.title && <ImageTitle>{image.title}</ImageTitle>}
                  {image.isShop ? (
                    <ShopImageWrapper>
                      <Image loading="lazy" src={image.src.sm} width={425} height={340} layout="intrinsic" alt=""></Image>
                    </ShopImageWrapper>

                  ) : (
                    (<Link href={`/akcio/${image.slug ? image.slug : ''}`} passHref>

                      <Image loading="lazy" src={image.src.sm} width={425} height={340} layout="intrinsic" alt=""></Image>

                    </Link>)
                  )}
                  {image.subtitle && (
                    <SubtitleWrapper title={image.title}>
                      <City>{image.subtitle.city}</City>
                      <Separator></Separator>
                      <AddressWrapper>
                        <LocationtIcon type="location" strokeColor="yellow"></LocationtIcon>
                        <Address>{image.subtitle.address}</Address>
                      </AddressWrapper>
                    </SubtitleWrapper>
                  )}
                </ImageWrapper>
              )}
            </RowWrapper>
          ))}
        </ImageContainer>
      );
    })}
  </>;
}
