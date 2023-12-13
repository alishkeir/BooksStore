import { useEffect, useState, useRef } from 'react';
import { useQueryClient } from 'react-query';
import { useRouter } from 'next/router';
import uri from 'lil-uri';
import _cloneDeep from 'lodash/cloneDeep';
import _get from 'lodash/get';
import _set from 'lodash/set';
import isArray from 'lodash/isArray';
import isNull from 'lodash/isNull';
import Dropdown from '@components/dropdown/dropdown';
import Overlay from '@components/overlay/overlay';
import MobileBooklistFilters from '@components/mobileBooklistFilters/mobileBooklistFilters';
import OverlayCard from '@components/overlayCard/overlayCard';
import Button from '@components/button/button';
import BookCard from '@components/bookCard/bookCard';
import FilterCheckBlock from '@components/filterCheckBlock/filterCheckBlock';
import BookListPagination from '@components/bookListPagination/bookListPagination';
import FilterTagBlock from '@components/filterTagBlock/filterTagBlock';
import { getResponseById, getRequestById } from '@libs/api';
import ImageNoHit from '@assets/images/elements/booklist-no-hit.svg';
import {
  ActionDropdownWrapper,
  ActionWrapper,
  ActionWrapperLabel,
  Actions,
  BookListerWrapper,
  Books,
  Column,
  Content,
  FilterBlockWrapper,
  Filters,
  FiltersBlocks,
  FiltersTitle,
  Lister,
  MobileFiltersIconWrapper,
  Nohit,
  NohitImage,
  NohitText,
  PaginantionWrapper,
  Row,
  SortByWrapper,
} from '@components/bookLister/bookLister.styled';

