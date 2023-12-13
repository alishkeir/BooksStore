import styled from '@emotion/styled';
import colors from '@vars/colors';
import theme from '@vars/theme';
import breakpoints from '@vars/breakpoints';

export let Top = styled.div`
  position: relative;
  height: 260px;
  background-color: gray;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 10px;
  background-color: ${colors.titanWhite};
  padding: 15px;
  z-index: 0;
`;

export let ImageWrapper = styled.div`
  width: 100%;
  height: 100%;
  max-width: 230px;
  max-height: 230px;
  position: relative;
  text-align: center;
  transition: transform 0.5s ease-in-out;
  display: flex;
  justify-content: center;
  align-items: center;

  @media (max-width: ${breakpoints.max.xl}) {
    max-width: 150px;
    max-height: 150px;
  }
  > div {
    overflow: initial !important;
  }
  img {
    filter: drop-shadow(-20px 20px 20px rgba(0, 0, 0, 0.2));
  }
`;

export let DeafultImageWrapper = styled.div`
  width: 100%;
  height: 100%;
  max-width: 149px;
  max-height: 193px;
  position: relative;
  text-align: center;

  @media (max-width: ${breakpoints.max.xl}) {
    max-width: 93px;
    max-height: 122px;
  }
  > div {
    overflow: initial !important;
  }
`;

export let BookCardWrapper = styled.div`
  &:hover {
    ${ImageWrapper} {
      transform: scale(1.13);
    }
  }
`;

export let TagWrapper = styled.div`
  position: absolute;
  top: 40px;
  left: 0;

  @media (max-width: ${breakpoints.max.xl}) {
    top: 20px;
  }
`;
export let Tag = styled.div`
  width: 55px;
  height: 30px;
  border-radius: 0px 10px 10px 0px;
  background-color: ${({ type }) => {
    return type === 'discount' ? theme.badge.discount : theme.badge.tag;
  }};
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: ${({ type }) => {
    return type === 'discount' ? '18px' : '16px';
  }};
  line-height: 1;
  color: white;
  margin-bottom: 10px;

  @media (max-width: ${breakpoints.max.xl}) {
    width: 50px;
    height: 22px;
    border-radius: 0px 6px 6px 0px;
    font-size: ${({ type }) => {
      return type === 'discount' ? '16px' : '14px';
    }};
  }
`;
export let ImageBadgeNumber = styled.div`
  position: absolute;
  z-index: 3;
  font-weight: 600;
  font-size: 15px;
  line-height: 0;
  left: 50%;
  transform: translateX(-50%);
  top: 40%;
`;

export let BadgeWrapper = styled.div`
  position: absolute;
  top: 0;
  right: 0;
  height: 48px;
  width: 36px;
  font-weight: 600;
  font-size: 18px;
  color: white;
  z-index: 1;
  background-size: 100%;
  background-repeat: no-repeat;

  @media (max-width: ${breakpoints.max.xl}) {
    height: 41px;
    width: 31px;
  }

  svg {
    display: block;
    height: 100%;
    width: 100%;
    filter: drop-shadow(-2px 4px 5px rgba(0, 0, 0, 0.1));
  }
`;
export let Type = styled.div`
  position: absolute;
  left: 0;
  bottom: 0;
  right: 0;
  height: 24px;
  font-weight: 600;
  font-size: 12px;
  line-height: 18px;
  width: 100%;
  background-color: ${theme.badge.type};
  border-radius: 0px 0px 10px 10px;
  text-align: center;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
`;
export let Bottom = styled.div`
  padding: 15px;

  @media (max-width: ${breakpoints.max.xl}) {
    padding: 15px 0;
  }
`;
export let Title = styled.div`
  font-weight: bold;
  font-size: 16px;
  line-height: 22px;
  color: ${colors.mineShaft};
  height: 45px;
  overflow: hidden;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;

  @media (max-width: ${breakpoints.max.xl}) {
    font-size: 14px;
    line-height: 20px;
    height: 40px;
    margin-bottom: 10px;
  }
`;
export let Author = styled.div`
  font-size: 14px;
  line-height: 16px;
  height: 33px;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  text-transform: uppercase;
  margin-bottom: 15px;
  color: ${colors.silverChalice};

  @media (max-width: ${breakpoints.max.md}) {
    font-size: 12px;
  }
`;
export let PriceAction = styled.div`
  display: flex;
  align-items: center;
  min-height: 45px;
`;
export let Price = styled.div`
  margin-right: 4px;
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 5px;
`;
export let PriceTop = styled.div`
  font-size: 14px;
  text-decoration: line-through;
  color: ${colors.silverChalice};
  line-height: 1;

  @media (max-width: ${breakpoints.max.xl}) {
    font-size: 12px;
  }
`;
export let PriceBottom = styled.div`
  font-weight: 600;
  font-size: 18px;
  color: ${colors.mineShaft};

  @media (max-width: ${breakpoints.max.xl}) {
    font-size: 16px;
  }
`;
export let Action = styled.div``;

export const PriceContainer = styled.div`
  display: flex;
  align-items: center;
  gap: 0.3125rem;
  column-gap: 0.3125rem;
  flex-wrap: wrap;

  p {
    margin: 0;
    font-weight: 600;
    font-size: 0.875rem;
    line-height: 1;

    @media (max-width: ${breakpoints.max.xl}) {
      font-size: 0.75rem;
    }
  }
`;