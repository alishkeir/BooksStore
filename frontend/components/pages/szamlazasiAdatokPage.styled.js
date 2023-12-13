import styled from '@emotion/styled';
import breakpoints from '@vars/breakpoints';

export let SzamlazasiAdatokPageWrapper = styled.div``;

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
