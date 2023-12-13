import dynamic from 'next/dynamic';
import { useCallback, useState, useEffect } from 'react';
import Switch from 'react-switch';
import colors from '@vars/colors';
const PageHead = dynamic(() => import('@components/pageHead/pageHead'));
const Header = dynamic(() => import('@components/header/header'));
const Content = dynamic(() => import('@components/content/content'));
import PageTitle from '@components/pageTitle/pageTitle';
const ProfileEmpty = dynamic(() => import('@components/profileEmpty/profileEmpty'));
const SiteColContainer = dynamic(() => import('@components/siteColContainer/siteColContainer'));
const ProfileNavigator = dynamic(() => import('@components/profileNavigator/profileNavigator'));
const ProfileDataTitle = dynamic(() => import('@components/profileDataTitle/profileDataTitle'));
const ProfileAuthorLine = dynamic(() => import('@components/profileAuthorLine/profileAuthorLine'));
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
  AutoSubscription,
  AutoSubscriptionSwitch,
  AutoSubscriptionText,
  List,
  PageContent,
  PaginantionWrapper,
  ProfilKovetettSzerzokPageComponent,
  ProfileData,
  ProfileNavigatorWrapper,
} from '@components/pages/profilKovetettSzerzokPage.styled';

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    'profile-authors-get': {
      method: 'GET',
      path: '/profile/authors',
      ref: 'customerAuthors',
      request_id: 'profile-authors-get',
      body: {},
    },
    'profile-authors-delete': {
      method: 'DELETE',
      path: '/profile/authors',
      ref: 'customerAuthors',
      request_id: 'profile-authors-delete',
      body: {
        author_id: null,
      },
    },
    'profile-authors-follow': {
      method: 'POST',
      path: '/profile/author-follow-up',
      ref: 'customerAuthors',
      request_id: 'profile-authors-follow',
      body: {},
    },
  },
};

