import { useState, useCallback, useEffect, useRef } from 'react';
import dynamic from 'next/dynamic';
import Link from 'next/link';
import { useRouter } from 'next/router';
import _cloneDeep from 'lodash/cloneDeep';
import { useQuery, useMutation, QueryClient } from 'react-query';
import { dehydrate } from 'react-query/hydration';
import useMediaQuery from '@hooks/useMediaQuery/useMediaQuery';
import breakpoints from '@vars/breakpoints';

const Rating = dynamic(() => import('@components/rating/rating'));
const Header = dynamic(() => import('@components/header/header'));
const Content = dynamic(() => import('@components/content/content'));
const SiteColContainer = dynamic(() => import('@components/siteColContainer/siteColContainer'));
const Footer = dynamic(() => import('@components/footer/footer'));
const AddButton = dynamic(() => import('@components/addButton/addButton'));
const BookScrollList = dynamic(() => import('@components/bookScrollList/bookScrollList'));
const TextReveal = dynamic(() => import('@components/textReveal/textReveal'));
const BookAuthorSection = dynamic(() => import('@components/bookAuthorSection/bookAuthorSection'));
const BookCategoryNav = dynamic(() => import('@components/bookCategoryNav/bookCategoryNav'));
const IconBox = dynamic(() => import('@components/iconBox/iconBox'));
import Currency from '@libs/currency';
import { analytics } from '@libs/analytics';
import { event as fbqEvent } from '@libs/fbpixel';
import { handleApiRequest, getResponseById, getMetadata } from '@libs/api';
import useRequest from '@hooks/useRequest/useRequest';
import useUser from '@hooks/useUser/useUser';

const HeaderPromo = dynamic(() => import('@components/headerPromo/headerPromo'));
const CommentList = dynamic(() => import('@components/commentList/commentList'));
import ImageBadge from '@assets/images/elements/badge.svg';

let Overlay = dynamic(() => import('@components/overlay/overlay'));
let OverlayCard = dynamic(() => import('@components/overlayCard/overlayCard'));
let OverlayCardContentConfirmation = dynamic(() => import('@components/overlayCardContentConfirmation/overlayCardContentConfirmation'));
import {
  ActionButtonWrapper,
  Author,
  AuthorName,
  CommentsWrapper,
  CommentsWrapperCol,
  CommentsWrapperContainer,
  CommentsWrapperRow,
  IconBoxWrapper,
  InfoLabel,
  KonyvPageWrapper,
  ListWrapper,
  Lists,
  Price,
  PriceTag,
  PriceTagWrapper,
  PriceValues,
  PriceValuesDiscount,
  PriceValuesOriginal,
  Product,
  ProductActions,
  ProductAuthor,
  ProductContent,
  ProductContentWrapper,
  ProductDescriptionTitle,
  ProductDescriptionWrapper,
  ProductImage,
  ProductImageType,
  ProductImageWrapper,
  ProductInfo,
  ProductMeta,
  ProductMetaItem,
  ProductNav,
  ProductRatingWrapper,
  ProductTag,
  ProductTagIcon,
  ProductTagNumber,
  ProductTagText,
  ProductTags,
  ProductTitle,
  Test,
  WishlistButtonWrapper,
  PriceValueContainer,
  ProductDiscountInfo,
} from '@components/pages/konyvPage.styled';
import DynamicHead from '@components/heads/DynamicHead';
import settingsVars from "@vars/settingsVars";
import url from '@libs/url';

