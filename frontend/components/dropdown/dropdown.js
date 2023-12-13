import { useEffect, useRef, useState } from 'react';
import Icon from '@components/icon/icon';
import colors from '@vars/colors';
import { DropdownWrapper, Error, IconWrapper, Input, InputIcon, InputText, Label, List, ListItem, ListItemsWrapper } from './dropdown.styled';
import settingsVars from "@vars/settingsVars";
import url from "@libs/url";

let theme = {
  dropdownChevronColor: colors.monza,
};

let settings = settingsVars.get(url.getHost());

if (settings.key === 'OLCSOKONYVEK') {
  theme = {
    dropdownChevronColor: colors.corn,
  };
}

if (settings.key === 'NAGYKER') {
  theme = {
    dropdownChevronColor: colors.dodgerBlueLight,
  };
}

export default function Dropdown(props) {
  let { options = [], placeholder = 'Rendezés', label, error, width, height, onSelect = () => {}, disabled } = props;

  let listRef = useRef();
  let [open, setOpen] = useState(false);
  let [selected, setSelected] = useState();

  useEffect(() => {
    let selectedOption = options.find((option) => option.selected === true);

    if (selectedOption) {
      setSelected(selectedOption);
    }
  }, [options]);

  // Global click
  useEffect(() => {
    function handleGlobalClick(e) {
      if (!e.target.closest(`.${listRef.current?.className.replace(' ', '.')}`)) {
        setOpen(false);
      }
    }

    document.addEventListener('click', handleGlobalClick);

    return () => {
      document.removeEventListener('click', handleGlobalClick);
    };
  }, []);

  return (
    <DropdownWrapper open={open} width={width} ref={listRef}>
      {label && <Label error={error}>{label}</Label>}
      <Input
        disabled={disabled}
        aria-haspopup="listbox"
        error={error}
        height={height}
        aria-expanded="true"
        onClick={() => (disabled ? '' : setOpen(!open))}
      >
        <InputText disabled={disabled}>{selected ? selected.label : placeholder}</InputText>
        <InputIcon>
          <IconWrapper>
            <Icon type="chevron-right" iconWidth="10px" iconColor={disabled ? colors.mischka : theme.dropdownChevronColor}></Icon>
          </IconWrapper>
        </InputIcon>
      </Input>
      {error && !open && <Error error={error}>{error}</Error>}
      <List tabIndex="-1" role="listbox">
        <ListItemsWrapper>
          {options.map((option, index) => (
            <ListItem key={index} role="option" data-text={option.label} onClick={() => handleItemSelect(option)}>
              {option.label}
            </ListItem>
          ))}
        </ListItemsWrapper>
      </List>
    </DropdownWrapper>
  );

  function handleItemSelect(selection) {
    if (open) setOpen(false);
    setSelected(selection);
    onSelect(selection);
  }
}
