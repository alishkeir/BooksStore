import React from 'react';
import { FadeInComponent } from './fadeIn.styled';

export default function FadeIn({ duration = 1000, delay = 0, children, ...rest }) {
  return (
    <FadeInComponent
      {...rest}
      style={{
        ...(rest.style || {}),
        animationDuration: duration + 'ms',
        animationDelay: delay + 'ms',
      }}
    >
      {children}
    </FadeInComponent>
  );
}