export default function ProfilKovetettSzerzokPage() {
  let { user, authChecking } = useProtectedRoute();
  let [switchChecked, setSwitchChecked] = useState(true);
  let [authors, setAuthors] = useState();
  let [pagination, setPagination] = useState();
  let [showPagination, setShowPagination] = useState(true);
  let [deleteConfirmationModalVisible, setDeleteConfirmationModalVisible] = useState(false);
  let [deleteConfirmationModalId, setDeleteConfirmationModalId] = useState(null);

  let authorsGetQuery = useQuery('profile-authors-get', () => handleApiRequest(requestAuthorsGet.build()), {
    enabled: false,
    refetchOnWindowFocus: false,
    refetchOnMount: true,
    staleTime: 0,
    onSuccess: (data) => {
      let profileAuthorsGetResponse = getResponseById(data, 'profile-authors-get');

      if (profileAuthorsGetResponse) {
        if (profileAuthorsGetResponse.success) {
          // Success
          setSwitchChecked(profileAuthorsGetResponse.body.author_follow_up);

          if (profileAuthorsGetResponse.body.pagination.current_page > 1) {
            setAuthors([...authors, ...profileAuthorsGetResponse.body.authors]);
          } else {
            setAuthors([...profileAuthorsGetResponse.body.authors]);
          }

          setPagination({ ...profileAuthorsGetResponse.body.pagination });
        }
      }
    },
  });

  let authorsDeleteQuery = useMutation('profile-authors-delete', (requestUpdateBuild) => handleApiRequest(requestUpdateBuild), {
    onSuccess: (data) => {
      let profileAuthorsDeleteResponse = getResponseById(data, 'profile-authors-delete');

      if (profileAuthorsDeleteResponse) {
        if (!profileAuthorsDeleteResponse.success) {
          setSwitchChecked(!switchChecked);
        } else {
          setAuthors(profileAuthorsDeleteResponse.body.authors);
          setPagination(profileAuthorsDeleteResponse.body.pagination);
          setDeleteConfirmationModalVisible(false);
          setDeleteConfirmationModalId(null);
        }
      }
    },
  });

  let authorsFollowQuery = useMutation('profile-authors-follow', (requestUpdateBuild) => handleApiRequest(requestUpdateBuild), {
    onSuccess: (data) => {
      let profileAuthorsFollowResponse = getResponseById(data, 'profile-authors-follow');

      if (profileAuthorsFollowResponse) {
        if (profileAuthorsFollowResponse.success) {
          // Success
          console.log(profileAuthorsFollowResponse.body.customer.author_follow_up);
        }
      }
    },
  });

  let requestAuthorsGet = useRequest(requestTemplates, authorsGetQuery);
  let requestAuthorsDelete = useRequest(requestTemplates, authorsDeleteQuery);
  let requestAuthorsFollow = useRequest(requestTemplates, authorsFollowQuery);
  requestAuthorsGet.addRequest('profile-authors-get');
  requestAuthorsDelete.addRequest('profile-authors-delete');
  requestAuthorsFollow.addRequest('profile-authors-follow');

  // Switching author follow
  let handleSwitchClick = useCallback(() => {
    setSwitchChecked(!switchChecked);

    requestAuthorsFollow.modifyHeaders((currentHeader) => {
      currentHeader['Authorization'] = `Bearer ${user.token}`;
    });

    requestAuthorsFollow.commit();
  });

  // Confirming item delete
  let handleDeleteConfirmation = useCallback(() => {
    requestAuthorsDelete.modifyHeaders((currentHeader) => {
      currentHeader['Authorization'] = `Bearer ${user.token}`;
    });

    requestAuthorsDelete.modifyRequest('profile-authors-delete', (requestObject) => {
      requestObject.body.author_id = requestObject.body.product_id = deleteConfirmationModalId;
    });

    requestAuthorsDelete.commit();
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

  // Infinite loader
  let handleLoadMoreClick = useCallback(() => {
    requestAuthorsGet.modifyHeaders((currentHeader) => {
      currentHeader['Authorization'] = `Bearer ${user.token}`;
    });

    requestAuthorsGet.modifyRequest('profile-authors-get', (currentRequest) => {
      currentRequest.body.page = pagination.current_page + 1;
    });

    requestAuthorsGet.commit();
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

    requestAuthorsGet.modifyHeaders((currentHeader) => {
      currentHeader['Authorization'] = `Bearer ${user.token}`;
    });

    requestAuthorsGet.commit();
  }, [user]);

  if (!user || authChecking) return <div>checking</div>;

  return (
    <ProfilKovetettSzerzokPageComponent>
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
              <ProfileNavigator selected={6}></ProfileNavigator>
            </ProfileNavigatorWrapper>
            <ProfileData className="col-md-8 col-lg-7 col-xl-6 col-xxl-5 offset-0 offset-lg-1">
              <ProfileDataTitle>Követett szerzők</ProfileDataTitle>
              {authorsGetQuery.isFetching && !authors && <ProfileEmpty>Töltődik...</ProfileEmpty>}
              {typeof authors !== 'undefined' && (
                <>
                  <AutoSubscription>
                    <AutoSubscriptionText>Vásárlás után automatikus feliratkozás a szerzőre</AutoSubscriptionText>
                    <AutoSubscriptionSwitch>
                      <Switch
                        onChange={handleSwitchClick}
                        checked={switchChecked}
                        uncheckedIcon={false}
                        checkedIcon={false}
                        onColor={colors.deyork}
                        boxShadow="0px 0px 4px rgba(0, 0, 0, 0.2)"
                        height={30}
                        width={56}
                        handleDiameter={26}
                      />
                    </AutoSubscriptionSwitch>
                  </AutoSubscription>
                  <List>
                    {authors?.map((author) => (
                      <ProfileAuthorLine key={author.id} {...author} onDelete={handleDeleteButtonClick}></ProfileAuthorLine>
                    ))}
                  </List>
                  {pagination && showPagination && (
                    <PaginantionWrapper>
                      <BookListPagination
                        itemCount={authors.length}
                        currentPage={pagination.current_page}
                        lastPage={pagination.last_page}
                        perPage={pagination.per_page}
                        totalItems={pagination.total}
                        onClick={handleLoadMoreClick}
                        loading={authorsGetQuery.isFetching}
                      ></BookListPagination>
                    </PaginantionWrapper>
                  )}
                </>
              )}
              {typeof authors !== 'undefined' && authors.length < 1 && <ProfileEmpty>Még nincs követett szerződ</ProfileEmpty>}
            </ProfileData>
          </PageContent>
        </SiteColContainer>
      </Content>
      <Footer></Footer>
    </ProfilKovetettSzerzokPageComponent>
  );
}
