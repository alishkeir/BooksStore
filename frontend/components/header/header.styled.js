import styled from '@emotion/styled';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';

export let HeaderUserMobileMenuWrapper = styled.div`
  flex: 0;
  overflow: hidden;
  transition: flex 0.3s ease-in-out;
`;

export let HeaderMobileMenuWrapper = styled.div`
  flex: 0;
  overflow: hidden;
  transition: flex 0.3s ease-in-out;
`;

export let HeaderMobileSearchWrapper = styled.div`
  overflow: hidden;
  transition: flex 0.3s ease-in-out;
  pointer-events: none;
  display: flex;
  flex-direction: column;
  z-index: 100000;
  > * {
    pointer-events: all;
  }
`;

export let HeaderMobileSearchInput = styled.div`
  padding: 20px;
  background-color: white;
`;

export let HeaderMobileSearchResults = styled.div`
  flex: 1;
  overflow-y: auto;
`;

export let HeaderTopNav = styled.div`
  background-color: white;
  z-index: 100002;
  transform: translateY(0);
  box-shadow: 0px 1px 20px rgba(0, 0, 0, 0.06);
`;

export let HeaderSidebarWrapper = styled.div`
  flex: 1;
  overflow: hidden;
`;

export let HeaderWrapper = styled.div`
  position: fixed;
  top: 0;
  left: 0;
  right: 0;

  display: flex;
  flex-direction: column;
  transition: height 0.3s ease-in-out;
  pointer-events: none;
  height: ${({ mobileMenuOpen, searchMenuOpen }) => (mobileMenuOpen || searchMenuOpen ? '100%' : '0%')};
  height: 100%;
  width: 100%;
  z-index: 100000;
  transition: transform 0.3s ease-in-out;
  transform: ${({ menuBarHidden }) => menuBarHidden && 'translateY(-80px)'};

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

  ${HeaderSidebarWrapper} {
    flex: ${({ sidebarOpen }) => !sidebarOpen && '0'};
    z-index: 100000;
  }

  ${HeaderUserMobileMenuWrapper} {
    flex: ${({ userMenuOpen }) => (userMenuOpen ? '1' : '0')};
  }

  ${HeaderMobileMenuWrapper} {
    flex: ${({ mobileMenuOpen }) => (mobileMenuOpen ? '1' : '0')};
  }

  ${HeaderMobileSearchWrapper} {
    flex: ${({ searchMenuOpen }) => !searchMenuOpen && '0'};
  }
`;

export let Container = styled.div``;

export let Row = styled.div``;

export let Col = styled.div``;

export let LogoWrapper = styled.div`
  display: flex;
  align-items: center;
  margin-right: 50px;

  @media (max-width: ${breakpoints.max.xl}) {
    margin-right: 0;
  }
`;

export let MenuWrapper = styled.div`
  display: flex;
  align-items: center;
  flex: 1;
`;

export let SearchWrapper = styled.div`
  width: 375px;
  display: flex;
  align-items: center;
  position: relative;

  form {
    width: 100%;
  }
`;

export let SearchResultsWrapper = styled.div`
  position: absolute;
  bottom: 0;
  transform: translateY(100%);
  width: 100%;
  z-index: 10000;
  box-shadow: 0px 8px 13px rgba(0, 0, 0, 0.25);
  border-radius: 0px 0px 10px 10px;
  overflow: hidden;
`;

export let IconsWrapper = styled.div`
  display: flex;
  align-items: center;
`;

export let IconWrapper = styled.div`
  height: 100%;
  margin-left: 30px;
  display: flex;
  align-items: center;
  position: relative;

  > div:first-of-type {
    cursor: pointer;
  }

  @media (max-width: ${breakpoints.max.xl}) {
    margin-left: 20px;
  }
`;

export let IconHamburgerWrapper = styled(IconWrapper)`
  margin: -12px -12px -12px 20px;

  @media (max-width: ${breakpoints.max.xl}) {
    margin: -12px -12px -12px 13px;
  }
`;

export let MobileMenuWrapper = styled.div`
  display: flex;
  align-items: center;
`;

export let Logo = styled.a`
  width: 129px;

  @media (max-width: ${breakpoints.max.xl}) {
    width: 120px;
  }
`;

export let Ul = styled.ul`
  margin: 0;
  padding: 0;
`;

export let Li = styled.li`
  margin: 0;
  margin-right: 40px;
  padding: 0;
  list-style: none;
  display: inline-block;

  &:last-child {
    margin-right: 0;
  }
  a {
    font-weight: 600;
    font-size: 16px;
    color: ${colors.mineShaft};

    &:hover {
      text-decoration: none;
    }
  }
`;

export let TopMenuWrapper = styled.div`
  display: flex;
  height: 80px;

  @media (max-width: ${breakpoints.max.xl}) {
    height: 60px;
  }
`;

export let HeaderPromoWrapper = styled.div`
  z-index: 100001;
`;

export let PromoProp = styled.div``;

export let Spin = styled.div``;
