import styled from '@emotion/styled';
import colors from '@vars/colors';
import theme from '@vars/theme';

export let ProfileNavigatorComponent = styled.div`
  width: 100%;
  background-color: ${colors.zircon};
  padding: 10px 30px;
  box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.07);
  border-radius: 10px;
`;

export let NavigatorLine = styled.div`
  line-height: 1;
  height: 60px;
  display: flex;
  align-items: center;
  border-bottom: 1px solid ${colors.mischka};

  &:first-of-type {
    padding-top: 0;
  }

  ${(props) =>
    props.selected &&
    `
    font-weight: 600;

    &::before {
        content: '';
        display: inline-block;
        width: 3px;
        height: 23px;
        background-color: ${theme.main.primary};
        vertical-align: middle;
        margin-right: 10px;
    }
  `}

  a {
    &:hover {
      font-weight: 600;
    }
  }
`;

export let LogoutLine = styled.div`
  font-weight: 600;
  font-size: 16px;
  line-height: 1;
  height: 60px;
  display: flex;
  align-items: center;
  color: ${theme.button.primary};
  cursor: pointer;
`;
