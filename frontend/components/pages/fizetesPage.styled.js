import styled from '@emotion/styled';
import breakpoints from '@vars/breakpoints';

export let FizetesPageWrapper = styled.div``;

export let NavStepsRow = styled.div`
  padding: 80px 0 100px;

  @media (max-width: ${breakpoints.max.md}) {
    padding: 60px 0 50px;
  }
`;

export let NavStepsCol = styled.div``;

export let Title = styled.div`
  font-weight: 700;
  font-size: 36px;
  text-align: center;
  margin-bottom: 50px;

  @media (max-width: ${breakpoints.max.md}) {
    font-size: 22px;
    margin-bottom: 20px;
  }
`;

export let FormRow = styled.div`
  margin-bottom: 120px;

  @media (max-width: ${breakpoints.max.md}) {
    margin-bottom: 60px;
  }
`;

export let FormCol = styled.div``;

export let FormActions = styled.div`
  display: flex;
  flex-direction: column;
  align-items: center;
`;

export let ButtonWrapper = styled.div`
  width: 100%;
  max-width: 350px;
  margin-bottom: 20px;
`;

export let FormImage = styled.div`
  margin-bottom: 50px;

  @media (max-width: ${breakpoints.max.md}) {
    margin-bottom: 30px;
  }

  svg {
    display: block;
    margin: 0 auto;
  }
`;

export let SuccessText = styled.div`
  text-align: center;
  margin-bottom: 50px;

  @media (max-width: ${breakpoints.max.md}) {
    margin-bottom: 40px;
  }
`;

export let TextBlock = styled.div`
  margin-bottom: 15px;
  strong {
    font-weight: 700;
  }
`;

export let TextLine = styled.div``;

export let SuccessContent = styled.div``;

export let ErrorContent = styled.div``;
