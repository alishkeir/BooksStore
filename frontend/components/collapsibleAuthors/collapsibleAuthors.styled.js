import styled from '@emotion/styled';
import { keyframes } from '@emotion/react';
import Icon from '@components/icon/icon';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';

const rotateOpen = keyframes`
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(180deg);
  }
`;

const rotateClose = keyframes`
  0% {
    transform: rotate(180deg);
  }
  100% {
    transform: rotate(0deg);
  }
`;

export let CollapsibleAuthorsComponent = styled.div``;

export let ListHeaderWrapper = styled.div`
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: ${({ open }) => (open && open ? '20px' : '60px')};
  border-bottom: ${({ border }) => border && `1px solid ${colors.mischka}`};
`;

export let Title = styled.div`
  font-weight: 700;
  font-size: 20px;
  flex: 1;

  @media (max-width: ${breakpoints.max.xl}) {
    font-size: 18px;
  }
`;

export let LinkIconWrapper = styled.div`
  transition: transform 1s linear;

  &:hover {
    cursor: pointer;
  }
`;

export let LinkIcon = styled(Icon)`
  vertical-align: middle;
  position: relative;
  bottom: 1px;
  height: 10px;
  animation: ${({ open }) => (open && open ? rotateClose : rotateOpen)};
  animation-duration: 0.2s;
  animation-timing-function: linear;
  animation-fill-mode: forwards;
`;

/*Author*/
export let AuthorNamesWrapper = styled.div`
  display: ${({ open }) => (open && open ? 'block' : 'none')};
`;

export let AuthorNameWrapper = styled.div`
  margin-bottom: 20px;
  &:last-child {
    margin-bottom: 60px;
  }
`;

export let AuthorName = styled.span`
  font-weight: 600;
  font-size: 18px;

  &:hover {
    color: ${colors.monza};
    cursor: pointer;
  }
`;
