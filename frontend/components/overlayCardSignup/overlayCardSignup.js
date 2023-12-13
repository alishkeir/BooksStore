import styled from '@emotion/styled';
import Overlay from '@components/overlay/overlay';
import OverlayCard from '@components/overlayCard/overlayCard';
import breakpoints from '@vars/breakpoints';
import colors from '@vars/colors';
import InputText from '@components/inputText/inputText';
import { useCallback, useState } from 'react';
import InputCheckbox from '@components/inputCheckbox/inputCheckbox';
import Button from '@components/button/button';
import { handleApiRequest, getResponseById } from '@libs/api';
import { useMutation } from 'react-query';
import useRequest from '@hooks/useRequest/useRequest';
import { useDispatch } from 'react-redux';
import { updateOverlay } from '@store/modules/ui';
import AlertBox from '@components/alertBox/alertBox';

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    'public-preorder-post': {
      method: 'POST',
      path: '/public/preorders',
      ref: 'publicPreOrders',
      request_id: 'public-preorder-post',
      body: {
        product_id: null,
        email: null,
      },
    },
  },
};

let inputsDefaults = {
  email: '',
  checkbox: false,
};

let errorsDefaults = {
  email: '',
  checkbox: '',
};

export default function OverlayCardSignup({ itemId }) {
  let [responseErrors, setResponseErrors] = useState(null);
  let [inputs, setInputs] = useState({ ...inputsDefaults });
  let [errors, setErrors] = useState({ ...errorsDefaults });
  let [success, setSuccess] = useState(false);

  let dispatch = useDispatch();

  let billingAddressUpdateQuery = useMutation('public-preorder-post', (requestUpdateBuild) => handleApiRequest(requestUpdateBuild), {
    onSuccess: (data) => {
      let billingAddressUpdateResponse = getResponseById(data, 'public-preorder-post');

      if (billingAddressUpdateResponse?.success) {
        setSuccess(true);
      } else {
        billingAddressUpdateResponse?.body?.errors && setResponseErrors(Object.values(billingAddressUpdateResponse.body.errors));
      }
    },
  });

  let requestUserBillingAddressUpdate = useRequest(requestTemplates, billingAddressUpdateQuery);
  requestUserBillingAddressUpdate.addRequest('public-preorder-post');

  function handleClose() {
    dispatch(updateOverlay({ open: false, type: '', data: '' }));
  }

  // Hiting submit on new address as user
  let handleSubmitButtonClick = useCallback(() => {
    if (responseErrors) setResponseErrors(null);

    hadleInputValidation().then((validation) => {
      if (validation === true) {
        setErrors({ ...errorsDefaults });

        requestUserBillingAddressUpdate.modifyRequest('public-preorder-post', (requestObject) => {
          requestObject.body.product_id = itemId;
          requestObject.body.email = inputs.email;
        });

        requestUserBillingAddressUpdate.commit();
      } else {
        setErrors(validation);
      }
    });
  });

  function hadleInputValidation() {
    return import('joi').then((module) => {
      let joi = module.default;

      let schema = joi.object({
        email: joi.string().required().email({ tlds: false }),
        checkbox: joi.boolean().valid(true),
      });

      let validation = schema.validate(inputs, { abortEarly: false, allowUnknown: true });

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

        return newErrorState;
      } else {
        return true;
      }
    });
  }

  return (
    <Overlay fixed={false}>
      <OverlayCard onClose={handleClose}>
        <OverlayCardContent
          title="Kedves Vásárlónk!"
          text="A kosaradba raktál e-könyvet. Az elektronikus vagy digitális könyvet (a vásárlás után) csak letölteni tudod, azt papír könyvként nem küldjük el neked. Az e-könyv a hatályos jogszabályok szerint szolgáltatásnak minősül, arra nem vonatkozik a 14 napos elállás joga."
          submitText="Értem, így szeretném"
          cancelText="Vissza a kosaramhoz"
          onCancel={handleClose}
        >
          <Title>{success ? 'Köszönjük!' : 'Kérjük add meg email címed!'}</Title>
          <Text>Értesítünk, amint elérhetővé válik ez a könyv.</Text>
          {!success && (
            <Form>
              <InputTextWrapper>
                <InputText
                  type="email"
                  value={inputs.email}
                  error={errors.email}
                  label="Email cím"
                  name="email"
                  onChange={(e) => handleInput('email', e.target.value)}
                  readOnly={billingAddressUpdateQuery.isLoading}
                ></InputText>
              </InputTextWrapper>
              <InputCheckboxWrapper>
                <InputCheckbox
                  label='Elolvastam az <a href="/oldal/adatvedelem" target="_blank" rel="noreferrer noopener">adatvédelmi tájékoztatót</a> és elfogadom a benne foglaltakat'
                  error={errors.checkbox}
                  checked={inputs.checkbox}
                  onChange={() => handleInput('checkbox', !inputs.checkbox)}
                  disabled={billingAddressUpdateQuery.isLoading}
                ></InputCheckbox>
              </InputCheckboxWrapper>
            </Form>
          )}
          {responseErrors && <AlertBoxS responseErrors={responseErrors}></AlertBoxS>}
          <FormActions>
            {!success && (
              <>
                <ButtonWrapper>
                  <Button
                    type="secondary"
                    buttonWidth="100%"
                    buttonHeight="50px"
                    onClick={handleClose}
                    disabled={billingAddressUpdateQuery.isLoading}
                  >
                    Mégse
                  </Button>
                </ButtonWrapper>
                <ButtonWrapper>
                  <Button
                    type="primary"
                    buttonWidth="100%"
                    buttonHeight="50px"
                    onClick={handleSubmitButtonClick}
                    loading={billingAddressUpdateQuery.isLoading}
                    disabled={billingAddressUpdateQuery.isLoading}
                  >
                    Előjegyzés
                  </Button>
                </ButtonWrapper>
              </>
            )}
            {success && (
              <ButtonWrapper>
                <Button type="primary" buttonWidth="100%" buttonHeight="50px" onClick={handleClose}>
                  Bezárás
                </Button>
              </ButtonWrapper>
            )}
          </FormActions>
        </OverlayCardContent>
      </OverlayCard>
    </Overlay>
  );

  function handleInput(key, value) {
    // Resetting field error
    if (errors[key]) setErrors({ ...errors, [key]: errorsDefaults[key] });

    // Setting new inputs field value
    setInputs({ ...inputs, [key]: value });
  }
}

const OverlayCardContent = styled.div`
  padding: 50px 50px 40px;

  @media (max-width: ${breakpoints.max.md}) {
    padding: 50px 0 10px;
  }
`;
const Title = styled.div`
  font-weight: 600;
  font-size: 24px;
  text-align: center;
  color: ${colors.mineShaftDark};

  @media (max-width: ${breakpoints.max.sm}) {
    font-size: 20px;
  }
`;

const Text = styled.div`
  margin-top: 10px;
  text-align: center;
`;
const Form = styled.div`
  margin-top: 20px;
`;
const InputTextWrapper = styled.div`
  margin-bottom: 20px;
`;
const InputCheckboxWrapper = styled.div``;
const FormActions = styled.div`
  display: flex;
  align-items: center;
  margin: 30px -10px 0;

  @media (max-width: ${breakpoints.max.md}) {
    flex-direction: column;
    margin-top: 30px;
  }
`;

const ButtonWrapper = styled.div`
  width: 100%;
  margin: 0 10px 0;

  @media (max-width: ${breakpoints.max.md}) {
    margin: 0 10px 10px;
  }
`;
const AlertBoxS = styled(AlertBox)`
  margin-top: 20px;
`;
