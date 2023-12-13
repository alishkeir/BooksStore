import styled from '@emotion/styled';
import theme from '@vars/theme';
import breakpoints from '@vars/breakpoints';

export let ScrollToTopComponent = styled.div`
  z-index: 1;
  cursor: pointer;
  position: sticky;
  top: ${({ windowHeight }) => windowHeight}px;
  z-index: 9999;
  transition: top 0.2s ease-in-out;
`;

export let Background = styled.div`
  width: 60px;
  height: 60px;
  margin: -60px 0 0 auto;
  transform: translate(-70px, -40px);
  background-color: ${theme.button.tertiary};
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 2;
  position: relative;
  opacity: ${({ visible }) => (visible ? 1 : 0)};
  transition: opacity 0.3s ease-in-out;

  &:hover {
    background-color: ${theme.button.tertiaryHover};
  }

  @media (max-width: ${breakpoints.max.xl}) {
    width: 50px;
    height: 50px;
    transform: translate(-20px, 0px);
  }
`;

export let IconWrapper = styled.div`
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%) rotateZ(-90deg);
  z-index: 3;
  font-size: 0;
`;

export let ContentWrapper = styled.div``;
