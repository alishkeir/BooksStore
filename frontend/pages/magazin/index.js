import dynamic from 'next/dynamic';
import { useState, useEffect, useCallback } from 'react';
import { useQuery, QueryClient } from 'react-query';
import { dehydrate } from 'react-query/hydration';
import { handleApiRequest, getResponseById, getMetadata } from '@libs/api';
import useRequest from '@hooks/useRequest/useRequest';
const Header = dynamic(() => import('@components/header/header'));
const HeaderPromo = dynamic(() => import('@components/headerPromo/headerPromo'));
const Content = dynamic(() => import('@components/content/content'));
const SiteColContainer = dynamic(() => import('@components/siteColContainer/siteColContainer'));
const InputText = dynamic(() => import('@components/inputText/inputText'));
const Dropdown = dynamic(() => import('@components/dropdown/dropdown'));
const MagazineCard = dynamic(() => import('@components/magazineCard/magazineCard'));
const BookListPagination = dynamic(() => import('@components/bookListPagination/bookListPagination'));
const Footer = dynamic(() => import('@components/footer/footer'));
const Button = dynamic(() => import('@components/button/button'));
import useMediaQuery from '@hooks/useMediaQuery/useMediaQuery';
import breakpoints from '@vars/breakpoints';
import Overlay from '@components/overlay/overlay';
import OverlayCard from '@components/overlayCard/overlayCard';

import {
  Controls,
  List,
  ListItem,
  MagazinPageWrapper,
  Pagination,
  SearchControl,
  SearchControlInput,
  SearchControlLabel,
  SortControl,
  SortControlLabel,
  SortControlMonth,
  SortControlYear,
  Title,
  ModalHeader,
  ModalTitle,
  ModaWrapper,
  FilterWrapper,
  ModalFooter,
  FilterButtonWrapper,
} from '@components/pages/magazinPage.styled';
import DynamicHead from '@components/heads/DynamicHead';

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },

  requests: {
    'magazines-get': {
      method: 'GET',
      path: '/pages/posts',
      ref: 'list',
      request_id: 'magazines-get',
      body: {
        filters: {
          search: null,
          year: null,
          month: null,
        },
        page: 1,
      },
    },
  },
};

