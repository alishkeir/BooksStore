import { useEffect, useState } from 'react';
import Icon from '@components/icon/icon';
import colors from '@vars/colors';

import {
  BookShopCardComponent,
  BookShopImageWrapper,
  Contact,
  ContactIcon,
  ContactWrapper,
  Days,
  DetailsContainer,
  Facebook,
  Hours,
  IconWrapper,
  InputIcon,
  ListHeaderWrapper,
  OpeningTimes,
  OpeningTimesContainer,
  OpeningTimesIcon,
  OpeningTimesTitle,
  OpeningTimesWrapper,
  Title,
} from '@components/bookShopCard/bookShopCard.styled';
import OptimizedImage from '@components/Images/OptimizedImage';

export default function BookShopCard(props) {
  let { shop, isMinLG, selectedShop, setBookShop } = props;
  let [openBookSection, setOpenBookSection] = useState(false);

  useEffect(() => {
    if (isMinLG) {
      setOpenBookSection(true);
    } else {
      shop.id === selectedShop.id ? setOpenBookSection(true) : setOpenBookSection(false);
    }
  }, [isMinLG, selectedShop]);

  return (
    <BookShopCardComponent isMinLG={isMinLG}>
      <ListHeaderWrapper onClick={() => handleOpenSection(!openBookSection)} open={openBookSection} isMinLG={isMinLG}>
        <Title isMinLG={isMinLG}>{shop.title}</Title>

        <InputIcon isVisible={isMinLG}>
          <IconWrapper>
            <Icon open={openBookSection} type="chevron-right" iconWidth="10px" iconColor={colors.monza}></Icon>
          </IconWrapper>
        </InputIcon>
      </ListHeaderWrapper>
      <DetailsContainer open={openBookSection}>
        {shop.cover && (
          <BookShopImageWrapper>
            <OptimizedImage height={320} width={400} src={shop.cover} layout="responsive" alt=""></OptimizedImage>
          </BookShopImageWrapper>
        )}
        <ContactWrapper>
          <ContactIcon type="location" strokeColor="black"></ContactIcon>
          <Contact>{getAddress(shop)}</Contact>
        </ContactWrapper>
        <ContactWrapper>
          <ContactIcon type="phone" iconColor="black"></ContactIcon>
          <Contact>{shop.phone}</Contact>
        </ContactWrapper>
        <ContactWrapper>
          <ContactIcon type="email" iconColor="black"></ContactIcon>
          <Contact>{shop.email}</Contact>
        </ContactWrapper>
        <ContactWrapper>
          <ContactIcon type="social-facebook-fat" iconColor="black"></ContactIcon>
          <Contact>
            <Facebook href={shop.facebook} target="_blank" rel="noreferrer">
              facebook
            </Facebook>
          </Contact>
        </ContactWrapper>
        <OpeningTimesContainer>
          <OpeningTimesIcon>
            <ContactIcon type="time" iconColor="black"></ContactIcon>
          </OpeningTimesIcon>
          <OpeningTimes>
            <OpeningTimesTitle>Nyitvatartás</OpeningTimesTitle>
            {shop['opening_hours'].map((openingTime, index) => (
              <OpeningTimesWrapper key={index}>
                <Days>{openingTime.days}</Days>
                <Hours>{openingTime.hours}</Hours>
              </OpeningTimesWrapper>
            ))}
          </OpeningTimes>
        </OpeningTimesContainer>
      </DetailsContainer>
    </BookShopCardComponent>
  );

  function handleOpenSection() {
    if (isMinLG || openBookSection) return;
    setBookShop(shop);
    setOpenBookSection(!openBookSection);
  }

  function getAddress(shop) {
    return `${shop['zip_code']} ${shop.city}, ${shop.address}`;
  }
}
