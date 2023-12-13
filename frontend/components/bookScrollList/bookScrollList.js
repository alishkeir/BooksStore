import { useEffect, useRef, useState } from 'react';
import SwiperCore, { Scrollbar } from 'swiper';
import { Swiper, SwiperSlide } from 'swiper/react';
import { ErrorBoundary } from 'react-error-boundary';
import ListHeader from '@components/listHeader/listHeader';
import BookCard from '@components/bookCard/bookCard';
import FadeIn from '@components/fadeIn/fadeIn';
import { useResizeObserver } from '@hooks/useResizeObserver';
import { BookScrollListWrapper, ListHeaderWrappers, Lister } from './bookScrollList.styled';

// Installing swiper modules
SwiperCore.use([Scrollbar]);

// Default layout config
let defaultLayoutConfig = {
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

function ErrorFallback({ error }) {
  return (
    <div>
      <p>Nem sikerült elkészítenem ezt a listát :/</p>
      {console.log(error)}
    </div>
  );
}

export default function BookScrollList(props) {
  let { title, titleBorder = true, titleLink, books = [], defaultConfig = defaultLayoutConfig, isCart = false } = props;

  let timeoutRef = useRef();
  let testRef = useRef();
  let scrollbarContainerRef = useRef();
  let [config] = useState({ ...defaultConfig, ...props });
  let [elemSpace, setElemSpace] = useState(30);
  let [elemCount, setElemCount] = useState(0);
  let listerWidth = useResizeObserver(scrollbarContainerRef);

  // Calculating
  useEffect(() => {
    if (!listerWidth) return;

    timeoutRef.current = setTimeout(() => {
      let nextGutter, nextCount;

      config.breakpoints.forEach((breakpoint) => {
        if (listerWidth >= parseInt(breakpoint.width)) {
          nextGutter = breakpoint.gutter;
          nextCount = breakpoint.count;
        }
      });

      if (nextGutter) setElemSpace(nextGutter);
      if (nextCount) setElemCount(nextCount);
    }, 0);

    return () => clearTimeout(timeoutRef.current);
  }, [listerWidth]);

  return (
    <BookScrollListWrapper ref={testRef}>
      <ErrorBoundary FallbackComponent={ErrorFallback}>
        {title && (
          <ListHeaderWrappers>
            <ListHeader title={title} link={titleLink} border={titleBorder}></ListHeader>
          </ListHeaderWrappers>
        )}
        <Lister ref={scrollbarContainerRef}>
          {elemCount > 0 && (
            <FadeIn>
              <Swiper spaceBetween={elemSpace} slidesPerView={elemCount} scrollbar={{ draggable: true }}>
                {books.length > 0 &&
                  books.map((book) => (
                    <SwiperSlide key={book.id}>
                      <BookCard
                        serial={book.rank}
                        isCart={isCart}
                        itemId={book.id}
                        imageSrc={book.cover}
                        title={book.title}
                        author={book.authors && book.authors.split(',').join(', ')}
                        originalPrice={book.price_list}
                        cartPrice={book.price_cart}
                        price={book.price_sale}
                        isNew={book.is_new}
                        slug={book.slug}
                        discount={book.discount_percent}
                        purchaseType={book.state}
                        bookType={book.type === 0 ? 'book' : 'ebook'}
                      ></BookCard>
                    </SwiperSlide>
                  ))}
              </Swiper>
            </FadeIn>
          )}
        </Lister>
      </ErrorBoundary>
    </BookScrollListWrapper>
  );
}
