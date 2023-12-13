import styled from '@emotion/styled';

export let FadeInComponent = styled.div`
  @keyframes fadeIn {
    from {
      opacity: 0;
    }
    to {
      opacity: 1;
    }
  }

  @media (prefers-reduced-motion: no-preference) {
    animation-name: fadeIn;
    animation-fill-mode: backwards;
  }
`;
