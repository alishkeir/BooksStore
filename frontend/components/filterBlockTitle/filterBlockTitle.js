import Icon from '@components/icon/icon';
import colors from '@vars/colors';
import { FilterBlockTitleWrapper, TitleIcon, TitleIconWrapper } from './filterBlockTitle.styled';

export default function FilterBlockTitle(props) {
  let { collapsible, collapsed, title, onCollapse = () => {} } = props;

  return (
    <FilterBlockTitleWrapper collapsible={collapsible} collapsed={collapsed} onClick={onCollapse}>
      {title}
      {collapsible && (
        <TitleIcon>
          <TitleIconWrapper>
            <Icon type="chevron-right" iconWidth="10px" iconColor={colors.monza}></Icon>
          </TitleIconWrapper>
        </TitleIcon>
      )}
    </FilterBlockTitleWrapper>
  );
}
