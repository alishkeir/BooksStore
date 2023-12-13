import styled from '@emotion/styled';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';

export let MagazinCikkPageWrapper = styled.div``;

export let ArticleTop = styled.div`
  padding-bottom: 40px;
  border-bottom: 1px solid ${colors.mischka};
  margin-bottom: 40px;
`;

export let ImageWrapper = styled.div``;

export let Info = styled.div``;

export let Title = styled.div`
  font-weight: bold;
  font-size: 36px;
  line-height: 44px;
  margin-bottom: 15px;

  @media (max-width: ${breakpoints.max.md}) {
    font-size: 22px;
  }
`;

export let Date = styled.div`
  font-weight: 300;
  font-size: 14px;
  margin-bottom: 15px;
`;

export let Lead = styled.div`
  font-weight: 300;
  font-size: 16px;
  line-height: 24px;
`;

export let ArticleBottom = styled.div`
  margin-bottom: 15px;
`;

export let ArticleContent = styled.div`
  font-weight: 300;
  font-size: 16px;
  line-height: 24px;

  a {
    font-weight: 300;
    font-size: 16px;
    color: ${colors.monza};
    text-decoration: underline;
  }
`;

export let Col = styled.div``;

export let Row = styled.div``;

export let Social = styled.div`
  text-align: right;
`;

export let ShareButton = styled.a`
  display: inline-flex;
  align-items: center;
  border: 1px solid #3a5897;
  padding: 0 30px;
  border-radius: 10px;
  height: 40px;
  font-weight: 600;
  font-size: 16px;
`;

export let ShareFacebook = styled(ShareButton)`
  border-color: ${colors.facebook};
  color: ${colors.facebook};
  cursor: pointer;

  &:hover {
    color: ${colors.facebook};
  }
`;

export let Article = styled.div`
  padding: 80px 0;
`;

export let ShareButtonIcon = styled.div`
  margin-right: 13px;
`;

export let ShareButtonText = styled.div``;
