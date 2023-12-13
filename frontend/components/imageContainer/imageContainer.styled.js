import styled from '@emotion/styled';
import breakpoints from '@vars/breakpoints';
import theme from '@vars/theme';
import Icon from '@components/icon/icon';

export let ImageContainer = styled.div`
  display: flex;

  flex-direction: ${({ isMinMd }) => (isMinMd ? 'row' : 'column')};
  align-items: ${({ isMinMd }) => (isMinMd ? 'flex-end' : 'center')};
  margin: 0 -15px;
`;

export let RowWrapper = styled.div`
  margin: 0 15px 30px 15px;

  img:first-of-type {
    border-radius: 10px;
    filter: ${({ hasFilter }) => (hasFilter ? 'brightness(50%)' : 'brightness(100%)')};
  }

  @media (max-width: ${breakpoints.max.md}) {
    margin-bottom: 20px;
  }
`;

export let ImageWrapper = styled.div`
  position: relative;
`;

export let ShopImageWrapper = styled.div`
  cursor: pointer;
`;

export let SubtitleWrapper = styled.div`
  position: absolute;
  top: ${({ title }) => (title && title.length ? '80px' : '')};
  bottom: ${({ title }) => (title && title.length ? '' : '28px')};

  @media (max-width: ${breakpoints.max.lg}) {
    top: ${({ title }) => (title && title.length ? '100px' : '')};
    bottom: ${({ title }) => (title && title.length ? '' : '20px')};
  }

  @media (max-width: ${breakpoints.max.md}) {
    top: ${({ title }) => (title && title.length ? '80px' : '')};
    bottom: ${({ title }) => (title && title.length ? '' : '28px')};
  }
`;

export let ImageTitle = styled.div`
  font-size: 20px;
  font-weight: 700;
  margin-bottom: 24px;

  @media (max-width: ${breakpoints.max.md}) {
    font-size: 18px;
    margin-bottom: 16px;
    margin-top: 20px;
  }
`;

export let City = styled.div`
  font-size: 38px;
  font-weight: 700;
  margin-bottom: -7px;
  margin-left: 30px;
  color: white;

  @media (max-width: ${breakpoints.max.xl}) {
    font-size: 18px;
  }

  @media (max-width: ${breakpoints.max.md}) {
    font-size: 32px;
  }
`;

export let Separator = styled.div`
  width: 168px;
  height: 4px;
  border-radius: 0px;
  background: ${theme.main.primary};
`;

export let Address = styled.div`
  color: white;
  overflow: auto;
  font-size: 14px;
  font-weight: 600;
  margin-top: 0;

  @media (max-width: ${breakpoints.max.xl}) {
    font-size: 8px;
    margin-top: 4px;
  }

  @media (max-width: ${breakpoints.max.md}) {
    margin-top: 0;
    font-size: 14px;
  }
`;

export let LocationtIcon = styled(Icon)`
  height: 18px;
  width: 18px;
  margin-right: 10px;
`;

export let AddressWrapper = styled.div`
  display: flex;
  margin-top: 14px;
  margin-left: 32px;

  @media (max-width: ${breakpoints.max.xl}) {
    margin-top: 8px;
  }

  @media (max-width: ${breakpoints.max.md}) {
    margin-top: 14px;
  }
`;
