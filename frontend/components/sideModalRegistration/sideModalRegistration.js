import { useEffect, useState } from 'react';
import InputText from '@components/inputText/inputText';
import { useMutation, useQuery } from 'react-query';
import useInputs from '@hooks/useInputs/useInputs';
import InputPassword from '@components/inputPassword/inputPassword';
import InputCheckbox from '@components/inputCheckbox/inputCheckbox';
import SideModalError from '@components/sideModalError/sideModalError';
import { FEEDBACK_CODES } from '@components/sideModalFeedback/sideModalFeedback';
import Button from '@components/button/button';
import { useDispatch } from 'react-redux';
import { updateSidebar } from '@store/modules/ui';
import { Request, getResponseById, handleApiRequest } from '@libs/api';
import { getSiteCode } from '@libs/site';
import {
  AgreeMarketingWrapper,
  AgreeTacWrapper,
  ButtonWrapper,
  Form,
  InputEmailAgainWrapper,
  InputEmailWrapper,
  InputPasswordAgainWrapper,
  InputPasswordWrapper,
  SideModalRegistrationWrapper,
} from '@components/sideModalRegistration/sideModalRegistration.styled';
import useRequest from '@hooks/useRequest/useRequest';
import { updateGuestData, updateUserData } from '@store/modules/user';
import { resetCheckout } from '@store/modules/checkout';
import useUser from '@hooks/useUser/useUser';
import settingsVars from "@vars/settingsVars";
import url from "@libs/url";

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    registration: {
      method: 'POST',
      path: '/register',
      ref: 'register',
      request_id: 'registration',
      body: {
        email: null,
        email_confirmation: null,
        password: null,
        password_confirmation: null,
        marketing_accepted: null,
      },
    },
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
  },
};

let inputsDefaults = {
  email: '',
  email_confirmation: '',
  password: '',
  password_confirmation: '',
  tac_accepted: false,
  marketing_accepted: false,
};

let errorsDefaults = {
  email: '',
  email_confirmation: '',
  password: '',
  password_confirmation: '',
  tac_accepted: '',
  marketing_accepted: '',
};

let request = new Request(requestTemplates);
request.addRequest('registration');

