import dynamic from 'next/dynamic';
import { useEffect, useState } from 'react';
const HeaderPromo = dynamic(() => import('@components/headerPromo/headerPromo'), { ssr: false });
import { useQuery, QueryClient } from 'react-query';
import { dehydrate } from 'react-query/hydration';
const Header = dynamic(() => import('@components/header/header'));
const Content = dynamic(() => import('@components/content/content'));
const SiteColContainer = dynamic(() => import('@components/siteColContainer/siteColContainer'));
const BookListPagination = dynamic(() => import('@components/bookListPagination/bookListPagination'));
const AddButton = dynamic(() => import('@components/addButton/addButton'), { ssr: false });
const Footer = dynamic(() => import('@components/footer/footer'));
const BookTable = dynamic(() => import('@components/bookTable/bookTable'));
const BookCard = dynamic(() => import('@components/bookCard/bookCard'));
const ListHeader = dynamic(() => import('@components/listHeader/listHeader'));
import { handleApiRequest, getResponseById } from '@libs/api';
import useRequest from '@hooks/useRequest/useRequest';
import useUser from '@hooks/useUser/useUser';
import
  {
    Author,
    AuthorActions,
    AuthorImage,
    AuthorInfo,
    AuthorName,
    AuthorText,
    AuthorWrapper,
    Booklist,
    Col,
    Container,
    ListHeaderWrapper,
    List,
    Row,
    SzerzoPageWrapper,
    WishlistButtonWrapper,
    PaginantionWrapper,
  } from '@components/pages/szerzoPage.styled';
import DynamicHead from '@components/heads/DynamicHead';
import OptimizedImage from '@components/Images/OptimizedImage';
import url from '@libs/url';
import settingsVars from "@vars/settingsVars";


