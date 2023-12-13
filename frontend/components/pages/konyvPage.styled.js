import styled from '@emotion/styled';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';
import settingsVars from "@vars/settingsVars";
import url from "@libs/url";

let settings = settingsVars.get(url.getHost());

let theme = {
  priceTag: colors.mediumPurple,
};

if (settings.key === 'OLCSOKONYVEK') {
  theme = {
    priceTag: colors.monza,
  };
}

if (settings.key === 'NAGYKER') {
  theme = {
    priceTag: colors.monza,
  };
}

export let KonyvPageWrapper = styled.div``;

export let ContentWrapper = styled.div``;

export let ContentWrapperEnd = styled.div``;

export let Product = styled.div`
  padding: 80px 0 40px;
`;

export let ProductInfo = styled.div``;

export let ProductImage = styled.div`
  margin-bottom: 20px;

  @media (max-width: ${breakpoints.max.md}) {
    padding: 0 50px;
  }

  @media (max-width: ${breakpoints.max.sm}) {
    padding: 0 25px;
  }
`;
export let ProductImageWrapper = styled.div`
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: ${colors.seashell};
  max-width: 363px;
  max-height: 400px;
`;
export let ProductImageType = styled.div`
  background-color: ${colors.malachite};
  height: 24px;
  font-weight: 700;
  font-size: 12px;
  text-align: center;
  color: white;
`;

export let ProductMeta = styled.div`
  margin-bottom: 20px;
`;

export let ProductMetaItem = styled.div`
  font-weight: 400;
  font-size: 12px;
  margin-bottom: 5px;

  @media (max-width: ${breakpoints.max.md}) {
    display: inline-block;
    margin-right: 20px;
  }
`;

export let ProductNav = styled.div``;

export let ProductContent = styled.div``;

export let ProductContentWrapper = styled.div`
  padding: 0 20px;
  display: flex;
  flex-direction: column;

  @media (max-width: ${breakpoints.max.lg}) {
    padding: 0;
  }
`;

export let ProductTitle = styled.div`
  order: 1;
  font-weight: 700;
  font-size: 36px;
  line-height: 44px;
  margin-bottom: 5px;

  @media (max-width: ${breakpoints.max.md}) {
    flex-direction: column;
    font-size: 22px;
    line-height: 30px;
    margin-bottom: 0;
  }
`;

export let ProductAuthor = styled.div`
  order: 2;
  font-size: 20px;
  line-height: 1.2;
  text-transform: uppercase;
  display: flex;
  flex-wrap: wrap;
  margin-bottom: 20px;

  @media (max-width: ${breakpoints.max.md}) {
    font-size: 16px;
  }
`;

export let AuthorName = styled.div`
  margin-right: 10px;
  &::after {
    content: ', ';
  }

  &:last-child {
    &::after {
      content: '';
    }
  }
`;

export let ProductTags = styled.div`
  order: 3;
  display: flex;
  flex-wrap: wrap;

  @media (max-width: ${breakpoints.max.md}) {
    order: 4;
  }
`;

export let ProductTag = styled.div`
  display: flex;
  align-items: center;
  margin-bottom: 20px;
  margin-right: 15px;
`;

export let ProductTagIcon = styled.div`
  width: 36px;
  position: relative;
  margin-right: 20px;
`;

export let ProductTagNumber = styled.div`
  position: absolute;
  font-weight: 600;
  font-size: 15px;
  color: white;
  line-height: 0;
  left: 50%;
  top: 45%;
  transform: translateX(-50%);
`;

export let ProductTagText = styled.div`
  font-weight: normal;
  font-size: 18px;

  @media (max-width: ${breakpoints.max.md}) {
    font-size: 16px;
  }
`;

export let ProductRatingWrapper = styled.div`
  order: 4;
  margin-bottom: 20px;

  @media (max-width: ${breakpoints.max.md}) {
    order: 3;
  }
`;

export let ProductDescriptionWrapper = styled.div`
  order: 5;
  text-align: justify;
  @media (max-width: ${breakpoints.max.md}) {
    margin-bottom: 40px;
  }
`;