export default function MagazinPage({metadata}) {
  let defaultFilter = {
    month: null,
    year: null,
    word: '',
  };
  let isMinMd = useMediaQuery(`(min-width: ${breakpoints.min.md})`);
  let [filterModalVisible, setFilterModalVisible] = useState(false);
  let [years, setYears] = useState([]);
  let [availableMonths, setAvailableMonths] = useState([]);
  let [months] = useState([
    {value: 1, label: 'Január'},
    {value: 2, label: 'Február'},
    {value: 3, label: 'Március'},
    {value: 4, label: 'Április'},
    {value: 5, label: 'Május'},
    {value: 6, label: 'Június'},
    {value: 7, label: 'Július'},
    {value: 8, label: 'Augusztus'},
    {value: 9, label: 'Szeptember'},
    {value: 10, label: 'Október'},
    {value: 11, label: 'November'},
    {value: 12, label: 'December'},
  ]);
  let [magazines, setMagazines] = useState([]);
  let [filter, setFilter] = useState(defaultFilter);
  let [pagination, setPagination] = useState({});

  let queryMagazines = useQuery('magazines-get', () => handleApiRequest(magazinesRequest.build()), {
    enabled: false,
    refetchOnWindowFocus: false,
    refetchOnMount: false,
    staleTime: 0,
    onSuccess: (data) => {
      let magazinesGetResponse = getResponseById(data, 'magazines-get');

      if (magazinesGetResponse && magazinesGetResponse.success) {
        if (magazinesGetResponse.body.pagination.current_page > 1) {
          setMagazines([...magazines, ...magazinesGetResponse.body.posts]);
        } else {
          setMagazines([...magazinesGetResponse.body.posts]);
        }
      }
    },
  });

  let magazinesRequest = useRequest(requestTemplates, queryMagazines);
  magazinesRequest.addRequest('magazines-get');

  function createFilterRequest(isSetDefault = false) {
    magazinesRequest.modifyRequest('magazines-get', (currentRequest) => {
      currentRequest.body.filters = {
        search: isSetDefault ? null : filter.word,
        year: isSetDefault ? null : filter.year,
        month: isSetDefault ? null : filter.month,
      };
    });
    magazinesRequest.commit();
  }

  let filterHandler = useCallback((key, value) => {
    setFilter({...filter, [key]: value});
  });

  function formatOptions(list, type) {
    if (type === 'month') {
      return availableMonths.length
        ? list
          .filter((month) => availableMonths.some((availableMonth) => availableMonth.month === month.value))
          .map((filteredMonth) => ({
            ...filteredMonth,
            selected: filter && filter.month === filteredMonth.value ? true : false,
          }))
        : [];
    }
    return list?.map((period) => {
      return {
        label: period[type],
        value: period[type],
        selected: period.selected,
      };
    });
  }

  let handleLoadMoreClick = useCallback(() => {
    magazinesRequest.modifyRequest('magazines-get', (currentRequest) => {
      currentRequest.body.page = pagination.current_page + 1;
    });
    magazinesRequest.commit();
  });

  function clearFilter() {
    createFilterRequest(true);
    setFilter(defaultFilter);
    setFilterModalVisible(false);
  }

  useEffect(() => {
    if (queryMagazines) {
      setYears(queryMagazines.data.response[0].body.available_years);
      setAvailableMonths(queryMagazines.data.response[0].body.available_months);
      setPagination(queryMagazines.data.response[0].body.pagination);
    }
  }, [queryMagazines]);

  useEffect(() => {
    if (filter.word.length >= 3 || filter.word.length === 0) {
      createFilterRequest();
    }
  }, [filter]);

  return (
    <MagazinPageWrapper>
      <DynamicHead metadata={metadata} />
      <Header promo={HeaderPromo}></Header>
      {filterModalVisible && !isMinMd && (
        <Overlay fixed>
          <OverlayCard type={'full'} onClose={() => setFilterModalVisible(false)}>
            <ModaWrapper>
              <ModalHeader>
                <ModalTitle>Szűrők</ModalTitle>
              </ModalHeader>
              <FilterWrapper>
                <Dropdown
                  width="100%"
                  placeholder="Év"
                  options={formatOptions(years, 'year')}
                  onSelect={(value) => {
                    filterHandler('year', value.value);
                  }}
                ></Dropdown>
              </FilterWrapper>
              <Dropdown
                width="100%"
                placeholder="Hónap"
                disabled={!formatOptions(months, 'month').length}
                options={formatOptions(months, 'month')}
                onSelect={(value) => {
                  filterHandler('month', value.value);
                }}
              ></Dropdown>
              <ModalFooter>
                <Button onClick={() => setFilterModalVisible(false)} type="primary" buttonWidth="180px"
                        buttonHeight="50px">
                  Szűrés
                </Button>
                <Button onClick={() => clearFilter()} type="secondary" buttonWidth="96px" buttonHeight="50px">
                  Törlés
                </Button>
              </ModalFooter>
            </ModaWrapper>
          </OverlayCard>
        </Overlay>
      )}
      <Content>
        <SiteColContainer>
          <Title>Magazin</Title>
          <Controls>
            <SearchControl isMobile={isMinMd}>
              <SearchControlLabel>Keresés:</SearchControlLabel>
              <SearchControlInput>
                <InputText
                  name="input-magazine-search"
                  onReset={() => filterHandler('word', '')}
                  onChange={(e) => filterHandler('word', e.target.value)}
                  value={filter.word}
                  button="search"
                  iconColor="green"
                  placeholder="Keresés a hírek között..."
                  reset
                  height={`${!isMinMd ? 40 : 50}`}
                ></InputText>
              </SearchControlInput>
            </SearchControl>
            {isMinMd ? (
              <SortControl>
                <SortControlLabel>Szűrés:</SortControlLabel>
                <SortControlYear>
                  <Dropdown
                    width="100%"
                    placeholder="Év"
                    options={formatOptions(years, 'year')}
                    height="50px"
                    onSelect={(value) => {
                      filterHandler('year', value.value);
                    }}
                  ></Dropdown>
                </SortControlYear>
                <SortControlMonth>
                  <Dropdown
                    disabled={!formatOptions(months, 'month').length}
                    width="100%"
                    placeholder="Hónap"
                    options={formatOptions(months, 'month')}
                    height="50px"
                    onSelect={(value) => {
                      filterHandler('month', value.value);
                    }}
                  ></Dropdown>
                </SortControlMonth>
              </SortControl>
            ) : (
              <FilterButtonWrapper>
                <Button
                  onClick={() => {
                    setFilterModalVisible(true);
                  }}
                  type="secondary"
                  buttonWidth="110px"
                  buttonHeight="40px"
                  icon="sliders"
                  iconWidth="24px"
                  iconHeight="18px"
                ></Button>
              </FilterButtonWrapper>
            )}
          </Controls>
          <List className="row">
            {magazines?.map((magazine) => (
              <ListItem key={magazine.id} className="col-md-6 col-lg-4 col-xxl-3">
                <MagazineCard magazine={magazine}></MagazineCard>
              </ListItem>
            ))}
          </List>
          <Pagination>
            <BookListPagination
              itemLabel={'magazin az összesből'}
              buttonLabel={'További cikkek betöltése'}
              itemCount={magazines.length}
              currentPage={pagination?.current_page}
              lastPage={pagination?.last_page}
              perPage={pagination?.per_page}
              totalItems={pagination?.total}
              onClick={handleLoadMoreClick}
            ></BookListPagination>
          </Pagination>
        </SiteColContainer>
      </Content>
      <Footer></Footer>
    </MagazinPageWrapper>
  );
}

export async function getStaticProps() {
  const queryClient = new QueryClient();

  await queryClient.prefetchQuery('magazines-get', () =>
    handleApiRequest({
      body: {
        request: [requestTemplates.requests['magazines-get']],
      },
    }),
  );

  const metadata = await getMetadata('/magazin');
  return { props: {
    dehydratedState: dehydrate(queryClient),
    metadata: metadata.length > 0 ? metadata[0].data : null
  }, revalidate: 90 };
}