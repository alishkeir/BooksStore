import styled from '@emotion/styled';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';

export let SideModalLoginWrapper = styled.div`
  padding-top: 10px;

  @media (max-width: ${breakpoints.max.md}) {
    padding-top: 15px;
  }
`;

export let InputEmailWrapper = styled.div`
  margin-bottom: 25px;
`;

export let InputPasswordWrapper = styled.div`
  margin-bottom: 20px;
`;

export let ButtonWrapper = styled.div``;

export let Form = styled.div``;

export let Separator = styled.div`
  text-align: center;
  position: relative;
  margin: 25px 0;
`;

export let SeparatorText = styled.div`
  display: inline-block;
  background-color: white;
  position: relative;
  padding: 0 20px;
  text-transform: uppercase;
  font-size: 12px;
  font-weight: 400;
  color: ${colors.boulder};
`;

export let SeparatorLine = styled.div`
  border-bottom: 1px solid ${colors.mercury};
  position: absolute;
  left: 0;
  top: 50%;
  width: 100%;
`;

export let Social = styled.div`
  margin-bottom: 40px;
`;

export let TopActions = styled.div`
  font-weight: 700;
  padding-bottom: 25px;
`;
export let BotActions = styled.div`
  margin-top: 40px;
  padding-bottom: 20px;
`;

export let SocialButtonWrapper = styled.div``;

let LoginButton = styled.div`
  width: 100%;
  height: 50px;
  box-shadow: 0px 0px 10px rgba(214, 216, 231, 0.5);
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  font-weight: 600;
  font-size: 16px;
`;

export let LoginButtonGoogle = styled(LoginButton)`
  margin-bottom: 20px;
  color: ${colors.google};
`;

export let SocialButtonText = styled.div``;

export let SocialButtonIcon = styled.div`
  margin-right: 13px;
`;

export let ActionItem = styled.a`
  margin-bottom: 20px;
  font-weight: 600;
  font-size: 14px;
  color: ${colors.monza};
  text-decoration: underline;
  cursor: pointer;
`;
