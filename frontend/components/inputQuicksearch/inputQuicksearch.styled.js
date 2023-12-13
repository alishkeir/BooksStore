import styled from '@emotion/styled';
import colors from '../../vars/colors';

export let InputQuicksearchComponent = styled.div`
  width: 100%;
`;

export let InputWrapper = styled.div`
  position: relative;
`;

export let Indicator = styled.div`
  position: absolute;
  right: 20px;
  top: 50%;
  transform: translateY(-50%);
`;

export let Input = styled.input`
  height: 50px;
  background: rgba(214, 216, 230, 0.16);
  border: 1px solid ${colors.mischka};
  border-bottom: 1px solid ${({ open }) => (open ? 'transparent' : colors.mischka)};
  border-radius: ${({ open }) => (open ? '10px 10px 0 0' : '10px')};
  padding: 0 50px 0 15px;
  width: 100%;
  color: ${colors.mineShaftDark};

  &:focus {
    outline: none;
  }
  &::placeholder {
    color: ${colors.mischka};
  }
`;

export let ResultWrapper = styled.div`
  position: relative;
`;

export let ResultLines = styled.div`
  border: 1px solid ${colors.mischka};
  border-top: none;
  border-radius: 0 0 10px 10px;
  position: absolute;
  left: 0;
  top: 0;
  z-index: 9999;
  background-color: white;
  width: 100%;
`;

export let ResultLine = styled.div`
  border-top: 1px solid ${colors.mischka};
  padding: 5px 15px;
  display: flex;
  align-items: center;
  cursor: pointer;

  &:hover {
    background-color: ${colors.titanWhite};
  }

  &:first-of-type {
    border-top: none;
  }

  &:last-of-type {
    border-radius: 0 0 10px 10px;
  }
`;

export let ResultIcon = styled.div`
  margin-right: 10px;
`;

export let ResultContent = styled.div``;

export let ResultText = styled.div`
  font-size: 14px;
  color: ${colors.mineShaftDark};
`;

export let ResultSubtext = styled.div`
  font-size: 12px;
  color: ${colors.silverChaliceDark};
`;

export let LoaderIcon = styled.div``;
