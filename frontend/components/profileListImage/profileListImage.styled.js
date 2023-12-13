import styled from '@emotion/styled';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';

export let ProfileListImageComponent = styled.div``;
export let ImageWrapper = styled.div`
  > div {
    overflow: initial !important;
  }
`;

export let ListItemImageType = styled.div`
  background-color: ${colors.malachite};
  display: flex;
  justify-content: center;
  align-items: center;
  color: white;
  font-weight: 700;
  font-size: 12px;
  border-radius: 0px 0px 10px 10px;
  min-height: 25px;
`;

export let ListItemImage = styled.div`
  width: 160px;
  height: auto;
  padding: 20px;
  background: ${colors.zirconBlue};
  border-radius: 10px;
  align-self: flex-start;
  display: flex;
  align-items: center;
  justify-content: center;

  @media (max-width: ${breakpoints.max.lg}) {
    padding: 10px;
    width: 90px;
    height: auto;
  }

  div {
    vertical-align: top;
  }

  img {
    filter: drop-shadow(-20px 20px 20px rgba(0, 0, 0, 0.2));
  }
`;
