import { useEffect } from 'react';
import Link from 'next/link';
import { useRouter } from 'next/router';
import AddButton from '@components/addButton/addButton';
import ImageBadge from '@assets/images/elements/badge.svg';
import ImageCoverEmpty from '@assets/images/elements/cover-empty.svg';
import Currency from '@libs/currency';
import { analytics } from '@libs/analytics';
import {
  BadgeWrapper,
  ImageBadgeNumber,
  BookCardWrapper,
  Bottom,
  ImageWrapper,
  DeafultImageWrapper,
  Tag,
  TagWrapper,
  Top,
  Type,
  Title,
  Author,
  PriceAction,
  Price,
  PriceTop,
  PriceBottom,
  Action,
  PriceContainer,
} from './bookCard.styled';
import OptimizedImage from '@components/Images/OptimizedImage';

export default function BookCard(props) {
  let {
    itemId,
    discount,
    slug,
    isNew,
    serial,
    bookType,
    purchaseType,
    title,
    author,
    price,
    originalPrice,
    imageSrc,
    prefetch,
    cartPrice,
    isCart = false,
    list = '', // google
    position = null, // google
  } = props;

  let router = useRouter();

  let itemObj = {
    id: itemId,
    name: title,
    list_name: router.route ?? list,
    brand: null,
    category: bookType,
    variant: bookType,
    list_position: position,
    quantity: 1,
    price: price,
  };

  // purchaseType can be: normal, preorder
  let buttonType = 'cart';
  switch (purchaseType) {
    case 'normal':
      buttonType = 'cart';
      break;
    case 'preorder':
      buttonType = 'preorder';
      break;
  }

  useEffect(() => {
    if (!itemId) return;

    analytics.addImpression(itemObj);
  }, [itemId]);

  return (
    <BookCardWrapper>
      <Link href={`/konyv/${slug}`} prefetch={prefetch} passHref>

        <Top>
          {imageSrc && (
            <ImageWrapper>
              <OptimizedImage src={imageSrc} layout="intrinsic" width={230} height={230} objectFit="contain" alt=""></OptimizedImage>
            </ImageWrapper>
          )}
          {!imageSrc && (
            <DeafultImageWrapper>
              <ImageCoverEmpty></ImageCoverEmpty>
            </DeafultImageWrapper>
          )}
          <TagWrapper>
            {!!discount && <Tag type="discount">{discount}%</Tag>}
            {isNew && <Tag type="new">ÚJ</Tag>}
          </TagWrapper>
          {serial && (
            <BadgeWrapper>
              <ImageBadgeNumber>{serial}</ImageBadgeNumber>
              <ImageBadge></ImageBadge>
            </BadgeWrapper>
          )}
          {bookType === 'ebook' && <Type type={bookType}>e-könyv</Type>}
        </Top>

      </Link>
      <Bottom>
        <Title>
          <Link href={`/konyv/${slug}`} prefetch={prefetch} passHref>
            {title}
          </Link>
        </Title>
        <Author>{author}</Author>
        <PriceAction>
          <Price>
            {originalPrice && (
              <PriceContainer>
                <p>Borító ár:</p>
                <PriceTop>{Currency.format(originalPrice)}</PriceTop>
              </PriceContainer>
            )}
            {price && (
              <PriceContainer>
                <p>Akciós ár:</p>
                <PriceBottom>{Currency.format(isCart ? cartPrice : price)}</PriceBottom>
              </PriceContainer>
            )}
          </Price>
          <Action>
            <AddButton
              isCartPrice={isCart && cartPrice}
              buttonHeight="40px"
              buttonWidth="90px"
              fontSize="14px"
              itemObj={{
                id: itemId,
                title: title,
                price: isCart ? cartPrice : price,
              }}
              itemId={itemId}
              item={itemObj}
              type={buttonType}
              text={buttonType === 'cart' ? 'Kosárba' : buttonType === 'preorder' ? 'Előjegyzés' : ''}
            ></AddButton>
          </Action>
        </PriceAction>
      </Bottom>
    </BookCardWrapper>
  );
}
