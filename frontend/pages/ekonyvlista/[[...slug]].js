import dynamic from 'next/dynamic';
import { useEffect, useRef, useState } from 'react';
import { useQuery, QueryClient } from 'react-query';
import { dehydrate } from 'react-query/hydration';
import _cloneDeep from 'lodash/cloneDeep';
import _set from 'lodash/set';
import { useSelector } from 'react-redux';
import isArray from 'lodash/isArray';
import PageListHead from '@components/pageListHead/pageListHead';
const Header = dynamic(() => import('@components/header/header'));
const Content = dynamic(() => import('@components/content/content'));
const BookLister = dynamic(() => import('@components/bookLister/bookLister'));
const Footer = dynamic(() => import('@components/footer/footer'));
const PageTitle = dynamic(() => import('@components/pageTitle/pageTitle'));
const SiteColContainer = dynamic(() => import('@components/siteColContainer/siteColContainer'));
import { getResponseById, getRequestById, handleApiRequest } from '@libs/api';
const HeaderPromo = dynamic(() => import('@components/headerPromo/headerPromo'));
import { EkonyvekPageWrapper, ListerWrapper } from '@components/pages/ekonyvekPage.styled';
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
          request_id: 'ebooklist',
          body: {
            section: 'ebook',
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

export default function EkonyvekPage(props) {
  let { SSRequest, SSUrl } = props;
  let settings = settingsVars.get(url.getHost());


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
    let SSRequestCloneBooklist = getRequestById(SSRequestClone, 'ebooklist');
    let MobileRequestCloneSubcat = getRequestById(MobileRequestClone, 'mobile-subcat');

    MobileRequestCloneSubcat.body.filters = SSRequestCloneBooklist.body.filters;

    return MobileRequestClone;
  }

  let mobileFiltersQueryRef = useRef(syncInitialRequestQueries(SSRequest, config.query.mobileFilters));

  // Initializing React Fetch
  let bookListQuery = useQuery('ebooklist', () => handleApiRequest({ headers: queryHeadersRef.current, body: queryRef.current }), {
    cacheTime: 0,
    refetchOnWindowFocus: false,
    refetchOnMount: false,
    onSettled: async (data) => {
      if (!data.optimistic) {
        let booklist = getResponseById(data, 'ebooklist');

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
  getResponseById(subcategoryQueryInitialData, 'ebooklist').request_id = 'mobile-subcat';

  let subcategoryQuery = useQuery('mobile-subcat', handleApiRequest({ headers: queryHeadersRef.current, body: mobileFiltersQueryRef.current }), {
    cacheTime: 0,
    initialData: subcategoryQueryInitialData,
    refetchOnWindowFocus: false,
    refetchOnMount: false,
  });

  // NAGYKER user personalization
  useEffect(() => {
    if (settings.key !== 'NAGYKER') return;
    if (authChecking || !actualUser) return;

    queryHeadersRef.current['Authorization'] = `Bearer ${actualUser.token}`;

    bookListQuery.refetch();
  }, [authChecking, actualUser]);

  let [productsList, setProductList] = useState(getResponseById(bookListQuery.data, 'ebooklist')?.body.products);
  let bookListQueryResponse = getResponseById(bookListQuery.data, 'ebooklist');

  return (
    <EkonyvekPageWrapper>
      <PageListHead response={bookListQueryResponse}></PageListHead>
      <Header promo={HeaderPromo}></Header>
      <Content>
        <SiteColContainer>
          <PageTitle mbd={5} mbm={30}>
            E-Könyvek
          </PageTitle>
          <ListerWrapper>
            <BookLister
              bookListQuery={bookListQuery}
              subcategoryQuery={subcategoryQuery}
              queryRef={queryRef}
              mobileFiltersQueryRef={mobileFiltersQueryRef}
              productsList={productsList}
              requestId="ebooklist"
              mobileRequestId="mobile-subcat"
              baseURL="/ekonyvlista"
              pageUrl={SSUrl}
              config={config}
            ></BookLister>
          </ListerWrapper>
        </SiteColContainer>
      </Content>
      <Footer></Footer>
    </EkonyvekPageWrapper>
  );
}

export async function getServerSideProps(context) {
  let queryClient = new QueryClient();

  let clonedQuery = _cloneDeep(config.query.default);
  let clonedQueryBooklist = getRequestById(clonedQuery, 'ebooklist');

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

  await queryClient.prefetchQuery('ebooklist', () => handleApiRequest({ body: clonedQuery }));

  return { props: { dehydratedState: dehydrate(queryClient), SSRequest: clonedQuery, SSUrl: context.resolvedUrl } };
}
