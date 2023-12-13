import { MainHeroIconComponent, Icon, Text, ImageWrapper } from './mainHeroIcon.styled';

export default function MainHeroIcon({ image: Image, imageWidth, imageHeight, children, justify }) {
  return (
    <MainHeroIconComponent justify={justify}>
      <Icon>
        <ImageWrapper imageWidth={imageWidth} imageHeight={imageHeight}>
          <Image loading="lazy" alt=""></Image>
        </ImageWrapper>
      </Icon>
      <Text>{children}</Text>
    </MainHeroIconComponent>
  );
}
