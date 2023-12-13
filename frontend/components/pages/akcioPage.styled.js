import styled from '@emotion/styled';
import breakpoints from '@vars/breakpoints';

export let AkcioPageWrapper = styled.div``;

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

export let Banner = styled.div`
  display: flex;
  justify-content: center;
  margin: 70px 0 80px;
  border-radius: 10px;
  overflow: hidden;

  @media (max-width: ${breakpoints.max.xl}) {
    margin: 60px 0 40px;
  }

  > div {
    vertical-align: top;
  }
`;
