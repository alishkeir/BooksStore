import { useEffect, useState } from 'react';
import Icon from '@components/icon/icon';
import { useSelector } from 'react-redux';
import { ScrollToTopComponent, Background, IconWrapper } from './scrollToTop.styled';

export default function ScrollToTop() {
  let scrollPosition = useSelector((store) => store.system.scrollPosition);
  let windowHeight = useSelector((store) => store.system.windowHeight);
  let [visible, setVisible] = useState(false);

  // Button should be visible after
  // 50% of the current window height
  useEffect(() => {
    if (scrollPosition >= windowHeight / 2) {
      if (!visible) setVisible(true);
    } else {
      if (visible) setVisible(false);
    }
  }, [scrollPosition, windowHeight]);

  return (
    <ScrollToTopComponent
      onClick={() =>
        window.scrollTo({
          left: 0,
          top: 0,
          behavior: 'smooth',
        })
      }
      windowHeight={windowHeight}
    >
      <Background visible={visible}>
        <IconWrapper>
          <Icon type="chevron-right" iconWidth="12px" iconColor="#FFFFFF"></Icon>
        </IconWrapper>
      </Background>
    </ScrollToTopComponent>
  );
}