export default function SideModalRegistration({ onClose = () => {} }) {
  let settings = settingsVars.get(url.getHost());

  let { inputs, setInput, setInputs, errors, setErrors } = useInputs(inputsDefaults, errorsDefaults);
  let [responseErrors, setResponseErrors] = useState(null);
  let dispatch = useDispatch();
  let { actualUser } = useUser();

  let registrationQuery = useQuery('registration', () => handleRegistration(request.build()), {
    enabled: false,
    refetchOnWindowFocus: false,
    refetchOnMount: false,
    staleTime: 0,
    onSettled: (data) => {
      let regResponse = getResponseById(data, 'registration');

      if (regResponse.body.errors) {
        setResponseErrors(Object.values(regResponse.body.errors));
      } else {
        if (responseErrors) setResponseErrors(null);

        // Login
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
    },
  });

  let loginQuery = useMutation('login', (requestUpdateBuild) => handleApiRequest(requestUpdateBuild));

  let loginRequest = useRequest(requestTemplates, loginQuery);

  // On request commit we refetch
  useEffect(() => {
    let commitHandler = () => {
      registrationQuery.refetch();
    };

    request.events.on('commit', commitHandler);

    return () => {
      request.events.off('commit', commitHandler);
    };
  }, []);

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

      // Closing sidebar
      onClose();

      // We reset the checkout data if any
      dispatch(resetCheckout());

      dispatch(updateSidebar({ open: true, type: 'feedback', data: `action:feedback|code:${FEEDBACK_CODES.registrationSuccess}` }));
    } else {
      if (response?.body.errors) {
        setResponseErrors(Object.values(response?.body.errors));
      } else {
        setResponseErrors(['Sikertelen bejelentkezés']);
      }
    }
  }

  function handleSubmit() {
    if (responseErrors) setResponseErrors(null);

    import('joi').then((module) => {
      let joi = module.default;

      let schema = joi.object({
        email: joi.string().required().email({ tlds: false }),
        email_confirmation: joi.ref('email'),
        password: joi.string().required().min(8),
        password_confirmation: joi.ref('password'),
        tac_accepted: joi.boolean().valid(true),
      });

      let validation = schema.validate(inputs, { abortEarly: false, allowUnknown: true });

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
            case 'string.min':
              newErrorState[error.context.key] = `Minimum ${error.context.limit} karakter lehet`;
              break;
            case 'any.only':
              switch (error.context.key) {
                case 'email_confirmation':
                  newErrorState[error.context.key] = 'A két e-mail cím nem egyezik';
                  break;
                case 'password_confirmation':
                  newErrorState[error.context.key] = 'A két jelszó nem egyezik';
                  break;
                case 'tac_accepted':
                  newErrorState[error.context.key] = 'Ezt el kell fogadnod';
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

        request.modifyRequest('registration', (requestObject) => {
          requestObject.body.email = inputs.email;
          requestObject.body.email_confirmation = inputs.email_confirmation;
          requestObject.body.password = inputs.password;
          requestObject.body.password_confirmation = inputs.password_confirmation;
          requestObject.body.marketing_accepted = inputs.marketing_accepted;
          requestObject.body.tac_accepted = inputs.tac_accepted;
        });

        request.commit();
      }
    });
  }

  return (
    <SideModalRegistrationWrapper>
      <Form>
        <InputEmailWrapper>
          <InputText
            name="input-sidemodal-registration-email"
            value={inputs.email}
            error={errors.email}
            label="Email cím"
            onChange={(e) => setInput('email', e.target.value)}
          ></InputText>
        </InputEmailWrapper>
        <InputEmailAgainWrapper>
          <InputText
            name="input-sidemodal-registration-email_confirmation"
            label="Email cím még egyszer"
            value={inputs.email_confirmation}
            error={errors.email_confirmation}
            onChange={(e) => setInput('email_confirmation', e.target.value)}
          ></InputText>
        </InputEmailAgainWrapper>
        <InputPasswordWrapper>
          <InputPassword
            name="input-sidemodal-registration-password"
            label="Jelszó"
            sub="Min. 8 karakter, nagybetű, kisbetű, szám"
            value={inputs.password}
            error={errors.password}
            onChange={(e) => setInput('password', e.target.value)}
          ></InputPassword>
        </InputPasswordWrapper>
        <InputPasswordAgainWrapper>
          <InputPassword
            name="input-sidemodal-registration-password_confirmation"
            label="Jelszó még egyszer"
            value={inputs.password_confirmation}
            error={errors.password_confirmation}
            onChange={(e) => setInput('password_confirmation', e.target.value)}
          ></InputPassword>
        </InputPasswordAgainWrapper>
        <AgreeMarketingWrapper>
          <InputCheckbox
            label={'Hozzájárulok, hogy a Publish and More Kft. a nevemet és e-mail címemet hírlevelezési céllal kezelje.'}
            checked={inputs.marketing_accepted}
            error={errors.marketing_accepted}
            onChange={(e) => setInput('marketing_accepted', e.target.checked)}
          ></InputCheckbox>
        </AgreeMarketingWrapper>
        <AgreeTacWrapper>
          <InputCheckbox
            label={
              'Elolvastam az <a href="/oldal/adatvedelem" target="_blank" rel="noreferrer noopener">Adatvédelmi tájékoztatót</a> és elfogadom a benne foglaltakat.'
            }
            checked={inputs.tac_accepted}
            error={errors.tac_accepted}
            onChange={(e) => setInput('tac_accepted', e.target.checked)}
          ></InputCheckbox>
        </AgreeTacWrapper>
        {responseErrors && <SideModalError responseErrors={responseErrors}></SideModalError>}
        <ButtonWrapper>
          <Button
            buttonWidth="100%"
            buttonHeight="50px"
            onClick={handleSubmit}
            loading={registrationQuery.isFetching}
            disabled={registrationQuery.isFetching}
          >
            Regisztráció
          </Button>
        </ButtonWrapper>
      </Form>
    </SideModalRegistrationWrapper>
  );
}

function handleRegistration(request) {
  let settings = settingsVars.get(url.getHost());

  return fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/v1/${getSiteCode(settings.key)}/composite`, {
    method: 'POST',
    headers: request.headers,
    body: JSON.stringify(request.body),
  })
    .then((response) => {
      if (!response.ok) throw new Error(`API response: ${response.status}`);
      return response.json();
    })
    .then((data) => data)
    .catch((error) => console.log(error));
}
