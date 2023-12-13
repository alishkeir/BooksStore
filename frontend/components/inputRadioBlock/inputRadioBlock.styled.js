import styled from '@emotion/styled';
import colors from '@vars/colors';

export let InputRadioBlockComponent = styled.div`
  display: flex;
  align-items: flex-start;
  padding: 20px;
  min-height: 60px;
  background: #f8f8ff;
  border: 1px solid ${(props) => (props.checked ? colors.mineShaft : colors.mischka)};
  /* Primary Colors / Black */
  box-sizing: border-box;
  box-shadow: 0px 0px 10px rgba(214, 216, 231, 0.65);
  border-radius: 10px;
  cursor: pointer;

  &:hover {
    border: 1px solid ${colors.mineShaft};

    rect {
      stroke: ${colors.mineShaft};
    }
  }
`;

export let RadioWrapper = styled.div`
  margin-right: 15px;
`;

export let LabelsWrapper = styled.div``;

export let Label = styled.div`
  font-weight: 600;
  font-size: 14px;
`;

export let Sublabel = styled.div`
  font-weight: 300;
  font-size: 14px;
`;
