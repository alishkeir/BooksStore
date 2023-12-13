import Icon from '@components/icon/icon';
import MobileMenuItem from '@components/mobileMenuItem/mobileMenuItem';
import SiteColContainer from '@components/siteColContainer/siteColContainer';
import {
  HeaderUserMobileMenuWrapper,
  MenuAction,
  MenuActionItem,
  MenuHead,
  MenuHeadEx,
  MenuHeadText,
  MenuList,
  MobileMenuContainer,
} from '@components/headerUserMobileMenu/headerUserMobileMenu.styled';
import settingsVars from "@vars/settingsVars";
import url from "@libs/url";

export default function HeaderUserMobileMenu({ onClose, onLogout, firstName }) {
  let settings = settingsVars.get(url.getHost());

  return (
    <HeaderUserMobileMenuWrapper>
      <MobileMenuContainer>
        <SiteColContainer>
          <MenuHead>
            <MenuHeadText>Üdv{firstName && `, ${firstName}`}!</MenuHeadText>
            <MenuHeadEx onClick={onClose}>
              <Icon type="ex-thin" iconWidth="20px"></Icon>
            </MenuHeadEx>
          </MenuHead>
          <MenuList>
            <MobileMenuItem chevron onClick={onClose} href="/profil/szemelyes-adataim">
              <a>Személyes adataim</a>
            </MobileMenuItem>
            <MobileMenuItem chevron onClick={onClose} href="/profil/rendeleseim">
              <a>Rendeléseim</a>
            </MobileMenuItem>
            <MobileMenuItem chevron onClick={onClose} href="/profil/elojegyzeseim">
              <a>Előjegyzéseim</a>
            </MobileMenuItem>
            <MobileMenuItem chevron onClick={onClose} href="/profil/szamlazasi-cimeim">
              <a>Számlázási címeim</a>
            </MobileMenuItem>
            <MobileMenuItem chevron onClick={onClose} href="/profil/szallitasi-cimeim">
              <a>Szállítási címeim</a>
            </MobileMenuItem>
            <MobileMenuItem chevron onClick={onClose} href="/profil/kivansaglistam">
              <a>Kívánságlistám</a>
            </MobileMenuItem>
            <MobileMenuItem chevron onClick={onClose} href="/profil/kovetett-szerzok">
              <a>Követett szerzők</a>
            </MobileMenuItem>
            {settings.key === 'ALOMGYAR' && (
              <MobileMenuItem chevron onClick={onClose} href="/profil/e-konyveim">
                <a>E-könyveim</a>
              </MobileMenuItem>
            )}
            <MobileMenuItem chevron onClick={onClose} href="/profil/konyvertekeleseim">
              <a>Könyvértékeléseim</a>
            </MobileMenuItem>
          </MenuList>
          <MenuAction>
            <MenuActionItem onClick={onLogout}>Kijelentkezés</MenuActionItem>
          </MenuAction>
        </SiteColContainer>
      </MobileMenuContainer>
    </HeaderUserMobileMenuWrapper>
  );
}
