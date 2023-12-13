import styled from '@emotion/styled';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';
import settingsVars from "@vars/settingsVars";
import url from "@libs/url";

let theme = {
  action: colors.monza,
};

let settings = settingsVars.get(url.getHost());

if (settings.key === 'OLCSOKONYVEK') {
  theme = {
    action: colors.amber,
  };
}

if (settings.key === 'NAGYKER') {
  theme = {
    action: colors.dodgerBlueLight,
  };
}

export let CommentListItemComponent = styled.div`
  padding: 20px 50px;
  background-color: white;
  box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.05);
  border-radius: 10px;
  margin-bottom: 20px;

  @media (max-width: ${breakpoints.max.md}) {
    padding: 20px;
  }
`;

export let User = styled.div`
  font-weight: 700;
  font-size: 16px;
  margin-bottom: 10px;
  display: flex;
  align-items: center;

  @media (max-width: ${breakpoints.max.md}) {
    font-size: 14px;
  }
`;

export let UserPhoto = styled.div``;

export let UserName = styled.div``;

export let UserNameId = styled.div`
  display: inline;
`;

export let UserNameOwn = styled.div`
  display: inline;
  font-weight: 400;
  margin-left: 5px;
`;

export let Text = styled.div`
  font-weight: 300;
  font-size: 14px;
  line-height: 22px;
  padding-bottom: 15px;
  margin-bottom: 15px;
  border-bottom: 1px solid ${colors.athensGrayDark};
`;

export let Footer = styled.div`
  display: flex;
  align-items: center;

  @media (max-width: ${breakpoints.max.md}) {
    flex-direction: column;
    align-items: initial;
  }
`;

export let Meta = styled.div`
  color: ${colors.ghost};
  flex: 1;

  @media (max-width: ${breakpoints.max.md}) {
    margin-bottom: 10px;
  }
`;

export let Actions = styled.div`
  display: flex;
`;

export let Action = styled.div`
  font-weight: 600;
  text-decoration-line: underline;
  color: ${theme.action};
  padding: 0 20px;
  border-right: 1px solid ${colors.athensGrayDark};
  cursor: pointer;

  &:first-of-type {
    padding-left: 0;
  }
  &:last-of-type {
    padding-right: 0;
    border-right: none;
  }
`;

export let TextEdit = styled.div``;

export let InputWrapper = styled.div`
  margin-bottom: 20px;
`;

export let ActionWrapper = styled.div`
  text-align: right;
  margin-bottom: 20px;
`;

export let ButtonWrapper = styled.div`
  width: 100%;
  max-width: 230px;
  display: inline-block;

  @media (max-width: ${breakpoints.max.md}) {
    max-width: 100%;
  }
`;
