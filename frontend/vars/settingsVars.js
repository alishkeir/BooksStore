import ImageOgimageOlcsokonyvek from '@assets/images/social/og-image-olcsokonyvek.png';
import ImageOgimageAlomgyar from '@assets/images/social/og-image-alomgyar.png';

let settingsVars = {};

settingsVars.ALOMGYAR = {
  key: "ALOMGYAR",
  common: {
    auth: {
      socialLogin: true,
      registration: true,
    },
    meta: {
      title: 'Álomgyár',
      description: 'Álomgyár - Várunk az álomgyár könyvesboltokban, országszerte 18 városban. Akciók, kedvezmények, naponta bővülő kínálat, több tízezer könyv vár. Válogass kedvedre!',
      url: 'https://alomgyar.hu',
      image: ImageOgimageAlomgyar,
      type: 'website',
    },
  },
  pages: {
    '/': {
      id: 'home',
      title: 'Főoldal',
      visibleInMenu: true,
      accessible: true,
    },
    '/konyvlista/[[...slug]]': {
      title: 'Könyvek',
    },
    '/ekonyvlista/[[...slug]]': {
      title: 'E-könyvek',
    },
    '/akciok': {
      title: 'Akciók',
    },
    '/ujdonsagok': {
      title: 'Újdonságok',
    },
    '/konyvesboltok': {
      title: 'Boltjaink',
    },
    '/magazin': {
      title: 'Magazin',
    },
    '/kapcsolat': {
      title: 'Kapcsolat',
    },
    '/kereses': {
      title: 'Találatok',
    },
    '/kosar': {
      title: 'Kosár',
    },
    '/csomagom': {
      title: 'Csomagom',
    },
    '/csomagom/[slug]': {
      title: 'Csomagom',
    },
    '/sikerlista/[slug]': {
      title: 'Sikerlista',
    },
    '/penztar/szamlazasi-adatok': {
      title: 'Számlázás',
    },
    '/penztar/szallitasi-adatok': {
      title: 'Szállítás',
    },
    '/penztar/osszesites': {
      title: 'Összesítés',
    },
    '/penztar/fizetes/[slug]': {
      title: 'Fizetés',
    },
    '/profil/szemelyes-adataim': {
      title: 'Személyes adataim',
    },
    '/profil/rendeleseim': {
      title: 'Rendeléseim',
    },
    '/profil/elojegyzeseim': {
      title: 'Előjegyzéseim',
    },
    '/profil/szamlazasi-cimeim': {
      title: 'Számlázási címeim',
    },
    '/profil/szallitasi-cimeim': {
      title: 'Szállítási címeim',
    },
    '/profil/kivansaglistam': {
      title: 'Kívánságlistám',
    },
    '/profil/kovetett-szerzok': {
      title: 'Követett szerzők',
    },
    '/profil/e-konyveim': {
      title: 'E-könyveim',
    },
    '/profil/konyvertekeleseim': {
      title: 'Könyvértékeléseim',
    },
  },
};

