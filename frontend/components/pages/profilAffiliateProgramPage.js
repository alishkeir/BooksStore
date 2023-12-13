import styled from '@emotion/styled';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';

export let ProfilAffiliateProgramPageComponent = styled.div``;

export let PageContent = styled.div`
  margin-bottom: 120px;

  @media (max-width: ${breakpoints.max.md}) {
    margin-bottom: 0;
    padding: 40px 0 60px;
  }
`;

export let ProfileNavigatorWrapper = styled.div``;

export let DataWrapper = styled.div``;

export let BillingInfo = styled.div`
    padding-bottom: 16px;
    border-bottom: 1px solid ${colors.mischka};
    margin-bottom: 30px
`;
export let BillingInfoHeader = styled.div`
  display: flex;
  width:100%;
  justify-content: space-between;
`;
export let BillingInfoTitle = styled.div`
    font-weight: 600;
    font-size: 20px;
    line-height: 28px;
    margin-bottom: 16px;

    @media (max-width: ${breakpoints.max.lg}) {
        font-size: 18px;
    }
`;
export let BillingInfoData = styled.div`
    margin-bottom: 5px;
`;
export let AffiliateCodeSection = styled.div`
    padding-bottom: 16px;
    border-bottom: 1px solid ${colors.mischka};
    margin-bottom: 30px
`;
export let AffiliateCodeSectionTitle = styled.div`
    font-weight: 600;
    font-size: 20px;
    line-height: 28px;
    margin-bottom: 16px;

    @media (max-width: ${breakpoints.max.lg}) {
        font-size: 18px;
    }
`;
export let AffiliateCodeWrapper = styled.div`
    display: flex;
    margin-bottom: 30px;
`;
export let AffiliateCode = styled.div`
    display: block;
    padding: 5px 25px;
    border-radius: 8px;
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    border: 1px red solid;
    color: red;
    text-align: center;
    font-weight: 600;
    font-size: 20px;
`;
export let UrlGeneratorTitle = styled.div`
    font-weight: 600;
    font-size: 20px;
    line-height: 28px;
    margin-bottom: 16px;

    @media (max-width: ${breakpoints.max.lg}) {
        font-size: 18px;
    }
`;
export let UrlGeneratorInputWrapper = styled.div`
    margin-bottom: 16px;
    label {
        font-weight: 600;
        font-size: 12px;
        color:grey;
    }
    input {
        color: grey;
    }
`;
export let GenerateButtonWrapper = styled.div`
    margin-bottom: 30px;
    display: flex;
    button {
        margin-inline-start: auto;
    }
`;
export let GeneratedUrlInput = styled.div`
    margin-bottom: 16px;
    display: flex;
    align-items: end;
    gap: 10px;
    label {
        font-weight: 600;
        font-size: 12px;
        color:red;
    }
    input {
        border: 1px solid red;
    }
`;
export let BalanceSection = styled.div`
    padding-bottom: 16px;
    border-bottom: 1px solid ${colors.mischka};
    margin-bottom: 30px
`;
export let BalanceSectionTitle = styled.div`
    font-weight: 600;
    font-size: 20px;
    line-height: 28px;
    margin-bottom: 16px;

    @media (max-width: ${breakpoints.max.lg}) {
        font-size: 18px;
    }
`;
export let BalanceWrapper = styled.div`
    display: flex;
    margin-bottom: 30px;
`;

export let Balance = styled.div`
    display: flex;
    gap: 5px;
    padding: 5px 25px;
    border-radius: 8px;
    border: 1px red solid;
    text-align: center;
    font-weight: 600;
    font-size: 20px;
`;
export let BalancePretext = styled.div`
   color:grey;
`;
export let BalanceAmount = styled.div`
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    color: red;
`;
export let RedeemInfo = styled.div`
    margin-bottom: 16px
`;
export let RedeemSection = styled.div`
    padding-bottom: 16px;
    margin-bottom: 30px
`;
export let RedeemSectionTitle = styled.div`
    font-weight: 600;
    font-size: 20px;
    line-height: 28px;
    margin-bottom: 16px;

    @media (max-width: ${breakpoints.max.lg}) {
        font-size: 18px;
    }
`;
export let RedeemInputWrapper = styled.div`
    margin-bottom: 16px;
    display: flex;
    align-items: end;
    gap: 10px;
`;