const OptimizedImage = dynamic(() => import('@components/Images/OptimizedImage'));

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    'book-get': {
      method: 'GET',
      path: '/product',
      ref: 'show',
      request_id: 'book-get',
      body: {
        slug: null,
      },
    },
    'book-user-get': {
      method: 'GET',
      path: '/product',
      ref: 'show',
      request_id: 'book-user-get',
      body: {
        slug: null,
        customer: true,
      },
    },
    'book-wishlist-update': {
      method: 'POST',
      path: '/profile/wishlist',
      ref: 'customerWishlist',
      request_id: 'book-wishlist-update',
      body: {
        product_id: null,
      },
    },
    'book-author-update': {
      method: 'POST',
      path: '/profile/authors',
      ref: 'customerAuthors',
      request_id: 'book-author-update',
      body: {
        author_id: null,
      },
    },
    'book-comment-get': {
      method: 'GET',
      path: '/comments',
      ref: 'get',
      request_id: 'book-comment-get',
      body: {
        entity_type: 'product',
        entity_id: null,
        customer: false,
        page: 1,
        slug: null,
      },
    },
    'book-comment-add': {
      method: 'POST',
      path: '/comments',
      ref: 'add',
      request_id: 'book-comment-add',
      body: {
        entity_type: null,
        entity_id: null,
        comment: null,
      },
    },
    'book-comment-delete': {
      method: 'DELETE',
      path: '/comments',
      ref: 'delete',
      request_id: 'book-comment-delete',
      body: {
        comment_id: null,
        entity_type: 'product',
      },
    },
    'book-comment-update': {
      method: 'PUT',
      path: '/comments',
      ref: 'add',
      request_id: 'book-comment-update',
      body: {
        comment_id: null,
        comment: null,
      },
    },
  },
};

