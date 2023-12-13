import styled from '@emotion/styled';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';
import settingsVars from "@vars/settingsVars";
import url from "@libs/url";

let theme = {
  listItemHoverColor: colors.monza,
};

let settings = settingsVars.get(url.getHost());

if (settings.key === 'OLCSOKONYVEK') {
  theme = {
    listItemHoverColor: colors.corn,
  };
}

if (settings.key === 'NAGYKER') {
  theme = {
    listItemHoverColor: colors.dodgerBlueLight,
  };
}

export let InputText = styled.div`
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  font-weight: 600;
  font-size: 14px;
  flex: 1;
  color: ${({ disabled }) => (disabled && disabled ? `${colors.mischka}` : `${colors.mineShaft}`)};
`;

export let InputIcon = styled.div`
  width: 16px;
  position: absolute;
  right: 15px;
  top: 50%;
  color: aliceblue;
  transform: translateY(-50%);
`;

export let IconWrapper = styled.div`
  width: 10px;
  margin: 0 auto;
  transform-origin: 50%;
  transition: transform 0.2s ease-in-out;
`;

export let ListItem = styled.li`
  padding: 10px 45px 10px 15px;
  list-style: none;
  margin: 0;

  &:hover {
    cursor: pointer;
    font-weight: 600;
    color: ${theme.listItemHoverColor};
  }

  &:active,
  &:focus {
    color: ${colors.monza};
  }

  &::before {
    display: block;
    height: 0;
    overflow: hidden;
    font-weight: 600;
    content: attr(data-text);
  }
`;

export let List = styled.div`
  border-radius: 0 0 10px 10px;
  border-width: 0px 1px 1px 1px;
  position: relative;
  background-color: white;
  width: 100%;
  z-index: 999;
`;

export let ListItemsWrapper = styled.ul`
  margin: 0;
  padding: 0;
  position: absolute;
  display: inline-block;
  border: 1px solid ${colors.mischka};
  display: table-cell;
  left: 0;
  top: -1px;
  width: 100%;
  background-color: white;
  max-height: 300px;
  overflow-y: auto;
`;

export let Input = styled.div`
  position: relative;
  border-radius: 10px;
  display: flex;
  height: ${({ height }) => (height ? height : '50px')};
  align-items: center;
  padding: 0 45px 0 15px;
  background-color: ${colors.titanWhite};
  box-shadow: inset 0 0 0 1px ${({ error }) => (error ? colors.monza : colors.mischka)};

  @media (max-width: ${breakpoints.max.xl}) {
    height: ${({ height }) => (height ? height : '40px')};
  }

  &:hover {
    box-shadow: inset 0 0 0 2px ${({ error }) => (error ? colors.monza : colors.mischka)};
    cursor: ${({ disabled }) => (disabled && disabled ? 'not-allowed' : 'pointer')};
  }
`;

export let DropdownWrapper = styled.div`
  display: inline-block;
  vertical-align: top;
  position: relative;
  width: ${({ width }) => (width ? width : '100%')};

  max-width: 100%;

  ${List} {
    height: ${({ open }) => !open && '0'};
    overflow: ${({ open }) => !open && 'hidden'};
  }

  ${Input} {
    border-radius: ${({ open }) => open && '10px 10px 0 0'};
  }

  ${IconWrapper} {
    transform: ${({ open }) => (open ? 'rotateZ(-90deg)' : 'rotateZ(90deg)')};
  }

  ${ListItemsWrapper} {
    border-width: ${({ open }) => open && '0 1px 1px 1px'};
  }
`;

export let BorderWrappers = styled.div`
  border-radius: 10px;
  padding: 1px;
  box-shadow: inset 0 0 0 1px ${colors.mischka};
  border: 1px solid transparent;

  &:hover {
    border: 1px solid ${colors.mischka};
  }
`;

export let Label = styled.div`
  display: block;
  font-weight: 400;
  font-size: 12px;
  padding-left: 15px;
  margin-bottom: 5px;
  line-height: 1.2;

  color: ${({ error }) => error && colors.monza};
`;

export let Error = styled.div`
  font-weight: 300;
  font-size: 12px;
  color: #e30613;
  padding-left: 15px;
  margin-top: 5px;
  line-height: 1.2;
`;
