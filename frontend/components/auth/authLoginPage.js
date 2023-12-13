import { useState, useEffect, useCallback, useRef } from 'react';
import dynamic from 'next/dynamic';
import { useRouter } from 'next/router';
import events from '@libs/events';
import { useSelector, useDispatch } from 'react-redux';
import { updateSidebar } from '@store/modules/ui';
import SideModal from '@components/sideModal/sideModal';
import SideModalLogin from '@components/sideModalLogin/sideModalLogin';
import {
  Container,
  SidebarWrapper,
  AuthLoginPageComponent,
  Content,
  FormWrapper,
  Logo,
  LogoWrapper,
  Title,
} from '@components/pages/authLoginPage.styled';
import settingsVars from "@vars/settingsVars";
import url from "@libs/url";
import {usePathname, useSearchParams} from "@node_modules/next/dist/client/components/navigation";

let ImageLogoAlomgyar = dynamic(() => import('@assets/images/logos/alomgyar-color.svg'));
let ImageLogoOlcsokonyvek = dynamic(() => import('@assets/images/logos/olcsokonyvek-color.svg'));
let ImageLogoNagyker = dynamic(() => import('@assets/images/logos/nagyker-color.svg'));

export default function AuthLoginPage() {
  let settings = settingsVars.get(url.getHost());

  let sidebarTimeoutRef = useRef();
  let router = useRouter();
  let dispatch = useDispatch();

  let [sidebarHeaderOpen, setSidebarHeaderOpen] = useState(false);
  let [sidebarHeaderOut, setSidebarHeaderOut] = useState(false);

  let sidebarOpen = useSelector((store) => store.ui.sidebarOpen);
  let sidebarType = useSelector((store) => store.ui.sidebarType);
  let sidebarData = useSelector((store) => store.ui.sidebarData);

  let handleSidebarToggle = useCallback((open, type = '', data = '') => {
    if (open) {
      !sidebarOpen && dispatch(updateSidebar({ open, type, data }));
    } else {
      sidebarOpen && dispatch(updateSidebar({ open: false, type: sidebarType, data: sidebarData }));
    }
  });

  // Sidebar opens local sidebar
  useEffect(() => {
    if (sidebarOpen) {
      !sidebarHeaderOpen && setSidebarHeaderOpen(true);
      !sidebarHeaderOut && setSidebarHeaderOut(true);
    } else {
      sidebarHeaderOut && setSidebarHeaderOut(false);

      sidebarTimeoutRef.current = setTimeout(() => {
        sidebarHeaderOpen && setSidebarHeaderOpen(false);
      }, 300);
    }

    return () => clearTimeout(sidebarTimeoutRef.current);
  }, [sidebarOpen]);

  // On "auth" action we open sidebar
  useEffect(() => {
    events.on('action:feedback', (hash) => {
      handleSidebarToggle(true, 'feedback', hash);
    });
    events.on('action:newpass', (hash) => {
      handleSidebarToggle(true, 'newpass', hash);
    });
  }, []);

  const pathname = usePathname()
  const searchParams = useSearchParams()
  useEffect(() => {
    if(typeof window !== "undefined") {
      const url = window.location.href;
      if (url.indexOf("action:newpass") > 0) {
        let hash = url.substring(url.indexOf("action:newpass"));
        handleSidebarToggle(true, 'newpass', hash);
      }
    }
  }, [pathname, searchParams])

  // Close sidebar on navigation
  useEffect(() => {
    function handleRouterChange() {
      sidebarOpen && handleSidebarToggle(false, '');
    }

    router.events.on('routeChangeStart', handleRouterChange);

    return () => {
      router.events.off('routeChangeStart', handleRouterChange);
    };
  }, []);

  return (
    <AuthLoginPageComponent>
      <Container sidebarOpen={sidebarHeaderOpen} sidebarOut={sidebarHeaderOut}>
        <SidebarWrapper>
          <SideModal
            type={sidebarType}
            data={sidebarData}
            out={sidebarHeaderOut}
            onClose={() => handleSidebarToggle(false)}
            onSetSidebar={(type) => dispatch(updateSidebar({ open: true, type }))}
          ></SideModal>
        </SidebarWrapper>
      </Container>
      <Content>
        <LogoWrapper>
          <Logo>
            {settings.key === 'ALOMGYAR' && <ImageLogoAlomgyar />}
            {settings.key === 'OLCSOKONYVEK' && <ImageLogoOlcsokonyvek />}
            {settings.key === 'NAGYKER' && <ImageLogoNagyker />}
          </Logo>
        </LogoWrapper>
        <FormWrapper>
          <Title>Bejelentkezés</Title>
          <SideModalLogin redirect="/" onSetSidebar={(type) => dispatch(updateSidebar({ open: true, type }))}></SideModalLogin>
        </FormWrapper>
      </Content>
    </AuthLoginPageComponent>
  );
}
