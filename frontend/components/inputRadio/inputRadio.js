import {
  InputRadioComponent,
  CheckBox,
  Label,
  LabelText,
  LabelIcon,
  LabelIconUncheckedWrapper,
  LabelIconCheckedWrapper,
  Text,
  LabelError,
} from './inputRadio.styled.js';

export default function InputRadio(props) {
  let { label, name = '', disabled, error, checked, onChange = () => {} } = props;

  return (
    <InputRadioComponent {...props}>
      <Label>
        <CheckBox type="radio" name={name} onChange={handleIconClick} checked={!!checked}></CheckBox>
        <LabelIcon error={error}>
          {!checked && (
            <LabelIconUncheckedWrapper>
              <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="0.5" y="0.5" width="19" height="19" rx="9.5" stroke="#D6D8E7" />
              </svg>
            </LabelIconUncheckedWrapper>
          )}
          {checked && (
            <LabelIconCheckedWrapper>
              <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="1" y="1" width="18" height="18" rx="9" stroke="#353535" strokeWidth="2" />
                <rect x="5" y="5" width="10" height="10" rx="5" fill="#353535" />
              </svg>
            </LabelIconCheckedWrapper>
          )}
        </LabelIcon>
        {(label || error) && (
          <Text>
            {label && <LabelText dangerouslySetInnerHTML={{ __html: label }}></LabelText>}
            {error && <LabelError>{error}</LabelError>}
          </Text>
        )}
      </Label>
    </InputRadioComponent>
  );

  function handleIconClick(e) {
    if (disabled) return;
    onChange(e);
  }
}
