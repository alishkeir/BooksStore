import { useQueryClient } from 'react-query';
import isNull from 'lodash/isNull';
import Button from '@components/button/button';
import FilterCheckBlock from '@components/filterCheckBlock/filterCheckBlock';
import FilterTagBlock from '@components/filterTagBlock/filterTagBlock';
import { getRequestById } from '@libs/api';
import {
  Actions,
  ActionsWrapper,
  Content,
  ContentWrapper,
  FilterBlockWrapper,
  MobileFiltersWrapper,
  ResetActionWrapper,
  SubmitActionWrapper,
  Title,
} from './mobileBooklistFilters.styled';

export default function MobileFilters(props) {
  let { filters, mobileFiltersQueryRef, requestId, baseURL, pageUrl, config, onSubmit = () => {}, onReset = () => {} } = props;

  let queryClient = useQueryClient();

  function handleMobileFilterCategorySelect(props) {
    let requestData = getRequestById(mobileFiltersQueryRef.current, requestId);

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

    queryClient.invalidateQueries('mobile-subcat');
  }

  return (
    <MobileFiltersWrapper>
      <Title>Szűrők</Title>
      <Content>
        <ContentWrapper>
          {filters?.map((filter) => {
            return (
              filter.data && (
                <FilterBlockWrapper key={filter.id}>
                  {(filter.type === 'checkbox' || filter.type === 'radio') && (
                    <FilterCheckBlock
                      id={filter.id}
                      type={filter.type}
                      title={filter.title}
                      categories={filter.data}
                      onSelect={handleMobileFilterCategorySelect}
                      baseURL={baseURL}
                      pageUrl={pageUrl}
                      config={config}
                    ></FilterCheckBlock>
                  )}
                  {filter.type === 'tag' && (
                    <FilterTagBlock
                      id={filter.id}
                      type={filter.type}
                      title={filter.title}
                      categories={filter.data}
                      onSelect={handleMobileFilterCategorySelect}
                      baseURL={baseURL}
                      pageUrl={pageUrl}
                      config={config}
                    ></FilterTagBlock>
                  )}
                </FilterBlockWrapper>
              )
            );
          })}
        </ContentWrapper>
      </Content>
      <Actions>
        <ActionsWrapper>
          <SubmitActionWrapper>
            <Button buttonWidth="100%" buttonHeight="50px" onClick={() => onSubmit(mobileFiltersQueryRef)}>
              Szűrés
            </Button>
          </SubmitActionWrapper>
          <ResetActionWrapper>
            <Button buttonWidth="100%" buttonHeight="50px" type="secondary" onClick={onReset}>
              Törlés
            </Button>
          </ResetActionWrapper>
        </ActionsWrapper>
      </Actions>
    </MobileFiltersWrapper>
  );
}
