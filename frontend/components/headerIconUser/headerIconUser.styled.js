import styled from '@emotion/styled';
import colors from '@vars/colors';

export let HeaderIconUserComponent = styled.div`
  width: 22px;
  height: 22px;

  display: flex;
  justify-content: center;
  align-items: center;
`;

export let UserInitial = styled.div`
  font-weight: 600;
  font-size: 14px;
  width: 22px;
  height: 22px;
  text-transform: uppercase;
  color: black;
  background-color: ${({ theme }) =>
    theme === 'ALOMGYAR' ? colors.cherub : theme === 'OLCSOKONYVEK' ? colors.eggWhite : theme === 'NAGYKER' ? colors.athensGrayDark : colors.cherub};
  border: 2px solid
    ${({ theme }) =>
      theme === 'ALOMGYAR'
        ? colors.cherub
        : theme === 'OLCSOKONYVEK'
        ? colors.eggWhite
        : theme === 'NAGYKER'
        ? colors.athensGrayDark
        : colors.cherub};
  border-radius: 50%;
  transition: border 0.1s ease-in-out;
  display: flex;
  align-items: center;
  justify-content: center;
  user-select: none;

  &:hover {
    border: 2px solid
      ${({ theme }) =>
        theme === 'ALOMGYAR' ? colors.monza : theme === 'OLCSOKONYVEK' ? colors.amber : theme === 'NAGYKER' ? colors.dodgerBlueLight : colors.cherub};
  }
`;

export let UserGuest = styled.div``;
