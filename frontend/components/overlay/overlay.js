import { useEffect } from 'react';
import { Content, OverlayWrapper } from './overlay.styled';

export default function Overlay(props) {
  let { children, overlayVisible = true, zIndex = 99998, fixed = true, floating = true, onClick = () => {} } = props;

  // If mobile menu open we fixate the body
  useEffect(() => {
    if (!fixed) return;

    document.body.classList.add('fixed');

    return () => {
      document.body.classList.remove('fixed');
    };
  }, [fixed]);

  return (
    <OverlayWrapper zIndex={zIndex} overlayVisible={overlayVisible} onClick={onClick} floating={floating}>
      <Content>{children}</Content>
    </OverlayWrapper>
  );
}
