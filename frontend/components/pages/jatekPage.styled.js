import { ContentWrapper } from '@components/content/content.styled';
import { Input } from '@components/dropdown/dropdown.styled';
import styled from '@emotion/styled';
import { UserDropdownWrapper } from './szallitasiAdatokPage.styled';

export let JatekPageWrapper = styled.div`
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  gap: 2rem;

  ${ContentWrapper} {
    min-height: 100vh;
  }
`;

export let JatekContainer = styled.form`
  height: 100%;
  max-width: 720px;
  width: 80%;
  margin: auto;
  margin-top: 2.5rem;
  display: flex;
  flex-direction: row;
  align-items: center;
  gap: 1.25rem;
  padding-bottom: 1.5rem;
  margin-bottom: 2.5rem;
  border-bottom: 1px solid #e30613;

  @media (max-width: 650px) {
    flex-direction: column-reverse;
  }
`;

export let XmasPrizesImage = styled.div``;

export let JatekHeaderContainer = styled.div`
  display: flex;
  flex-direction: column;
  flex: 1;

  & b {
    font-weight: 600;
  }

  & a {
    text-decoration: underline;

    &:hover {
      text-decoration: none;
    }
  }
`;

export let JatekHeader = styled.h2`
  font-size: 36px;
  line-height: 54px;
  font-weight: 700;
  color: #e30613;
`;

export let SuccessMessageHeader = styled.h3`
  font-size: 36px;
  line-height: 54px;
  font-weight: 500;
  color: #e30613;

  & > span {
    margin-right: 0.5rem;
  }

  @media (max-width: 650px) {
    text-align: center;
  }
`;

export let JatekFormWrapper = styled.div`
  max-width: 720px;
  width: 80%;
  margin: auto;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  gap: 2.5rem;
  margin-bottom: 2.5rem;

  ${UserDropdownWrapper} {
    margin-right: 0;

    ${Input} {
      height: 50px;
    }
  }
`;

export let JatekInputs = styled.form`
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1.25rem;

  @media (max-width: 600px) {
    grid-template-columns: repeat(1, 1fr);
  }
`;

export let JatekButtonWrapper = styled.div`
  width: 100%;
  display: flex;
  justify-content: flex-end;

  & > button {
    width: 50%;
  }
`;
