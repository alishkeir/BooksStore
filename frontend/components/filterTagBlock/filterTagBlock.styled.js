import styled from '@emotion/styled';
import colors from '@vars/colors';

export let Title = styled.div`
  font-weight: bold;
  font-size: 18px;
  margin-bottom: 18px;
`;

export let Tags = styled.div`
  padding-bottom: 10px;
  overflow: hidden;
`;

export let Tag = styled.div`
  background-color: ${({ selected }) => (selected ? colors.ghost : colors.titanWhite)};
  min-height: 30px;
  border-radius: 15px;
  display: inline-flex;
  padding: 0 15px;
  align-items: center;
  cursor: pointer;
`;

export let TagWrapper = styled.div`
  margin-bottom: 10px;
`;

export let FilterTagBlockWrapper = styled.div`
  ${Tags} {
    padding-bottom: ${({ collapsed }) => collapsed && '0'};
    height: ${({ collapsed }) => (collapsed ? '0' : 'auto')};
  }
`;
