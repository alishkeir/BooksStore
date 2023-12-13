import dynamic from 'next/dynamic';
import { useCallback, useEffect, useState } from 'react';
import { useDispatch } from 'react-redux';
import { updateUserData } from '@store/modules/user';
const PageHead = dynamic(() => import('@components/pageHead/pageHead'));
const Header = dynamic(() => import('@components/header/header'));
const Content = dynamic(() => import('@components/content/content'));
const Overlay = dynamic(() => import('@components/overlay/overlay'));
const OverlayCard = dynamic(() => import('@components/overlayCard/overlayCard'));
const OverlayCardContentConfirmation = dynamic(() => import('@components/overlayCardContentConfirmation/overlayCardContentConfirmation'));
const Button = dynamic(() => import('@components/button/button'));
import PageTitle from '@components/pageTitle/pageTitle';
const InputText = dynamic(() => import('@components/inputText/inputText'));
const SiteColContainer = dynamic(() => import('@components/siteColContainer/siteColContainer'));
const ProfileNavigator = dynamic(() => import('@components/profileNavigator/profileNavigator'));
const ProfileDataTitle = dynamic(() => import('@components/profileDataTitle/profileDataTitle'));
const AlertBox = dynamic(() => import('@components/alertBox/alertBox'));
const Footer = dynamic(() => import('@components/footer/footer'));
import { useQuery, useMutation } from 'react-query';
import useInputs from '@hooks/useInputs/useInputs';
import { getResponseById } from '@libs/api';
import useProtectedRoute from '@hooks/useProtectedRoute/useProtectedRoute';
import useFlash from '@hooks/useFlash/useFlash';
import useRequest from '@hooks/useRequest/useRequest';
import { useLogout } from '@hooks/useAuth/useAuth';
import { FEEDBACK_CODES } from '@components/sideModalFeedback/sideModalFeedback';
import { getSiteCode } from '@libs/site';
import {
  Actions,
  ButtonWrapper,
  DataWrapper,
  Form,
  InputEmailWrapper,
  InputFirstnameWrapper,
  InputPhoneWrapper,
  InputSurnameWrapper,
  PageContent,
  PassChange,
  ProfilSzemelyesAdataimPageComponent,
  ProfileNavigatorWrapper,
} from '@components/pages/profilSzemelyesAdataimPage.styled';
import settingsVars from "@vars/settingsVars";
import url from "@libs/url";

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    details: {
      method: 'GET',
      path: '/profile',
      ref: 'personalDetails',
      request_id: 'profile-details',
      body: {},
    },
    detailsUpdate: {
      method: 'POST',
      path: '/profile/update',
      ref: 'profileUpdate',
      request_id: 'profile-update',
      body: {
        lastname: null,
        firstname: null,
        email: null,
        phone: null,
      },
    },
    passwordUpdate: {
      method: 'POST',
      path: '/forgot-password',
      ref: 'forgot-password',
      request_id: 'profile-forgot-password',
      body: {
        email: null,
      },
    },
  },
};

let inputsDefaults = {
  firstname: '',
  lastname: '',
  email: '',
  phone: '',
};

let errorsDefaults = {
  firstname: '',
  lastname: '',
  email: '',
  phone: '',
};