export let Form = styled.div`
    display: flex;
    flex-flow: wrap;
    margin-top: 40px;
`;
export let InputWrapper = styled.div`
  margin-bottom: 25px;
  width: 50%;
  padding: 0 10px;
`;
export let Actions = styled.div`
    display: flex;
    align-items: center;

    @media (max-width: ${breakpoints.max.md}) {
    flex-direction: column;
    justify-content: center;
    }
    margin-inline-start: auto;
    gap: 10px;
`;
export let Highlight = styled.span`
    font-weight: 600;
    color: red
`;
export let RedeemError = styled.div`
    font-weight: 600;
    color: red;
    background: rgba(255, 0, 0, 0.1);
    border-radius: 5px;
    padding: 5px 10px;
    display: flex;
`;
export let RedeemErrorClose = styled.div`
    margin-inline-start: auto;
    &:hover {
      cursor:pointer;
    }
`;
export let RedeemSuccess = styled.div`
    font-weight: 600;
    color: green;
    background: rgba(0, 255, 0, 0.1);
    border-radius: 5px;
    padding: 5px 10px;
    display: flex;
`;
export let RedeemSuccessClose = styled.div`
    margin-inline-start: auto;
    &:hover {
      cursor:pointer;
    }
`;
export let TableSection = styled.div``;
export let TableWrapper = styled.div``;

export let Table = styled.table`
  width: 100%;
  border-spacing: 0;
  border-color: gray;
`;

export let Tbody = styled.tbody`
@media (max-width: ${breakpoints.max.lg}) {
font-size: 12px;
}
position: relative;
line-height: 1;
&::after {
    content: '';
    display: block;
    position: absolute;
    left: -1px;
    top: -1px;
    width: calc(100% + 2px);
    height: calc(100% + 2px);
    z-index: 0;
    border: 1px solid ${colors.mischka};
    border-radius: 10px;
    pointer-events: none;
  }
`;

export let Td = styled.td`
  padding: 20px;

  @media (max-width: ${breakpoints.max.lg}) {
    padding: 20px 10px;
  }
`;
export let Tr = styled.tr`
    margin: 0;
    padding: 0;
  box-sizing: border-box;
  cursor: pointer;

  ${Td} {
    position: relative;

    span {
      position: relative;
      z-index: 1;
    }

    &::after {
      content: '';
      display: block;
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background: ${colors.titanWhite};
      z-index: 0;
      pointer-events: none;
    }

    &:first-of-type {
      white-space: nowrap;

      &::after {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 0;
      }
    }

    &:last-of-type {
      text-align: left;
      padding-right: 40px;

      &::after {
        border-top-right-radius: 10px;
        border-bottom-right-radius: 0;
      }
    }
  }
`;

export let Th = styled.th`
  padding: 15px 20px;

  &:last-of-type {
    width: 30%;

    @media (max-width: ${breakpoints.max.lg}) {
      width: initial;
    }
  }
`;


export let Thead = styled.thead`
  ${Th} {
    &:first-of-type {
      padding-left: 0;

      @media (max-width: ${breakpoints.max.lg}) {
        padding-left: 15px;
      }
    }
  }
`;
export let SeparatorTd = styled.td`
  padding: 15px 20px;
`;
export let SeparatorTbody = styled.tbody`
  ${SeparatorTd} {
    padding: 0;
    height: 10px;
  }
`;
export let Link = styled.a`
    color: rgba(0, 0, 255, 1);
  &:hover {
    color: rgba(0, 0, 255, 0.7);
  }
`;

export let ModalNotification = styled.div`
  padding: 15px 60px 15px;
`;

export let NotificationText = styled.div`
  font-size: 16px;
  font-weight: 300;
  text-align: center;
  color: rgb(19 178 65 / 85%);
  @media (max-width: ${breakpoints.max.md}) {
    font-size: 14px;
    font-weight: 300;
  }
`;
export let AdditionalInfo = styled.div`
  margin-top: 30px;
  font-size: 16px;
  & b {
    font-weight: 600;
  }
  @media (max-width: ${breakpoints.max.md}) {
    font-size: 14px;
    font-weight: 300;
  }
`;


