import Icon from '@components/icon/icon';
import { HeaderIconUserComponent, UserGuest, UserInitial } from './headerIconUser.styled';

export default function HeaderIconUser({ theme, user, onClick = () => {} }) {
  return (
    <HeaderIconUserComponent onClick={onClick}>
      {user && <UserInitial theme={theme}>{user.customer.firstname ? user.customer.firstname[0] : user.customer.email[0]}</UserInitial>}
      {!user && <Icon type="profile" iconWidth="22px" iconHeight="22px"></Icon>}
      <UserGuest></UserGuest>
    </HeaderIconUserComponent>
  );
}
