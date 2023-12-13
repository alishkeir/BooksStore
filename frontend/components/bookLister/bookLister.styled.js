import styled from '@emotion/styled';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';

export let BookListerWrapper = styled.div``;

export let FiltersTitle = styled.div`
  font-weight: 700;
  font-size: 24px;
  margin-bottom: 40px;
`;

export let FilterBlockWrapper = styled.div`
  margin-bottom: 20px;
  border-bottom: 1px solid #d6d8e7;

  @media (max-width: ${breakpoints.max.xl}) {
    margin-bottom: 15px;
  }

  &:last-child {
    border-bottom: none;
  }
`;

export let Title = styled.div`
  margin-top: 80px;
  font-weight: 700;
  font-size: 36px;
`;

export let Lister = styled.div``;

export let Actions = styled.div`
  display: flex;
  margin-bottom: 30px;
  align-items: center;
`;

export let SortByWrapper = styled.div`
  margin-left: auto;
`;

export let ActionDropdownWrapper = styled.div`
  width: 230px;

  @media (max-width: ${breakpoints.max.xl}) {
    width: 210px;
  }
`;

export let Content = styled.div`
  display: flex;
`;

export let Filters = styled.div`
  max-width: 250px;
  margin-right: 30px;
  width: 200px;

  @media (max-width: ${breakpoints.max.md}) {
    display: none;
  }
`;

export let Books = styled.div`
  flex: 1;
`;

export let ActionWrapper = styled.div`
  display: flex;
  align-items: center;
`;

export let MobileFiltersIconWrapper = styled.div`
  width: 110px;
  margin-left: 20px;
`;

export let ActionWrapperLabel = styled.div`
  margin-right: 15px;
`;

export let Row = styled.div``;
export let Column = styled.div`
  margin-bottom: 50px;
`;

export let PaginantionWrapper = styled.div`
  display: flex;
  justify-content: center;
`;

export let FiltersBlocks = styled.div``;

export let Nohit = styled.div`
  display: flex;
  flex-direction: column;
  align-items: center;
`;

export let NohitImage = styled.div`
  width: 370px;
  margin-bottom: 60px;

  @media (max-width: ${breakpoints.max.md}) {
    width: 270px;
    margin-bottom: 30px;
  }

  svg {
    width: 100%;
    height: auto;
    max-width: 100%;
  }
`;

export let NohitText = styled.div`
  font-weight: 300;
  font-size: 16px;
  color: ${colors.silverChaliceDark};

  @media (max-width: ${breakpoints.max.md}) {
    font-size: 14px;
  }
`;