export let ProductActions = styled.div``;

export let Price = styled.div`
  margin-bottom: 20px;
  display: flex;

  @media (max-width: ${breakpoints.max.lg}) {
    flex-direction: column;
  }

  @media (max-width: ${breakpoints.max.md}) {
    flex-direction: initial;
  }
`;

export let PriceValues = styled.div`
  flex: 1;
`;

export let PriceValuesOriginal = styled.div`
  font-weight: 400;
  font-size: 24px;
  line-height: 1;
  color: ${colors.silverChalice};
  text-decoration-line: line-through;
  margin-bottom: 10px;

  @media (max-width: ${breakpoints.max.md}) {
    font-size: 20px;
    margin-bottom: 6px;
  }
`;

export let PriceValuesDiscount = styled.div`
  font-weight: 600;
  font-size: 32px;
  line-height: 1;
  color: ${colors.mineShaft};
`;

export let PriceTagWrapper = styled.div`
  align-self: flex-end;

  @media (max-width: ${breakpoints.max.lg}) {
    width: 100%;
    margin-top: 15px;
  }

  @media (max-width: ${breakpoints.max.md}) {
    width: initial;
    margin-top: 0;
  }
`;

export let PriceTag = styled.div`
  display: flex;
  height: 50px;
  align-items: center;
  justify-content: center;
  padding: 0 20px;
  background-color: ${theme.priceTag};
  color: white;
  border-radius: 10px;
  font-weight: 600;
  font-size: 24px;
  line-height: 1;

  @media (max-width: ${breakpoints.max.xl}) {
    height: 40px;
    padding: 0 10px;
  }

  @media (max-width: ${breakpoints.max.lg}) {
    height: 30px;
    width: 100%;
  }

  @media (max-width: ${breakpoints.max.md}) {
    height: 40px;
    width: auto;
    padding: 0 20px;
  }
`;

export let ActionButtonWrapper = styled.div`
  margin-bottom: 10px;

  @media (max-width: ${breakpoints.max.md}) {
    margin-bottom: 5px;
  }
`;

export let InfoLabel = styled.div`
  margin-bottom: 40px;
  font-weight: 400;
  font-size: 14px;

  @media (max-width: ${breakpoints.max.md}) {
    margin-bottom: 25px;
    font-size: 12px;
  }
`;

export let WishlistButtonWrapper = styled.div`
  margin-bottom: 30px;
`;

export let IconBoxWrapper = styled.div`
  @media (max-width: ${breakpoints.max.md}) {
    margin-bottom: 25px;
  }
`;

export let Author = styled.div`
  margin-bottom: 120px;

  @media (max-width: ${breakpoints.max.md}) {
    margin-bottom: 50px;
  }
`;

export let Lists = styled.div``;

export let Test = styled.div``;

export let ListWrapper = styled.div`
  margin-bottom: 90px;
`;

export let ProductDescriptionTitle = styled.div`
  font-weight: 700;
  font-size: 16px;
  margin-bottom: 15px;
`;

export let CommentsWrapper = styled.div`
  padding: 70px 0 100px;
  background-color: ${colors.zirconBlue};

  @media (max-width: ${breakpoints.max.md}) {
    padding: 35px 0 60px;
  }
`;

export let CommentsWrapperContainer = styled.div``;

export let CommentsWrapperRow = styled.div``;

export let CommentsWrapperCol = styled.div``;

export let Overlay = styled.div``;

export let OverlayCard = styled.div``;

export const PriceValueContainer = styled.div`
  display: flex;
  align-items: center;
  gap: 5px;

  p {
    margin: 0;
    font-weight: 600;
    font-size: 16px;
  }
`;

export const ProductDiscountInfo = styled.div`
  display: flex;
  align-items: center;
  gap: 5px;

  p {
    margin: 0 0 10px;
    &:first-of-type {
      font-weight: 600;
    }
    &:last-of-type {
      font-weight: 700;
    }
  }
`;
