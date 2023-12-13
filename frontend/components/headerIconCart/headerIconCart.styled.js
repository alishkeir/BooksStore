import styled from '@emotion/styled';
import colors from '@vars/colors';

export let HeaderIconCartComponent = styled.div`
  position: relative;

  > a {
    cursor: ${({ disabled }) => (disabled ? 'pointer' : 'not-allowed')};
  }
`;

export let CartNumber = styled.div`
  position: absolute;
  top: 0;
  right: -7px;
  z-index: 1;
  background-color: ${colors.monza};
  width: 20px;
  height: 20px;
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 50%;
  font-weight: 600;
  font-size: 12px;
  color: white;
  line-height: 1;
`;
