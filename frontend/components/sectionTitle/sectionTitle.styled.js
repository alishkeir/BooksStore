import styled from '@emotion/styled';
import breakpoints from '@vars/breakpoints';
import colors from '@vars/colors';

export let SectionTitleComponent = styled.div`
  margin-bottom: ${({ mb }) => mb && `${mb}px`};
  font-weight: 700;
  font-size: 18px;
  line-height: 26px;
  padding-left: 20px;
  position: relative;

  @media (max-width: ${breakpoints.max.md}) {
    font-size: 16px;
  }

  &::before {
    content: '';
    display: block;
    height: 100%;
    width: 3px;
    border-radius: 17px;
    background-color: ${colors.monza};
    position: absolute;
    top: 0;
    left: 0;
  }
`;
