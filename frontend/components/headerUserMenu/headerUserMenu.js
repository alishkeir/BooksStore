import Link from 'next/link';
import useUser from '@hooks/useUser/useUser';
import {
  Action,
  ActionItem,
  Content,
  HeaderUserMenuWrapper,
  List,
  ListItem,
  Title,
  Triangle,
} from '@components/headerUserMenu/headerUserMenu.styled';
import settingsVars from "@vars/settingsVars";
import url from "@libs/url";

export default function HeaderUserMenu({ onClose, onLogout, firstName }) {
  let settings = settingsVars.get(url.getHost());

  let { actualUser } = useUser();
  return (
    <HeaderUserMenuWrapper>
      <Triangle></Triangle>
      <Content>
        <Title>Üdv{firstName && `, ${firstName}`}!</Title>
        <List>
          <ListItem onClick={onClose}>
            <Link href="/profil/szemelyes-adataim" passHref>
              Személyes adataim
            </Link>
          </ListItem>
          <ListItem onClick={onClose}>
            <Link href="/profil/rendeleseim" passHref>
              Rendeléseim
            </Link>
          </ListItem>
          <ListItem onClick={onClose}>
            <Link href="/profil/elojegyzeseim" passHref>
              Előjegyzéseim
            </Link>
          </ListItem>
          <ListItem onClick={onClose}>
            <Link href="/profil/szamlazasi-cimeim" passHref>
              Számlázási címeim
            </Link>
          </ListItem>
          <ListItem onClick={onClose}>
            <Link href="/profil/szallitasi-cimeim" passHref>
              Szállítási címeim
            </Link>
          </ListItem>
          <ListItem onClick={onClose}>
            <Link href="/profil/kivansaglistam" passHref>
              Kívánságlistám
            </Link>
          </ListItem>
          <ListItem onClick={onClose}>
            <Link href="/profil/kovetett-szerzok" passHref>
              Követett szerzők
            </Link>
          </ListItem>
          {settings.key === 'ALOMGYAR' && (
            <ListItem onClick={onClose}>
              <Link href="/profil/e-konyveim" passHref>
                E-könyveim
              </Link>
            </ListItem>
          )}
          <ListItem onClick={onClose}>
            <Link href="/profil/konyvertekeleseim" passHref>
              Könyvértékeléseim
            </Link>
          </ListItem>
          {actualUser.customer?.is_affiliate &&
          <ListItem onClick={onClose}>
            <Link href="/profil/affiliate-program" passHref>
              Affiliate program
            </Link>
          </ListItem>}
        </List>
        <Action>
          <ActionItem onClick={onLogout}>Kijelentkezés</ActionItem>
        </Action>
      </Content>
    </HeaderUserMenuWrapper>
  );
}
