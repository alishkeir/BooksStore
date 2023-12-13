import Link from 'next/link';
import { useLogout } from '@hooks/useAuth/useAuth';
import { LogoutLine, NavigatorLine, ProfileNavigatorComponent } from '@components/profileNavigator/profileNavigator.styled';
import useUser from '@hooks/useUser/useUser';
import settingsVars from "@vars/settingsVars";
import url from "@libs/url";
let data = [
  {
    title: 'Személyes adataim',
    link: '/profil/szemelyes-adataim',
  },
  {
    title: 'Rendeléseim',
    link: '/profil/rendeleseim',
  },
  {
    title: 'Előjegyzéseim',
    link: '/profil/elojegyzeseim',
  },
  {
    title: 'Számlázási címeim',
    link: '/profil/szamlazasi-cimeim',
  },
  {
    title: 'Szállítási címeim',
    link: '/profil/szallitasi-cimeim',
  },
  {
    title: 'Kívánságlistám',
    link: '/profil/kivansaglistam',
  },
  {
    title: 'Követett szerzők',
    link: '/profil/kovetett-szerzok',
  },
  {
    title: 'E-könyveim',
    link: '/profil/e-konyveim',
  },
  {
    title: 'Könyvértékeléseim',
    link: '/profil/konyvertekeleseim',
  },
];

export default function ProfileNavigator({ selected }) {
  let logout = useLogout();
  let settings = settingsVars.get(url.getHost());
  let { actualUser } = useUser();
  if (actualUser.customer.is_affiliate && !data.find(item => item.link == '/profil/affiliate-program')) {
    data.push({
      title: 'Affiliate program',
      link: '/profil/affiliate-program',
    });
  }
  return (
    <ProfileNavigatorComponent>
      {data.map((item, itemIndex) => {
        if (settings.pages[item.link]?.accessible === false) return;

        return (
          <NavigatorLine key={itemIndex} selected={itemIndex === selected}>
            <Link href={item.link} passHref>
              {item.title}
            </Link>
          </NavigatorLine>
        );
      })}
      <LogoutLine onClick={() => logout()}>Kijelentkezés</LogoutLine>
    </ProfileNavigatorComponent>
  );
}
