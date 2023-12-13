import styled from '@emotion/styled';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';

export let SearchContainer = styled.div`
  display: flex;
  justify-content: center;
  margin-bottom: ${({ hasFound }) => (hasFound && hasFound ? '0' : '120px')};
`;

export let InputWrapper = styled.div`
  max-width: 600px;
  width: 100%;
  margin-bottom: 60px;
`;

export let IconWrapper = styled.div`
  width: 10px;
  margin: 0 auto;
  transform-origin: 50%;
  transition: transform 0.2s ease-in-out;
`;

export let ListHeaderWrapper = styled.div`
  cursor: pointer;
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: ${({ open }) => (open && open ? '20px' : '40px')};
  border-bottom: ${({ border }) => border && `1px solid ${colors.mischka}`};
  display: ${({ hasBooks }) => (hasBooks && hasBooks ? 'block' : 'none')};

  ${IconWrapper} {
    transform: ${({ open }) => (open ? 'rotateZ(-90deg)' : 'rotateZ(90deg)')};
  }
`;

export let AuthorHeaderWrapper = styled.div`
  cursor: pointer;
  justify-content: space-between;
  align-items: center;
  margin-bottom: ${({ open }) => (open && open ? '20px' : '60px')};
  border-bottom: ${({ border }) => border && `1px solid ${colors.mischka}`};
  display: ${({ hasBooks }) => (hasBooks && hasBooks ? 'flex' : 'none')};

  ${IconWrapper} {
    transform: ${({ open }) => (open ? 'rotateZ(-90deg)' : 'rotateZ(90deg)')};
  }
`;

export let Title = styled.div`
  font-weight: 700;
  font-size: 20px;
  flex: 1;

  @media (max-width: ${breakpoints.max.xl}) {
    font-size: 18px;
  }
`;

export let InputIcon = styled.div`
  display: ${({ isVisible }) => (isVisible ? 'none' : 'block')};
  width: 16px;
  position: relative;
  top: 12px;
  transform: translateY(-50%);
`;

export let ListContainer = styled.div`
  min-width: 100%;
  margin: ${({ hasBooks }) => (hasBooks && hasBooks ? '30px 0 120px' : '0')};
  display: ${({ open }) => (open && open ? 'flex' : 'none')};
  flex-wrap: wrap;
`;

export let GroupWrapper = styled.div`
  flex: ${({ elemCount }) => `0 1 ${100 / elemCount}%`};
`;

export let AuthorContainer = styled.div`
  min-width: 100%;
  margin-top: 30px;
  display: ${({ open }) => (open && open ? 'flex' : 'none')};
  margin-bottom: 120px;
  margin-left: calc(var(--bs-gutter-x) * -1);
  margin-right: calc(var(--bs-gutter-x) * -1);
  justify-content: space-between;
`;

export let ItemWrapper = styled.div`
  margin-left: calc(var(--bs-gutter-x) * 1);
  margin-right: calc(var(--bs-gutter-x) * 1);
`;

export let AuthorNameWrapper = styled.div`
  margin-bottom: 25px;
`;

export let AuthorLink = styled.a`
  &:hover {
    color: red;
    cursor: pointer;
  }
`;

export let AuthorName = styled.span`
  font-weight: 600;
  font-size: 18px;

  @media (max-width: ${breakpoints.max.xl}) {
    font-size: 16px;
  }
`;

export let AuthorNamesWrapper = styled.div`
  display: ${({ open }) => (open && open ? 'block' : 'none')};
`;

export let KeresesPageComponent = styled.div``;

export let AuthorWrapper = styled.div`
  margin-bottom: 60px;
`;

export let Pagination = styled.div`
  margin-bottom: 120px;
  justify-content: center;
  display: ${({ hasBooks }) => (hasBooks && hasBooks ? 'flex' : 'none')};
`;

export let BooksSection = styled.div``;
