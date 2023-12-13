import dynamic from 'next/dynamic';
import { useState, useEffect, useCallback } from 'react';
import Link from 'next/link';
const PageHead = dynamic(() => import('@components/pageHead/pageHead'));
const Icon = dynamic(() => import('@components/icon/icon'));
import currency from '@libs/currency';
const AddButton = dynamic(() => import('@components/addButton/addButton'));
const ProfileEmpty = dynamic(() => import('@components/profileEmpty/profileEmpty'));
import colors from '@vars/colors';
const Header = dynamic(() => import('@components/header/header'));
const Content = dynamic(() => import('@components/content/content'));
import PageTitle from '@components/pageTitle/pageTitle';
const SiteColContainer = dynamic(() => import('@components/siteColContainer/siteColContainer'));
const ProfileNavigator = dynamic(() => import('@components/profileNavigator/profileNavigator'));
const ProfileListImage = dynamic(() => import('@components/profileListImage/profileListImage'));
const ProfileDataTitle = dynamic(() => import('@components/profileDataTitle/profileDataTitle'));
const BookListPagination = dynamic(() => import('@components/bookListPagination/bookListPagination'));
const Footer = dynamic(() => import('@components/footer/footer'));
import useProtectedRoute from '@hooks/useProtectedRoute/useProtectedRoute';
const Overlay = dynamic(() => import('@components/overlay/overlay'));
const OverlayCard = dynamic(() => import('@components/overlayCard/overlayCard'));
const OverlayCardContentConfirmation = dynamic(() => import('@components/overlayCardContentConfirmation/overlayCardContentConfirmation'));
import { useQuery, useMutation } from 'react-query';
import { handleApiRequest, getResponseById } from '@libs/api';
import useRequest from '@hooks/useRequest/useRequest';
import {
  AuthorWrapper,
  ButtonWrapper,
  List,
  ListItem,
  ListItemAction,
  ListItemAuthor,
  ListItemCurrentPrize,
  ListItemDelete,
  ListItemImageWrapper,
  ListItemOriginalPrize,
  ListItemText,
  ListItemTextWrapper,
  ListItemTitle,
  PageContent,
  PaginantionWrapper,
  ProfilKivansaglistamPageComponent,
  ProfileData,
  ProfileNavigatorWrapper,
} from '@components/pages/profilKivansaglistamPage.styled';

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    profileWishlistGet: {
      method: 'GET',
      path: '/profile/wishlist',
      ref: 'customerWishlist',
      request_id: 'profile-wishlist-get',
      body: {
        page: null,
      },
    },
    profileWishlistDelete: {
      method: 'DELETE',
      path: '/profile/wishlist',
      ref: 'customerWishlist',
      request_id: 'profile-wishlist-delete',
      body: {
        product_id: null,
      },
    },
  },
};

