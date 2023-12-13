import dynamic from 'next/dynamic';
import { useState, useEffect, useCallback } from 'react';
import Link from 'next/link';
const PageHead = dynamic(() => import('@components/pageHead/pageHead'));
import currency from '@libs/currency';
const Header = dynamic(() => import('@components/header/header'));
const Content = dynamic(() => import('@components/content/content'));
const PageTitle = dynamic(() => import('@components/pageTitle/pageTitle'));
const ProfileListImage = dynamic(() => import('@components/profileListImage/profileListImage'));
const ProfileEmpty = dynamic(() => import('@components/profileEmpty/profileEmpty'));
const SiteColContainer = dynamic(() => import('@components/siteColContainer/siteColContainer'));
const ProfileNavigator = dynamic(() => import('@components/profileNavigator/profileNavigator'));
const ProfileDataTitle = dynamic(() => import('@components/profileDataTitle/profileDataTitle'));
const BookDownloadLink = dynamic(() => import('@components/bookDownloadLink/bookDownloadLink'));
const Footer = dynamic(() => import('@components/footer/footer'));
import useProtectedRoute from '@hooks/useProtectedRoute/useProtectedRoute';
const BookListPagination = dynamic(() => import('@components/bookListPagination/bookListPagination'));
import { useQuery } from 'react-query';
import { handleApiRequest, getResponseById } from '@libs/api';
import useRequest from '@hooks/useRequest/useRequest';
import {
  AuthorWrapper,
  DownloadLink,
  DownloadLinks,
  DownloadTitle,
  List,
  ListItem,
  ListItemAuthor,
  ListItemCurrentPrize,
  ListItemDownload,
  ListItemImageWrapper,
  ListItemOriginalPrize,
  ListItemText,
  ListItemTextWrapper,
  ListItemTitle,
  PageContent,
  PaginantionWrapper,
  ProfilEkonyveimPageComponent,
  ProfileData,
  ProfileNavigatorWrapper,
} from '@components/pages/profilEkonyveimPage.styled';

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    profileEbooks: {
      method: 'GET',
      path: '/profile/ebooks',
      ref: 'customerEbooks',
      request_id: 'profile-ebooks-get',
      body: {
        page: null,
      },
    },
  },
};

export default function ProfilEkonyveimPage() {
  let { user, authChecking } = useProtectedRoute();

  let [books, setBooks] = useState();
  let [pagination, setPagination] = useState();
  let [showPagination, setShowPagination] = useState(true);

  let ebooksGetQuery = useQuery('profile-ebooks-get', () => handleApiRequest(requestEbooksGet.build()), {
    enabled: false,
    refetchOnWindowFocus: false,
    refetchOnMount: true,
    staleTime: 0,
    onSuccess: (data) => {
      let profileOrdersResponse = getResponseById(data, 'profile-ebooks-get');

      if (profileOrdersResponse) {
        if (profileOrdersResponse.success) {
          // Success
          if (profileOrdersResponse.body.pagination.current_page > 1) {
            setBooks([...books, ...profileOrdersResponse.body.ebooks]);
          } else {
            setBooks([...profileOrdersResponse.body.ebooks]);
          }

          setPagination({ ...profileOrdersResponse.body.pagination });
        }
      }
    },
  });

  let requestEbooksGet = useRequest(requestTemplates, ebooksGetQuery);
  requestEbooksGet.addRequest('profileEbooks');

  let handleLoadMoreClick = useCallback(() => {
    requestEbooksGet.modifyHeaders((currentHeader) => {
      currentHeader['Authorization'] = `Bearer ${user.token}`;
    });

    requestEbooksGet.modifyRequest('profileEbooks', (currentRequest) => {
      currentRequest.body.page = pagination.current_page + 1;
    });

    requestEbooksGet.commit();
  });

  // Pagination
  useEffect(() => {
    if (!pagination) return;

    if (pagination.last_page && pagination.current_page === 1) {
      if (showPagination) setShowPagination(false);
    } else {
      if (!showPagination) setShowPagination(true);
    }
  }, [pagination]);

  useEffect(() => {
    if (!user) return;

    requestEbooksGet.modifyHeaders((currentHeader) => {
      currentHeader['Authorization'] = `Bearer ${user.token}`;
    });

    requestEbooksGet.commit();
  }, [user]);

  if (!user || authChecking) return <div>checking</div>;

  return (
    <ProfilEkonyveimPageComponent>
      <PageHead></PageHead>
      <Header></Header>
      <Content>
        <SiteColContainer>
          <PageTitle className="d-none d-md-block">Profilom</PageTitle>
          <PageContent className="row">
            <ProfileNavigatorWrapper className="col-md-4 col-lg-3 d-none d-md-block">
              <ProfileNavigator selected={7}></ProfileNavigator>
            </ProfileNavigatorWrapper>
            <ProfileData className="col-md-8 col-lg-7 col-xl-6 col-xxl-5 offset-0 offset-lg-1">
              <ProfileDataTitle>E-könyveim</ProfileDataTitle>
              {ebooksGetQuery.isFetching && !books && <ProfileEmpty>Töltődik...</ProfileEmpty>}
              {typeof books !== 'undefined' && books.length < 1 && <ProfileEmpty>Még nincs egy e-könyved sem</ProfileEmpty>}
              {typeof books !== 'undefined' && (
                <>
                  <List>
                    {books.map((book) => (
                      <ListItem key={book.id} {...book}>
                        <ListItemImageWrapper>
                          <ProfileListImage slug={book.slug} cover={book.cover} type={book.type}></ProfileListImage>
                        </ListItemImageWrapper>
                        <ListItemText>
                          <ListItemTextWrapper>
                            <ListItemTitle>
                              <Link href={`/konyv/${book.slug}`} passHref>
                                {book.title}
                              </Link>
                            </ListItemTitle>
                            <ListItemAuthor>{book?.authors?.length > 0 && <AuthorWrapper>{book.authors[0].title}</AuthorWrapper>}</ListItemAuthor>
                            <ListItemOriginalPrize>{currency.format(book.price_list)}</ListItemOriginalPrize>
                            <ListItemCurrentPrize>{currency.format(book.price_sale)}</ListItemCurrentPrize>
                            <ListItemDownload>
                              <DownloadTitle>Letöltés</DownloadTitle>
                              <DownloadLinks>
                                <DownloadLink>
                                  <BookDownloadLink id={book.id} size={book.mobi_size} type="mobi">
                                    mobi
                                  </BookDownloadLink>
                                </DownloadLink>
                                <DownloadLink>
                                  <BookDownloadLink id={book.id} size={book.epub_size} type="epub">
                                    epub
                                  </BookDownloadLink>
                                </DownloadLink>
                              </DownloadLinks>
                            </ListItemDownload>
                          </ListItemTextWrapper>
                        </ListItemText>
                      </ListItem>
                    ))}
                  </List>
                  {pagination && showPagination && (
                    <PaginantionWrapper>
                      <BookListPagination
                        itemCount={books.length}
                        currentPage={pagination.current_page}
                        lastPage={pagination.last_page}
                        perPage={pagination.per_page}
                        totalItems={pagination.total}
                        onClick={handleLoadMoreClick}
                        loading={ebooksGetQuery.isFetching}
                      ></BookListPagination>
                    </PaginantionWrapper>
                  )}
                </>
              )}
            </ProfileData>
          </PageContent>
        </SiteColContainer>
      </Content>
      <Footer></Footer>
    </ProfilEkonyveimPageComponent>
  );
}