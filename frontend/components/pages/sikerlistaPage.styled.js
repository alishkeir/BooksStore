import styled from '@emotion/styled';
import breakpoints from '@vars/breakpoints';

export let SikerlistaPageWrapper = styled.div``;

export let Booklist = styled.div`
  margin-bottom: 120px;

  @media (max-width: ${breakpoints.max.md}) {
    margin-bottom: 45px;
  }
`;

export let List = styled.div`
  min-height: 100vh;
`;
