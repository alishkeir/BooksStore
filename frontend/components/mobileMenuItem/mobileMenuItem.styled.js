import styled from '@emotion/styled';
import colors from '@vars/colors';

export let MobileMenuItemComponent = styled.div`
  border-bottom: 1px solid #d6d8e7;
  font-weight: normal;
  font-size: 16px;
  cursor: pointer;
`;

export let MobileMenuLinkItem = styled.a`
  display: flex;
  align-items: center;
  justify-content: space-between;
  min-height: 50px;
  color: ${colors.tundora};

  &:hover {
    color: ${colors.tundora};
  }
`;

export let MobileMenuSubItem = styled.div`
  display: flex;
  align-items: center;
  justify-content: space-between;
  min-height: 50px;
`;

export let Text = styled.div``;
