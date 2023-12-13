import styled from '@emotion/styled';
import breakpoints from '@vars/breakpoints';

export let PageTitleWrapper = styled.div`
  margin-top: ${({ mtd }) => (mtd ? `${mtd}px` : '80px')};
  margin-bottom: ${({ mbd }) => (mbd ? `${mbd}px` : '60px')};
  font-weight: 700;
  font-size: 36px;

  @media (max-width: ${breakpoints.max.md}) {
    font-size: 22px;
    margin-top: ${({ mtm }) => (mtm ? `${mtm}px` : '60px')};
    margin-bottom: ${({ mbm }) => (mbm ? `${mbm}px` : '30px')};
  }
`;
