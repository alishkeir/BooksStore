import styled from '@emotion/styled';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';

export let ProfilSzallitasiCimeimComponent = styled.div`
  position: relative;
`;

export let PageContent = styled.div`
  margin-bottom: 120px;

  @media (max-width: ${breakpoints.max.md}) {
    margin-bottom: 0;
    padding: 40px 0 60px;
  }
`;

export let ProfileNavigatorWrapper = styled.div``;

export let ProfileData = styled.div``;

export let List = styled.div`
  > div {
    padding-bottom: 20px;
    border-bottom: 1px solid ${colors.mischka};
    margin-bottom: 20px;

    &:last-of-type {
      border-bottom: none;
    }
  }
`;

export let Actions = styled.div``;

export let ButtonWrapper = styled.div`
  width: 180px;

  @media (max-width: ${breakpoints.max.md}) {
    width: 100%;
  }
`;
