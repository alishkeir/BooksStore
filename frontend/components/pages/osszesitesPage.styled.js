import styled from '@emotion/styled';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';

export let OsszesitesPageWrapper = styled.div``;

export let CommentWrapper = styled.div`
  margin-bottom: 50px;
`;
export let PhoneWrapper = styled.div`
  margin-bottom: 25px;
`;

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
`;

export let ButtonWrapper = styled.div`
  width: 100%;
  max-width: 350px;
  margin-bottom: 20px;
`;

export let Overlay = styled.div``;

export let OverlayCard = styled.div``;

export let UserSelectControl = styled.div`
  margin-bottom: 90px;

  @media (max-width: ${breakpoints.max.md}) {
    margin-bottom: 40px;
  }
`;

export let Tabs = styled.div``;

export let TabWrapper = styled.div`
  margin-bottom: 10px;
  position: relative;
`;

export let TabWrapperCard = styled(TabWrapper)``;

export let TabWrapperCardIcons = styled.div`
  display: flex;
  position: absolute;
  right: 60px;
  top: 50%;
  transform: translateY(-50%);
  user-select: none;
  pointer-events: none;
`;

export let TabWrapperCardIcon = styled.div`
  > div {
    vertical-align: top;
  }
`;

export let TabWrapperTransfer = styled(TabWrapper)``;

export let TabWrapperDelivery = styled(TabWrapper)``;

export let TabDeliveryCost = styled.div`
  position: absolute;
  right: 20px;
  top: 50%;
  transform: translateY(-50%);
  color: ${colors.monza};
`;

export let Summary = styled.div`
  margin-bottom: 20px;
  border-top: 1px solid ${colors.mischka};
`;

export let SummaryBlock = styled.div`
  margin-bottom: 15px;
`;

export let SummaryBlockTitle = styled.div`
  font-weight: 600;
  font-size: 14px;
  line-height: 22px;
`;

export let SummaryBlockLine = styled.div`
  font-weight: 300;
  font-size: 14px;
  line-height: 22px;
`;

export let SummaryBlockContainer = styled.div`
  display: flex;
`;

export let SummarBlockCol = styled.div`
  display: flex;
  flex-direction: column;
  justify-content: center;
  flex: ${({ flex1 }) => flex1 && '1'};
`;

export let Total = styled.div`
  margin-bottom: 40px;
  display: flex;
  font-weight: 700;
  font-size: 18px;
  color: ${colors.monza};

  @media (max-width: ${breakpoints.max.md}) {
    margin-bottom: 40px;
    font-size: 16px;
  }
`;

export let TotalTitle = styled.div`
  flex: 1;
`;

export let TotalValue = styled.div``;

export let ButtonError = styled.div`
  color: ${colors.monza};
  font-size: 14px;
  margin-bottom: 10px;
  text-align: center;
`;

export let SummaryBlockLineAuthor = styled.div`
  display: inline-block;

  &::after {
    content: ',';
    margin-right: 5px;
  }

  &:last-of-type {
    &::after {
      content: none;
    }
  }
`;

export let FormBorgun = styled.div`
  display: none;
`;
