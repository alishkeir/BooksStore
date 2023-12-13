import Head from 'next/head';
import styled from '@emotion/styled';

// pages/404.js
export default function Custom404() {
  return (
    <Custom404Wrapper>
      <Head>
        <title>404: Az oldal nem található</title>
      </Head>
      <div>
        <Four>404</Four>
        <Line>
          <Text>Az oldal nem található.</Text>
        </Line>
      </div>
    </Custom404Wrapper>
  );
}

const Custom404Wrapper = styled.div`
  color: #000;
  background: #fff;
  font-family: -apple-system, BlinkMacSystemFont, Roboto, 'Segoe UI', 'Fira Sans', Avenir, 'Helvetica Neue', 'Lucida Grande', sans-serif;
  height: 100vh;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
`;
const Four = styled.h1`
  display: inline-block;
  border-right: 1px solid rgba(0, 0, 0, 0.3);
  margin: 0;
  margin-right: 20px;
  padding: 10px 23px 10px 0;
  font-size: 24px;
  font-weight: 500;
  vertical-align: top;
`;
const Line = styled.div`
  display: inline-block;
  text-align: left;
  line-height: 49px;
  height: 49px;
  vertical-align: middle;
`;
const Text = styled.div`
  font-size: 14px;
  font-weight: normal;
  line-height: inherit;
  margin: 0;
  padding: 0;
`;
