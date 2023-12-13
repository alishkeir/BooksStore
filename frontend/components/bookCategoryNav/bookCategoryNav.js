import Link from 'next/link';
import Icon from '@components/icon/icon';
import { BookCategoryNavWrapper, Category, List, ListItem, SubCategoryItem, SubCategoryList, Title, TitleText } from './bookCategoryNav.styled';

export default function BookCategoryNav({ type, categories }) {
  let path;

  switch (type) {
    case 0:
      path = 'konyvlista';
      break;
    case 1:
      path = 'ekonyvlista';
      break;

    default:
      path = 'konyvlista';
      break;
  }

  return (
    <BookCategoryNavWrapper>
      <Title>
        <Icon type="folder" iconWidth="14px" iconHeight="12px"></Icon>
        <TitleText>Kategória</TitleText>
      </Title>
      {categories && (
        <List>
          {categories.map((category) => (
            <ListItem key={category.slug}>
              <Category>
                <Link href={`/${path}/${category.slug}`} passHref>
                  {category.title}
                </Link>
              </Category>
              <SubCategoryList>
                {category.subcategories.map((subcategory) => (
                  <SubCategoryItem key={subcategory.slug}>
                    <Link href={`/${path}/${category.slug}/${subcategory.slug}`} passHref>
                      {subcategory.title}
                    </Link>
                  </SubCategoryItem>
                ))}
              </SubCategoryList>
            </ListItem>
          ))}
        </List>
      )}
    </BookCategoryNavWrapper>
  );
}
