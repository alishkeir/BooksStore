import styled from '@emotion/styled';
import colors from '@vars/colors';
import theme from '@vars/theme';
import breakpoints from '@vars/breakpoints';

export let IconBoxWrapper = styled.div`
  padding: 30px 20px;
  box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.07);
  border-radius: 10px;
  background-color: ${colors.zircon};
`;

export let IconWrapper = styled.div`
  display: flex;
  align-items: center;
  margin-bottom: 20px;

  &:last-child {
    margin-bottom: 0;
  }

  @media (max-width: ${breakpoints.max.lg}) {
    flex-direction: column;
    justify-content: center;
    text-align: center;
  }

  @media (max-width: ${breakpoints.max.md}) {
    flex-direction: initial;
    justify-content: initial;
    text-align: initial;
  }
`;

export let MainHeroIconImageWrapper = styled.div`
  width: 60px;
  height: 60px;
  min-width: 60px;
  min-height: 60px;
  margin-right: 20px;

  @media (max-width: ${breakpoints.max.lg}) {
    margin-right: 0;
    margin-bottom: 10px;
  }

  @media (max-width: ${breakpoints.max.md}) {
    margin-right: 20px;
    margin-bottom: 0;
  }
`;

export let Text = styled.div`
  font-weight: 400;
  font-size: 14px;

  span {
    font-weight: 700;
    color: ${theme.iconPin.bold};
  }
`;
