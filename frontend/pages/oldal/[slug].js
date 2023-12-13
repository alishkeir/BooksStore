import dynamic from 'next/dynamic';
import { useState, useEffect } from 'react';
const Header = dynamic(() => import('@components/header/header'));
const Content = dynamic(() => import('@components/content/content'));
const SiteColContainer = dynamic(() => import('@components/siteColContainer/siteColContainer'));
const Footer = dynamic(() => import('@components/footer/footer'));
const HeaderPromo = dynamic(() => import('@components/headerPromo/headerPromo'));
import { QueryClient, useQuery } from 'react-query';
import useRequest from '@hooks/useRequest/useRequest';
import { dehydrate } from 'react-query/hydration';
import { handleApiRequest, getResponseById } from '@libs/api';
import { ContentWrapper, MainContent, MainRow, OldalPageWrapper, Title } from '@components/pages/oldalPage.styled';
import DynamicHead from '@components/heads/DynamicHead';

const PAGE_CONTENT_REQUEST_ID = 'page-content';

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    'page-content': {
      method: 'GET',
      path: '/page-content',
      ref: 'content',
      request_id: PAGE_CONTENT_REQUEST_ID,
      body: {
        slug: null,
      },
    },
  },
};

export default function OldalPage(props)
{
  let { slug } = props;
  let [content, setContent] = useState({});
  // let settings = settingsVars.get(url.getHost());
  let queryPageContent = useQuery([PAGE_CONTENT_REQUEST_ID, slug], () => handleApiRequest(pageContentRequest.build()), {
    refetchOnWindowFocus: false,
    refetchOnMount: false,
    staleTime: 0,
  });

  let pageContentRequest = useRequest(requestTemplates, queryPageContent);

  pageContentRequest.addRequest(PAGE_CONTENT_REQUEST_ID);

  useEffect(() =>
  {
    if (!queryPageContent.data.success) return;
    setContent(queryPageContent.data.response[0].body);
  }, [queryPageContent]);

  return (
    <>
      <OldalPageWrapper>
        <DynamicHead title={content.meta_title} description={content.meta_description} />
        <Header promo={HeaderPromo}></Header>
        <Content>
          <SiteColContainer>
            <ContentWrapper>
              <Title>{content.title}</Title>
              <MainRow className="row">
                <MainContent className="col-md-8 offset-md-2" dangerouslySetInnerHTML={{ __html: content.body }}></MainContent>
              </MainRow>
            </ContentWrapper>
          </SiteColContainer>
        </Content>
        <Footer></Footer>
      </OldalPageWrapper>
    </>
  );
}

export async function getStaticProps({ params })
{
  const queryClient = new QueryClient();

  requestTemplates.requests[PAGE_CONTENT_REQUEST_ID].body.slug = params.slug;

  await queryClient.prefetchQuery([PAGE_CONTENT_REQUEST_ID, params.slug], () =>
    handleApiRequest({
      body: {
        request: [requestTemplates.requests[PAGE_CONTENT_REQUEST_ID]],
      },
    }),
  );

  // Checking if book is found
  let queryData = queryClient.getQueryData();

  if (queryData?.response)
  {
    let bookResponse = getResponseById(queryData, PAGE_CONTENT_REQUEST_ID);

    if (bookResponse && !bookResponse.success)
    {
      return {
        notFound: true,
        revalidate: 10,
      };
    }
  }

  return {
    props: {
      key: params.slug,
      slug: params.slug,
      dehydratedState: dehydrate(queryClient),
    }, revalidate: 90
  };
}

export async function getStaticPaths()
{
  return {
    paths: [],
    fallback: 'blocking',
  };
}
