import Image from 'next/image';
import Link from 'next/link';
import dynamic from 'next/dynamic';

const Icon = dynamic(() => import('@components/icon/icon'));
const SiteColContainer = dynamic(() => import('@components/siteColContainer/siteColContainer'));

import {
  Bottom,
  BottomCenter,
  BottomText,
  CardList,
  Col,
  ColContent,
  ColTitle,
  ContactLine,
  ContactLinkButton,
  ContectIcon,
  ContectText,
  FooterComponent,
  SocialList,
  TextLine,
  Top,
  TopCenter,
} from './footer.styled.js';

let ImageLogoAlomgyar = dynamic(() => import('@assets/images/logos/alomgyar-white.svg'));
let ImageLogoOlcsokonyvek = dynamic(() => import('@assets/images/logos/olcsokonyvek-white.svg'));
let ImageLogoNagyker = dynamic(() => import('@assets/images/logos/nagyker-white.svg'));

import ImageVisa from '@assets/images/logos/visa-color.png';
import ImageMastercard from '@assets/images/logos/mastercard-color.png';
import settingsVars from "@vars/settingsVars";
import url from "libs/url";

export default function Footer() {
  let settings = settingsVars.get(url.getHost());

  return (
    <FooterComponent>
      <SiteColContainer>
        <Top>
          <TopCenter>
            <Col>
              <Link href="/" passHref>

                {settings.key === 'ALOMGYAR' && <ImageLogoAlomgyar/>}
                {settings.key === 'OLCSOKONYVEK' && <ImageLogoOlcsokonyvek/>}
                {settings.key === 'NAGYKER' && <ImageLogoNagyker/>}

              </Link>
            </Col>
            <Col>
              <ColTitle>Kapcsolat</ColTitle>
              <ColContent>
                <ContactLine>
                  <ContectIcon>
                    <Icon type="phone" iconWidth="30px" iconColor="white"></Icon>
                  </ContectIcon>
                  <ContectText>
                    <a href={`tel:${settings.key === 'ALOMGYAR' && '+36-1-770-8701'}`}>
                      {settings.key === 'ALOMGYAR' && '+36-1-770-8701'}
                    </a>
                    <a href={`tel:${settings.key === 'OLCSOKONYVEK' && '+36-1-770-8702'}`}>
                      {settings.key === 'OLCSOKONYVEK' && '+36-1-770-8702'}
                    </a>
                    <a href={`tel:${settings.key === 'NAGYKER' && '+36-1-614-3476'}`}>
                      {settings.key === 'NAGYKER' && '+36-1-614-3476'}
                    </a>
                    <br/>
                    <sub>
                      (munkanapokon
                      <br/>
                      9:30 és 16:30 között)
                    </sub>
                  </ContectText>
                </ContactLine>
                <ContactLine>
                  <ContectIcon>
                    <Icon type="email" iconWidth="30px" iconColor="white"></Icon>
                  </ContectIcon>
                  <ContectText>
                    {settings.key === 'ALOMGYAR' &&
                      <a href="mailto:info@alomgyar.hu">info@alomgyar.hu</a>}
                    {settings.key === 'OLCSOKONYVEK' &&
                      <a href="mailto:info@olcsokonyvek.hu">info@olcsokonyvek.hu</a>}
                    {settings.key === 'NAGYKER' &&
                      <a href="mailto:webshop@alomgyar.hu">webshop@alomgyar.hu</a>}
                  </ContectText>
                </ContactLine>
                <ContactLine>
                  <Link href="/kapcsolat" passHref>
                    <ContactLinkButton>Üzenetet küldök</ContactLinkButton>
                  </Link>
                </ContactLine>
              </ColContent>
            </Col>
            <Col>
              <ColTitle>Vásárlás</ColTitle>
              <ColContent>
                <TextLine>
                  <Link href="/oldal/vasarlas-menete" passHref>
                    Vásárlás menete
                  </Link>
                </TextLine>
                <TextLine>
                  <Link href="/oldal/szallitas-belfoldre" passHref>
                    Szállítás belföldre
                  </Link>
                </TextLine>
                <TextLine>
                  <Link href="/oldal/szallitas-kulfoldre" passHref>
                    Szállítás külföldre
                  </Link>
                </TextLine>
                <TextLine>
                  <Link href="/csomagom" passHref>
                    Csomagkövetés
                  </Link>
                </TextLine>
                <TextLine>
                  <Link href="/profil/szemelyes-adataim" passHref>
                    Fiókom
                  </Link>
                </TextLine>
              </ColContent>
            </Col>
            <Col>
              <ColTitle>Információ</ColTitle>
              <ColContent>
                <TextLine>
                  <Link href="/oldal/adatvedelem" passHref>
                    Adatvédelem
                  </Link>
                </TextLine>
                <TextLine>
                  <Link href="/oldal/aszf" passHref>
                    ÁSZF
                  </Link>
                </TextLine>
                <TextLine>
                  <Link href="/oldal/impresszum" passHref>
                    Impresszum
                  </Link>
                </TextLine>
                <TextLine>
                  <Link href="/oldal/konyvkiadas" passHref>
                    Könyvkiadás
                  </Link>
                </TextLine>
                {settings.key !== 'NAGYKER' &&
                    <TextLine>
                      <a href="/sitemap.xml">
                        Oldaltérkép
                      </a>
                    </TextLine>}
              </ColContent>
            </Col>
          </TopCenter>
        </Top>
        <Bottom>
          <BottomCenter>
            <Col>
              <BottomText>
                2021{' '}
                {settings.key === 'ALOMGYAR' && (
                  <Link href="/" passHref>
                    www.alomgyar.hu
                  </Link>
                )}
                {settings.key === 'OLCSOKONYVEK' && (
                  <Link href="/" passHref>
                    www.olcsokonyvek.hu
                  </Link>
                )}
                {settings.key === 'NAGYKER' && (
                  <Link href="/" passHref>
                    www.nagyker.alomgyar.hu
                  </Link>
                )}{' '}
                © Minden jog fenntartva Publish and More Kft.{' '}
              </BottomText>
            </Col>
            <Col>
              <CardList>
                <Image loading="lazy" {...ImageVisa} layout="fixed" alt="Visa"></Image>
                <Image loading="lazy" {...ImageMastercard} layout="fixed" alt="Mastercard"></Image>
              </CardList>
            </Col>
            <Col>
              <SocialList>
                <a href="https://www.facebook.com/alomgyar" target="_blank" rel="noreferrer noopener">
                  <Icon type="social-facebook" iconWidth="40px" iconHeight="40px" iconColor="white"></Icon>
                </a>
                <a href="https://www.linkedin.com/company/publish-and-more" target="_blank" rel="noreferrer noopener">
                  <Icon type="social-linkedin" iconWidth="40px" iconHeight="40px" iconColor="white"></Icon>
                </a>
                <a href="https://www.instagram.com/alomgyarkiado" target="_blank" rel="noreferrer noopener">
                  <Icon type="social-instagram" iconWidth="40px" iconHeight="40px" iconColor="white"></Icon>
                </a>
                <a href="https://www.twitter.com/alomgyar" target="_blank" rel="noreferrer noopener">
                  <Icon type="social-twitter" iconWidth="40px" iconHeight="40px" iconColor="white"></Icon>
                </a>
              </SocialList>
            </Col>
          </BottomCenter>
        </Bottom>
      </SiteColContainer>
    </FooterComponent>
  );
}
