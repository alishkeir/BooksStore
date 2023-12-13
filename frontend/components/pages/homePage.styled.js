import styled from '@emotion/styled';
import breakpoints from '@vars/breakpoints';

export let HomePageWrapper = styled.div``;

export let MainHeroWrapper = styled.div`
  margin: 50px 0 50px;
  order: 0;

  @media (max-width: ${breakpoints.max.xl}) {
    margin: 40px 0 40px;
    order: 0;
  }
`;

export let MainHeroIconsWrapper = styled.div`
  margin-bottom: 140px;
  order: 1;

  @media (max-width: ${breakpoints.max.xl}) {
    margin-bottom: 70px;
    order: 1;
  }
`;

export let BestsellerBooksWrapper = styled.div`
  order: 2;
  margin-bottom: 100px;

  @media (max-width: ${breakpoints.max.xl}) {
    order: 2;
    margin-bottom: 50px;
  }
`;

export let SaleBooksWrapper = styled.div`
  order: 3;
  margin-bottom: 100px;

  @media (max-width: ${breakpoints.max.xl}) {
    order: 3;
    margin-bottom: 50px;
  }
`;

export let StoreMapWrapper = styled.div`
  order: 4;
  margin-bottom: 100px;
  text-align: center;

  @media (max-width: ${breakpoints.max.xl}) {
    order: 6;
    margin-bottom: 50px;
  }
`;

export let MainStoreMapWrapper = styled.a``;

export let NewsletterSignupWrapper = styled.div`
  order: 5;
  margin-bottom: 100px;

  @media (max-width: ${breakpoints.max.xl}) {
    order: 6;
    margin-bottom: 50px;
  }
`;

export let ReservationBooksWrapper = styled.div`
  order: 7;
  margin-bottom: 100px;

  @media (max-width: ${breakpoints.max.xl}) {
    order: 4;
    margin-bottom: 50px;
  }
`;

export let EbookBestsellerBooksWrapper = styled.div`
  order: 8;
  margin-bottom: 100px;

  @media (max-width: ${breakpoints.max.xl}) {
    order: 5;
    margin-bottom: 50px;
  }
`;

export let SalesListWrapper = styled.div`
  order: 9;
  margin-bottom: 100px;

  @media (max-width: ${breakpoints.max.xl}) {
    order: 9;
    margin-bottom: 50px;
  }
`;

export let NewBooksWrapper = styled.div`
  order: 10;
  margin-bottom: 100px;

  @media (max-width: ${breakpoints.max.xl}) {
    order: 10;
    margin-bottom: 50px;
  }
`;

export let MainBannerWrapper = styled.div`
  order: 11;
  margin-bottom: 120px;

  @media (max-width: ${breakpoints.max.xl}) {
    order: 12;
    margin-bottom: 50px;
  }
`;

export let ClassicBooksWrapper = styled.div`
  order: 12;
  margin-bottom: 100px;

  @media (max-width: ${breakpoints.max.xl}) {
    order: 11;
    margin-bottom: 50px;
  }
`;

export let MainCategoriesListerWrapper = styled.div`
  margin-bottom: 120px;
  position: relative;
  order: 13;

  @media (max-width: ${breakpoints.max.xl}) {
    margin-bottom: 40px;
  }
`;

export let ContentWrapper = styled.div`
  display: flex;
  flex-direction: column;
  position: relative;
  @media (max-width: ${breakpoints.max.xl}) {
    margin-bottom: -35px;
  }
`;

export let ContentWrapperEnd = styled.div`
  order: 999;
  @media (max-width: ${breakpoints.max.xl}) {
    padding-bottom: 35px;
  }
`;

export let ListHeaderWrapper = styled.div`
  margin: 0 0 23px;
`;

export let PoromtionWrapper = styled.div``;
