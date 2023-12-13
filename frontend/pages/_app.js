import dynamic from 'next/dynamic';
import { useRef } from 'react';
import { Provider } from 'react-redux';
import store from '@store/store';
import DocumentInfo from '@components/documentInfo/documentInfo';
import AppAction from '@components/appAction/appAction';
import AppAuth from '@components/appAuth/appAuth';
import Affiliate from '@components/affiliate/affiliate';
import AppInit from '@components/appInit/appInit';
import AppRoutes from '@components/appRoutes/appRoutes';
import AppFlash from '@components/appFlash/appFlash';
const AppLoginWall = dynamic(() => import('@components/appLoginWall/appLoginWall'), { ssr: false });
import Heads from '@components/heads/heads';
import { QueryClientProvider, QueryClient } from 'react-query';
import { ReactQueryDevtools } from 'react-query/devtools';
import { Hydrate } from 'react-query/hydration';
const CookieConsent = dynamic(() => import('@components/cookieConsent/cookieConsent'), { ssr: false });
import { GoogleOAuthProvider } from '@react-oauth/google';

import '../styles/globals.scss';
import url from "@libs/url";
import {updateSettings} from "@store/modules/settings";
import settingsVars from "@vars/settingsVars";
import NextNProgress from 'nextjs-progressbar';


function MyApp({ Component, pageProps }) {
  let queryClientRef = useRef();
  if (!queryClientRef.current) {
    queryClientRef.current = new QueryClient();
  }

  let settings = settingsVars.get(url.getHost());


  return (
    <>
      <QueryClientProvider client={queryClientRef.current}>
        <GoogleOAuthProvider clientId={process.env.NEXT_PUBLIC_GOOGLE_CLIENT_ID}>
          <Hydrate state={pageProps.dehydratedState}>
            <Provider store={store}>
              <Heads></Heads>
              <Affiliate />
              <AppAuth />
              {settings.key === 'NAGYKER' ? (
                <AppLoginWall>
                  <AppInit />
                  <DocumentInfo />
                  <AppRoutes>
                    <Component {...pageProps} />
                  </AppRoutes>
                </AppLoginWall>
              ) : (
                <>
                  <AppInit />
                  <DocumentInfo />
                  <AppRoutes>
                    <NextNProgress transformCSS={(css) => {
                      css += "#nprogress{position:fixed;z-index:100000000000 !important;}";
                      return <style>{css}</style>;
                    }} />
                    <Component {...pageProps} />
                  </AppRoutes>
                </>
              )}
              <AppAction />
              <AppFlash />
              <ReactQueryDevtools />
            </Provider>
          </Hydrate>
          <CookieConsent />
        </GoogleOAuthProvider>
      </QueryClientProvider>
    </>
  );
}
export default MyApp;

MyApp.getInitialProps = async (appContext, pageProps) =>
{
  url.setHost(appContext.ctx.req);
  store.dispatch(updateSettings(settingsVars.get(url.getHost())));

  return {
    pageProps: pageProps,
  }
}
