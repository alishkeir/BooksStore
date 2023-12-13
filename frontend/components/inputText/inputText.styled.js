import styled from '@emotion/styled';
import colors from '../../vars/colors';

export let InputWrapper = styled.div`
  position: relative;
  width: 100%;

  ${({ error }) =>
    error &&
    `
    ${Label} {
      color: ${colors.monza};
    }

    ${Input} {
      border: 1px solid ${colors.monza};
      color: ${colors.monza};
    }
    
    `}
`;
export let Label = styled.label`
  display: block;
  font-weight: 400;
  font-size: 12px;
  padding-left: 15px;
  margin-bottom: 5px;
  line-height: 1.2;
`;
export let Input = styled.input`
  height: ${(props) => props.inputHeight && props.inputHeight}px;
  background: rgba(214, 216, 230, 0.16);
  border: 1px solid ${colors.mischka};
  border-radius: 10px;
  padding: 0 ${(props) => (props.button ? '50px' : '15px')} 0 15px;
  width: 100%;
  color: ${({ readOnly }) => (readOnly ? colors.silverChaliceDark : 'black')};

  &:focus {
    outline: none;
  }
  &::placeholder {
    color: ${colors.mischka};
  }
`;
export let Button = styled.div`
  position: absolute;
  right: 20px;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
  max-width: 20px;

  svg {
    max-width: 100%;
  }
`;

export let Subtext = styled.div`
  font-weight: 300;
  font-size: 12px;
  color: ${colors.silverChalice};
  padding-left: 15px;
  margin-top: 5px;
  line-height: 1.2;
`;

export let Error = styled.div`
  font-weight: 300;
  font-size: 12px;
  color: ${colors.monza};
  padding-left: 15px;
  margin-top: 5px;
  line-height: 1.2;
`;
