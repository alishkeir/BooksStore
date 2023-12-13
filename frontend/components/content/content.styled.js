import styled from '@emotion/styled';
import breakpoints from '@vars/breakpoints';

export let ContentWrapper = styled.div`
  display: flex;
  flex-direction: column;
  position: relative;
  padding-top: 80px;
  background-color: white;

  @media (max-width: ${breakpoints.max.xl}) {
    margin-bottom: -35px;
  }
  @media (max-width: ${breakpoints.max.xl}) {
    padding-top: 60px;
  }
`;

export let ContentWrapperEnd = styled.div`
  order: 999;
  @media (max-width: ${breakpoints.max.xl}) {
    padding-bottom: 35px;
  }
`;

export let SideModal = styled.div``;
