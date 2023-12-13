import styled from '@emotion/styled';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';

export let FooterComponent = styled.div`
  background-color: ${colors.mineShaftDark};
  color: white;
  position: relative;
  z-index: 100;

  @media (max-width: ${breakpoints.max.xl}) {
    text-align: center;
  }

  a {
    color: white;
  }
`;

export let Col = styled.div``;

export let Top = styled.div`
  padding: 35px 0;
  border-bottom: 1px solid ${colors.mineShaft};

  @media (max-width: ${breakpoints.max.xl}) {
    padding: 50px 0 30px;
  }
`;

export let Bottom = styled.div`
  padding: 20px 0;
`;

export let TopCenter = styled.div`
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  margin-bottom: -50px;

  @media (max-width: ${breakpoints.max.md}) {
    flex-direction: column;
  }

  ${Col} {
    padding: 0 70px;
    margin-bottom: 50px;

    @media (max-width: ${breakpoints.max.xxl}) {
      padding: 0 40px;
    }

    @media (max-width: ${breakpoints.max.xl}) {
      padding: 0 15px;
    }

    @media (max-width: ${breakpoints.max.lg}) {
      width: 50%;
    }

    @media (max-width: ${breakpoints.max.md}) {
      width: auto;
    }
  }
`;

export let BottomCenter = styled.div`
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  align-items: center;
  margin-bottom: -20px;

  @media (max-width: ${breakpoints.max.xl}) {
    flex-direction: column;
  }

  ${Col} {
    padding: 0 50px;
    margin-bottom: 20px;

    @media (max-width: ${breakpoints.max.xxl}) {
      padding: 0 15px;
    }

    @media (max-width: ${breakpoints.max.xl}) {
      &:nth-of-type(1) {
        order: 3;
      }
      &:nth-of-type(2) {
        order: 2;
      }
      &:nth-of-type(3) {
        order: 1;
      }
    }
  }
`;

export let ColTitle = styled.div`
  font-weight: 600;
  font-size: 20px;
  line-height: 1;
  margin-bottom: 30px;
`;

export let ContactLine = styled.div`
  display: flex;
  align-items: center;
  margin-bottom: 30px;

  @media (max-width: ${breakpoints.max.xl}) {
    justify-content: center;
  }
`;

export let ContactLinkButton = styled.div`
  width: 200px;
  height: 40px;
  border: 1px solid white;
  border-radius: 10px;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 0 20px;

  @media (max-width: ${breakpoints.max.md}) {
    width: auto;
  }
`;

export let ColContent = styled.div`
  font-weight: 300;
  font-size: 16px;

  ${ContactLine} {
    &:last-child {
      margin-bottom: 0;
    }
  }
`;

export let ContectIcon = styled.div`
  margin-right: 25px;
`;

export let ContectText = styled.div``;

export let TextLine = styled.div`
  margin-bottom: 10px;

  &:last-child {
    margin-bottom: 0;
  }
`;

export let CardList = styled.div`
  display: flex;

  > div {
    margin: 0 5px;
  }
`;

export let SocialList = styled.div`
  display: flex;
  margin: 0 -20px;

  > a {
    margin: 0 20px;

    @media (max-width: ${breakpoints.max.sm}) {
      margin: 0 10px;
    }
  }
`;

export let BottomText = styled.div`
  a {
    font-weight: 600;
    font-size: 14px;
    color: white;
  }
`;
