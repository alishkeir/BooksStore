import dynamic from 'next/dynamic';
import { useEffect, useRef, useState } from 'react';
import { useQuery, QueryClient } from 'react-query';
import { dehydrate } from 'react-query/hydration';
import _cloneDeep from 'lodash/cloneDeep';
import { useSelector } from 'react-redux';
import useMediaQuery from '@hooks/useMediaQuery/useMediaQuery';
import breakpoints from '@vars/breakpoints';
const PageListHead = dynamic(() => import('@components/pageListHead/pageListHead'));
import _set from 'lodash/set';
import isArray from 'lodash/isArray';
const Header = dynamic(() => import('@components/header/header'));
const Content = dynamic(() => import('@components/content/content'));
const BookLister = dynamic(() => import('@components/bookLister/bookLister'));
const Footer = dynamic(() => import('@components/footer/footer'));
const PageTitle = dynamic(() => import('@components/pageTitle/pageTitle'));
const ScrollToTop = dynamic(() => import('@components/scrollToTop/scrollToTop'), { ssr: false });
const SiteColContainer = dynamic(() => import('@components/siteColContainer/siteColContainer'));
import { getResponseById, getRequestById, handleApiRequest, getMetadata } from '@libs/api';
const HeaderPromo = dynamic(() => import('@components/headerPromo/headerPromo'), { ssr: false });
import { AkcioPageWrapper, Banner, ListerWrapper } from '@components/pages/akcioPage.styled';
import OptimizedImage from '@components/Images/OptimizedImage';
import settingsVars from "@vars/settingsVars";
import url from "@libs/url";

let config = {
  controls: {
    parameters: [
      {
        id: 'by_publishing',
        path: 'filters.by_publishing',
        type: 'param',
        name: 'm',
        valueType: 'array',
      },
      {
        id: 'category',
        path: 'filters.category',
        type: 'path',
        pathIndex: 2, // 0
        valueType: 'string',
      },
      {
        id: 'subcategory',
        path: 'filters.subcategory',
        type: 'path',
        pathIndex: 3,
        valueType: 'string',
        depends: ['filters.category'],
      },
      {
        id: 'sort_by',
        path: 'sort_by',
        type: 'param',
        name: 'r',
        valueType: 'string',
      },
      {
        id: 'page',
        path: 'page',
        type: 'param',
        name: 'p',
        valueType: 'number',
        defaultValue: 1,
        optional: true,
      },
    ],
  },
  query: {
    default: {
      request: [
        {
          method: 'GET',
          path: '/product',
          ref: 'list',
          request_id: 'promotion',
          body: {
            section: 'promotion',
            section_params: {
              slug: null,
            },
            filters: {
              by_publishing: null,
              category: null,
              subcategory: null,
            },
            search: null,
            sort_by: null,
            page: 1,
          },
        },
      ],
    },
    mobileFilters: {
      request: [
        {
          method: 'GET',
          path: '/product',
          ref: 'subcategoriesByCategory',
          request_id: 'mobile-subcat',
          body: {
            section: 'promotion',
            section_params: {
              slug: null,
            },
            filters: {
              by_publishing: null,
              category: null,
              subcategory: null,
            },
          },
        },
      ],
    },
  },
};

