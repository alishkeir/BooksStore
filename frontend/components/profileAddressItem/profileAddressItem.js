import Icon from '@components/icon/icon';
import colors from '@vars/colors';
import currency from '@libs/currency'
import {
  Actions,
  Address,
  BusinessName,
  CityZip,
  Country,
  IconWrapper,
  Icons,
  Name,
  Note,
  ProfileAddressItemComponent,
  Street,
  TaxNumber,
  Title,
} from '@components/profileAddressItem/profileAddressItem.styled';

export default function ProfileAddressItem({ address = {}, onEdit = () => {}, onDelete = () => {} }) {
  return (
    <ProfileAddressItemComponent>
      <Address>
        {address.entity_type === 'private' && (
          <Title>
            {address.last_name} {address.first_name}
          </Title>
        )}
        {address.entity_type === 'business' && <Title>{address.business_name}</Title>}
        {address.entity_type === 'business' && (
          <>
            {(address.last_name || address.first_name) && (
              <Name>
                {address.last_name} {address.first_name}
              </Name>
            )}
          </>
        )}
        <Street>{address.address}</Street>
        <CityZip>
          {address.city}, {address.zip_code}
        </CityZip>
        <Country>{address.country.name}{address.type === 'shipping' && ` - ${currency.format(address.country.fee)}`}</Country>
        {address.comment && <Note>Megjegyzés: {address.comment}</Note>}
        {address.entity_type === 'private' && address.business_name && <BusinessName>Cégnév: {address.business_name} </BusinessName>}
        {address.vat_number && <TaxNumber>Adószám: {address.vat_number} </TaxNumber>}
      </Address>
      <Actions>
        <Icons>
          <IconWrapper onClick={onEdit}>
            <Icon type="edit" iconWidth="17px" iconHeight="18px" iconColor={colors.monza}></Icon>
          </IconWrapper>
          <IconWrapper onClick={() => onDelete(address.id, address.type)}>
            <Icon type="delete" iconWidth="17px" iconHeight="18px" iconColor={colors.monza}></Icon>
          </IconWrapper>
        </Icons>
      </Actions>
    </ProfileAddressItemComponent>
  );
}
