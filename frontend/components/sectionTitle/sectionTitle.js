import { SectionTitleComponent } from '@components/sectionTitle/sectionTitle.styled';

export default function SectionTitle({ children, ...rest }) {
  return <SectionTitleComponent {...rest}>{children}</SectionTitleComponent>;
}
