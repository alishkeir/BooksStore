import dynamic from 'next/dynamic';
import { useState, useCallback, useEffect } from 'react';
import Link from 'next/link';
import { useRouter } from 'next/router';
import _cloneDeep from 'lodash/cloneDeep';
import { useQuery, useMutation, useQueryClient } from 'react-query';
import { useSelector, useDispatch } from 'react-redux';
import {
  updateBillingType,
  updateBillingUserSelectedAddress,
  updateBillingInput,
  updateBillingInputs,
  resetBillingInputs,
  updateBillingError,
  updateBillingErrors,
  resetBillingErrors,
  updateBillingValid,
  billingErrorsDefault,
} from '@store/modules/checkout';
import { analytics } from '@libs/analytics';
import { handleApiRequest, getResponseById } from '@libs/api';
import useRequest from '@hooks/useRequest/useRequest';

const PageHead = dynamic(() => import('@components/pageHead/pageHead'));
const AlertBox = dynamic(() => import('@components/alertBox/alertBox'));
const Button = dynamic(() => import('@components/button/button'));
const Dropdown = dynamic(() => import('@components/dropdown/dropdown'));
const Header = dynamic(() => import('@components/header/header'));
const Content = dynamic(() => import('@components/content/content'));
const SiteColContainer = dynamic(() => import('@components/siteColContainer/siteColContainer'));
const Footer = dynamic(() => import('@components/footer/footer'));
import useStoreInputs from '@hooks/useStoreInputs/useStoreInputs';
import useUser from '@hooks/useUser/useUser';

const NavSteps = dynamic(() => import('@components/navSteps/navSteps'));
const SectionTitle = dynamic(() => import('@components/sectionTitle/sectionTitle'));
import { FormContent } from '@components/overlayCardContentAddress/overlayCardContentAddress';
import {
  ButtonWrapper,
  FormActions,
  FormCol,
  FormRow,
  NavStepsCol,
  NavStepsRow,
  SzamlazasiAdatokPageWrapper,
  Title,
  UserActionWrapper,
  UserAddressItem,
  UserAddressItemLine,
  UserAddressList,
  UserDropdownWrapper,
  UserFormControls,
} from '@components/pages/szamlazasiAdatokPage.styled';

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    'checkout-billing-addresses-get': {
      method: 'GET',
      path: '/profile/addresses',
      ref: 'customerAddresses',
      request_id: 'checkout-billing-addresses-get',
      body: {
        type: 'billing',
      },
    },
    'checkout-shipping-countries-get': {
      method: 'GET',
      path: '/helpers',
      ref: 'countries',
      request_id: 'checkout-shipping-countries-get',
    },
    'checkout-billing-addresses-update': {
      method: 'POST',
      path: '/profile/addresses',
      ref: 'customerAddresses',
      request_id: 'checkout-billing-addresses-update',
      body: {
        type: 'billing',
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
        entity_type: null,
      },
    },
  },
};

