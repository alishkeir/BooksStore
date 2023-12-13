import styled from '@emotion/styled';
import breakpoints from '@vars/breakpoints';

export let AkciokPageWrapper = styled.div``;

export let Banners = styled.div`
  margin-bottom: 60px;

  @media (max-width: ${breakpoints.max.md}) {
    margin-bottom: 25px;
  }
`;

export let BannerWrapper = styled.div`
  border-radius: 10px;
  overflow: hidden;
  margin-bottom: calc(var(--bs-gutter-x));
  flex: 0 0 auto;
  padding-right: calc(var(--bs-gutter-x) / 2);
  padding-left: calc(var(--bs-gutter-x) / 2);
  width: ${({ big }) => (big ? '40%' : '30%')};

  @media (max-width: ${breakpoints.max.md}) {
    width: 100%;
  }

  div {
    vertical-align: top;
    border-radius: 10px;
    overflow: hidden;
  }
`;

export let Booklist = styled.div`
  margin-bottom: 120px;

  @media (max-width: ${breakpoints.max.md}) {
    margin-bottom: 45px;
  }
`;

export let List = styled.div``;

export let ListHeaderWrapper = styled.div`
  margin: 0 0 30px;
`;
