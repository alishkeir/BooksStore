import styled from '@emotion/styled';
import breakpoints from '@vars/breakpoints';

export let EkonyvekPageWrapper = styled.div``;

export let ListerWrapper = styled.div`
  margin-bottom: 120px;

  @media (max-width: ${breakpoints.max.xl}) {
    margin-bottom: 60px;
  }
`;
