import styled from '@emotion/styled';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';

export let OldalPageWrapper = styled.div``;

export let ContentWrapper = styled.div`
  padding: 80px 0 120px;

  @media (max-width: ${breakpoints.max.md}) {
    padding: 60px 0 60px;
  }
`;

export let Title = styled.div`
  margin-bottom: 80px;
  font-weight: 700;
  font-size: 36px;
  color: ${colors.mineShaft};

  @media (max-width: ${breakpoints.max.md}) {
    font-size: 22px;
    margin-bottom: 20 px;
  }
`;

export let MainRow = styled.div``;

export let MainContent = styled.div`
  font-weight: 300;
  font-size: 14px;
  line-height: 22px;
`;
