import styled from '@emotion/styled';
import theme from '@vars/theme';
import breakpoints from '@vars/breakpoints';

export let ProfilSzemelyesAdataimPageComponent = styled.div``;

export let PageContent = styled.div`
  margin-bottom: 120px;

  @media (max-width: ${breakpoints.max.md}) {
    margin-bottom: 0;
    padding: 40px 0 60px;
  }
`;

export let ProfileNavigatorWrapper = styled.div``;

export let DataWrapper = styled.div``;

export let Form = styled.div`
  margin-bottom: 40px;
`;

export let InputWrapper = styled.div`
  margin-bottom: 25px;
`;

export let InputSurnameWrapper = styled(InputWrapper)``;

export let InputFirstnameWrapper = styled(InputWrapper)``;

export let InputEmailWrapper = styled(InputWrapper)``;

export let InputPhoneWrapper = styled(InputWrapper)`
  margin-bottom: 0;
`;

export let Actions = styled.div`
  display: flex;
  align-items: center;

  @media (max-width: ${breakpoints.max.md}) {
    flex-direction: column;
    justify-content: center;
  }
`;

export let PassChange = styled.div`
  font-weight: 600;
  font-size: 16px;
  color: ${theme.button.primary};
  cursor: pointer;
  text-decoration: underline;

  @media (max-width: ${breakpoints.max.md}) {
    width: 100%;
    margin-bottom: 35px;
  }
`;

export let ButtonWrapper = styled.div`
  margin-left: auto;
  width: 150px;

  @media (max-width: ${breakpoints.max.md}) {
    width: 100%;
    margin-left: initial;
  }
`;
