import {
  InputCheckboxWrapper,
  CheckBox,
  Label,
  LabelText,
  LabelIcon,
  LabelIconUncheckedWrapper,
  LabelIconCheckedWrapper,
  Text,
  LabelError,
} from './inputCheckbox.styled.js';

export default function InputCheckbox(props) {
  let { label, disabled, error, checked, onChange = () => {} } = props;

  return (
    <InputCheckboxWrapper {...props}>
      <Label>
        <CheckBox type="checkbox" onChange={handleIconClick} checked={!!checked}></CheckBox>
        <LabelIcon error={error}>
          {!checked && (
            <LabelIconUncheckedWrapper>
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="0.5" y="0.5" width="23" height="23" rx="3.5" stroke="#D6D8E7" />
              </svg>
            </LabelIconUncheckedWrapper>
          )}
          {checked && (
            <LabelIconCheckedWrapper>
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="24" height="24" rx="4" fill="#212121" />
                <path
                  fillRule="evenodd"
                  clipRule="evenodd"
                  d="M10.52 14.6666L17.8799 7L19 8.16672L10.52 17L6 12.2917L7.12005 11.125L10.52 14.6666Z"
                  fill="white"
                />
                <path
                  fillRule="evenodd"
                  clipRule="evenodd"
                  d="M10.52 14.6666L17.8799 7L19 8.16672L10.52 17L6 12.2917L7.12005 11.125L10.52 14.6666Z"
                  fill="url(#paint0_linear)"
                />
                <path
                  fillRule="evenodd"
                  clipRule="evenodd"
                  d="M10.52 14.6666L17.8799 7L19 8.16672L10.52 17L6 12.2917L7.12005 11.125L10.52 14.6666Z"
                  fill="url(#paint1_linear)"
                />
                <defs>
                  <linearGradient id="paint0_linear" x1="12.5" y1="7" x2="12.5" y2="17" gradientUnits="userSpaceOnUse">
                    <stop stopColor="white" />
                    <stop offset="1" stopColor="white" stopOpacity="0" />
                  </linearGradient>
                  <linearGradient id="paint1_linear" x1="12.5" y1="7" x2="12.5" y2="17" gradientUnits="userSpaceOnUse">
                    <stop stopColor="white" />
                    <stop offset="1" stopColor="white" stopOpacity="0" />
                  </linearGradient>
                </defs>
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
    </InputCheckboxWrapper>
  );

  function handleIconClick(e) {
    if (disabled) return;
    onChange(e);
  }
}