export default function ProfilSzemelyesAdataimPage() {
  let { user, authChecking } = useProtectedRoute();
  let { inputs, setInput, setInputs, errors } = useInputs(inputsDefaults, errorsDefaults);
  let [, setFlash] = useFlash();
  let logout = useLogout();
  let dispatch = useDispatch();

  let [responseErrors, setResponseErrors] = useState(null);
  let [passwordChangeModalVisible, setPasswordChangeModalVisible] = useState(false);

  let detailsGetQuery = useQuery('profile-details', () => handlePersonalDetails(requestGet.build()), {
    enabled: false,
    refetchOnWindowFocus: false,
    refetchOnMount: true,
    staleTime: 0,
    onSuccess: (data) => {
      let profileDetailsResponse = getResponseById(data, 'profile-details');

      if (profileDetailsResponse) {
        if (!profileDetailsResponse.success) {
          setResponseErrors(Object.values(profileDetailsResponse.body.errors));
        } else {
          // Success
          let newInputs = {};

          for (let key in profileDetailsResponse.body) {
            newInputs[key] = profileDetailsResponse.body[key] ? profileDetailsResponse.body[key] : '';
          }

          setInputs({ ...newInputs });
        }
      }
    },
  });

  let detailsUpdateQuery = useMutation('profile-update', (requestUpdateBuild) => handlePersonalDetails(requestUpdateBuild), {
    onSuccess: (data) => {
      let profileDetailsResponse = getResponseById(data, 'profile-update');

      if (profileDetailsResponse?.success) {
        // Success
        setResponseErrors(null);
        dispatch(updateUserData(profileDetailsResponse.body));
      } else {
        setResponseErrors(profileDetailsResponse.body.errors ? Object.values(profileDetailsResponse.body.errors) : null);
      }
    },
  });

  let passwordUpdateQuery = useMutation(
    'profile-forgot-password',
    (requestPasswordUpdateBuild) => handlePersonalDetails(requestPasswordUpdateBuild),
    {
      onSuccess: (data) => {
        let profileDetailsResponse = getResponseById(data, 'profile-forgot-password');

        if (profileDetailsResponse) {
          if (profileDetailsResponse.body.errors) {
            setResponseErrors(Object.values(profileDetailsResponse.body.errors));
          } else {
            setResponseErrors(null);

            setPasswordChangeModalVisible(false);
            setFlash('action', `action:feedback|code:${FEEDBACK_CODES.forgottenPassSendSuccess}`);
            logout();
          }
        }
      },
    },
  );

  let requestGet = useRequest(requestTemplates, detailsGetQuery);
  let requestUpdate = useRequest(requestTemplates, detailsUpdateQuery);
  let requestPasswordUpdate = useRequest(requestTemplates, passwordUpdateQuery);
  requestGet.addRequest('details');
  requestUpdate.addRequest('detailsUpdate');
  requestPasswordUpdate.addRequest('passwordUpdate');

  // Pressing the SAVE button
  function handleSubmit() {
    requestUpdate.modifyHeaders((currentHeader) => {
      currentHeader['Authorization'] = `Bearer ${user.token}`;
    });

    requestUpdate.modifyRequest('detailsUpdate', (currentRequest) => {
      let modifiedInputs = { ...inputs };
      delete modifiedInputs.email;
      delete modifiedInputs.id;

      currentRequest.body = modifiedInputs;
    });

    requestUpdate.commit();
  }

  // Submitting password change in modal
  let handlePasswordChangeSubmit = useCallback(() => {
    requestPasswordUpdate.modifyHeaders((currentHeader) => {
      currentHeader['Authorization'] = `Bearer ${user.token}`;
    });

    requestPasswordUpdate.modifyRequest('passwordUpdate', (currentRequest) => {
      currentRequest.body.email = user.customer.email;
    });

    requestPasswordUpdate.commit();
  });

  // Hide confirmation dialog
  let hideConfirmation = useCallback(() => {
    setPasswordChangeModalVisible(false);
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
    <ProfilSzemelyesAdataimPageComponent>
      <PageHead></PageHead>
      <Header></Header>
      {passwordChangeModalVisible && (
        <Overlay onClick={hideConfirmation} fixed>
          <OverlayCard onClose={hideConfirmation}>
            <OverlayCardContentConfirmation
              title="Biztosan megváltoztatod a jelszavad?"
              submitText="Változtatás"
              cancelText="Mégse"
              onSubmit={handlePasswordChangeSubmit}
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
              <ProfileNavigator selected={0}></ProfileNavigator>
            </ProfileNavigatorWrapper>
            <DataWrapper className="col-md-8 col-lg-7 offset-0 offset-lg-1">
              <ProfileDataTitle>Személyes adataim</ProfileDataTitle>
              <Form>
                <InputSurnameWrapper>
                  <InputText
                    name="input-profile-personal-lastname"
                    value={inputs.lastname}
                    error={errors.lastname}
                    label="Vezetéknév"
                    onChange={(e) => setInput('lastname', e.target.value)}
                  ></InputText>
                </InputSurnameWrapper>
                <InputFirstnameWrapper>
                  <InputText
                    name="input-profile-personal-firstname"
                    value={inputs.firstname}
                    error={errors.firstname}
                    label="Keresztnév"
                    onChange={(e) => setInput('firstname', e.target.value)}
                  ></InputText>
                </InputFirstnameWrapper>
                <InputEmailWrapper>
                  <InputText
                    name="input-profile-personal-email"
                    value={inputs.email}
                    error={errors.email}
                    label="Email cím"
                    onChange={(e) => setInput('email', e.target.value)}
                    readOnly
                  ></InputText>
                </InputEmailWrapper>
                <InputPhoneWrapper>
                  <InputText
                    name="input-profile-personal-phone"
                    value={inputs.phone}
                    error={errors.phone}
                    label="Telefonszám"
                    onChange={(e) => setInput('phone', e.target.value)}
                  ></InputText>
                </InputPhoneWrapper>
              </Form>
              {responseErrors && <AlertBox responseErrors={responseErrors}></AlertBox>}
              <Actions>
                <PassChange onClick={() => setPasswordChangeModalVisible(true)}>Jelszó módosítása</PassChange>
                <ButtonWrapper>
                  <Button
                    buttonHeight="50px"
                    buttonWidth="100%"
                    onClick={handleSubmit}
                    loading={detailsUpdateQuery.isLoading}
                    disabled={detailsUpdateQuery.isLoading}
                  >
                    Mentés
                  </Button>
                </ButtonWrapper>
              </Actions>
            </DataWrapper>
          </PageContent>
        </SiteColContainer>
      </Content>
      <Footer></Footer>
    </ProfilSzemelyesAdataimPageComponent>
  );
}

function handlePersonalDetails(requestBuild) {
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
