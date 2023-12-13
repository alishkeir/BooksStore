import styled from '@emotion/styled';
import breakpoints from '@vars/breakpoints';
import colors from '@vars/colors';

export let SzallitasiAdatokPageWrapper = styled.div``;

export let NavStepsRow = styled.div`
  padding: 80px 0 100px;

  @media (max-width: ${breakpoints.max.md}) {
    padding: 60px 0 50px;
  }
`;

export let NavStepsCol = styled.div``;

export let Title = styled.div`
  font-weight: 700;
  font-size: 36px;
  text-align: center;
  margin-bottom: 100px;

  @media (max-width: ${breakpoints.max.md}) {
    font-size: 22px;
    margin-bottom: 40px;
    text-align: left;
  }
`;

export let FormRow = styled.div`
  margin-bottom: 120px;

  @media (max-width: ${breakpoints.max.md}) {
    margin-bottom: 60px;
  }
`;

export let FormCol = styled.div``;

export let FormActions = styled.div`
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-top: 70px;

  @media (max-width: ${breakpoints.max.md}) {
    margin-top: 30px;
  }
`;

export let ButtonWrapper = styled.div`
  width: 100%;
  max-width: 350px;
  margin-bottom: 20px;
`;

export let UserFormControls = styled.div`
  display: flex;
  margin-bottom: 25px;

  @media (max-width: ${breakpoints.max.md}) {
    margin-bottom: 15px;
  }
`;

export let UserDropdownWrapper = styled.div`
  flex: 1;
  margin-right: 30px;

  @media (max-width: ${breakpoints.max.md}) {
    margin-right: 15px;
  }

  &:last-of-type {
    margin-right: 0;
  }
`;

export let UserActionWrapper = styled.div`
  width: 100px;
`;

export let UserAddressList = styled.div`
  margin-bottom: 70px;

  @media (max-width: ${breakpoints.max.md}) {
    margin-bottom: 30px;
  }
`;

export let UserAddressItem = styled.div``;

export let UserAddressItemLine = styled.div`
  font-weight: ${({ strong }) => strong && 700};
`;

export let Overlay = styled.div``;

export let OverlayCard = styled.div``;

export let UserSelectControl = styled.div`
  margin-bottom: 50px;
`;

export let Tabs = styled.div``;

export let TabWrapper = styled.div`
  position: relative;
  margin-bottom: 10px;
`;

export let TabDeliveryCost = styled.div`
  position: absolute;
  right: 20px;
  top: 50%;
  transform: translateY(-50%);
  color: ${colors.monza};
`;

export let TabSubDeliveryCost = styled.div`
  color: ${colors.monza};
`;

export let StoreMapWrapper = styled.div`
  margin-bottom: 30px;
`;

export let StoreMap = styled.div`
  border-radius: 10px;
  width: 100%;
  height: 300px;

  @media (max-width: ${breakpoints.max.lg}) {
    height: 50vh;
  }
`;

export let StoreInfoWrapper = styled.div`
  margin-bottom: 70px;
`;

export let MapHead = styled.div``;

export let BoxTitleInfo = styled.div`
  margin-bottom: 20px;
`;

export let BoxMapWrapper = styled.div`
  margin-bottom: 30px;
`;

export let BoxMap = styled.div`
  border-radius: 10px;
  width: 100%;
  height: 300px;

  @media (max-width: ${breakpoints.max.lg}) {
    height: 50vh;
  }
`;

export let BoxInfoWrapper = styled.div``;
