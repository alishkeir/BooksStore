import { useEffect, useState } from 'react';
import InputPassword from '@components/inputPassword/inputPassword';
import Button from '@components/button/button';
import { useQuery } from 'react-query';
import useInputs from '@hooks/useInputs/useInputs';
import { Request, getResponseById } from '@libs/api';
import SideModalError from '@components/sideModalError/sideModalError';
import { FEEDBACK_CODES } from '@components/sideModalFeedback/sideModalFeedback';
import useHash from '@hooks/useHash/useHash';
import { useDispatch } from 'react-redux';
import { updateSidebar } from '@store/modules/ui';
import { getSiteCode } from '@libs/site';
import {
  ButtonWrapper,
  Form,
  InputPasswordAgainWrapper,
  InputPasswordWrapper,
  SideModalNewPassWrapper,
  Title,
} from '@components/sideModalNewPass/sideModalNewPass.styled';
import settingsVars from "@vars/settingsVars";
import url from "@libs/url";

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    newpass: {
      method: 'PUT',
      path: '/update-password',
      ref: 'update-password',
      request_id: 'newpass',
      body: {
        email: null,
        token: null,
        password: null,
        password_confirmation: null,
      },
    },
  },
};

let inputsDefaults = {
  password1: '',
  password2: '',
};

let errorsDefaults = {
  password1: '',
  password2: '',
};

let request = new Request(requestTemplates);
request.addRequest('newpass');

export default function SideModalNewPass({ data }) {
  let hashData = useHash(data, ['token', 'email']);
  let dispatch = useDispatch();

  let { inputs, setInput, errors, setErrors } = useInputs(inputsDefaults, errorsDefaults);
  let [responseErrors, setResponseErrors] = useState(null);

  let newpassQuery = useQuery('newpass', () => handleNewPass(request.build()), {
    enabled: false,
    refetchOnWindowFocus: false,
    refetchOnMount: false,
    staleTime: 0,
    onSettled: (data) => {
      let newpassResponse = getResponseById(data, 'newpass');

      if (!newpassResponse.success) {
        setResponseErrors(newpassResponse.body.errors ? Object.values(newpassResponse.body.errors) : null);
      } else {
        if (responseErrors) setResponseErrors(null);

        dispatch(updateSidebar({ open: true, type: 'feedback', data: `action:feedback|code:${FEEDBACK_CODES.newPassSuccess}` }));
      }
    },
  });

  // On request commit we refetch
  useEffect(() => {
    let commitHandler = () => {
      newpassQuery.refetch();
    };

    request.events.on('commit', commitHandler);

    return () => {
      request.events.off('commit', commitHandler);
    };
  }, []);

  function handleSubmit() {
    if (responseErrors) setResponseErrors(null);

    import('joi').then((module) => {
      let joi = module.default;

      let schema = joi.object({
        password1: joi.string().required().min(8),
        // .pattern(/^(?=.*[a-záéíóöőúüű])(?=.*[A-ZÁÉÍÓÖŐÚÜŰ])(?=.*\d).{3,}$/),
        password2: joi.ref('password1'),
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
            case 'string.min':
              newErrorState[error.context.key] = `Minimum ${error.context.limit} karakter lehet`;
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

        request.modifyRequest('newpass', (requestObject) => {
          requestObject.body.email = hashData.email;
          requestObject.body.token = hashData.token;
          requestObject.body.password = inputs.password1;
          requestObject.body.password_confirmation = inputs.password2;
        });

        request.commit();
      }
    });
  }

  return (
    <SideModalNewPassWrapper>
      <Form>
        <Title>Jelszó létrehozása</Title>
        <InputPasswordWrapper>
          <InputPassword
            value={inputs.password1}
            error={errors.password1}
            sub="Min. 8 karakter, nagybetű, kisbetű, szám"
            label="Jelszó"
            onChange={(e) => setInput('password1', e.target.value)}
          ></InputPassword>
        </InputPasswordWrapper>
        <InputPasswordAgainWrapper>
          <InputPassword
            value={inputs.password2}
            error={errors.password2}
            label="Jelszó"
            onChange={(e) => setInput('password2', e.target.value)}
          ></InputPassword>
        </InputPasswordAgainWrapper>
        {responseErrors && <SideModalError responseErrors={responseErrors}></SideModalError>}
        <ButtonWrapper>
          <Button buttonWidth="100%" buttonHeight="50px" onClick={handleSubmit} loading={newpassQuery.isFetching} disabled={newpassQuery.isFetching}>
            Kész
          </Button>
        </ButtonWrapper>
      </Form>
    </SideModalNewPassWrapper>
  );
}

function handleNewPass(request) {
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
