import dynamic from 'next/dynamic';
import { useRef, useState, useEffect, useCallback } from 'react';
import { useRouter } from 'next/router';
import Link from 'next/link';
const Header = dynamic(() => import('@components/header/header'));
import PageTitle from '@components/pageTitle/pageTitle';
const Content = dynamic(() => import('@components/content/content'));
const SiteColContainer = dynamic(() => import('@components/siteColContainer/siteColContainer'));
const Footer = dynamic(() => import('@components/footer/footer'));
const BookCard = dynamic(() => import('@components/bookCard/bookCard'));
const HeaderPromo = dynamic(() => import('@components/headerPromo/headerPromo'));
const InputText = dynamic(() => import('@components/inputText/inputText'));
const Icon = dynamic(() => import('@components/icon/icon'));
import colors from '@vars/colors';
import useMediaQuery from '@hooks/useMediaQuery/useMediaQuery';
import breakpoints from '@vars/breakpoints';
import { useResizeObserver } from '@hooks/useResizeObserver';
import { useQuery, useQueryClient } from 'react-query';
import { handleApiRequest, getResponseById, getMetadata } from '@libs/api';
import useRequest from '@hooks/useRequest/useRequest';
const BookListPagination = dynamic(() => import('@components/bookListPagination/bookListPagination'));

import {
  AuthorName,
  AuthorContainer,
  AuthorHeaderWrapper,
  AuthorNamesWrapper,
  AuthorNameWrapper,
  AuthorWrapper,
  AuthorLink,
  GroupWrapper,
  InputWrapper,
  ItemWrapper,
  KeresesPageComponent,
  ListContainer,
  SearchContainer,
  ListHeaderWrapper,
  Title,
  IconWrapper,
  InputIcon,
  Pagination,
  BooksSection,
} from '@components/pages/keresesPage.styled';
import useUser from '@hooks/useUser/useUser';
import DynamicHead from '@components/heads/DynamicHead';

const REQUEST_ID = 'search-post';

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },

  requests: {
    'search-post': {
      method: 'POST',
      path: '/search',
      ref: 'search',
      request_id: REQUEST_ID,
      body: {
        term: null,
        in_header: null,
        book_page: null,
        ebook_page: null,
      },
    },
  },
};

