import styled from '@emotion/styled';
import settingsVars from "@vars/settingsVars";
import url from "@libs/url";

let theme = {
  buttonBorderWidth: '1px',
};

let settings = settingsVars.get(url.getHost());

if (settings.key === 'OLCSOKONYVEK') {
  theme = {
    buttonBorderWidth: '2px',
  };
}

if (settings.key === 'NAGYKER') {
  theme = {
    buttonBorderWidth: '1px',
  };
}

export let ButtonWrapper = styled.button`
  border: none;
  padding: 0 15px;
  border-radius: 10px;
  border: ${theme.buttonBorderWidth} solid ${({ config }) => config.borderColor};
  background-color: ${({ config }) => config.backgroundColor};
  height: ${({ config }) => config.buttonHeight};
  width: ${({ config }) => (config.buttonWidth ? '100%' : 'auto')};
  max-width: ${({ config }) => config.buttonWidth};
  color: ${({ config }) => config.buttonColor};
  font-weight: 600;
  font-size: 16px;

  &:hover {
    background-color: ${({ config }) => config.backgroundColorHover};
  }
  &:focus {
    outline: none;
  }
`;

export let IconWrapper = styled.div`
  margin-right: ${({ config }) => config.iconMargin};
  display: inline-block;
`;

export let LoaderWrapper = styled.div`
  line-height: 1;
`;

export let Loader = styled.div``;
