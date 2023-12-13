import styled from '@emotion/styled';
import breakpoints from '@vars/breakpoints';

export let OverlayCardComponent = styled.div`
  position: absolute;

  background-color: #fff;
  width: calc(100% - 30px);
  max-width: ${({ maxWidth }) => maxWidth};
  max-height: ${({ maxHeight }) => maxHeight};
  overflow: hidden;
  box-shadow: 0px 0px 40px -10px rgba(7, 30, 44, 0.17);
  border-radius: 10px;
  padding: 20px;
  margin: auto;

  ${(props) =>
    props.type === 'full' &&
    ` 
      right: 15px;
      left: 15px;
      bottom: 20px;

      @media (max-width: ${breakpoints.max.xl}) {
        top: 180px;
      }
  
  `}

  ${(props) =>
    props.mobile === 'full' &&
    `
    @media (max-width: ${breakpoints.max.sm}) {
    right: 0;
    left: 0;
    top: 60px;
    width: 100%;
    border-radius: 0;
    }
  `}

  ${(props) =>
    props.mobile === 'box' &&
    `
    @media (max-width: ${breakpoints.max.sm}) {
    right: 15px;
    left: 15px;
    }
  `}

  ${(props) =>
    props.align === 'top' &&
    `
      top: 120px;

      @media (max-width: ${breakpoints.max.sm}) {
        top: 60px;
      }
  
  `}
`;

export let CloseIconWrapper = styled.div`
  position: absolute;
  top: 13px;
  right: 13px;
  cursor: pointer;
`;
