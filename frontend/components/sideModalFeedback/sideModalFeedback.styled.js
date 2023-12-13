import styled from '@emotion/styled';
import colors from '@vars/colors';

export let SideModalFeedbackWrapper = styled.div`
  height: 100%;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
`;

export let Form = styled.div`
  width: 100%;
`;

export let ButtonWrapper = styled.div`
  margin-bottom: 20px;
`;

export let Title = styled.div`
  font-weight: 700;
  font-size: 22px;
  margin-bottom: 20px;
`;

export let IconWrapper = styled.div`
  margin-bottom: 25px;
`;

let Icon = styled.div`
  width: 70px;
  height: 70px;
  border: 1px solid gray;
  border-radius: 50%;
  margin: 0 auto;
  display: flex;
  align-items: center;
  justify-content: center;
`;

export let CheckIcon = styled(Icon)`
  border-color: ${colors.malachite};

  path {
    fill: ${colors.malachite};
  }
`;

export let ExIcon = styled(Icon)`
  border-color: ${colors.monza};

  path {
    stroke: ${colors.monza};
  }
`;

export let Text = styled.div`
  margin-bottom: 25px;
`;
