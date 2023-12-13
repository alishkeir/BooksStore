import styled from '@emotion/styled';
import colors from '@vars/colors';

export let AlertBoxComponent = styled.div`
  margin-bottom: 15px;
  background-color: ${colors.monza};
  padding: 5px 10px;
  color: white;
  border-radius: 5px;
`;

export let ErrorWrapperUl = styled.div`
  margin: 0;
  padding: 0;
`;

export let ErrorWrapperLi = styled.div`
  list-style: none;

  &::before {
    content: '!';
    font-weight: 700;
    display: inline-block;
    margin-right: 10px;
  }
`;
