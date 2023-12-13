import OptimizedImage from '@components/Images/OptimizedImage';
import Link from 'next/link';
import { format, parseISO } from 'date-fns/fp';
import Button from '@components/button/button';
import useMediaQuery from '@hooks/useMediaQuery/useMediaQuery';
import breakpoints from '@vars/breakpoints';
import { Actions, Bottom, Date, ImageWrapper, MagazineCardWrapper, Text, TitleText } from '@components/magazineCard/magazineCard.styled';

export default function MagazineCard(props) {
  let { cover, published_at, slug, title } = props.magazine;
  let parsedISODate = parseISO(published_at);
  let isMinMd = useMediaQuery(`(min-width: ${breakpoints.min.md})`);

  return (
    <MagazineCardWrapper>
      <ImageWrapper>
        <Link href={`/magazin/${slug}`} passHref>

          <OptimizedImage src={cover} width="491" height="491" layout="responsive" alt="" />

        </Link>
      </ImageWrapper>
      <Bottom>
        <Text>
          <Date>{parsedISODate && format('yyyy. MM. dd.', parsedISODate)}</Date>
          <Link href={`/magazin/${slug}`} passHref>

            <TitleText>{title}</TitleText>

          </Link>
        </Text>
        <Actions>
          <Link href={`/magazin/${slug}`} passHref>

            <Button buttonWidth={isMinMd ? '120px' : '100%'} buttonHeight="50px" type="secondary">
              Részletek
            </Button>

          </Link>
        </Actions>
      </Bottom>
    </MagazineCardWrapper>
  );
}
