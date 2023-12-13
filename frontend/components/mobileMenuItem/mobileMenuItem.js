import Link from 'next/link';
import Icon from '@components/icon/icon';
import { MobileMenuItemComponent, MobileMenuLinkItem, MobileMenuSubItem, Text } from './mobileMenuItem.styled';

export default function MobileMenuItem(props) {
  let { children, href, chevron, submenu, onClick = () => {} } = props;

  return (
    <MobileMenuItemComponent>
      {href && (
        <Link href={href} passHref legacyBehavior>
          <MobileMenuLinkItem>
            <Text>{children}</Text>
            {chevron && <Icon type="chevron-right-small" iconWidth="8px" iconHeight="13px"></Icon>}
          </MobileMenuLinkItem>
        </Link>
      )}
      {submenu && (
        <MobileMenuSubItem onClick={onClick}>
          <Text>{children}</Text>
          <Icon type="chevron-right-small" iconWidth="8px" iconHeight="13px"></Icon>
        </MobileMenuSubItem>
      )}
    </MobileMenuItemComponent>
  );
}
