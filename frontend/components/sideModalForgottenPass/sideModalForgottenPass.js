import { useEffect, useState } from 'react';
import { useRouter } from 'next/router';
import SideModalError from '@components/sideModalError/sideModalError';
import InputText from '@components/inputText/inputText';
import Button from '@components/button/button';
import { useQuery } from 'react-query';
import useInputs from '@hooks/useInputs/useInputs';
import { Request, getResponseById } from '@libs/api';
import { FEEDBACK_CODES } from '@components/sideModalFeedback/sideModalFeedback';
import { useDispatch } from 'react-redux';
import { updateSidebar } from '@store/modules/ui';
import { getSiteCode } from '@libs/site';
import {
  ButtonWrapper,
  Form,
  InputEmailWrapper,
  SideModalForgottenPassWrapper,
  Text,
  Title,
} from '@components/sideModalForgottenPass/sideModalForgottenPass.styled';
import settingsVars from "@vars/settingsVars";
import url from "@libs/url";

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    forgottenpass: {
      method: 'POST',
      path: '/forgot-password',
      ref: 'forgot-password',
      request_id: 'forgottenpass',
      body: {
        email: null,
      },
    },
  },
};

let inputsDefaults = {
  email: '',
};

let errorsDefaults = {
  email: '',
};

let request = new Request(requestTemplates);
request.addRequest('forgottenpass');

export default function SideModalForgottenPass(props) {
  let { onSetSidebar, onClose } = props;

  let { inputs, setInput, errors, setErrors } = useInputs(inputsDefaults, errorsDefaults);
  let [responseErrors, setResponseErrors] = useState(null);
  let dispatch = useDispatch();
  let router = useRouter();

  let forgottenPassQuery = useQuery('forgottenpass', () => handleForgottenPass(request.build()), {
    enabled: false,
    refetchOnWindowFocus: false,
    refetchOnMount: false,
    staleTime: 0,
    onSettled: (data) => {
      let forgottenPassResponse = getResponseById(data, 'forgottenpass');

      if (!forgottenPassResponse.success) {
        setResponseErrors(forgottenPassResponse.body.errors ? Object.values(forgottenPassResponse.body.errors) : null);
      } else {
        if (responseErrors) setResponseErrors(null);

        dispatch(updateSidebar({ open: true, type: 'feedback', data: `action:feedback|code:${FEEDBACK_CODES.forgottenPassSendSuccess}` }));
      }
    },
  });

  // On request commit we refetch
  useEffect(() => {
    let commitHandler = () => {
      forgottenPassQuery.refetch();
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
        email: joi.string().required().email({ tlds: false }),
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

        request.modifyRequest('forgottenpass', (requestObject) => {
          requestObject.body.email = inputs.email;
        });

        request.commit();
      }
    });
  }

  return (
    <SideModalForgottenPassWrapper>
      <Form>
        <Title>Elfelejtett jelszó</Title>
        <Text>Add meg a regisztrációkor használt email címed. Az emailben kapott linkkel új jelszót adhatsz meg.</Text>
        <InputEmailWrapper>
          <InputText
            name="input-sidemodal-forgottenpass-email"
            value={inputs.email}
            error={errors.email}
            label="Email cím"
            onChange={(e) => setInput('email', e.target.value)}
          ></InputText>
        </InputEmailWrapper>
        {responseErrors && <SideModalError responseErrors={responseErrors}></SideModalError>}
        <ButtonWrapper>
          <Button
            buttonWidth="100%"
            buttonHeight="50px"
            onClick={handleSubmit}
            loading={forgottenPassQuery.isFetching}
            disabled={forgottenPassQuery.isFetching}
          >
            Küldés
          </Button>
        </ButtonWrapper>
        <ButtonWrapper>
          <Button type="secondary" buttonWidth="100%" buttonHeight="50px" onClick={handleCancelButtonClick}>
            Mégse
          </Button>
        </ButtonWrapper>
      </Form>
    </SideModalForgottenPassWrapper>
  );

  function handleCancelButtonClick() {
    if (router.route === '/auth/login') {
      onClose();
    } else {
      onSetSidebar('login');
    }
  }
}

function handleForgottenPass(request) {
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
