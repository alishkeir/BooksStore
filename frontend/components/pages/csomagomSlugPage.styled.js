import styled from '@emotion/styled';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';

export let CsomagomSlugPageWrapper = styled.div``;

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

export let InputRow = styled.div`
  margin-bottom: 70px;
  text-align: center;

  @media (max-width: ${breakpoints.max.md}) {
    margin-bottom: 50px;
  }
`;

export let InputCol = styled.div``;

export let ImageWrapper = styled.div`
  margin-bottom: 20px;
`;

export let InputNumber = styled.div`
  font-weight: 700;
  font-size: 24px;
  margin-bottom: 15px;

  @media (max-width: ${breakpoints.max.md}) {
    font-size: 20px;
  }
`;

export let InputDescription = styled.div``;

export let Progress = styled.div``;

export let InfoBox = styled.div`
  background-color: ${colors.eggWhite};
  padding: 20px;
  border-radius: 10px;
  color: black;
`;

export let ContentWrapper = styled.div`
  padding: 80px 0 120px;

  @media (max-width: ${breakpoints.max.md}) {
    padding: 60px 0 60px;
  }
`;
