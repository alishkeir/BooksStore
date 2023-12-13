import dynamic from 'next/dynamic';
import { useEffect, useState } from 'react';
import { useQuery, QueryClient } from 'react-query';
import { handleApiRequest, getResponseById } from '@libs/api';
import { dehydrate } from 'react-query/hydration';
import useRequest from '@hooks/useRequest/useRequest';

const Icon = dynamic(() => import('@components/icon/icon'));
const Header = dynamic(() => import('@components/header/header'));
const HeaderPromo = dynamic(() => import('@components/headerPromo/headerPromo'));
const Content = dynamic(() => import('@components/content/content'));
const SiteColContainer = dynamic(() => import('@components/siteColContainer/siteColContainer'));
const Footer = dynamic(() => import('@components/footer/footer'));
import { format, parseISO } from 'date-fns/fp';
import colors from '@vars/colors';
import {
  Article,
  ArticleBottom,
  ArticleContent,
  ArticleTop,
  Col,
  Date,
  ImageWrapper,
  Info,
  Lead,
  MagazinCikkPageWrapper,
  Row,
  ShareButtonIcon,
  ShareButtonText,
  ShareFacebook,
  Social,
  Title,
} from '@components/pages/magazinCikkPage.styled';
import DynamicHead from '@components/heads/DynamicHead';
import OptimizedImage from '@components/Images/OptimizedImage';

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    'article-get': {
      method: 'GET',
      path: '/pages/posts',
      ref: 'show',
      request_id: 'article-get',
      body: {
        slug: null,
      },
    },
  },
};

export default function MagazinCikkPage(props) {
  let { slug } = props;
  let [url, setUrl] = useState('');

  let queryPost = useQuery(['article-get', slug], () => handleApiRequest(requestPost.build()), {
    refetchOnWindowFocus: false,
    refetchOnMount: false,
    staleTime: 0,
  });

  let requestPost = useRequest(requestTemplates, queryPost);

  requestPost.addRequest('article-get');

  let post = getResponseById(queryPost.data, 'article-get')?.body;

  useEffect(() => {
    setUrl(window.location.href);
  }, []);

  return (
      <MagazinCikkPageWrapper>
        <DynamicHead title={post.meta_title} description={post.meta_description} image={post.cover[0]} />
        <Header promo={HeaderPromo}></Header>
        <Content>
          <SiteColContainer>
            <Row className="row">
              <Col className="col-xxl-6 offset-xxl-3 col-xl-8 offset-xl-2">
                <Article>
                  <ArticleTop className="row">
                    <ImageWrapper className="col-md-6">
                      <OptimizedImage src={post.cover} width="363" height="363" layout="responsive" alt={post.title}></OptimizedImage>
                    </ImageWrapper>
                    <Info className="col-md-6">
                      <Title>{post.title}</Title>
                      <Date>{parseISO(post.published_at) && format('yyyy. MM. dd.', parseISO(post.published_at))}</Date>
                      <Lead>{post.lead}</Lead>
                    </Info>
                  </ArticleTop>
                  <ArticleBottom className="row">
                    <ArticleContent className="col" dangerouslySetInnerHTML={{ __html: post.body }}></ArticleContent>
                  </ArticleBottom>
                  <Social>
                    <ShareFacebook href={`https://www.facebook.com/sharer/sharer.php?u=${url}`}>
                      <ShareButtonIcon>
                        <Icon type="social-facebook-icon" iconWidth="8px" iconHeight="16px" iconColor={colors.facebook}></Icon>
                      </ShareButtonIcon>
                      <ShareButtonText>Megosztom</ShareButtonText>
                    </ShareFacebook>
                  </Social>
                </Article>
              </Col>
            </Row>
          </SiteColContainer>
        </Content>
        <Footer></Footer>
      </MagazinCikkPageWrapper>
  );
}

export async function getStaticProps({ params }) {
  const queryClient = new QueryClient();

  requestTemplates.requests['article-get'].body.slug = params.slug;

  await queryClient.prefetchQuery(['article-get', params.slug], () =>
      handleApiRequest({
        body: {
          request: [requestTemplates.requests['article-get']],
        },
      }),
  );

  let queryData = queryClient.getQueryData();

  if (queryData?.response) {
    let postResponse = getResponseById(queryData, 'article-get');

    if (postResponse && !postResponse.success) {
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
    },
    revalidate: 90,
  };
}

export async function getStaticPaths() {
  return {
    paths: [],
    fallback: 'blocking',
  };
}
