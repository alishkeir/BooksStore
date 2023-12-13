import styled from '@emotion/styled';
import breakpoints from '@vars/breakpoints';

export let CommentListComponent = styled.div``;

export let Title = styled.div`
  font-weight: 700;
  font-size: 20px;
  margin-bottom: 40px;

  @media (max-width: ${breakpoints.max.md}) {
    margin-bottom: 20px;
    font-size: 18px;
  }
`;

export let TitleText = styled.div`
  display: inline;
`;

export let TitleCount = styled.div`
  display: inline;
  margin-left: 5px;
`;

export let Form = styled.form`
  background: white;
  box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.05);
  border-radius: 10px;
  padding: 20px 50px;
  margin-bottom: 20px;

  @media (max-width: ${breakpoints.max.md}) {
    padding: 20px;
  }
`;

export let FormTitle = styled.div`
  font-weight: 700;
  font-size: 16px;
  margin-bottom: 15px;

  @media (max-width: ${breakpoints.max.md}) {
    font-size: 14px;
  }
`;

export let FormInput = styled.div`
  margin-bottom: 20px;
`;

export let FormButton = styled.div`
  text-align: right;
`;

export let ButtonWrapper = styled.div`
  width: 100%;
  max-width: 230px;
  display: inline-block;

  @media (max-width: ${breakpoints.max.md}) {
    max-width: 100%;
  }
`;

export let Comments = styled.div`
  margin-bottom: 30px;
`;

export let PaginantionWrapper = styled.div`
  display: flex;
  justify-content: center;
  margin-bottom: 20px;
`;
