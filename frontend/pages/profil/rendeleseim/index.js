import dynamic from 'next/dynamic';
import Link from 'next/link';
import React, { useState, useEffect, useCallback } from 'react';
const PageHead = dynamic(() => import('@components/pageHead/pageHead'));
const Header = dynamic(() => import('@components/header/header'));
const Content = dynamic(() => import('@components/content/content'));
import PageTitle from '@components/pageTitle/pageTitle';
const ProfileEmpty = dynamic(() => import('@components/profileEmpty/profileEmpty'));
const OrderProductTbody = dynamic(() => import('@components/orderProductTbody/orderProductTbody'));
const SiteColContainer = dynamic(() => import('@components/siteColContainer/siteColContainer'));
const ProfileNavigator = dynamic(() => import('@components/profileNavigator/profileNavigator'));
const ProfileDataTitle = dynamic(() => import('@components/profileDataTitle/profileDataTitle'));
const BookListPagination = dynamic(() => import('@components/bookListPagination/bookListPagination'));
const Footer = dynamic(() => import('@components/footer/footer'));
import useProtectedRoute from '@hooks/useProtectedRoute/useProtectedRoute';
import { useQuery } from 'react-query';
import { getResponseById } from '@libs/api';
import useRequest from '@hooks/useRequest/useRequest';
import { getSiteCode } from '@libs/site';
import {
  PageContent,
  ProfilRendeleseimPageComponent,
  ProfileNavigatorWrapper,
  PaginantionWrapper,
  SeparatorTbody,
  Table,
  TableWrapper,
  InfoText,
  Th,
  Td,
  Thead,
  Tr,
} from '@components/pages/profilRendeleseimPage.styled';
import settingsVars from "@vars/settingsVars";
import url from "@libs/url";

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    profileOrders: {
      method: 'GET',
      path: '/profile/orders',
      ref: 'customerOrders',
      request_id: 'profile-orders',
      body: {
        page: null,
      },
    },
  },
};

export default function ProfilRendeleseimPage() {
  let { user, authChecking } = useProtectedRoute();
  let [orders, setOrders] = useState();
  let [pagination, setPagination] = useState();
  let [showPagination, setShowPagination] = useState(false);

  let ordersGetQuery = useQuery('profile-orders', () => handleProfileOrders(requestOrders.build()), {
    enabled: false,
    refetchOnWindowFocus: false,
    refetchOnMount: true,
    staleTime: 0,
    onSuccess: (data) => {
      let profileOrdersResponse = getResponseById(data, 'profile-orders');

      if (profileOrdersResponse) {
        if (profileOrdersResponse.success) {
          // Success
          if (profileOrdersResponse.body.pagination.current_page > 1) {
            setOrders([...orders, ...profileOrdersResponse.body.orders]);
          } else {
            setOrders([...profileOrdersResponse.body.orders]);
          }
          setPagination({ ...profileOrdersResponse.body.pagination });
        }
      }
    },
  });

  let requestOrders = useRequest(requestTemplates, ordersGetQuery);
  requestOrders.addRequest('profileOrders');

  let handleLoadMoreClick = useCallback(() => {
    requestOrders.modifyHeaders((currentHeader) => {
      currentHeader['Authorization'] = `Bearer ${user.token}`;
    });

    requestOrders.modifyRequest('profileOrders', (currentRequest) => {
      currentRequest.body.page = pagination.current_page + 1;
    });

    requestOrders.commit();
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

    requestOrders.modifyHeaders((currentHeader) => {
      currentHeader['Authorization'] = `Bearer ${user.token}`;
    });

    requestOrders.commit();
  }, [user]);

  if (!user || authChecking) return <div>checking</div>;

  return (
    <ProfilRendeleseimPageComponent>
      <PageHead></PageHead>
      <Header></Header>
      <Content>
        <SiteColContainer>
          <PageTitle className="d-none d-md-block">Profilom</PageTitle>
          <PageContent className="row">
            <ProfileNavigatorWrapper className="col-md-4 col-lg-3 d-none d-md-block">
              <ProfileNavigator selected={1}></ProfileNavigator>
            </ProfileNavigatorWrapper>
            <TableWrapper className="col-md-8 col-xl-7 offset-0 offset-lg-1">
              <ProfileDataTitle>Rendeléseim</ProfileDataTitle>
              <InfoText>
                2021. augusztus 1-nél korábbi megrendelés esetén keresd{' '}
                <Link href="/kapcsolat" passHref>
                  ügyfélszolgálatunkat
                </Link>
                .
              </InfoText>
              {ordersGetQuery.isFetching && !orders && <ProfileEmpty>Töltődik...</ProfileEmpty>}
              {typeof orders !== 'undefined' && orders.length < 1 && <ProfileEmpty>Még nincs rendelésed</ProfileEmpty>}
              {typeof orders !== 'undefined' && orders.length > 0 && (
                <>
                  <Table>
                    <Thead>
                      <Tr>
                        <Th>
                          <span className="d-none d-lg-inline">Rendelés időpontja</span>
                          <span className="d-inline d-lg-none">Időpont</span>
                        </Th>
                        <Th>
                          <span className="d-none d-lg-inline">Rendelés összege</span>
                          <span className="d-inline d-lg-none">Összeg</span>
                        </Th>
                        <Th>Azonosító</Th>
                        <Th colSpan="2">Állapot</Th>
                      </Tr>
                    </Thead>
                    {orders.map((order) => (
                      <React.Fragment key={order.id}>
                        <OrderProductTbody {...order}></OrderProductTbody>
                        <SeparatorTbody>
                          <Tr>
                            <Td></Td>
                          </Tr>
                        </SeparatorTbody>
                      </React.Fragment>
                    ))}
                  </Table>

                  {pagination && showPagination && (
                    <PaginantionWrapper>
                      <BookListPagination
                        itemCount={orders.length}
                        currentPage={pagination.current_page}
                        lastPage={pagination.last_page}
                        perPage={pagination.per_page}
                        totalItems={pagination.total}
                        onClick={handleLoadMoreClick}
                        loading={ordersGetQuery.isFetching}
                        itemLabel="Rendelés az összesből"
                        buttonLabel="További rendelések betöltése"
                      ></BookListPagination>
                    </PaginantionWrapper>
                  )}
                </>
              )}
            </TableWrapper>
          </PageContent>
        </SiteColContainer>
      </Content>
      <Footer></Footer>
    </ProfilRendeleseimPageComponent>
  );
}

function handleProfileOrders(requestBuild) {
  let settings = settingsVars.get(url.getHost());
  return fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/v1/${getSiteCode(settings.key)}/composite`, {
    method: 'POST',
    headers: requestBuild.headers,
    body: JSON.stringify(requestBuild.body),
  })
    .then((response) => {
      if (!response.ok) throw new Error(`API response: ${response.status}`);
      return response.json();
    })
    .then((data) => data)
    .catch((error) => console.log(error));
}
