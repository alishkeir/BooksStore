import styled from '@emotion/styled';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';

export let MainCategoriesItemComponent = styled.a`
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  margin-bottom: 8px;
  background-color: ${colors.athensGray};
  border-radius: 10px;
  width: 100%;
  cursor: pointer;

  @media (max-width: ${breakpoints.max.sm}) {
    width: 100%;
    padding: 10px 20px;
  }
`;

export let ItemTitle = styled.div`
  font-weight: 600;
  font-size: 16px;

  @media (max-width: ${breakpoints.max.lg}) {
    font-size: 14px;
  }
`;

export let ItemIcon = styled.div``;
