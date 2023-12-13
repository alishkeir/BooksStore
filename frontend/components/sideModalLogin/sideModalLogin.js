import { useEffect, useState } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { useGoogleLogin } from '@react-oauth/google';
import InputText from '@components/inputText/inputText';
import InputPassword from '@components/inputPassword/inputPassword';
import { useMutation } from 'react-query';
import Button from '@components/button/button';
import Icon from '@components/icon/icon';
import SideModalError from '@components/sideModalError/sideModalError';
import useInputs from '@hooks/useInputs/useInputs';
import useUser from '@hooks/useUser/useUser';
import { resetCheckout } from '@store/modules/checkout';
import { updateUserData, updateGuestData } from '@store/modules/user';
import { handleApiRequest, getResponseById } from '@libs/api';
import useRequest from '@hooks/useRequest/useRequest';
import {
  ActionItem,
  TopActions,
  BotActions,
  ButtonWrapper,
  Form,
  InputEmailWrapper,
  InputPasswordWrapper,
  LoginButtonGoogle,
  Separator,
  SeparatorLine,
  SeparatorText,
  SideModalLoginWrapper,
  Social,
  SocialButtonIcon,
  SocialButtonText,
  SocialButtonWrapper,
} from '@components/sideModalLogin/sideModalLogin.styled';
import router from 'next/router';
import { updateRedirectAfterLogin } from '@store/modules/ui';
import settingsVars from "@vars/settingsVars";
import url from "@libs/url";

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    login: {
      method: 'POST',
      path: '/login',
      ref: 'login',
      request_id: 'login',
      body: {
        email: null,
        password: null,
      },
    },
    'social-login': {
      method: 'POST',
      path: '/social/login',
      ref: 'social-login',
      request_id: 'social-login',
      body: {
        provider: null,
        token: null,
      },
    },
  },
};

let inputsDefaults = {
  email: '',
  password: '',
};

let errorsDefaults = {
  email: '',
  password: '',
};

