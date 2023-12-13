import { useState, useEffect, useRef, useCallback } from 'react';
import events from '@libs/events';
import Link from 'next/link';
import dynamic from 'next/dynamic';
import { useRouter } from 'next/router';
import { useSelector, useDispatch } from 'react-redux';
import { updateSidebar } from '@store/modules/ui';
import { Spin } from 'hamburger-react';
import { useQuery, useQueryClient } from 'react-query';
import { handleApiRequest, getResponseById } from '@libs/api';
import useRequest from '@hooks/useRequest/useRequest';
import useMediaQuery from '@hooks/useMediaQuery/useMediaQuery';
import breakpoints from '@vars/breakpoints';
const Overlay = dynamic(() => import('@components/overlay/overlay'), { ssr: false });
const SideModal = dynamic(() => import('@components/sideModal/sideModal'), { ssr: false });
import InputText from '@components/inputText/inputText';
import Icon from '@components/icon/icon';
import { useLogout } from '@hooks/useAuth/useAuth';
import useUser from '@hooks/useUser/useUser';

const MobileMenu = dynamic(() => import('@components/mobileMenu/mobileMenu'), { ssr: false });
const SearchResults = dynamic(() => import('@components/searchResults/searchResults'), { ssr: false });
import HeaderIconUser from '@components/headerIconUser/headerIconUser';
import HeaderIconCart from '@components/headerIconCart/headerIconCart';
import HeaderUserMenu from '@components/headerUserMenu/headerUserMenu';
const HeaderUserMobileMenu = dynamic(() => import('@components/headerUserMobileMenu/headerUserMobileMenu'), { ssr: false });
import {
  Col,
  Container,
  HeaderMobileMenuWrapper,
  HeaderMobileSearchInput,
  HeaderMobileSearchResults,
  HeaderMobileSearchWrapper,
  HeaderPromoWrapper,
  HeaderSidebarWrapper,
  HeaderTopNav,
  HeaderWrapper,
  HeaderUserMobileMenuWrapper,
  IconHamburgerWrapper,
  IconWrapper,
  IconsWrapper,
  Li,
  Logo,
  LogoWrapper,
  MenuWrapper,
  Row,
  SearchResultsWrapper,
  SearchWrapper,
  TopMenuWrapper,
  Ul,
} from './header.styled';
import settingsVars from "@vars/settingsVars";
import url from "@libs/url";

let ImageLogoAlomgyar = dynamic(() => import('@assets/images/logos/alomgyar-color.svg'));
let ImageLogoOlcsokonyvek = dynamic(() => import('@assets/images/logos/olcsokonyvek-color.svg'));
let ImageLogoNagyker = dynamic(() => import('@assets/images/logos/nagyker-color.svg'));
let OverlayCardSignup = dynamic(() => import('@components/overlayCardSignup/overlayCardSignup'));

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },

  requests: {
    'header-search-post': {
      method: 'POST',
      path: '/search',
      ref: 'search',
      request_id: 'header-search-post',
      body: {
        term: null,
        in_header: true,
        book_page: null,
        ebook_page: null,
      },
    },
  },
};

