import { useState, useCallback } from 'react';
import Icon from '../icon/icon';
import colors from '../../vars/colors';
import { Button, Error, Input, InputPasswordWrapper, InputWrapper, Label, Subtext } from './inputPassword.styled.js';

export default function InputPassword(props) {
  let { onChange = () => {}, onReveal = () => {}, label, name, placeholder, sub, value = '', error, height = 50 } = props;

  let [revealed, setRevealed] = useState(false);

  let handleRevealClick = useCallback(() => {
    setRevealed(!revealed);
    onReveal();
  });

  return (
    <InputPasswordWrapper error={error}>
      {label && <Label>{label}</Label>}

      <InputWrapper>
        <Input
          type={revealed ? 'text' : 'password'}
          name={name}
          placeholder={placeholder}
          value={value}
          onChange={onChange}
          inputHeight={height}
        ></Input>
        <Button onClick={handleRevealClick}>
          <Icon type="eyeno" iconColor={revealed ? colors.mischka : colors.mineShaft} iconWidth="22px"></Icon>
        </Button>
      </InputWrapper>

      {sub && <Subtext>{sub}</Subtext>}

      {error && <Error>{error}</Error>}
    </InputPasswordWrapper>
  );
}
