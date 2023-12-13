import styled from '@emotion/styled';
import colors from '@vars/colors';
import breakpoints from '@vars/breakpoints';

export let Options = styled.div`
  padding-bottom: 10px;
  overflow: hidden;
`;

export let Item = styled.div`
  display: flex;
  margin-bottom: 20px;
  cursor: pointer;

  @media (max-width: ${breakpoints.max.xl}) {
    margin-bottom: 15px;
  }
`;

export let Checkbox = styled.div`
  display: inline-block;
  vertical-align: middle;
  margin-right: 15px;
  pointer-events: none;
`;

export let Label = styled.div`
  display: inline-block;
  vertical-align: middle;
  font-size: 14px;
  color: ${colors.mineShaftDark};
`;

export let FilterCheckBlockWrapper = styled.div`
  ${Options} {
    padding-bottom: ${({ collapsed }) => collapsed && '0'};
    height: ${({ collapsed }) => (collapsed ? '0' : 'auto')};
  }
`;
