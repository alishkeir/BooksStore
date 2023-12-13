import styled from '@emotion/styled';

export let OverlayWrapper = styled.div`
  ${({ floating }) => {
    if (floating) {
      return `
        position: fixed;
    `;
    } else {
      return `
        position: absolute;
        height: 100%;
      `;
    }
  }}
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  width: 100%;
  background-color: rgba(0, 0, 0, 0.2);
  z-index: ${({ zIndex }) => zIndex};
  backdrop-filter: blur(4px);
`;

export let Content = styled.div`
  height: 100%;
  width: 100%;
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
`;
