import dynamic from 'next/dynamic';
import { useState, useCallback, useEffect } from 'react';
import { useRouter } from 'next/router';
import Link from 'next/link';
import _cloneDeep from 'lodash/cloneDeep';
import { useQuery, useMutation, useQueryClient } from 'react-query';
import { useSelector, useDispatch } from 'react-redux';
import {
  updateShippingType,
  updateShippingValid,
  updateShippingUserSelectedAddress,
  updateShippingSelectedShop,
  updateShippingSelectedBox,
  updateShippingHomeInput,
  updateShippingHomeInputs,
  resetShippingHomeInputs,
  updateShippingHomeError,
  updateShippingHomeErrors,
  resetShippingHomeErrors,
  shippingHomeErrorsDefault,
} from '@store/modules/checkout';
import { analytics } from '@libs/analytics';
import { handleApiRequest, getResponseById } from '@libs/api';

const PageHead = dynamic(() => import('@components/pageHead/pageHead'));
const ShippingInputRadioBlock = dynamic(() => import('@components/inputRadioBlock/shippingInputRadioBlock'));
const CheckoutDeliveryStoreInfo = dynamic(() => import('@components/checkoutDeliveryStoreInfo/checkoutDeliveryStoreInfo'));
import useRequest from '@hooks/useRequest/useRequest';

const AlertBox = dynamic(() => import('@components/alertBox/alertBox'));
const Button = dynamic(() => import('@components/button/button'));
const Dropdown = dynamic(() => import('@components/dropdown/dropdown'));
const Header = dynamic(() => import('@components/header/header'));
const Content = dynamic(() => import('@components/content/content'));
const SiteColContainer = dynamic(() => import('@components/siteColContainer/siteColContainer'));
const Footer = dynamic(() => import('@components/footer/footer'));
const CheckoutBoxMap = dynamic(() => import('@components/checkoutBoxMap/checkoutBoxMap'));
const CheckoutShopMap = dynamic(() => import('@components/checkoutShopMap/checkoutShopMap'));
import useStoreInputs from '@hooks/useStoreInputs/useStoreInputs';
import useUser from '@hooks/useUser/useUser';

const NavSteps = dynamic(() => import('@components/navSteps/navSteps'));
const SectionTitle = dynamic(() => import('@components/sectionTitle/sectionTitle'));
import currency from '@libs/currency';
import { FormContent } from '@components/overlayCardContentAddress/overlayCardContentAddress';
import {
  BoxInfoWrapper,
  BoxMapWrapper,
  BoxTitleInfo,
  ButtonWrapper,
  FormActions,
  FormCol,
  FormRow,
  NavStepsCol,
  NavStepsRow,
  StoreInfoWrapper,
  StoreMapWrapper,
  SzallitasiAdatokPageWrapper,
  TabWrapper,
  Tabs,
  Title,
  UserActionWrapper,
  UserAddressItem,
  UserAddressItemLine,
  UserAddressList,
  UserDropdownWrapper,
  UserFormControls,
  UserSelectControl,
  TabDeliveryCost,
} from '@components/pages/szallitasiAdatokPage.styled';
import { ButtonError } from '@components/pages/osszesitesPage.styled';
import settingsVars from "@vars/settingsVars";
import url from "@libs/url";

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    'checkout-shipping-shoplist-get': {
      method: 'GET',
      path: '/pages/shops',
      ref: 'list',
      request_id: 'checkout-shipping-shoplist-get',
    },
    'checkout-shipping-methods-get': {
      method: 'POST',
      path: '/order',
      ref: 'shipping_methods',
      request_id: 'checkout-shipping-methods-get',
      body: {},
    },
    'checkout-shipping-countries-get': {
      method: 'GET',
      path: '/helpers',
      ref: 'countries',
      request_id: 'checkout-shipping-countries-get',
    },
    'checkout-shipping-addresses-get': {
      method: 'GET',
      path: '/profile/addresses',
      ref: 'customerAddresses',
      request_id: 'checkout-shipping-addresses-get',
      body: {
        type: 'shipping',
      },
    },
    'checkout-shipping-addresses-update': {
      method: 'POST',
      path: '/profile/addresses',
      ref: 'customerAddresses',
      request_id: 'checkout-shipping-addresses-update',
      body: {
        type: 'shipping',
        address_id: null,
        last_name: null,
        first_name: null,
        business_name: null,
        vat_number: null,
        city: null,
        zip_code: null,
        address: null,
        comment: null,
        country_id: null,
        entity_type: 'private',
      },
    },
  },
};

