import styled from '@emotion/styled';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';
import settingsVars from "@vars/settingsVars";
import url from "@libs/url";

let theme = {
  swiper: {
    bullet: colors.cherub,
    bulletActive: colors.monza,
  },
};

let settings = settingsVars.get(url.getHost());

if (settings.key === 'OLCSOKONYVEK') {
  theme = {
    swiper: {
      bullet: colors.mischka,
      bulletActive: colors.mineShaftDark,
    },
  };
}

if (settings.key === 'NAGYKER') {
  theme = {
    swiper: {
      bullet: colors.mischka,
      bulletActive: colors.dodgerBlueLight,
    },
  };
}

export let MainHeroComponent = styled.div``;

export let Container = styled.div``;

export let Row = styled.div``;

export let Col = styled.div``;

export let SwiperWrapper = styled.div`
  .swiper-container {
    height: 100%;
    overflow-x: hidden;
    overflow-y: initial;

    @media (max-width: ${breakpoints.max.xl}) {
      padding-bottom: 30px;
    }
  }

  .swiper-pagination {
    text-align: right;
    padding: 0 25px;

    @media (max-width: ${breakpoints.max.xl}) {
      bottom: 0;
      text-align: center;
    }
  }

  .swiper-slide {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.15);

    > div {
      vertical-align: top;
    }
  }

  .swiper-pagination-bullet {
    background-color: white;
    opacity: 1;
    height: 3px;
    width: 10px;
    margin: 0 5px !important;
    border-radius: 2px;
    transition: width 0.3s ease-in-out, background-color 0.3s ease-in-out;

    @media (max-width: ${breakpoints.max.xl}) {
      background-color: ${theme.swiper.bullet};
    }
  }

  .swiper-pagination-bullet-active {
    width: 35px;

    @media (max-width: ${breakpoints.max.xl}) {
      background-color: ${theme.swiper.bulletActive};
    }
  }
`;

export let MapImageWrapper = styled.a`
  height: 100%;
`;

export let ImageWrapper = styled.a`
  display: block;

  > div {
    vertical-align: top;
  }
`;
