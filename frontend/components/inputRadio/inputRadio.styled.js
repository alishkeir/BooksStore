import styled from '@emotion/styled';
import colors from '../../vars/colors';

export let InputRadioComponent = styled.div`
  display: inline-block;
  vertical-align: top;

  ${({ label }) =>
    label &&
    `${LabelIcon} {
      margin-right: 15px;
    }`}

  ${({ disabled }) =>
    disabled &&
    `${LabelText} {
      color: ${colors.mischka};
    }
    ${LabelIconUncheckedWrapper},
    ${LabelIconUncheckedWrapper}:hover,
    ${LabelIconCheckedWrapper},
    ${LabelIconCheckedWrapper}:hover
     {
       rect {
         stroke: transparent;
         fill: ${colors.titanWhite};

       }
    }`}
`;

export let CheckBox = styled.input`
  display: none;
`;

export let Label = styled.label`
  display: flex;
  margin-bottom: 0;

  a {
    text-decoration: underline;
    color: ${colors.monza};
  }
`;

export let LabelText = styled.div`
  font-weight: 300;
  font-size: 12px;
  line-height: 20px;
  color: ${colors.mineShaftDark};
`;

export let LabelIconUncheckedWrapper = styled.div`
  &:hover {
    rect {
      stroke: ${colors.mineShaft};
    }
  }
`;

export let LabelIconCheckedWrapper = styled.div``;

export let LabelIcon = styled.div`
  cursor: pointer;

  ${LabelIconUncheckedWrapper} {
    rect {
      stroke: ${({ error }) => error && colors.monza};
    }
  }
  svg {
    display: block;
  }
`;

export let Text = styled.div``;

export let LabelError = styled.div`
  font-weight: 300;
  font-size: 12px;
  line-height: 20px;
  color: ${colors.monza};
`;
