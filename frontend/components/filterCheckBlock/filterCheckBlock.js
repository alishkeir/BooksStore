import { useState, useCallback } from 'react';
import InputCheckbox from '@components/inputCheckbox/inputCheckbox';
import FilterBlockTitle from '@components/filterBlockTitle/filterBlockTitle';
import { buildLink } from '@libs/bookfilter';
import { Checkbox, FilterCheckBlockWrapper, Item, Label, Options } from './filterCheckBlock.styled';

export default function FilterCheckBlock(props) {
  let { categories = [], collapsible, defaultCollapsed, title, id, type, baseURL, pageUrl, config, onSelect = () => {} } = props;

  let [collapsed, setCollapsed] = useState(defaultCollapsed ? true : false);
  let resolvedUrl = process.browser ? window.location.href : pageUrl;

  let linkUrl = useCallback((id, value, baseURL, pageUrl, config) => buildLink(id, value, baseURL, pageUrl, config), [resolvedUrl]);

  return (
    <FilterCheckBlockWrapper collapsed={collapsed}>
      <FilterBlockTitle title={title} collapsible={collapsible} collapsed={collapsed} onCollapse={handleCollapse}></FilterBlockTitle>
      <Options>
        {categories &&
          categories.map((category) => (
            <Item key={category.slug} onClick={() => onSelect({ id, type, value: category.slug })}>
              <Checkbox>
                <InputCheckbox checked={category.selected}></InputCheckbox>
              </Checkbox>
              <Label>
                <a href={linkUrl(id, category.slug, baseURL, pageUrl, config)} onClick={(e) => e.preventDefault()}>
                  {category.title}
                </a>
              </Label>
            </Item>
          ))}
      </Options>
    </FilterCheckBlockWrapper>
  );

  function handleCollapse() {
    if (!collapsible) return;
    setCollapsed(!collapsed);
  }
}
