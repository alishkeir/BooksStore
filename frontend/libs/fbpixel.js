import url from "@libs/url";
import settingsVars from "@vars/settingsVars";
let settings = settingsVars.get(url.getHost());

let token = "";

if(settings.key === "ALOMGYAR"){
  token = process.env.NEXT_PUBLIC_FACEBOOK_PIXEL_ID_ALOMGYAR;
}else if(settings.key === "OLCSOKONYVEK"){
  token = process.env.NEXT_PUBLIC_FACEBOOK_PIXEL_ID_OLCSOKONYVEK;
}else if(settings.key === "NAGYKER"){
  token = process.env.NEXT_PUBLIC_FACEBOOK_PIXEL_ID_NAGYKER;
}
export const FB_PIXEL_ID = token;

export const pageview = () => {
  window.fbq('track', 'PageView');
};

// https://developers.facebook.com/docs/facebook-pixel/advanced/
export const event = (name, options = {}) => {
  //console.log('track', name, options);
  window.fbq('track', name, options);
};
