import styled from '@emotion/styled';
import colors from '@vars/colors';
import theme from '@vars/theme';
import breakpoints from '@vars/breakpoints';

export let KosarPageWrapper = styled.div``;

export let CartItemWrapper = styled.div`
  padding: 54px 30px 60px 12px;

  @media (max-width: ${breakpoints.max.xl}) {
    padding: 24px 20px 30px 12px;
  }
`;

export let RecommendationWrapper = styled.div`
  padding: 54px 62px 60px;
  border-radius: 10px;
  background-color: ${colors.titanWhite};

  @media (max-width: ${breakpoints.max.xl}) {
    padding: 20px;
  }
`;

export let PageContainer = styled.div``;

export let PageWarning = styled.div`
  background-color: ${colors.eggWhite};
  padding: 15px 20px;
  margin: -54px 0 30px;
  font-size: 14px;
  line-height: 20px;
  font-weight: 400;
  color: #212121;

  @media (max-width: ${breakpoints.max.xl}) {
    margin: -24px 0 30px;
  }
`;

export let Title = styled.div`
  font-weight: 700;
  font-size: 24px;
  margin-top: ${({ mtd }) => (mtd ? `${mtd}px` : '0')};
  margin-bottom: ${({ mbd }) => (mbd ? `${mbd}px` : '0')};
  color: ${({ color }) => `${colors[color]}`};

  @media (max-width: ${breakpoints.max.xl}) {
    font-size: 20px;
    margin-top: ${({ mtm }) => (mtm ? `${mtm}px` : '0')};
    margin-bottom: ${({ mbm }) => (mbm ? `${mbm}px` : '0')};
  }
`;

export let SideTitle = styled(Title)`
  color: ${theme.button.primary};
`;

export let SideLoader = styled(Title)`
  color: ${theme.button.primary};
  font-size: 18px;
`;

export let CartLoader = styled.div`
  margin-top: 15px;
  font-size: 18px;
`;

export let CartWrapper = styled.div`
  padding: 36px 0 38px;
  border-bottom: 1px solid ${colors.mischka};
  display: flex;
  justify-content: space-between;
`;

export let EBookNotificationWrapper = styled.div`
  padding-top: 30px;
  padding-bottom: 30px;
  border-bottom: 1px solid ${colors.mischka};
`;

export let EBookNotificationContenrWrapper = styled.div`
  border-radius: 10px;
  padding: 26px 30px 24px 30px;
  background-color: ${colors.eggWhite};
`;

export let EBookNotificationTitle = styled.div`
  font-size: 20px;
  font-weight: 700;

  @media (max-width: ${breakpoints.max.xl}) {
    font-size: 16px;
  }
`;

export let EBookNotificationBody = styled.div`
  font-size: 16px;
  font-weight: 300;

  @media (max-width: ${breakpoints.max.xl}) {
    font-size: 12px;
  }
`;

export let FinalPriceTotal = styled.div`
  color: ${theme.button.primary};
`;

export let FinalPrice = styled.div`
  text-align: right;
  font-size: 12px;
  font-weight: 400;
  text-decoration: line-through;
  color: ${colors.mischka};

  @media (max-width: ${breakpoints.max.xl}) {
    font-size: 14px;
  }
`;

export let FinalPriceWrapper = styled.div`
  font-size: 24px;
  font-weight: 700;
  color: ${colors.monza};

  @media (max-width: ${breakpoints.max.xl}) {
    font-size: 20px;
  }
`;

export let ButtonWrapper = styled.div`
  margin-top: 30px;
`;

export let BestsellersMobileWrapper = styled.div`
  background-color: ${colors.titanWhite};
  padding-bottom: ${({ isOpen }) => (isOpen ? '28px' : 0)};
  margin-bottom: ${({ isOpen }) => (isOpen ? 0 : '28px')};
`;

export let DetailsReveal = styled.div`
  position: absolute;
  z-index: 1;
  bottom: 0;
  height: 74px;
  width: 100%;
  background: linear-gradient(180deg, rgba(255, 255, 255, 0) 0%, #ffffff 55%);
`;

export let DetailsRevealWrapper = styled.div`
  overflow: hidden;
  position: relative;
  height: ${({ isOpen }) => (isOpen ? 'auto' : '160px')};
`;

export let TextRevealButtonWrapper = styled.div`
  display: flex;
  justify-content: center;
  padding-top: ${({ isOpen }) => (isOpen ? 0 : '20px')};
  background-color: white;
`;

export let Overlay = styled.div``;

export let OverlayCard = styled.div``;
