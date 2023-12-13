import styled from '@emotion/styled';
import colors from '@vars/colors';

export let MobileMenuHeadItemComponent = styled.div`
  border-bottom: 1px solid #d6d8e7;
  font-weight: 600;
  font-size: 16px;
  display: flex;
  align-items: center;
  min-height: 50px;
  cursor: pointer;
  color: ${colors.tundora};
`;

export let Text = styled.div``;

export let IconWrapper = styled.div`
  transform: rotateZ(180deg);
  margin-right: 20px;
`;
