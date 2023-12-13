import styled from '@emotion/styled';
import breakpoints from '@vars/breakpoints';

export let UjdonsagokPageWrapper = styled.div``;

export let Booklist = styled.div`
  margin-bottom: 60px;

  @media (max-width: ${breakpoints.max.md}) {
    margin-bottom: 30px;
  }
`;

export let List = styled.div`
  min-height: 100vh;
`;

export let PaginantionWrapper = styled.div`
  display: flex;
  justify-content: center;
  margin-bottom: 60px;
`;
