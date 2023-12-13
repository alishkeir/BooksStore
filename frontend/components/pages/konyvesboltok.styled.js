import styled from '@emotion/styled';
import breakpoints from '@vars/breakpoints';
import colors from '@vars/colors';

export let KonyvesboltokPageWrapper = styled.div``;
export let Title = styled.div`
  font-weight: 700;
  font-size: 20px;
  flex: 1;
  margin-bottom: 22px;

  @media (max-width: ${breakpoints.max.xl}) {
    font-size: 18px;
    margin-bottom: 12px;
  }
`;

export let Notification = styled.div`
  padding: 26px 30px 24px;
  background-color: ${colors.eggWhite};
  border-radius: 10px;
  margin-bottom: 56px;

  @media (max-width: ${breakpoints.max.xl}) {
    margin-bottom: 22px;
    padding: 14px 20px 14px 20px;
  }
`;

export let NotificationTitle = styled.div`
  font-size: 20px;
  font-weight: 700;
  margin-bottom: 5px;

  @media (max-width: ${breakpoints.max.xl}) {
    font-size: 16px;
  }
`;

export let NotificationMessage = styled.div`
  font-size: 16px;
  @media (max-width: ${breakpoints.max.xl}) {
    font-size: 12px;
  }
`;

export let BookshopsTitle = styled.div`
  font-size: 18px;
  font-weight: 700;
  margin-top: 28px;
`;

export let ShopsWrapper = styled.div`
  margin-bottom: 120px;

  @media (max-width: ${breakpoints.max.xl}) {
    font-size: 16px;
    margin-bottom: 120px;
  }
`;

export let Map = styled.div`
  border-radius: 10px;
  margin-right: 30px;
  width: 100%;
  height: 960px;

  @media (max-width: ${breakpoints.max.xl}) {
    height: 880px;
  }

  @media (max-width: ${breakpoints.max.lg}) {
    height: 100%;
  }
`;

export let MapWrapper = styled.div`
  @media (max-width: ${breakpoints.max.lg}) {
    height: 50vh;
  }
`;
