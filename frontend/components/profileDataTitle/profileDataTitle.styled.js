import styled from '@emotion/styled';
import breakpoints from '@vars/breakpoints';

export let ProfileDataTitleComponent = styled.div`
  font-weight: 700;
  font-size: 24px;
  line-height: 1;
  margin-bottom: 35px;

  @media (max-width: ${breakpoints.max.md}) {
    font-size: 22px;
  }
`;
