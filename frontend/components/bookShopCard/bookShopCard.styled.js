import styled from '@emotion/styled';
import colors from '@vars/colors';
import theme from '@vars/theme';
import Icon from '@components/icon/icon';
import breakpoints from '@vars/breakpoints';

export let BookShopCardComponent = styled.div`
  background-color: ${colors.zircon};
  padding: 22px 30px;
  margin-top: 42px;
  margin-bottom: 10px;
  box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.07);
  border-radius: 10px;

  @media (max-width: ${breakpoints.max.lg}) {
    padding: 10px 20px;
    margin-top: 12px;
    background-color: ${colors.titanWhite};
  }
`;

export let IconWrapper = styled.div`
  width: 10px;
  margin: 0 auto;
  transform-origin: 50%;
  transition: transform 0.2s ease-in-out;
`;

export let InputIcon = styled.div`
  display: ${({ isVisible }) => (isVisible ? 'none' : 'block')};
  width: 16px;
  position: relative;
  top: 12px;
  transform: translateY(-50%);
`;

export let ListHeaderWrapper = styled.div`
  cursor: pointer;
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: ${({ isMinLG }) => (isMinLG && !isMinLG ? `20px` : 0)};
  border-bottom: ${({ border }) => border && `1px solid ${colors.mischka}`};

  ${IconWrapper} {
    transform: ${({ open }) => (open ? 'rotateZ(-90deg)' : 'rotateZ(90deg)')};
  }
`;

export let DetailsContainer = styled.div`
  display: ${({ open }) => (open ? 'block' : 'none')};
`;

export let TitleWrapper = styled.div`
  margin-bottom: 22px;
`;

export let Title = styled.div`
  font-size: 20px;
  font-weight: 700;
  flex: 1;
  margin-bottom: 22px;

  @media (max-width: ${breakpoints.max.lg}) {
    font-size: 14px;
    margin-bottom: 0;
  }
`;

export let StoreStatus = styled.div`
  font-size: 18px;
  font-weight: 600;
  flex: 1;

  @media (max-width: ${breakpoints.max.lg}) {
    font-size: 12px;
    margin-bottom: 0;
  }
`;
export let BookShopImageWrapper = styled.div`
  border-radius: 10px;
  margin-top: 20px;
  margin-bottom: 28px;
  div {
    vertical-align: top;
    border-radius: 10px;
    overflow: hidden;
  }
`;

export let ContactWrapper = styled.div`
  display: flex;
  margin-bottom: 17px;
`;

export let ContactIcon = styled(Icon)`
  height: 18px;
  width: 18px;
  margin-right: 22px;
`;

export let Contact = styled.div`
  font-size: 14px;
  font-weight: 300;

  @media (max-width: ${breakpoints.max.lg}) {
    font-size: 12px;
  }
`;

export let OpeningTimesContainer = styled.div`
  margin-top: 16px;
  border-top: 1px solid ${colors.mischka};
  display: flex;
  padding-top: 16px;
`;

export let OpeningTimesTitle = styled.div`
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 14px;

  @media (max-width: ${breakpoints.max.lg}) {
    font-size: 12px;
  }
`;

export let OpeningTimesIcon = styled.div``;

export let OpeningTimes = styled.div`
  width: 100%;
`;

export let OpeningTimesWrapper = styled.div`
  display: flex;
  justify-content: space-between;

  @media (max-width: ${breakpoints.max.lg}) {
    font-size: 12px;
  }
`;

export let Days = styled.div`
  font-size: 14px;
  font-weight: 300;

  @media (max-width: ${breakpoints.max.lg}) {
    font-size: 12px;
  }
`;

export let Hours = styled.div``;

export let Facebook = styled.a`
  color: ${theme.button.primary};
  text-decoration: underline;
`;
