import Button from '@components/button/button';
import {
  Actions,
  ButtonWrapper,
  OverlayCardContentGeneralComponent,
  Text,
  Title,
} from '@components/overlayCardContentGeneral/overlayCardContentGeneral.styled';

export default function OverlayCardContentGeneral(props) {
  let { title, text, submitText, cancelText, onSubmit, onCancel } = props;

  return (
    <OverlayCardContentGeneralComponent>
      {title && <Title>{title}</Title>}
      {text && <Text>{text}</Text>}
      <Actions>
        <ButtonWrapper>
          <Button type="secondary" buttonHeight="50px" buttonWidth="100%" onClick={onCancel}>
            {cancelText}
          </Button>
        </ButtonWrapper>
        <ButtonWrapper>
          <Button buttonHeight="50px" buttonWidth="100%" onClick={onSubmit}>
            {submitText}
          </Button>
        </ButtonWrapper>
      </Actions>
    </OverlayCardContentGeneralComponent>
  );
}