export default function KonyvPage(props) {
  let { slug } = props;
  let commentListRef = useRef();
  let isMaxMd = useMediaQuery(`(max-width: ${breakpoints.max.md})`);
  let { actualUser, authChecking } = useUser();
  let router = useRouter();

  let [userBook, setUserBook] = useState();
  let [comments, setComments] = useState();
  let [commentDeleteConfirmId, setCommentDeleteConfirmId] = useState(null);
  let [commentDeleteConfirmOpen, setCommentDeleteConfirmOpen] = useState(false);

  let queryBook = useQuery(['book-get', slug], () => handleApiRequest(requestBook.build()), {
    refetchOnWindowFocus: false,
    refetchOnMount: false,
    staleTime: 0,
  });

  let queryUserBook = useQuery(['book-user-get', slug], () => handleApiRequest(requestUserBook.build()), {
    enabled: false,
    refetchOnWindowFocus: false,
    refetchOnMount: false,
    staleTime: 0,
    onSuccess: (data) => {
      let bookUserBookResponse = getResponseById(data, 'book-user-get');
      let bookCommentGetResponse = getResponseById(data, 'book-comment-get');

      if (bookUserBookResponse?.success) {
        setUserBook(bookUserBookResponse.body);
      }

      if (bookCommentGetResponse?.success) {
        setComments(bookCommentGetResponse.body);
      }
    },
  });

  let queryCommentGet = useQuery(['book-comment-get', slug], () => handleApiRequest(requestCommentGet.build()), {
    enabled: false,
    staleTime: 0,
    onSuccess: (data) => {
      let bookCommentGetResponse = getResponseById(data, 'book-comment-get');

      if (bookCommentGetResponse?.success) {
        if (bookCommentGetResponse.body.pagination.current_page > 1) {
          let newComments = _cloneDeep(comments);

          newComments.comments = [...newComments.comments, ...bookCommentGetResponse.body.comments];
          newComments.pagination = bookCommentGetResponse.body.pagination;

          setComments(newComments);
        } else {
          setComments(bookCommentGetResponse.body);
        }
      }
    },
  });

  let queryCommentAdd = useMutation('book-comment-add', (requestUpdateBuild) => handleApiRequest(requestUpdateBuild), {
    onSuccess: (data) => {
      let bookCommentAddResponse = getResponseById(data, 'book-comment-add');

      if (bookCommentAddResponse?.success) {
        setComments(bookCommentAddResponse.body);
        commentListRef.current.clearInput();
      }
    },
  });

  let queryCommentDelete = useMutation('book-comment-delete', (requestUpdateBuild) => handleApiRequest(requestUpdateBuild), {
    onSuccess: (data) => {
      let bookCommentDeleteResponse = getResponseById(data, 'book-comment-delete');

      if (bookCommentDeleteResponse?.success) {
        setComments(bookCommentDeleteResponse.body);
      }
    },
  });

  let queryCommentUpdate = useMutation('book-comment-update', (requestUpdateBuild) => handleApiRequest(requestUpdateBuild), {
    onSuccess: (data) => {
      let bookCommentUpdateResponse = getResponseById(data, 'book-comment-update');

      if (bookCommentUpdateResponse) {
        if (bookCommentUpdateResponse.success) {
          let newComments = _cloneDeep(comments);

          newComments.comments.forEach((comment) => {
            if (comment.id === bookCommentUpdateResponse.body.id) {
              comment.comment = bookCommentUpdateResponse.body.comment;
            }
          });

          setComments(newComments);
        }
      }
    },
  });

  let requestBook = useRequest(requestTemplates, queryBook);
  let requestUserBook = useRequest(requestTemplates, queryUserBook);

  let requestCommentGet = useRequest(requestTemplates, queryCommentGet);
  let requestCommentAdd = useRequest(requestTemplates, queryCommentAdd);
  let requestCommentDelete = useRequest(requestTemplates, queryCommentDelete);
  let requestCommentUpdate = useRequest(requestTemplates, queryCommentUpdate);
  requestBook.addRequest('book-get', 'book-comment-get');

  requestCommentGet.addRequest('book-comment-get');
  requestCommentAdd.addRequest('book-comment-add');
  requestCommentDelete.addRequest('book-comment-delete');
  requestCommentUpdate.addRequest('book-comment-update');

  let handleCommentSubmit = useCallback(
    (e, comment) => {
      e.preventDefault();
      if (!actualUser) return;

      requestCommentAdd.modifyHeaders((currentHeader) => {
        currentHeader['Authorization'] = `Bearer ${actualUser.token}`;
      });

      requestCommentAdd.modifyRequest('book-comment-add', (currentRequest) => {
        currentRequest.body.entity_type = 'product';
        currentRequest.body.entity_id = book.id;
        currentRequest.body.comment = comment;
      });

      requestCommentAdd.commit();
    },
    [actualUser],
  );

  let handleCommentEdit = useCallback(
    (id, comment) => {
      if (!actualUser) return;

      requestCommentUpdate.modifyHeaders((currentHeader) => {
        currentHeader['Authorization'] = `Bearer ${actualUser.token}`;
      });

      requestCommentUpdate.modifyRequest('book-comment-update', (currentRequest) => {
        currentRequest.body.comment_id = id;
        currentRequest.body.comment = comment;
      });

      requestCommentUpdate.commit();
    },
    [actualUser],
  );

  let handleCommentDeleteClick = useCallback((id) => {
    setCommentDeleteConfirmId(id);
    setCommentDeleteConfirmOpen(true);
  }, []);

  let handleCommentDelete = useCallback(() => {
    requestCommentDelete.modifyHeaders((currentHeader) => {
      currentHeader['Authorization'] = `Bearer ${actualUser.token}`;
    });

    requestCommentDelete.modifyRequest('book-comment-delete', (currentRequest) => {
      currentRequest.body.comment_id = commentDeleteConfirmId;
    });

    requestCommentDelete.commit();

    setCommentDeleteConfirmOpen(false);
    setCommentDeleteConfirmId(null);
  }, [actualUser, commentDeleteConfirmId]);

  let handleCommentLoadMoreClick = useCallback(() => {
    requestCommentGet.modifyRequest('book-comment-get', (currentRequest) => {
      currentRequest.body.slug = slug;
      currentRequest.body.page = comments.pagination.current_page + 1;
    });

    requestCommentGet.commit();
  }, [slug, comments]);

  let handleCommentDeleteConfirmClose = useCallback(() => {
    setCommentDeleteConfirmOpen(false);
    setCommentDeleteConfirmId(null);
  }, []);

  useEffect(() => {
    if (authChecking) return;

    requestUserBook.resetRequest();

    // There is user
    if (actualUser?.type === 'user') {
      requestUserBook.addRequest('book-user-get', 'book-comment-get');

      requestUserBook.modifyHeaders((currentHeader) => {
        currentHeader['Authorization'] = `Bearer ${actualUser.token}`;
      });

      requestUserBook.modifyRequest('book-user-get', (currentRequest) => {
        currentRequest.body.slug = slug;
      });

      requestUserBook.modifyRequest('book-comment-get', (currentRequest) => {
        currentRequest.body.slug = slug;
      });

      requestUserBook.commit();
    }

    // Never seen a user in my life
    else {
      if (!comments) {
        requestUserBook.addRequest('book-comment-get');

        requestUserBook.modifyRequest('book-comment-get', (currentRequest) => {
          currentRequest.body.slug = slug;
        });

        requestUserBook.commit();
      }

      setUserBook(undefined);
    }
  }, [actualUser, authChecking]);

  useEffect(() => {
    if (!queryBook.data) return;

    let book = getResponseById(queryBook.data, 'book-get')?.body;

    analytics.addItemView({
      id: book.id,
      name: book.title,
      list_name: router.route,
      brand: null,
      category: book.type === 0 ? 'book' : 'ebook',
      variant: book.type === 0 ? 'book' : 'ebook',
      list_position: 1,
      quantity: 1,
      price: book.price_sale,
    });

    fbqEvent('ViewContent', {
      content_name: book.title,
      content_category: book.type === 0 ? 'book' : 'ebook',
      content_ids: String(book.id),
      content_type: 'product',
      value: `"${book.price_sale}"`,
      currency: 'HUF',
    });
  }, [queryBook.data]);

  // NAGYKER user personalization
  useEffect(() => {
    if (settings.key !== 'NAGYKER') return;
    if (authChecking || !actualUser) return;

    requestBook.modifyRequest('book-get', (draft) => {
      draft.body.slug = slug;
    });

    requestBook.modifyHeaders((headerObject) => {
      headerObject['Authorization'] = `Bearer ${actualUser.token}`;
    });

    requestBook.commit();
  }, [authChecking, actualUser]);

  let book = getResponseById(queryBook.data, 'book-get')?.body;
  const originalPrice = userBook?.price_list ? userBook?.price_list : book.price_list;
  const discountedPrice = userBook?.price_sale ? userBook?.price_sale : book.price_sale;

  let settings = settingsVars.get(url.getHost());

  return (
    <KonyvPageWrapper>
      <DynamicHead
        title={book.meta_title ? book.meta_title : book.title}
        description={book.meta_description ? book.meta_description : book.description}
        image={book.cover}
      >
        <meta name="yuspItemId" content={book.id} />
      </DynamicHead>
      <Header promo={HeaderPromo}></Header>
      {commentDeleteConfirmOpen && (
        <Overlay fixed={false}>
          <OverlayCard onClose={handleCommentDeleteConfirmClose}>
            <OverlayCardContentConfirmation
              title="Biztosan törölni szeretnéd a hozzászólásod?"
              submitText="Törlés"
              cancelText="Mégse"
              onSubmit={handleCommentDelete}
              onCancel={handleCommentDeleteConfirmClose}
            ></OverlayCardContentConfirmation>
          </OverlayCard>
        </Overlay>
      )}
      <Test></Test>
      <Content>
        <SiteColContainer>
          <Product className="row">
            <ProductInfo className="col-md-3 order-2 order-md-1">
              <ProductImage>
                <ProductImageWrapper>
                  <OptimizedImage src={book.cover} layout="intrinsic" width={363} height={400} objectFit="contain" alt=""></OptimizedImage>
                </ProductImageWrapper>
                {book.type === 1 && <ProductImageType>e-könyv</ProductImageType>}
              </ProductImage>
              <ProductMeta>
                <ProductMetaItem>
                  <strong>ISBN:</strong> {book.isbn}
                </ProductMetaItem>
                {book.publisher && (
                  <ProductMetaItem>
                    <strong>Kiadó:</strong> {book.publisher}
                  </ProductMetaItem>
                )}
                {book.published_at &&
                  (<ProductMetaItem>
                    <strong>{new Date(book.published_at) > Date.now() ? 'Várható megjelenés' : 'Megjelenés'}:</strong> {new Date(book.published_at) > Date.now() ? book.published_at : new Date(book.published_at).getFullYear()}
                  </ProductMetaItem>
                  )}
                {(book.release_year && !book.published_at) &&
                  (<ProductMetaItem>
                    <strong>{new Date(book.release_year + '') > Date.now() ? 'Várható megjelenés' : 'Megjelenés'}:</strong> {book.release_year}
                  </ProductMetaItem>
                  )}
                {book.number_of_pages != null && (
                  <ProductMetaItem>
                    <strong>Oldalszám:</strong> {book.number_of_pages}
                  </ProductMetaItem>
                )}
                {book.language && (
                  <ProductMetaItem>
                    <strong>Nyelv:</strong> {book.language}
                  </ProductMetaItem>
                )}
                {book.type === 1 ? (
                  <ProductMetaItem>
                    <strong>Formátum:</strong> e-könyv
                  </ProductMetaItem>
                ) : book.book_binding_method && (
                  <ProductMetaItem>
                    <strong>Kötésmód:</strong> {book.book_binding_method}
                  </ProductMetaItem>
                )}
              </ProductMeta>
              {!isMaxMd && book.categories.length > 0 && (
                <ProductNav>
                  <BookCategoryNav type={book.type} categories={book.categories}></BookCategoryNav>
                </ProductNav>
              )}
            </ProductInfo>
            <ProductContent className="col-md-6 order-1 order-md-2">
              <ProductContentWrapper>
                <ProductTitle>{book.title}</ProductTitle>
                <ProductAuthor>
                  {book.authors?.map((author) => (
                    <AuthorName key={author.slug}>
                      <Link href={`/szerzo/${author.slug}`} forwardRef>
                        {author.title}
                      </Link>
                    </AuthorName>
                  ))}
                </ProductAuthor>
                <ProductTags>
                  {book.ranked_list &&
                    book.ranked_list.map((rank, rankIndex) => (
                      <ProductTag key={rankIndex}>
                        <ProductTagIcon>
                          <ProductTagNumber>{rank.place}</ProductTagNumber>
                          <ImageBadge></ImageBadge>
                        </ProductTagIcon>
                        <ProductTagText>
                          <Link href={rank.slug} forwardRef>
                            {rank.title}
                          </Link>
                        </ProductTagText>
                      </ProductTag>
                    ))}
                </ProductTags>
                <ProductRatingWrapper>
                  <Rating
                    user={actualUser}
                    productId={book.id}
                    globalRating={book.rating?.current_rating}
                    count={book.rating?.rating_count}
                    userRating={userBook?.rating?.user_rating ? userBook?.rating?.user_rating : book.rating?.user_rating}
                    numbers
                  ></Rating>
                </ProductRatingWrapper>
                {!isMaxMd && (
                  <ProductDescriptionWrapper>
                    <TextReveal height={400}>{book.description}</TextReveal>
                  </ProductDescriptionWrapper>
                )}
              </ProductContentWrapper>
            </ProductContent>
            <ProductActions className="col-md-3 order-3 order-md-3">
              <Price>
                <PriceValues>
                  <PriceValueContainer>
                    <p>Borító ár:</p>
                    <PriceValuesOriginal>{Currency.format(originalPrice)}</PriceValuesOriginal>
                  </PriceValueContainer>
                  <PriceValueContainer>
                    <p>Akciós ár:</p>
                    <PriceValuesDiscount>{Currency.format(discountedPrice)}</PriceValuesDiscount>
                  </PriceValueContainer>
                </PriceValues>
                <PriceTagWrapper>
                  <PriceTag>{userBook?.discount_percent ? userBook?.discount_percent : book.discount_percent}%</PriceTag>
                </PriceTagWrapper>
              </Price>
              {book.state === 'normal' && (
                <ProductDiscountInfo>
                  <p>Elmúlt 30 nap legalacsonyabb ára:</p>
                  <p>{Currency.format(book.type === 0 ? originalPrice * 0.73 : discountedPrice)}</p>
                </ProductDiscountInfo>
              )}
              <ActionButtonWrapper>
                <AddButton
                  buttonHeight="50px"
                  buttonWidth="100%"
                  fontSize="16px"
                  itemObj={{ id: book.id, title: book.title, price: book.price_sale }}
                  itemId={book.id}
                  type={book.state === 'normal' ? 'cart' : book.state === 'preorder' ? 'preorder' : ''}
                  text={book.state === 'normal' ? 'Kosárba' : book.state === 'preorder' ? 'Előjegyzés' : ''}
                  afterText={book.state === 'normal' ? 'Kosárba téve' : book.state === 'preorder' ? 'Előjegyezve' : ''}
                ></AddButton>
              </ActionButtonWrapper>
              <InfoLabel>
                <strong>Várható szállítás:</strong> {book.expected_delivery_time}
              </InfoLabel>
              <WishlistButtonWrapper>
                <AddButton
                  id={book.id}
                  buttonHeight="50px"
                  buttonWidth="100%"
                  fontSize="16px"
                  type="wishlist"
                  itemId={book.id}
                  text="Kívánságlistára"
                  textIcon="heart"
                  afterText="Kívánságlistán"
                  inCart={userBook?.in_wishlist}
                ></AddButton>
              </WishlistButtonWrapper>
              <IconBoxWrapper>
                <IconBox data={book.shop_info}></IconBox>
              </IconBoxWrapper>
              {isMaxMd && (
                <ProductDescriptionWrapper>
                  <ProductDescriptionTitle>A könyvről</ProductDescriptionTitle>
                  <TextReveal height={400}>{book.description}</TextReveal>
                </ProductDescriptionWrapper>
              )}
              {isMaxMd && book.categories.length > 0 && (
                <ProductNav>
                  <BookCategoryNav type={book.type} categories={book.categories}></BookCategoryNav>
                </ProductNav>
              )}
            </ProductActions>
          </Product>
        </SiteColContainer>
        {book.authors.length > 0 && (
          <TextReveal height={350} setHtml={false}>
            <Author>
              <BookAuthorSection author={book.authors?.[0]} inCart={userBook?.is_follow_main_author} />
            </Author>
          </TextReveal>
        )}
        <SiteColContainer>
          <Lists>
            {book.authors_books.length > 0 && (
              <ListWrapper>
                <BookScrollList
                  title="A szerző további művei"
                  titleLink={book.authors[0] ? `/szerzo/${book.authors[0].slug}` : null}
                  books={book.authors_books}
                ></BookScrollList>
              </ListWrapper>
            )}
            {book.similar_books.length > 0 && (
              <ListWrapper>
                <BookScrollList title="Hasonló könyvek" books={book.similar_books}></BookScrollList>
              </ListWrapper>
            )}
          </Lists>
        </SiteColContainer>
        <CommentsWrapper>
          <CommentsWrapperContainer className="container">
            <CommentsWrapperRow className="row">
              <CommentsWrapperCol className="col-sm-8 offset-sm-2">
                <CommentList
                  ref={commentListRef}
                  comments={comments}
                  user={actualUser}
                  onCommentSubmit={handleCommentSubmit}
                  onCommentEdit={handleCommentEdit}
                  onCommentDeleteClick={handleCommentDeleteClick}
                  onLoadMoreClick={handleCommentLoadMoreClick}
                ></CommentList>
              </CommentsWrapperCol>
            </CommentsWrapperRow>
          </CommentsWrapperContainer>
        </CommentsWrapper>
      </Content>
      <Footer></Footer>
    </KonyvPageWrapper>
  );
}

export async function getStaticProps({ params }) {
  const queryClient = new QueryClient();

  requestTemplates.requests['book-get'].body.slug = params.slug;

  await queryClient.prefetchQuery(['book-get', params.slug], () =>
    handleApiRequest({
      body: {
        request: [requestTemplates.requests['book-get']],
      },
    }),
  );

  // Checking if book is found
  let queryData = queryClient.getQueryData();

  if (queryData?.response) {
    let bookResponse = getResponseById(queryData, 'book-get');

    if (bookResponse && !bookResponse.success) {
      return {
        notFound: true,
        revalidate: 10,
      };
    }
  }

  const metadata = await getMetadata(`konyv/${params.slug}`);

  return {
    props: {
      key: params.slug,
      slug: params.slug,
      dehydratedState: dehydrate(queryClient),
      metadata: metadata.length > 0 ? metadata[0].data : null,
    },
    revalidate: 90,
  };
}


export async function getStaticPaths() {
  return {
    paths: [],
    fallback: 'blocking',
  };
}
