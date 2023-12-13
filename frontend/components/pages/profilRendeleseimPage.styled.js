import styled from '@emotion/styled';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';

export let ProfilRendeleseimPageComponent = styled.div``;

export let PageContent = styled.div`
  margin-bottom: 120px;

  @media (max-width: ${breakpoints.max.md}) {
    margin-bottom: 0;
    padding: 40px 0 60px;
  }
`;

export let ProfileNavigatorWrapper = styled.div``;

export let TableWrapper = styled.div``;

export let Table = styled.table`
  width: 100%;
  border-spacing: 0;
`;

export let Tbody = styled.tbody``;

export let Tr = styled.tr`
  margin: 0;
  padding: 0;
`;

export let Th = styled.th`
  padding: 15px 20px;

  &:last-of-type {
    width: 30%;

    @media (max-width: ${breakpoints.max.lg}) {
      width: initial;
    }
  }
`;

export let Td = styled.td`
  padding: 15px 20px;
`;

export let Thead = styled.thead`
  ${Th} {
    &:first-of-type {
      padding-left: 0;

      @media (max-width: ${breakpoints.max.lg}) {
        padding-left: 15px;
      }
    }
  }
`;

export let SeparatorTbody = styled(Tbody)`
  ${Td} {
    padding: 0;
    height: 10px;
  }
`;

export let PaginantionWrapper = styled.div`
  display: flex;
  justify-content: center;
  margin: 20px 0;
`;

export let InfoText = styled.div`
  margin: 10px 0;
  background-color: ${colors.amber};
  padding: 5px 10px;
  color: black;
  border-radius: 5px;

  a {
    text-decoration: underline;
  }
`;
