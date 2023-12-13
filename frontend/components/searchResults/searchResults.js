import Link from 'next/link';
import Icon from '@components/icon/icon';
import colors from '@vars/colors';
import { SearchResultsComponent, ResultGroup, ResultTitle, ResultList, ResultListItem, ResultLink, ResultLinkText } from './searchResults.styled';
import settingsVars from "@vars/settingsVars";
import url from "@libs/url";

export default function SearchResults(props) {
    let settings = settingsVars.get(url.getHost());

    let { books, eBooks, authors, searchTerm, inputLengthValid } = props;

  if (!inputLengthValid)
    return (
      <SearchResultsComponent>
        <ResultGroup>
          <ResultList>
            <ResultListItem>Adj meg legalább 3 karaktert</ResultListItem>
          </ResultList>
        </ResultGroup>
      </SearchResultsComponent>
    );

  return (
    <SearchResultsComponent>
      <ResultGroup>
        <ResultTitle>Könyvek</ResultTitle>
        <ResultList>
          {books && books.length <= 0 && <ResultListItem>Nem találtam könyvet</ResultListItem>}
          {books?.map((book) => (
            <Link
              key={book.id}
              href={`/konyv/${book.slug}`}
              prefetch={false}
              passHref
              legacyBehavior>
              <ResultListItem>{book.title}</ResultListItem>
            </Link>
          ))}
        </ResultList>
      </ResultGroup>
      {settings.key === 'ALOMGYAR' && (
        <ResultGroup>
          <ResultTitle>E-könyvek</ResultTitle>
          <ResultList>
            {eBooks && eBooks.length <= 0 && <ResultListItem>Nem találtam e-könyvet</ResultListItem>}
            {eBooks?.map((eBook) => (
              <Link
                key={eBook.id}
                href={`/konyv/${eBook.slug}`}
                prefetch={false}
                passHref
                legacyBehavior>
                <ResultListItem>{eBook.title}</ResultListItem>
              </Link>
            ))}
          </ResultList>
        </ResultGroup>
      )}
      <ResultGroup>
        <ResultTitle>Szerzők</ResultTitle>
        <ResultList>
          {authors && authors.length <= 0 && <ResultListItem>Nem találtam szerzőt</ResultListItem>}
          {authors?.map((author) => (
            <Link
              key={author.id}
              href={`/szerzo/${author.slug}`}
              prefetch={false}
              passHref
              legacyBehavior>
              <ResultListItem>{author.title}</ResultListItem>
            </Link>
          ))}
        </ResultList>
      </ResultGroup>
      <Link
        href={{ pathname: '/kereses', query: { q: searchTerm } }}
        prefetch={false}
        passHref
        legacyBehavior>
        <ResultLink>
          <Icon iconWidth="18px" iconColor={colors.monza}></Icon>
          <ResultLinkText>Összes találat</ResultLinkText>
        </ResultLink>
      </Link>
    </SearchResultsComponent>
  );
}
