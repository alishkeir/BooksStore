import dynamic from 'next/dynamic';
import { useCallback, useEffect, useState, useRef } from 'react';
import { useRouter } from 'next/router';
import { useDispatch, useSelector } from 'react-redux';
import { updateCheckout, updateSummaryPaymentMethod } from '@store/modules/checkout';
import Link from 'next/link';
import useUser from '@hooks/useUser/useUser';
import PageHead from '@components/pageHead/pageHead';
import url from '@libs/url';
const Button = dynamic(() => import('@components/button/button'));
const Header = dynamic(() => import('@components/header/header'));
const Content = dynamic(() => import('@components/content/content'));
const SiteColContainer = dynamic(() => import('@components/siteColContainer/siteColContainer'));
const Footer = dynamic(() => import('@components/footer/footer'));
const NavSteps = dynamic(() => import('@components/navSteps/navSteps'));
const CheckoutLoader = dynamic(() => import('@components/checkoutLoader/checkoutLoader'));
import { useQuery } from 'react-query';
import { analytics } from '@libs/analytics';
import { event as fbqEvent } from '@libs/fbpixel';
import { handleApiRequest, getResponseById } from '@libs/api';
import useRequest from '@hooks/useRequest/useRequest';
import {
  ButtonWrapper,
  ErrorContent,
  FizetesPageWrapper,
  FormActions,
  FormCol,
  FormImage,
  FormRow,
  NavStepsCol,
  NavStepsRow,
  SuccessContent,
  SuccessText,
  TextBlock,
  TextLine,
  Title,
} from '@components/pages/fizetesPage.styled';

import ImageBookstack from '@assets/images/elements/bookstack.svg';
import ImageSadDocu from '@assets/images/elements/sad-docu.svg';
import { yuspify } from '@libs/yuspify';
import settingsVars from "@vars/settingsVars";

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    'checkout-payment-check': {
      method: 'POST',
      path: '/order',
      ref: 'get',
      request_id: 'checkout-payment-check',
      body: {
        order_number: '',
      },
    },
    'checkout-cart-items-get': {
      method: 'POST',
      path: '/carts',
      ref: 'get',
      request_id: 'checkout-cart-items-get',
      body: {
        guest_token: null,
      },
    },
  },
};

