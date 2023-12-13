import React from 'react';
import aspectratio from 'aspectratio';
import OptimizedImage from '@components/Images/OptimizedImage';

export default React.memo(function ImageResizer(props) {
  let { src, maxWidth = 230, maxHeight = 230, layout = 'intrinsic' } = props;

  let resizedValues = [230, 230];

  let fileResolution = src.split('_').pop().split('.').shift().split('-');

  resizedValues = aspectratio.resize(Number(fileResolution[0]), Number(fileResolution[1]), Number(maxWidth), Number(maxHeight));

  return <OptimizedImage src={src} layout={layout} width={resizedValues[0]} height={resizedValues[1]} alt=""></OptimizedImage>;
});
