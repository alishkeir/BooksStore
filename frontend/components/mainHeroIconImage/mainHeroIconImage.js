import { ImageWrapper, MainHeroIconImageWrapper } from './mainHeroIconImage.styled';

export default function MainHeroIconImage({ image: Image, imageWidth, imageHeight }) {
  return (
    <MainHeroIconImageWrapper>
      <ImageWrapper imageWidth={imageWidth} imageHeight={imageHeight}>
        <Image  alt=""></Image>
      </ImageWrapper>
    </MainHeroIconImageWrapper>
  );
}