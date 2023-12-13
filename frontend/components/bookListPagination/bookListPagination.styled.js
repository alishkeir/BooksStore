import styled from '@emotion/styled';
import colors from '@vars/colors';
import settingsVars from "@vars/settingsVars";
import url from "@libs/url";

let theme = {
  progressLineColor: colors.monza,
  progressBarColor: colors.monzaLight,
};

let settings = settingsVars.get(url.getHost());

if (settings.key === 'OLCSOKONYVEK') {
  theme = {
    progressLineColor: colors.mineShaftDark,
    progressBarColor: colors.mischka,
  };
}

if (settings.key === 'NAGYKER') {
  theme = {
    progressLineColor: colors.dodgerBlueLight,
    progressBarColor: colors.mischka,
  };
}

export let BookListPaginationWrapper = styled.div`
  width: 100%;
  max-width: 330px;
  text-align: center;
`;

export let InfoText = styled.div`
  font-weight: normal;
  font-size: 16px;
  text-align: center;
  margin-bottom: 13px;
`;

export let ProgressBarLine = styled.div`
  position: absolute;
  left: 0;
  top: 0;
  height: 6px;
  background-color: ${theme.progressLineColor};
  transition: width 0.3s ease-in-out;
`;

export let ProgressBar = styled.div`
  height: 6px;
  background-color: ${theme.progressBarColor};
  border-radius: 3px;
  position: relative;
  overflow: hidden;
  width: 100%;

  ${ProgressBarLine} {
    width: ${({ progress }) => (progress ? `${progress}%` : '0')};
  }
`;

export let ProgressBarWrapper = styled.div`
  margin-bottom: 50px;
`;

export let Actions = styled.div`
  a {
    pointer-events: none;

    > * {
      pointer-events: all;
    }
  }
`;
