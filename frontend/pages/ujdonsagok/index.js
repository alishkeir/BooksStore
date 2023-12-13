import dynamic from 'next/dynamic';
import { useEffect, useState } from 'react';
import { useQuery, QueryClient } from 'react-query';
import { dehydrate } from 'react-query/hydration';
import url from '@libs/url';

const Header = dynamic(() => import('@components/header/header'));
const PageTitle = dynamic(() => import('@components/pageTitle/pageTitle'));
const Content = dynamic(() => import('@components/content/content'));
const SiteColContainer = dynamic(() => import('@components/siteColContainer/siteColContainer'));
const Footer = dynamic(() => import('@components/footer/footer'));
const BookTable = dynamic(() => import('@components/bookTable/bookTable'));
const BookCard = dynamic(() => import('@components/bookCard/bookCard'));
const HeaderPromo = dynamic(() => import('@components/headerPromo/headerPromo'));
const BookListPagination = dynamic(() => import('@components/bookListPagination/bookListPagination'));
import { getMetadata, getResponseById, handleApiRequest } from '@libs/api';
import useRequest from '@hooks/useRequest/useRequest';
import { UjdonsagokPageWrapper, Booklist, List, PaginantionWrapper } from '@components/pages/ujdonsagokPage.styled';
import useUser from '@hooks/useUser/useUser';
import settingsVars from "@vars/settingsVars";
const DynamicHead = dynamic(() => import('@components/heads/DynamicHead'));

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    'ujdonsagok-get': {
      method: 'GET',
      path: '/product',
      ref: 'list',
      request_id: 'ujdonsagok-get',
      body: {
        section: 'ujdonsagok',
        section_params: {},
        filters: {
          by_publishing: null,
          category: null,
          subcategory: null,
        },
        sort_by: null,
        page: 1,
      },
    },
  },
};

export default function UjdonsagokPage({metadata}) {
  let {authChecking, actualUser} = useUser();
  let settings = settingsVars.get(url.getHost());

  let ujdonsagokGetQuery = useQuery(`ujdonsagok-get`, () => handleApiRequest(headerSearchRequest.build()), {
    enabled: false,
    refetchOnWindowFocus: false,
    refetchOnMount: false,
    staleTime: 0,
    cacheTime: 0,
    onSettled: (data) => {
      let ujdonsagokGetResponse = getResponseById(data, 'ujdonsagok-get');

      if (ujdonsagokGetResponse.success) {
        if (ujdonsagokGetResponse.body.pagination.current_page > 1) {
          setList([...list, ...ujdonsagokGetResponse.body.products]);
        } else {
          setList(ujdonsagokGetResponse.body.products);
        }
        setPagination(ujdonsagokGetResponse.body.pagination);
      }
    },
  });

  let headerSearchRequest = useRequest(requestTemplates, ujdonsagokGetQuery);

  let [list, setList] = useState(() => {
    let ujdonsagokGetResponse = getResponseById(ujdonsagokGetQuery.data, 'ujdonsagok-get');
    return ujdonsagokGetResponse.success ? ujdonsagokGetResponse.body.products : [];
  });
  let [pagination, setPagination] = useState(() => {
    let ujdonsagokGetResponse = getResponseById(ujdonsagokGetQuery.data, 'ujdonsagok-get');
    return ujdonsagokGetResponse.success ? ujdonsagokGetResponse.body.pagination : {};
  });
  let [showPagination] = useState(true);

  function handleLoadMore() {
    // Changing the request builder object
    headerSearchRequest.addRequest('ujdonsagok-get');

    headerSearchRequest.modifyHeaders((headerObject) => {
      headerObject['Authorization'] = `Bearer ${actualUser.token}`;
    });

    headerSearchRequest.modifyRequest('ujdonsagok-get', (requestObject) => {
      requestObject.body.page = pagination.current_page + 1;
    });

    headerSearchRequest.commit();
  }

  // NAGYKER user personalization
  useEffect(() => {
    if (settings.key !== 'NAGYKER') return;
    if (authChecking || !actualUser) return;

    headerSearchRequest.addRequest('ujdonsagok-get');

    headerSearchRequest.modifyHeaders((headerObject) => {
      headerObject['Authorization'] = `Bearer ${actualUser.token}`;
    });

    headerSearchRequest.commit();
  }, [authChecking, actualUser]);

  return (
    <UjdonsagokPageWrapper>
      <DynamicHead metadata={metadata}/>
      <Header promo={HeaderPromo}></Header>
      <Content>
        <SiteColContainer>
          {list && (
            <>
              <PageTitle>Újdonságok</PageTitle>
              <List>
                <Booklist>
                  <BookTable>
                    {list.map((book) => (
                      <BookCard
                        itemId={book.id}
                        serial={book.rank}
                        key={book.id}
                        imageSrc={book.cover}
                        title={book.title}
                        author={book.authors && book.authors.split(',').join(', ')}
                        originalPrice={book.price_list}
                        price={book.price_sale}
                        isNew={book.is_new}
                        slug={book.slug}
                        discount={book.discount_percent}
                        purchaseType={book.state}
                        bookType={book.type === 0 ? 'book' : 'ebook'}
                      ></BookCard>
                    ))}
                  </BookTable>
                </Booklist>
              </List>
              {showPagination && (
                <PaginantionWrapper>
                  <BookListPagination
                    itemCount={list.length}
                    currentPage={pagination.current_page}
                    lastPage={pagination.last_page}
                    perPage={pagination.per_page}
                    totalItems={pagination.total}
                    onClick={handleLoadMore}
                    loading={ujdonsagokGetQuery.isFetching}
                  ></BookListPagination>
                </PaginantionWrapper>
              )}
            </>
          )}
        </SiteColContainer>
      </Content>
      <Footer></Footer>
    </UjdonsagokPageWrapper>
  );
}

export async function getStaticProps() {
  const queryClient = new QueryClient();

  let requestTemplate = requestTemplates.requests['ujdonsagok-get'];

  let request = {
    body: {
      request: [requestTemplate],
    },
  };

  await queryClient.prefetchQuery(`ujdonsagok-get`, () => handleApiRequest(request));

  let queryData = queryClient.getQueryData();

  if (!queryData || !queryData.success)
    return {
      notFound: true,
      revalidate: 10,
    };

    const metadata = await getMetadata('/ujdonsagok');

  return {props: {
    dehydratedState: dehydrate(queryClient),
    metadata: metadata.length > 0 ? metadata[0].data : null
  }, revalidate: 90};
}
