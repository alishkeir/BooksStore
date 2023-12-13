import styled from '@emotion/styled';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';

export let CartItemComponent = styled.div`
  margin-top: 30px;
`;

export let Title = styled.div`
  font-weight: 700;
  font-size: 24px;
  margin-top: ${({ mtd }) => (mtd ? `${mtd}px` : '0')};
  margin-bottom: ${({ mbd }) => (mbd ? `${mbd}px` : '0')};
  color: ${({ color }) => `${colors[color]}`};

  @media (max-width: ${breakpoints.max.xl}) {
    font-size: 20px;
    margin-top: ${({ mtm }) => (mtm ? `${mtm}px` : '0')};
    margin-bottom: ${({ mbm }) => (mbm ? `${mbm}px` : '0')};
  }
`;

export let BooksWrapper = styled.div`
  display: flex;
  justify-content: space-between;
`;

export let BookWrapper = styled.div`
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding-bottom: 30px;
  border-bottom: 1px solid ${colors.mischka};
`;

export let BookDetailsWrapper = styled.div`
  display: flex;
  align-items: center;
`;

export let BookCoverWrapper = styled.div`
  padding: 10px 15px;
  background-color: ${colors.titanWhite};
`;

export let EBookLabel = styled.div`
  text-align: center;
  color: white;
  background-color: ${colors.malachite};
  font-weight: 600;
  font-size: 12px;
  line-height: 18px;
  border-radius: 0px 0px 10px 10px;
`;

export let AuthorSection = styled.div`
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  margin-left: 21px;

  @media (max-width: ${breakpoints.max.xl}) {
    margin-left: 23px;
  }
`;

export let BookTitle = styled.div`
  font-size: 16px;
  font-weight: 600;
  margin-bottom: -4px;
`;

export let Author = styled.div`
  font-size: 12px;
  color: ${colors.silverChaliceDark};
  margin-bottom: 16px;

  @media (max-width: ${breakpoints.max.xl}) {
    font-size: 14px;
  }
`;

export let OldPriceSecondary = styled.div`
  font-size: 12px;
  font-weight: 400;
  text-decoration: line-through;
  color: ${colors.mischka};

  @media (max-width: ${breakpoints.max.xl}) {
    font-size: 14px;
  }
`;

export let PriceSecondary = styled.div`
  font-size: 16px;
  font-weight: 600;
  color: ${colors.silverChaliceDark};

  @media (max-width: ${breakpoints.max.xl}) {
    font-size: 18px;
    font-weight: 600;
    color: ${colors.mineShaft};
  }
`;

export let CounterWrapper = styled.div`
  display: flex;
`;

export let PriceWrapper = styled.div`
  margin-left: 24px;
  min-width: 90px;
`;

export let Price = styled.div`
  font-size: 20px;
  font-weight: 700;
  text-align: end;

  @media (max-width: ${breakpoints.max.xl}) {
    font-size: 18px;
  }
`;

export let OldPrice = styled.div`
  text-decoration: line-through;
  color: ${colors.silverChaliceDark};
  text-align: end;

  @media (max-width: ${breakpoints.max.xl}) {
    font-size: 14px;
  }
`;

export const PriceContainer = styled.div`
  display: flex;
  align-items: center;
  gap: 0.3125rem;

  p {
    margin: 0;
    font-weight: 600;
    color: ${colors.silverChaliceDark};
  }

  &:last-of-type {
    @media (max-width: ${breakpoints.max.xl}) {
      margin-bottom: 1.25rem;
    }
  }
`;