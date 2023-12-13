import Counter from '@components/counter/counter';
import useMediaQuery from '@hooks/useMediaQuery/useMediaQuery';
import breakpoints from '@vars/breakpoints';
import Currency from '@libs/currency';

import {
  Author,
  AuthorSection,
  BookTitle,
  BookWrapper,
  BookDetailsWrapper,
  BookCoverWrapper,
  CartItemComponent,
  CounterWrapper,
  OldPriceSecondary,
  OldPrice,
  Price,
  PriceSecondary,
  PriceWrapper,
  EBookLabel,
  PriceContainer,
} from '@components/cartItem/cartItem.styled';
import OptimizedImage from '@components/Images/OptimizedImage';

export default function CartItem(props) {
  let isMinXL = useMediaQuery(`(min-width: ${breakpoints.min.xl})`);
  let { id, cover, title, authors, price_sale, price_list, quantity = 0, incrementAmount = () => {}, decrementAmount = () => {}, type } = props;

  function getAuthors(authors) {
    return authors.map((author) => author.title).join(', ');
  }

  return (
    <CartItemComponent>
      <BookWrapper>
        <BookDetailsWrapper>
          <div>
            <BookCoverWrapper>
              {isMinXL ? (
                <OptimizedImage src={cover} width="50" height="76" layout="intrinsic" alt={title}></OptimizedImage>
              ) : (
                <OptimizedImage src={cover} width="84" height="137" layout="intrinsic" alt={title}></OptimizedImage>
              )}
            </BookCoverWrapper>
            {type === 1 && <EBookLabel>e-könyv</EBookLabel>}
          </div>
          <AuthorSection>
            <div>
              <BookTitle>{title}</BookTitle>
              <Author>{getAuthors(authors)}</Author>
            </div>
            <div>
              <PriceContainer>
                <p>Borító ár:</p>
                <OldPriceSecondary>{Currency.format(isMinXL ? price_list : price_list * quantity)}</OldPriceSecondary>
              </PriceContainer>
              <PriceContainer>
                <p>Akciós ár:</p>
                <PriceSecondary>{Currency.format(isMinXL ? price_sale : price_sale * quantity)}</PriceSecondary>
              </PriceContainer>
            </div>
            {!isMinXL && <Counter onclickPlus={incrementAmount} onclickMinus={decrementAmount} value={quantity} bookId={id}></Counter>}
          </AuthorSection>
        </BookDetailsWrapper>
        <CounterWrapper>
          {isMinXL && <Counter onclickPlus={incrementAmount} onclickMinus={decrementAmount} value={quantity} bookId={id}></Counter>}
          {isMinXL && (
            <PriceWrapper>
              <OldPrice>{Currency.format(price_list * quantity)}</OldPrice>
              <Price>{Currency.format(price_sale * quantity)}</Price>
            </PriceWrapper>
          )}
        </CounterWrapper>
      </BookWrapper>
    </CartItemComponent>
  );
}
