import dynamic from 'next/dynamic';
import { useEffect, useState } from 'react';
import url from "libs/url";

import Link from 'next/link';
import { useSelector } from 'react-redux';
import useMediaQuery from '@hooks/useMediaQuery/useMediaQuery';
import breakpoints from '@vars/breakpoints';
// REMOVE THIS, SO PASSWORD CHANGE ACTION WILL WORK AGAIN
//const Header = dynamic(() => import('@components/header/header'), { ssr: false}); //
const Header = dynamic(() => import('@components/header/header')); //
const Content = dynamic(() => import('@components/content/content'));
const Footer = dynamic(() => import('@components/footer/footer'));

const MainHero = dynamic(() => import('@components/mainHero/mainHero'));
const MainHeroIcons = dynamic(() => import('@components/mainHeroIcons/mainHeroIcons'));
const SiteColContainer = dynamic(() => import('@components/siteColContainer/siteColContainer'));
const BookScrollList = dynamic(() => import('@components/bookScrollList/bookScrollList'));
const ListHeader = dynamic(() => import('@components/listHeader/listHeader'));
import MainNewsletterSignup from '@components/mainNewsletterSignup/mainNewsletterSignup';
const MainCategoriesLister = dynamic(() => import('@components/mainCategoriesLister/mainCategoriesLister'));
const MainStoreMap = dynamic(() => import('@components/mainStoreMap/mainStoreMap'));
const ImageContainer = dynamic(() => import('@components/imageContainer/imageContainer'));
import { QueryClient, useQuery } from 'react-query';
import { dehydrate } from 'react-query/hydration';
import { handleApiRequest, getMetadata } from '@libs/api';

import useRequest from '@hooks/useRequest/useRequest';

const NewsletterModal = dynamic(() => import('@components/newsletterModal/newsletterModal'));

import
{
  BestsellerBooksWrapper,
  ClassicBooksWrapper,
  EbookBestsellerBooksWrapper,
  HomePageWrapper,
  ListHeaderWrapper,
  MainBannerWrapper,
  MainCategoriesListerWrapper,
  MainHeroIconsWrapper,
  MainHeroWrapper,
  MainStoreMapWrapper,
  NewBooksWrapper,
  NewsletterSignupWrapper,
  PoromtionWrapper,
  ReservationBooksWrapper,
  SaleBooksWrapper,
  SalesListWrapper,
  StoreMapWrapper,
} from '../components/pages/homePage.styled';
import DynamicHead from '@components/heads/DynamicHead';
import settingsVars from "@vars/settingsVars";

const MainBanner = dynamic(() => import('@components/mainBanner/mainBanner'));
const TopPosts = dynamic(() => import('@components/TopPosts/TopPosts'));

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    'main-get': {
      method: 'GET',
      path: '/mainpage',
      ref: 'mainpage',
      request_id: 'main-get',
    },
  },
};

let postsRequestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    'last-posts-get': {
      method: 'GET',
      path: '/last-posts',
      ref: 'list',
      request_id: 'last-posts-get',
    },
  },
};

