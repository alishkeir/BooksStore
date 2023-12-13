import styled from '@emotion/styled';
import breakpoints from '@vars/breakpoints';

export let TitleIcon = styled.div`
  width: 16px;
  position: absolute;
  right: 15px;
  top: 50%;
  transform: translateY(-50%);
`;

export let TitleIconWrapper = styled.div`
  width: 10px;
  margin: 0 auto;
  transform-origin: 50%;
  transition: transform 0.2s ease-in-out;
`;

export let FilterBlockTitleWrapper = styled.div`
  font-weight: bold;
  font-size: 18px;
  margin-bottom: 18px;
  position: relative;
  cursor: pointer;

  @media (max-width: ${breakpoints.max.xl}) {
    font-size: 16px;
  }

  ${TitleIconWrapper} {
    transform: ${({ collapsed }) => (collapsed ? 'rotateZ(90deg)' : 'rotateZ(-90deg)')};
  }
`;
