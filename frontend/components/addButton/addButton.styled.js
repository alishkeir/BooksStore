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

export let ImageCheckWrapper = styled.div`
  @keyframes dash {
    to {
      stroke-dashoffset: 0;
    }
  }

  path {
    stroke-dasharray: 100;
    stroke-dashoffset: 100;
    animation: dash 1s linear forwards;
    animation-delay: 0.1s;
  }
`;

export let ImageHeartWrapper = styled.div`
  path {
    fill: ${({ config }) => config.checkIconColor};
  }
`;

export let TextWrapper = styled.div``;

export let AferTextWrapper = styled.div`
  margin-left: 8px;
`;

export let Layer = styled.div`
  position: absolute;
  width: 100%;
  height: 100%;
  left: 0;
  top: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 10px;
  z-index: 1;
  font-weight: 600;
  line-height: 1.2;
  border: ${theme.buttonBorderWidth} solid transparent;
`;

export let CheckLayer = styled(Layer)`
  z-index: 4;
  svg {
    width: 22px;
  }
`;

export let ExLayer = styled(Layer)`
  z-index: 5;
  svg {
    width: 18px;
    height: 18px;
  }
`;

export let TextLayer = styled(Layer)`
  z-index: 3;

  ${ImageCheckWrapper} {
    svg {
      width: 22px;
    }
  }
`;

export let TextImage = styled.div`
  margin-right: 10px;
`;

export let TextImagePlus = styled.div`
  svg {
    width: 14px;
  }
`;

export let TextImageHeart = styled.div`
  svg {
    width: 21px;
  }
`;

export let TextIcon = styled.div`
  svg {
    width: 22px;
  }
`;

export let AddButtonWrapper = styled.div`
  height: ${({ config }) => config.buttonHeight};
  width: ${({ config }) => config.buttonWidth};
  font-size: ${({ config }) => config.fontSize};
  cursor: pointer;
  position: relative;

  ${CheckLayer} {
    color: ${({ config }) => config.checkTextColor};
    background-color: ${({ config }) => config.checkBackgroundColor};
    border-color: ${({ config }) => config.checkBorderColor};

    &:hover {
      background-color: ${({ config }) => config.checkBackgroundColorHover};
      border-color: ${({ config }) => config.checkBorderColorHover};
    }

    path {
      stroke: ${({ config }) => config.checkIconColor};
    }
  }

  ${TextLayer} {
    color: ${({ config }) => config.textTextColor};
    background-color: ${({ config }) => config.textBackgroundColor};
    border-color: ${({ config }) => config.textBorderColor};

    &:hover {
      background-color: ${({ config }) => config.textBackgroundColorHover};
      border-color: ${({ config }) => config.textBorderColorHover};
    }

    path {
      stroke: ${({ config }) => config.textIconColor};
    }
  }

  ${ExLayer} {
    background-color: ${({ config }) => config.exBackgroundColor};
    border-color: ${({ config }) => config.exBorderColor};

    &:hover {
      background-color: ${({ config }) => config.exBackgroundColorHover};
      border-color: ${({ config }) => config.exBorderColorHover};
    }

    path {
      stroke: ${({ config }) => config.exIconColor};
    }
  }
`;