export default function KeresesPage({metadata}) {
  let router = useRouter();
  let defaultConfig = {
    breakpoints: [
      {
        width: 0,
        count: 1,
      },
      {
        width: 391,
        count: 2,
      },
      {
        width: 700,
        count: 3,
      },
      {
        width: 900,
        count: 4,
      },
      {
        width: 1200,
        count: 5,
      },
    ],
  };

  let {authChecking, actualUser} = useUser();
  let listContainerRef = useRef(null);
  let queryClient = useQueryClient();
  let [config] = useState({...defaultConfig});
  let [elemCount, setElemCount] = useState(0);
  let [openBookSection, setOpenBookSection] = useState(true);
  let [openEBookSection, setOpenEBookSections] = useState(true);
  let [openAuthorSection, setOpenAuthorSection] = useState(true);
  let [books, setBooks] = useState([]);
  let [booksPagination, setBooksPagination] = useState({});
  let [eBooks, setEBooks] = useState([]);
  let [eBooksPagination, setEBooksPagination] = useState({});
  let [authors, setAuthors] = useState([]);
  let [authorsForMobile, setAuthorsForMobile] = useState([]);
  let [searchInput, setSearchInput] = useState('');
  let [searchResult, setSearchResult] = useState(0);

  let listerWidth = useResizeObserver(listContainerRef);
  let isMinLG = useMediaQuery(`(min-width: ${breakpoints.min.lg})`);

  let querySearch = useQuery(REQUEST_ID, () => handleApiRequest(searchRequest.build()), {
    enabled: false,
    refetchOnWindowFocus: false,
    refetchOnMount: false,
    staleTime: 0,
    onSuccess: (data) => {
      let searchResponse = getResponseById(data, REQUEST_ID);
      if (searchResponse && searchResponse.success) {
        if (searchResponse.body.books.pagination.current_page > 1) {
          setBooks([...books, ...(searchResponse?.body?.books?.products || [])]);
        } else {
          setBooks([...(searchResponse?.body?.books?.products || [])]);
        }

        if (searchResponse.body.ebooks.pagination.current_page > 1) {
          setEBooks([...eBooks, ...(searchResponse?.body?.ebooks?.products || [])]);
        } else {
          setEBooks([...(searchResponse?.body?.ebooks?.products || [])]);
        }
        setSearchResult(searchResponse?.body?.total_results);
        setBooksPagination(searchResponse?.body?.books?.pagination);
        setEBooksPagination(searchResponse?.body?.ebooks?.pagination);

        setAuthors(searchResponse?.body?.authors || []);
        setAuthorsForMobile(getDivideArray(searchResponse?.body?.authors || [], 5));
      }
    },
  });

  let searchRequest = useRequest(requestTemplates, querySearch);
  searchRequest.addRequest(REQUEST_ID);

  let createSearchRequest = useCallback(() => {
    queryClient.cancelQueries(REQUEST_ID);

    if (!authChecking && actualUser) {
      searchRequest.modifyHeaders((headerObject) => {
        headerObject['Authorization'] = `Bearer ${actualUser.token}`;
      });
    }

    searchRequest.modifyRequest(REQUEST_ID, (currentRequest) => {
      currentRequest.body.term = searchInput;
    });

    searchRequest.commit();
  }, [searchInput, authChecking, actualUser]);

  let getDivideArray = useCallback((array, parts) => {
    let result = [];

    for (let i = 0, j = array.length; i < j; i += parts) {
      let temporary = array.slice(i, i + parts);
      result.push(temporary);
    }

    return result;
  });

  let handleLoadMoreClick = useCallback(
    (section) => {
      searchRequest.modifyRequest(REQUEST_ID, (currentRequest) => {
        currentRequest.body.term = searchInput;
        currentRequest.body[section] = section === 'book_page' ? booksPagination.current_page + 1 : eBooksPagination.current_page + 1;
      });

      if (!authChecking && actualUser) {
        searchRequest.modifyHeaders((headerObject) => {
          headerObject['Authorization'] = `Bearer ${actualUser.token}`;
        });
      }

      searchRequest.commit();
    },
    [searchInput, booksPagination, eBooksPagination, authChecking, actualUser],
  );

  useEffect(() => {
    if (isMinLG) {
      setOpenBookSection(true);
      setOpenEBookSections(true);
      setOpenAuthorSection(true);
    }
  }, [isMinLG]);

  useEffect(() => {
    if (!listerWidth) return;

    setTimeout(() => {
      let nextCount;

      config.breakpoints.forEach((breakpoint) => {
        if (listerWidth >= parseInt(breakpoint.width)) {
          nextCount = breakpoint.count;
        }
      });
      if (nextCount) setElemCount(nextCount);
    }, 0);
  }, [listerWidth, listContainerRef]);

  useEffect(() => {
    if (searchInput?.length >= 3 || router.query.length) {
      createSearchRequest();
    }
  }, [searchInput]);

  useEffect(() => {
    if (!router.query.q) return;
    setSearchInput(router.query.q);
  }, [router.query.q]);

  return (
    <KeresesPageComponent>
      <DynamicHead metadata={metadata}></DynamicHead>
      <Header promo={HeaderPromo}></Header>
      <Content>
        <SiteColContainer>
          <PageTitle>Találatok</PageTitle>
          <SearchContainer hasFound={books.length > 0 && eBooks.length > 0 && authors.length > 0}>
            <InputWrapper>
              <InputText
                name="input-search-term"
                value={searchInput}
                onChange={(e) => setSearchInput(e.target.value)}
                onReset={() => setSearchInput('')}
                button="search"
                iconColor="green"
                placeholder="Keresés könyvek és szerzők között..."
                height={60}
                reset
                sub={`${searchInput.length > 0 ? searchResult + ' találat a ' + ' kifejezésre ' + searchInput : ''} `}
              ></InputText>
            </InputWrapper>
          </SearchContainer>
          <BooksSection>
            <ListHeaderWrapper onClick={() => handleOpenSection('books')} border open={openBookSection}
                               hasBooks={books.length > 0}>
              <Title>Könyvek</Title>
              <InputIcon isVisible={isMinLG}>
                <IconWrapper>
                  <Icon open={openBookSection} type="chevron-right" iconWidth="10px" iconColor={colors.monza}></Icon>
                </IconWrapper>
              </InputIcon>
            </ListHeaderWrapper>
            <ListContainer open={openBookSection} ref={listContainerRef} hasBooks={books.length > 0}>
              {books?.map((book, idx) => (
                <GroupWrapper key={`${book.id}-${idx}`} elemCount={elemCount}>
                  <ItemWrapper>
                    <BookCard
                      itemId={book.id}
                      imageSrc={book.cover}
                      title={book.title}
                      author={book.authors && book.authors.split(',').join(', ')}
                      originalPrice={book.price_list}
                      price={book.price_sale}
                      isNew={book.is_new}
                      slug={book.slug}
                      prefetch={false}
                      discount={book.discount_percent}
                      purchaseType={book.state}
                      bookType={book.type === 0 ? 'book' : 'ebook'}
                    ></BookCard>
                  </ItemWrapper>
                </GroupWrapper>
              ))}
            </ListContainer>
            <Pagination hasBooks={books.length > 0}>
              <BookListPagination
                itemCount={books.length}
                currentPage={booksPagination?.current_page}
                lastPage={booksPagination?.last_page}
                perPage={booksPagination?.per_page}
                totalItems={booksPagination?.total}
                onClick={() => {
                  handleLoadMoreClick('book_page');
                }}
              ></BookListPagination>
            </Pagination>
          </BooksSection>
          <BooksSection>
            <ListHeaderWrapper onClick={() => handleOpenSection('ebooks')} border open={openEBookSection}
                               hasBooks={eBooks.length > 0}>
              <Title>E-könyvek</Title>
              <InputIcon isVisible={isMinLG}>
                <IconWrapper>
                  <Icon open={openEBookSection} type="chevron-right" iconWidth="10px" iconColor={colors.monza}></Icon>
                </IconWrapper>
              </InputIcon>
            </ListHeaderWrapper>
            <ListContainer open={openEBookSection} ref={listContainerRef} hasBooks={books.length > 0}>
              {eBooks?.map((book, idx) => (
                <GroupWrapper key={`${book.id}-${idx}`} elemCount={elemCount}>
                  <ItemWrapper>
                    <BookCard
                      itemId={book.id}
                      imageSrc={book.cover}
                      title={book.title}
                      author={book.authors && book.authors.split(',').join(', ')}
                      originalPrice={book.price_list}
                      price={book.price_sale}
                      isNew={book.is_new}
                      slug={book.slug}
                      prefetch={false}
                      discount={book.discount_percent}
                      purchaseType={book.state}
                      bookType={book.type === 0 ? 'book' : 'ebook'}
                    ></BookCard>
                  </ItemWrapper>
                </GroupWrapper>
              ))}
            </ListContainer>
            <Pagination hasBooks={eBooks.length > 0}>
              <BookListPagination
                itemCount={eBooks.length}
                currentPage={eBooksPagination?.current_page}
                lastPage={eBooksPagination?.last_page}
                perPage={eBooksPagination?.per_page}
                totalItems={eBooksPagination?.total}
                onClick={() => {
                  handleLoadMoreClick('ebook_page');
                }}
              ></BookListPagination>
            </Pagination>
          </BooksSection>
          <BooksSection>
            <AuthorHeaderWrapper onClick={() => handleOpenSection('authors')} border open={openAuthorSection}
                                 hasBooks={authors.length > 0}>
              <Title>Szerzők</Title>
              <InputIcon isVisible={isMinLG}>
                <IconWrapper>
                  <Icon open={openAuthorSection} type="chevron-right" iconWidth="10px" iconColor={colors.monza}></Icon>
                </IconWrapper>
              </InputIcon>
            </AuthorHeaderWrapper>
            {isMinLG ? (
              <AuthorContainer open={openAuthorSection}>
                {authorsForMobile?.map((group, idx) => (
                  <GroupWrapper key={`${group.key}-${idx}`}>
                    <ItemWrapper>
                      {group?.map((author) => (
                        <AuthorNameWrapper key={author.id}>
                          <AuthorName>
                            <Link href={`/szerzo/${author.slug}`} prefetch={false} passHref legacyBehavior>
                              <AuthorLink>{author.title}</AuthorLink>
                            </Link>
                          </AuthorName>
                        </AuthorNameWrapper>
                      ))}
                    </ItemWrapper>
                  </GroupWrapper>
                ))}
              </AuthorContainer>
            ) : (
              <AuthorWrapper>
                <AuthorNamesWrapper open={openAuthorSection}>
                  {authors?.map((author) => (
                    <AuthorNameWrapper key={author.id}>
                      <AuthorName>
                        <Link href={`/szerzo/${author.slug}`} prefetch={false} passHref legacyBehavior>
                          <AuthorLink>{author.title}</AuthorLink>
                        </Link>
                      </AuthorName>
                    </AuthorNameWrapper>
                  ))}
                </AuthorNamesWrapper>
              </AuthorWrapper>
            )}
          </BooksSection>
        </SiteColContainer>
      </Content>
      <Footer></Footer>
    </KeresesPageComponent>
  );

  function handleOpenSection(section) {
    if (isMinLG) return;

    switch (section) {
      case 'books':
        setOpenBookSection(!openBookSection);
        break;
      case 'ebooks':
        setOpenEBookSections(!openEBookSection);
        break;

      default:
        setOpenAuthorSection(!openAuthorSection);
        break;
    }
  }
}

KeresesPage.getInitialProps = async () =>
{
  const metadata = await getMetadata('/kereses')
  return { metadata: metadata.length > 0 ? metadata[0].data : null }
}