import dynamic from 'next/dynamic';
import { useEffect, useRef, useState } from 'react';
import { useQuery, QueryClient } from 'react-query';
import { dehydrate } from 'react-query/hydration';
import _cloneDeep from 'lodash/cloneDeep';
import _set from 'lodash/set';
import { useSelector } from 'react-redux';
import isArray from 'lodash/isArray';
import url from '@libs/url';
const PageListHead = dynamic(() => import('@components/pageListHead/pageListHead'));
const Header = dynamic(() => import('@components/header/header'));
import PageTitle from '@components/pageTitle/pageTitle';
const Content = dynamic(() => import('@components/content/content'));
const BookLister = dynamic(() => import('@components/bookLister/bookLister'));
const Footer = dynamic(() => import('@components/footer/footer'));
const ScrollToTop = dynamic(() => import('@components/scrollToTop/scrollToTop'));
const SiteColContainer = dynamic(() => import('@components/siteColContainer/siteColContainer'));
const HeaderPromo = dynamic(() => import('@components/headerPromo/headerPromo'), {ssr: false});
import {getResponseById, getRequestById, handleApiRequest, getMetadata} from '@libs/api';
import { KonyvekPageWrapper, ListerWrapper } from '@components/pages/konyvekPage.styled';
import settingsVars from "@vars/settingsVars";

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
        pathIndex: 1,
        valueType: 'string',
      },
      {
        id: 'subcategory',
        path: 'filters.subcategory',
        type: 'path',
        pathIndex: 2,
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
          request_id: 'booklist',
          body: {
            section: 'book',
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
            section: 'book',
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
  let {SSRequest, SSUrl} = props;

  let queryHeadersRef = useRef({
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  });
  let queryRef = useRef(SSRequest);

  let authChecking = useSelector((store) => store.user.authChecking);
  let actualUser = useSelector((store) => store.user.user);

  // We create a separate query for mobile filter list
  // We copy over SSR query settings to sync up
  function syncInitialRequestQueries(SSRequest, MobileRequest) {
    let SSRequestClone = _cloneDeep(SSRequest);
    let MobileRequestClone = _cloneDeep(MobileRequest);
    let SSRequestCloneBooklist = getRequestById(SSRequestClone, 'booklist');
    let MobileRequestCloneSubcat = getRequestById(MobileRequestClone, 'mobile-subcat');

    MobileRequestCloneSubcat.body.filters = SSRequestCloneBooklist.body.filters;

    return MobileRequestClone;
  }

  let mobileFiltersQueryRef = useRef(syncInitialRequestQueries(SSRequest, config.query.mobileFilters));

  // Initializing React Fetch

  let bookListQuery = useQuery('booklist', () => handleApiRequest({
    headers: queryHeadersRef.current,
    body: queryRef.current,
  }), {
    cacheTime: 0,
    refetchOnWindowFocus: false,
    refetchOnMount: false,
    onSettled: async (data) => {
      if (!data.optimistic) {
        let booklist = getResponseById(data, 'booklist');

        if (booklist.body.pagination.current_page <= 1) {
          setProductList(booklist.body.products);
        } else {
          setProductList((oldProducts) => [...oldProducts, ...booklist.body.products]);
        }
      }
    },
  });

  // Initializing React Fetch for Mobile categories

  // Mobile subcat initial data copied from SSR with modified request id
  let subcategoryQueryInitialData = _cloneDeep(bookListQuery.data);
  let booklist = getResponseById(subcategoryQueryInitialData, 'booklist');
  if(booklist !== undefined && booklist !== null) {
    getResponseById(subcategoryQueryInitialData, 'booklist').request_id = 'mobile-subcat';
  }
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

  let settings = settingsVars.get(url.getHost());

  // NAGYKER user personalization
  useEffect(() => {
    if (settings.key !== 'NAGYKER') return;
    if (authChecking || !actualUser) return;

    queryHeadersRef.current['Authorization'] = `Bearer ${actualUser.token}`;

    bookListQuery.refetch();
  }, [authChecking, actualUser]);

  let [productsList, setProductList] = useState(getResponseById(bookListQuery.data, 'booklist')?.body.products);
  let bookListQueryResponse = getResponseById(bookListQuery.data, 'booklist');

  return (
    <KonyvekPageWrapper>
      <PageListHead response={bookListQueryResponse} metadata={props.metadata}></PageListHead>
      <Header promo={HeaderPromo}></Header>
      <Content>
        <ScrollToTop></ScrollToTop>
        <SiteColContainer>
          <PageTitle mbd={5} mbm={30}>
            Könyvek
          </PageTitle>
          <ListerWrapper>
            <BookLister
              bookListQuery={bookListQuery}
              subcategoryQuery={subcategoryQuery}
              queryRef={queryRef}
              mobileFiltersQueryRef={mobileFiltersQueryRef}
              productsList={productsList}
              requestId="booklist"
              mobileRequestId="mobile-subcat"
              baseURL="/konyvlista"
              pageUrl={SSUrl}
              config={config}
            ></BookLister>
          </ListerWrapper>
        </SiteColContainer>
      </Content>
      <Footer></Footer>
    </KonyvekPageWrapper>
  );
}

export async function getServerSideProps(context) {
  let queryClient = new QueryClient();

  let clonedQuery = _cloneDeep(config.query.default);
  let clonedQueryBooklist = getRequestById(clonedQuery, 'booklist');

  // How many params can there be in the url
  let paramsInConfig = config.controls.parameters.filter((param) => param.type === 'path');

  // Checking if not too many params in URL
  if (context.params.slug && context.params.slug.length > paramsInConfig.length) {
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

  await queryClient.prefetchQuery('booklist', () => handleApiRequest({body: clonedQuery}));

  const metadata = await getMetadata('/konyvlista');

  return {
    props: {
      dehydratedState: dehydrate(queryClient),
      SSRequest: clonedQuery,
      SSUrl: context.resolvedUrl,
      metadata: metadata.length > 0 ? metadata[0].data : null,
    },
  };
}