settingsVars.OLCSOKONYVEK = {
  key: "OLCSOKONYVEK",
  common: {
    auth: {
      socialLogin: true,
      registration: true,
    },
    meta: {
      title: 'Olcsókönyvek',
      description:
        'Olcsókönyvek - legalább 23% kedvezmény több tízezer könyvre, 9500 Ft felett ingyenes szállítás vagy személyes átvétel országszerte több ezer helyen. Naponta frissülő akciós könyvek minden korosztálynak.',
      url: 'https://olcsokonyvek.hu',
      image: ImageOgimageOlcsokonyvek,
      type: 'website',
    },
  },
  pages: {
    '/': {
      title: 'Főoldal',
      visibleInMenu: true,
      accessible: true,
    },
    '/konyvlista/[[...slug]]': {
      title: 'Könyvek',
    },
    '/ekonyvlista/[[...slug]]': {
      title: 'E-könyvek',
      visibleInMenu: false,
      accessible: false,
    },
    '/akciok': {
      title: 'Akciók',
    },
    '/ujdonsagok': {
      title: 'Újdonságok',
    },
    '/konyvesboltok': {
      title: 'Boltjaink',
      visibleInMenu: false,
      accessible: false,
    },
    '/magazin': {
      title: 'Magazin',
      visibleInMenu: false,
      accessible: false,
    },
    '/kapcsolat': {
      title: 'Kapcsolat',
    },
    '/kereses': {
      title: 'Találatok',
    },
    '/kosar': {
      title: 'Kosár',
    },
    '/csomagom': {
      title: 'Csomagom',
    },
    '/csomagom/[slug]': {
      title: 'Csomagom',
    },
    '/penztar/szamlazasi-adatok': {
      title: 'Számlázás',
    },
    '/penztar/szallitasi-adatok': {
      title: 'Szállítás',
    },
    '/penztar/osszesites': {
      title: 'Összesítés',
    },
    '/penztar/fizetes': {
      title: 'Fizetés',
    },
    '/profil/szemelyes-adataim': {
      title: 'Személyes adataim',
    },
    '/profil/rendeleseim': {
      title: 'Rendeléseim',
    },
    '/profil/elojegyzeseim': {
      title: 'Előjegyzéseim',
    },
    '/profil/szamlazasi-cimeim': {
      title: 'Számlázási címeim',
    },
    '/profil/szallitasi-cimeim': {
      title: 'Szállítási címeim',
    },
    '/profil/kivansaglistam': {
      title: 'Kívánságlistám',
    },
    '/profil/kovetett-szerzok': {
      title: 'Követett szerzők',
    },
    '/profil/e-konyveim': {
      title: 'E-könyveim',
      accessible: false,
    },
    '/profil/konyvertekeleseim': {
      title: 'Könyvértékeléseim',
    },
  },
};

settingsVars.NAGYKER = {
  key: "NAGYKER",
  common: {
    auth: {
      socialLogin: false,
      registration: false,
    },
    meta: {
      title: 'Álomgyár nagyker',
      description: 'Álomgyár nagykereskedés',
      url: 'https://nagyker.alomgyar.hu',
      image: ImageOgimageAlomgyar,
      type: 'website',
    },
  },
  pages: {
    '/': {
      title: 'Főoldal',
      visibleInMenu: true,
      accessible: true,
    },
    '/konyvlista/[[...slug]]': {
      title: 'Könyvek',
    },
    '/ekonyvlista/[[...slug]]': {
      title: 'E-könyvek',
      visibleInMenu: false,
      accessible: false,
    },
    '/akciok': {
      title: 'Akciók',
    },
    '/ujdonsagok': {
      title: 'Újdonságok',
    },
    '/konyvesboltok': {
      title: 'Boltjaink',
      visibleInMenu: false,
      accessible: false,
    },
    '/magazin': {
      title: 'Magazin',
      visibleInMenu: false,
      accessible: false,
    },
    '/kapcsolat': {
      title: 'Kapcsolat',
    },
    '/kereses': {
      title: 'Találatok',
    },
    '/kosar': {
      title: 'Kosár',
    },
    '/csomagom': {
      title: 'Csomagom',
    },
    '/csomagom/[slug]': {
      title: 'Csomagom',
    },
    '/penztar/szamlazasi-adatok': {
      title: 'Számlázás',
    },
    '/penztar/szallitasi-adatok': {
      title: 'Szállítás',
    },
    '/penztar/osszesites': {
      title: 'Összesítés',
    },
    '/penztar/fizetes': {
      title: 'Fizetés',
    },
    '/profil/szemelyes-adataim': {
      title: 'Személyes adataim',
    },
    '/profil/rendeleseim': {
      title: 'Rendeléseim',
    },
    '/profil/elojegyzeseim': {
      title: 'Előjegyzéseim',
    },
    '/profil/szamlazasi-cimeim': {
      title: 'Számlázási címeim',
    },
    '/profil/szallitasi-cimeim': {
      title: 'Szállítási címeim',
    },
    '/profil/kivansaglistam': {
      title: 'Kívánságlistám',
    },
    '/profil/kovetett-szerzok': {
      title: 'Követett szerzők',
    },
    '/profil/e-konyveim': {
      title: 'E-könyveim',
      accessible: false,
    },
    '/profil/konyvertekeleseim': {
      title: 'Könyvértékeléseim',
    },
  },
};

settingsVars.get = function (host = null){
  if (host?.indexOf("olcsokonyvek") > -1) {
    return settingsVars.OLCSOKONYVEK;
  } else if (host?.indexOf("nagyker") > -1) {
    return settingsVars.NAGYKER;
  }
  return settingsVars.ALOMGYAR;
}

export { settingsVars };
export default settingsVars;
