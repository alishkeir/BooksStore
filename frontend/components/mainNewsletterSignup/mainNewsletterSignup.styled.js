import styled from '@emotion/styled';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';

export let MainNewsletterSignupComponent = styled.div`
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
`;

export let InputTextWrapper = styled.div`
  margin-bottom: 30px;
  width: 100%;
  max-width: 600px;

  @media (max-width: ${breakpoints.max.xl}) {
    margin-bottom: 20px;
  }
`;

export let InputCheckboxWrapper = styled.div`
  margin-bottom: 30px;
  width: 100%;
  max-width: 600px;
  padding: 0 15px;
  font-weight: 300;
  font-size: 12px;
  line-height: 20px;

  @media (max-width: ${breakpoints.max.xl}) {
    padding: 0;
    margin-bottom: 20px;
  }
`;

export let ButtonWrapper = styled.div``;

export let TitleWrapper = styled.div`
  margin-bottom: 30px;
  font-weight: 600;
  font-size: 24px;
  line-height: 28px;
  color: ${colors.mineShaft};

  @media (max-width: ${breakpoints.max.xl}) {
    text-align: center;
    font-weight: 600;
    font-size: 20px;
  }
`;
