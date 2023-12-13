import Currency from '@libs/currency';
import MainHeroIcon from '@components/mainHeroIcon/mainHeroIcon';
import { MainHeroIconsWrapper, MainHeroIconWrapper, Separator } from './mainHeroIcons.styled';

import iconDelivery from '@assets/images/icons/delivery.svg';
import iconHand from '@assets/images/icons/hand.svg';
import iconBookstack from '@assets/images/icons/bookstack.svg';
import iconSale from '@assets/images/icons/sale.svg';
import settingsVars from "@vars/settingsVars";
import url from "libs/url";

export default function MainHeroIcons({ data }) {
    let settings = settingsVars.get(url.getHost());

    return (
    <MainHeroIconsWrapper>
      {settings.key !== 'NAGYKER' && (
        <>
          <MainHeroIconWrapper justify="flex-start">
            <MainHeroIcon image={iconDelivery} imageWidth="60%">
              <span>Ingyenes</span>
              <br /> szállítás <nobr>{Currency.format(data?.free_shopping_limit)}-tól</nobr>
            </MainHeroIcon>
          </MainHeroIconWrapper>
          <Separator className="d-none d-xl-flex"></Separator>
        </>
      )}
      <MainHeroIconWrapper justify="center">
        <MainHeroIcon image={iconHand} imageWidth="51.25%">
          <span>Több ezer</span>
          <br /> átvevőpont
        </MainHeroIcon>
      </MainHeroIconWrapper>
      <Separator className="d-none d-xl-flex"></Separator>
      <MainHeroIconWrapper justify="center">
        <MainHeroIcon image={iconBookstack} imageWidth="47.5%">
          <span>Naponta</span>
          <br /> bővülő kínálat
        </MainHeroIcon>
      </MainHeroIconWrapper>
      {settings.key !== 'NAGYKER' && (
        <>
          <Separator className="d-none d-xl-flex"></Separator>
          <MainHeroIconWrapper justify="flex-end" className="d-none d-md-flex">
            <MainHeroIcon image={iconSale} imageWidth="45%">
              {settings.key === 'ALOMGYAR' && (
                <>
                  <span>{data?.discount_rate}% kedvezmény</span> az <br /> előjegyezhető könyvekre
                </>
              )}
              {settings.key === 'OLCSOKONYVEK' && (
                <>
                  <span>Legalább {data?.discount_rate}% kedvezmény</span>
                  <br />
                  minden könyvre
                </>
              )}
            </MainHeroIcon>
          </MainHeroIconWrapper>
        </>
      )}
    </MainHeroIconsWrapper>
  );
}
