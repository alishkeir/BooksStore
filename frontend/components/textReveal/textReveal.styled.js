import styled from '@emotion/styled';
import colors from '@vars/colors';

export let Content = styled.div``;

export let Opener = styled.div`
  font-weight: 600;
  font-size: 14px;
  color: ${colors.monza};
  text-decoration-line: underline;
  text-align: center;
  cursor: pointer;
`;

export let ContentGradient = styled.div`
  position: absolute;
  bottom: 0;
  height: 181px;
  width: 100%;
  background: linear-gradient(180deg, rgba(255, 255, 255, 0) 0%, #ffffff 94.27%);
`;

export let ContentWrapper = styled.div`
  height: var(--reveal-height);
  overflow: hidden;
  position: relative;
`;

export let TextRevealWrapper = styled.div`
  ${ContentWrapper} {
    cursor: ${({ open }) => !open && 'pointer'};
  }

  ${ContentGradient} {
    display: ${({ open }) => open && 'none'};
  }

  ${Opener} {
    display: ${({ open }) => open && 'none'};
  }
`;
