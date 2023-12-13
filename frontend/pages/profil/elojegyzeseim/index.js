import dynamic from 'next/dynamic';
import { useState, useEffect, useCallback } from 'react';
import Link from 'next/link';
import { useDispatch } from 'react-redux';
const Icon = dynamic(() => import('@components/icon/icon'));
const PageHead = dynamic(() => import('@components/pageHead/pageHead'));
import currency from '@libs/currency';
const AddButton = dynamic(() => import('@components/addButton/addButton'));
const ProfileEmpty = dynamic(() => import('@components/profileEmpty/profileEmpty'));
import colors from '@vars/colors';
const Header = dynamic(() => import('@components/header/header'));
const Content = dynamic(() => import('@components/content/content'));
import PageTitle from '@components/pageTitle/pageTitle';
const Overlay = dynamic(() => import('@components/overlay/overlay'));
const OverlayCard = dynamic(() => import('@components/overlayCard/overlayCard'));
const OverlayCardContentConfirmation = dynamic(() => import('@components/overlayCardContentConfirmation/overlayCardContentConfirmation'));
const SiteColContainer = dynamic(() => import('@components/siteColContainer/siteColContainer'));
const ProfileNavigator = dynamic(() => import('@components/profileNavigator/profileNavigator'));
const ProfileListImage = dynamic(() => import('@components/profileListImage/profileListImage'));
const ProfileDataTitle = dynamic(() => import('@components/profileDataTitle/profileDataTitle'));
const BookListPagination = dynamic(() => import('@components/bookListPagination/bookListPagination'));
const Footer = dynamic(() => import('@components/footer/footer'));
import useProtectedRoute from '@hooks/useProtectedRoute/useProtectedRoute';
import { useQuery, useMutation } from 'react-query';
import { handleApiRequest, getResponseById } from '@libs/api';
import useRequest from '@hooks/useRequest/useRequest';
import { updateUserPreorder } from '@store/modules/user';
import {
  AuthorWrapper,
  ButtonWrapper,
  List,
  ListItem,
  ListItemAction,
  ListItemAuthor,
  ListItemCurrentPrize,
  ListItemDate,
  ListItemDateTitle,
  ListItemDateValue,
  ListItemDelete,
  ListItemImageWrapper,
  ListItemOriginalPrize,
  ListItemText,
  ListItemTextWrapper,
  ListItemTitle,
  PageContent,
  PaginantionWrapper,
  ProfilElojegyzeseimPageComponent,
  ProfileData,
  ProfileNavigatorWrapper,
} from '@components/pages/profilElojegyzeseimPage.styled';

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    profilePreOrdersGet: {
      method: 'GET',
      path: '/profile/preorders',
      ref: 'customerPreOrders',
      request_id: 'profile-preorders-get',
      body: {
        page: null,
      },
    },
    profilePreOrdersDelete: {
      method: 'DELETE',
      path: '/profile/preorders',
      ref: 'customerPreOrders',
      request_id: 'profile-preorders-delete',
      body: {
        product_id: null,
      },
    },
  },
};

