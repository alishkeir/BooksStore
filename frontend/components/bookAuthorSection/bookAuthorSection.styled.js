import styled from '@emotion/styled';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';

export let BookAuthorSectionWrapper = styled.div`
  background-color: ${colors.zirconBlue};
  padding: 50px 0;

  @media (max-width: ${breakpoints.max.md}) {
    padding: 30px 0;
  }
`;

export let Container = styled.div``;

export let Row = styled.div``;

export let Col = styled.div``;

export let Author = styled.div`
  display: flex;
  align-items: center;
`;

export let AuthorImage = styled.div`
  margin-right: 15px;
  background-color: ${colors.mischka};
  width: 50px;
  height: 50px;
  border-radius: 50%;
  div {
    vertical-align: top;
  }
  img {
    border-radius: 50%;
    overflow: hidden;
  }
`;

export let AuthorName = styled.div`
  font-size: 16px;

  @media (max-width: ${breakpoints.max.md}) {
    font-size: 14px;
  }
`;

export let AuthorMeta = styled.div`
  margin-bottom: 40px;

  @media (max-width: ${breakpoints.max.md}) {
    margin-bottom: 20px;
  }
`;

export let Action = styled.div`
  margin-bottom: 40px;

  @media (max-width: ${breakpoints.max.md}) {
    margin-bottom: 0;
  }
`;

export let Description = styled.div`
  text-align: justify;
  font-weight: 300;
  font-size: 14px;
  line-height: 22px;

  @media (max-width: ${breakpoints.max.md}) {
    margin-bottom: 30px;
  }
`;

export let WishlistButtonWrapper = styled.div`
  margin: 0 0 0 auto;
  max-width: 300px;

  @media (max-width: ${breakpoints.max.md}) {
    margin: 0 auto 0;
  }
`;

export let AuthorTitle = styled.div`
  font-weight: 700;
  font-size: 20px;
  margin-bottom: 25px;

  @media (max-width: ${breakpoints.max.md}) {
    font-size: 16px;
  }
`;
