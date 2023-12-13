import styled from '@emotion/styled';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';

export let SideModalWrapper = styled.div`
  height: 100%;
  z-index: 0;
`;

export let Content = styled.div`
  height: 100%;
  background-color: white;
  width: 450px;
  margin: 0 0 0 auto;
  display: flex;
  flex-direction: column;
  transform: ${(props) => (props.out ? 'translateX(0)' : 'translateX(100%)')};
  transition: transform 0.3s ease-in-out;
  z-index: 99999;
  position: relative;

  @media (max-width: ${breakpoints.max.md}) {
    width: 100%;
  }
`;

export let SideModalContentWrapper = styled.div`
  flex: 1;
  padding: 0 50px 0;
  overflow-y: auto;

  @media (max-width: ${breakpoints.max.md}) {
    padding: 0 15px 0;
  }
`;

export let Title = styled.div`
  font-weight: 700;
  font-size: 22px;
`;

export let Close = styled.div`
  font-size: 25px;
  font-weight: 300;
  line-height: 15px;
  display: inline-block;
  text-align: right;

  @media (max-width: ${breakpoints.max.md}) {
    order: 1;
    margin-left: auto;
    font-size: 45px;
    line-height: 25px;
  }
`;

export let CloseIcon = styled.div`
  display: inline-block;
  cursor: pointer;
`;

export let SideModalInfoWrapper = styled.div`
  background-color: ${colors.eggWhite};
  padding: 15px 50px;

  @media (max-width: ${breakpoints.max.md}) {
    padding: 10px 15px;
  }
`;

export let Header = styled.div`
  padding: 25px 25px 0px 50px;
  display: flex;
  flex-direction: column;

  @media (max-width: ${breakpoints.max.md}) {
    padding: 20px 15px 0;
    flex-direction: row;
    align-items: center;
  }
`;

export let LoginContent = styled.div``;

export let RegistrationContent = styled.div``;

export let ForgottenPassContent = styled.div``;

export let NewPassContent = styled.div``;

export let FeedbackContent = styled.div``;
