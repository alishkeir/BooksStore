import styled from '@emotion/styled';
import breakpoints from '@vars/breakpoints';

export let KonyvekPageWrapper = styled.div``;

export let FiltersTitle = styled.div`
  font-weight: 700;
  font-size: 24px;
  margin-bottom: 40px;
`;

export let ListerWrapper = styled.div`
  margin-bottom: 120px;

  @media (max-width: ${breakpoints.max.xl}) {
    margin-bottom: 60px;
  }
`;