export default function SzamlazasiAdatokPage() {
  let [responseErrors, setResponseErrors] = useState(null);
  let [userAddressAddVisible, setUserAddressAddVisible] = useState(false);
  let [countries, setCountries] = useState([{ id: 1, name: 'Magyarország', fee: 0 }]);

  let { authChecking, actualUser } = useUser();
  let dispatch = useDispatch();
  let router = useRouter();
  let useInput = useStoreInputs(
    'checkout.steps.billing.inputs',
    'checkout.steps.billing.errors',
    updateBillingInput,
    updateBillingInputs,
    resetBillingInputs,
    updateBillingError,
    updateBillingErrors,
    resetBillingErrors,
  );
  // let user = useSelector((store) => store.user);
  let addressType = useSelector((store) => store.checkout.steps.billing.type);
  let userSelectedAddress = useSelector((store) => store.checkout.steps.billing.user_selected_address?.id);
  let queryClientUserBillingAddressGet = useQueryClient();

  let queryUserBillingAddressGet = useQuery('checkout-billing-addresses-get', () => handleApiRequest(requestUserBillingAddressGet.build()), {
    enabled: false,
    cacheTime: 0,
    refetchOnWindowFocus: false,
    refetchOnMount: false,
    staleTime: 0,
    onSuccess: (data) => {
      let shippingCountriesGetResponse = getResponseById(data, 'checkout-shipping-countries-get');

      if (shippingCountriesGetResponse?.success) {
        let countries = shippingCountriesGetResponse.body.countries.map((country) => ({
          id: country.id,
          name: country.name,
          fee: country.fee,
        }));

        setCountries(countries);
      }
    },
  });

  let billingAddressUpdateQuery = useMutation('checkout-billing-addresses-update', (requestUpdateBuild) => handleApiRequest(requestUpdateBuild), {
    onSuccess: (data) => {
      let billingAddressUpdateResponse = getResponseById(data, 'checkout-billing-addresses-update');

      if (billingAddressUpdateResponse?.success) {
        // Success
        let queryClientUserBillingAddressGetData = queryClientUserBillingAddressGet.getQueryData('checkout-billing-addresses-get');

        if (queryClientUserBillingAddressGetData) {
          let queryClientUserBillingAddressGetDataClone = _cloneDeep(queryClientUserBillingAddressGetData);
          let billingAddressGetResponse = getResponseById(queryClientUserBillingAddressGetDataClone, 'checkout-billing-addresses-get');

          // Replacing current with updated address list
          billingAddressGetResponse.body.addresses = billingAddressUpdateResponse.body.addresses;

          // Set dropdown to last address
          {
            let lastAddress = billingAddressUpdateResponse.body.addresses[billingAddressUpdateResponse.body.addresses.length - 1];
            lastAddress && dispatch(updateBillingUserSelectedAddress(lastAddress));
          }

          queryClientUserBillingAddressGet.setQueryData('checkout-billing-addresses-get', queryClientUserBillingAddressGetDataClone);
        }

        handleUserAddressAddClose();
      } else {
        setResponseErrors(Object.values(billingAddressUpdateResponse.body.errors[0]));
      }
    },
  });

  let requestUserBillingAddressGet = useRequest(requestTemplates, queryUserBillingAddressGet);
  let requestUserBillingAddressUpdate = useRequest(requestTemplates, billingAddressUpdateQuery);
  requestUserBillingAddressUpdate.addRequest('checkout-billing-addresses-update');

  // Changing address type
  let handleAddressTypeTabClick = useCallback((type) => {
    // Resetting errors
    if (addressType !== type) useInput.resetErrors();

    dispatch(updateBillingType(type));
  });

  // Hiting submit on next page as user
  let handleUserSubmitButtonClick = useCallback(() => {
    // router.push('/penztar/szallitasi-adatok');

    if (!userSelectedAddress) {
      useInput.setError('user_address_id', 'Választanod kell egy címet');
    } else {
      dispatch(updateBillingValid(true));
      router.push('/penztar/szallitasi-adatok');
    }
  });

  // Hiting submit on new address as user
  let handleUserAddressSubmitButtonClick = useCallback(() => {
    if (responseErrors) setResponseErrors(null);

    hadleInputValidation().then((validation) => {
      if (validation === true) {
        useInput.resetErrors();

        requestUserBillingAddressUpdate.modifyHeaders((currentHeader) => {
          currentHeader['Authorization'] = `Bearer ${actualUser.token}`;
        });

        requestUserBillingAddressUpdate.modifyRequest('checkout-billing-addresses-update', (requestObject) => {
          requestObject.body = { ...requestObject.body, ...useInput.inputs };
          requestObject.body.entity_type = addressType;
        });

        requestUserBillingAddressUpdate.commit();
      } else {
        useInput.setErrors(validation);
      }
    });
  });

  function hadleInputValidation() {
    return import('joi').then((module) => {
      let joi = module.default;
      let schema = {};

      if (addressType === 'private') {
        schema = joi.object({
          last_name: joi.string().required().min(2).max(60),
          first_name: joi.string().required().min(2).max(60),
          city: joi.string().required().min(2).max(60),
          zip_code: joi.string().required().min(2).max(20),
          address: joi.string().required().min(2).max(60),
          country_id: joi.number().required(),
        });
      } else if (addressType === 'business') {
        schema = joi.object({
          business_name: joi.string().required().min(2).max(60),
          vat_number: joi.string().required().min(6).max(15),
          city: joi.string().required().min(2).max(60),
          zip_code: joi.string().required().min(2).max(20),
          address: joi.string().required().min(2).max(60),
          country_id: joi.number().required(),
        });
      }

      let validation = schema.validate(useInput.inputs, { abortEarly: false, allowUnknown: true });

      if (validation.error) {
        let newErrorState = { ...billingErrorsDefault };

        validation.error.details.forEach((error) => {
          switch (error.type) {
            case 'string.empty':
              newErrorState[error.context.key] = 'Ez a mező nem lehet üres';
              break;
            case 'number.base':
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

  // Add new address close
  let handleUserAddressAddClose = useCallback(() => {
    setUserAddressAddVisible(false);
    useInput.resetInputs();
    dispatch(updateBillingType('private'));
  });

  // Add new address open
  let handleUserAddressAddModalOpen = useCallback(() => {
    setUserAddressAddVisible(true);
  });

  // Handle address dropdown select
  let handleUserAddressDropdownSelect = useCallback(({ value }) => {
    if (value) useInput.setError('user_address_id', '');
    dispatch(updateBillingUserSelectedAddress(value));
  });

  useEffect(() => {
    if (authChecking) return;

    if (actualUser?.type !== 'user') {
      router.push('/');
      return;
    }

    if (actualUser?.type === 'user') {
      requestUserBillingAddressGet.addRequest('checkout-billing-addresses-get', 'checkout-shipping-countries-get');
      requestUserBillingAddressGet.modifyHeaders((currentHeader) => {
        currentHeader['Authorization'] = `Bearer ${actualUser.token}`;
      });
    }

    requestUserBillingAddressGet.commit();
  }, [authChecking, actualUser]);

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

    analytics.beginCheckout(cartItems);
  }, [actualUser]);

  let billingAddressesResponse = getResponseById(queryUserBillingAddressGet.data, 'checkout-billing-addresses-get');

  let billingAddressesDropdownData = billingAddressesResponse?.success
    ? billingAddressesResponse.body.addresses.map((address) => ({
        value: address,
        label: address.entity_type === 'business' ? address.business_name : `${address.last_name} ${address.first_name}`,
        selected: address.id === userSelectedAddress,
      }))
    : undefined;
  let billingAddressDisplay =
    billingAddressesResponse?.success && userSelectedAddress
      ? billingAddressesResponse.body.addresses.find((address) => address.id === userSelectedAddress)
      : null;

  if (actualUser?.type !== 'user') return null;

  return (
    <SzamlazasiAdatokPageWrapper>
      <PageHead></PageHead>
      <Header></Header>
      <Content>
        <SiteColContainer>
          <NavStepsRow className="row">
            <NavStepsCol className="col-md-8 offset-md-2">
              <NavSteps activeSpot={0}></NavSteps>
            </NavStepsCol>
          </NavStepsRow>
          <FormRow className="row">
            <FormCol className="col-md-8 col-lg-6 offset-md-2 offset-lg-3">
              <Title>Számlázási adatok</Title>
              <SectionTitle mb={20}>Kinek írjuk a számlát?</SectionTitle>
              {!userAddressAddVisible && (
                <>
                  <UserFormControls>
                    <UserDropdownWrapper>
                      <Dropdown
                        width="100%"
                        height="50px"
                        placeholder="Válassz számlázási címet"
                        error={useInput.errors.user_address_id}
                        options={billingAddressesDropdownData}
                        onSelect={handleUserAddressDropdownSelect}
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
                        onClick={handleUserAddressAddModalOpen}
                      >
                        Új
                      </Button>
                    </UserActionWrapper>
                  </UserFormControls>
                  {billingAddressDisplay && (
                    <UserAddressList>
                      <UserAddressItem>
                        <UserAddressItemLine strong>
                          {billingAddressDisplay.entity_type === 'business'
                            ? billingAddressDisplay.business_name
                            : `${billingAddressDisplay.last_name} ${billingAddressDisplay.first_name}`}
                        </UserAddressItemLine>
                        {billingAddressDisplay.entity_type === 'business' && (
                          <UserAddressItemLine>
                            {billingAddressDisplay.last_name} {billingAddressDisplay.first_name}
                          </UserAddressItemLine>
                        )}
                        <UserAddressItemLine>{billingAddressDisplay.address}</UserAddressItemLine>
                        <UserAddressItemLine>
                          {billingAddressDisplay.city} {billingAddressDisplay.zip_code}
                        </UserAddressItemLine>
                        <UserAddressItemLine>{billingAddressDisplay.country.name}</UserAddressItemLine>
                        {billingAddressDisplay.entity_type === 'business' && (
                          <UserAddressItemLine>Adószám: {billingAddressDisplay.vat_number}</UserAddressItemLine>
                        )}
                      </UserAddressItem>
                    </UserAddressList>
                  )}
                  <FormActions>
                    <ButtonWrapper>
                      <Button type="primary" buttonWidth="100%" buttonHeight="50px" onClick={handleUserSubmitButtonClick}>
                        Tovább a szállításhoz
                      </Button>
                    </ButtonWrapper>
                    <ButtonWrapper>
                      <Link href="/kosar" passHref>
                        <Button type="secondary" buttonWidth="100%" buttonHeight="50px">
                          Vissza a kosaramhoz
                        </Button>
                      </Link>
                    </ButtonWrapper>
                  </FormActions>
                </>
              )}
              {userAddressAddVisible && (
                <>
                  <FormContent
                    useInput={useInput}
                    address={null}
                    countries={countries.map((country) => {
                      if (country.id === useInput.inputs.country_id) country.selected = true;
                      return country;
                    })}
                    addressType={addressType}
                    onAddressTypeClick={handleAddressTypeTabClick}
                    display="checkout"
                    title="Számlázási adatok"
                    question="Kinek írjuk a számlát?"
                  ></FormContent>
                  <FormActions>
                    {responseErrors && <AlertBox responseErrors={responseErrors}></AlertBox>}
                    <ButtonWrapper>
                      <Button type="primary" buttonWidth="100%" buttonHeight="50px" onClick={handleUserAddressSubmitButtonClick}>
                        Új számlázási adat mentése
                      </Button>
                    </ButtonWrapper>
                    <ButtonWrapper>
                      <Button type="secondary" buttonWidth="100%" buttonHeight="50px" onClick={handleUserAddressAddClose}>
                        Mégse
                      </Button>
                    </ButtonWrapper>
                  </FormActions>
                </>
              )}
            </FormCol>
          </FormRow>
        </SiteColContainer>
      </Content>
      <Footer></Footer>
    </SzamlazasiAdatokPageWrapper>
  );
}
