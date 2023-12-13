import { InputWrapper, Label, Input, Subtext, Error } from './inputTextarea.styled.js';

export default function InputText({ onChange = () => {}, label, placeholder, sub, value = '', name, error, height = 75 }) {
  return (
    <InputWrapper error={error}>
      {label && <Label>{label}</Label>}

      <Input name={name} placeholder={placeholder} value={value} onChange={onChange} inputHeight={height}></Input>

      {sub && <Subtext>{sub}</Subtext>}

      {error && <Error>{error}</Error>}
    </InputWrapper>
  );
}
