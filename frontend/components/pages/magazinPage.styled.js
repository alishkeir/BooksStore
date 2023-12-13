import styled from '@emotion/styled';
import breakpoints from '@vars/breakpoints';

export let MagazinPageWrapper = styled.div``;

export let Title = styled.div`
  margin-top: 80px;
  font-weight: 700;
  font-size: 36px;
  margin-bottom: 60px;

  @media (max-width: ${breakpoints.max.md}) {
    font-size: 22px;
    margin-top: 60px;
    margin-bottom: 30px;
  }
`;

export let Controls = styled.div`
  display: flex;
  margin-bottom: 50px;
  align-items: center;
`;

export let SearchControl = styled.div`
  flex: 1;
  display: flex;
  flex-direction: ${({ isMobile }) => (isMobile ? 'row' : 'column')};
  align-items: ${({ isMobile }) => (isMobile ? 'center' : '')};
`;

export let SearchControlLabel = styled.div`
  margin-right: 15px;
`;

export let SearchControlInput = styled.div``;

export let SortControl = styled.div`
  display: flex;
  align-items: center;
  margin-left: 15px;
`;

export let SortControlLabel = styled.div`
  margin-right: 15px;
`;

export let SortControlYear = styled.div`
  margin-right: 20px;
  width: 120px;
`;

export let SortControlMonth = styled.div`
  width: 210px;
`;

export let List = styled.div`
  margin-bottom: 20px;
`;

export let ListItem = styled.div`
  margin-bottom: 40px;
`;

export let Pagination = styled.div`
  margin-bottom: 120px;
  display: flex;
  justify-content: center;
`;

export let ModalHeader = styled.div`
  display: flex;
`;

export let ModalTitle = styled.div`
  font-weight: 700;
  font-size: 20px;
`;

export let ModaWrapper = styled.div`
  position: relative;
  height: 100%;
`;

export let FilterWrapper = styled.div`
  margin-top: 25px;
  margin-bottom: 25px;
`;

export let ModalFooter = styled.div`
  position: absolute;
  justify-content: space-between;
  display: flex;
  bottom: 0;
  width: 100%;
`;

export let FilterButtonWrapper = styled.div`
  margin-top: 22px;
  margin-left: 20px;
  width: 68px;
`;
