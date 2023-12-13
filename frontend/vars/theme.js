import colors from '@vars/colors';
import url from "libs/url";

let theme;
let themes = {};

themes.ALOMGYAR = {
  main: {
    primary: colors.monza,
    secondary: colors.mineShaft,
    error: colors.monza,
  },
  text: {
    dark: colors.mineShaft,
    light: colors.silverChaliceDark,
  },
  badge: {
    discount: colors.mediumPurple,
    index: colors.saffron,
    tag: colors.dodgerBlue,
    type: colors.malachite,
  },
  message: {
    info: colors.pattensBlue,
    warning: colors.eggWhite,
    success: colors.harp,
  },
  gray: {
    darkBlue: colors.ghost,
    blue: colors.mischka,
    light: colors.athensGray,
    lightPurple: colors.titanWhite,
    lightBlue: colors.zirconBlue,
    white: colors.zircon,
  },
  button: {
    primary: colors.monza,
    primaryHover: colors.monzaDark,
    secondary: colors.white,
    secondaryHover: colors.chablis,
    tertiary: colors.monza,
    tertiaryHover: colors.monzaDark,
    preorder: colors.greenHaze,
    preorderHover: colors.greenHazeDark,
    inactive: colors.mischka,
  },
  iconPin: {
    main: colors.athensGrayDark,
    border: colors.athensGrayDark,
    bold: colors.monza,
  },
};

themes.OLCSOKONYVEK = {
  main: {
    primary: colors.amber,
    secondary: colors.mineShaft,
    error: colors.monza,
  },
  text: {
    dark: colors.mineShaft,
    light: colors.silverChaliceDark,
  },
  badge: {
    discount: colors.monza,
    index: colors.saffron,
    tag: colors.dodgerBlue,
    type: colors.malachite,
  },
  message: {
    info: colors.pattensBlue,
    warning: colors.eggWhite,
    success: colors.harp,
  },
  gray: {
    darkBlue: colors.ghost,
    blue: colors.mischka,
    light: colors.athensGray,
    lightPurple: colors.titanWhite,
    lightBlue: colors.zirconBlue,
    white: colors.zircon,
  },
  button: {
    primary: colors.christine,
    primaryHover: colors.christineDark,
    secondary: colors.white,
    secondaryHover: colors.yoghurt,
    tertiary: colors.amber,
    tertiaryHover: colors.corn,
    preorder: colors.greenHaze,
    preorderHover: colors.greenHazeDark,
    inactive: colors.mischka,
  },
  iconPin: {
    main: colors.white,
    border: colors.saffron,
    bold: colors.mineShaft,
  },
};

themes.NAGYKER = {
  main: {
    primary: colors.dodgerBlueLight,
    secondary: colors.mineShaft,
    error: colors.monza,
  },
  text: {
    dark: colors.mineShaft,
    light: colors.silverChaliceDark,
  },
  badge: {
    discount: colors.monza,
    index: colors.saffron,
    tag: colors.dodgerBlue,
    type: colors.malachite,
  },
  message: {
    info: colors.pattensBlue,
    warning: colors.eggWhite,
    success: colors.harp,
  },
  gray: {
    darkBlue: colors.ghost,
    blue: colors.mischka,
    light: colors.athensGray,
    lightPurple: colors.titanWhite,
    lightBlue: colors.zirconBlue,
    white: colors.zircon,
  },
  button: {
    primary: colors.dodgerBlueLight,
    primaryHover: colors.dodgerBlueLightDark,
    secondary: colors.white,
    secondaryHover: colors.zirconGray,
    tertiary: colors.dodgerBlueLight,
    tertiaryHover: colors.dodgerBlueLightDark,
    preorder: colors.greenHaze,
    preorderHover: colors.greenHazeDark,
    inactive: colors.mischka,
  },
  iconPin: {
    main: colors.white,
    border: colors.dodgerBlueLight,
    bold: colors.mineShaft,
  },
};


if (url.getHost()?.indexOf("olcsokonyvek") > -1) {
  theme = themes.OLCSOKONYVEK;
} else if (url.getHost()?.indexOf("nagyker") > -1) {
  theme = themes.NAGYKER;
}else{
  theme = themes.ALOMGYAR;

}

export { themes };
export default theme;
