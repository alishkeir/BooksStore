import styled from '@emotion/styled';
import colors from '@vars/colors';
import Icon from '@components/icon/icon';

export let HeaderPromoWrapper = styled.div`
  height: 30px;
  background-color: ${({ bannerOpts }) => {
    return colors[bannerOpts.color];
  }};
  display: flex;
  align-items: center;
  justify-content: center;
`;

export let Content = styled.div`
  font-size: 14px;
  color: ${colors.mineShaftDark};

  b,
  strong {
    font-weight: 600;
  }
`;

export let IconWrapper = styled.div`
  margin-right: 12px;
`;

export let TruckIcon = styled(Icon)`
  width: 24px;
  margin-top: 2px;
`;
