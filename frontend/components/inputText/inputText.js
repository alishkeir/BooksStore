import Icon from '../../components/icon/icon';
import colors from '../../vars/colors';
import { InputWrapper, Label, Input, Button, Subtext, Error } from './inputText.styled.js';

export default function InputText({
  onChange = () => {},
  onReset = () => {},
  label,
  placeholder,
  button,
  type = 'text',
  reset,
  name,
  buttonColor = colors.ghost,
  sub,
  value = '',
  error,
  height = 50,
  readOnly,
}) {
  return (
    <>
      <InputWrapper error={error}>
        {label && <Label>{label}</Label>}

        <Input
          type={type}
          button={button || reset}
          placeholder={placeholder}
          value={value}
          onChange={onChange}
          inputHeight={height}
          name={name}
          readOnly={readOnly}
        ></Input>

        {button && (
          <>
            {reset && !value && (
              <Button>
                <Icon type={button} iconColor={buttonColor} iconWidth="18px"></Icon>
              </Button>
            )}
            {!reset && (
              <Button>
                <Icon type={button} iconColor={buttonColor} iconWidth="18px"></Icon>
              </Button>
            )}
          </>
        )}

        {reset && value && (
          <Button onClick={onReset}>
            <Icon type="ex-thin" iconColor={buttonColor} iconWidth="24px"></Icon>
          </Button>
        )}
      </InputWrapper>
      {sub && <Subtext>{sub}</Subtext>}

      {error && <Error>{error}</Error>}
    </>
  );
}
