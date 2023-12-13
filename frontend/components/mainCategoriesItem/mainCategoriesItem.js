import Link from 'next/link';
import Icon from '@components/icon/icon';
import { MainCategoriesItemComponent, ItemTitle, ItemIcon } from './mainCategoriesItem.styled';

export default function MainCategoriesItem(props) {
  let { slug, title } = props;
  return (
    <Link href={`/konyvlista/${slug}`} passHref legacyBehavior>
      <MainCategoriesItemComponent>
        <ItemTitle>{title}</ItemTitle>
        <ItemIcon>
          <Icon type="chevron-right-small" iconWidth="8px" iconHeight="13px"></Icon>
        </ItemIcon>
      </MainCategoriesItemComponent>
    </Link>
  );
}
