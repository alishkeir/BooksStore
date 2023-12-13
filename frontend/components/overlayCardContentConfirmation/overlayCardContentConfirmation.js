import Button from '@components/button/button';
import {
  Actions,
  ButtonWrapper,
  OverlayCardContentConfirmationComponent,
  Title,
} from '@components/overlayCardContentConfirmation/overlayCardContentConfirmation.styled';

export default function OverlayCardContentConfirmation(props) {
  let { title, submitText, cancelText, onSubmit = () => {}, onCancel = () => {} } = props;

  return (
    <OverlayCardContentConfirmationComponent>
      <Title>{title}</Title>
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
    </OverlayCardContentConfirmationComponent>
  );
}