export default function Home({ metadata })
{
  let [page, setPage] = useState(null);
  const [posts, setPosts] = useState(null);
  let [modalOpen, setModalOpen] = useState(false);
  let isMaxXL = useMediaQuery(`(max-width: ${breakpoints.max.xl})`);

  let authChecking = useSelector((store) => store.user.authChecking);
  let actualUser = useSelector((store) => store.user.user);

  let settings = settingsVars.get(url.getHost());

  let queryMain = useQuery('main-get', () => handleApiRequest(mainRequest.build()), {
    enabled: false,
    refetchOnWindowFocus: false,
    refetchOnMount: false,
    staleTime: 0,
  });
  let mainRequest = useRequest(requestTemplates, queryMain);
  mainRequest.addRequest('main-get');

  let queryPosts = useQuery('last-posts-get', () => handleApiRequest(postsRequest.build()), {
    refetchOnWindowFocus: false,
    refetchOnMount: false,
    staleTime: 0,
  });
  let postsRequest = useRequest(postsRequestTemplates, queryPosts);
  postsRequest.addRequest('last-posts-get');

  function getImages(images = [])
  {
    return images.map((image) =>
    {
      return {
        title: '',
        slug: image.slug,
        src: {xl: image.list_image_xl, sm: image.list_image_sm},
      };
    });
  }

  // NAGYKER user personalization
  useEffect(() =>
  {
    if (settings.key !== 'NAGYKER') return;
    if (authChecking || !actualUser) return;

    mainRequest.modifyHeaders((headerObject) =>
    {
      headerObject['Authorization'] = `Bearer ${actualUser.token}`;
    });

    postsRequest.modifyHeaders((headerObject) =>
    {
      headerObject['Authorization'] = `Bearer ${actualUser.token}`;
    });

    mainRequest.commit();
    postsRequest.commit();
  }, [authChecking, actualUser]);

  useEffect(() =>
  {
    setPage(queryMain.data.response[0].body);
  }, [queryMain]);

  useEffect(() =>
  {
    setPosts(queryPosts.data?.response[0].body.posts);
  }, [queryPosts]);

  return (
    <HomePageWrapper>
      <DynamicHead metadata={metadata} />
      {modalOpen && (
        <NewsletterModal
          title={'Köszönjük a feliratkozást!'}
          text={'Reméljük hasznos információkkal tudunk szolgálni az új termékekkel és akcióinkkal kapcsolatban.'}
          setModal={() =>
          {
            setModalOpen(false);
          }}
        ></NewsletterModal>
      )}
      <Header></Header>
      <Content>
        <MainHeroWrapper>
          <MainHero carousels={page?.carousels} banner={page?.banner?.main_hero_banner}></MainHero>
        </MainHeroWrapper>
        <MainHeroIconsWrapper>
          <SiteColContainer>
            <MainHeroIcons data={page?.shop_info}></MainHeroIcons>
          </SiteColContainer>
        </MainHeroIconsWrapper>
        {page?.bestsellers?.products?.length > 0 && (
          <BestsellerBooksWrapper>
            <SiteColContainer>
              <BookScrollList
                title="Eladási sikerlista"
                titleLink={page?.bestsellers?.url_to_the_list}
                books={page?.bestsellers?.products}
              ></BookScrollList>
            </SiteColContainer>
          </BestsellerBooksWrapper>
        )}
        {page?.best_discounted?.products?.length > 0 && (
          <SaleBooksWrapper>
            <SiteColContainer>
              <BookScrollList
                title="Akciós sikerlista"
                titleLink={page?.best_discounted?.url_to_the_list}
                books={page?.best_discounted.products}
              ></BookScrollList>
            </SiteColContainer>
          </SaleBooksWrapper>
        )}
        <SaleBooksWrapper>
          <SiteColContainer>
            <div className="yuspify-box" />
          </SiteColContainer>
        </SaleBooksWrapper>
        {isMaxXL && (
          <StoreMapWrapper>
            <SiteColContainer>
              <Link href="/" passHref>
                <MainStoreMapWrapper>
                  <MainStoreMap></MainStoreMap>
                </MainStoreMapWrapper>
              </Link>
            </SiteColContainer>
          </StoreMapWrapper>
        )}
        <NewsletterSignupWrapper>
          <SiteColContainer>
            <MainNewsletterSignup
              setModalOpen={() =>
              {
                setModalOpen(true);
              }}
            ></MainNewsletterSignup>
          </SiteColContainer>
        </NewsletterSignupWrapper>
        {page?.best_preorders?.products?.length > 0 && (
          <ReservationBooksWrapper>
            <SiteColContainer>
              <BookScrollList
                title="Előjegyzés sikerlista"
                titleLink={page?.best_preorders?.url_to_the_list}
                books={page?.best_preorders.products}
              ></BookScrollList>
            </SiteColContainer>
          </ReservationBooksWrapper>
        )}

        {posts && settings.key === 'ALOMGYAR' && (
          <ReservationBooksWrapper>
            <SiteColContainer>
              <ListHeaderWrapper>
                <ListHeader title="Magazin" link="/magazin" border></ListHeader>
              </ListHeaderWrapper>
              <TopPosts posts={posts} />
            </SiteColContainer>
          </ReservationBooksWrapper>
        )}

        {settings.key === 'ALOMGYAR' && page?.ebook_bestsellers?.products.length > 0 && (
          <EbookBestsellerBooksWrapper>
            <SiteColContainer>
              <BookScrollList
                title="E-könyv sikerlista"
                titleLink={page?.ebook_bestsellers?.url_to_the_list}
                books={page?.ebook_bestsellers.products}
              ></BookScrollList>
            </SiteColContainer>
          </EbookBestsellerBooksWrapper>
        )}
        <SalesListWrapper>
          <PoromtionWrapper className="container">
            {page?.promotions?.promotions?.length > 0 && (
              <ListHeaderWrapper>
                <ListHeader title="Akciók" link={page?.promotions?.url_to_the_list} border></ListHeader>
              </ListHeaderWrapper>
            )}
            <ImageContainer images={getImages(page?.promotions?.promotions)}></ImageContainer>
          </PoromtionWrapper>
        </SalesListWrapper>
        {page?.new_arrivals?.products?.length > 0 && (
          <NewBooksWrapper>
            <SiteColContainer>
              <BookScrollList title="Újdonságok" books={page?.new_arrivals.products}
                titleLink="/ujdonsagok"></BookScrollList>
            </SiteColContainer>
          </NewBooksWrapper>
        )}
        {page?.banner?.main_banner && (
          <MainBannerWrapper>
            <MainBanner banner={page?.banner?.main_banner}></MainBanner>
          </MainBannerWrapper>
        )}
        {page?.home_category?.products?.length > 0 && (
          <ClassicBooksWrapper>
            <SiteColContainer>
              <BookScrollList
                title={page?.home_category?.category?.title}
                titleLink={page?.home_category?.url_to_the_list}
                books={page?.home_category?.products}
              ></BookScrollList>
            </SiteColContainer>
          </ClassicBooksWrapper>
        )}
        <MainCategoriesListerWrapper>
          <SiteColContainer>
            <MainCategoriesLister categories={page?.all_categories?.categories}></MainCategoriesLister>
          </SiteColContainer>
        </MainCategoriesListerWrapper>
      </Content>
      <Footer></Footer>
    </HomePageWrapper>
  );
}

export async function getStaticProps()
{
  const queryClient = new QueryClient();

  await queryClient.prefetchQuery('main-get', () =>
    handleApiRequest({
      body: {
        request: [requestTemplates.requests['main-get']],
      },
    }),
  );

  const metadata = await getMetadata('/');

  // Had to wrap 'dehydrate(queryClient)' with JSON.parse(JSON.stringify()) otherwise this error popup:
  // Error: Error serializing `.dehydratedState.queries[0].state.data` returned from `getStaticProps` in "/".
  // Reason: `undefined` cannot be serialized as JSON. Please use `null` or omit this value.

  return {
    props: {
      dehydratedState: JSON.parse(JSON.stringify(dehydrate(queryClient))),
      metadata: metadata.length > 0 ? metadata[0].data : null,
    },
    revalidate: 90
  };
}
