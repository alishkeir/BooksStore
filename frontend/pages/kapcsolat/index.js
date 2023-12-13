import dynamic from 'next/dynamic';
import { useState, useRef, useCallback } from 'react';
import { useMutation } from 'react-query';
import useRequest from '@hooks/useRequest/useRequest';
import { handleApiRequest, getMetadata } from '@libs/api';
import url from '@libs/url';
const Header = dynamic(() => import('@components/header/header'));
const Content = dynamic(() => import('@components/content/content'));
const SiteColContainer = dynamic(() => import('@components/siteColContainer/siteColContainer'));
const Footer = dynamic(() => import('@components/footer/footer'));
const HeaderPromo = dynamic(() => import('@components/headerPromo/headerPromo'));
const PageTitle = dynamic(() => import('@components/pageTitle/pageTitle'));
const Button = dynamic(() => import('@components/button/button'));
const InputText = dynamic(() => import('@components/inputText/inputText'));
const InputTextarea = dynamic(() => import('@components/inputTextarea/inputTextarea'));
const Dropdown = dynamic(() => import('@components/dropdown/dropdown'));
const ListHeader = dynamic(() => import('@components/listHeader/listHeader'));
const InputCheckbox = dynamic(() => import('@components/inputCheckbox/inputCheckbox'));
import useMediaQuery from '@hooks/useMediaQuery/useMediaQuery';
import breakpoints from '@vars/breakpoints';
import DynamicHead from '@components/heads/DynamicHead';
import settingsVars from "@vars/settingsVars";
const NewsletterModal = dynamic(() => import('@components/newsletterModal/newsletterModal'));

const AddressWrapper = dynamic(() =>
  import('@components/pages/kapcsolatPage.styled').then((module) => module.AddressWrapper)
);

const CompanyAddress = dynamic(() =>
  import('@components/pages/kapcsolatPage.styled').then((module) => module.CompanyAddress)
);

const CompanyData = dynamic(() =>
  import('@components/pages/kapcsolatPage.styled').then((module) => module.CompanyData)
);

const ContactsWrapper = dynamic(() =>
  import('@components/pages/kapcsolatPage.styled').then((module) => module.ContactsWrapper)
);

const InputWrapper = dynamic(() =>
  import('@components/pages/kapcsolatPage.styled').then((module) => module.InputWrapper)
);

const KapcsolatPageComponent = dynamic(() =>
  import('@components/pages/kapcsolatPage.styled').then((module) => module.KapcsolatPageComponent)
);

const LinkIcon = dynamic(() =>
  import('@components/pages/kapcsolatPage.styled').then((module) => module.LinkIcon)
);

const LinkIconWrapper = dynamic(() =>
  import('@components/pages/kapcsolatPage.styled').then((module) => module.LinkIconWrapper)
);

const PageContent = dynamic(() =>
  import('@components/pages/kapcsolatPage.styled').then((module) => module.PageContent)
);

const PageContentWrapper = dynamic(() =>
  import('@components/pages/kapcsolatPage.styled').then((module) => module.PageContentWrapper)
);

const PhoneIconWrapper = dynamic(() =>
  import('@components/pages/kapcsolatPage.styled').then((module) => module.PhoneIconWrapper)
);

const PhoneNumber = dynamic(() =>
  import('@components/pages/kapcsolatPage.styled').then((module) => module.PhoneNumber)
);

const PhoneWrapper = dynamic(() =>
  import('@components/pages/kapcsolatPage.styled').then((module) => module.PhoneWrapper)
);

const REQUEST_ID = 'contact-post';

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    'contact-post': {
      method: 'POST',
      path: '/contact',
      ref: 'send',
      request_id: REQUEST_ID,
      body: {
        subject: null,
        name: null,
        email: null,
        message: null,
        privacy: null,
        captcha: null,
      },
    },
  },
};