export default function BookLister(props) {
  let { bookListQuery, subcategoryQuery, queryRef, mobileFiltersQueryRef, productsList, requestId, mobileRequestId, baseURL, pageUrl, config } =
    props;

  let subcategoryQuerySnapshotRef = useRef();
  let mobileFiltersQuerySnapshotRef = useRef();
  let router = useRouter();
  let queryClient = useQueryClient();
  let [showPagination, setShowPagination] = useState(true);
  let [mobileFilterOpen, setMobileFilterOpen] = useState(false);

  // Rebuilding query object on URL change
  useEffect(() => {
    function handleRouterChange(path) {
      if (path.includes(baseURL)) {
        let lilUri = uri(path);

        let newQuery = queryFromURL(config.controls.parameters, lilUri, queryRef);
        queryRef.current = newQuery;

        invalidateQuery(requestId);
      }
    }

    router.events.on('routeChangeComplete', handleRouterChange);

    return () => {
      bookListQuery.remove();
      router.events.off('routeChangeComplete', handleRouterChange);
    };
  }, []);

  // Hiding pagination if fewer hits
  useEffect(() => {
    let pagination = getResponseById(bookListQuery.data, requestId)?.body.pagination;
    if (!pagination) return;

    if (pagination.last_page && pagination.current_page === 1) {
      if (showPagination) setShowPagination(false);
    } else {
      if (!showPagination) setShowPagination(true);
    }
  }, [bookListQuery.data]);

  function queryFromURL(configParameters, lilUri, queryRef) {
    let clonedQuery = _cloneDeep(queryRef.current);
    let clonedQueryBooklist = getRequestById(clonedQuery, requestId);
    let uriData = {};

    uriData.param = lilUri.query();
    uriData.path = lilUri
      .path()
      .split('/')
      .filter((segment) => segment.length > 0);

    configParameters.forEach((param) => {
      let paramValue;

      if (param.type === 'param') {
        paramValue = uriData.param ? uriData.param[param.name] : null;
      } else if (param.type === 'path') {
        paramValue = uriData.path ? uriData.path[param.pathIndex] : null;
      }

      if (param.valueType === 'number') {
        if (!param.optional) {
          let setValue = paramValue ? paramValue : param.defaultValue ? param.defaultValue : null;
          _set(clonedQueryBooklist.body, param.path, setValue);
        }
      } else if (param.valueType === 'string') {
        let setValue;

        if (isArray(paramValue)) {
          setValue = paramValue.join('+');
        } else {
          setValue = paramValue ? paramValue : null;
        }

        _set(clonedQueryBooklist.body, param.path, setValue);
      } else if (param.valueType === 'array') {
        let setValue;

        if (!isArray(paramValue)) {
          setValue = paramValue ? [paramValue] : null;
        } else {
          setValue = paramValue ? (paramValue.length < 1 ? null : paramValue) : null;
        }

        _set(clonedQueryBooklist.body, param.path, setValue);
      }
    });

    return clonedQuery;
  }

  // Populating Dropdown options
  let dropdownOptions = getResponseById(bookListQuery.data, requestId).body.sort_by.map((item) => {
    return { value: item.slug, label: item.title, selected: item.selected };
  });

  function handleSortBySelect(props) {
    // Changing the request builder object
    let requestData = getRequestById(queryRef.current, requestId);
    requestData.body.page = 1;
    requestData.body.sort_by = props.value;

    // Optimistic RQ update of dropdowns
    queryClient.setQueryData(requestId, (oldData) => {
      let clonedOldData = _cloneDeep(oldData);
      let clonedOldDataBooklist = getResponseById(clonedOldData, requestId);
      let newSortBy = clonedOldDataBooklist.body.sort_by.map((item) =>
        item.slug === props.value ? { ...item, selected: true } : { ...item, selected: false },
      );
      clonedOldDataBooklist.body.sort_by = newSortBy;
      clonedOldData.optimistic = true;

      return clonedOldData;
    });

    buildURL();
  }

  function handleSortByFilter(props) {
    // Changing the request builder object
    let requestData = getRequestById(queryRef.current, requestId);
    requestData.body.page = 1;

    function getRequestFilterValue(props, request) {
      let newValue;

      // Creating new filter value based on type
      if (props.type === 'radio' || props.type === 'tag') {
        // Add or remove value
        newValue = props.value === request ? null : props.value;
      } else if (props.type === 'checkbox') {
        // Checkbox can hold multiple values

        if (isNull(request)) {
          newValue = [props.value];
        } else {
          if (request.includes(props.value)) {
            newValue = request.filter((value) => value !== props.value);
          } else {
            newValue = [...request, props.value];
          }
        }

        // If array is empty we transfor it to null
        if (newValue.length < 1) newValue = null;
      }
      return newValue;
    }

    requestData.body.filters[props.id] = getRequestFilterValue(props, requestData.body.filters[props.id]);

    // On any category change subcategory is reseted
    if (props.id === 'category') {
      requestData.body.filters.subcategory = null;
    }

    // Optimistic RQ update of filters
    queryClient.setQueryData(requestId, (oldData) => {
      let clonedOldData = _cloneDeep(oldData);
      let clonedOldDataBooklist = getResponseById(clonedOldData, requestId);

      clonedOldDataBooklist.body.filters.forEach((filter) => {
        if (filter.id === props.id) {
          if (props.type === 'radio' || props.type === 'tag') {
            filter.data.forEach((filterData) => {
              filterData.selected = filterData.slug === props.value ? !filterData.selected : false;
            });
          } else if (props.type === 'checkbox') {
            filter.data.forEach((filterData) => {
              filterData.selected = filterData.slug === props.value ? !filterData.selected : filterData.selected;
            });
          }
        }
      });

      clonedOldData.optimistic = true;
      return clonedOldData;
    });

    buildURL();
  }

  function handleLoadMore() {
    // Changing the request builder object
    let requestData = getRequestById(queryRef.current, requestId);
    requestData.body.page = Number(requestData.body.page) + 1;

    buildURL();
  }

  function buildURL() {
    let url = queryToULR(getRequestById(queryRef.current, requestId).body, config.controls.parameters, baseURL);
    router.push(url, '', { shallow: true });
  }

  function invalidateQuery(query) {
    queryClient.invalidateQueries(query);
  }

  function queryToULR(query, config, base) {
    let paths = [];
    let params = {};

    config.forEach((configItem) => {
      if (configItem.optional) return;

      // Item dependency
      if (configItem.depends) {
        let dependencySatisfied = true;

        configItem.depends.forEach((dependency) => {
          if (isNull(_get(query, dependency))) dependencySatisfied = false;
        });

        if (!dependencySatisfied) return;
      }

      let queryItemValue = _get(query, configItem.path);

      if (!isNull(queryItemValue)) {
        if (configItem.type === 'param') {
          params[configItem.name] = queryItemValue;
        } else if (configItem.type === 'path') {
          paths[configItem.pathIndex] = queryItemValue;
        }
      }
    });

    // Removing empty elements
    paths = paths.filter((el) => {
      return el != null && el != '';
    });

    let url = uri();
    url.path(`${base}${paths.length > 0 ? '/' + paths.join('/') : ''}`);
    Object.keys(params).length > 0 && url.query(params);

    return url.build();
  }

  function handleMobileFilterSubmit(mobileFiltersQueryRef) {
    let listerFilters = getRequestById(queryRef.current, requestId);
    let mobileListerFilters = getRequestById(mobileFiltersQueryRef.current, mobileRequestId);
    listerFilters.body.filters = _cloneDeep(mobileListerFilters.body.filters);

    setMobileFilterOpen(false);
    buildURL();
  }

  // We need to save in case user closes without commiting

  function handleMobileFilterOpen() {
    // We save query data
    subcategoryQuerySnapshotRef.current = _cloneDeep(queryClient.getQueryData(mobileRequestId));

    // We save request data
    mobileFiltersQuerySnapshotRef.current = _cloneDeep(mobileFiltersQueryRef.current);

    setMobileFilterOpen(true);
  }

  // We restore snapshots after closing by X
  function handleMobileFilterClose() {
    // Restoring query data
    queryClient.setQueryData(mobileRequestId, subcategoryQuerySnapshotRef.current);

    // Restoring request data
    mobileFiltersQueryRef.current = mobileFiltersQuerySnapshotRef.current;

    setMobileFilterOpen(false);
  }

  // We restore snapshots after deleting
  function handleMobileFilterReset() {
    // We clone query data and reset it
    let mobileRequestIdClone = _cloneDeep(queryClient.getQueryData(mobileRequestId));

    let mobileRequestIdCloneSubcat = getResponseById(mobileRequestIdClone, 'mobile-subcat');

    mobileRequestIdCloneSubcat.body.filters.forEach((filter) => {
      if (filter.data) {
        if (filter.id === 'subcategory') {
          filter.data = null;
        } else {
          filter.data.forEach((filterData) => (filterData.selected = false));
        }
      }
    });

    // We clone request data and reset it
    let mobileFiltersQueryRefClone = _cloneDeep(mobileFiltersQueryRef.current);

    let mobileFiltersQueryRefCloneSubcat = getRequestById(mobileFiltersQueryRefClone, 'mobile-subcat');

    Object.keys(mobileFiltersQueryRefCloneSubcat.body.filters).forEach((key) => (mobileFiltersQueryRefCloneSubcat.body.filters[key] = null));

    // Reseting query data
    queryClient.setQueryData(mobileRequestId, mobileRequestIdClone);

    // Reseting request data
    mobileFiltersQueryRef.current = mobileFiltersQueryRefClone;
  }

  return (
    <BookListerWrapper>
      {mobileFilterOpen && (
        <Overlay zIndex={999}>
          <OverlayCard type="full" onClose={handleMobileFilterClose}>
            <MobileBooklistFilters
              onSubmit={handleMobileFilterSubmit}
              onReset={handleMobileFilterReset}
              requestId={mobileRequestId}
              mobileFiltersQueryRef={mobileFiltersQueryRef}
              filters={getResponseById(subcategoryQuery.data, mobileRequestId)?.body.filters}
              baseURL={baseURL}
              pageUrl={pageUrl}
              config={config}
            ></MobileBooklistFilters>
          </OverlayCard>
        </Overlay>
      )}
      <Lister>
        <Actions>
          <SortByWrapper>
            <ActionWrapper>
              <ActionWrapperLabel className="d-none d-xl-block">Rendezés:</ActionWrapperLabel>
              <ActionDropdownWrapper>
                <Dropdown width="100% " options={dropdownOptions} onSelect={handleSortBySelect}></Dropdown>
              </ActionDropdownWrapper>
            </ActionWrapper>
          </SortByWrapper>
          <MobileFiltersIconWrapper className="d-block d-md-none">
            <ActionWrapper>
              <Button
                type="secondary"
                icon="sliders"
                iconWidth="23px"
                iconHeight="16px"
                buttonWidth="110px"
                onClick={handleMobileFilterOpen}
              ></Button>
            </ActionWrapper>
          </MobileFiltersIconWrapper>
        </Actions>
        <Content>
          <Filters>
            <FiltersTitle>Szűrők</FiltersTitle>
            <FiltersBlocks>
              {bookListQuery.data?.response[0].body.filters.map((filter) => {
                return filter.data ? (
                  <FilterBlockWrapper key={filter.id}>
                    {(filter.type === 'checkbox' || filter.type === 'radio') && (
                      <FilterCheckBlock
                        id={filter.id}
                        title={filter.title}
                        type={filter.type}
                        categories={filter.data}
                        onSelect={handleSortByFilter}
                        baseURL={baseURL}
                        pageUrl={pageUrl}
                        config={config}
                      ></FilterCheckBlock>
                    )}
                    {filter.type === 'tag' && (
                      <FilterTagBlock
                        id={filter.id}
                        title={filter.title}
                        type={filter.type}
                        categories={filter.data}
                        onSelect={handleSortByFilter}
                        baseURL={baseURL}
                        pageUrl={pageUrl}
                        config={config}
                      ></FilterTagBlock>
                    )}
                  </FilterBlockWrapper>
                ) : null;
              })}
            </FiltersBlocks>
          </Filters>
          <Books>
            <Row className="row">
              {productsList?.length <= 0 && (
                <Nohit>
                  <NohitImage>
                    <ImageNoHit />
                  </NohitImage>
                  <NohitText>Nincs a keresésnek megfelelő találat.</NohitText>
                </Nohit>
              )}
              {productsList?.length > 0 &&
                productsList?.map((product) => {
                  return (
                    <Column className="col-6 col-lg-4 col-xl-3" key={product.id}>
                      <BookCard
                        prefetch={false}
                        imageSrc={product.cover}
                        title={product.title}
                        author={product?.authors?.split(',').join(', ')}
                        originalPrice={product.price_list}
                        price={product.price_sale}
                        isNew={product.is_new}
                        slug={product.slug}
                        itemId={product.id}
                        discount={product.discount_percent}
                        purchaseType={product.state}
                        bookType={product.type === 0 ? 'book' : 'ebook'}
                      ></BookCard>
                    </Column>
                  );
                })}
            </Row>
            {showPagination && (
              <PaginantionWrapper>
                <BookListPagination
                  itemCount={productsList.length}
                  currentPage={bookListQuery.data?.response[0].body.pagination.current_page}
                  lastPage={bookListQuery.data?.response[0].body.pagination.last_page}
                  perPage={bookListQuery.data?.response[0].body.pagination.per_page}
                  totalItems={bookListQuery.data?.response[0].body.pagination.total}
                  onClick={handleLoadMore}
                  loading={bookListQuery.isFetching}
                  pageUrl={pageUrl}
                ></BookListPagination>
              </PaginantionWrapper>
            )}
          </Books>
        </Content>
      </Lister>
    </BookListerWrapper>
  );
}
