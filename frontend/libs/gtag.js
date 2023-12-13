import url from "@libs/url";
import settingsVars from "@vars/settingsVars";
let settings = settingsVars.get(url.getHost());

let token = "";
let tokenImporved = "";
let tokenGtm = "";

if(settings.key === "ALOMGYAR"){
  token = process.env.NEXT_PUBLIC_GA_ID_ALOMGYAR;
  tokenImporved = process.env.NEXT_PUBLIC_GA_ID_IMPROVED_ALOMGYAR;
  tokenGtm = process.env.NEXT_PUBLIC_GTM_ID_ALOMGYAR;
}else if(settings.key === "OLCSOKONYVEK"){
  token = process.env.NEXT_PUBLIC_GA_ID_OLCSOKONYVEK;
  tokenImporved = process.env.NEXT_PUBLIC_GA_ID_IMPROVED_OLCSOKONYVEK;
  tokenGtm = process.env.NEXT_PUBLIC_GTM_ID_OLCSOKONYVEK;
}else if(settings.key === "NAGYKER"){
  token = process.env.NEXT_PUBLIC_GA_ID_NAGYKER;
  tokenImporved = process.env.NEXT_PUBLIC_GA_ID_IMPROVED_NAGYKER;
  tokenGtm = process.env.NEXT_PUBLIC_GTM_ID_NAGYKER;
}
export const GA_TRACKING_ID = token;
export const GA_TRACKING_ID_IMPROVED = tokenImporved;
export const GTM_TRACKING_ID = tokenGtm;

// https://developers.google.com/analytics/devguides/collection/gtagjs/pages
export const pageview = (url) => {
  window.gtag('config', GA_TRACKING_ID, {
    page_path: url,
  });
};

// // https://developers.google.com/analytics/devguides/collection/gtagjs/events
// export const event = ({ action, category, label, value }) => {
//   window.gtag('event', action, {
//     event_category: category,
//     event_label: label,
//     value: value,
//   });
// };

// https://developers.google.com/analytics/devguides/collection/gtagjs/events
export const gtagEvent = (action, payload) => {
  window.gtag('event', action, payload);
};

export const gaEvent = (action, payload) => {
  window.gtag('event', action, payload);
};
