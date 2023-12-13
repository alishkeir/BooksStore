import dynamic from 'next/dynamic';
import { useCallback, useEffect, useState } from 'react';
const PageHead = dynamic(() => import('@components/pageHead/pageHead'));
const Button = dynamic(() => import('@components/button/button'));
const Header = dynamic(() => import('@components/header/header'));
const Content = dynamic(() => import('@components/content/content'));
import PageTitle from '@components/pageTitle/pageTitle';
const ProfileAddressItem = dynamic(() => import('@components/profileAddressItem/profileAddressItem'));
const SiteColContainer = dynamic(() => import('@components/siteColContainer/siteColContainer'));
const ProfileNavigator = dynamic(() => import('@components/profileNavigator/profileNavigator'));
const ProfileDataTitle = dynamic(() => import('@components/profileDataTitle/profileDataTitle'));
const ProfileEmpty = dynamic(() => import('@components/profileEmpty/profileEmpty'));
const OverlayCardContentAddress = dynamic(() => import('@components/overlayCardContentAddress/overlayCardContentAddress'));
const Footer = dynamic(() => import('@components/footer/footer'));
import useInputs from '@hooks/useInputs/useInputs';
import useProtectedRoute from '@hooks/useProtectedRoute/useProtectedRoute';
import { useQuery, useMutation } from 'react-query';
import { handleApiRequest, getResponseById } from '@libs/api';
const Overlay = dynamic(() => import('@components/overlay/overlay'));
const OverlayCard = dynamic(() => import('@components/overlayCard/overlayCard'));
const OverlayCardContentConfirmation = dynamic(() => import('@components/overlayCardContentConfirmation/overlayCardContentConfirmation'));
import useRequest from '@hooks/useRequest/useRequest';
import {
  Actions,
  ButtonWrapper,
  List,
  PageContent,
  ProfilSzamlazasiCimeimComponent,
  ProfileData,
  ProfileNavigatorWrapper,
} from '@components/pages/profilSzamlazasiCimeim.styled';

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    profileBillingAddressesGet: {
      method: 'GET',
      path: '/profile/addresses',
      ref: 'customerAddresses',
      request_id: 'profile-billing-addresses-get',
      body: {
        type: 'billing',
      },
    },
    profileBillingAddressesUpdate: {
      method: 'PUT',
      path: '/profile/addresses',
      ref: 'customerAddresses',
      request_id: 'profile-billing-addresses-update',
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
    profileBillingAddressesDelete: {
      method: 'DELETE',
      path: '/profile/addresses',
      ref: 'customerAddresses',
      request_id: 'profile-billing-addresses-delete',
      body: {
        address_id: null,
        type: null,
      },
    },
  },
};

let inputsDefaults = {
  last_name: '',
  first_name: '',
  business_name: '',
  vat_number: '',
  city: '',
  zip_code: '',
  address: '',
  comment: '',
  country_id: '',
};

let errorsDefaults = {
  last_name: '',
  first_name: '',
  business_name: '',
  vat_number: '',
  city: '',
  zip_code: '',
  address: '',
  comment: '',
  country_id: '',
};