export default function Header(props) {
  let { promo: PromoProp } = props;

  let searchWrapperRef = useRef();
  let mobileSearchWrapperRef = useRef();
  let sidebarTimeoutRef = useRef();

  let isMinXL = useMediaQuery(`(min-width: ${breakpoints.min.xl})`);
  let isMinLG = useMediaQuery(`(min-width: ${breakpoints.min.lg})`);
  let isMaxLG = useMediaQuery(`(max-width: ${breakpoints.max.lg})`);
  let isMinMD = useMediaQuery(`(min-width: ${breakpoints.min.md})`);
  let isMaxMD = useMediaQuery(`(max-width: ${breakpoints.max.md})`);

  let router = useRouter();
  let logout = useLogout();
  let dispatch = useDispatch();
  let { actualUser } = useUser();
  let queryClient = useQueryClient();

  let settings = settingsVars.get(url.getHost());
  let scrollDirection = useSelector((store) => store.system.scrollDirection);
  let scrollPosition = useSelector((store) => store.system.scrollPosition);
  let sidebarOpen = useSelector((store) => store.ui.sidebarOpen);
  let sidebarType = useSelector((store) => store.ui.sidebarType);
  let sidebarData = useSelector((store) => store.ui.sidebarData);
  let { overlayOpen, overlayType, overlayData } = useSelector((store) => ({
    overlayOpen: store.ui.overlayOpen,
    overlayType: store.ui.overlayType,
    overlayData: store.ui.overlayData,
  }));

  let [searchInput, setSearchInput] = useState('');
  let [menuBarHidden, setMenuBarHidden] = useState(false);
  let [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  let [userMenuOpen, setUserMenuOpen] = useState(false);
  let [searchMenuOpen, setSearchMenuOpen] = useState(false);
  let [sidebarHeaderOpen, setSidebarHeaderOpen] = useState(false);
  let [sidebarHeaderOut, setSidebarHeaderOut] = useState(false);
  let [booksResult, setBooksResult] = useState([]);
  let [eBooksResult, setEBooksResult] = useState([]);
  let [authorsResult, setAuthors] = useState([]);
  let [inputLengthValid, setInputLengthValid] = useState(false);

  let queryHeaderSearch = useQuery('header-search-post', () => handleApiRequest(headerSearchRequest.build()), {
    enabled: false,
    refetchOnWindowFocus: false,
    refetchOnMount: false,
    staleTime: 0,
    onSuccess: (data) => {
      let headerSearchResult = getResponseById(data, 'header-search-post');

      if (headerSearchResult?.success === true) {
        setBooksResult(headerSearchResult.body.books);
        setEBooksResult(headerSearchResult.body.ebooks);
        setAuthors(headerSearchResult.body.authors);
      }
    },
  });

  let headerSearchRequest = useRequest(requestTemplates, queryHeaderSearch);

  headerSearchRequest.addRequest('header-search-post');

  let createFilterRequest = useCallback((search) => {
    queryClient.cancelQueries('header-search-post');

    headerSearchRequest.modifyRequest('header-search-post', (currentRequest) => {
      currentRequest.body = {
        term: search,
        in_header: true,
        book_page: null,
        ebook_page: null,
      };
    });

    headerSearchRequest.commit();
  }, []);

  // On "auth" action we open sidebar
  useEffect(() => {
    events.on('action:feedback', (hash) => {
      handleSidebarToggle(true, 'feedback', hash);
    });
    events.on('action:newpass', (hash) => {
      handleSidebarToggle(true, 'newpass', hash);
    });
  }, []);

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

  // Sidebar cleanup
  useEffect(() => {
    if (!sidebarHeaderOpen) {
      dispatch(updateSidebar({ type: '', data: '' }));
    }
  }, [sidebarHeaderOpen]);

  // Hiding menu on scroll
  useEffect(() => {
    if (searchInput.length > 0) return;
    if (userMenuOpen) return;

    if (scrollDirection === 'down') {
      if (scrollPosition < 150) return;
      if (!menuBarHidden) setMenuBarHidden(true);
      if (searchMenuOpen) setSearchMenuOpen(false);
    } else if (scrollDirection === 'up') {
      if (menuBarHidden) setMenuBarHidden(false);
    }
  }, [scrollDirection, scrollPosition, searchInput, userMenuOpen]);

  // Close mobile menu on navigation
  useEffect(() => {
    function handleRouterChange() {
      setMobileMenuOpen(false);
      setSearchInput('');
      setSearchMenuOpen(false);
      handleSidebarToggle(false, '');
      setUserMenuOpen(false);
    }

    router.events.on('routeChangeStart', handleRouterChange);

    return () => {
      router.events.off('routeChangeStart', handleRouterChange);
    };
  }, []);

  // If mobile menu open we fixate the body
  useEffect(() => {
    if (sidebarOpen || mobileMenuOpen || (isMaxMD && userMenuOpen) || (searchMenuOpen && searchInput.length > 0)) {
      document.body.classList.add('fixed');
    } else {
      document.body.classList.remove('fixed');
    }
  }, [isMaxMD, sidebarOpen, mobileMenuOpen, searchMenuOpen, searchInput, userMenuOpen]);

  // Cleaning up mobile/desktop changes
  useEffect(() => {
    if (mobileMenuOpen) setMobileMenuOpen(false);
    if (menuBarHidden) setMenuBarHidden(false);
  }, [isMinXL]);

  useEffect(() => {
    if (searchMenuOpen) setSearchMenuOpen(false);
    if (searchInput.length > 0) setSearchInput('');
  }, [isMaxLG]);

  useEffect(() => {
    if (searchInput.length >= 3) {
      !inputLengthValid && setInputLengthValid(true);
      createFilterRequest(searchInput);
    } else {
      inputLengthValid && setInputLengthValid(false);
    }
  }, [searchInput]);

  useEffect(() => {
    if (router.query.forgottenpass) {
      dispatch(updateSidebar({ open: true, type: 'forgottenpass' }));
    }
  }, []);

  let handleSidebarToggle = useCallback((open, type = '', data = '') => {
    if (open) {
      userMenuOpen && setUserMenuOpen(false);
      mobileMenuOpen && setMobileMenuOpen(false);
      searchMenuOpen && setSearchMenuOpen(false);
      !sidebarOpen && dispatch(updateSidebar({ open, type, data }));
    } else {
      sidebarOpen && dispatch(updateSidebar({ open: false, type: sidebarType, data: sidebarData }));
    }
  });

  useEffect(() => {
    if (router.query.forgottenpass) {
      dispatch(updateSidebar({ open: true, type: 'forgottenpass' }));
    }
  }, []);

  let handleUserMenuToggle = useCallback((bool) => {
    if (bool) {
      mobileMenuOpen && setMobileMenuOpen(false);
      searchMenuOpen && setSearchMenuOpen(false);
      sidebarOpen && dispatch(updateSidebar({ open: false, type: '' }));
      !userMenuOpen && setUserMenuOpen(true);
    } else {
      userMenuOpen && setUserMenuOpen(false);
    }
  });

  let toggleMobileMenu = useCallback((bool) => {
    if (bool) {
      searchMenuOpen && setSearchMenuOpen(false);
      sidebarOpen && dispatch(updateSidebar({ open: false, type: '' }));
      userMenuOpen && setUserMenuOpen(false);
      !mobileMenuOpen && setMobileMenuOpen(true);
    } else {
      mobileMenuOpen && setMobileMenuOpen(false);
    }
  });

  let toggleSearchMenu = useCallback((bool) => {
    if (bool) {
      sidebarOpen && dispatch(updateSidebar({ open: false, type: '' }));
      userMenuOpen && setUserMenuOpen(false);
      mobileMenuOpen && setMobileMenuOpen(false);
      !searchMenuOpen && setSearchMenuOpen(true);
    } else {
      searchMenuOpen && setSearchMenuOpen(false);
    }
  });

  let handleLogout = useCallback(() => {
    logout();
    setUserMenuOpen(false);
  });

  return (
    <HeaderWrapper
      mobileMenuOpen={mobileMenuOpen}
      userMenuOpen={userMenuOpen}
      searchMenuOpen={searchMenuOpen}
      sidebarOpen={sidebarHeaderOpen}
      sidebarOut={sidebarHeaderOut}
      menuBarHidden={menuBarHidden}
    >
      {overlayOpen && overlayType === 'preorderSignup' && <OverlayCardSignup itemId={overlayData?.itemId}></OverlayCardSignup>}
      <HeaderTopNav>
        <Container className="container">
          <Row className="row">
            <Col className="col-12">
              <TopMenuWrapper>
                <LogoWrapper>
                  <Link href="/" passHref legacyBehavior>
                    <Logo>
                      {settings.key === 'ALOMGYAR' && <ImageLogoAlomgyar />}
                      {settings.key === 'OLCSOKONYVEK' && <ImageLogoOlcsokonyvek />}
                      {settings.key === 'NAGYKER' && <ImageLogoNagyker />}
                    </Logo>
                  </Link>
                </LogoWrapper>
                <MenuWrapper>
                  <Ul className="d-none d-xl-flex">
                    <Li>
                      <Link href="/konyvlista" passHref>Könyvek</Link>
                    </Li>
                    {settings.pages['/ekonyvlista/[[...slug]]']?.visibleInMenu !== false && (
                      <Li>
                        <Link href="/ekonyvlista" passHref>E-könyvek</Link>
                      </Li>
                    )}
                    <Li>
                      <Link href="/akciok" passHref>Akciók</Link>
                    </Li>
                    {settings.pages['/konyvesboltok']?.visibleInMenu !== false && (
                      <Li>
                        <Link href="/konyvesboltok" passHref>Boltjaink</Link>
                      </Li>
                    )}
                    {settings.pages['/magazin']?.visibleInMenu !== false && (
                      <Li>
                        <Link href="/magazin" passHref>Magazin</Link>
                      </Li>
                    )}
                  </Ul>
                </MenuWrapper>
                <SearchWrapper className="d-none d-lg-flex" ref={searchWrapperRef}>
                  <form action="/kereses">
                    <InputText
                      value={searchInput}
                      name="q"
                      onChange={(e) => setSearchInput(e.target.value)}
                      onReset={() => setSearchInput('')}
                      button="search"
                      iconColor="green"
                      placeholder="Keresés könyvek és szerzők között..."
                      height={40}
                      reset
                    ></InputText>
                  </form>
                  {isMinLG && searchInput.length > 0 && (
                    <SearchResultsWrapper>
                      <SearchResults
                        eBooks={eBooksResult}
                        books={booksResult}
                        authors={authorsResult}
                        searchTerm={searchInput}
                        inputLengthValid={inputLengthValid}
                        fetching={queryHeaderSearch.isFetching}
                      ></SearchResults>
                    </SearchResultsWrapper>
                  )}
                </SearchWrapper>
                <IconsWrapper>
                  <IconWrapper
                    className="d-lg-none"
                    onClick={() => {
                      toggleSearchMenu(!searchMenuOpen);
                    }}
                  >
                    <Icon type="search" iconWidth="22px" iconHeight="22px"></Icon>
                  </IconWrapper>
                  <IconWrapper>
                    <HeaderIconUser
                      theme={settings.key}
                      user={actualUser?.type === 'user' ? actualUser : null}
                      onClick={() => {
                        if (actualUser?.type === 'user') {
                          handleUserMenuToggle(!userMenuOpen);
                        } else {
                          if (sidebarOpen) {
                            if (sidebarType === 'login') {
                              handleSidebarToggle(false);
                            } else {
                              handleSidebarToggle(true, 'login');
                            }
                          } else {
                            handleSidebarToggle(true, 'login');
                          }
                        }
                      }}
                    ></HeaderIconUser>
                    {isMinMD && userMenuOpen && (
                      <HeaderUserMenu
                        onClose={() => handleUserMenuToggle(false)}
                        onLogout={handleLogout}
                        firstName={actualUser?.type === 'user' && actualUser.customer.firstname}
                      ></HeaderUserMenu>
                    )}
                  </IconWrapper>
                  <IconWrapper>
                    <HeaderIconCart count={actualUser && actualUser.customer.cart.items_in_cart}></HeaderIconCart>
                  </IconWrapper>
                  <IconHamburgerWrapper className="d-xl-none">
                    <Spin size={24} toggled={mobileMenuOpen} toggle={toggleMobileMenu}></Spin>
                  </IconHamburgerWrapper>
                </IconsWrapper>
              </TopMenuWrapper>
            </Col>
          </Row>
        </Container>
      </HeaderTopNav>
      {PromoProp && (
        <HeaderPromoWrapper>
          <PromoProp />
        </HeaderPromoWrapper>
      )}
      {searchInput.length > 0 && <Overlay fixed={false} onClick={() => setSearchInput('')}></Overlay>}
      {isMinMD && userMenuOpen && <Overlay fixed={false} onClick={() => setUserMenuOpen(false)}></Overlay>}
      {searchMenuOpen && (
        <HeaderMobileSearchWrapper ref={mobileSearchWrapperRef}>
          <HeaderMobileSearchInput>
            <form action="/kereses">
              <InputText
                name="q"
                value={searchInput}
                onChange={(e) => setSearchInput(e.target.value)}
                onReset={() => setSearchInput('')}
                button="search"
                iconColor="green"
                placeholder="Keresés könyvek és szerzők között..."
                height={40}
                reset
              ></InputText>
            </form>
          </HeaderMobileSearchInput>
          {searchInput.length > 0 && (
            <HeaderMobileSearchResults>
              <SearchResults
                eBooks={eBooksResult}
                books={booksResult}
                authors={authorsResult}
                searchTerm={searchInput}
                inputLengthValid={inputLengthValid}
                fetching={queryHeaderSearch.isFetching}
              ></SearchResults>
            </HeaderMobileSearchResults>
          )}
        </HeaderMobileSearchWrapper>
      )}
      <HeaderMobileMenuWrapper>
        <MobileMenu></MobileMenu>
      </HeaderMobileMenuWrapper>
      {isMaxMD && (
        <HeaderUserMobileMenuWrapper>
          <HeaderUserMobileMenu
            onClose={() => handleUserMenuToggle(false)}
            onLogout={handleLogout}
            firstName={actualUser?.type === 'user' && actualUser.customer.firstname}
          ></HeaderUserMobileMenu>
        </HeaderUserMobileMenuWrapper>
      )}
      <HeaderSidebarWrapper>
        <SideModal
          type={sidebarType}
          data={sidebarData}
          out={sidebarHeaderOut}
          onClose={() => handleSidebarToggle(false)}
          onSetSidebar={(type) => sidebarOpen && dispatch(updateSidebar({ open: true, type }))}
        ></SideModal>
      </HeaderSidebarWrapper>
    </HeaderWrapper>
  );
}
