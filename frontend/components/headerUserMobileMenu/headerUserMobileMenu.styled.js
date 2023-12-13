import styled from '@emotion/styled';
import colors from '@vars/colors';

export let HeaderUserMobileMenuWrapper = styled.div`
  height: 100%;
  width: 100%;
  background-color: white;
  position: relative;
  overflow: hidden;
`;

export let MobileMenuContainer = styled.div`
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

export let MenuList = styled.div`
  > div {
    &:last-child {
      border-bottom: none;
    }
  }
`;

export let MenuHead = styled.div`
  display: flex;
  align-items: center;
  padding-bottom: 20px;
  border-bottom: 1px solid ${colors.mischka};
`;

export let MenuHeadText = styled.div`
  flex: 1;
  font-weight: 600;
  font-size: 18px;
  color: #000000;
`;

export let MenuHeadEx = styled.div`
  position: relative;
  right: -3px;
  cursor: pointer;
`;

export let MenuAction = styled.div``;

export let MenuActionItem = styled.div`
  font-weight: 600;
  font-size: 16px;
  color: ${colors.monza};
  border-top: 1px solid ${colors.mischka};
  padding-top: 20px;
  cursor: pointer;
`;
