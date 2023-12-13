import dynamic from 'next/dynamic';
import { useCallback } from 'react';
import { useRouter } from 'next/router';
import useInputs from '@hooks/useInputs/useInputs';
const InputText = dynamic(() => import('@components/inputText/inputText'));
const Button = dynamic(() => import('@components/button/button'));
const Header = dynamic(() => import('@components/header/header'));
const Content = dynamic(() => import('@components/content/content'));
const SiteColContainer = dynamic(() => import('@components/siteColContainer/siteColContainer'));
const Footer = dynamic(() => import('@components/footer/footer'));
const HeaderPromo = dynamic(() => import('@components/headerPromo/headerPromo'));
const ImagePinPackage = dynamic(() => import('@assets/images/elements/pin-package.svg'));
import { getMetadata } from '@libs/api';
import {
  ContentWrapper,
  CsomagomPageWrapper,
  ImageWrapper,
  InputActions,
  InputCol,
  InputDescription,
  InputRow,
  InputWrapper,
  Title,
} from '@components/pages/csomagomPage.styled';

import DynamicHead from '@components/heads/DynamicHead';

let inputsDefaults = {
  search: '',
};

let errorsDefaults = {
  search: '',
};

export default function CsomagomPage({metadata}) {
  let { inputs, setInput, errors, setErrors } = useInputs(inputsDefaults, errorsDefaults);

  let router = useRouter();

  let handleFormSubmit = useCallback(
    (e) => {
      e.preventDefault();

      import('joi').then((module) => {
        let joi = module.default;

        let schema = joi.object({
          search: joi.string().required(),
        });

        let validation = schema.validate(inputs, {abortEarly: false});

        if (validation.error) {
          let newErrorState = {...errorsDefaults};

          validation.error.details.forEach((error) => {
            switch (error.type) {
              case 'string.empty':
                newErrorState[error.context.key] = 'Ez a mező nem lehet üres';
                break;

              default:
                newErrorState[error.context.key] = 'Hibás mező';
                break;
            }
          });

          setErrors(newErrorState);
        } else {
          setErrors({...errorsDefaults});

          router.push(`/csomagom/${inputs.search}`);
        }
      });
    },
    [inputs],
  );

  return (
    <CsomagomPageWrapper>
      <DynamicHead metadata={metadata} />
      <Header promo={HeaderPromo}></Header>
      <Content>
        <SiteColContainer>
          <ContentWrapper>
            <Title>Hol a csomagom?</Title>
            <InputRow className="row">
              <InputCol className="col-md-8 offset-md-2">
                <form onSubmit={handleFormSubmit}>
                  <ImageWrapper>
                    <ImagePinPackage></ImagePinPackage>
                  </ImageWrapper>
                  <InputWrapper>
                    <InputText
                      name="input-search-term"
                      value={inputs.search}
                      error={errors.search}
                      onChange={(e) => setInput('search', e.target.value)}
                      onReset={() => setInput('search', '')}
                      button="search"
                      iconColor="green"
                      placeholder="Keresés csomagkód alapján..."
                      height={60}
                      reset
                    ></InputText>
                  </InputWrapper>
                  <InputDescription>Ezen az oldalon megtekintheted rendelésed aktuális állapotát</InputDescription>
                  <InputActions>
                    <Button buttonHeight="50px" buttonWidth="150px">
                      Keresés
                    </Button>
                  </InputActions>
                </form>
              </InputCol>
            </InputRow>
          </ContentWrapper>
        </SiteColContainer>
      </Content>
      <Footer></Footer>
    </CsomagomPageWrapper>
  );
}

CsomagomPage.getInitialProps = async () => {
  const metadata = await getMetadata('/csomagom')
  return {metadata: metadata.length > 0 ? metadata[0].data : null}
}