export default function ProfilKivansaglistamPage() {
  let { user, authChecking } = useProtectedRoute();

  let [wishlist, setWishlist] = useState();
  let [pagination, setPagination] = useState();
  let [showPagination, setShowPagination] = useState(true);
  let [deleteConfirmationModalVisible, setDeleteConfirmationModalVisible] = useState(false);
  let [deleteConfirmationModalId, setDeleteConfirmationModalId] = useState(null);

  let wishlistGetQuery = useQuery('profile-wishlist-get', () => handleApiRequest(requestWishlistGet.build()), {
    enabled: false,
    refetchOnWindowFocus: false,
    refetchOnMount: true,
    staleTime: 0,
    onSuccess: (data) => {
      let profilePreordersGetResponse = getResponseById(data, 'profile-wishlist-get');

      if (profilePreordersGetResponse) {
        if (profilePreordersGetResponse.success) {
          // Success
          if (profilePreordersGetResponse.body.pagination.current_page > 1) {
            setWishlist([...wishlist, ...profilePreordersGetResponse.body.wishlist]);
          } else {
            setWishlist([...profilePreordersGetResponse.body.wishlist]);
          }
          setPagination({ ...profilePreordersGetResponse.body.pagination });
        }
      }
    },
  });

  let wishlistDeleteQuery = useMutation('profile-wishlist-delete', (requestUpdateBuild) => handleApiRequest(requestUpdateBuild), {
    onSuccess: (data) => {
      let profilePreordersDeleteResponse = getResponseById(data, 'profile-wishlist-delete');

      if (profilePreordersDeleteResponse) {
        if (profilePreordersDeleteResponse.success) {
          // Success
          setWishlist(profilePreordersDeleteResponse.body.wishlist);
          setPagination(profilePreordersDeleteResponse.body.pagination);
          setDeleteConfirmationModalVisible(false);
          setDeleteConfirmationModalId(null);
        }
      }
    },
  });

  let requestWishlistGet = useRequest(requestTemplates, wishlistGetQuery);
  let requestWishlistDelete = useRequest(requestTemplates, wishlistDeleteQuery);
  requestWishlistGet.addRequest('profileWishlistGet');
  requestWishlistDelete.addRequest('profileWishlistDelete');

  // Confirming item delete
  let handleDeleteConfirmation = useCallback(() => {
    requestWishlistDelete.modifyHeaders((currentHeader) => {
      currentHeader['Authorization'] = `Bearer ${user.token}`;
    });

    requestWishlistDelete.modifyRequest('profileWishlistDelete', (requestObject) => {
      requestObject.body.product_id = deleteConfirmationModalId;
    });

    requestWishlistDelete.commit();
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
    requestWishlistGet.modifyHeaders((currentHeader) => {
      currentHeader['Authorization'] = `Bearer ${user.token}`;
    });

    requestWishlistGet.modifyRequest('profileWishlistGet', (currentRequest) => {
      currentRequest.body.page = pagination.current_page + 1;
    });

    requestWishlistGet.commit();
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

    requestWishlistGet.modifyHeaders((currentHeader) => {
      currentHeader['Authorization'] = `Bearer ${user.token}`;
    });

    requestWishlistGet.commit();
  }, [user]);

  if (!user || authChecking) return <div>checking</div>;

  return (
    <ProfilKivansaglistamPageComponent>
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
              <ProfileNavigator selected={5}></ProfileNavigator>
            </ProfileNavigatorWrapper>
            <ProfileData className="col-md-8 col-lg-7 col-xl-6 col-xxl-5 offset-0 offset-lg-1">
              <ProfileDataTitle>Kívánságlistám</ProfileDataTitle>
              {wishlistGetQuery.isFetching && !wishlist && <ProfileEmpty>Töltődik...</ProfileEmpty>}
              {typeof wishlist !== 'undefined' && wishlist.length < 1 && <ProfileEmpty>Még nincs kívánságlistád</ProfileEmpty>}
              {typeof wishlist !== 'undefined' && (
                <>
                  <List>
                    {wishlist.map((preOrder) => (
                      <ListItem key={preOrder.id}>
                        <ListItemImageWrapper>
                          <ProfileListImage slug={preOrder.slug} cover={preOrder.cover} type={preOrder.type}></ProfileListImage>
                        </ListItemImageWrapper>
                        <ListItemText>
                          <ListItemTextWrapper>
                            <ListItemTitle>
                              <Link href={`/konyv/${preOrder.slug}`} passHref>
                                {preOrder.title}
                              </Link>
                            </ListItemTitle>
                            <ListItemAuthor>{preOrder.authors && <AuthorWrapper>{preOrder.authors}</AuthorWrapper>}</ListItemAuthor>
                            <ListItemOriginalPrize>{currency.format(preOrder.price_list)}</ListItemOriginalPrize>
                            <ListItemCurrentPrize>{currency.format(preOrder.price_sale)}</ListItemCurrentPrize>
                            <ListItemDelete onClick={() => handleDeleteButtonClick(preOrder.id)}>
                              <Icon type="delete" iconWidth="17px" iconHeight="18px" iconColor={colors.monza}></Icon>
                            </ListItemDelete>
                          </ListItemTextWrapper>
                          {false && (
                            <ListItemAction>
                              <ButtonWrapper>
                                <AddButton
                                  buttonHeight="100%"
                                  buttonWidth="100%"
                                  fontSize="16px"
                                  theme="primary"
                                  text="Kosárba"
                                  afterText="Kosárba téve"
                                ></AddButton>
                              </ButtonWrapper>
                            </ListItemAction>
                          )}
                        </ListItemText>
                      </ListItem>
                    ))}
                  </List>
                  {pagination && showPagination && (
                    <PaginantionWrapper>
                      <BookListPagination
                        itemCount={wishlist.length}
                        currentPage={pagination.current_page}
                        lastPage={pagination.last_page}
                        perPage={pagination.per_page}
                        totalItems={pagination.total}
                        onClick={handleLoadMoreClick}
                        loading={wishlistGetQuery.isFetching}
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
    </ProfilKivansaglistamPageComponent>
  );
}