let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    'author-get': {
      method: 'GET',
      path: '/product',
      ref: 'list',
      request_id: 'author-get',
      body: {
        section: 'author',
        section_params: {
          slug: null,
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
    'author-user-get': {
      method: 'GET',
      path: '/product',
      ref: 'list',
      request_id: 'author-user-get',
      body: {
        section: 'author',
        section_params: {
          slug: null,
        },
        filters: {
          by_publishing: null,
          category: null,
          subcategory: null,
        },
        sort_by: null,
        page: 1,
        customer: false,
      },
    },
    'author-update': {
      method: 'POST',
      path: '/profile/authors',
      ref: 'customerAuthors',
      request_id: 'author-update',
      body: {
        author_id: null,
      },
    },
  },
};

export default function Szerzo(props)
{
  let { slug } = props;
  let settings = settingsVars.get(url.getHost());

  let { authChecking, actualUser } = useUser();
  let [followsAuthor, setFollowsAuthor] = useState(false);
  let [showPagination, setShowPagination] = useState(true);

  let queryAuthorGet = useQuery(['author-get', slug], () => handleApiRequest(requestAuthorGet.build()), {
    enabled: false,
    refetchOnWindowFocus: false,
    refetchOnMount: false,
    staleTime: 0,
    onSettled: (data) =>
    {
      let authorUserGetResponse = getResponseById(data, 'author-get');

      if (authorUserGetResponse?.success)
      {
        if (authorUserGetResponse.body.pagination.current_page > 1)
        {
          setList([...list, ...authorUserGetResponse.body.books]);
        } else
        {
          setList(authorUserGetResponse.body.books);
        }

        setPagination(authorUserGetResponse.body.pagination);
        setShowPagination(authorUserGetResponse.body.pagination.last_page ? false : true);
      }
    },
  });

  let queryAuthorUserGet = useQuery(['author-user-get', slug], () => handleApiRequest(requestAuthorUserGet.build()), {
    enabled: false,
    refetchOnWindowFocus: false,
    refetchOnMount: false,
    staleTime: 0,
    onSettled: (data) =>
    {
      let authorUserGetResponse = getResponseById(data, 'author-user-get');

      if (authorUserGetResponse?.success)
      {
        if (followsAuthor !== authorUserGetResponse.body.subscribed) setFollowsAuthor(authorUserGetResponse.body.subscribed);
      }
    },
  });

  let requestAuthorGet = useRequest(requestTemplates, queryAuthorGet);
  let requestAuthorUserGet = useRequest(requestTemplates, queryAuthorUserGet);

  let [list, setList] = useState(() =>
  {
    let authorGetResponse = getResponseById(queryAuthorGet.data, 'author-get');
    return authorGetResponse.success ? authorGetResponse.body.books : [];
  });

  let [pagination, setPagination] = useState(() =>
  {
    let authorGetResponse = getResponseById(queryAuthorGet.data, 'author-get');
    return authorGetResponse.success ? authorGetResponse.body.pagination : {};
  });

  function handleLoadMore()
  {
    // Changing the request builder object
    requestAuthorGet.addRequest('author-get');

    requestAuthorGet.modifyRequest('author-get', (draft) =>
    {
      draft.body.section_params.slug = slug;
      draft.body.page = pagination.current_page + 1;
    });

    if (actualUser?.type === 'user')
    {
      requestAuthorGet.modifyHeaders((headerObject) =>
      {
        headerObject['Authorization'] = `Bearer ${actualUser.token}`;
      });
    }

    requestAuthorGet.commit();
  }

  // Does the user follow the author?
  useEffect(() =>
  {
    if (authChecking) return;

    if (actualUser)
    {
      requestAuthorUserGet.addRequest('author-user-get');

      requestAuthorUserGet.modifyHeaders((currentHeader) =>
      {
        currentHeader['Authorization'] = `Bearer ${actualUser.token}`;
      });

      requestAuthorUserGet.modifyRequest('author-user-get', (currentRequest) =>
      {
        currentRequest.body.customer = true;
        currentRequest.body.section_params.slug = slug;
      });

      requestAuthorUserGet.commit();
    } else
    {
      followsAuthor && setFollowsAuthor(false);
    }
  }, [authChecking, actualUser]);

  // NAGYKER user personalization
  useEffect(() =>
  {
    if (settings.key !== 'NAGYKER') return;
    if (authChecking || !actualUser) return;

    requestAuthorGet.addRequest('author-get');

    requestAuthorGet.modifyRequest('author-get', (draft) =>
    {
      draft.body.section_params.slug = slug;
    });

    requestAuthorGet.modifyHeaders((headerObject) =>
    {
      headerObject['Authorization'] = `Bearer ${actualUser.token}`;
    });

    requestAuthorGet.commit();
  }, [authChecking, actualUser]);

  let author = getResponseById(queryAuthorGet.data, 'author-get')?.body;

  return (
    <SzerzoPageWrapper>
      <DynamicHead
        title={author.meta_title ? author.meta_title : author.title}
        description={author.meta_description ? author.meta_description : author.description}
        image={author.cover} />
      <Header promo={HeaderPromo}></Header>
      <Content>
        <Author>
          <AuthorWrapper>
            <Container className="container">
              <Row className="row">
                <Col className="col-xl-8 offset-xl-2">
                  <Row>
                    <AuthorInfo>
                      <AuthorImage>
                        {author.cover && (
                          <OptimizedImage src={author.cover} layout="responsive" width="183" height="183" alt={author.title} objectFit="cover"></OptimizedImage>
                        )}
                      </AuthorImage>
                      <AuthorName>{author.title}</AuthorName>
                    </AuthorInfo>
                    <AuthorText dangerouslySetInnerHTML={{ __html: author.description }}></AuthorText>
                    <AuthorActions>
                      <WishlistButtonWrapper>
                        <AddButton
                          buttonHeight="40px"
                          buttonWidth="100%"
                          fontSize="16px"
                          type="author"
                          itemId={author.id}
                          inCart={followsAuthor}
                          text="Feliratkozom a szerzőre"
                          textIcon="plus"
                          afterText="Feliratkozva a szerzőre"
                        ></AddButton>
                      </WishlistButtonWrapper>
                    </AuthorActions>
                  </Row>
                </Col>
              </Row>
            </Container>
          </AuthorWrapper>
        </Author>
        <SiteColContainer>
          <List>
            <ListHeaderWrapper>
              <ListHeader title={`${author.title} könyvei`}></ListHeader>
            </ListHeaderWrapper>
            <Booklist>
              <BookTable>
                {list.map((book) => (
                  <BookCard
                    itemId={book.id}
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
            {showPagination && (
              <PaginantionWrapper>
                <BookListPagination
                  itemCount={list.length}
                  currentPage={pagination.current_page}
                  lastPage={pagination.last_page}
                  perPage={pagination.per_page}
                  totalItems={pagination.total}
                  onClick={handleLoadMore}
                  loading={queryAuthorGet.isFetching}
                ></BookListPagination>
              </PaginantionWrapper>
            )}
          </List>
        </SiteColContainer>
      </Content>
      <Footer></Footer>
    </SzerzoPageWrapper>
  );
}

export async function getStaticProps({ params })
{
  const queryClient = new QueryClient();

  requestTemplates.requests['author-get'].body.section_params.slug = params.slug;

  await queryClient.prefetchQuery(['author-get', params.slug], () =>
    handleApiRequest({
      body: {
        request: [requestTemplates.requests['author-get']],
      },
    }),
  );

  return { props: { slug: params.slug, dehydratedState: dehydrate(queryClient) }, revalidate: 90 };
}

export async function getStaticPaths()
{
  return {
    paths: [],
    fallback: 'blocking',
  };
}