export default function ProfilElojegyzeseimPage() {
  let { user, authChecking } = useProtectedRoute();

  let dispatch = useDispatch();

  let [preOrders, setPreOrders] = useState();
  let [responseErrors, setResponseErrors] = useState(null);
  let [pagination, setPagination] = useState();
  let [showPagination, setShowPagination] = useState(true);

  let [deleteConfirmationModalVisible, setDeleteConfirmationModalVisible] = useState(false);
  let [deleteConfirmationModalId, setDeleteConfirmationModalId] = useState(null);

  let preOrdersGetQuery = useQuery('profile-preorders-get', () => handleApiRequest(requestPreOrdersGet.build()), {
    enabled: false,
    refetchOnWindowFocus: false,
    refetchOnMount: true,
    staleTime: 0,
    onSuccess: (data) => {
      let profilePreordersGetResponse = getResponseById(data, 'profile-preorders-get');

      if (profilePreordersGetResponse) {
        if (profilePreordersGetResponse.success) {
          // Success
          if (profilePreordersGetResponse.body.pagination.current_page > 1) {
            setPreOrders([...preOrders, ...profilePreordersGetResponse.body.preorder_items]);
          } else {
            setPreOrders([...profilePreordersGetResponse.body.preorder_items]);
          }
          setPagination({ ...profilePreordersGetResponse.body.pagination });
        }
      }
    },
  });

  let preOrdersDeleteQuery = useMutation('profile-preorders-delete', (requestUpdateBuild) => handleApiRequest(requestUpdateBuild), {
    onSuccess: (data) => {
      let profilePreordersDeleteResponse = getResponseById(data, 'profile-preorders-delete');

      if (profilePreordersDeleteResponse) {
        if (profilePreordersDeleteResponse?.success) {
          // Success
          setDeleteConfirmationModalVisible(false);
          setDeleteConfirmationModalId(null);
          setPreOrders(profilePreordersDeleteResponse.body.preorder_items);
          setPagination(profilePreordersDeleteResponse.body.pagination);

          // Modifying user object preorders
          dispatch(updateUserPreorder({ preorder_items: profilePreordersDeleteResponse.body.preorder_items }));
        } else {
          setResponseErrors(Object.values(profilePreordersDeleteResponse.body.errors));
        }
      }
    },
  });

  let requestPreOrdersGet = useRequest(requestTemplates, preOrdersGetQuery);
  let requestPreOrdersDelete = useRequest(requestTemplates, preOrdersDeleteQuery);
  requestPreOrdersGet.addRequest('profilePreOrdersGet');
  requestPreOrdersDelete.addRequest('profilePreOrdersDelete');

  // Confirming item delete
  let handleDeleteConfirmation = useCallback(() => {
    requestPreOrdersDelete.modifyHeaders((currentHeader) => {
      currentHeader['Authorization'] = `Bearer ${user.token}`;
    });

    requestPreOrdersDelete.modifyRequest('profilePreOrdersDelete', (requestObject) => {
      requestObject.body.product_id = deleteConfirmationModalId;
    });

    requestPreOrdersDelete.commit();
  });

  // Clicking delete button
  let handleDeleteButtonClick = useCallback((id) => {
    responseErrors && setResponseErrors(null);
    setDeleteConfirmationModalId(id);
    setDeleteConfirmationModalVisible(true);
  });

  let handleLoadMoreClick = useCallback(() => {
    requestPreOrdersGet.modifyHeaders((currentHeader) => {
      currentHeader['Authorization'] = `Bearer ${user.token}`;
    });

    requestPreOrdersGet.modifyRequest('profilePreOrdersGet', (currentRequest) => {
      currentRequest.body.page = pagination.current_page + 1;
    });

    requestPreOrdersGet.commit();
  });

  // Hide confirmation dialog
  let hideConfirmation = useCallback(() => {
    setDeleteConfirmationModalId(null);
    setDeleteConfirmationModalVisible(false);
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

    requestPreOrdersGet.modifyHeaders((currentHeader) => {
      currentHeader['Authorization'] = `Bearer ${user.token}`;
    });

    requestPreOrdersGet.commit();
  }, [user]);

  if (!user || authChecking) return <div>checking</div>;

  return (
    <ProfilElojegyzeseimPageComponent>
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
              <ProfileNavigator selected={2}></ProfileNavigator>
            </ProfileNavigatorWrapper>
            <ProfileData className="col-md-8 col-lg-7 col-xl-6 col-xxl-5 offset-0 offset-lg-1">
              <ProfileDataTitle>Előjegyzéseim</ProfileDataTitle>
              {preOrdersGetQuery.isFetching && !preOrders && <ProfileEmpty>Töltődik...</ProfileEmpty>}
              {typeof preOrders !== 'undefined' && preOrders.length < 1 && <ProfileEmpty>Még nincs előjegyzésed</ProfileEmpty>}
              {typeof preOrders !== 'undefined' && (
                <>
                  <List>
                    {preOrders.map((preOrder) => (
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
                            <ListItemDate>
                              {preOrder.state === 'preorder' && <ListItemDateTitle>Várható megjelenés:</ListItemDateTitle>}
                              {preOrder.state === 'normal' && <ListItemDateTitle>Megjelenés dátuma:</ListItemDateTitle>}
                              <ListItemDateValue>{preOrder.published_at}</ListItemDateValue>
                            </ListItemDate>
                            <ListItemDelete onClick={() => handleDeleteButtonClick(preOrder.id)}>
                              <Icon type="delete" iconWidth="17px" iconHeight="18px" iconColor={colors.monza}></Icon>
                            </ListItemDelete>
                          </ListItemTextWrapper>
                          {preOrder.state === 'normal' && (
                            <ListItemAction>
                              <ButtonWrapper>
                                <AddButton
                                  buttonHeight="100%"
                                  buttonWidth="100%"
                                  fontSize="16px"
                                  type="cart"
                                  itemId={preOrder.id}
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
                        itemCount={preOrders.length}
                        currentPage={pagination.current_page}
                        lastPage={pagination.last_page}
                        perPage={pagination.per_page}
                        totalItems={pagination.total}
                        onClick={handleLoadMoreClick}
                        loading={preOrdersGetQuery.isFetching}
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
    </ProfilElojegyzeseimPageComponent>
  );
}
