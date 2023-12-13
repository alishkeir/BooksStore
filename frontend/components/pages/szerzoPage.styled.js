import styled from '@emotion/styled';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';

export let SzerzoPageWrapper = styled.div``;

export let Author = styled.div`
  background-color: ${colors.zirconBlue};
`;

export let Container = styled.div``;

export let Row = styled.div``;

export let Col = styled.div``;

export let AuthorWrapper = styled.div`
  margin: 110px 0 60px;

  @media (max-width: ${breakpoints.max.md}) {
    margin: 70px 0 40px;
  }
`;

export let AuthorInfo = styled.div`
  display: flex;
  align-items: center;
  margin-bottom: 50px;

  @media (max-width: ${breakpoints.max.md}) {
    margin-bottom: 15px;
  }
`;

export let AuthorImage = styled.div`
  width: 183px;
  height: 183px;
  min-width: 183px;
  margin-right: 60px;
  border-radius: 50%;
  overflow: hidden;
  background-color: ${colors.mischka};

  @media (max-width: ${breakpoints.max.md}) {
    width: 90px;
    height: 90px;
    min-width: 90px;
    margin-right: 20px;
  }
`;

export let AuthorName = styled.div`
  text-transform: uppercase;
  font-weight: 700;
  font-size: 36px;

  @media (max-width: ${breakpoints.max.md}) {
    font-size: 22px;
    line-height: 30px;
  }
`;

export let AuthorText = styled.div`
  font-weight: 300;
  font-size: 16px;
  line-height: 24px;
  margin-bottom: 40px;

  @media (max-width: ${breakpoints.max.md}) {
    font-size: 14px;
    line-height: 22px;
    margin-bottom: 30px;
  }
`;

export let AuthorActions = styled.div`
  text-align: right;
`;

export let WishlistButtonWrapper = styled.div`
  max-width: 280px;
  margin-left: auto;

  @media (max-width: ${breakpoints.max.md}) {
    width: 100%;
    max-width: 100%;
  }
`;

export let List = styled.div`
  padding: 60px 0 140px;

  @media (max-width: ${breakpoints.max.md}) {
    padding: 30px 0 55px;
  }
`;

export let ListHeaderWrapper = styled.div`
  margin: 0 0 30px;
`;

export let Booklist = styled.div`
  margin-bottom: 60px;

  @media (max-width: ${breakpoints.max.md}) {
    margin-bottom: 30px;
  }
`;

export let PaginantionWrapper = styled.div`
  display: flex;
  justify-content: center;
  margin-bottom: 60px;
`;
