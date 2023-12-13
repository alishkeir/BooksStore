import styled from '@emotion/styled';
import colors from '@vars/colors';

export let SummaryDrawerComponent = styled.div`
  border-bottom: 1px solid ${colors.mischka};
`;

export let Header = styled.div`
  height: 50px;
  position: relative;
  display: flex;
  align-items: center;
  padding: 0 20px;
  cursor: pointer;
`;

export let Body = styled.div`
  padding: 0 20px;
`;

export let Title = styled.div`
  flex: 1;
`;

export let Value = styled.div`
  margin-right: 30px;
  font-weight: 700;
`;

export let IconWrapper = styled.div`
  width: 10px;
  margin: 0 auto;
  transform-origin: 50%;
  transition: transform 0.2s ease-in-out;
  transform: ${({ open }) => (open ? 'rotateZ(-90deg)' : 'rotateZ(90deg)')};
`;

export let HeaderIcon = styled.div`
  width: 16px;
  position: absolute;
  right: 15px;
  top: 50%;
  transform: translateY(-50%);
`;
