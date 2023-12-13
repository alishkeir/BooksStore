import dynamic from 'next/dynamic';
import { useState, useEffect, useCallback } from 'react';
import Link from 'next/link';
const Icon = dynamic(() => import('@components/icon/icon'));
import colors from '@vars/colors';
const PageHead = dynamic(() => import('@components/pageHead/pageHead'));
const Rating = dynamic(() => import('@components/rating/rating'));
const Header = dynamic(() => import('@components/header/header'));
const Content = dynamic(() => import('@components/content/content'));
import PageTitle from '@components/pageTitle/pageTitle';
const ProfileEmpty = dynamic(() => import('@components/profileEmpty/profileEmpty'));
const ProfileListImage = dynamic(() => import('@components/profileListImage/profileListImage'));
const SiteColContainer = dynamic(() => import('@components/siteColContainer/siteColContainer'));
const ProfileNavigator = dynamic(() => import('@components/profileNavigator/profileNavigator'));
const ProfileDataTitle = dynamic(() => import('@components/profileDataTitle/profileDataTitle'));
const Footer = dynamic(() => import('@components/footer/footer'));
import useProtectedRoute from '@hooks/useProtectedRoute/useProtectedRoute';
const BookListPagination = dynamic(() => import('@components/bookListPagination/bookListPagination'));
import { useQuery, useMutation } from 'react-query';
import { handleApiRequest, getResponseById } from '@libs/api';
const Overlay = dynamic(() => import('@components/overlay/overlay'));
const OverlayCard = dynamic(() => import('@components/overlayCard/overlayCard'));
const OverlayCardContentConfirmation = dynamic(() => import('@components/overlayCardContentConfirmation/overlayCardContentConfirmation'));
import useRequest from '@hooks/useRequest/useRequest';
import {
  AuthorWrapper,
  List,
  ListItem,
  ListItemAuthor,
  ListItemDate,
  ListItemDateTitle,
  ListItemDateValue,
  ListItemDelete,
  ListItemImageWrapper,
  ListItemRating,
  ListItemText,
  ListItemTextWrapper,
  ListItemTitle,
  PageContent,
  PaginantionWrapper,
  ProfilKonyvertekeleseimPageComponent,
  ProfileData,
  ProfileNavigatorWrapper,
  RatingStars,
  RatingTitle,
} from '@components/pages/profilKonyvertekeleseimPage.styled';

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    'profile-reviews-get': {
      method: 'GET',
      path: '/profile/reviews',
      ref: 'customerReviews',
      request_id: 'profile-reviews-get',
      body: {},
    },
    'profile-reviews-delete': {
      method: 'DELETE',
      path: '/profile/reviews',
      ref: 'customerReviews',
      request_id: 'profile-reviews-delete',
      body: {
        product_id: null,
      },
    },
  },
};

