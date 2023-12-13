import { ErrorWrapperLi, ErrorWrapperUl, SideModalErrorWrapper } from '@components/sideModalError/sideModalError.styled';

export default function SideModalError({ responseErrors }) {
  return (
    <SideModalErrorWrapper>
      <ErrorWrapperUl>
        {responseErrors.map((error, index) => (
          <ErrorWrapperLi key={index}>{error}</ErrorWrapperLi>
        ))}
      </ErrorWrapperUl>
    </SideModalErrorWrapper>
  );
}
