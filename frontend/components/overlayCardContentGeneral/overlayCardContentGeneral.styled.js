import styled from '@emotion/styled';
import breakpoints from '@vars/breakpoints';
import colors from '@vars/colors';

export let OverlayCardContentGeneralComponent = styled.div`
  padding: 70px 0 20px;

  @media (max-width: ${breakpoints.max.sm}) {
    padding: 60px 0 0;
  }
`;

export let Title = styled.div`
  font-weight: 600;
  font-size: 24px;
  text-align: center;
  color: ${colors.mineShaftDark};

  @media (max-width: ${breakpoints.max.sm}) {
    font-size: 20px;
  }
`;

export let Text = styled.div`
  margin-top: 30px;
`;

export let Actions = styled.div`
  display: flex;
  margin-top: 60px;

  @media (max-width: ${breakpoints.max.sm}) {
    flex-direction: column;
    margin-top: 40px;
  }
`;

export let ButtonWrapper = styled.div`
  padding: 0 10px;
  width: 50%;
  margin-bottom: 10px;

  @media (max-width: ${breakpoints.max.sm}) {
    padding: 0;
    width: 100%;
  }
`;
