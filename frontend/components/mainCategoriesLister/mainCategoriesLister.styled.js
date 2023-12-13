import styled from '@emotion/styled';
import breakpoints from '@vars/breakpoints';

export let MainCategoriesListerComponent = styled.div``;

export let Header = styled.div`
  margin-bottom: 25px;

  @media (max-width: ${breakpoints.max.lg}) {
    margin-bottom: 15px;
  }
`;

export let HeaderTitle = styled.div`
  font-weight: 600;
  font-size: 20px;
  line-height: 28px;

  @media (max-width: ${breakpoints.max.lg}) {
    font-size: 18px;
  }
`;

export let Content = styled.div`
  display: flex;
  flex-wrap: wrap;
  margin: 0 calc(var(--bs-gutter-y) * -1);
`;

export let MainCategoriesItemWrapper = styled.div`
  padding: 0 calc(var(--bs-gutter-x) / 2);
  width: 33.3333333%;

  @media (max-width: ${breakpoints.max.lg}) {
    width: 50%;
  }

  @media (max-width: ${breakpoints.max.sm}) {
    width: 100%;
  }
`;
