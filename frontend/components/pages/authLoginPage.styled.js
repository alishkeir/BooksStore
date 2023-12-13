import styled from '@emotion/styled';
import breakpoints from '@vars/breakpoints';

export let AuthLoginPageComponent = styled.div`
  width: 100%;
  min-height: 100vh;
  background-color: #fff;
`;

export let Content = styled.div`
  max-width: 350px;
  margin: 0 auto;
  padding: 50px 0 0;
`;

export let LogoWrapper = styled.div``;

export let Logo = styled.div`
  text-align: center;
  margin-bottom: 30px;
`;

export let ImageLogoAlomgyar = styled.div``;

export let ImageLogoOlcsokonyvek = styled.div``;

export let ImageLogoNagyker = styled.div``;

export let FormWrapper = styled.div``;

export let Title = styled.div`
  font-weight: 700;
  font-size: 22px;
  text-align: center;
`;

export let SidebarWrapper = styled.div`
  flex: 1;
  overflow: hidden;
`;

export let Container = styled.div`
  position: fixed;
  top: 0;
  left: 0;
  right: 0;

  display: flex;
  flex-direction: column;
  transition: height 0.3s ease-in-out;
  pointer-events: none;
  height: 100%;
  width: 100%;
  z-index: 100000;
  transition: transform 0.3s ease-in-out;

  @media (max-width: ${breakpoints.max.xl}) {
    transform: ${({ menuBarHidden }) => menuBarHidden && 'translateY(-60px)'};
    box-shadow: none;
    position: fixed;
    top: 0;
    right: 0;
    left: 0;
    z-index: 100000;
  }

  > * {
    pointer-events: initial;
  }

  ${SidebarWrapper} {
    flex: ${({ sidebarOpen }) => !sidebarOpen && '0'};
    z-index: 100000;
  }
`;
