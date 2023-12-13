import dynamic from 'next/dynamic';
import { useState, useEffect, useRef } from 'react';
import { useQuery, QueryClient } from 'react-query';
import { dehydrate } from 'react-query/hydration';
import { useSelector } from 'react-redux';

const Header = dynamic(() => import('@components/header/header'));
const PageTitle = dynamic(() => import('@components/pageTitle/pageTitle'));
const Content = dynamic(() => import('@components/content/content'));
const SiteColContainer = dynamic(() => import('@components/siteColContainer/siteColContainer'));
const Footer = dynamic(() => import('@components/footer/footer'));
const BookTable = dynamic(() => import('@components/bookTable/bookTable'));
const BookCard = dynamic(() => import('@components/bookCard/bookCard'));
const ListHeader = dynamic(() => import('@components/listHeader/listHeader'));
const HeaderPromo = dynamic(() => import('@components/headerPromo/headerPromo'));
import { getMetadata, getResponseById, handleApiRequest } from '@libs/api';

const ImageContainer = dynamic(() => import('@components/imageContainer/imageContainer'));
import { AkciokPageWrapper, Banners, Booklist, List, ListHeaderWrapper } from '@components/pages/akciokPage.styled';
import DynamicHead from '@components/heads/DynamicHead';
import settingsVars from "@vars/settingsVars";
import url from "@libs/url";

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8', 'Content-type': 'application/json; charset=utf-8',
  }, requests: {
    'promotions-get': {method: 'GET', path: '/pages/promotions', ref: 'list', request_id: 'promotions-get'},
  },
};

export default function AkciokPage({metadata}) {
  let settings = settingsVars.get(url.getHost());

  let queryHeadersRef = useRef(requestTemplates.headers);

  let authChecking = useSelector((store) => store.user.authChecking);
  let actualUser = useSelector((store) => store.user.user);

  let [promotionImages, setPromotionImages] = useState([]);

  let promotionsQuery = useQuery('promotions-get', () => handleApiRequest({
    headers: queryHeadersRef.current, body: {request: [requestTemplates.requests['promotions-get']]},
  }), {
    enabled: false, refetchOnWindowFocus: false, refetchOnMount: false, staleTime: 0,
  });

  let promotions = getResponseById(promotionsQuery.data, 'promotions-get');

  // NAGYKER user personalization
  useEffect(() =>
  {
    if (settings.key !== 'NAGYKER') return;
    if (authChecking || !actualUser) return;

    queryHeadersRef.current['Authorization'] = `Bearer ${actualUser.token}`;

    promotionsQuery.refetch();
  }, [authChecking, actualUser]);

  useEffect(() =>
  {
    if (promotions)
    {
      setPromotionImages(promotions.body.promotions);
    }
  }, [promotions]);

  return (
    <AkciokPageWrapper>
      <DynamicHead title="Akciók" metadata={metadata}/>
      <Header promo={HeaderPromo}></Header>
      <Content>
        <SiteColContainer>
          <PageTitle>Akciók</PageTitle>
          <Banners className="row">
            <ImageContainer images={getImages(promotionImages)}></ImageContainer>
          </Banners>
          <List>
            <ListHeaderWrapper>
              <ListHeader title="Akciós sikerlista"></ListHeader>
            </ListHeaderWrapper>
            <Booklist>
              <BookTable>
                {promotions.body.products.map((book) => (<BookCard
                  serial={book.rank}
                  itemId={book.id}
                  key={book.id}
                  imageSrc={book.cover}
                  title={book.title}
                  author={book.authors && book.authors.split(',').join(', ')}
                  originalPrice={book.price_list}
                  price={book.price_sale}
                  isNew={book.is_new}
                  slug={book.slug}
                  discount={book.discount_percent}
                  purchaseType={book.state}
                  bookType={book.type === 0 ? 'book' : 'ebook'}
                ></BookCard>))}
              </BookTable>
            </Booklist>
          </List>
        </SiteColContainer>
      </Content>
      <Footer></Footer>
    </AkciokPageWrapper>
  )
    ;

  function getImages(images)
  {
    return images.map((image) =>
    {
      return {
        title: '', slug: image.slug, src: {xl: image.list_image_xl, sm: image.list_image_sm},
      };
    });
  }
}

export async function getStaticProps()
{
  const queryClient = new QueryClient();

  await queryClient.prefetchQuery('promotions-get', () => handleApiRequest({body: {request: [requestTemplates.requests['promotions-get']]}}));

  const metadata = await getMetadata('/akciok');

  return {
    props: {
      dehydratedState: dehydrate(queryClient),
      metadata: metadata.length > 0 ? metadata[0].data : null,
    }, revalidate: 90,
  };
}
