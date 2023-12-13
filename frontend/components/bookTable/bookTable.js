import { useState, useEffect, useRef } from 'react';
import { BookTableWrapper, BookWrapper, Container, Row } from '@components/bookTable/bookTable.styled';
import { useResizeObserver } from '@hooks/useResizeObserver';
import FadeIn from '@components/fadeIn/fadeIn';

// Default layout config
let defaultConfig = {
  breakpoints: [
    {
      width: 0,
      gutter: 20,
      count: 1,
    },
    {
      width: 300,
      gutter: 20,
      count: 2,
    },
    {
      width: 700,
      gutter: 20,
      count: 3,
    },
    {
      width: 900,
      gutter: 20,
      count: 4,
    },
    {
      width: 1200,
      gutter: 30,
      count: 5,
    },
  ],
};

export default function BookTable(props) {
  let { children } = props;

  let tableTimeoutRef = useRef();
  let bookTableRef = useRef();
  let tableWidth = useResizeObserver(bookTableRef);
  let [config] = useState({ ...defaultConfig, ...props });
  let [elemSpace, setElemSpace] = useState(30);
  let [elemWidth, setElemWidth] = useState(0);

  // Calculating
  useEffect(() => {
    if (!tableWidth) return;

    tableTimeoutRef.current = setTimeout(() => {
      let nextGutter, nextCount;

      config.breakpoints.forEach((breakpoint) => {
        if (tableWidth >= parseInt(breakpoint.width)) {
          nextGutter = breakpoint.gutter;
          nextCount = breakpoint.count;
        }
      });

      if (nextGutter) setElemSpace(nextGutter);
      if (nextCount) setElemWidth(100 / nextCount);
    }, 0);

    return () => {
      clearTimeout(tableTimeoutRef.current);
    };
  }, [tableWidth]);

  return (
    <BookTableWrapper ref={bookTableRef} spaceBetween={elemSpace} elemWidth={elemWidth}>
      {elemWidth > 0 && (
        <FadeIn>
          <Container>
            <Row>{children?.length ? children.map((child) => <BookWrapper key={child.key}>{child}</BookWrapper>) : children}</Row>
          </Container>
        </FadeIn>
      )}
    </BookTableWrapper>
  );
}
