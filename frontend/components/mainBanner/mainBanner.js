import Link from 'next/link';
import { Col, ImageWrapper, MainBannerWrapper, Row } from './mainBanner.styled';
import OptimizedImage from '@components/Images/OptimizedImage';

export default function MainBanner({ banner }) {
  return (
    <MainBannerWrapper className="container">
      <Row className="row">
        <Col className="col-12">
          <Link href={banner.link ? banner.link : '/'} passHref legacyBehavior>
            <ImageWrapper>
              {banner?.cover && <OptimizedImage src={banner.cover} layout="responsive" width={1554} height={350} objectFit="cover" alt={banner.title}></OptimizedImage>}
            </ImageWrapper>
          </Link>
        </Col>
      </Row>
    </MainBannerWrapper>
  );
}
