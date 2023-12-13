import ScrollToTop from '@components/scrollToTop/scrollToTop';

import { ContentWrapper, ContentWrapperEnd } from '@components/content/content.styled';

export default function Content({ children }) {
  return (
    <ContentWrapper>
      <ScrollToTop></ScrollToTop>
      {children}
      <ContentWrapperEnd />
    </ContentWrapper>
  );
}
