import styled from '@emotion/styled';
import theme from '@vars/theme';

export let MainHeroIconImageWrapper = styled.div`
  width: 100%;
  height: 100%;
  border-radius: 50%;
  display: flex;
  flex-shrink: 0;
  align-items: center;
  justify-content: center;
  border: 1px solid ${theme.iconPin.border};
  background-color: ${theme.iconPin.main};
  margin-right: 10px;
  box-shadow: -5px 5px 30px -10px rgba(7, 30, 44, 0.17);
`;

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

export let Image = styled.div``;
