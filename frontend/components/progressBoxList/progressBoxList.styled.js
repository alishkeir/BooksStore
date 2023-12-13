import styled from '@emotion/styled';
import breakpoints from '@vars/breakpoints';

export let BoxGroup = styled.div`
  display: flex;
  flex: 1;
  margin-bottom: 40px;
  display: flex;

  @media (max-width: ${breakpoints.max.md}) {
    flex-direction: column;
    margin-bottom: 0;
  }
`;

export let BoxWrapper = styled.div`
  flex: 1;
`;

export let Separator = styled.div`
  padding: 0 15px;
  display: flex;
  justify-content: center;
  align-items: center;

  @media (max-width: ${breakpoints.max.md}) {
    padding: 20px 0 40px;

    > div {
      transform: rotateZ(90deg);
    }
  }
`;

export let IconWrapper = styled.div`
  font-size: 0;
`;

export let ProgressBoxListComponent = styled.div`
  display: flex;
  flex-wrap: wrap;

  @media (max-width: ${breakpoints.max.md}) {
    flex-direction: column;
  }

  ${BoxGroup} {
    &:last-of-type {
      ${Separator} {
        display: none;
      }
    }
  }
`;
