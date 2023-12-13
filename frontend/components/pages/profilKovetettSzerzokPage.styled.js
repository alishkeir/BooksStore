import styled from '@emotion/styled';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';

export let ProfilKovetettSzerzokPageComponent = styled.div``;

export let PageContent = styled.div`
  margin-bottom: 120px;

  @media (max-width: ${breakpoints.max.md}) {
    margin-bottom: 0;
    padding: 40px 0 60px;
  }
`;

export let AutoSubscription = styled.div`
  display: flex;
  align-items: center;
  margin-bottom: 40px;
`;

export let AutoSubscriptionText = styled.div`
  flex: 1;
  font-weight: 700;
  font-size: 18px;
  line-height: 26px;

  @media (max-width: ${breakpoints.max.lg}) {
    font-size: 16px;
  }
`;

export let AutoSubscriptionSwitch = styled.div`
  margin-left: 20px;
`;

export let ProfileNavigatorWrapper = styled.div``;

export let ProfileData = styled.div``;

export let List = styled.div`
  > div {
    padding-bottom: 20px;
    border-bottom: 1px solid ${colors.mischka};
    margin-bottom: 20px;

    &:last-of-type {
      border-bottom: none;
    }
  }
`;

export let PaginantionWrapper = styled.div`
  display: flex;
  justify-content: center;
  margin-top: 20px;
`;
