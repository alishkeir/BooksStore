import styled from '@emotion/styled';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';

export let NavStepsComponent = styled.div``;

export let Line = styled.div`
  flex: 1;
  height: 2px;
  background-color: ${({ active }) => (active ? colors.monza : colors.mischka)};
  position: relative;
  top: 20px;

  @media (max-width: ${breakpoints.max.md}) {
    top: 15px;
  }
`;

export let Spots = styled.div`
  position: relative;
  z-index: 2;
  display: flex;
`;

export let Spot = styled.div``;

export let CircleWrapper = styled.div`
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 7px;
  position: relative;

  &::before {
    content: '';
    position: absolute;
    width: 50%;
    height: 2px;
    background-color: ${({ backLineActive }) => (backLineActive ? colors.monza : colors.mischka)};
    top: 20px;
    transform: translateX(-51%);
    z-index: 1;
    display: ${({ isFirst }) => (isFirst ? 'none' : 'block')};

    @media (max-width: ${breakpoints.max.md}) {
      top: 15px;
    }
  }

  &::after {
    content: '';
    position: absolute;
    width: 50%;
    height: 2px;
    background-color: ${({ forwardLineActive }) => (forwardLineActive ? colors.monza : colors.mischka)};
    top: 20px;
    transform: translateX(51%);
    z-index: 1;
    display: ${({ isLast }) => (isLast ? 'none' : 'block')};

    @media (max-width: ${breakpoints.max.md}) {
      top: 15px;
    }
  }
`;

export let Label = styled.div`
  font-size: 14px;
  ${({ finished, active, failed }) => `
    color: ${finished || active ? colors.monza : failed ? colors.mineShaft : colors.mischka};
    font-weight: ${active ? '700' : '400'};
  `}

  @media (max-width: ${breakpoints.max.md}) {
    font-size: 12px;
  }
`;

export let Circle = styled.div`
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  font-weight: 700;
  font-size: 16px;
  z-index: 2;
  position: relative;

  @media (max-width: ${breakpoints.max.md}) {
    width: 30px;
    height: 30px;
  }
`;

export let CircleFailed = styled(Circle)`
  border: 2px solid ${colors.mineShaft};
  background-color: ${colors.mineShaft};

  & ~ ${Label} {
    color: ${colors.mineShaft};
  }
`;

export let CircleFinished = styled(Circle)`
  border: 2px solid ${colors.monza};
  background-color: ${colors.monza};

  & ~ ${Label} {
    color: ${colors.monza};
  }
`;

export let CircleActive = styled(Circle)`
  background-color: white;
  border: 2px solid ${colors.monza};
  color: ${colors.monza};

  & ~ ${Label} {
    font-weight: 700;
    color: ${colors.monza};
  }
`;

export let CircleInactive = styled(Circle)`
  background-color: white;
  border: 2px solid ${colors.mischka};
  color: ${colors.mischka};

  & ~ ${Label} {
    color: ${colors.mischka};
  }
`;
