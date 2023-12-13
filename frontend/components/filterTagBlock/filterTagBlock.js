import { useState, useCallback } from 'react';
import FilterBlockTitle from '@components/filterBlockTitle/filterBlockTitle';
import { buildLink } from '@libs/bookfilter';
import { FilterTagBlockWrapper, Tag, TagWrapper, Tags } from './filterTagBlock.styled';

export default function FilterTagBlock(props) {
  let { categories = [], collapsible, defaultCollapsed, title, id, type, baseURL, pageUrl, config, onSelect = () => {} } = props;

  let [collapsed, setCollapsed] = useState(defaultCollapsed ? true : false);
  let resolvedUrl = process.browser ? window.location.href : pageUrl;

  let linkUrl = useCallback((id, value, baseURL, pageUrl, config) => buildLink(id, value, baseURL, pageUrl, config), [resolvedUrl]);

  return (
    <FilterTagBlockWrapper collapsed={collapsed}>
      <FilterBlockTitle title={title} collapsible={collapsible} collapsed={collapsed} onCollapse={handleCollapse}></FilterBlockTitle>
      <Tags>
        {categories &&
          categories.map((category) => (
            <TagWrapper key={category.slug} onClick={() => onSelect({ id, type, value: category.slug })}>
              <a href={linkUrl(id, category.slug, baseURL, pageUrl, config)} onClick={(e) => e.preventDefault()}>
                <Tag selected={category.selected}>{category.title}</Tag>
              </a>
            </TagWrapper>
          ))}
      </Tags>
    </FilterTagBlockWrapper>
  );

  function handleCollapse() {
    if (!collapsible) return;
    setCollapsed(!collapsed);
  }
}
