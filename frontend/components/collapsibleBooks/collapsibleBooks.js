import { useState } from 'react';
import {
  CollapsibleBooksWrapper,
  BooksWrapper,
  LinkIcon,
  LinkIconWrapper,
  ListHeaderWrapper,
  Title,
  BookListWrapper,
  BooksGroupWrapper,
} from './collapsibleBooks.styled';
import BookCard from '@components/bookCard/bookCard';

export default function CollapsibleBooks(props) {
  let { border, title, books } = props;

  let [open, setOpen] = useState(true);

  return (
    <CollapsibleBooksWrapper>
      <ListHeaderWrapper border={border} open={open}>
        <Title>{title}</Title>

        <LinkIconWrapper onClick={() => setOpen(!open)}>
          <LinkIcon open={open} type="chevron-up-small" iconWidth="18px" iconColor="red"></LinkIcon>
        </LinkIconWrapper>
      </ListHeaderWrapper>
      <BookListWrapper className={''}>
        {books?.map((book) => (
          <BooksGroupWrapper key={book.id}>
            <BooksWrapper open={open}>
              <BookCard
                itemId={book.id}
                imageSrc={book.cover}
                title={book.title}
                author={book.authors && book.authors.split(',').join(', ')}
                originalPrice={book.price_list}
                price={book.price_sale}
                isNew={book.is_new}
                slug={book.slug}
                discount={book.discount_percent}
                purchaseType={book.state}
                bookType={book.type === 0 ? 'book' : 'ebook'}
              ></BookCard>
            </BooksWrapper>
          </BooksGroupWrapper>
        ))}
      </BookListWrapper>
    </CollapsibleBooksWrapper>
  );
}
