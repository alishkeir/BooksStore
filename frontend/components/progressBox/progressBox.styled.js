import styled from '@emotion/styled';
import theme from '@vars/theme';

export let IconWrapper = styled.div`
  position: absolute;
  width: 40px;
  height: 40px;
  left: 50%;
  top: -20px;
  transform: translateX(-50%);
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 50%;
  border: 2px solid
    ${({ type }) => {
      switch (type) {
        case 'finished':
          return theme.main.primary;

        default:
          return theme.gray.blue;
      }
    }};
  background-color: ${({ type }) => {
    switch (type) {
      case 'finished':
        return theme.main.primary;

      default:
        return 'white';
    }
  }};
`;

export let Content = styled.div`
  text-align: center;
`;

export let Title = styled.div`
  font-weight: 600;
  font-size: 16px;
  margin-bottom: 5px;
  color: ${theme.main.primary};
`;

export let Text = styled.div`
  font-size: 12px;
  line-height: 18px;
`;

export let Date = styled.div`
  font-size: 12px;
  line-height: 18px;
`;

export let ProgressBoxComponent = styled.div`
  position: relative;
  background: #fcfdff;
  box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.07);
  border-radius: 10px;
  padding: 40px 20px 20px;
  height: 100%;

  ${({ finished }) => console.log(finished)}

  ${Content} {
    ${Title}, ${Text}, ${Date} {
      color: ${({ finished }) => {
        switch (finished) {
          case false:
            return theme.gray.blue;
        }
      }};
    }
  }
`;