export default function ProfilKonyvertekeleseimPage() {
  let { user, authChecking } = useProtectedRoute();

  let [reviews, setReviews] = useState();
  let [pagination, setPagination] = useState();
  let [showPagination, setShowPagination] = useState(true);
  let [deleteConfirmationModalVisible, setDeleteConfirmationModalVisible] = useState(false);
  let [deleteConfirmationModalId, setDeleteConfirmationModalId] = useState(null);

  let reviewsGetQuery = useQuery('profile-reviews-get', () => handleApiRequest(requestReviewsGet.build()), {
    enabled: false,
    refetchOnWindowFocus: false,
    refetchOnMount: true,
    staleTime: 0,
    onSuccess: (data) => {
      let profileReviewsGetResponse = getResponseById(data, 'profile-reviews-get');

      if (profileReviewsGetResponse) {
        if (profileReviewsGetResponse.success) {
          // Success

          if (profileReviewsGetResponse.body.pagination.current_page > 1) {
            setReviews([...reviews, ...profileReviewsGetResponse.body.reviews]);
          } else {
            setReviews([...profileReviewsGetResponse.body.reviews]);
          }

          setPagination({ ...profileReviewsGetResponse.body.pagination });
        }
      }
    },
  });

  let reviewsDeleteQuery = useMutation('profile-reviews-delete', (requestUpdateBuild) => handleApiRequest(requestUpdateBuild), {
    onSuccess: (data) => {
      let profileReviewsDeleteResponse = getResponseById(data, 'profile-reviews-delete');

      if (profileReviewsDeleteResponse) {
        if (profileReviewsDeleteResponse.success) {
          // Success
          setReviews(profileReviewsDeleteResponse.body.reviews);
          setPagination(profileReviewsDeleteResponse.body.pagination);
          setDeleteConfirmationModalVisible(false);
          setDeleteConfirmationModalId(null);
        }
      }
    },
  });

  let requestReviewsGet = useRequest(requestTemplates, reviewsGetQuery);
  let requestReviewsDelete = useRequest(requestTemplates, reviewsDeleteQuery);
  requestReviewsGet.addRequest('profile-reviews-get');
  requestReviewsDelete.addRequest('profile-reviews-delete');

  // Confirming item delete
  let handleDeleteConfirmation = useCallback(() => {
    requestReviewsDelete.modifyHeaders((currentHeader) => {
      currentHeader['Authorization'] = `Bearer ${user.token}`;
    });

    requestReviewsDelete.modifyRequest('profile-reviews-delete', (requestObject) => {
      requestObject.body.product_id = deleteConfirmationModalId;
    });

    requestReviewsDelete.commit();
  });

  // Clicking delete button
  let handleDeleteButtonClick = useCallback((id) => {
    setDeleteConfirmationModalId(id);
    setDeleteConfirmationModalVisible(true);
  });

  // Hide confirmation dialog
  let hideConfirmation = useCallback(() => {
    setDeleteConfirmationModalVisible(false);
    setDeleteConfirmationModalId(null);
  });

  let handleLoadMoreClick = useCallback(() => {
    requestReviewsGet.modifyHeaders((currentHeader) => {
      currentHeader['Authorization'] = `Bearer ${user.token}`;
    });

    requestReviewsGet.modifyRequest('profile-reviews-get', (currentRequest) => {
      currentRequest.body.page = pagination.current_page + 1;
    });

    requestReviewsGet.commit();
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

    requestReviewsGet.modifyHeaders((currentHeader) => {
      currentHeader['Authorization'] = `Bearer ${user.token}`;
    });

    requestReviewsGet.commit();
  }, [user]);

  if (!user || authChecking) return <div>checking</div>;

  return (
    <ProfilKonyvertekeleseimPageComponent>
      <PageHead></PageHead>
      <Header></Header>
      {deleteConfirmationModalVisible && (
        <Overlay onClick={hideConfirmation} fixed>
          <OverlayCard onClose={hideConfirmation}>
            <OverlayCardContentConfirmation
              title="Biztosan szeretnéd törölni?"
              submitText="Törlés"
              cancelText="Mégse"
              onSubmit={handleDeleteConfirmation}
              onCancel={hideConfirmation}
            ></OverlayCardContentConfirmation>
          </OverlayCard>
        </Overlay>
      )}
      <Content>
        <SiteColContainer>
          <PageTitle className="d-none d-md-block">Profilom</PageTitle>
          <PageContent className="row">
            <ProfileNavigatorWrapper className="col-md-4 col-lg-3 d-none d-md-block">
              <ProfileNavigator selected={8}></ProfileNavigator>
            </ProfileNavigatorWrapper>
            <ProfileData className="col-md-8 col-lg-7 col-xl-6 col-xxl-5 offset-0 offset-lg-1">
              <ProfileDataTitle>Könyvértékeléseim</ProfileDataTitle>
              {reviewsGetQuery.isFetching && !reviews && <ProfileEmpty>Töltődik...</ProfileEmpty>}
              {typeof reviews !== 'undefined' && reviews.length < 1 && <ProfileEmpty>Még nincs könyvértékelésed</ProfileEmpty>}
              {typeof reviews !== 'undefined' && (
                <>
                  <List>
                    {reviews.map((item) => (
                      <ListItem key={item.id}>
                        <ListItemImageWrapper>
                          <ProfileListImage slug={item.slug} cover={item.cover} type={item.type}></ProfileListImage>
                        </ListItemImageWrapper>
                        <ListItemText>
                          <ListItemTextWrapper>
                            <ListItemTitle>
                              <Link href={`/konyv/${item.slug}`} passHref>
                                {item.title}
                              </Link>
                            </ListItemTitle>
                            <ListItemAuthor>{item?.authors?.length > 0 && <AuthorWrapper>{item.authors[0].title}</AuthorWrapper>}</ListItemAuthor>
                            <ListItemRating>
                              <RatingTitle>Értékelésem:</RatingTitle>
                              <RatingStars>
                                <Rating userRating={item.review} productId={item.id} user={user}></Rating>
                              </RatingStars>
                            </ListItemRating>
                            <ListItemDate>
                              <ListItemDateTitle>Értékelés időpontja:</ListItemDateTitle>
                              <ListItemDateValue>{item.review_date}</ListItemDateValue>
                            </ListItemDate>
                            <ListItemDelete onClick={() => handleDeleteButtonClick(item.id)}>
                              <Icon type="delete" iconWidth="17px" iconHeight="18px" iconColor={colors.monza}></Icon>
                            </ListItemDelete>
                          </ListItemTextWrapper>
                        </ListItemText>
                      </ListItem>
                    ))}
                  </List>
                  {pagination && showPagination && (
                    <PaginantionWrapper>
                      <BookListPagination
                        itemCount={reviews.length}
                        currentPage={pagination.current_page}
                        lastPage={pagination.last_page}
                        perPage={pagination.per_page}
                        totalItems={pagination.total}
                        onClick={handleLoadMoreClick}
                        loading={reviewsGetQuery.isFetching}
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
    </ProfilKonyvertekeleseimPageComponent>
  );
}