export default function KapcsolatPage({metadata}) {
  let inputsDefaults = {
    name: '',
    email: '',
    message: '',
    checkbox: false,
    selection: { label: 'Rendelés' },
  };

  let errorsDefaults = {
    email: '',
    name: '',
    message: '',
    checkbox: '',
  };
  let [inputs, setInputs] = useState(inputsDefaults);
  let [errors, setErrors] = useState(errorsDefaults);
  let [modalOpen, setModalOpen] = useState(false);
  let [modalText, setModalText] = useState({
    title: '',
    body: '',
  });

  let dropdownOptionsRef = useRef([
    { label: 'Rendelés', selected: true },
    { label: 'Számlázás', selected: false },
  ]);

  let isMinSm = useMediaQuery(`(min-width: ${breakpoints.min.sm})`);

  let querySendContactForm = useMutation(REQUEST_ID, (requestBuild) => handleApiRequest(requestBuild), {
    onSuccess: (data) => {
      if (data.success) {
        setModalOpen(true);
        setInputs(inputsDefaults);
        setModalText({ title: 'Köszönjük a levelét!', body: 'Üzenetet sikeresen elküldte, melyre hamarosan válaszolunk. Köszönjük!' });
      } else {
        let errors = data.response[0].body.errors[0];
        let messages = [];

        Object.entries(errors).forEach((err) => {
          messages.push(err[1][0]);
        });
        setModalOpen(true);
        setModalText({ title: '', body: messages.join(' ') });
      }
    },
  });

  let requestSendContactForm = useRequest(requestTemplates, querySendContactForm);

  requestSendContactForm.addRequest(REQUEST_ID);

  let handleSendContactForm = useCallback(async (props) => {
    let { checkbox, email, message, name, selection } = props;

    getRecaptchaToken()
      .then((token) => {
        return requestSendContactForm.modifyRequest(REQUEST_ID, (currentRequest) => {
          currentRequest.body.email = email;
          currentRequest.body.message = message;
          currentRequest.body.name = name;
          currentRequest.body.privacy = checkbox;
          currentRequest.body.subject = selection.label;
          currentRequest.body.captcha = token;
        });
      })
      .then(() => {
        requestSendContactForm.commit();
      });
  }, []);

  let getRecaptchaToken = useCallback(async () => {
    return new Promise((resolve, reject) => {
      window.grecaptcha.ready(function () {
        window.grecaptcha
          .execute(process.env.NEXT_PUBLIC_RECAPTCHA_SITE_KEY)
          .then(function (token) {
            resolve(token);
          })
          .catch(reject);
      });
    });
  }, []);

  let settings = settingsVars.get(url.getHost());

  return (
    <KapcsolatPageComponent>
      <DynamicHead metadata={metadata}></DynamicHead>
      <DynamicHead>
        <script async defer src={`https://www.google.com/recaptcha/api.js?render=${process.env.NEXT_PUBLIC_RECAPTCHA_SITE_KEY}`}></script>
      </DynamicHead>
      <Header promo={HeaderPromo}></Header>
      {modalOpen && (
        <NewsletterModal
          title={modalText.title}
          text={modalText.body}
          setModal={() => {
            setModalOpen(false);
          }}
        ></NewsletterModal>
      )}
      <Content>
        <SiteColContainer>
          <PageTitle>Kapcsolat</PageTitle>
          <PageContent>
            <PageContentWrapper>
              <InputWrapper marginBottom={23} marginBottomMobile={11}>
                <ListHeader title="Üzenet"></ListHeader>
              </InputWrapper>
              <InputWrapper marginBottom={15}>
                <Dropdown
                  width="100%"
                  height={'50px'}
                  label="Az üzenet tárgya"
                  options={dropdownOptionsRef.current}
                  onSelect={(a, b, c) => handleInput('selection', a, b, c)}
                ></Dropdown>
              </InputWrapper>
              <InputWrapper marginBottom={15}>
                <InputText
                  name="input-contact-name"
                  label="Az ön neve"
                  value={inputs.name}
                  onChange={(e) => handleInput('name', e.target.value)}
                  onReset={() => handleInput('name', '')}
                  error={errors.name}
                  height={50}
                ></InputText>
              </InputWrapper>
              <InputWrapper marginBottom={25}>
                <InputText
                  name="input-contact-email"
                  label="Email cím"
                  value={inputs.email}
                  onChange={(e) => handleInput('email', e.target.value)}
                  onReset={() => handleInput('email', '')}
                  error={errors.email}
                  height={50}
                ></InputText>
              </InputWrapper>
              <InputWrapper marginBottom={22}>
                <InputTextarea
                  name="input-contact-message"
                  height={150}
                  label="Üzenet"
                  value={inputs.message}
                  error={errors.message}
                  onChange={(e) => handleInput('message', e.target.value)}
                ></InputTextarea>
              </InputWrapper>
              <InputWrapper marginBottom={42} marginBottomMobile={34}>
                <InputCheckbox
                  label='Elolvastam az <a href="/oldal/adatvedelem" target="_blank" rel="noreferrer noopener">Adatvédelmi tájékoztatót</a> és elfogadom a benne foglaltakat.'
                  error={errors.checkbox}
                  checked={inputs.checkbox}
                  onChange={(e) => handleInput('checkbox', e.target.checked)}
                ></InputCheckbox>
              </InputWrapper>
              <InputWrapper marginBottom={112} marginBottomMobile={34}>
                <Button buttonWidth="100%" buttonHeight="50px" onClick={(e) => handleSubmit(e)}>
                  Küldés
                </Button>
              </InputWrapper>
              <ContactsWrapper>
                <PhoneWrapper>
                  <ListHeader title="Telefon"></ListHeader>
                  {isMinSm ? (
                    <LinkIconWrapper>
                      <PhoneIconWrapper>
                        <LinkIcon type="phone" iconWidth="18px" iconColor="black"></LinkIcon>
                      </PhoneIconWrapper>
                      <PhoneNumber>
                        {settings.key === 'ALOMGYAR' && '36-1-770-8701'}
                        {settings.key === 'OLCSOKONYVEK' && '36-1-770-8702'}
                        {settings.key === 'NAGYKER' && '36-1-614-3476'}
                        <br />
                        <sub>
                          (munkanapokon
                          <br />
                          9:30 és 16:30 között)
                        </sub>
                      </PhoneNumber>
                    </LinkIconWrapper>
                  ) : (
                    <InputWrapper marginBottomMobile={40} marginTopMobile={20}>
                      <Button icon="phone" type="secondary" buttonWidth="100%" buttonHeight="50px">
                        {settings.key === 'ALOMGYAR' && '36-1-770-8701'}
                        {settings.key === 'OLCSOKONYVEK' && '36-1-770-8702'}
                        {settings.key === 'NAGYKER' && '06-1-614-3476'}
                      </Button>
                    </InputWrapper>
                  )}
                </PhoneWrapper>
                <CompanyData>
                  <ListHeader title="Cégadatok"></ListHeader>
                  <AddressWrapper>
                    <CompanyAddress>Publish and More Kft.</CompanyAddress>
                    <CompanyAddress>1137 Budapest, Pozsonyi út 10. 1/4.</CompanyAddress>
                    <CompanyAddress>Cégjegyzési szám: 01-09-981023</CompanyAddress>
                  </AddressWrapper>
                </CompanyData>
              </ContactsWrapper>
            </PageContentWrapper>
          </PageContent>
        </SiteColContainer>
      </Content>
      <Footer></Footer>
    </KapcsolatPageComponent>
  );

  function handleInput(key, value) {
    // Resetting field error
    if (errors[key]) setErrors({ ...errors, [key]: errorsDefaults[key] });

    // Setting new inputs field value
    setInputs({ ...inputs, [key]: value });
  }

  function handleSubmit(e) {
    e.preventDefault();
    e.stopPropagation();
    e.nativeEvent.stopImmediatePropagation();

    import('joi').then((module) => {
      let joi = module.default;

      let schema = joi.object({
        name: joi.string().required(),
        email: joi.string().required().email({ tlds: false }),
        message: joi.string().required(),
        checkbox: joi.boolean().invalid(false).required(),
        selection: joi.required(),
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
        });
        setErrors(newErrorState);
      } else {
        handleSendContactForm(inputs);
        setErrors({ ...errorsDefaults });
      }
    });
  }
}

KapcsolatPage.getInitialProps = async () =>
{
  const metadata = await getMetadata('/kapcsolat')
  return { metadata: metadata.length > 0 ? metadata[0].data : null }
}
