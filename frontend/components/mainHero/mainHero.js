import Link from 'next/link';
import SwiperCore, { Pagination, Autoplay } from 'swiper';
import { Swiper, SwiperSlide } from 'swiper/react';
import MainStoreMap from '@components/mainStoreMap/mainStoreMap';
import { Col, Container, ImageWrapper, MainHeroComponent, MapImageWrapper, Row, SwiperWrapper } from './mainHero.styled';
import OptimizedImage from '@components/Images/OptimizedImage';

SwiperCore.use([Pagination, Autoplay]);

export default function MainHero({ carousels, banner })
{
  return (
    <MainHeroComponent>
      <Container className="container">
        <Row className="row">
          <Col className="col-12 col-xl-8">
            <SwiperWrapper>
              <Swiper
                spaceBetween={20}
                slidesPerView={1}
                speed={1000}
                autoplay={{
                  delay: 5000,
                }}
                pagination={{ clickable: true }}
              >
                {carousels?.map((item, index) => (
                  <SwiperSlide key={index}>
                    <Link href={item.url} passHref legacyBehavior>
                      <ImageWrapper>
                        <OptimizedImage src={item.cover} width="1003" height="400" layout="intrinsic" alt=""></OptimizedImage>
                      </ImageWrapper>
                    </Link>
                  </SwiperSlide>
                ))}
              </Swiper>
            </SwiperWrapper>
          </Col>
          <Col className="col-sm-4 d-none d-xl-block">
            <Link href={banner?.link ? banner.link : '/'} passHref legacyBehavior>
              <MapImageWrapper>
                <MainStoreMap banner={banner}></MainStoreMap>
              </MapImageWrapper>
            </Link>
          </Col>
        </Row>
      </Container>
    </MainHeroComponent>
  );
}
