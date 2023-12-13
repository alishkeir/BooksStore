import { useState } from 'react';
import { useSelector } from 'react-redux';
import SiteColContainer from '@components/siteColContainer/siteColContainer';
import MobileMenuItem from '@components/mobileMenuItem/mobileMenuItem';
import MobileMenuHeadItem from '@components/mobileMenuHeadItem/mobileMenuHeadItem';
import { MobileMenuComponent, MenuContainer, SubMenuContainer, MenuList } from './mobileMenu.styled';
import settingsVars from "@vars/settingsVars";
import url from "@libs/url";

export default function MobileMenu() {
  let [submenuOpen, setSubmenuOpen] = useState(false);
  let [submenuSelected, setSubmenuSelected] = useState();

  let settings = settingsVars.get(url.getHost());
  let categories = useSelector((store) => store.ui.categories);

  return (
    <MobileMenuComponent>
      <MenuContainer>
        <SiteColContainer>
          <MenuList>
            <MobileMenuItem href="/akciok">Akciók</MobileMenuItem>
            <MobileMenuItem onClick={() => handleMenuItemClick('books')} submenu>
              Könyvek
            </MobileMenuItem>
            {settings.pages['/ekonyvlista/[[...slug]]']?.visibleInMenu !== false && (
              <MobileMenuItem onClick={() => handleMenuItemClick('ebooks')} submenu>
                E-könyvek
              </MobileMenuItem>
            )}
            <MobileMenuItem onClick={() => handleMenuItemClick('toplists')} submenu>
              Sikerlisták
            </MobileMenuItem>
            {settings.pages['/magazin']?.visibleInMenu !== false && <MobileMenuItem href="/magazin">Magazin</MobileMenuItem>}
            {settings.pages['/konyvesboltok']?.visibleInMenu !== false && (
              <MobileMenuItem href="/konyvesboltok">Álomgyár könyvesboltok</MobileMenuItem>
            )}

            <MobileMenuItem href="/kapcsolat">Kapcsolat</MobileMenuItem>
          </MenuList>
        </SiteColContainer>
      </MenuContainer>
      <SubMenuContainer open={submenuOpen}>
        {submenuSelected === 'books' && (
          <SiteColContainer>
            <MenuList>
              <MobileMenuHeadItem onClick={() => setSubmenuOpen(false)}>Könyvek</MobileMenuHeadItem>
              {categories.map((category) => (
                <MobileMenuItem href={`/konyvlista/${category.slug}`} key={category.slug}>
                  {category.title}
                </MobileMenuItem>
              ))}
            </MenuList>
          </SiteColContainer>
        )}
        {submenuSelected === 'ebooks' && (
          <SiteColContainer>
            <MenuList>
              <MobileMenuHeadItem onClick={() => setSubmenuOpen(false)}>E-Könyvek</MobileMenuHeadItem>
              {categories.map((category) => (
                <MobileMenuItem href={`/ekonyvlista/${category.slug}`} key={category.slug}>
                  {category.title}
                </MobileMenuItem>
              ))}
            </MenuList>
          </SiteColContainer>
        )}
        {submenuSelected === 'toplists' && (
          <SiteColContainer>
            <MenuList>
              <MobileMenuHeadItem onClick={() => setSubmenuOpen(false)}>Sikerlisták</MobileMenuHeadItem>
              <MobileMenuItem href="/sikerlista/eladasi-sikerlista">Eladási sikerlista</MobileMenuItem>
              <MobileMenuItem href="/sikerlista/akcios-sikerlista">Akciós sikerlista</MobileMenuItem>
              <MobileMenuItem href="/sikerlista/elojegyzes-sikerlista">Előjegyzési sikerlista</MobileMenuItem>
              {settings.key !== 'NAGYKER' && <MobileMenuItem href="/sikerlista/akcios-sikerlista">E-könyv sikerlista</MobileMenuItem>}
            </MenuList>
          </SiteColContainer>
        )}
      </SubMenuContainer>
    </MobileMenuComponent>
  );

  function handleMenuItemClick(submenu) {
    setSubmenuOpen(true);
    setSubmenuSelected(submenu);
  }
}
