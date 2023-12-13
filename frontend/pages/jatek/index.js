import dynamic from 'next/dynamic';
import { useState, useEffect } from 'react';
import { useQuery } from 'react-query';
import { handleApiRequest } from '@libs/api';
// import { handleApiRequest, getResponseById } from '@libs/api';
import useRequest from '@hooks/useRequest/useRequest';

const Button = dynamic(() => import('@components/button/button'));
const Content = dynamic(() => import('@components/content/content'));
const Header = dynamic(() => import('@components/header/header'));
const InputRadioBlock = dynamic(() => import('@components/inputRadioBlock/inputRadioBlock'));
const InputText = dynamic(() => import('@components/inputText/inputText'));

const JatekContainer = dynamic(() => import('@components/pages/jatekPage.styled').then((module) => module.JatekContainer));

const JatekPageWrapper = dynamic(() => import('@components/pages/jatekPage.styled').then((module) => module.JatekPageWrapper));

const JatekHeaderContainer = dynamic(() => import('@components/pages/jatekPage.styled').then((module) => module.JatekHeaderContainer));

const JatekHeader = dynamic(() => import('@components/pages/jatekPage.styled').then((module) => module.JatekHeader));

const XmasPrizesImage = dynamic(() => import('@components/pages/jatekPage.styled').then((module) => module.XmasPrizesImage));

const JatekInputs = dynamic(() => import('@components/pages/jatekPage.styled').then((module) => module.JatekInputs));

const JatekFormWrapper = dynamic(() => import('@components/pages/jatekPage.styled').then((module) => module.JatekFormWrapper));

const JatekButtonWrapper = dynamic(() => import('@components/pages/jatekPage.styled').then((module) => module.JatekButtonWrapper));

const SuccessMessageHeader = dynamic(() => import('@components/pages/jatekPage.styled').then((module) => module.SuccessMessageHeader));

import XmaxPrize from '@assets/images/samples/xmas_prize.jpg';
const InputTextWrapper = dynamic(() => import('@components/mainNewsletterSignup/mainNewsletterSignup.styled').then((mod) => mod.InputTextWrapper));
import { InputEmailWrapper } from '@components/sideModalLogin/sideModalLogin.styled';

const Dropdown = dynamic(() => import('@components/dropdown/dropdown'));

// import XmasTreeIcon from '@assets/images/icons/xmas-tree.svg';
const InputCheckboxWrapper = dynamic(() => import('@components/inputCheckbox/inputCheckbox.styled').then((module) => module.InputCheckboxWrapper));
const UserDropdownWrapper = dynamic(() => import('@components/pages/szallitasiAdatokPage.styled').then((module) => module.UserDropdownWrapper));

// import Link from 'next/link';
import OptimizedImage from '@components/Images/OptimizedImage';
import { useRouter } from 'next/router';

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    'storelist-get': {
      method: 'GET',
      path: '/alomgyar/store-list',
      ref: 'list',
      request_id: 'storelist-get',
    },
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

