import dynamic from 'next/dynamic';
import { useRef, useState, useEffect } from 'react';
import Head from 'next/head';
import useRequest from '@hooks/useRequest/useRequest';

const Header = dynamic(() => import('@components/header/header'));
const HeaderPromo = dynamic(() => import('@components/headerPromo/headerPromo'));
import { useQuery, QueryClient } from 'react-query';
import { dehydrate } from 'react-query/hydration';
import PageTitle from '@components/pageTitle/pageTitle';
const Content = dynamic(() => import('@components/content/content'));
const SiteColContainer = dynamic(() => import('@components/siteColContainer/siteColContainer'));
const BookShopCard = dynamic(() => import('@components/bookShopCard/bookShopCard'));
const Footer = dynamic(() => import('@components/footer/footer'));
const Dropdown = dynamic(() => import('@components/dropdown/dropdown'));
import useMediaQuery from '@hooks/useMediaQuery/useMediaQuery';
import breakpoints from '@vars/breakpoints';
import { getResponseById, getMetadata, handleApiRequest } from '@libs/api';
import inactiveMarker from '@assets/images/markers/marker-alomgyar-inactive.png';
import activeMarker from '@assets/images/markers/marker-alomgyar-active.png';

import {
    BookshopsTitle,
    KonyvesboltokPageWrapper,
    Map,
    MapWrapper,
    Notification,
    NotificationMessage,
    NotificationTitle,
    ShopsWrapper,
    Title,
} from '@components/pages/konyvesboltok.styled';
import DynamicHead from '@components/heads/DynamicHead';

let requestTemplates = {
    headers: {
        Accept: 'application/json; charset=utf-8',
        'Content-type': 'application/json; charset=utf-8',
    },
    requests: {
        'shops-get': {
            method: 'GET',
            request_id: 'shops-get',
            path: '/pages/shops',
            ref: 'list',
        },
    },
};

export default function KonyvesboltokPage({ metadata }) {
    let mapDivRef = useRef();
    let markersRef = useRef([]);
    let [map, setMap] = useState();
    let isMinLG = useMediaQuery(`(min-width: ${breakpoints.min.lg})`);
    let options = useRef({
        center: { lat: 47.49691, lng: 19.06912 },
        zoom: 7,
    });

    let queryShops = useQuery('shops-get', () => handleApiRequest(requestShops.build()), {
        enabled: false,
        refetchOnWindowFocus: false,
        refetchOnMount: false,
        staleTime: 0,
    });

    let requestShops = useRequest(requestTemplates, queryShops);

    requestShops.addRequest('shops-get');

    let bookShopsResponse = getResponseById(queryShops.data, 'shops-get');
    let [bookShop, setBookShop] = useState(bookShopsResponse.body.book_shops[0]);
    let [bookShops, setBookShops] = useState(bookShopsResponse.body.book_shops);

    function handleSetBookshop(selectedBookShop) {
        let newBookShops = bookShops.map((shop) => ({
            ...shop,
            selected: selectedBookShop.id === shop.id ? true : false,
            label: shop.title,
        }));

        setBookShops(newBookShops);
        setBookShop(selectedBookShop);
    }

    function onMount(map) {
        bookShops.forEach((shop) => {
            let marker = new window.google.maps.Marker({
                map,
                position: { lat: Number(shop.latitude), lng: Number(shop.longitude) },
                title: shop.title,
                icon: Number(bookShop.latitude) === Number(shop.latitude) ? activeMarker.src : inactiveMarker.src,
                shopId: shop.id,
            });
            markersRef.current.push(marker);
            marker.addListener(`click`, (e) => {
                if (e.latLng.lat() === Number(shop.latitude) && e.latLng.lng() === Number(shop.longitude)) {
                    marker.setIcon(activeMarker.src);
                    handleSetBookshop(shop);
                }
            });
        });
    }

    useEffect(() => {
        return () => {
            delete window.google.maps;
        };
    }, []);

    useEffect(() => {
        window.initMap = () => {
            setMap(new window.google.maps.Map(mapDivRef.current, options.current));
        };
    }, []);

    useEffect(() => {
        if (map && typeof onMount === `function`) onMount(map);
    }, [map]);

    useEffect(() => {
        let bookShop = bookShopsResponse.body.book_shops.map((shop, idx) => {
            return { ...shop, label: shop.title, selected: idx === 0 ? true : false };
        });
        handleSetBookshop(bookShop[0]);
    }, [bookShopsResponse]);

    useEffect(() => {
        if (!bookShops) return;
        markersRef.current.forEach((marker) => {
            if (marker.shopId === bookShop.id) {
                marker.setIcon(activeMarker.src);
            } else {
                marker.setIcon(inactiveMarker.src);
            }
        });
    }, [bookShops, bookShop, markersRef]);

    return (
        <KonyvesboltokPageWrapper>
            <DynamicHead title="könyvesboltok" image={bookShops.length > 0 && bookShops[0].image} metadata={metadata} />
            {process.browser && (
                <Head>
                    <script
                        async
                        defer
                        src={`https://maps.googleapis.com/maps/api/js?key=${process.env.NEXT_PUBLIC_GOOGLE_MAP_API_KEY}&callback=initMap`}
                    ></script>
                </Head>
            )}
            <Header promo={HeaderPromo}></Header>
            <Content>
                <SiteColContainer>
                    <PageTitle mbd={30} mbm={26}>
                        {bookShopsResponse.body.page_title}
                    </PageTitle>
                    {bookShopsResponse.body.notification && (
                        <Notification>
                            <NotificationTitle>{bookShopsResponse.body.notification.title}</NotificationTitle>
                            <NotificationMessage>{bookShopsResponse.body.notification.message}</NotificationMessage>
                        </Notification>
                    )}
                    <Title>Országszerte
                        már {bookShops?.length ? bookShops?.length : 11} helyen</Title>
                    <ShopsWrapper className="row">
                        <MapWrapper className="col-lg-8">
                            <Map ref={mapDivRef} id="map"></Map>
                        </MapWrapper>
                        <div className="col-lg-4">
                            {isMinLG ? (
                                <Dropdown width="100%" options={bookShops} placeholder="Boltok"
                                          onSelect={(selected) => handleSetBookshop(selected)}></Dropdown>
                            ) : (
                                <BookshopsTitle>Boltjaink</BookshopsTitle>
                            )}
                            {isMinLG
                                ? [bookShops.find((shop) => shop.id === bookShop.id)].map((shop, index) => (
                                    <BookShopCard isMinLG={isMinLG} key={index} shop={shop}></BookShopCard>
                                ))
                                : bookShops.map((shop, index) => (
                                    <BookShopCard isMinLG={isMinLG} key={index} shop={shop} selectedShop={bookShop} setBookShop={handleSetBookshop}></BookShopCard>
                                ))}
                        </div>
                    </ShopsWrapper>
                </SiteColContainer>
            </Content>
            <Footer></Footer>
        </KonyvesboltokPageWrapper>
    );
}

export async function getStaticProps() {
    const queryClient = new QueryClient();

    const metadata = await getMetadata('/konyvesboltok');

    await queryClient.prefetchQuery('shops-get', () =>
        handleApiRequest({
            body: {
                request: [requestTemplates.requests['shops-get']],
            },
        }),
    );

    return {
        props: {
            dehydratedState: dehydrate(queryClient),
            metadata: metadata.length > 0 ? metadata[0].data : null,
        }, revalidate: 90
    };
}
