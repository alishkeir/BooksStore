import dynamic from 'next/dynamic';
import { useCallback, useEffect, useState, useRef } from 'react';
import { useRouter } from 'next/router';
import Link from 'next/link';
import { useDispatch, useSelector } from 'react-redux';
import { updateSummaryPaymentMethods, updateSummaryPaymentMethod, updateSummaryComment, updateSummaryPhone } from '@store/modules/checkout';
const PageHead = dynamic(() => import('@components/pageHead/pageHead'));
const InputRadioBlock = dynamic(() => import('@components/inputRadioBlock/inputRadioBlock'));
import useUser from '@hooks/useUser/useUser';
import url from '@libs/url';
const Button = dynamic(() => import('@components/button/button'));
const CheckoutLoader = dynamic(() => import('@components/checkoutLoader/checkoutLoader'));
const Header = dynamic(() => import('@components/header/header'));
const Content = dynamic(() => import('@components/content/content'));
const SiteColContainer = dynamic(() => import('@components/siteColContainer/siteColContainer'));
const Footer = dynamic(() => import('@components/footer/footer'));
const SummaryDrawer = dynamic(() => import('@components/summaryDrawer/summaryDrawer'), { ssr: false });
const NavSteps = dynamic(() => import('@components/navSteps/navSteps'));
const SectionTitle = dynamic(() => import('@components/sectionTitle/sectionTitle'));
import currency from '@libs/currency';
import { useQuery, useMutation } from 'react-query';
import { handleApiRequest, getResponseById } from '@libs/api';
import useRequest from '@hooks/useRequest/useRequest';
import { analytics } from '@libs/analytics';
const InputText = dynamic(() => import('@components/inputText/inputText'));
const InputTextarea = dynamic(() => import('@components/inputTextarea/inputTextarea'));
import { Cookies } from 'react-cookie';
import {
  ButtonWrapper,
  CommentWrapper,
  PhoneWrapper,
  FormActions,
  FormBorgun,
  FormCol,
  FormRow,
  NavStepsCol,
  NavStepsRow,
  SummarBlockCol,
  Summary,
  SummaryBlock,
  SummaryBlockContainer,
  SummaryBlockLine,
  SummaryBlockLineAuthor,
  SummaryBlockTitle,
  OsszesitesPageWrapper,
  TabDeliveryCost,
  TabWrapperCard,
  TabWrapperCardIcon,
  TabWrapperCardIcons,
  TabWrapperDelivery,
  TabWrapperTransfer,
  Tabs,
  Title,
  Total,
  TotalTitle,
  TotalValue,
  UserSelectControl,
} from '@components/pages/osszesitesPage.styled';

import ImageVisa from '@assets/images/logos/visa-color.png';
import ImageMastercard from '@assets/images/logos/mastercard-color.png';
import OptimizedImage from '@components/Images/OptimizedImage';
import settingsVars from "@vars/settingsVars";

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    'checkout-summary-order-check': {
      method: 'POST',
      path: '/order',
      ref: 'check',
      request_id: 'checkout-summary-order-check',
      body: {
        guest_token: null,
        steps: null,
      },
    },
    'checkout-summary-order-create': {
      method: 'POST',
      path: '/order',
      ref: 'create',
      request_id: 'checkout-summary-order-create',
      body: {
        mode: null,
        guest_token: null,
        steps: null,
      },
    },
    'checkout-summary-cart-get': {
      method: 'POST',
      path: '/carts',
      ref: 'get',
      request_id: 'checkout-summary-cart-get',
      body: {
        guest_token: null,
      },
    },
  },
};

