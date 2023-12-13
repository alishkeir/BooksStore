import dynamic from 'next/dynamic';
import { useState } from 'react';
import { useMutation } from 'react-query';
import useRequest from '@hooks/useRequest/useRequest';
import { handleApiRequest } from '@libs/api';
const InputText = dynamic(() => import('@components/inputText/inputText'));
const InputCheckbox = dynamic(() => import('@components/inputCheckbox/inputCheckbox'));
const Button = dynamic(() => import('@components/button/button'));
import { MainNewsletterSignupComponent, InputTextWrapper, InputCheckboxWrapper, ButtonWrapper, TitleWrapper } from './mainNewsletterSignup.styled.js';

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    'newsletter-subscribe': {
      method: 'POST',
      path: '/newsletter',
      ref: 'subscribe',
      request_id: 'newsletter-subscribe',
      body: {
        email: null,
        marketing_accepted: null,
      },
    },
  },
};

export default function MainNewsletterSignup(props) {
  let { setModalOpen = () => {} } = props;
  let inputsDefaults = {
    email: '',
    checkbox: false,
  };

  let errorsDefaults = {
    email: '',
    checkbox: '',
  };

  let [inputs, setInputs] = useState(inputsDefaults);
  let [errors, setErrors] = useState(errorsDefaults);

  let queryNewsletterSubscribe = useMutation('newsletter-subscribe', (requestBuild) => handleApiRequest(requestBuild), {
    onSuccess: () => {
      setModalOpen();
      setInputs(inputsDefaults);
    },
  });

  let requestNewsletterSubscribe = useRequest(requestTemplates, queryNewsletterSubscribe);

  requestNewsletterSubscribe.addRequest('newsletter-subscribe');

  let handleNewsletterSubscribeSubmit = ({ email, checkboxState, event }) => {
    event.preventDefault();
    event.stopPropagation();

    requestNewsletterSubscribe.modifyRequest('newsletter-subscribe', (currentRequest) => {
      currentRequest.body.email = email;
      currentRequest.body.marketing_accepted = checkboxState;
    });

    requestNewsletterSubscribe.commit();
  };

  return (
    <MainNewsletterSignupComponent>
      <TitleWrapper>Iratkozz fel hírlevelünkre</TitleWrapper>
      <InputTextWrapper>
        <InputText
          label="Email cím"
          error={errors.email}
          name="input-main-newsletter"
          value={inputs.email}
          onChange={(e) => handleInput('email', e.target.value)}
        ></InputText>
      </InputTextWrapper>
      <InputCheckboxWrapper>
        <InputCheckbox
          label='Hozzájárulok, hogy a Publish and More Kft. a nevemet és e-mail címemet hírlevelezési céllal kezelje. Elolvastam az <a href="/oldal/adatvedelem" target="_blank" rel="noreferrer noopener">adatvédelmi tájékoztatót</a> és elfogadom a benne foglaltakat.'
          error={errors.checkbox}
          checked={inputs.checkbox}
          onChange={() => handleInput('checkbox', !inputs.checkbox)}
        ></InputCheckbox>
      </InputCheckboxWrapper>
      <ButtonWrapper>
        <Button buttonWidth="200px" onClick={handleSubmit}>
          Feliratkozom
        </Button>
      </ButtonWrapper>
    </MainNewsletterSignupComponent>
  );

  function handleInput(key, value) {
    // Resetting field error
    if (errors[key]) setErrors({ ...errors, [key]: errorsDefaults[key] });

    // Setting new inputs field value
    setInputs({ ...inputs, [key]: value });
  }

  function handleSubmit(event) {
    import('joi').then((module) => {
      let joi = module.default;

      let schema = joi.object({
        email: joi.string().required().email({ tlds: false }),
        checkbox: joi.boolean().valid(true),
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
              newErrorState[error.context.key] = 'Ez a mező kötelező';
              break;

            default:
              newErrorState[error.context.key] = 'Hibás mező';
              break;
          }

          setErrors(newErrorState);
        });
      } else {
        handleNewsletterSubscribeSubmit({ event, email: inputs.email, checkboxState: inputs.checkbox });
        setInputs(inputsDefaults);
        setErrors({ ...errorsDefaults });
      }
    });
  }
}
