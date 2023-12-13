import MainCategoriesItem from '@components/mainCategoriesItem/mainCategoriesItem';

import { MainCategoriesListerComponent, MainCategoriesItemWrapper, Header, HeaderTitle, Content } from './mainCategoriesLister.styled';

export default function MainCategoriesLister(props) {
  let { categories } = props;

  return (
    <MainCategoriesListerComponent>
      <Header>
        <HeaderTitle>Összes kategória</HeaderTitle>
      </Header>
      <Content>
        {categories?.length &&
          categories.map((category) => (
            <MainCategoriesItemWrapper key={category.slug}>
              <MainCategoriesItem slug={category.slug} title={category.title}></MainCategoriesItem>
            </MainCategoriesItemWrapper>
          ))}
      </Content>
    </MainCategoriesListerComponent>
  );
}
