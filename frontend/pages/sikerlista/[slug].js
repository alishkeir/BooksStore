import dynamic from 'next/dynamic';
import { useQuery, QueryClient } from 'react-query';
import { dehydrate } from 'react-query/hydration';
const Header = dynamic(() => import('@components/header/header'));
const PageTitle = dynamic(() => import('@components/pageTitle/pageTitle'));
const Content = dynamic(() => import('@components/content/content'));
const SiteColContainer = dynamic(() => import('@components/siteColContainer/siteColContainer'));
const Footer = dynamic(() => import('@components/footer/footer'));
const BookTable = dynamic(() => import('@components/bookTable/bookTable'));
const BookCard = dynamic(() => import('@components/bookCard/bookCard'));
const HeaderPromo = dynamic(() => import('@components/headerPromo/headerPromo'));
import { getMetadata, getResponseById, handleApiRequest } from '@libs/api';
import { SikerlistaPageWrapper, Booklist, List } from '@components/pages/sikerlistaPage.styled';
import useUser from '@hooks/useUser/useUser';
import useRequest from '@hooks/useRequest/useRequest';
import { useEffect } from 'react';
import DynamicHead from '@components/heads/DynamicHead';
import url from '@libs/url';
import settingsVars from "@vars/settingsVars";


let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    'ranked-get': {
      method: 'GET',
      path: '/product',
      ref: 'list',
      request_id: 'ranked-get',
      body: {
        section: 'ranked',
        section_params: {
          slug: 'akcios-sikerlista',
        },
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

export default function SikerlistaPage(props)
{
  let { slug } = props;

  let { authChecking, actualUser } = useUser();

  let rankedListGetQuery = useQuery(`ranked-get-${slug}`, () => handleApiRequest(requestAuthorGet.build()), {
    enabled: false,
    refetchOnWindowFocus: false,
    refetchOnMount: false,
    staleTime: 0,
    cacheTime: 0,
  });

  let requestAuthorGet = useRequest(requestTemplates, rankedListGetQuery);

  let settings = settingsVars.get(url.getHost());

  // NAGYKER user personalization
  useEffect(() =>
  {
    if (settings.key !== 'NAGYKER') return;
    if (authChecking || !actualUser) return;

    requestAuthorGet.addRequest('ranked-get');

    requestAuthorGet.modifyRequest('ranked-get', (draft) =>
    {
      draft.body.section_params.slug = slug;
    });

    requestAuthorGet.modifyHeaders((headerObject) =>
    {
      headerObject['Authorization'] = `Bearer ${actualUser.token}`;
    });

    requestAuthorGet.commit();
  }, [authChecking, actualUser]);

  let list = getResponseById(rankedListGetQuery.data, 'ranked-get');

  return (
    <SikerlistaPageWrapper>
      <DynamicHead metadata={props.metadata} />
      <Header promo={HeaderPromo}></Header>
      <Content>
        <SiteColContainer>
          {list && (
            <>
              <PageTitle>{list.body.page_title}</PageTitle>
              <List>
                <Booklist>
                  <BookTable>
                    {list.body.products.map((book) => (
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
            </>
          )}
        </SiteColContainer>
      </Content>
      <Footer></Footer>
    </SikerlistaPageWrapper>
  );
}

export async function getStaticProps(context)
{
  const queryClient = new QueryClient();

  let requestTemplate = requestTemplates.requests['ranked-get'];
  requestTemplate.body.section_params.slug = context.params.slug;

  let request = {
    body: {
      request: [requestTemplate],
    },
  };

  await queryClient.prefetchQuery(`ranked-get-${context.params.slug}`, () => handleApiRequest(request));

  let queryData = queryClient.getQueryData();

  if (!queryData || !queryData.success)
    return {
      notFound: true,
      revalidate: 10,
    };

  const metadata = await getMetadata('/kereses')

  return {
    props: {
      dehydratedState: dehydrate(queryClient),
      slug: context.params.slug,
      metadata: metadata.length > 0 ? metadata[0].data : null,
    }, revalidate: 90
  };
}

export async function getStaticPaths()
{
  return {
    paths: [],
    fallback: 'blocking',
  };
}
