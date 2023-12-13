import Icon from '@components/icon/icon';
import theme from '@vars/theme';
import ProgressBox from '@components/progressBox/progressBox';

import { BoxGroup, BoxWrapper, IconWrapper, ProgressBoxListComponent, Separator } from '@components/progressBoxList/progressBoxList.styled';

export default function ProgressBoxList({ order }) {
  let orderStepDone = true;

  let steps = order?.steps?.map((step) => {
    step.done = orderStepDone;
    if (step.active === true) orderStepDone = false;
    return step;
  });

  return (
    <ProgressBoxListComponent>
      {steps?.map((step, index) => (
        <BoxGroup key={index}>
          <BoxWrapper>
            <ProgressBox {...step} finished={step.done ? true : false}></ProgressBox>
          </BoxWrapper>
          <Separator>
            <IconWrapper>
              <Icon type="chevron-right-small" iconWidth="10px" iconColor={theme.main.primary}></Icon>
            </IconWrapper>
          </Separator>
        </BoxGroup>
      ))}
    </ProgressBoxListComponent>
  );
}
