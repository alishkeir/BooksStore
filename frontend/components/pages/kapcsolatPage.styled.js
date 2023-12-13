import styled from '@emotion/styled';
import Icon from '@components/icon/icon';
import breakpoints from '@vars/breakpoints';

export let KapcsolatPageComponent = styled.div``;
export let InputWrapper = styled.div`
  max-width: 600px;
  width: 100%;
  margin-bottom: ${({ marginBottom }) => `${marginBottom}px`};
  margin-top: ${({ marginTop }) => (marginTop && marginTop ? `${marginTop}px` : '')};

  @media (max-width: ${breakpoints.max.sm}) {
    margin-bottom: ${({ marginBottomMobile }) => (marginBottomMobile && marginBottomMobile ? `${marginBottomMobile}px` : '')};
    margin-top: ${({ marginTopMobile }) => (marginTopMobile && marginTopMobile ? `${marginTopMobile}px` : '')};
  }
`;

export let PageContent = styled.div`
  display: flex;
  flex-direction: column;
  align-items: center;
`;
export let PageContentWrapper = styled.div``;

export let PhoneWrapper = styled.div`
  border-right: 1px solid #d6d8e7;

  @media (max-width: ${breakpoints.max.sm}) {
    border-right: none;
  }
`;

export let ContactsWrapper = styled.div`
  display: flex;
  justify-content: space-between;
  margin-bottom: 120px;

  @media (max-width: ${breakpoints.max.sm}) {
    flex-direction: column;
  }
`;

export let PhoneIconWrapper = styled.div`
  margin-right: 20px;
`;

export let LinkIcon = styled(Icon)`
  height: 24px;
  width: 24px;
`;

export let LinkIconWrapper = styled.div`
  display: flex;
  margin-top: 25px;
  margin-right: 30px;
`;

export let PhoneNumber = styled.div`
  font-size: 16px;
  font-style: normal;
  font-weight: 400;
`;

export let CompanyData = styled.div``;

export let AddressWrapper = styled.div`
  margin-top: 18px;
`;

export let CompanyAddress = styled.div`
  font-size: 16px;
`;
