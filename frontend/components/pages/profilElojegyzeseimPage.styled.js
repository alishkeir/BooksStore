import styled from '@emotion/styled';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';

export let ProfilElojegyzeseimPageComponent = styled.div``;

export let PageContent = styled.div`
  margin-bottom: 120px;

  @media (max-width: ${breakpoints.max.md}) {
    margin-bottom: 0;
    padding: 40px 0 60px;
  }
`;

export let ProfileNavigatorWrapper = styled.div``;

export let ProfileData = styled.div``;

export let List = styled.div``;

export let ListItem = styled.div`
  display: flex;
  margin-bottom: 30px;
  padding-bottom: 30px;
  border-bottom: 1px solid ${colors.mischka};

  &:last-of-type {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
  }
`;

export let ListItemImageWrapper = styled.div`
  margin-right: 20px;
`;

export let ListItemText = styled.div`
  flex: 1;
`;

export let ListItemTextWrapper = styled.div`
  position: relative;
  padding-right: 40px;
`;

export let ListItemTitle = styled.div`
  font-weight: 700;
  font-size: 20px;

  @media (max-width: ${breakpoints.max.lg}) {
    font-size: 16px;
  }
`;

export let ListItemAuthor = styled.div`
  font-weight: 300;
  font-size: 16px;
  margin-bottom: 20px;

  @media (max-width: ${breakpoints.max.lg}) {
    font-size: 12px;
    margin-bottom: 15px;
  }

  a {
    color: ${colors.silverChaliceDark};
  }
`;

export let AuthorWrapper = styled.div`
  display: inline-block;

  &::after {
    content: ',';
    margin-right: 10px;
    color: ${colors.silverChaliceDark};
  }

  &:last-of-type {
    &::after {
      content: '';
    }
  }
`;

export let ListItemOriginalPrize = styled.div`
  font-weight: normal;
  font-size: 14px;
  line-height: 1;
  color: ${colors.silverChaliceDark};
  text-decoration-line: line-through;

  @media (max-width: ${breakpoints.max.lg}) {
    font-size: 12px;
  }
`;

export let ListItemCurrentPrize = styled.div`
  font-weight: 700;
  font-size: 20px;
  line-height: 28px;
  margin-bottom: 20px;

  @media (max-width: ${breakpoints.max.lg}) {
    font-size: 16px;
    margin-bottom: 10px;
  }
`;

export let ListItemDate = styled.div``;

export let ListItemDateTitle = styled.div`
  font-weight: 600;
  font-size: 14px;
`;

export let ListItemDateValue = styled.div`
  font-weight: 300;
  font-size: 14px;
`;

export let ListItemAction = styled.div`
  margin-top: 25px;
`;

export let ListItemDelete = styled.div`
  position: absolute;
  right: 0;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
  padding: 10px;
`;

export let ButtonWrapper = styled.div`
  height: 50px;

  @media (max-width: ${breakpoints.max.lg}) {
    height: 40px;
  }
`;

export let PaginantionWrapper = styled.div`
  display: flex;
  justify-content: center;
  margin-top: 20px;
`;
