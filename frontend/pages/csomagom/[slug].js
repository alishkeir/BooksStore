import dynamic from 'next/dynamic';
import { useEffect, useState } from 'react';
import { useMutation } from 'react-query';
import { useRouter } from 'next/router';
import { handleApiRequest, getResponseById, getMetadata } from '@libs/api';
import useRequest from '@hooks/useRequest/useRequest';
const ProgressBoxList = dynamic(() => import('@components/progressBoxList/progressBoxList'));
const Header = dynamic(() => import('@components/header/header'));
const Content = dynamic(() => import('@components/content/content'));
const SiteColContainer = dynamic(() => import('@components/siteColContainer/siteColContainer'));
const Footer = dynamic(() => import('@components/footer/footer'));
const HeaderPromo = dynamic(() => import('@components/headerPromo/headerPromo'));
import {
  ContentWrapper,
  CsomagomSlugPageWrapper,
  ImageWrapper,
  InfoBox,
  InputCol,
  InputDescription,
  InputNumber,
  InputRow,
  Progress,
  Title,
} from '@components/pages/csomagomSlugPage.styled';

import ImagePinPackage from '@assets/images/elements/pin-package.svg';
import DynamicHead from '@components/heads/DynamicHead';

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    'order-status-check': {
      method: 'GET',
      path: '/order-status',
      ref: 'status',
      request_id: 'order-status-check',
      body: {
        order_number: 'ALOM46A1C246',
      },
    },
  },
};

export default function CsomagomSlugPage({metadata}) {
  let router = useRouter();

  let [order, setOrder] = useState({});

  let orderStatusCheckQuery = useMutation('auth-check-token', (requestUpdateBuild) => handleApiRequest(requestUpdateBuild));
  let orderStatusCheckRequest = useRequest(requestTemplates, orderStatusCheckQuery);

  useEffect(() => {
    if (!router.query.slug) return;

    orderStatusCheckRequest.addRequest('order-status-check');

    orderStatusCheckRequest.modifyRequest('order-status-check', (request) => {
      request.body.order_number = router.query.slug;
    });

    orderStatusCheckRequest.commit({
      onSettled: (data) => {
        let orderStatusCheckResponse = getResponseById(data, 'order-status-check');

        if (orderStatusCheckResponse?.success) {
          setOrder(orderStatusCheckResponse.body);
        } else {
          setOrder({ error: 'Hopp! Nem sikerült lekérnem a rendeléshez tartozó adatokat :(' });
        }
      },
    });
  }, [router.query.slug]);

  return (
    <CsomagomSlugPageWrapper>
      <DynamicHead metadata={metadata} />
      <Header promo={HeaderPromo}></Header>
      <Content>
        <SiteColContainer>
          <ContentWrapper>
            <Title>Hol a csomagom?</Title>
            <InputRow className="row">
              <InputCol className="col-md-8 offset-md-2">
                <ImageWrapper>
                  <ImagePinPackage></ImagePinPackage>
                </ImageWrapper>
                {!order.error && <InputNumber>{order.order_number}</InputNumber>}
                <InputDescription>Ezen az oldalon megtekintheted rendelésed aktuális állapotát</InputDescription>
              </InputCol>
            </InputRow>
            {order.error && <InfoBox>{order.error}</InfoBox>}
            {!order.error && (
              <>
                <Progress>
                  <ProgressBoxList order={order}></ProgressBoxList>
                </Progress>
                <InfoBox>Utoljára frissítve: {order.last_update}</InfoBox>
              </>
            )}
          </ContentWrapper>
        </SiteColContainer>
      </Content>
      <Footer></Footer>
    </CsomagomSlugPageWrapper>
  );
}

CsomagomSlugPage.getInitialProps = async (ctx) => {
  const metadata = await getMetadata(ctx.req.url)
  return {metadata: metadata.length > 0 ? metadata[0].data : null};
}
