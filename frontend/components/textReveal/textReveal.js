import { useEffect, useRef, useState } from 'react';
import { Content, ContentGradient, ContentWrapper, Opener, TextRevealWrapper } from './textReveal.styled';

export default function TextReveal(props) {
  let { children, height } = props;
  // If setHtml is true we render the children as html
  const setHtml = props.setHtml === undefined ? true : props.setHtml;

  let contentRef = useRef();
  let [open, setOpen] = useState(true);

  // If content is bigger than  expected we truncate
  useEffect(() => {
    if (!height) return;

    if (contentRef.current.getBoundingClientRect().height > height) {
      if (open) setOpen(false);
    } else {
      if (!open) setOpen(true);
    }
  }, [children]);

  let wrapperHeight = open ? 'auto' : `${height ? height : 300}px`;

  return (
    <TextRevealWrapper style={{ '--reveal-height': wrapperHeight }} open={open}>
      <ContentWrapper onClick={handleClick}>
        {setHtml ? (
          <Content ref={contentRef} dangerouslySetInnerHTML={{ __html: children }}></Content>
        ) : (
          <Content ref={contentRef}>{children}</Content>
        )}
        <ContentGradient></ContentGradient>
      </ContentWrapper>
      <Opener onClick={handleClick}>Több mutatása</Opener>
    </TextRevealWrapper>
  );

  function handleClick() {
    if (!open) setOpen(true);
  }
}