/* eslint-disable @next/next/no-img-element */
import { useEffect } from 'react';
import Head from 'next/head';
import { useRouter } from 'next/router';
import Script from 'next/script';
import * as fbq from '@libs/fbpixel';
import * as gtag from '@libs/gtag';

import imageFaviconAlomgyar from '@assets/images/favicons/favicon-alomgyar.png';
import imageFaviconOlcsokonyvek from '@assets/images/favicons/favicon-olcsokonyvek.png';
import imageFaviconNagyker from '@assets/images/favicons/favicon-nagyker.png';
import useUser from '@hooks/useUser/useUser';
import settingsVars from "@vars/settingsVars";
import url from "@libs/url";

export default function Heads() {
  let settings = settingsVars.get(url.getHost());

  const router = useRouter();
  let { actualUser } = useUser();

  useEffect(() => {
    // This pageview only triggers the first time (it's important for Pixel to have real information)
    fbq.pageview();

    const handleRouteChange = (url) => {
      console.log('pageview');
      fbq.pageview();
      gtag.pageview(url);
    };

    router.events.on('routeChangeComplete', handleRouteChange);
    return () => {
      router.events.off('routeChangeComplete', handleRouteChange);
    };
  }, [router.events]);

  return (
    <>
      <Script
        strategy="afterInteractive"
        id="script-facebook-tracking"
        dangerouslySetInnerHTML={{
          __html: `
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', ${fbq.FB_PIXEL_ID});
          `,
        }}
      />

      <Script strategy="afterInteractive" src={`https://www.googletagmanager.com/gtag/js?id=${gtag.GA_TRACKING_ID}`} />
      <Script strategy="afterInteractive" src={`https://www.googletagmanager.com/gtag/js?id=${gtag.GA_TRACKING_ID_IMPROVED}`} />
      {settings.key === 'OLCSOKONYVEK' && (
        <Script strategy="afterInteractive" src={`https://www.googletagmanager.com/gtag/js?id=${gtag.GTM_TRACKING_ID}`} />
      )}

      <Script
        strategy="afterInteractive"
        id="script-google-tracking"
        dangerouslySetInnerHTML={{
          __html: `
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '${gtag.GA_TRACKING_ID}', {	
              page_path: window.location.pathname	
              });	
              gtag('config', '${gtag.GA_TRACKING_ID_IMPROVED}', {	
              allow_enhanced_conversions: true	
              });
            gtag('require', 'ec');
          `,
        }}
      />

      { settings.key === "ALOMGYAR" && <Script
        strategy="afterInteractive"
        id="script-yuspify"
        dangerouslySetInnerHTML={{
          __html: `
          (function(g,r,a,v,i,t,y){
            g[a]=g[a]||[];g[a].push({type:'config',partnerId:t,targetServer:i,dynamic:true});
            y=r.createElement(v), g=r.getElementsByTagName(v)[0];
            y.src='//'+i+'/js/'+t+'/gr_reco7.min.js';
            g.parentNode.insertBefore(y,g);
        })(window, document, '_gravity','script', '${process.env.NEXT_PUBLIC_YUPIFY_USERNAME}.engine.yusp.com', '${process.env.NEXT_PUBLIC_YUPIFY_USERNAME}');`,
        }}
      />}
      { settings.key === "OLCSOKONYVEK" && <Script
        id="script-live-chat"
        dangerouslySetInnerHTML={{
          __html: `
          var _smartsupp = _smartsupp || {};
          _smartsupp.key = '36c52a3e7f92d68024a2c57c21550bd88a1d484f';
          window.smartsupp||(function(d) {
                  var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
                  s=d.getElementsByTagName('script')[0];c=d.createElement('script');
                  c.type='text/javascript';c.charset='utf-8';c.async=true;
                  c.src='https://www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
          })(document)
          `,
        }}
      />}

      <Head>
        <noscript>
          <img
            height="1"
            width="1"
            style={{ display: 'none' }}
            src={`https://www.facebook.com/tr?id=${fbq.FB_PIXEL_ID}&ev=PageView&noscript=1`}
            alt=""
          />
        </noscript>

        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0" />
        <meta property="og:url" content={`${settings.common.meta.url}${router.asPath}`} />
        <meta property="og:type" content={settings.common.meta.type} />
        {router.route !== '/konyv/[slug]' && (
          <>
            <meta property="og:image:width" content={settings.common.meta.image.width} />
            <meta property="og:image:height" content={settings.common.meta.image.height} />
          </>
        )}
        {settings.key === 'OLCSOKONYVEK' && <meta name="facebook-domain-verification" content="obxxzn3ixdwm3azh36l34mtqcuokmq" />}
        {settings.key === 'ALOMGYAR' && <meta name="facebook-domain-verification" content="195z6r2cmfzqxbxwxkz8jkx1cze599" />}

        {settings.key === 'ALOMGYAR' && <link rel="icon" type="image/png" href={imageFaviconAlomgyar.src}></link>}
        {settings.key === 'OLCSOKONYVEK' && <link rel="icon" type="image/png" href={imageFaviconOlcsokonyvek.src}></link>}
        {settings.key === 'NAGYKER' && <link rel="icon" type="image/png" href={imageFaviconNagyker.src}></link>}

        {actualUser != null && actualUser.customer.cart.items_in_cart > 0 ? (
          <>
            {actualUser.customer.cart.cart_items.map((item) => (
              <meta name="yuspItemInCart" content={item.id} key={item.id} />
            ))}
          </>
        ) : (
          <>
            <meta name="yuspItemInCart" />
          </>
        )}
      </Head>
    </>
  );
}
