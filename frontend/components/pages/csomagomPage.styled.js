import styled from '@emotion/styled';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';

export let CsomagomPageWrapper = styled.div``;

export let Title = styled.div`
  margin-bottom: 80px;
  font-weight: 700;
  font-size: 36px;
  color: ${colors.mineShaft};

  @media (max-width: ${breakpoints.max.md}) {
    font-size: 22px;
    margin-bottom: 60px;
  }
`;
export let InputRow = styled.div``;

export let InputCol = styled.div``;

export let ImageWrapper = styled.div`
  margin-bottom: 20px;
  text-align: center;
`;

export let InputWrapper = styled.div`
  margin-bottom: 15px;

  @media (max-width: ${breakpoints.max.md}) {
    margin-bottom: 25px;
  }
`;

export let InputDescription = styled.div`
  text-align: center;
  margin-bottom: 25px;
`;

export let InputActions = styled.div`
  text-align: center;
`;

export let ContentWrapper = styled.div`
  padding: 80px 0 250px;

  @media (max-width: ${breakpoints.max.md}) {
    padding: 60px 0 150px;
  }
`;
