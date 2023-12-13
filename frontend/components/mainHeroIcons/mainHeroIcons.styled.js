import styled from '@emotion/styled';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';

export let MainHeroIconsWrapper = styled.div`
  display: flex;
  align-items: center;

  @media (max-width: ${breakpoints.max.xl}) {
    align-items: flex-start;
  }
`;
export let Separator = styled.div`
  width: 1px;
  height: 45px;
  opacity: 0.2;
  background-color: ${colors.mineShaft};
`;
export let MainHeroIconWrapper = styled.div`
  flex: 1;
  display: flex;
  justify-content: ${({ justify }) => (justify ? justify : 'center')};

  @media (max-width: ${breakpoints.max.xl}) {
    justify-content: center;
  }
`;
