import styled from '@emotion/styled';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';
import Icon from '@components/icon/icon';
import settingsVars from "@vars/settingsVars";
import url from "@libs/url";

let theme = {
  swiper: {
    scrollBarDrag: colors.monza,
  },
};

let settings = settingsVars.get(url.getHost());

if (settings.key === 'OLCSOKONYVEK') {
  theme = {
    swiper: {
      scrollBarDrag: colors.mineShaftDark,
    },
  };
}

if (settings.key === 'NAGYKER') {
  theme = {
    swiper: {
      scrollBarDrag: colors.dodgerBlueLight,
    },
  };
}

export let BookScrollListWrapper = styled.div``;

export let Title = styled.div`
  font-weight: 600;
  font-size: 20px;

  @media (max-width: ${breakpoints.max.xl}) {
    font-size: 18px;
  }
`;

export let Header = styled.div`
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid ${colors.mischka};
  margin: 0 0 23px;
`;

export let LinkWrapper = styled.div`
  display: flex;
  align-items: center;
  a {
    font-weight: 600;
    font-size: 16px;
    line-height: 1;
    color: ${colors.mineShaft};

    @media (max-width: ${breakpoints.max.xl}) {
      font-size: 12px;
    }
  }
`;

export let LinkIcon = styled(Icon)`
  vertical-align: middle;
  position: relative;
  bottom: 1px;

  @media (max-width: ${breakpoints.max.xl}) {
    width: 6px;
  }
`;

export let LinkIconWrapper = styled.div`
  margin-left: 10px;
`;

export let Lister = styled.div`
  .swiper-container {
    padding-bottom: 15px;
  }

  .swiper-scrollbar {
    height: 6px;
    border-radius: 3px;
    bottom: 0;
  }
  .swiper-scrollbar-drag {
    background: ${theme.swiper.scrollBarDrag};
    height: 6px;
    border-radius: 3px;
  }
`;

export let ListHeaderWrappers = styled.div`
  margin: 0 0 23px;
`;
