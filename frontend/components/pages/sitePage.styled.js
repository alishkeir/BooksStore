import styled from '@emotion/styled';

export let SitePageWrapper = styled.div`
  padding: 40px;
  background-color: #fff;
  height: 100%;
  min-height: 100vh;
`;

export let Section = styled.div`
  margin-bottom: 40px;
`;

export let SectionTitle = styled.h2`
  color: gray;
  padding-bottom: 10px;
  border-bottom: 1px solid gray;
  margin-bottom: 30px;
`;

export let SectionContent = styled.div``;

export let SwatchList = styled.div`
  display: flex;
  flex-wrap: wrap;
  margin: 0 -15px;
`;

export let SwatchWrapper = styled.div`
  padding: 0 15px;
  margin: 0 0 15px;
  width: 10%;
`;

export let Color = styled.div`
  border: 1px solid lightgray;
  height: 100px;
  width: 100%;
  margin-bottom: 5px;
  background-color: ${({ color }) => (color ? color : 'gray')};
`;

export let Text = styled.div``;

export let Swatch = styled.div``;

export let SubSection = styled.div`
  margin-bottom: 40px;

  h3 {
    margin-bottom: 20px;
  }
`;

export let SectionBlock = styled.div`
  margin-bottom: 10px;
`;
