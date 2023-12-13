import styled from '@emotion/styled';
import colors from '@vars/colors';
import theme from '@vars/theme';

export let HeaderUserMenuWrapper = styled.div`
  position: absolute;
  font-size: 16px;
  bottom: 0;
  right: 0;

  transform: translate(20%, 100%);
  width: 300px;
  z-index: 100;
`;

export let Content = styled.div`
  z-index: 4;
  position: relative;
  background-color: white;
  box-shadow: 0px 5px 25px rgba(113, 135, 157, 0.2);
  border-radius: 10px;
  padding: 25px;
`;

export let Triangle = styled.div`
  z-index: 5;
  position: absolute;
  width: 50px;
  height: 25px;
  top: -25px;
  right: 45px;
  background-color: transparent;
  overflow: hidden;
  &::before {
    content: '';
    display: block;
    background-color: transparent;
    width: 0;
    height: 0;
    position: absolute;
    bottom: -12px;
    left: 50%;
    border: 12px solid transparent;
    border-bottom-color: white;
    border-right-color: white;
    transform: translateX(-50%) rotateZ(225deg);
    box-shadow: 0px 0px 5px rgba(113, 135, 157, 0.2);
    z-index: 3;
    // filter: drop-shadow(0px 0px 5px rgba(113, 135, 157, 0.2));
  }
`;

export let Title = styled.div`
  font-weight: 600;
  font-size: 18px;
  border-bottom: 1px solid ${colors.mischka};
  padding-bottom: 25px;
  margin-bottom: 25px;
  color: black;
`;

export let List = styled.div`
  border-bottom: 1px solid ${colors.mischka};
  margin-bottom: 25px;
`;

export let ListItem = styled.div`
  margin-bottom: 25px;
  font-weight: 300;
`;

export let Action = styled.div``;

export let ActionItem = styled.div`
  font-weight: 600;
  color: ${theme.button.primary};
  cursor: pointer;
`;
