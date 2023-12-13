import { PageTitleWrapper } from '@components/pageTitle/pageTitle.styled';

export default function PageTitle({ children, ...rest }) {
  return <PageTitleWrapper {...rest}>{children}</PageTitleWrapper>;
}
