import styled from '@emotion/styled';
import Icon from '@components/icon/icon';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';

export let ListHeaderWrapper = styled.div`
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: ${({ border }) => border && `1px solid ${colors.mischka}`};
`;

export let Title = styled.div`
  font-weight: 700;
  font-size: 20px;
  flex: 1;

  @media (max-width: ${breakpoints.max.xl}) {
    font-size: 18px;
  }
`;

export let LinkWrapper = styled.div`
  display: flex;
  align-items: center;

  a {
    font-weight: 600;
    font-size: 16px;
    line-height: 1;
    color: ${colors.mineShaft};

    @media (max-width: ${breakpoints.max.xl}) {
      font-size: 12px;
    }
  }
`;

export let LinkIcon = styled(Icon)`
  vertical-align: middle;
  position: relative;
  bottom: 1px;

  @media (max-width: ${breakpoints.max.xl}) {
    width: 6px;
  }
`;

export let LinkIconWrapper = styled.div`
  margin-left: 10px;
`;
