import styled from '@emotion/styled';
import breakpoints from '@vars/breakpoints';

export let MobileFiltersWrapper = styled.div`
  display: flex;
  flex-direction: column;
  height: 100%;
`;

export let Title = styled.div`
  font-weight: 700;
  font-size: 20px;
  margin-bottom: 40px;

  @media (orientation: landscape) {
    margin-bottom: 10px;
  }
`;

export let Content = styled.div`
  margin-right: -15px;
  overflow: hidden;
  margin-bottom: 30px;
  flex: 1;
  overflow-y: auto;

  @media (orientation: landscape) {
    margin-bottom: 10px;
  }
`;

export let Actions = styled.div``;

export let ActionsWrapper = styled.div`
  margin: 0 auto;
  max-width: 300px;
  display: flex;
  justify-content: center;

  @media (orientation: landscape) {
    button {
      height: 35px;
    }
  }
`;

export let SubmitActionWrapper = styled.div`
  flex: 2;
  margin-right: 20px;
`;

export let ResetActionWrapper = styled.div`
  flex: 1;
`;

export let FilterBlockWrapper = styled.div`
  margin-bottom: 20px;
  border-bottom: 1px solid #d6d8e7;

  @media (max-width: ${breakpoints.max.xl}) {
    margin-bottom: 15px;
  }
`;

export let ContentWrapper = styled.div`
  padding-right: 15px;
`;
