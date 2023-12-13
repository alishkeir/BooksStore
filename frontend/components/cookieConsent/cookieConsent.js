import styled from '@emotion/styled';
import Button from '@components/button/button';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';
import { useEffect, useState } from 'react';

export default function CookieConsent() {
  let [visible, setVisible] = useState(false);

  function handleConsentClick() {
    localStorage.setItem(`cookie-consent`, true);
    setVisible(false);
  }

  useEffect(() => {
    let cookieConsent = localStorage.getItem(`cookie-consent`);

    setVisible(!cookieConsent);
  }, []);

  return (
    <CookieConsentWrapper visible={visible}>
      <Text>
        Ez az oldal Teljesítmény, Marketing sütiket használ a jobb böngészési élmény biztosítása érdekében. A webhely használatának folytatásával elfogadja
        ezeket a sütiket.
      </Text>
      <Action>
        <Button onClick={handleConsentClick}>Értettem</Button>
      </Action>
    </CookieConsentWrapper>
  );
}

// Styled
let CookieConsentWrapper = styled.div`
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  width: 100%;
  z-index: 99999;
  background: ${colors.tundora};
  color: #fff;
  padding: 15px 30px;
  display: ${({ visible }) => (visible ? 'flex' : 'none')};
  align-items: center;
  font-size: 14px;

  @media (max-width: ${breakpoints.max.md}) {
    font-size: 12px;
    padding: 10px;
  }
  
  @media (max-width: 450px) {
    flex-direction: column;
    align-items: start;
    gap: 16px;
  }
  
  a {
    color: ${colors.monza};
    text-decoration: underline;
  }
`;
let Text = styled.div`
  flex: 1;
`;
let Action = styled.div`
  margin-right: 120px;

  @media (max-width: 450px) {
    margin-right: 0;
  }
`;