export default function FizetesPage() {
  let analyticsStepAdded = useRef(false);
  let { actualUser, userAddCart } = useUser();
  let router = useRouter();
  let dispatch = useDispatch();
  let [checkoutStateExists, setCheckoutStateExists] = useState(true);

  let checkoutStoreSteps = useSelector((store) => store.checkout.steps);

  let queryPaymentGet = useQuery('checkout-payment-get', () => handleApiRequest(requestPaymentGet.build()), {
    enabled: false,
    cacheTime: 0,
    refetchOnWindowFocus: false,
    refetchOnMount: false,
    staleTime: 0,
    onSettled: (data) => {
      let checkoutPaymentCheckResponse = getResponseById(data, 'checkout-payment-check');
      let isOkPayment = router.query.i;
      let settings = settingsVars.get(url.getHost());

      if (checkoutPaymentCheckResponse?.success) {
        // Order is error
        if (checkoutPaymentCheckResponse.body.order.status === 'error') {
          // We need tore reload the data if CARD payment made a roudtrip
          if (checkoutPaymentCheckResponse.body.order.payment_method === 'card') {
            try {
              let orderStorageContent = sessionStorage.getItem(`${settings.key}-checkout-state`);

              if (!orderStorageContent) throw new Error('Saved state could not be found in LocalStorage');

              let orderStorage = JSON.parse(orderStorageContent);
              let orderSteps = orderStorage[checkoutPaymentCheckResponse.body.order.order_id];

              if (!orderSteps) throw new Error('No saved state for this order ID could be found in LocalStorage');

              // All is fine, we restore state
              !checkoutStateExists && setCheckoutStateExists(true);
              dispatch(updateCheckout({ steps: orderSteps.steps }));
            } catch (error) {
              console.log(error.message);
              checkoutStateExists && setCheckoutStateExists(false);
            }
          }
        }
        // Order went ok
        else {
          if (actualUser) {
            // Updating user cart
            requestCartGet.addRequest('checkout-cart-items-get');

            if (actualUser?.type === 'guest') {
              requestCartGet.modifyHeaders((currentHeader) => {
                currentHeader['Authorization'] = null;
              });

              requestCartGet.modifyRequest('checkout-cart-items-get', (currentRequest) => {
                currentRequest.body.guest_token = actualUser.token;
              });
            } else if (actualUser?.type === 'user') {
              requestCartGet.modifyHeaders((currentHeader) => {
                currentHeader['Authorization'] = `Bearer ${actualUser.token}`;
              });

              requestCartGet.modifyRequest('checkout-cart-items-get', (currentRequest) => {
                currentRequest.body.guest_token = null;
              });
            }

            requestCartGet.commit();

            // Sending Analytics
            try {
              let orderStorageItems = sessionStorage.getItem(`${settings.key}-checkout-items`);

              if (!orderStorageItems) throw new Error('No saved items');

              let orderStorage = JSON.parse(orderStorageItems);
              let { transaction_id } = orderStorage;

              if (!transaction_id) throw new Error('No saved items');
              if (transaction_id !== router.query.slug) throw new Error('Not same order');

              let { value, items } = orderStorage;
              let itemsIdArray = [];

              try {
                items.forEach((item) => {
                  itemsIdArray.push(`"${item.id}"`);
                });
              } catch (error) {
                console.log(error);
              }

              if (isOkPayment === 'true') {
                analytics.addPurchase(orderStorage);
                fbqEvent('Purchase', {
                  content_ids: `[${itemsIdArray.join(',')}]`,
                  value: String(value),
                  currency: 'HUF',
                });
                try {
                  items.forEach((item) => {
                    yuspify.buyEvent(item.id, orderStorage.transaction_id, item.price)
                  });
                } catch (error) {
                  console.log(error);
                }
              }
            } catch (error) {
              console.log(error.message);
            }
          }
        }
      }

      // Removig saved state
      sessionStorage.removeItem(`${settings.key}-checkout-items`);

      if (isOkPayment === 'true') {
        setTimeout(() => {
          router.replace(`/penztar/fizetes/${router.query.slug}`);
        },2000);
      }
    },
  });

  let queryCartGet = useQuery('checkout-cart-items-get', () => handleApiRequest(requestCartGet.build()), {
    enabled: false,
    refetchOnWindowFocus: false,
    refetchOnMount: false,
    staleTime: 0,
    onSuccess: (data) => {
      let cartItems = getResponseById(data, 'checkout-cart-items-get');

      if (cartItems?.success) {
        userAddCart(cartItems.body);
      }
    },
  });

  let requestPaymentGet = useRequest(requestTemplates, queryPaymentGet);
  let requestCartGet = useRequest(requestTemplates, queryCartGet);

  let handleBackToMainButtonClick = useCallback(() => {
    router.push('/');
  }, []);

  let handleUserOrdersButtonClick = useCallback(() => {
    router.push('/profil/rendeleseim');
  }, []);

  let handleRetryButtonClick = useCallback(() => {
    router.push('/penztar/osszesites');
  }, []);

  let handlePaymentTypeButtonClick = useCallback(() => {
    if (!checkoutStoreSteps?.summary?.payment_methods?.transfer.active) return;
    dispatch(updateSummaryPaymentMethod('transfer'));
    router.push('/penztar/osszesites');
  }, [checkoutStoreSteps]);

  useEffect(() => {
    if (!router.query.slug) return;

    requestPaymentGet.resetRequest();
    requestPaymentGet.addRequest('checkout-payment-check');

    requestPaymentGet.modifyRequest('checkout-payment-check', (requestObject) => {
      requestObject.body.order_number = router.query.slug;
    });

    requestPaymentGet.commit();
  }, [router]);

  // Analytics
  useEffect(() => {
    if (!actualUser || analyticsStepAdded.current || !queryPaymentGet.isFetched) return;

    let checkoutPaymentCheckResponse = getResponseById(queryPaymentGet.data, 'checkout-payment-check');

    let cartItems = [];

    for (const cartItem of actualUser.customer.cart.cart_items) {
      cartItems.push({
        id: cartItem.id,
        name: cartItem.title,
        list_name: router.route,
        brand: null,
        category: cartItem.type === 0 ? 'book' : 'ebook',
        variant: cartItem.type === 0 ? 'book' : 'ebook',
        list_position: 1,
        quantity: 1,
        price: cartItem.price_sale,
      });
    }

    // Step
    //analytics.progressCheckout(cartItems);
    analytics.progressCheckout({
      checkout_step: 4,
      items: cartItems,
    });

    // Options
    if (checkoutPaymentCheckResponse?.success) {
      analytics.optionsCheckout({
        checkout_step: 4,
        checkout_option: checkoutPaymentCheckResponse.body.order.status,
      });
    }

    analyticsStepAdded.current = true;
  }, [actualUser, queryPaymentGet]);

  let checkoutPaymentCheckResponse = getResponseById(queryPaymentGet.data, 'checkout-payment-check');

  // Handling card payment
  return (
    <FizetesPageWrapper>
      <PageHead></PageHead>
      <Header></Header>
      <Content>
        <SiteColContainer>
          <NavStepsRow className="row">
            <NavStepsCol className="col-md-8 offset-md-2">
              <NavSteps
                activeSpot={4}
                failedSpot={
                  queryPaymentGet.isFetched &&
                  (!checkoutPaymentCheckResponse.success || checkoutPaymentCheckResponse?.body.order.status === 'error') &&
                  3
                }
              ></NavSteps>
            </NavStepsCol>
          </NavStepsRow>
          <FormRow className="row">
            <FormCol className="col-md-8 col-lg-6 offset-md-2 offset-lg-3">
              {queryPaymentGet.isFetching && <CheckoutLoader>Ellenőrzöm a rendelést...</CheckoutLoader>}
              {checkoutPaymentCheckResponse?.success && (
                <>
                  {checkoutPaymentCheckResponse.body.order.status === 'success' && (
                    <SuccessContent>
                      <FormImage>
                        <ImageBookstack></ImageBookstack>
                      </FormImage>
                      <Title>Rendelésed megkaptuk</Title>
                      <SuccessText>
                        <TextBlock>
                          <TextLine>{getPaymentTypeText(checkoutPaymentCheckResponse.body.order.payment_method)}</TextLine>
                          <TextLine>
                            A rendelésedről e-mailt küldtünk a vásárlás során megadott e-mail-címre. (
                            <strong>{checkoutPaymentCheckResponse.body.order.email}</strong>). Ha nem érkezett meg, kérjük, hogy nézd meg a{' '}
                            <strong>kéretlen üzenetek</strong> vagy <strong>SPAM</strong> mappában is. Ha továbbra sem kaptál e-mail kérjük, hogy írj
                            a <a href="mailto:webshop@alomgyar.hu">webshop@alomgyar.hu</a> címre. <strong>Köszönjük!</strong>
                          </TextLine>
                        </TextBlock>
                        <TextBlock>
                          <TextLine>
                            Rendelésed száma: <strong>{checkoutPaymentCheckResponse.body.order.order_id}</strong>
                          </TextLine>
                        </TextBlock>
                      </SuccessText>
                      <FormActions>
                        <ButtonWrapper>
                          <Link href="/" passHref>
                            <Button type="primary" buttonWidth="100%" buttonHeight="50px" onClick={handleBackToMainButtonClick}>
                              Vissza a nyitólapra
                            </Button>
                          </Link>
                        </ButtonWrapper>
                        {actualUser?.type === 'user' && (
                          <ButtonWrapper>
                            <Link href="/profil/rendeleseim" passHref>
                              <Button type="secondary" buttonWidth="100%" buttonHeight="50px" onClick={handleUserOrdersButtonClick}>
                                Rendeléseim
                              </Button>
                            </Link>
                          </ButtonWrapper>
                        )}
                      </FormActions>
                    </SuccessContent>
                  )}
                </>
              )}
              {queryPaymentGet.isFetched && checkoutPaymentCheckResponse?.success && (
                <>
                  {checkoutPaymentCheckResponse.body.order.status === 'error' && (
                    <ErrorContent>
                      <FormImage>
                        <ImageSadDocu></ImageSadDocu>
                      </FormImage>
                      <Title>Rendelésed sikertelen</Title>
                      <SuccessText>
                        <TextBlock>
                          <TextLine>{getPaymentTypeText(checkoutPaymentCheckResponse.body.order.payment_method)}</TextLine>
                          <TextLine>A folyamat megszakadt, kérjük add le a rendelésed újra.</TextLine>
                        </TextBlock>
                        <TextBlock>
                          <TextLine>
                            Rendelésed száma: <strong>{checkoutPaymentCheckResponse.body.order.order_id}</strong>
                          </TextLine>
                        </TextBlock>
                      </SuccessText>
                      {checkoutStateExists && (
                        <FormActions>
                          <ButtonWrapper>
                            <Button type="primary" buttonWidth="100%" buttonHeight="50px" onClick={handleRetryButtonClick}>
                              Újrapróbálkozom
                            </Button>
                          </ButtonWrapper>
                          {checkoutStoreSteps?.summary?.payment_methods?.transfer.active && (
                            <ButtonWrapper>
                              <Button type="secondary" buttonWidth="100%" buttonHeight="50px" onClick={handlePaymentTypeButtonClick}>
                                Váltás előre utalásra
                              </Button>
                            </ButtonWrapper>
                          )}
                        </FormActions>
                      )}
                    </ErrorContent>
                  )}
                </>
              )}
              {queryPaymentGet.isFetched && !checkoutPaymentCheckResponse?.success && (
                <>
                  <ErrorContent>
                    <FormImage>
                      <ImageSadDocu></ImageSadDocu>
                    </FormImage>
                    <Title>Valami nem sikerült</Title>
                  </ErrorContent>
                </>
              )}
            </FormCol>
          </FormRow>
        </SiteColContainer>
      </Content>
      <Footer></Footer>
    </FizetesPageWrapper>
  );

  function getPaymentTypeText(paymentType) {
    switch (paymentType) {
      case 'card':
        return 'A bankkártyával való fizetést választottad.';
      case 'transfer':
        return 'Az előre átutalást választottad.';
      case 'cash_on_delivery':
        return 'Az utánvéttel való fizetést választottad.';
      default:
        return '';
    }
  }
}
