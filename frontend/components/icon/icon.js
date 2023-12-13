import React from 'react';
import dynamic from 'next/dynamic';
import styled from '@emotion/styled';

let Icon = React.forwardRef(function Icon(props, ref) {
  let { type = 'search', iconWidth = 0, iconHeight = 0 } = props;

  let DynamicComponent = dynamic(() => import(`./icon-${type}`));

  let IconElementWrapper = styled.div`
    font-size: 0;
    pointer-events: none;
    display: inline-block;
    max-width: 100%;
    width: ${() => (iconWidth ? `${iconWidth}` : 'auto')};
    height: ${() => (iconHeight ? `${iconHeight}` : 'auto')};
    vertical-align: middle;
  `;

  let IconElement = styled(DynamicComponent)`
    width: ${() => (iconWidth ? `${iconWidth}` : 'auto')};
    height: ${() => (iconHeight ? `${iconHeight}` : 'auto')};
    vertical-align: top;

    svg {
      vertical-align: top;
    }
  `;

  return (
    <IconElementWrapper>
      <IconElement {...props} ref={ref}></IconElement>
    </IconElementWrapper>
  );
});

export default React.memo(Icon);
