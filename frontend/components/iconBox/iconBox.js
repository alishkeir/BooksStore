import MainHeroIconImage from '@components/mainHeroIconImage/mainHeroIconImage';
import { IconBoxWrapper, IconWrapper, MainHeroIconImageWrapper, Text } from './iconBox.styled';
import iconDelivery from '@assets/images/icons/delivery.svg';
import iconHand from '@assets/images/icons/hand.svg';
import iconBookstack from '@assets/images/icons/bookstack.svg';
import iconSale from '@assets/images/icons/sale.svg';
import settingsVars from "@vars/settingsVars";
import url from "@libs/url";

export default function IconBox({ data }) {
  let settings = settingsVars.get(url.getHost());

  return (
    <IconBoxWrapper image={iconDelivery} imageWidth="60%">
      {settings.key !== 'NAGYKER' && (
        <IconWrapper>
          <MainHeroIconImageWrapper>
            <MainHeroIconImage image={iconDelivery} imageWidth="60%" />
          </MainHeroIconImageWrapper>
          <Text>
            <span>Ingyenes</span>
            <br /> szállítás <nobr>{data.free_shopping_limit} Ft-tól</nobr>
          </Text>
        </IconWrapper>
      )}
      <IconWrapper>
        <MainHeroIconImageWrapper>
          <MainHeroIconImage image={iconHand} imageWidth="51.25%" />
        </MainHeroIconImageWrapper>
        <Text>
          <span>Több ezer</span>
          <br /> átvevőpont
        </Text>
      </IconWrapper>
      <IconWrapper className="d-none d-xl-flex">
        <MainHeroIconImageWrapper>
          <MainHeroIconImage image={iconBookstack} imageWidth="47.5%" />
        </MainHeroIconImageWrapper>
        <Text>
          <span>Naponta</span>
          <br /> bővülő kínálat
        </Text>
      </IconWrapper>
      {settings.key !== 'NAGYKER' && (
        <IconWrapper className="d-none d-xl-flex">
          <MainHeroIconImageWrapper>
            <MainHeroIconImage image={iconSale} imageWidth="45%" />
          </MainHeroIconImageWrapper>
          <Text>
            {settings.key === 'ALOMGYAR' && (
              <>
                <span>{data.discount_rate}% kedvezmény</span> az <br /> előjegyezhető könyvekre
              </>
            )}
            {settings.key === 'OLCSOKONYVEK' && (
              <>
                <span>Legalább {data.discount_rate}% kedvezmény</span>
                <br />
                minden könyvre
              </>
            )}
            {settings.key === 'NAGYKER' && (
              <>
                <span>{data.discount_rate}% kedvezmény</span> az <br /> előjegyezhető könyvekre
              </>
            )}
          </Text>
        </IconWrapper>
      )}
    </IconBoxWrapper>
  );
}