export default function SzallitasiAdatokPage() {
  let [responseErrors, setResponseErrors] = useState(null);
  let [userAddressAddVisible, setUserAddressAddVisible] = useState(false);
  let [shippingMethods, setShippingMethods] = useState([]);
  let [selectedShippingOption, setSelectedShippingOption] = useState(null);
  let [countries, setCountries] = useState([{ id: 1, name: 'Magyarország', fee: 0 }]);
  let [selectedBox, setSelectedBox] = useState();

  let useStoreInput = useStoreInputs(
    'checkout.steps.shipping.types.home.inputs',
    'checkout.steps.shipping.types.home.errors',
    updateShippingHomeInput,
    updateShippingHomeInputs,
    resetShippingHomeInputs,
    updateShippingHomeError,
    updateShippingHomeErrors,
    resetShippingHomeErrors,
  );

  let stepBillingValid = useSelector((store) => store.checkout.steps.billing.valid);
  let deliveryType = useSelector((store) => store.checkout.steps.shipping.type);
  let checkoutSelectedStore = useSelector((store) => store.checkout.steps.shipping.types.shop.selected_shop?.id);
  let checkoutSelectedBox = useSelector((store) => store.checkout.steps.shipping.types.box.selected_box);
  let checkoutUserSelectedAddress = useSelector((store) => store.checkout.steps.shipping.types.home.user_selected_address?.id);
  let { authChecking, actualUser } = useUser();
  let queryClientUserShippingAddressGet = useQueryClient();
  let router = useRouter();
  let dispatch = useDispatch();

  let queryUserShippingAddressGet = useQuery('checkout-shipping-addresses-get', () => handleApiRequest(requestUserShippingAddressGet.build()), {
    enabled: false,
    refetchOnWindowFocus: false,
    refetchOnMount: false,
    staleTime: 0,
    cacheTime: Infinity,
    onSuccess: (data) => {
      let shippingCountriesGetResponse = getResponseById(data, 'checkout-shipping-countries-get');
      let shippingMethodsGetResponse = getResponseById(data, 'checkout-shipping-methods-get');

      if (shippingCountriesGetResponse?.success) {
        let countries = shippingCountriesGetResponse.body.countries.map((country) => ({
          id: country.id,
          name: country.name,
          fee: country.fee,
        }));

        setCountries(countries);
      }

      if (shippingMethodsGetResponse?.success) {
        let methods = shippingMethodsGetResponse.body.shipping_methods;

        // Merge methods with getShippingMethodContent
        methods = methods.map((method) => {
          let methodContent = getShippingMethodContent(method.key, { shopCount: getShopCount() });

          if (methodContent) {
            method = { ...method, ...methodContent };
          }

          return method;
        });

        let settings = settingsVars.get(url.getHost());

        // Add dpd and sameday inside the home object
        methods = methods.map((method) => {
          if (method.key === 'home' && settings.key !== "NAGYKER") {
            method.methods = methods.filter((m) => {
              if (['dpd', 'sameday'].includes(m.key)) {
                return m;
              }
            });
            // Show the cheapest shipping methods first
            method.methods = method.methods.sort((a, b) => {
              return a.fee - b.fee;
            });
          }

          if (!['dpd', 'sameday'].includes(method.key)) return method;
        });
        // Remove the undefined from methods
        methods = methods.filter((method) => method !== undefined);

        setShippingMethods(methods);
      }
    },
  });

  let queryShopListGet = useQuery('checkout-shipping-shoplist-get', () => handleApiRequest(requestShoplistGet.build()), {
    enabled: false,
    refetchOnWindowFocus: false,
    refetchOnMount: false,
    staleTime: 0,
  });

  let shippingAddressUpdateQuery = useMutation('checkout-shipping-addresses-update', (requestUpdateBuild) => handleApiRequest(requestUpdateBuild), {
    onSuccess: (data) => {
      let shippingAddressUpdateResponse = getResponseById(data, 'checkout-shipping-addresses-update');

      if (shippingAddressUpdateResponse?.success) {
        // Success
        let queryClientUserShippingAddressGetData = queryClientUserShippingAddressGet.getQueryData('checkout-shipping-addresses-get');

        if (queryClientUserShippingAddressGetData) {
          let queryClientUserShippingAddressGetDataClone = _cloneDeep(queryClientUserShippingAddressGetData);
          let shippingAddressGetResponse = getResponseById(queryClientUserShippingAddressGetDataClone, 'checkout-shipping-addresses-get');

          // Replacing current with updated address list
          shippingAddressGetResponse.body.addresses = shippingAddressUpdateResponse.body.addresses;

          // Set dropdown to last address
          {
            let lastAddress = shippingAddressUpdateResponse.body.addresses[shippingAddressUpdateResponse.body.addresses.length - 1];
            lastAddress && dispatch(updateShippingUserSelectedAddress(lastAddress));
          }

          queryClientUserShippingAddressGet.setQueryData('checkout-shipping-addresses-get', queryClientUserShippingAddressGetDataClone);
        }

        handleUserShippingAddressAddClose();
      } else {
        setResponseErrors(Object.values(shippingAddressUpdateResponse.body.errors[0]));
      }
    },
  });

  let requestUserShippingAddressGet = useRequest(requestTemplates, queryUserShippingAddressGet);
  let requestUserShippingAddressUpdate = useRequest(requestTemplates, shippingAddressUpdateQuery);
  let requestShoplistGet = useRequest(requestTemplates, queryShopListGet);
  requestUserShippingAddressUpdate.addRequest('checkout-shipping-addresses-update');
  requestShoplistGet.addRequest('checkout-shipping-shoplist-get');

  // Changing delivery type
  let handleDeliveryTypeTabClick = useCallback((type) => {
    // Resetting errors
    if (deliveryType !== type) {
      useStoreInput.resetInputs();
      useStoreInput.resetErrors();
      setSelectedBox(null);
      setUserAddressAddVisible(false);
      dispatch(updateShippingType(type));
    }
  });

  // Hiting submit on next page as user
  let handleUserSubmitButtonClick = useCallback(() => {
    if (!checkoutUserSelectedAddress) {
      useStoreInput.setError('user_address_id', 'Választanod kell egy címet');
    } else {
      const homeShippingMethod = shippingMethods.find((method) => method.key === 'home');
      let chosenShippingMethod;
      if (deliveryType !== 'home') {
        chosenShippingMethod = homeShippingMethod.methods.find((method) => method.key === deliveryType);
      } else {
        chosenShippingMethod = homeShippingMethod;
      }
      analytics.optionsCheckout({
        checkout_step: 2,
        checkout_option: chosenShippingMethod.key,
      });

      let cartItemsForNewGa = [];
      for (const cartItem of actualUser.customer.cart.cart_items) {
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
      analytics.addShippingInfo({
        shipping_tier: chosenShippingMethod.key,
        items: cartItemsForNewGa,
      });

      dispatch(updateShippingValid(true));

      router.push('/penztar/osszesites');
    }
  });

  // Hiting submit on shops
  let handleShopSubmitButtonClick = useCallback(() => {
    if (!checkoutSelectedStore) {
      useStoreInput.setError('shop_selection_id', 'Választanod kell egy boltot');
    } else {
      analytics.optionsCheckout({
        checkout_step: 2,
        checkout_option: 'Könyvesbolt',
      });

      let cartItemsForNewGa = [];
      for (const cartItem of actualUser.customer.cart.cart_items) {
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
      analytics.addShippingInfo({
        shipping_tier: 'Könyvesbolt',
        items: cartItemsForNewGa,
      });

      dispatch(updateShippingValid(true));
      router.push('/penztar/osszesites');
    }
  }, [checkoutSelectedStore]);

  // Hiting submit on boxes
  let handleBoxSubmitButtonClick = useCallback(() => {
    if (!checkoutSelectedBox) {
      useStoreInput.setError('shop_selection_id', 'Választanod kell egy csomagpontot');
    } else {
      analytics.optionsCheckout({
        checkout_step: 2,
        checkout_option: 'Csomagpont',
      });

      let cartItemsForNewGa = [];
      for (const cartItem of actualUser.customer.cart.cart_items) {
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
      analytics.addShippingInfo({
        shipping_tier: 'Csomagpont',
        items: cartItemsForNewGa,
      });

      dispatch(updateShippingValid(true));
      router.push('/penztar/osszesites');
    }
  }, [checkoutSelectedBox]);

  // Hiting submit on new address as user
  let handleUserAddressSubmitButtonClick = useCallback(() => {
    if (responseErrors) setResponseErrors(null);

    hadleInputValidation().then((validation) => {
      if (validation === true) {
        useStoreInput.resetErrors();

        requestUserShippingAddressUpdate.modifyHeaders((currentHeader) => {
          currentHeader['Authorization'] = `Bearer ${actualUser.token}`;
        });

        requestUserShippingAddressUpdate.modifyRequest('checkout-shipping-addresses-update', (requestObject) => {
          requestObject.body = { ...requestObject.body, ...useStoreInput.inputs };
        });

        requestUserShippingAddressUpdate.commit();
      } else {
        useStoreInput.setErrors(validation);
      }
    });
  });

  function hadleInputValidation() {
    return import('joi').then((module) => {
      let joi = module.default;
      let schema = {};

      schema = joi.object({
        last_name: joi.string().required().min(2).max(60),
        first_name: joi.string().required().min(2).max(60),
        business_name: joi.string().empty('').min(2).max(60),
        city: joi.string().required().min(2).max(60),
        zip_code: joi.string().required().min(2).max(20),
        address: joi.string().required().min(2).max(60),
        country_id: joi.number().required(),
      });

      let validation = schema.validate(useStoreInput.inputs, { abortEarly: false, allowUnknown: true });

      if (validation.error) {
        let newErrorState = { ...shippingHomeErrorsDefault };

        validation.error.details.forEach((error) => {
          switch (error.type) {
            case 'string.empty':
              newErrorState[error.context.key] = 'Ez a mező nem lehet üres';
              break;
            case 'number.base':
              newErrorState[error.context.key] = 'Ez a mező nem lehet üres';
              break;
            case 'any.required':
              newErrorState[error.context.key] = 'Ez a mező nem lehet üres';
              break;
            case 'string.min':
              newErrorState[error.context.key] = `Minimum ${error.context.limit} karakter szükséges`;
              break;
            case 'string.max':
              newErrorState[error.context.key] = `Maximum ${error.context.limit} karakter lehet`;
              break;
            default:
              newErrorState[error.context.key] = 'Hibás mező';
              break;
          }
        });

        return newErrorState;
      } else {
        return true;
      }
    });
  }

  // Selecting shop
  let handleShopMarkerSelect = useCallback((store) => {
    dispatch(updateShippingSelectedShop(store));
  }, []);

  // Selecting box
  let handleBoxMarkerSelect = useCallback((box) => {
    setSelectedBox(box);
    dispatch(updateShippingSelectedBox(box.box));
  }, []);

  // Add new address close
  let handleUserShippingAddressAddClose = useCallback(() => {
    setUserAddressAddVisible(false);
    useStoreInput.resetInputs();
    useStoreInput.resetErrors();
  }, []);

  // Add new address open
  let handleUserShippingAddressAddModalOpen = useCallback(() => {
    setUserAddressAddVisible(true);
  }, []);

  // Handle address dropdown select
  let handleUserShippingAddressDropdownSelect = ({ value }) => {
    if (value) useStoreInput.setError('user_address_id', '');

    dispatch(updateShippingUserSelectedAddress(value));
  };

  // Handle store dropdown select
  let handleShoplistDropdownSelect = useCallback(({ value }) => {
    dispatch(updateShippingSelectedShop(value));
  }, []);

  // Previous steps valid?
  useEffect(() => {
    if (!stepBillingValid) router.replace('/penztar/szamlazasi-adatok');
  }, [stepBillingValid]);

  // No user, no page
  useEffect(() => {
    if (authChecking) return;
    if (actualUser?.type !== 'user') router.push('/');
  }, [actualUser]);

  // Setting default shipping type if books in cart
  // This is needed so if only ebooks are present we need to skip shipping
  useEffect(() => {
    if (actualUser?.type !== 'user') return;

    let booksInCart = actualUser.customer.cart.cart_items.filter((cartItem) => cartItem.type === 0);

    if (booksInCart.length <= 0) {
      dispatch(updateShippingValid(true));
      router.push('/penztar/osszesites');
    } else {
      dispatch(updateShippingType('home'));
    }
  }, [actualUser]);

  // Fetching user data
  useEffect(() => {
    if (authChecking || actualUser?.type !== 'user') return;

    requestUserShippingAddressGet.addRequest('checkout-shipping-addresses-get', 'checkout-shipping-countries-get', 'checkout-shipping-methods-get');

    requestUserShippingAddressGet.modifyHeaders((currentHeader) => {
      currentHeader['Authorization'] = `Bearer ${actualUser.token}`;
    });

    requestUserShippingAddressGet.commit();
  }, [actualUser]);

  // Fetching form data
  useEffect(() => {
    if (authChecking || actualUser?.type !== 'user') return;

    requestShoplistGet.commit();
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
      checkout_step: 2,
      items: cartItems,
    });
  }, [actualUser]);

  // Response parsing

  // Shipping addresses
  let shippingAddressesResponse, shippingAddressesDropdownData, shippingAddressDisplay;
  if (selectedShippingOption === 'home') {
    shippingAddressesResponse = getResponseById(queryUserShippingAddressGet.data, 'checkout-shipping-addresses-get');
    shippingAddressesDropdownData = shippingAddressesResponse
      ? shippingAddressesResponse.body.addresses.map((address) => ({
          value: address,
          label: address.entity_type === 'business' ? address.business_name : `${address.last_name} ${address.first_name}`,
          selected: address.id === checkoutUserSelectedAddress,
        }))
      : undefined;
    shippingAddressDisplay =
      shippingAddressesResponse && checkoutUserSelectedAddress
        ? shippingAddressesResponse.body.addresses.find((address) => address.id === checkoutUserSelectedAddress)
        : null;
  }

  // Stores
  let shoplistResponse, shoplistResponseDropdownData, shoplistResponseSelected;
  if (deliveryType === 'shop') {
    shoplistResponse = getResponseById(queryShopListGet.data, 'checkout-shipping-shoplist-get');

    shoplistResponseDropdownData = shoplistResponse?.success
      ? shoplistResponse.body.book_shops
          .filter((e) => e?.show_shipping)
          .map((bookShop) => ({
            value: bookShop,
            label: bookShop.title,
            selected: bookShop.id === checkoutSelectedStore ? true : false,
          }))
      : undefined;
    shoplistResponseSelected =
      shoplistResponse?.success && checkoutSelectedStore
        ? shoplistResponse.body.book_shops.find((bookShop) => bookShop.id === checkoutSelectedStore)
        : undefined;
  }

  function getShopCount() {
    let shoplistResponse = getResponseById(queryShopListGet.data, 'checkout-shipping-shoplist-get');
    return shoplistResponse?.success ? shoplistResponse?.body?.book_shops?.filter((e) => e?.show_shipping)?.length : 'több';
  }

  if (actualUser?.type !== 'user' || !stepBillingValid || deliveryType === null) return null;


  let settings = settingsVars.get(url.getHost());
  const nextButtonDisableOnHomeDelivery = () => {
    return !['dpd', 'sameday'].includes(deliveryType) && shippingAddressDisplay?.country?.name === 'Magyarország' && settings.key !== "NAGYKER";
  };

  return (
    <SzallitasiAdatokPageWrapper>
      <PageHead></PageHead>
      <Header></Header>
      <Content>
        <SiteColContainer>
          <NavStepsRow className="row">
            <NavStepsCol className="col-md-8 offset-md-2">
              <NavSteps activeSpot={1}></NavSteps>
            </NavStepsCol>
          </NavStepsRow>
          <FormRow className="row">
            <FormCol className="col-md-8 col-lg-6 offset-md-2 offset-lg-3">
              <Title>Szállítási adatok</Title>
              <SectionTitle mb={20}>Hogyan szeretnéd átvenni a rendelésed?</SectionTitle>
              <UserSelectControl>
                <Tabs>
                  {shippingMethods.map((shippingMethod) => {
                    return shippingMethod.active ? (
                      <TabWrapper key={shippingMethod.key}>
                        {shippingMethod.key === 'home' && shippingAddressDisplay && shippingAddressDisplay.country.name !== 'Magyarország' && (
                          <TabDeliveryCost>{currency.format(shippingAddressDisplay.country.fee)}</TabDeliveryCost>
                        )}
                        <ShippingInputRadioBlock
                          name={`delivery-${shippingMethod.key}`}
                          checked={selectedShippingOption === shippingMethod.key}
                          setChecked={(key) => handleDeliveryTypeTabClick(key)}
                          onClick={() => setSelectedShippingOption(shippingMethod.key)}
                          selectedShippingOption={selectedShippingOption}
                          method={shippingMethod}
                          fee={
                            shippingAddressDisplay &&
                            shippingAddressDisplay?.country &&
                            shippingAddressDisplay?.country?.name !== 'Magyarország' &&
                            shippingMethod.key === 'home'
                              ? shippingAddressDisplay.country.fee
                              : shippingMethod.fee
                          }
                          showOnePrice={shippingAddressDisplay?.country?.name !== 'Magyarország' || shippingMethod.key !== 'home' || settings.key === "NAGYKER"}
                          selectDefaultMethod={(shippingAddressDisplay?.country?.name !== 'Magyarország'  || settings.key === "NAGYKER") && shippingMethod.key === 'home'}
                        ></ShippingInputRadioBlock>
                      </TabWrapper>
                    ) : null;
                  })}
                </Tabs>
              </UserSelectControl>
              {selectedShippingOption === 'home' && (
                <>
                  <SectionTitle mb={20}>Szállítási cím</SectionTitle>
                  {!userAddressAddVisible && (
                    <>
                      <UserFormControls>
                        <UserDropdownWrapper>
                          <Dropdown
                            width="100%"
                            height="50px"
                            error={useStoreInput.errors.user_address_id}
                            placeholder="Válassz szállítási címet"
                            options={shippingAddressesDropdownData}
                            onSelect={handleUserShippingAddressDropdownSelect}
                          ></Dropdown>
                        </UserDropdownWrapper>
                        <UserActionWrapper>
                          <Button
                            type="secondary"
                            buttonWidth="100%"
                            buttonHeight="50px"
                            icon="plus"
                            iconWidth="12px"
                            iconHeight="12px"
                            onClick={handleUserShippingAddressAddModalOpen}
                          >
                            Új
                          </Button>
                        </UserActionWrapper>
                      </UserFormControls>
                      {shippingAddressDisplay && (
                        <UserAddressList>
                          <UserAddressItem>
                            <UserAddressItemLine strong>
                              {shippingAddressDisplay.entity_type === 'business'
                                ? shippingAddressDisplay.business_name
                                : `${shippingAddressDisplay.last_name} ${shippingAddressDisplay.first_name}`}
                            </UserAddressItemLine>
                            {shippingAddressDisplay.entity_type === 'business' && (
                              <UserAddressItemLine>
                                {shippingAddressDisplay.last_name} {shippingAddressDisplay.first_name}
                              </UserAddressItemLine>
                            )}
                            <UserAddressItemLine>{shippingAddressDisplay.address}</UserAddressItemLine>
                            <UserAddressItemLine>
                              {shippingAddressDisplay.city} {shippingAddressDisplay.zip_code}
                            </UserAddressItemLine>
                            <UserAddressItemLine>
                              {shippingAddressDisplay.country.name}
                              {shippingAddressDisplay.country.name !== 'Magyarország' && ' - ' + currency.format(shippingAddressDisplay.country.fee)}
                            </UserAddressItemLine>
                            {shippingAddressDisplay.entity_type === 'business' && (
                              <UserAddressItemLine>Adószám: {shippingAddressDisplay.vat_number}</UserAddressItemLine>
                            )}
                          </UserAddressItem>
                        </UserAddressList>
                      )}
                      <FormActions>
                        <ButtonWrapper>
                          {nextButtonDisableOnHomeDelivery() && <ButtonError>Válaszd ki a futárszolgálatot!</ButtonError>}
                          <Button
                            type="primary"
                            buttonWidth="100%"
                            buttonHeight="50px"
                            onClick={handleUserSubmitButtonClick}
                            disabled={nextButtonDisableOnHomeDelivery()}
                          >
                            Tovább az összesítéshez
                          </Button>
                        </ButtonWrapper>
                        <ButtonWrapper>
                          <Link href="/penztar/szamlazasi-adatok" passHref>
                            <Button type="secondary" buttonWidth="100%" buttonHeight="50px">
                              Vissza a számlázási adatokhoz
                            </Button>
                          </Link>
                        </ButtonWrapper>
                      </FormActions>
                    </>
                  )}
                  {userAddressAddVisible && (
                    <>
                      <FormContent
                        useInput={useStoreInput}
                        address={null}
                        countries={countries.map((country) => {
                          if (country.id === useStoreInput.inputs.country_id) country.selected = true;
                          return country;
                        })}
                        type="shipping"
                        addressType="business"
                        display="checkout"
                        title="Sszállítási adatok"
                        question="Kinek írjuk a számlát?"
                      ></FormContent>
                      <FormActions>
                        {responseErrors && <AlertBox responseErrors={responseErrors}></AlertBox>}
                        <ButtonWrapper>
                          <Button type="primary" buttonWidth="100%" buttonHeight="50px" onClick={handleUserAddressSubmitButtonClick}>
                            Új szállítási adat mentése
                          </Button>
                        </ButtonWrapper>
                        <ButtonWrapper>
                          <Button type="secondary" buttonWidth="100%" buttonHeight="50px" onClick={handleUserShippingAddressAddClose}>
                            Mégse
                          </Button>
                        </ButtonWrapper>
                      </FormActions>
                    </>
                  )}
                </>
              )}

              {deliveryType === 'shop' && (
                <>
                  <SectionTitle mb={20}>Melyik Álomgyár könyvesboltban szeretnéd átvenni a rendelésed?</SectionTitle>
                  <UserFormControls>
                    <UserDropdownWrapper>
                      <Dropdown
                        width="100%"
                        height="50px"
                        placeholder="Válassz szállítási címet"
                        options={shoplistResponseDropdownData}
                        onSelect={handleShoplistDropdownSelect}
                        error={useStoreInput.errors.shop_selection_id}
                      ></Dropdown>
                    </UserDropdownWrapper>
                  </UserFormControls>
                  <StoreMapWrapper>
                    <CheckoutShopMap
                      shoplist={shoplistResponse}
                      onMarkerSelect={handleShopMarkerSelect}
                      selectedStore={checkoutSelectedStore}
                    ></CheckoutShopMap>
                  </StoreMapWrapper>
                  <StoreInfoWrapper>
                    <CheckoutDeliveryStoreInfo store={shoplistResponseSelected}></CheckoutDeliveryStoreInfo>
                  </StoreInfoWrapper>
                  <FormActions>
                    {responseErrors && <AlertBox responseErrors={responseErrors}></AlertBox>}
                    <ButtonWrapper>
                      <Button type="primary" buttonWidth="100%" buttonHeight="50px" onClick={handleShopSubmitButtonClick}>
                        Tovább az összesítéshez
                      </Button>
                    </ButtonWrapper>
                    <ButtonWrapper>
                      <Link href="/penztar/szamlazasi-adatok" passHref>
                        <Button type="secondary" buttonWidth="100%" buttonHeight="50px">
                          Vissza a számlázási adatokhoz
                        </Button>
                      </Link>
                    </ButtonWrapper>
                  </FormActions>
                </>
              )}

              {deliveryType === 'box' && (
                <>
                  <SectionTitle mb={20}>Válassz csomagpontot</SectionTitle>
                  <BoxTitleInfo>Kezdd el beírni a várost és az utcát, és mi megmutatjuk a közelben lévő csomagpontokat.</BoxTitleInfo>
                  <BoxMapWrapper>
                    <CheckoutBoxMap onBoxSelect={handleBoxMarkerSelect} selectedBox={selectedBox}></CheckoutBoxMap>
                  </BoxMapWrapper>
                  <BoxInfoWrapper></BoxInfoWrapper>
                  <FormActions>
                    <ButtonWrapper>
                      <Button type="primary" buttonWidth="100%" buttonHeight="50px" onClick={handleBoxSubmitButtonClick}>
                        Tovább az összesítéshez
                      </Button>
                    </ButtonWrapper>
                    <ButtonWrapper>
                      <Link href="/penztar/szamlazasi-adatok" passHref>
                        <Button type="secondary" buttonWidth="100%" buttonHeight="50px">
                          Vissza a számlázási adatokhoz
                        </Button>
                      </Link>
                    </ButtonWrapper>
                  </FormActions>
                </>
              )}
            </FormCol>
          </FormRow>
        </SiteColContainer>
      </Content>
      <Footer></Footer>
    </SzallitasiAdatokPageWrapper>
  );

  function getShippingMethodContent(shippingMethod, data = {}) {
    switch (shippingMethod) {
      case 'home':
        return {
          label: 'Házhozszállítással',
          sublabel: '',
        };
      case 'shop':
        return {
          label: 'Álomgyár könyvesboltban',
          sublabel: `Országszerte ${data.shopCount} településen`,
        };
      case 'box':
        return {
          label: 'Csomagponton',
          sublabel: 'Országszerte 3547 helyszínen',
        };
      case 'dpd':
        return {
          label: 'DPD futárszolgálat',
          sublabel: '',
        };
      case 'sameday':
        return {
          label: 'Sameday futárszolgálat',
          sublabel: '',
        };

      default:
        return {
          label: '',
          sublabel: '',
        };
    }
  }
}