const JatekPage = () => {
  let [inputs, setInputs] = useState({ ...inputsDefaults });
  let [errors, setErrors] = useState({ ...errorsDefaults });
  let [submitSuccess, setSubmitSuccess] = useState(false);
  let [submitFailed, setSubmitFailed] = useState(false);
  const [conditionsAgreed, setConditionsAgreed] = useState(false);

  const router = useRouter();

  useEffect(() => {
    if (new Date() >= lastAvailableBefore) {
      router.replace('/hirlevel-jatek');
    }
  }, []);

  const prizeEndpoint = '/alomgyar/prize-gaming-form/store';

  let queryShopListGet = useQuery('storelist-get', () => handleApiRequest(requestShoplistGet.build()), {
    enabled: false,
    refetchOnWindowFocus: false,
    refetchOnMount: false,
    staleTime: 0,
  });

  let requestShoplistGet = useRequest(requestTemplates, queryShopListGet);
  requestShoplistGet.addRequest('storelist-get');

  useEffect(() => {
    requestShoplistGet.commit();
  }, []);

  let shoplistDropdownData = [
    { label: 'ÁLOMGYÁR WEBSHOP' },
    { label: 'BÉKÉSCSABAI KÖNYVESBOLT' },
    { label: 'BUDAPEST - BLAHA KÖNYVESBOLT' },
    { label: 'DEBRECENI KÖNYVESBOLT' },
    { label: 'HAJDÚNÁNÁSI KÖNYVESBOLT' },
    { label: 'KAZINCBARCIKAI KÖNYVESBOLT' },
    { label: 'MISKOLCI KÖNYVESBOLT' },
    { label: 'MOSONMAGYARÓVÁRI KÖNYVESBOLT' },
    { label: 'NAGYKANIZSAI KÖNYVESBOLT' },
    { label: 'NYÍREGYHÁZI KÖNYVESBOLT' },
    { label: 'SOPRONI KÖNYVESBOLT' },
    { label: 'SZEGEDI KÖNYVESBOLT' },
    { label: 'SZÉKESFEHÉRVÁRI KÖNYVESBOLT' },
    { label: 'SZEKSZÁRDI KÖNYVESBOLT' },
    { label: 'TATAI KÖNYVESBOLT' },
    { label: 'VÁCI KÖNYVESBOLT' },
  ];
  // let shoplistResponse, shoplistResponseDropdownData;
  // let shoplistResponse;
  // shoplistResponse = getResponseById(queryShopListGet.data, 'storelist-get');

  // shoplistResponseDropdownData = shoplistResponse?.success
  //   ? shoplistResponse.body.map((bookShop) => ({
  //     label: bookShop,
  //   }))
  //   : undefined;

  const lastAvailableBefore = new Date('12/13/2023'); // m/d/y
  const handleInputValidation = () => {
    return import('joi').then((module) => {
      let joi = module.default;

      let schema = joi.object({
        name: joi.string().required(),
        email: joi.string().required().email({ tlds: false }),
        telephone: joi.string().required(),
        // address: joi.string().required(),
        order_number: joi.string().required(),
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
  };

  const handleSubmitButtonClick = () => {
    handleInputValidation().then((validation) => {
      if (validation === true) {
        setErrors({ ...errorsDefaults });
        // setSubmitSuccess(true);

        //requestPrizeGamingForm.modifyRequest('prize-gaming-form', (requestObject) => {
        //  requestObject.body.name = inputs.name;
        //  requestObject.body.email = inputs.email;
        //	requestObject.body.phone = inputs.telephone;
        //	requestObject.body.order_number = inputs.order_number;
        //	requestObject.body.address = inputs.address
        //});

        //requestPrizeGamingForm.commit()

        //console.log(requestPrizeGamingForm)

        fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/v1${prizeEndpoint}`, {
          method: 'POST',
          headers: requestTemplates.headers,
          body: JSON.stringify({
            name: inputs.name,
            address: inputs.address,
            email: inputs.email,
            phone: inputs.telephone,
            order_number: inputs.order_number,
          }),
        }).then((response) => {
          if (response.status == 201) {
            setSubmitSuccess(true);
            setSubmitFailed(false);
          } else {
            setSubmitSuccess(false);
            setSubmitFailed(true);
          }
        });
      } else {
        setErrors(validation);
      }
    });
  };

  function handleInput(key, value) {
    // Resetting field error
    if (errors[key]) setErrors({ ...errors, [key]: errorsDefaults[key] });

    // Setting new inputs field value
    setInputs({ ...inputs, [key]: value });
  }

  const styles = {
    link: { color: '#dca10d', textDecoration: 'underline !important' },
  };

  return (
    <JatekPageWrapper style={{ marginBottom: '70px' }}>
      <Header></Header>
      <Content>
        <JatekContainer style={{ maxWidth: '880px !important' }}>
          <XmasPrizesImage>
            <OptimizedImage src={XmaxPrize.src} width="300" height="600" alt="alomgyar karacsonyi nyeremenyjatek" />
          </XmasPrizesImage>
          <JatekHeaderContainer>
            <JatekHeader>Legyen egy álom-tablet már a karácsonyfa alatt.</JatekHeader>
            <p>
              <b>Vásárolj 7000 Ft felett</b> a 15 álomgyár könyvesbolt egyikében (
              <a href="https://www.alomgyar.hu/konyvesboltok" style={styles.link} target="_blank" rel="noreferrer">
                www.alomgyar.hu/konyvesboltok
              </a>
              ) vagy a{' '}
              <a href="https://www.alomgyar.hu" style={styles.link} target="_blank" rel="noreferrer">
                www.alomgyar.hu
              </a>{' '}
              webshopban és megnyerheted a 3 db <b style={{ fontSize: '1.1em' }}>Samsung Galaxy Tablet (A7 Lite T220</b> -{' '}
              <b>ajánlott kiskereskedelmi ár: 64.990 Ft) egyikét</b>.
              <br />
              <br />
              <b>Amennyiben 7000 Ft felett vásároltál</b> valamelyik álomgyár könyvesboltban vagy a{' '}
              <a href="https://www.alomgyar.hu" style={styles.link} target="_blank" rel="noreferrer">
                www.alomgyar.hu
              </a>{' '}
              webshopban, akkor töltsd ki az ezen az oldalon található űrlapot (minden mezőt) és add meg online vásárlás esetén a rendelésszámot,
              bolti vásárlás esetén a kapott nyugta vagy blokk számát <i>(fontos, hogy az ellenőrzéshez ezt őrizd meg)</i>. Ha többször vásárolsz,
              nagyobb az esélyed, töltsd ki minden 7000 Ft-os vásárlás után az űrlapot!
              <br />
              <br />
              <b>A nyereményjáték időtartama:</b> 2023. november 20 - 2023. december 12. (éjfél)
              <br />
              <br />
              <b>Sorsolás:</b> 2023. december 13-án.
              <br />
              A nyerteseket e-mailen és telefonon is értesítjük. A nyeremény kiszállítása csak Magyarország területén belül lehetséges.
              <br />
              Kérdéseddel kapcsolatban keress minket a{' '}
              <a href="mailto:jatek@alomgyar.hu" style={styles.link}>
                jatek@alomgyar.hu
              </a>{' '}
              e-mail címén.
            </p>

            {submitSuccess && (
              <SuccessMessageHeader>
                <span>Sikeres jelentkezés!</span>
                {/* <XmasTreeIcon /> */}
              </SuccessMessageHeader>
            )}
            {submitFailed && (
              <SuccessMessageHeader>
                <span>Sikertelen regisztráció!</span>
              </SuccessMessageHeader>
            )}
          </JatekHeaderContainer>
        </JatekContainer>

        {!submitSuccess && (
          <JatekFormWrapper>
            <JatekInputs>
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
              {/* <UserDropdownWrapper>
                <Dropdown
                  placeholder="Válassz egyet"
                  label="Vásárlás helye*"
                  options={shoplistResponseDropdownData}
                  name="address"
                  onSelect={(e) => handleInput('address', e.label)}
                  error={errors.address}
                />
              </UserDropdownWrapper> */}
              <UserDropdownWrapper>
                <Dropdown
                  placeholder="Válassz egyet"
                  label="Vásárlás helye*"
                  options={shoplistDropdownData}
                  name="address"
                  onSelect={(e) => handleInput('address', e.label)}
                  error={errors.address}
                />
              </UserDropdownWrapper>
              <InputTextWrapper>
                <InputText
                  type="text"
                  value={inputs.order_number}
                  error={errors.order_number}
                  label="Bizonylat/nyugta/számla/rendelés száma*"
                  name="order_number"
                  onChange={(e) => handleInput('order_number', e.target.value)}
                />
              </InputTextWrapper>
            </JatekInputs>
            <InputCheckboxWrapper style={{ display: 'flex' }}>
              <InputRadioBlock
                style={{
                  fontSize: '20px',
                  background: 'none',
                  border: 'none',
                  boxShadow: 'none',
                  padding: '0',
                }}
                type="text"
                value={inputs.checkbox}
                error={errors.checkbox}
                name="checkbox"
                checked={conditionsAgreed}
                onChange={() => handleInput('checkbox', setConditionsAgreed(true))}
                // label="Elfogadom a játék feltételeit és feliratkozom az alomgyar.hu hírlevelére (a leiratkozás bármikor lehetséges)."
              />
              <span>
                Elfogadom a játék feltételeit és hozzájárulok, hogy a Publish and More Kft. a nevemet és e-mail címemet hírlevelezési céllal kezelje.
                (a leiratkozás bármikor lehetséges). Elolvastam az{' '}
                <a style={{ fontWeight: '600' }} href="https://alomgyar.hu/oldal/adatvedelem" target="_blank" rel="noreferrer">
                  <b>adatvédelmi tájékoztatót</b>
                </a>{' '}
                elfogadom a benne foglaltakat.
              </span>
            </InputCheckboxWrapper>

            <JatekButtonWrapper>
              <Button onClick={handleSubmitButtonClick} buttonHeight="50px">
                Küldés
              </Button>
            </JatekButtonWrapper>
          </JatekFormWrapper>
        )}
      </Content>
    </JatekPageWrapper>
  );
};

export default JatekPage;
