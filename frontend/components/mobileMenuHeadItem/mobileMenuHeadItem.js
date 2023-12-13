import Icon from '@components/icon/icon';
import { MobileMenuHeadItemComponent, Text, IconWrapper } from './mobileMenuHeadItem.styled';

export default function MobileMenuHeadItem(props) {
  let { children, onClick = () => {} } = props;

  return (
    <MobileMenuHeadItemComponent onClick={onClick}>
      <IconWrapper>
        <Icon type="chevron-right-small" iconWidth="8px"></Icon>
      </IconWrapper>
      <Text>{children}</Text>
    </MobileMenuHeadItemComponent>
  );
}
