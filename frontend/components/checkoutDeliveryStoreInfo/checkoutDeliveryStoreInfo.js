import {
  Address,
  AdressData,
  AdressDataLine,
  AdressFooter,
  AdressFooterLine,
  CheckoutDeliveryStoreInfoComponent,
  Heading,
  Hours,
  HoursData,
  HoursDataLeft,
  HoursDataLine,
  HoursDataRight,
} from '@components/checkoutDeliveryStoreInfo/checkoutDeliveryStoreInfo.styled';

export default function CheckoutDeliveryStoreInfo({ store }) {
  if (!store) return null;

  return (
    <CheckoutDeliveryStoreInfoComponent>
      <Address>
        <Heading>Könyvesbolt címe:</Heading>
        <AdressData>
          <AdressDataLine>{store.address}</AdressDataLine>
          <AdressDataLine>
            {store.city}, {store.zip_code}
          </AdressDataLine>
        </AdressData>
        <AdressFooter>
          <AdressFooterLine>{store.phone}</AdressFooterLine>
          <AdressFooterLine>{store.email}</AdressFooterLine>
        </AdressFooter>
      </Address>
      <Hours>
        <Heading>Könyvesbolt címe:</Heading>
        <HoursData>
          {store.opening_hours.map((opening, openingIndex) => (
            <HoursDataLine key={openingIndex}>
              <HoursDataLeft>
                <HoursDataLine>{opening.days}</HoursDataLine>
              </HoursDataLeft>
              <HoursDataRight>
                <HoursDataLine>{opening.hours}</HoursDataLine>
              </HoursDataRight>
            </HoursDataLine>
          ))}
        </HoursData>
      </Hours>
    </CheckoutDeliveryStoreInfoComponent>
  );
}