export default function OsszesitesPage() {
  let currentOrderId = useRef(0);

  let router = useRouter();
  let dispatch = useDispatch();

  let { authChecking, actualUser } = useUser();
  let summaryComment = useSelector((store) => store.checkout.steps.summary.comment);
  let summaryPhone = useSelector((store) => store.checkout.steps.summary.phone);
  let stepShippingValid = useSelector((store) => store.checkout.steps.shipping.valid);
  let checkoutStoreSteps = useSelector((store) => store.checkout.steps);
  let checkoutStoreSummaryPaymentMethod = useSelector((store) => store.checkout.steps.summary.payment_method);
  let [paymentInProgress, setPaymentInProgress] = useState(false);
  let [formBorgun, setFormBorgun] = useState(null);
  let [inputErrors, setInputErrors] = useState({ phone: '' });
  let [disableContinueToPayment] = useState(false);
  let [affiliateData, setAffiliateData] = useState(null);
  let [firstLoad, setFirstLoad] = useState(true);


  let shippingAddress = useSelector((store) => store.checkout.steps.shipping.types);

  // Previous steps valid?
  useEffect(() => {
    if (!stepShippingValid) router.replace('/penztar/szallitasi-adatok');
  }, [stepShippingValid]);

  let cartItemsForNewGa = [];

  let querySummaryGet = useQuery('checkout-summary-get', () => handleApiRequest(requestSummaryGet.build()), {
    enabled: false,
    cacheTime: 0,
    refetchOnWindowFocus: false,
    refetchOnMount: false,
    staleTime: 0,

    onSettled: (response) => {
      let querySummaryGetResponse = getResponseById(response, 'checkout-summary-order-check');

      if (querySummaryGetResponse?.success) {
        dispatch(updateSummaryPaymentMethods(querySummaryGetResponse.body.steps.summary.payment_methods));
        if(firstLoad) {
          if (querySummaryGetResponse.body.steps.summary.payment_methods.card.active) {
            dispatch(updateSummaryPaymentMethod("card"));
          } else {
            for (const key in querySummaryGetResponse.body.steps.summary.payment_methods) {
              if (querySummaryGetResponse.body.steps.summary.payment_methods[key].active) {
                dispatch(updateSummaryPaymentMethod(key));
                break;
              }
            }
          }
          setFirstLoad(false);
        }
      }
    },
  });

  let querySummaryCreate = useMutation('checkout-summary-create', (requestUpdateBuild) => handleApiRequest(requestUpdateBuild));

  let requestSummaryGet = useRequest(requestTemplates, querySummaryGet);
  let requestSummaryCreate = useRequest(requestTemplates, querySummaryCreate);

  const selectedShippingType = checkoutStoreSteps.shipping.types[checkoutStoreSteps.shipping.type];

  let settings = settingsVars.get(url.getHost());


  // Changing payment type
  let handlePaymentTypeTabClick = useCallback(
    (type) => {
      // Resetting errors
      if (checkoutStoreSummaryPaymentMethod !== type) {
        dispatch(updateSummaryPaymentMethod(type));
      }
    },
    [checkoutStoreSummaryPaymentMethod],
  );

  // If borgun form is created we submit
  let handleFormBorgunSubmit = useCallback(
    (elem) => {
      if (!elem) return;

      sessionStorage.setItem(
        `${settings.key}-checkout-state`,
        JSON.stringify({
          [currentOrderId.current]: {
            created: Date.now(),
            steps: checkoutStoreSteps,
          },
        }),
      );

      elem.submit();
    },
    [checkoutStoreSteps],
  );

  // Hiting submit on next page as user
  let handleUserSubmitButtonClick = useCallback(() => {
    if (checkoutStoreSummaryPaymentMethod) {
      import('joi').then((module) => {
        let joi = module.default;

        let schema = joi.object({
          phone: joi
            .string()
            .required()
            .max(20)
            .pattern(/^\+{0,1}\d{1,20}$/),
        });

        let validation = schema.validate({ phone: summaryPhone }, { abortEarly: false });

        if (validation.error) {
          let newErrorState = { ...inputErrors };

          validation.error.details.forEach((error) => {
            switch (error.type) {
              case 'string.empty':
                newErrorState[error.context.key] = 'Ez a mező nem lehet üres';
                break;

              default:
                newErrorState[error.context.key] = 'Hibás mező';
                break;
            }
            console.log(validation.error);
            setInputErrors(newErrorState);
          });
        } else {
          setInputErrors({ ...inputErrors });

          // Start payment
          setPaymentInProgress(true);

          // Summary check
          requestSummaryCreate.resetRequest();

          // Regular user
          requestSummaryCreate.addRequest('checkout-summary-order-create');

          requestSummaryCreate.modifyHeaders((currentHeader) => {
            currentHeader['Authorization'] = `Bearer ${actualUser.token}`;
          });

          requestSummaryCreate.modifyRequest('checkout-summary-order-create', (requestObject) => {
            requestObject.body.mode = process.env.NODE_ENV === 'development' ? 'development' : 'production';
            requestObject.body.steps = checkoutStoreSteps;
            requestObject.body.affiliate = affiliateData;
          });
          requestSummaryCreate.commit({
            onSuccess: (result) => {
              let checkoutSummaryOrderCreateResponse = getResponseById(result, 'checkout-summary-order-create');
              let checkoutSummaryOrderCheckResponse = getResponseById(querySummaryGet.data, 'checkout-summary-order-check');
              if (checkoutSummaryOrderCreateResponse.success) {
                currentOrderId.current = checkoutSummaryOrderCreateResponse.body.order.order_id;

                // Analytics
                analytics.optionsCheckout({
                  checkout_step: 3,
                  checkout_option: checkoutStoreSummaryPaymentMethod,
                });

                // Saving cart for analytics

                {
                  let cartItems = [];

                  for (let cartItem of actualUser.customer.cart.cart_items) {
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

                    cartItemsForNewGa.push({
                      item_id: cartItem.id,
                      item_name: cartItem.title,
                      list_name: router.route,
                      item_brand: null,
                      item_category: cartItem.type === 0 ? 'book' : 'ebook',
                      item_variant: cartItem.type === 0 ? 'book' : 'ebook',
                      list_position: 1,
                      quantity: 1,
                      price: cartItem.price_sale,
                    });
                  }

                  sessionStorage.setItem(
                    `${settings.key}-checkout-items`,
                    JSON.stringify({
                      transaction_id: checkoutSummaryOrderCreateResponse.body.order.order_id,
                      affiliation: null,
                      value: actualUser.customer.cart.total_amount,
                      currency: 'HUF',
                      tax: 0,
                      shipping: checkoutSummaryOrderCheckResponse.body.steps.summary.shipping_fee,
                      items: cartItems,
                    }),
                  );
                }

                const street = shippingAddress.home?.user_selected_address?.address || '';
                const city = shippingAddress.home?.user_selected_address?.city || '';
                const postalCode = shippingAddress.home?.user_selected_address?.zip_code || '';
                const country = shippingAddress.home?.user_selected_address?.country?.name || '';
                const firstname = actualUser?.customer?.firstname || '';
                const lastname = actualUser?.customer?.lastname || '';
                const email = actualUser?.customer?.email || '';
                const phone = actualUser?.customer?.phone || '';

                window?.gtag('set', 'user_data', {
                  email,
                  phone_number: phone,
                  address: {
                    first_name: firstname,
                    last_name: lastname,
                    street,
                    city,
                    postal_code: postalCode,
                    country,
                  },
                });


                let tokenImporved = "";
                if(settings.key === "ALOMGYAR"){
                  tokenImporved = process.env.NEXT_PUBLIC_GA_ID_IMPROVED_ALOMGYAR+'/'+process.env.NEXT_PUBLIC_GA_AD_ID_ALOMGYAR;
                }else if(settings.key === "OLCSOKONYVEK"){
                  tokenImporved = process.env.NEXT_PUBLIC_GA_ID_IMPROVED_OLCSOKONYVEK+'/'+process.env.NEXT_PUBLIC_GA_AD_ID_OLCSOKONYVEK;
                }else if(settings.key === "NAGYKER"){
                  tokenImporved = process.env.NEXT_PUBLIC_GA_ID_IMPROVED_NAGYKER;
                }

                window?.gtag('event', 'conversion', {
                  send_to: `${tokenImporved}`,
                  value: actualUser.customer.cart.total_amount,
                  currency: 'HUF',
                  transaction_id: checkoutSummaryOrderCreateResponse.body.order.order_id,
                });

                if (checkoutStoreSummaryPaymentMethod === 'card') {
                  analytics.addPaymentInfo({
                    payment_type: checkoutStoreSummaryPaymentMethod,
                    items: cartItemsForNewGa,
                  });
                  setFormBorgun(createFormBorgun(checkoutSummaryOrderCreateResponse.body));
                } else if (checkoutStoreSummaryPaymentMethod === 'transfer') {
                  analytics.addPaymentInfo({
                    payment_type: checkoutStoreSummaryPaymentMethod,
                    items: cartItemsForNewGa,
                  });
                  router.push(`/penztar/fizetes/${checkoutSummaryOrderCreateResponse.body.order.order_id}?i=true`);
                } else if (checkoutStoreSummaryPaymentMethod === 'cash_on_delivery') {
                  analytics.addPaymentInfo({
                    payment_type: checkoutStoreSummaryPaymentMethod,
                    items: cartItemsForNewGa,
                  });
                  router.push(`/penztar/fizetes/${checkoutSummaryOrderCreateResponse.body.order.order_id}?i=true`);
                }
              }
            },
          });
        }
      });
    }
  }, [checkoutStoreSummaryPaymentMethod, actualUser, checkoutStoreSteps]);

  function createFormBorgun(borgun) {
    // Generating form element
    return (
      <FormBorgun>
        <form id="borgun-form" action={borgun.options.action} method={borgun.options.method} ref={handleFormBorgunSubmit}>
          {(() => {
            let borgunInputs = [];

            for (let key in borgun.post_data) {
              borgunInputs.push(<input type="text" name={key} value={borgun.post_data[key]} readOnly key={key} />);
            }

            return borgunInputs;
          })()}
          <input type="submit" name="PostButton" readOnly />
        </form>
      </FormBorgun>
    );
  }

  // No user, no page
  useEffect(() => {
    return () => {
      paymentInProgress && setPaymentInProgress(false);
    };
  }, []);

  // No user, no page
  useEffect(() => {
    if (authChecking) return;
    if (actualUser?.type !== 'user') router.push('/');
  }, [actualUser]);

  useEffect(() => {
    // No user
    if (actualUser?.type !== 'user') return;

    // Summary check
    requestSummaryGet.resetRequest();

    // Regular user
    requestSummaryGet.addRequest('checkout-summary-order-check', 'checkout-summary-cart-get');

    requestSummaryGet.modifyHeaders((currentHeader) => {
      currentHeader['Authorization'] = `Bearer ${actualUser.token}`;
    });

    requestSummaryGet.modifyRequest('checkout-summary-order-check', (requestObject) => {
      requestObject.body.steps = checkoutStoreSteps;
    });

    requestSummaryGet.commit();
  }, [actualUser, stepShippingValid, checkoutStoreSummaryPaymentMethod]);

  // If user has phone number, we prepopulate
  useEffect(() => {
    if (!actualUser) return;

    let phone = actualUser?.customer?.phone;

    if (phone && !summaryPhone) {
      dispatch(updateSummaryPhone(phone));
    }
  }, [actualUser]);

  // Analytics
  useEffect(() => {
    if (!actualUser) return;

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

    //analytics.progressCheckout(cartItems);
    analytics.progressCheckout({
      checkout_step: 3,
      items: cartItems,
    });
  }, [actualUser]);

  // add affiliate data to the order request body
  useEffect(() => {
    if (!actualUser) return;
    const cookies = new Cookies();
    let affiliateCode = cookies.get('affiliate_code_' + actualUser.customer.id);
    let affiliateSettings = actualUser.customer.affiliate_settings;
    if (affiliateCode && affiliateSettings && Object.keys(affiliateSettings).length > 0) {
      setAffiliateData(() => {
        return {
          affiliate_code: affiliateCode,
          affiliate_commission_percentage: affiliateSettings.affiliate_commission_percentage,
        }
      });
    }
  }, [actualUser]);

  let checkoutSummaryOrderResponse = getResponseById(querySummaryGet.data, 'checkout-summary-order-check');
  let checkoutSummaryCartResponse = getResponseById(querySummaryGet.data, 'checkout-summary-cart-get');

  if (actualUser?.type !== 'user' || !stepShippingValid) return null;

  return (
    <OsszesitesPageWrapper>
      <PageHead></PageHead>
      <Header></Header>
      <Content>
        <SiteColContainer>
          <NavStepsRow className="row">
            <NavStepsCol className="col-md-8 offset-md-2">
              <NavSteps activeSpot={2}></NavSteps>
            </NavStepsCol>
          </NavStepsRow>
          {paymentInProgress && (
            <FormRow className="row">
              <FormCol className="col-md-8 col-lg-6 offset-md-2 offset-lg-3">
                <CheckoutLoader>Készülök a fizetéshez...</CheckoutLoader>
                {formBorgun && formBorgun}
              </FormCol>
            </FormRow>
          )}
          {!paymentInProgress && (
            <FormRow className="row">
              <FormCol className="col-md-8 col-lg-6 offset-md-2 offset-lg-3">
                <Title>Összesítés</Title>
                {querySummaryGet.isFetched && !checkoutSummaryOrderResponse?.success && (
                  <SectionTitle mb={20}>Valami nem sikerült az összesítésnél :(</SectionTitle>
                )}
                {checkoutSummaryOrderResponse?.success && (
                  <>
                    <SectionTitle mb={20}>Hogyan szeretnél fizetni?</SectionTitle>
                    <UserSelectControl>
                      <Tabs>
                        {checkoutSummaryOrderResponse.body.steps.summary.payment_methods.card.active && (
                            <TabWrapperCard>
                              <TabDeliveryCost>
                                {currency.format(checkoutSummaryOrderResponse.body.steps.summary.payment_methods.card.fee)}
                              </TabDeliveryCost>
                              <TabWrapperCardIcons>
                                <TabWrapperCardIcon>
                                  <OptimizedImage {...ImageVisa} layout="fixed" alt="Visa"></OptimizedImage>
                                </TabWrapperCardIcon>
                                <TabWrapperCardIcon>
                                  <OptimizedImage {...ImageMastercard} layout="fixed" alt="Mastercard"></OptimizedImage>
                                </TabWrapperCardIcon>
                              </TabWrapperCardIcons>
                              <InputRadioBlock
                                label="Bankkártyával"
                                name="payment-card"
                                checked={checkoutStoreSummaryPaymentMethod === 'card'}
                                onClick={() => handlePaymentTypeTabClick('card')}
                              ></InputRadioBlock>
                            </TabWrapperCard>
                          )}
                        {checkoutSummaryOrderResponse.body.steps.summary.payment_methods.transfer.active && (
                            <TabWrapperTransfer>
                              <TabDeliveryCost>
                                {currency.format(checkoutSummaryOrderResponse.body.steps.summary.payment_methods.transfer.fee)}
                              </TabDeliveryCost>
                              <InputRadioBlock
                                label="Előre utalással"
                                name="payment-transfer"
                                checked={checkoutStoreSummaryPaymentMethod === 'transfer'}
                                onClick={() => handlePaymentTypeTabClick('transfer')}
                              ></InputRadioBlock>
                            </TabWrapperTransfer>
                          )}
                        {checkoutSummaryOrderResponse.body.steps.summary.payment_methods.cash_on_delivery.active && (
                          <TabWrapperDelivery>
                            <TabDeliveryCost>
                              {currency.format(checkoutSummaryOrderResponse.body.steps.summary.payment_methods.cash_on_delivery.fee)}
                            </TabDeliveryCost>
                            <InputRadioBlock
                              label={namePaymentMethod('cash_on_delivery', checkoutStoreSteps.shipping.type)}
                              name="payment-cash"
                              checked={checkoutStoreSummaryPaymentMethod === 'cash_on_delivery'}
                              onClick={() => handlePaymentTypeTabClick('cash_on_delivery')}
                            ></InputRadioBlock>
                          </TabWrapperDelivery>
                        )}
                      </Tabs>
                    </UserSelectControl>
                    {querySummaryGet.isFetching && <CheckoutLoader>Összesítek...</CheckoutLoader>}
                    {querySummaryGet.isFetched && !querySummaryGet.isFetching && (
                      <>
                        <SectionTitle mb={20}>Összesítő</SectionTitle>
                        <Summary>
                          {checkoutSummaryCartResponse?.success && (
                            <SummaryDrawer title="Kosár" value={currency.format(checkoutSummaryCartResponse.body.total_amount)}>
                              {checkoutSummaryCartResponse.body.cart_items.map((cartItem) => (
                                <SummaryBlock key={cartItem.id}>
                                  <SummaryBlockContainer>
                                    <SummarBlockCol flex1>
                                      <SummaryBlockTitle>{cartItem.title}</SummaryBlockTitle>
                                      <SummaryBlockLine>
                                        {cartItem.authors.map((author) => (
                                          <SummaryBlockLineAuthor key={author.id}>{author.title}</SummaryBlockLineAuthor>
                                        ))}
                                      </SummaryBlockLine>
                                    </SummarBlockCol>
                                    <SummarBlockCol>
                                      <SummaryBlockLine>
                                        {cartItem.quantity} x {currency.format(cartItem.price_sale)}
                                      </SummaryBlockLine>
                                    </SummarBlockCol>
                                  </SummaryBlockContainer>
                                </SummaryBlock>
                              ))}
                            </SummaryDrawer>
                          )}
                          {checkoutStoreSteps.shipping.type !== null && (
                            <SummaryDrawer
                              title="Szállítás"
                              value={
                                checkoutSummaryOrderResponse?.success && currency.format(checkoutSummaryOrderResponse.body.steps.summary.shipping_fee)
                              }
                            >
                              <SummaryBlock>
                                <SummaryBlockTitle>Szállítási mód</SummaryBlockTitle>
                                <SummaryBlockLine>{nameDeliveryMethod(checkoutStoreSteps.shipping.type)}</SummaryBlockLine>
                              </SummaryBlock>
                              <SummaryBlock>
                                <SummaryBlockTitle>Szállítási cím</SummaryBlockTitle>
                                {['dpd', 'sameday'].includes(checkoutStoreSteps.shipping.type) && (
                                  <>
                                    {selectedShippingType.user_selected_address && (
                                      <>
                                        <SummaryBlockLine>
                                          {selectedShippingType.user_selected_address.last_name}{' '}
                                          {selectedShippingType.user_selected_address.first_name}
                                        </SummaryBlockLine>
                                        <SummaryBlockLine>{selectedShippingType.user_selected_address.address}</SummaryBlockLine>
                                        <SummaryBlockLine>
                                          {selectedShippingType.user_selected_address.city},{' '}
                                          {selectedShippingType.user_selected_address.zip_code}
                                        </SummaryBlockLine>
                                        <SummaryBlockLine>{selectedShippingType.user_selected_address.comment}</SummaryBlockLine>
                                      </>
                                    )}

                                    {!selectedShippingType.user_selected_address && (
                                      <>
                                        <SummaryBlockLine>
                                          {selectedShippingType.inputs.last_name}{' '}
                                          {selectedShippingType.inputs.first_name}
                                        </SummaryBlockLine>
                                        <SummaryBlockLine>{selectedShippingType.inputs.address}</SummaryBlockLine>
                                        <SummaryBlockLine>
                                          {selectedShippingType.inputs.city},{' '}
                                          {selectedShippingType.inputs.zip_code}
                                        </SummaryBlockLine>
                                        <SummaryBlockLine>{selectedShippingType.inputs.comment}</SummaryBlockLine>
                                      </>
                                    )}
                                  </>
                                )}

                                {checkoutStoreSteps.shipping.type === 'shop' && (
                                  <>
                                    <SummaryBlockLine>{checkoutStoreSteps.shipping.types.shop.selected_shop.address}</SummaryBlockLine>
                                    <SummaryBlockLine>
                                      {checkoutStoreSteps.shipping.types.shop.selected_shop.city}{' '}
                                      {checkoutStoreSteps.shipping.types.shop.selected_shop.zip_code}
                                    </SummaryBlockLine>
                                  </>
                                )}

                                {checkoutStoreSteps.shipping.type === 'box' && (
                                  <>
                                    <SummaryBlockLine>{checkoutStoreSteps.shipping.types.box.selected_box.name}</SummaryBlockLine>
                                    <SummaryBlockLine>{checkoutStoreSteps.shipping.types.box.selected_box.address}</SummaryBlockLine>
                                    <SummaryBlockLine>
                                      {checkoutStoreSteps.shipping.types.box.selected_box.city},{' '}
                                      {checkoutStoreSteps.shipping.types.box.selected_box.zip}
                                    </SummaryBlockLine>
                                  </>
                                )}
                              </SummaryBlock>
                            </SummaryDrawer>
                          )}
                          <SummaryDrawer
                            title="Fizetési mód"
                            value={
                              checkoutSummaryOrderResponse?.success &&
                              currency.format(checkoutSummaryOrderResponse.body.steps.summary.payment_methods[checkoutStoreSummaryPaymentMethod].fee)
                            }
                          >
                            <SummaryBlock>
                              <SummaryBlockTitle>
                                {namePaymentMethod(checkoutStoreSummaryPaymentMethod, checkoutStoreSteps.shipping.type)}
                              </SummaryBlockTitle>
                            </SummaryBlock>
                          </SummaryDrawer>
                          <SummaryDrawer title="Számla adatok">
                            <SummaryBlock>
                              <SummaryBlockTitle>Számla típusa</SummaryBlockTitle>
                              <SummaryBlockLine>
                                {nameBillingMethod(
                                  checkoutStoreSteps.billing.user_selected_address
                                    ? checkoutStoreSteps.billing.user_selected_address.entity_type
                                    : checkoutStoreSteps.billing.inputs.entity_type,
                                )}
                              </SummaryBlockLine>
                            </SummaryBlock>
                            <SummaryBlock>
                              <SummaryBlockTitle>Számlázási cím</SummaryBlockTitle>
                              {checkoutStoreSteps.billing.user_selected_address && (
                                <>
                                  {checkoutStoreSteps.billing.user_selected_address.entity_type === 'business' && (
                                    <SummaryBlockLine>{checkoutStoreSteps.billing.user_selected_address.business_name}</SummaryBlockLine>
                                  )}
                                  <SummaryBlockLine>
                                    {checkoutStoreSteps.billing.user_selected_address.last_name}{' '}
                                    {checkoutStoreSteps.billing.user_selected_address.first_name}
                                  </SummaryBlockLine>
                                  <SummaryBlockLine>{checkoutStoreSteps.billing.user_selected_address.address}</SummaryBlockLine>
                                  <SummaryBlockLine>
                                    {checkoutStoreSteps.billing.user_selected_address.city},{' '}
                                    {checkoutStoreSteps.billing.user_selected_address.zip_code}
                                  </SummaryBlockLine>
                                  <SummaryBlockLine>{checkoutStoreSteps.billing.user_selected_address.comment}</SummaryBlockLine>
                                </>
                              )}

                              {!checkoutStoreSteps.billing.user_selected_address && (
                                <>
                                  <SummaryBlockLine>
                                    {checkoutStoreSteps.billing.inputs.last_name} {checkoutStoreSteps.billing.inputs.first_name}
                                  </SummaryBlockLine>
                                  <SummaryBlockLine>{checkoutStoreSteps.billing.inputs.address}</SummaryBlockLine>
                                  <SummaryBlockLine>
                                    {checkoutStoreSteps.billing.inputs.city}, {checkoutStoreSteps.billing.inputs.zip_code}
                                  </SummaryBlockLine>
                                  <SummaryBlockLine>{checkoutStoreSteps.billing.inputs.comment}</SummaryBlockLine>
                                </>
                              )}
                            </SummaryBlock>
                          </SummaryDrawer>
                        </Summary>
                        <Total>
                          <TotalTitle>Fizetendő összesen</TotalTitle>
                          <TotalValue>
                            {checkoutSummaryOrderResponse?.success && currency.format(checkoutSummaryOrderResponse.body.steps.summary.total)}
                          </TotalValue>
                        </Total>
                        <PhoneWrapper>
                          <InputText
                            label="Telefonszám"
                            value={summaryPhone}
                            error={inputErrors.phone}
                            onChange={(e) => {
                              inputErrors.phone && setInputErrors({ ...inputErrors, phone: '' });
                              dispatch(updateSummaryPhone(e.target.value));
                            }}
                          ></InputText>
                        </PhoneWrapper>
                        <p>
                          Kérjük vegyétek figyelembe, hogy az ajándék könyvjelzők meglepetésszerűen kerülnek a csomagokba. Sajnos a megjegyzés rovat
                          beli kéréseket nem tudunk figyelembe venni.
                        </p>
                        <CommentWrapper>
                          <InputTextarea
                            label="Megjegyzés"
                            value={summaryComment}
                            onChange={(e) => dispatch(updateSummaryComment(e.target.value))}
                          ></InputTextarea>
                        </CommentWrapper>
                        <FormActions>
                          <ButtonWrapper>
                            <Button type="primary" buttonWidth="100%" buttonHeight="50px"
                              onClick={handleUserSubmitButtonClick}
                              disabled={disableContinueToPayment}>
                              Tovább a fizetéshez
                            </Button>
                          </ButtonWrapper>
                          <ButtonWrapper>
                            <Link href="/penztar/szallitasi-adatok" passHref>
                              <Button type="secondary" buttonWidth="100%" buttonHeight="50px">
                                Vissza a szállítási adatokhoz
                              </Button>
                            </Link>
                          </ButtonWrapper>
                        </FormActions>
                      </>
                    )}
                  </>
                )}
              </FormCol>
            </FormRow>
          )}
        </SiteColContainer>
      </Content>
      <Footer></Footer>
    </OsszesitesPageWrapper>
  );

  function nameBillingMethod(method) {
    switch (method) {
      case 'private':
        return 'Magánszemély';
      case 'business':
        return 'Szervezet (cég, könyvtár, egyesület)';

      default:
        return '';
    }
  }

  function nameDeliveryMethod(method) {
    switch (method) {
      case 'home':
        return 'Házhozszállítás';
      case 'dpd':
        return 'DPD házhozszállítás';
      case 'sameday':
        return 'Sameday házhozszállítás';
      case 'shop':
        return 'Álomgyár könyvesbolt';
      case 'box':
        return 'Csomagpont';

      default:
        return '';
    }
  }

  // Naming
  function namePaymentMethod(paymentMethodKey = '', shippingMethodKey = '') {
    switch (paymentMethodKey) {
      case 'card':
        return 'Bankkártya';
      case 'transfer':
        return 'Előre utalás';
      case 'cash_on_delivery':
        return shippingMethodKey === 'shop' ? 'Boltban' : 'Utánvéttel';

      default:
        return '';
    }
  }
}
