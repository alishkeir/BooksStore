import { AlertBoxComponent, ErrorWrapperLi, ErrorWrapperUl } from '@components/alertBox/alertBox.styled';

export default function AlertBox({ responseErrors, className }) {
  if (!responseErrors) return null;

  return (
    <AlertBoxComponent className={className}>
      <ErrorWrapperUl>
        {responseErrors.map((error, index) => (
          <ErrorWrapperLi key={index}>{error}</ErrorWrapperLi>
        ))}
      </ErrorWrapperUl>
    </AlertBoxComponent>
  );
}
