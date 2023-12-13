import styled from '@emotion/styled';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';

export let BookCategoryNavWrapper = styled.div``;

export let Title = styled.div`
  margin-bottom: 20px;
`;

export let TitleText = styled.div`
  display: inline-block;
  vertical-align: middle;
  line-height: 1;
  font-weight: 700;
  font-size: 18px;
  margin-left: 10px;
  color: ${colors.mineShaft};
  position: relative;
  top: 2px;

  @media (max-width: ${breakpoints.max.md}) {
    font-size: 16px;
  }
`;

export let List = styled.div``;

export let Category = styled.div`
  font-weight: 600;
  font-size: 14px;
  margin-bottom: 10px;
  color: ${colors.mineShaft};
`;

export let SubCategoryList = styled.div`
  margin: -5px;
`;

export let SubCategoryItem = styled.div`
  display: inline-flex;
  align-items: center;
  margin: 5px;
  padding: 0 15px;
  background-color: ${colors.titanWhite};
  border-radius: 25px;
  height: 30px;
  color: black;
`;

export let ListItem = styled.div`
  margin-bottom: 20px;

  &:last-child {
    margin-bottom: 0;
  }
`;
