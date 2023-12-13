import Image from 'next/legacy/image';
import { useEffect } from 'react';
import { useState } from 'react';

const OptimizedImage = ({ src, alt, width, height, layout, ...otherProps }) => {
  const [imageSrc, setImageSrc] = useState(typeof src === 'string' ? src : src?.[0]);
  const handleImageError = () => {
    if (Array.isArray(src) && src.length > 1) {
      setImageSrc(src[1]);
    }
  };
  useEffect(() => {
    setImageSrc(() => typeof src === 'string' ? src : src?.[0])
  }, [src])
  return (
    <>
      <Image
        src={imageSrc}
        width={width}
        height={height}
        layout={layout}
        alt={alt}
        onError={handleImageError}
        loading='lazy'
        {...otherProps}
      />
    </>
  );
};

export default OptimizedImage;
