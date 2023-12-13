import styled from '@emotion/styled';

export let MobileMenuComponent = styled.div`
  height: 100%;
  width: 100%;
  background-color: white;
  position: relative;
  overflow: hidden;
`;

export let MenuContainer = styled.div`
  height: calc(100% - 30px);
  width: 100%;
  position: absolute;
  top: 15px;
  right: 0;
  bottom: 0;
  left: 0;
  overflow-y: auto;
  background-color: white;
  z-index: 100;
`;

export let SubMenuContainer = styled(MenuContainer)`
  z-index: 101;
  // background-color: coral;
  transition: transform 0.3s ease-in-out;
  transform: ${({ open }) => (open ? 'translateX(0%)' : 'translateX(100%)')};
`;

export let MenuList = styled.div`
  > div {
    &:last-child {
      border-bottom: none;
    }
  }
`;
