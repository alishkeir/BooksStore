import InputRadio from '@components/inputRadio/inputRadio';
import { InputRadioBlockComponent, Label, LabelsWrapper, RadioWrapper, Sublabel } from '@components/inputRadioBlock/inputRadioBlock.styled';

export default function InputRadioBlock(props) {
  let { label, sublabel, ...rest } = props;

  return (
    <InputRadioBlockComponent {...rest}>
      <RadioWrapper>
        <InputRadio {...rest}></InputRadio>
      </RadioWrapper>
      <LabelsWrapper>
        {label && <Label>{label}</Label>}
        {sublabel && <Sublabel>{sublabel}</Sublabel>}
      </LabelsWrapper>
    </InputRadioBlockComponent>
  );
}
