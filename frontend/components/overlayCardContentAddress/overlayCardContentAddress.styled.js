import styled from '@emotion/styled';
import theme from '@vars/theme';
import breakpoints from '@vars/breakpoints';

export let Title = styled.div`
  text-align: center;
  font-weight: 700;
  font-size: 36px;
  margin-bottom: 80px;

  @media (max-width: ${breakpoints.max.sm}) {
    text-align: left;
    font-size: 22px;
    margin-bottom: 35px;
  }
`;

export let Top = styled.div``;

export let Content = styled.div`
  position: relative;
  flex: 1;
`;

export let Bottom = styled.div`
  padding-top: 50px;
`;

export let Question = styled.div`
  font-weight: bold;
  font-size: 18px;
  vertical-align: middle;
  margin-bottom: 20px;

  &:before {
    vertical-align: middle;
    content: '';
    display: inline-block;
    width: 3px;
    height: 23px;
    background-color: ${theme.main.primary};
    margin-right: 15px;
  }
`;

export let Tabs = styled.div`
  margin-bottom: 30px;
`;

export let Form = styled.div``;

export let TabWrapper = styled.div`
  margin-bottom: 10px;
`;

export let InputWrapper = styled.div`
  margin-bottom: 25px;
  display: flex;
`;

export let InputCol = styled.div``;

export let ButtonWrapper = styled.div`
  margin-bottom: 20px;

  &:last-of-type {
    margin-bottom: 0;
  }
`;

export let OverlayCardContentAddressComponent = styled.div`
  display: flex;
  flex-direction: column;
  height: 100%;

  ${({ display }) => {
    if (display === 'checkout') {
      return `
        padding: 0;
      `;
    }
  }}

  @media (max-width: ${breakpoints.max.sm}) {
    ${({ display }) => {
      if (display === 'checkout') {
        return `
        padding: 0;
      `;
      } else {
        return `
        padding: 20px 0;
      `;
      }
    }}
  }
`;

export let FormContent = styled.div``;