export default function ProfilSzamlazasiCimeim() {
  let { user, authChecking } = useProtectedRoute();
  let useInput = useInputs(inputsDefaults, errorsDefaults);
  let [addresses, setAddresses] = useState();
  let [countries, setCountries] = useState([]);

  let [responseErrors, setResponseErrors] = useState(null);
  let [addressAddModalVisible, setAddressAddModalVisible] = useState(false);
  let [modalContentIndex, setModalContentIndex] = useState(null);
  let [deleteConfirmationModalVisible, setDeleteConfirmationModalVisible] = useState(false);
  let [deleteConfirmationModalId, setDeleteConfirmationModalId] = useState(null);
  let [deleteConfirmationModalType, setDeleteConfirmationModalType] = useState(null);

  let billingAddressGetQuery = useQuery('profile-billing-addresses-get', () => handleApiRequest(requestGet.build()), {
    enabled: false,
    refetchOnWindowFocus: false,
    refetchOnMount: true,
    staleTime: 0,
    onSuccess: (data) => {
      let profileAddressesResponse = getResponseById(data, 'profile-billing-addresses-get');

      if (profileAddressesResponse) {
        if (!profileAddressesResponse.success) {
          profileAddressesResponse.body.errors && setResponseErrors(Object.values(profileAddressesResponse.body.errors));
        } else {
          // Success
          setAddresses([...profileAddressesResponse.body.addresses]);
          setCountries([...profileAddressesResponse.body.countries]);
        }
      }
    },
  });

  let billingAddressUpdateQuery = useMutation('profile-billing-addresses-update', (requestUpdateBuild) => handleApiRequest(requestUpdateBuild), {
    onSuccess: (data) => {
      let profileDetailsResponse = getResponseById(data, 'profile-billing-addresses-update');

      if (profileDetailsResponse) {
        if (profileDetailsResponse.body.errors) {
          setResponseErrors(Object.values(profileDetailsResponse.body.errors));
        } else {
          // Success
          handleModalToggle(false);
          setAddresses([...profileDetailsResponse.body.addresses]);
        }
      }
    },
  });

  let billingAddressDeleteQuery = useMutation('profile-billing-addresses-delete', (requestUpdateBuild) => handleApiRequest(requestUpdateBuild), {
    onSuccess: (data) => {
      let profileDetailsResponse = getResponseById(data, 'profile-billing-addresses-delete');

      if (profileDetailsResponse) {
        if (profileDetailsResponse.body.errors) {
          setResponseErrors(Object.values(profileDetailsResponse.body.errors));
        } else {
          // Success
          setAddresses([...profileDetailsResponse.body.addresses]);
          setDeleteConfirmationModalVisible(false);
          setDeleteConfirmationModalId(null);
          setDeleteConfirmationModalType(null);
        }
      }
    },
  });

  let requestGet = useRequest(requestTemplates, billingAddressGetQuery);
  let requestUpdate = useRequest(requestTemplates, billingAddressUpdateQuery);
  let requestDelete = useRequest(requestTemplates, billingAddressDeleteQuery);
  requestGet.addRequest('profileBillingAddressesGet');
  requestUpdate.addRequest('profileBillingAddressesUpdate');
  requestDelete.addRequest('profileBillingAddressesDelete');

  // Confirming item delete
  let handleDeleteConfirmation = useCallback(() => {
    requestDelete.modifyHeaders((currentHeader) => {
      currentHeader['Authorization'] = `Bearer ${user.token}`;
    });

    requestDelete.modifyRequest('profileBillingAddressesDelete', (requestObject) => {
      requestObject.body.address_id = deleteConfirmationModalId;
      requestObject.body.type = deleteConfirmationModalType;
    });

    requestDelete.commit();
  });

  // Clicking delete button
  let handleDeleteButtonClick = useCallback((id, type) => {
    setDeleteConfirmationModalType(type);
    setDeleteConfirmationModalId(id);
    setDeleteConfirmationModalVisible(true);
  });

  // Hide confirmation dialog
  let hideConfirmation = useCallback(() => {
    setDeleteConfirmationModalVisible(false);
    setDeleteConfirmationModalId(null);
    setDeleteConfirmationModalType(null);
  });

  // Validating add and modify modal submit
  let handleModalSubmitButtonClick = useCallback((addressType, addressId) => {
    if (responseErrors) setResponseErrors(null);

    import('joi').then((module) => {
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
        let newErrorState = { ...errorsDefaults };

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

        useInput.setErrors(newErrorState);
      } else {
        useInput.setErrors({ ...errorsDefaults });

        // Updating address
        if (addressId) {
          requestUpdate.modifyHeaders((currentHeader) => {
            currentHeader['Authorization'] = `Bearer ${user.token}`;
          });

          requestUpdate.modifyRequest('profileBillingAddressesUpdate', (requestObject) => {
            requestObject.method = 'PUT';
            requestObject.body = { ...requestObject.body, ...useInput.inputs };
            requestObject.body.address_id = addressId;
            requestObject.body.entity_type = addressType;
          });

          requestUpdate.commit();
        } else {
          requestUpdate.modifyHeaders((currentHeader) => {
            currentHeader['Authorization'] = `Bearer ${user.token}`;
          });

          requestUpdate.modifyRequest('profileBillingAddressesUpdate', (requestObject) => {
            requestObject.method = 'POST';
            requestObject.body = { ...requestObject.body, ...useInput.inputs };
            requestObject.body.address_id = null;
            requestObject.body.entity_type = addressType;
          });

          requestUpdate.commit();
        }
      }
    });
  });

  let handleModalToggle = useCallback((bool) => {
    if (!bool) {
      setAddressAddModalVisible(false);
      useInput.setErrors({ ...errorsDefaults });
      useInput.setInputs({ ...inputsDefaults });
    } else if (bool) {
      setAddressAddModalVisible(true);
    }
  });

  useEffect(() => {
    if (!user) return;

    requestGet.modifyHeaders((currentHeader) => {
      currentHeader['Authorization'] = `Bearer ${user.token}`;
    });

    requestGet.commit();
  }, [user]);

  if (!user || authChecking) return <div>checking</div>;

  return (
    <ProfilSzamlazasiCimeimComponent>
      <PageHead></PageHead>
      <Header></Header>
      {addressAddModalVisible && (
        <Overlay zIndex={99999} fixed={false} floating={false}>
          <OverlayCard mobile="full" align="top" onClose={() => handleModalToggle(false)}>
            <OverlayCardContentAddress
              useInput={useInput}
              address={modalContentIndex ? addresses.find((item) => item.id === modalContentIndex) : null}
              countries={countries}
              title="Számlázási cím"
              question="Kinek írjuk a számlát?"
              responseErrors={responseErrors}
              onSubmit={handleModalSubmitButtonClick}
              onCancel={() => handleModalToggle(false)}
            ></OverlayCardContentAddress>
          </OverlayCard>
        </Overlay>
      )}
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
              <ProfileNavigator selected={3}></ProfileNavigator>
            </ProfileNavigatorWrapper>
            <ProfileData className="col-md-8 col-lg-7 col-xl-6 col-xxl-5 offset-0 offset-lg-1">
              <ProfileDataTitle>Számlázási címeim</ProfileDataTitle>
              {billingAddressGetQuery.isFetching && !addresses && <ProfileEmpty>Töltődik...</ProfileEmpty>}
              {typeof addresses !== 'undefined' && addresses.length < 1 && <ProfileEmpty>Még nincs számlázási címed megadva</ProfileEmpty>}
              {typeof addresses !== 'undefined' && (
                <>
                  <List>
                    {addresses.map((address) => (
                      <ProfileAddressItem
                        address={address}
                        key={address.id}
                        onEdit={() => {
                          setModalContentIndex(address.id);
                          setAddressAddModalVisible(true);
                        }}
                        onDelete={handleDeleteButtonClick}
                      ></ProfileAddressItem>
                    ))}
                  </List>
                  {addresses.length <= 4 && (
                    <Actions>
                      <ButtonWrapper>
                        <Button
                          icon="plus"
                          type="secondary"
                          iconHeight="12px"
                          buttonHeight="50px"
                          buttonWidth="100%"
                          onClick={() => {
                            setModalContentIndex(null);
                            setAddressAddModalVisible(true);
                          }}
                        >
                          Hozzáadás
                        </Button>
                      </ButtonWrapper>
                    </Actions>
                  )}
                </>
              )}
            </ProfileData>
          </PageContent>
        </SiteColContainer>
      </Content>
      <Footer></Footer>
    </ProfilSzamlazasiCimeimComponent>
  );
}
