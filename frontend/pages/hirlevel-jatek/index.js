import { useState } from 'react';
import dynamic from 'next/dynamic';
import { getMetadata } from '@libs/api';

const Button = dynamic(() => import('@components/button/button'));
const Content = dynamic(() => import('@components/content/content'));
const Header = dynamic(() => import('@components/header/header'));
const InputRadioBlock = dynamic(() => import('@components/inputRadioBlock/inputRadioBlock'));
const InputText = dynamic(() => import('@components/inputText/inputText'));
import {
  HirlevelJatekContainer,
  HirlevelJatekPageWrapper,
  HirlevelJatekHeaderContainer,
  HirlevelJatekInputs,
  HirlevelJatekFormWrapper,
  HirlevelJatekButtonWrapper,
  SuccessMessageHeader,
  NewsletterGame,
} from '@components/pages/hirlevelJatekPage.styled';

import NewsletterGameImage from '@assets/images/samples/newsletter_game.jpg';
import DynamicHead from '@components/heads/DynamicHead';

const InputTextWrapper = dynamic(() => import('@components/mainNewsletterSignup/mainNewsletterSignup.styled').then((mod) => mod.InputTextWrapper));
const InputEmailWrapper = dynamic(() => import('@components/sideModalLogin/sideModalLogin.styled').then(mod => mod.InputEmailWrapper));

//import XmasTreeIcon from '@assets/images/icons/xmas-tree.svg';
import { InputCheckboxWrapper } from '@components/inputCheckbox/inputCheckbox.styled';
import OptimizedImage from '@components/Images/OptimizedImage';

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
};

let inputsDefaults = {
  name: '',
  email: '',
  telephone: '',
  address: '',
  order_number: '',
  checkbox: '',
};

let errorsDefaults = {
  name: '',
  email: '',
  telephone: '',
  address: '',
  order_number: '',
  checkbox: '',
};

const HirlevelJatekPage = ({metadata}) =>
{
  let [inputs, setInputs] = useState({ ...inputsDefaults });
  let [errors, setErrors] = useState({ ...errorsDefaults });
  let [submitSuccess, setSubmitSuccess] = useState(false);
  const [conditionsAgreed, setConditionsAgreed] = useState(false);

  // TODO: CHANGE URL
  const newsletterGameEndpoint = '/0/basic-gaming-form/store';

  const handleInputValidation = () =>
  {
    return import('joi').then((module) =>
    {
      let joi = module.default;

      let schema = joi.object({
        name: joi.string().required(),
        email: joi.string().required().email({ tlds: false }),
        telephone: joi.string().required(),
        checkbox: joi.boolean().valid(true),
      });

      let validation = schema.validate(inputs, { abortEarly: false, allowUnknown: true });

      if (validation.error)
      {
        let newErrorState = { ...errorsDefaults };

        validation.error.details.forEach((error) =>
        {
          switch (error.type)
          {
            case 'string.empty':
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
      } else
      {
        return true;
      }
    });
  };

  const handleSubmitButtonClick = () =>
  {
    handleInputValidation().then((validation) =>
    {
      if (validation === true)
      {
        setErrors({ ...errorsDefaults });
        setSubmitSuccess(true);

        fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/v1${newsletterGameEndpoint}`, {
          method: 'POST',
          headers: requestTemplates.headers,
          body: JSON.stringify({
            name: inputs.name,
            email: inputs.email,
            phone: inputs.telephone,
            prize_game_form: 'kap_dumaszinhaz_belepojegy',
          }),
        });
      } else
      {
        setErrors(validation);
        console.log(validation)
      }
    });
  };

  function handleInput(key, value)
  {
    // Resetting field error
    if (errors[key]) setErrors({ ...errors, [key]: errorsDefaults[key] });

    // Setting new inputs field value
    setInputs({ ...inputs, [key]: value });
  }

  return (<HirlevelJatekPageWrapper>
    <DynamicHead image={NewsletterGameImage} metadata={metadata} />
    <Header></Header>
    <Content>
      <HirlevelJatekContainer>
        <NewsletterGame>
          <OptimizedImage src={NewsletterGameImage} width="300" height="600" alt="" />
        </NewsletterGame>
        <HirlevelJatekHeaderContainer>
          {/*<HirlevelJatekHeader>Iratkozz fel és nyerj két jegyet Kovács András Péter előadására!</HirlevelJatekHeader>*/}
          <p>
            Kedves Érdeklődő!
            Jelenleg nincs aktuális nyereményjátékunk, ha lesz jelentkezünk a felhívással.
          </p>
          {submitSuccess && (<SuccessMessageHeader>
            <span>Sikeres jelentkezés!</span>
            {/*<XmasTreeIcon />*/}
          </SuccessMessageHeader>)}
        </HirlevelJatekHeaderContainer>
      </HirlevelJatekContainer>

      {!submitSuccess && (<HirlevelJatekFormWrapper>
        <HirlevelJatekInputs>
          <InputTextWrapper>
            <InputText
              type="text"
              value={inputs.name}
              error={errors.name}
              label="Név*"
              name="name"
              onChange={(e) => handleInput('name', e.target.value)}
            />
          </InputTextWrapper>
          <InputEmailWrapper>
            <InputText
              type="email"
              value={inputs.email}
              error={errors.email}
              label="Email*"
              name="email"
              onChange={(e) => handleInput('email', e.target.value)}
            />
          </InputEmailWrapper>
          <InputTextWrapper>
            <InputText
              type="text"
              value={inputs.telephone}
              error={errors.telephone}
              label="Telefonszám*"
              name="telephone"
              onChange={(e) => handleInput('telephone', e.target.value)}
            />
          </InputTextWrapper>
        </HirlevelJatekInputs>
        <InputCheckboxWrapper>
          <InputRadioBlock
            style={{
              fontSize: '20px', background: 'none', border: 'none', boxShadow: 'none', padding: '0',
            }}
            type="text"
            value={inputs.checkbox}
            error={errors.checkbox}
            name="checkbox"
            checked={conditionsAgreed}
            onChange={() => handleInput('checkbox', setConditionsAgreed(true))}
            label="Feliratkozom az alomgyar.hu hírlevelére (bármikor le tudsz iratkozni)"
          />
        </InputCheckboxWrapper>

        <HirlevelJatekButtonWrapper>
          <Button onClick={handleSubmitButtonClick} buttonHeight="50px">
            Küldés
          </Button>
        </HirlevelJatekButtonWrapper>
      </HirlevelJatekFormWrapper>)}
    </Content>
  </HirlevelJatekPageWrapper>);
};

HirlevelJatekPage.getInitialProps = async () =>
{
  const metadata = await getMetadata('/hirlevel-jatek')
  return { metadata: metadata.length > 0 ? metadata[0].data : null }
}

export default HirlevelJatekPage;
