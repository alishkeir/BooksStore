import styled from '@emotion/styled';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';

export let Tr = styled.tr`
  margin: 0;
  padding: 0;
`;

export let Tbody = styled.tbody`
  @media (max-width: ${breakpoints.max.lg}) {
    font-size: 12px;
  }
`;

export let Td = styled.td`
  padding: 20px;

  @media (max-width: ${breakpoints.max.lg}) {
    padding: 20px 10px;
  }
`;

export let GrayTr = styled(Tr)`
  box-sizing: border-box;
  cursor: pointer;

  ${Td} {
    position: relative;

    span {
      position: relative;
      z-index: 1;
    }

    &::after {
      content: '';
      display: block;
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background: ${colors.titanWhite};
      z-index: 0;
      pointer-events: none;
    }

    &:first-of-type {
      white-space: nowrap;

      &::after {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 0;
      }
    }

    &:last-of-type {
      text-align: left;
      padding-right: 40px;

      &::after {
        border-top-right-radius: 10px;
        border-bottom-right-radius: 0;
      }
    }
  }
`;

export let GrayIcon = styled.div`
  width: 16px;
  position: absolute;
  z-index: 1;
  right: 20px;
  top: 50%;
  transform: translateY(-50%);
`;

export let IconWrapper = styled.div`
  width: 10px;
  margin: 0 auto;
  transform-origin: 50%;
  transition: transform 0.2s ease-in-out;
  transform: ${({ open }) => (open ? 'rotateZ(-90deg)' : 'rotateZ(90deg)')};
`;

export let ProductTr = styled(Tr)`
  ${Td} {
    &:last-of-type {
      text-align: right;
    }
  }
`;

export let ProductImageTitle = styled.div`
  display: flex;
  align-items: center;
`;

export let ProductImage = styled.div`
  height: 60px;
  width: 60px;
  background: ${colors.titanWhite};
  border-radius: 4px;
  margin-right: 20px;
  display: flex;
  justify-content: center;
  align-items: center;
`;

export let ProductAuthorTitle = styled.div``;

export let ProductAuthors = styled.div`
  font-size: 12px;
  line-height: 15px;
  margin-bottom: 3px;
  color: ${colors.silverChaliceDark};
`;

export let ProductAuthor = styled.div`
  display: inline-block;

  &::after {
    content: ',';
    margin-right: 5px;
  }

  &:last-of-type {
    &::after {
      display: none;
    }
  }
`;

export let ProductTitle = styled.div`
  font-weight: 600;
  font-size: 14px;
  line-height: 18px;

  @media (max-width: ${breakpoints.max.lg}) {
    font-size: 12px;
  }
`;

export let ProductPrizes = styled.div`
  color: ${colors.silverChaliceDark};
`;

export let ProductOriginalPrize = styled.div`
  font-size: 12px;
  line-height: 16px;
  text-decoration: line-through;
`;

export let ProductFinalPrize = styled.div`
  font-weight: 600;
  font-size: 14px;
  line-height: 24px;

  @media (max-width: ${breakpoints.max.lg}) {
    font-size: 12px;
  }
`;

export let ProductCount = styled.div`
  color: ${colors.silverChaliceDark};
  font-size: 12px;
`;

export let ProductSum = styled.div`
  font-weight: 600;
  font-size: 14px;
  color: ${colors.mineShaft};
  text-align: right;

  @media (max-width: ${breakpoints.max.lg}) {
    font-size: 12px;
  }
`;

export let ProductMobileTr = styled.tr``;

export let DeliveryTr = styled(Tr)`
  font-weight: 600;
  font-size: 14px;

  @media (max-width: ${breakpoints.max.lg}) {
    font-size: 12px;
  }

  ${Td} {
    padding-top: 0;

    @media (max-width: ${breakpoints.max.lg}) {
      padding-top: 10px;
      padding-bottom: 10px;
    }

    &:last-of-type {
      text-align: right;
    }
  }
`;

export let SumTr = styled(Tr)`
  font-weight: 600;
  font-size: 14px;
  border-bottom: 1px solid ${colors.mischka};

  @media (max-width: ${breakpoints.max.lg}) {
    font-size: 12px;
  }

  ${Td} {
    @media (max-width: ${breakpoints.max.lg}) {
      padding-top: 10px;
    }

    &:last-of-type {
      text-align: right;
    }
  }
`;

export let OrderInfoTr = styled(Tr)`
  border-bottom: 1px solid ${colors.mischka};

  p {
    margin-bottom: 10px;

    @media (max-width: ${breakpoints.max.lg}) {
      margin-bottom: 5px;
    }

    &:last-of-type {
      margin-bottom: 0;
    }
  }
`;

export let OrderInfo = styled.div`
  display: flex;
  flex-wrap: wrap;

  @media (max-width: ${breakpoints.max.lg}) {
    margin-bottom: -25px;
  }
`;

export let OrderInfoCol = styled.div`
  width: 25%;
  text-align: left;

  @media (max-width: ${breakpoints.max.lg}) {
    width: 50%;
    margin-bottom: 25px;
  }
`;

export let OrderInfoTitle = styled.div`
  font-weight: 600;
  font-size: 14px;
  margin-bottom: 10px;

  @media (max-width: ${breakpoints.max.lg}) {
    font-size: 12px;
  }
`;

export let OrderInfoText = styled.div`
  font-weight: 300;
  font-size: 12px;
  line-height: 16px;
`;

export let OrderProductTbodyComponent = styled(Tbody)`
  position: relative;
  line-height: 1;

  &::after {
    content: '';
    display: block;
    position: absolute;
    left: -1px;
    top: -1px;
    width: calc(100% + 2px);
    height: calc(100% + 2px);
    z-index: 0;
    border: 1px solid ${colors.mischka};
    border-radius: 10px;
    pointer-events: none;
  }
`;

export let ProductMobileTrGrid = styled.div`
  display: grid;
  grid-row-gap: 10px;
  grid-template-columns: 0fr auto auto auto;
  grid-template-rows: 60px auto;
  align-items: center;

  @media (max-width: ${breakpoints.max.lg}) {
    ${ProductImage} {
      grid-row-start: 1;
      grid-row-end: 3;
      align-self: start;
    }

    ${ProductAuthorTitle} {
      grid-column-start: 2;
      grid-column-end: 5;
    }

    ${ProductPrizes},
    ${ProductCount},
    ${ProductSum} {
      line-height: 24px;
      align-self: end;
    }
  }
`;

export let ProductBlock = styled.div``;

export let OrderLinkTr = styled(Tr)``;