export default function KonyvekPage(props) {
  let {SSRequest, basePath, SSUrl} = props;
  let settings = settingsVars.get(url.getHost());


  let queryHeadersRef = useRef({
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  });
  let queryRef = useRef(SSRequest);

  let authChecking = useSelector((store) => store.user.authChecking);
  let actualUser = useSelector((store) => store.user.user);

  let isMaxMd = useMediaQuery(`(max-width: ${breakpoints.max.md})`);

  // We create a separate query for mobile filter list
  // We copy over SSR query settings to sync up
  function syncInitialRequestQueries(SSRequest, MobileRequest) {
    let SSRequestClone = _cloneDeep(SSRequest);
    let MobileRequestClone = _cloneDeep(MobileRequest);
    let SSRequestCloneBooklist = getRequestById(SSRequestClone, 'promotion');
    let MobileRequestCloneSubcat = getRequestById(MobileRequestClone, 'mobile-subcat');

    MobileRequestCloneSubcat.body.filters = SSRequestCloneBooklist.body.filters;
    MobileRequestCloneSubcat.body.section_params.slug = SSRequestCloneBooklist.body.section_params.slug;

    return MobileRequestClone;
  }

  let mobileFiltersQueryRef = useRef(syncInitialRequestQueries(SSRequest, config.query.mobileFilters));

  // Initializing React Fetch
  let bookListQuery = useQuery('promotion', () => handleApiRequest({
    headers: queryHeadersRef.current,
    body: queryRef.current,
  }), {
    cacheTime: 0,
    refetchOnWindowFocus: false,
    refetchOnMount: false,
    onSettled: async (data) => {
      if (!data.optimistic) {
        let booklist = getResponseById(data, 'promotion');

        if (booklist.body.pagination.current_page <= 1) {
          setProductList(() => booklist.body.products);
        } else {
          setProductList([...productsList, ...booklist.body.products]);
        }
      }
    },
  });

  // Initializing React Fetch for Mobile categories

  // Mobile subcat initial data copied from SSR with modified request id
  let subcategoryQueryInitialData = _cloneDeep(bookListQuery.data);
  getResponseById(subcategoryQueryInitialData, 'promotion').request_id = 'mobile-subcat';

  let subcategoryQuery = useQuery(
    'mobile-subcat',
    () => handleApiRequest({headers: queryHeadersRef.current, body: mobileFiltersQueryRef.current}),
    {
      cacheTime: 0,
      initialData: subcategoryQueryInitialData,
      refetchOnWindowFocus: false,
      refetchOnMount: false,
    },
  );

  // NAGYKER user personalization
  useEffect(() => {
    if (settings.key !== 'NAGYKER') return;
    if (authChecking || !actualUser) return;

    queryHeadersRef.current['Authorization'] = `Bearer ${actualUser.token}`;

    bookListQuery.refetch();
  }, [authChecking, actualUser]);

  let [productsList, setProductList] = useState(getResponseById(bookListQuery.data, 'promotion')?.body.products);
  let promotionQueryResponse = getResponseById(bookListQuery.data, 'promotion');

  return (
    <AkcioPageWrapper>
      <PageListHead response={promotionQueryResponse} metadata={props.metadata}/>
      <Header promo={HeaderPromo}></Header>
      <Content>
        <ScrollToTop></ScrollToTop>
        <SiteColContainer>
          <Banner>
            {!isMaxMd ? (
              <OptimizedImage src={promotionQueryResponse.body.promotion?.cover} width="2000" height="400" layout="intrinsic" alt=""></OptimizedImage>
            ) : (
              <OptimizedImage src={promotionQueryResponse.body.promotion?.list_image_xl} width="640" height="360" layout="intrinsic" alt=""></OptimizedImage>
            )}
          </Banner>
          <PageTitle mtd={30} mtm={30} mbd={5} mbm={30}>
            {promotionQueryResponse.body.promotion?.title}
          </PageTitle>
          <ListerWrapper>
            <BookLister
              bookListQuery={bookListQuery}
              subcategoryQuery={subcategoryQuery}
              queryRef={queryRef}
              mobileFiltersQueryRef={mobileFiltersQueryRef}
              productsList={productsList}
              requestId="promotion"
              mobileRequestId="mobile-subcat"
              baseURL={basePath}
              pageUrl={SSUrl}
              config={config}
            ></BookLister>
          </ListerWrapper>
        </SiteColContainer>
      </Content>
      <Footer></Footer>
    </AkcioPageWrapper>
  );
}

export async function getServerSideProps(context) {
  let queryClient = new QueryClient();

  let clonedQuery = _cloneDeep(config.query.default);
  let clonedQueryBooklist = getRequestById(clonedQuery, 'promotion');

  // How many params can there be in the url
  let paramsInConfig = config.controls.parameters.filter((param) => param.type === 'path');

  // Checking if not too many params in URL
  if (context.params.slug && context.params.slug.length - 1 > paramsInConfig.length) {
    return {
      notFound: true,
    };
  }

  // Creating a query object from request config
  config.controls.parameters.forEach((parameter) => {
    let paramValue;

    switch (parameter.type) {
      case 'path':
        paramValue = context.query.slug?.[parameter.pathIndex - 1];
        break;
      case 'param':
        paramValue = context.query[parameter.name];
        break;

      default:
        paramValue = '';
        break;
    }

    if (paramValue) {
      // Param is string and were always getting array from server
      if (parameter.valueType === 'array') {
        paramValue = isArray(paramValue) ? paramValue : [paramValue];
      }

      _set(clonedQueryBooklist.body, `${parameter.path}`, paramValue);
    }
  });

  // Setting promotion slug
  clonedQuery.request[0].body.section_params.slug = context.query.slug[0];

  await queryClient.prefetchQuery('promotion', () => handleApiRequest({body: clonedQuery}));

  const metadata = await getMetadata(`/akcio/${context.query.slug[0]}`);

  return {
    props: {
      basePath: `/akcio/${context.query.slug[0]}`,
      dehydratedState: dehydrate(queryClient),
      SSRequest: clonedQuery,
      SSUrl: context.resolvedUrl,
      metadata: metadata.length > 0 ? metadata[0] : null,
    },
  };
}
