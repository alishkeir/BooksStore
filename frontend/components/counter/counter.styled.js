import styled from '@emotion/styled';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';

export let Counter = styled.div`
  display: flex;
  border: 1px solid ${colors.mischka};
  justify-content: space-around;
  width: 140px;
  height: 50px;
  border-radius: 10px;
  font-size: 18px;
  font-weight: 700;
  align-items: center;

  @media (max-width: ${breakpoints.max.xl}) {
    height: 40px;
  }
`;

export let CounterButtonPlus = styled.button`
  width: 100%;
  height: 100%;
  line-height: 48px;
  text-align: center;
  padding-right: 18px;
  border: none;
  background-color: transparent;

  @media (max-width: ${breakpoints.max.xl}) {
    line-height: 40px;
  }
`;

export let CounterButtonMinus = styled.button`
  width: 100%;
  height: 100%;
  line-height: 48px;
  text-align: center;
  padding-left: 18px;
  border: none;
  background-color: transparent;

  @media (max-width: ${breakpoints.max.xl}) {
    line-height: 40px;
  }
`;

export let Amount = styled.div`
  text-align: center;
  min-width: 20px;
`;
