import styled from '@emotion/styled';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';
import ImageStar from '@assets/images/icons/star.svg';

export let RatingWrapper = styled.div`
  display: flex;
  align-items: center;
  line-height: 1;
`;

export let Stars = styled.div`
  margin-right: 10px;
  cursor: pointer;
  display: flex;
`;

export let Star = styled(ImageStar)`
  width: 16px;
  height: 16px;
  vertical-align: top;
  margin-right: 8px;
  cursor: pointer;

  @media (max-width: ${breakpoints.max.md}) {
    width: 13px;
    height: 13px;
  }

  path {
    transition: fill 0.4s ease-in-out;
  }
`;

export let StarWrapper = styled.div`
  ${Star} {
    path {
      fill: ${({ isOn, hovering, userRating }) => (isOn ? (hovering ? colors.saffron : userRating ? colors.saffron : '#ffe58a') : colors.mischka)};
    }
  }
`;

export let Numbers = styled.div`
  position: relative;
  font-weight: 400;
  font-size: 16px;
  top: 1px;

  @media (max-width: ${breakpoints.max.md}) {
    font-size: 14px;
  }

  strong {
    font-weight: 600;
  }
`;
