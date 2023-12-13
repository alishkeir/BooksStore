import styled from '@emotion/styled';

export let BookWrapper = styled.div``;

export let Row = styled.div`
  display: flex;
  flex-wrap: wrap;
`;

export let BookTableWrapper = styled.div`
  ${BookWrapper} {
    margin: ${({ spaceBetween }) => `0 ${spaceBetween / 2}px 40px`};
    width: ${({ elemWidth, spaceBetween }) => `calc( ${elemWidth}% - ${spaceBetween}px)`};
  }

  ${Row} {
    margin-left: ${({ spaceBetween }) => `-${spaceBetween / 2}px`};
    margin-right: ${({ spaceBetween }) => `-${spaceBetween / 2}px`};
    margin-bottom: -40px;
  }
`;

export let Container = styled.div`
  overflow: hidden;
`;
