import React, { useMemo } from 'react';
import Button from '@components/button/button';
import lilUri from 'lil-uri';
import { Actions, BookListPaginationWrapper, InfoText, ProgressBar, ProgressBarLine, ProgressBarWrapper } from './bookListPagination.styled';

export default React.memo(function BookListPagination(props) {
  let {
    pageUrl,
    itemLabel = 'Könyv az összesből',
    buttonLabel = 'További könyvek betöltése',
    loading,
    itemCount = 0,
    currentPage = 0,
    lastPage = false,
    perPage = 0,
    totalItems = 0,
    onClick = () => {},
  } = props;

  let shownBookAmount = perPage * currentPage;
  let progress = shownBookAmount / (totalItems / 100);
  let resolvedUrl = process.browser ? window.location.href : pageUrl;

  let linkUrl = useMemo(() => {
    let currentUri = lilUri(process.browser ? window.location.href : pageUrl);
    let uriQuery = currentUri.query();

    let newUri = lilUri();
    newUri.path(currentUri.path());

    newUri.query(uriQuery?.p ? { ...uriQuery, p: Number(uriQuery.p) + 1 } : { ...uriQuery, p: 2 });

    return newUri.build();
  }, [resolvedUrl]);

  return (
    <BookListPaginationWrapper>
      <InfoText>
        {itemCount} {itemLabel} ({totalItems})
      </InfoText>
      <ProgressBarWrapper>
        <ProgressBar progress={progress}>
          <ProgressBarLine role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></ProgressBarLine>
        </ProgressBar>
      </ProgressBarWrapper>
      {!lastPage && (
        <Actions>
          {pageUrl && (
            <a href={linkUrl} onClick={(e) => e.preventDefault()}>
              <Button buttonHeight="50px" buttonWidth="280px" type="secondary" onClick={onClick} loading={loading}>
                {buttonLabel}
              </Button>
            </a>
          )}
          {!pageUrl && (
            <Button buttonHeight="50px" buttonWidth="280px" type="secondary" onClick={onClick} loading={loading}>
              {buttonLabel}
            </Button>
          )}
        </Actions>
      )}
    </BookListPaginationWrapper>
  );
});
