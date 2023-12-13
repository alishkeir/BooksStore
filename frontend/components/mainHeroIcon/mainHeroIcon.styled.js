import styled from '@emotion/styled';
import theme from '@vars/theme';
import breakpoints from '@vars/breakpoints';

export let MainHeroIconComponent = styled.div`
  display: flex;
  align-items: center;

  @media (max-width: ${breakpoints.max.xl}) {
    flex-direction: column;
    align-items: center;
    text-align: center;
  }
`;
export let Icon = styled.div`
  width: 80px;
  height: 80px;
  border-radius: 50%;
  display: flex;
  flex-shrink: 0;
  align-items: center;
  justify-content: center;
  background-color: ${theme.iconPin.main};
  border: 1px solid ${theme.iconPin.border};
  margin-right: 10px;
  box-shadow: -5px 5px 30px -10px rgba(7, 30, 44, 0.17);

  @media (max-width: ${breakpoints.max.xl}) {
    width: 50px;
    height: 50px;
    margin: 0 0 10px;
  }
`;
export let Text = styled.div`
  font-weight: 400;
  font-size: 16px;

  @media (max-width: ${breakpoints.max.xl}) {
    font-size: 12px;
  }
  span {
    font-weight: 700;
    color: ${theme.iconPin.bold};
  }
`;

export let Image = styled.div``;

export let ImageWrapper = styled.div`
  width: 100%;
  height: 100%;
  max-width: ${({ imageWidth }) => imageWidth};
  max-height: ${({ imageHeight }) => imageHeight};

  svg {
    max-width: 100%;
    max-height: 100%;
    width: 100%;
    height: 100%;
  }
`;