export default function SideModalLogin(props) {
  let { onSetSidebar = () => { }, onClose = () => { }, redirect } = props;

  let dispatch = useDispatch();
  let { actualUser } = useUser();

  let settings = settingsVars.get(url.getHost());
  let redirectAfterLogin = useSelector((store) => store.ui.redirectAfterLogin);

  let { inputs, setInput, setInputs, errors, setErrors } = useInputs(inputsDefaults, errorsDefaults);
  let [responseErrors, setResponseErrors] = useState(null);
  let [googleLoginVisible, setGoogleLoginVisible] = useState(true);

  let loginQuery = useMutation('login', (requestUpdateBuild) => handleApiRequest(requestUpdateBuild));
  let socialLoginQuery = useMutation('social-login', (requestUpdateBuild) => handleApiRequest(requestUpdateBuild));

  let loginRequest = useRequest(requestTemplates, loginQuery);
  let socialLoginRequest = useRequest(requestTemplates, socialLoginQuery);

  function handleGoogleCallback(response) {
    if (responseErrors) setResponseErrors(null);
    if (!response.access_token) {
      setResponseErrors(['Sikertelen Google bejelentkezés']);
    } else {
      handleSocialLogin('google', response.access_token);
    }
  }

  function handleGoogleFailure(response) {
    console.log('Google login error:', response);

    if (response.error) {
      //FIXME: this is just a temporary fix.
      setGoogleLoginVisible(true);
    } else {
      setGoogleLoginVisible(true);
    }
  }

  function handleSocialLogin(provider, token) {
    socialLoginRequest.addRequest('social-login');

    socialLoginRequest.modifyRequest('social-login', (requestObject) => {
      requestObject.body.provider = provider;
      requestObject.body.token = token;

      // If guest is present, we merge with user
      if (actualUser?.type === 'guest') requestObject.body.guest_token = actualUser.token;
    });

    socialLoginRequest.commit({
      onSettled: (data) => loginSettledCallback(data, 'social-login'),
    });
  }

  function handleSubmit() {
    if (responseErrors) setResponseErrors(null);

    import('joi').then((module) => {
      let joi = module.default;

      let schema = joi.object({
        email: joi.string().required().email({ tlds: false }),
        password: joi.string().required(),
      });

      let validation = schema.validate(inputs, { abortEarly: false });

      if (validation.error) {
        let newErrorState = { ...errorsDefaults };

        validation.error.details.forEach((error) => {
          switch (error.type) {
            case 'string.empty':
              newErrorState[error.context.key] = 'Ez a mező nem lehet üres';
              break;
            case 'string.email':
              newErrorState[error.context.key] = 'Nem megfelelő e-mail cím';
              break;
            case 'any.only':
              switch (error.context.key) {
                case 'email2':
                  newErrorState[error.context.key] = 'A két e-mail cím nem egyezik';
                  break;
                case 'password2':
                  newErrorState[error.context.key] = 'A két jelszó nem egyezik';
                  break;
              }

              break;

            default:
              newErrorState[error.context.key] = 'Hibás mező';
              break;
          }
        });

        setErrors(newErrorState);
      } else {
        setErrors({ ...errorsDefaults });

        loginRequest.addRequest('login');

        loginRequest.modifyRequest('login', (requestObject) => {
          requestObject.body.email = inputs.email;
          requestObject.body.password = inputs.password;

          // If guest is present, we merge with user
          if (actualUser?.type === 'guest') requestObject.body.guest_token = actualUser.token;
        });

        loginRequest.commit({
          onSettled: (data) => loginSettledCallback(data, 'login'),
        });
      }
    });
  }

  function loginSettledCallback(data, request) {
    let response = getResponseById(data, request);

    if (response?.success) {
      // Login success

      // Resetting errors/forms
      setInputs({ ...inputsDefaults });
      if (responseErrors) setResponseErrors(null);

      // Adding user to store
      dispatch(updateUserData(response.body));

      // Removing guest user
      dispatch(updateGuestData(null));
      localStorage.removeItem(`${settings.key}-guest-token`);

      // Saving token
      localStorage.setItem(
        `${settings.key}-user-token`,
        JSON.stringify({
          token: response.body.token,
          valid_until: response.body.valid_until,
        }),
      );

      // We reset the checkout data if any
      dispatch(resetCheckout());

      // Redirect
      if (redirect) {
        router.push(redirect);
      } else if (redirectAfterLogin) {
        router.push(redirectAfterLogin);
      }

      // Closing sidebar
      onClose();
    } else {
      if (response?.body.errors) {
        setResponseErrors(Object.values(response?.body.errors));
      } else {
        setResponseErrors(['Sikertelen bejelentkezés']);
      }
    }
  }

  const loginGoogle = useGoogleLogin({
    onSuccess: (tokenResponse) => handleGoogleCallback(tokenResponse),
    onError: (response) => handleGoogleFailure(response),
  });

  useEffect(() => {
    return () => dispatch(updateRedirectAfterLogin(null));
  }, []);

  return (
    <SideModalLoginWrapper>
      <TopActions>
        {settings.common.auth.registration === true && (
          <>
            Még nem regisztráltál? &nbsp; <ActionItem onClick={() => onSetSidebar('registration')}>Regisztráció &raquo;</ActionItem>
          </>
        )}
      </TopActions>
      <Form>
        <InputEmailWrapper>
          <InputText
            name="input-sidemodal-login-email"
            value={inputs.email}
            error={errors.email}
            label="Email cím"
            onChange={(e) => setInput('email', e.target.value)}
          ></InputText>
        </InputEmailWrapper>
        <InputPasswordWrapper>
          <InputPassword
            name="input-sidemodal-login-password"
            value={inputs.password}
            error={errors.password}
            label="Jelszó"
            onChange={(e) => setInput('password', e.target.value)}
          ></InputPassword>
        </InputPasswordWrapper>
        {responseErrors && <SideModalError responseErrors={responseErrors}></SideModalError>}
        <ButtonWrapper>
          <Button buttonWidth="100%" buttonHeight="50px" onClick={handleSubmit} loading={loginQuery.isFetching} disabled={loginQuery.isFetching}>
            Belépés
          </Button>
        </ButtonWrapper>
      </Form>
      {settings.common.auth.socialLogin === true && (
        <>
          <Separator>
            <SeparatorLine></SeparatorLine>
            <SeparatorText>vagy</SeparatorText>
          </Separator>
          <Social>
            {googleLoginVisible && (
              <SocialButtonWrapper onClick={() => loginGoogle()}>
                <LoginButtonGoogle>
                  <SocialButtonIcon>
                    <Icon type="social-google-icon" iconWidth="19px"></Icon>
                  </SocialButtonIcon>
                  <SocialButtonText>Google fiókkal</SocialButtonText>
                </LoginButtonGoogle>
              </SocialButtonWrapper>
            )}
          </Social>
        </>
      )}
      <BotActions>
        <ActionItem onClick={() => onSetSidebar('forgottenpass')}>Elfelejtettem a jelszavam</ActionItem>
      </BotActions>
    </SideModalLoginWrapper>
  );
}
