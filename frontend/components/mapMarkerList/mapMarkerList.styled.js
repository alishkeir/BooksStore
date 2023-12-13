import styled from '@emotion/styled';
import colors from '@vars/colors';

export let MapMarkerListComponent = styled.div`
  position: relative;
  z-index: 0;

  .scrollbar {
    > div:last-of-type {
      background-color: ${colors.monzaLight} !important;
      > div {
        background-color: ${colors.monza} !important;
      }
    }
  }
`;

export let ListItem = styled.div`
  padding: 20px;
  margin-right: 20px;
  border: 1px solid #d6d8e7;
  border-radius: 10px;
  margin-bottom: 10px;
  display: flex;
  align-items: center;
  cursor: pointer;

  &:hover {
    border-color: ${colors.silverChalice};
    box-shadow: 0px 0px 10px rgba(214, 216, 231, 0.65);
  }

  &:last-of-type {
    margin-bottom: 0;
  }

  ${({ selected }) =>
    selected &&
    `
    border-color: ${colors.mineShaft};
    box-shadow: 0px 0px 10px rgba(214, 216, 231, 0.65);
    background-color: ${colors.titanWhite};

    &:hover {
    border-color: ${colors.mineShaft};
  }
  `}
`;

export let ImageWrapper = styled.div`
  margin-right: 20px;
  flex-shrink: 0;
`;

export let Meta = styled.div`
  color: ${colors.mineShaftDark};
  flex: 1;
`;

export let Name = styled.div`
  font-size: 14px;
  font-weight: 600;
`;

export let Location = styled.div`
  font-size: 12px;
  font-weight: 300;
`;

export let IconWrapper = styled.div``;

export let Scrollbars = styled.div``;
