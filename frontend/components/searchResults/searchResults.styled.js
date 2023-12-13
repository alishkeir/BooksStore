import styled from '@emotion/styled';
import colors from '@vars/colors';

export let SearchResultsComponent = styled.div``;

export let ResultGroup = styled.div``;

export let ResultTitle = styled.div`
  height: 30px;
  background-color: ${colors.ghost};
  display: flex;
  align-items: center;
  padding: 0 20px;
  font-weight: 600;
  font-size: 14px;
  color: white;
`;

export let ResultListItem = styled.a`
  padding: 10px 20px;
  min-height: 40px;
  display: flex;
  align-items: center;
  background-color: white;
  border-bottom: 1px solid ${colors.mischka};
  display: block;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
`;

export let ResultList = styled.div`
  ${ResultListItem} {
    &:last-child {
      border-bottom: none;
    }
  }
`;

//import { SearchResultsComponent, ResultGroup, ResultTitle, ResultList, ResultListItem } from './searchResults.styled';

export let ResultLink = styled.a`
  display: flex;
  align-items: center;
  background-color: white;
  min-height: 60px;
  padding: 0 20px;
  color: ${colors.monza};
  font-weight: 600;
  font-size: 14px;
  cursor: pointer;
  border-top: 1px solid ${colors.mischka};
`;

export let ResultLinkText = styled.div`
  margin-left: 18px;
`;

//import { SearchResultsComponent, ResultGroup, ResultTitle, ResultList, ResultListItem, ResultLink, ResultLinkText } from './searchResults.styled';
