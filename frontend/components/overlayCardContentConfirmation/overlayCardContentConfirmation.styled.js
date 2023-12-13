import styled from '@emotion/styled';
import breakpoints from '@vars/breakpoints';
import colors from '@vars/colors';

export let OverlayCardContentConfirmationComponent = styled.div`
  padding: 100px 0 20px;

  @media (max-width: ${breakpoints.max.sm}) {
    padding: 60px 0 0;
  }
`;

export let Title = styled.div`
  font-weight: 600;
  font-size: 24px;
  text-align: center;
  margin-bottom: 90px;
  color: ${colors.mineShaftDark};

  @media (max-width: ${breakpoints.max.sm}) {
    font-size: 20px;
    margin-bottom: 50px;
  }
`;

export let Actions = styled.div`
  display: flex;

  @media (max-width: ${breakpoints.max.sm}) {
    flex-direction: column;
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
