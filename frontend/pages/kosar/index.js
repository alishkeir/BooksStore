import { useCallback, useEffect, useState } from 'react';
import dynamic from 'next/dynamic';
import router from 'next/router';
import { useQuery, useMutation } from 'react-query';
const PageHead = dynamic(() => import('@components/pageHead/pageHead'));
const Header = dynamic(() => import('@components/header/header'));
const BookScrollList = dynamic(() => import('@components/bookScrollList/bookScrollList'));
const Content = dynamic(() => import('@components/content/content'));
const SiteColContainer = dynamic(() => import('@components/siteColContainer/siteColContainer'));
const Footer = dynamic(() => import('@components/footer/footer'));
const PageTitle = dynamic(() => import('@components/pageTitle/pageTitle'));
const CartItem = dynamic(() => import('@components/cartItem/cartItem'));
const Button = dynamic(() => import('@components/button/button'));
const HeaderPromo = dynamic(() => import('@components/headerPromo/headerPromo'));
import Currency from '@libs/currency';
import useMediaQuery from '@hooks/useMediaQuery/useMediaQuery';
import breakpoints from '@vars/breakpoints';
import { handleApiRequest, getMetadata } from '@libs/api';
import useRequest from '@hooks/useRequest/useRequest';
import { getResponseById } from '@libs/api';
import { analytics } from '@libs/analytics';
import useUser from '@hooks/useUser/useUser';
import { useDispatch } from 'react-redux';
import { updateRedirectAfterLogin, updateSidebar } from '@store/modules/ui';

let Overlay = dynamic(() => import('@components/overlay/overlay'));
let OverlayCard = dynamic(() => import('@components/overlayCard/overlayCard'));
let OverlayCardContentGeneral = dynamic(() => import('@components/overlayCardContentGeneral/overlayCardContentGeneral'));
import {
    BestsellersMobileWrapper,
    ButtonWrapper,
    CartItemWrapper,
    CartWrapper,
    DetailsReveal,
    DetailsRevealWrapper,
    EBookNotificationBody,
    EBookNotificationContenrWrapper,
    EBookNotificationTitle,
    EBookNotificationWrapper,
    FinalPrice,
    FinalPriceTotal,
    FinalPriceWrapper,
    KosarPageWrapper,
    PageContainer,
    PageWarning,
    RecommendationWrapper,
    TextRevealButtonWrapper,
    Title,
    SideTitle,
    SideLoader,
    CartLoader,
} from '@components/pages/kosarPage.styled';
import DynamicHead from '@components/heads/DynamicHead';

let defaultConfig = {
    breakpoints: [
        {
            width: 150,
            gutter: 20,
            count: 1,
        },
        {
            width: 330,
            gutter: 20,
            count: 2,
        },
        {
            width: 500,
            gutter: 20,
            count: 2,
        },
        {
            width: 600,
            gutter: 20,
            count: 3,
        },
    ],
};

const PROMOTIONS_REQUEST_ID = 'cart-promotions-get';
const CART_ITEMS_REQUEST_ID = 'cart-items-get';
const INCREMENT_REQUEST_ID = 'cart-item-increment';
const DECREMENT_REQUEST_ID = 'cart-item-decrement';

let requestTemplates = {
    headers: {
        Accept: 'application/json; charset=utf-8',
        'Content-type': 'application/json; charset=utf-8',
    },
    requests: {
        'cart-promotions-get': {
            method: 'POST',
            path: '/carts',
            ref: 'cartPageLists',
            request_id: PROMOTIONS_REQUEST_ID,
            body: {
                guest_token: null,
            },
        },
        'cart-items-get': {
            method: 'POST',
            path: '/carts',
            ref: 'get',
            request_id: CART_ITEMS_REQUEST_ID,
            body: {
                guest_token: null,
            },
        },
        'cart-item-increment': {
            method: 'POST',
            path: '/carts',
            ref: 'increment',
            request_id: INCREMENT_REQUEST_ID,
            body: {
                guest_token: null,
                product_id: null,
            },
        },
        'cart-item-decrement': {
            method: 'POST',
            path: '/carts',
            ref: 'decrement',
            request_id: DECREMENT_REQUEST_ID,
            body: {
                guest_token: null,
                product_id: null,
            },
        },
    },
};

export default function KosarPage({metadata}) {
    let dispatch = useDispatch();
    let { authChecking, actualUser, userAddCart } = useUser();
    let [books, setBooks] = useState([]);
    let [recommendedBooks, setRecommendedBooks] = useState([]);
    let [bestsellers, setBestsellers] = useState([]);
    let [grossPrice, setGrossPrice] = useState(0);
    let [actualPrice, setActualPrice] = useState(0);
    let [booksOpen, setBooksOpen] = useState(false);
    let [hasEbook, setHasEBook] = useState(false);
    let [orderOnlyAlone, setOrderOnlyAlone] = useState(null);
    let [overlayEbookWarningOpen, setOverlayEbookWarningOpen] = useState(false);

    let isMinMd = useMediaQuery(`(min-width: ${breakpoints.min.md})`);

    let queryCart = useQuery(CART_ITEMS_REQUEST_ID, () => handleApiRequest(requestCart.build()), {
        enabled: false,
        refetchOnWindowFocus: false,
        refetchOnMount: false,
        staleTime: 0,
        onSuccess: (data) => {
            let cartItems = getResponseById(data, CART_ITEMS_REQUEST_ID);

            if (cartItems?.success) {
                setBooks(cartItems.body.cart_items);
                setGrossPrice(cartItems.body.total_amount_full_price);
                setActualPrice(cartItems.body.total_amount);
                setOrderOnlyAlone(cartItems.body.order_only_alone);
            }
        },
    });

    let queryPromotions = useQuery(PROMOTIONS_REQUEST_ID, () => handleApiRequest(requestPromotions.build()), {
        enabled: false,
        refetchOnWindowFocus: false,
        refetchOnMount: false,
        staleTime: 0,
        onSettled: (data) => {
            let promotions = getResponseById(data, PROMOTIONS_REQUEST_ID);

            if (promotions?.success) {
                setBestsellers(promotions.body.super_cheap_list);
                setRecommendedBooks(promotions.body.others_bought_these);
            }
        },
    });

    let queryItemIncrement = useMutation(INCREMENT_REQUEST_ID, (requestBuild) => handleApiRequest(requestBuild), {
        onSuccess: (data) => {
            userAddCart(data.response[0].body);
            setBooks(data.response[0].body.cart_items);
            setGrossPrice(data.response[0].body.total_amount_full_price);
            setActualPrice(data.response[0].body.total_amount);
            setOrderOnlyAlone(data.response[0].body.body.order_only_alone);
        },
    });

    let queryItemDecrement = useMutation(DECREMENT_REQUEST_ID, (requestBuild) => handleApiRequest(requestBuild), {
        onSuccess: (data) => {
            userAddCart(data.response[0].body);
            setBooks(data.response[0].body.cart_items);
            setGrossPrice(data.response[0].body.total_amount_full_price);
            setActualPrice(data.response[0].body.total_amount);
            setOrderOnlyAlone(data.response[0].body.body.order_only_alone);
        },
    });

    let requestCart = useRequest(requestTemplates, queryCart);
    let requestPromotions = useRequest(requestTemplates, queryPromotions);
    let requestItemIncrement = useRequest(requestTemplates, queryItemIncrement);
    let requestItemDecrement = useRequest(requestTemplates, queryItemDecrement);

    let handleChangeAmountItem = useCallback(
        (e, book, type) => {
            e.preventDefault();
            let typeOfRequest = type === INCREMENT_REQUEST_ID ? requestItemIncrement : requestItemDecrement;

            if (!actualUser) return;

            typeOfRequest.addRequest(type);

            if (actualUser.type === 'guest') {
                typeOfRequest.modifyHeaders((currentHeader) => {
                    currentHeader['Authorization'] = null;
                });

                typeOfRequest.modifyRequest(type, (currentRequest) => {
                    currentRequest.body.product_id = book.id;
                    currentRequest.body.guest_token = actualUser.token;
                });
            } else {
                typeOfRequest.modifyHeaders((currentHeader) => {
                    currentHeader['Authorization'] = `Bearer ${actualUser.token}`;
                });

                typeOfRequest.modifyRequest(type, (currentRequest) => {
                    currentRequest.body.product_id = book.id;
                    currentRequest.body.guest_token = null;
                });
            }

            typeOfRequest.commit();

            let itemObj = {
                id: book.id,
                name: book.title,
                list_name: router.route,
                brand: null,
                category: book.type === 0 ? 'book' : 'ebook',
                variant: book.type === 0 ? 'book' : 'ebook',
                list_position: 1,
                quantity: 1,
                price: book.price_sale,
            };

            if (type === INCREMENT_REQUEST_ID) analytics.addToCart(itemObj);
            if (type === DECREMENT_REQUEST_ID) analytics.removeFromCart(itemObj);
        },
        [actualUser],
    );

    let handleCheckoutButtonClick = useCallback(() => {
        if (actualUser?.type === 'user') {
            if (hasEbook) {
                setOverlayEbookWarningOpen(true);
            } else {
                router.push('/penztar/szamlazasi-adatok');
            }
        } else if (actualUser?.type === 'guest') {
            dispatch(updateSidebar({ open: true, type: 'login' }));
            dispatch(updateRedirectAfterLogin('/penztar/szamlazasi-adatok'));
        }
    }, [actualUser, hasEbook]);

    let handleOverlayEbookWarningClose = useCallback(() => {
        setOverlayEbookWarningOpen(false);
    }, []);

    let handleOverlayEbookWarningSubmit = useCallback(() => {
        router.push('/penztar/szamlazasi-adatok');
    }, []);

    // Requesting user cart
    useEffect(() => {
        if (!actualUser) return;

        requestCart.resetRequest();
        requestCart.addRequest(CART_ITEMS_REQUEST_ID);

        if (actualUser.type === 'guest') {
            requestCart.modifyHeaders((currentHeader) => {
                currentHeader['Authorization'] = null;
            });

            requestCart.modifyRequest(CART_ITEMS_REQUEST_ID, (currentRequest) => {
                currentRequest.body.guest_token = actualUser.token;
            });
        } else {
            requestCart.modifyHeaders((currentHeader) => {
                currentHeader['Authorization'] = `Bearer ${actualUser.token}`;
            });

            requestCart.modifyRequest(CART_ITEMS_REQUEST_ID, (currentRequest) => {
                currentRequest.body.guest_token = null;
            });
        }

        requestCart.commit();
    }, [actualUser]);

    // Requesting promotions
    useEffect(() => {
        if (!actualUser) return;

        requestPromotions.resetRequest();
        requestPromotions.addRequest(PROMOTIONS_REQUEST_ID);

        if (actualUser.type === 'guest') {
            requestPromotions.modifyHeaders((currentHeader) => {
                currentHeader['Authorization'] = null;
            });

            requestPromotions.modifyRequest(PROMOTIONS_REQUEST_ID, (currentRequest) => {
                currentRequest.body.guest_token = actualUser.token;
            });
        } else {
            requestPromotions.modifyHeaders((currentHeader) => {
                currentHeader['Authorization'] = `Bearer ${actualUser.token}`;
            });

            requestPromotions.modifyRequest(PROMOTIONS_REQUEST_ID, (currentRequest) => {
                currentRequest.body.guest_token = null;
            });
        }

        requestPromotions.commit();
    }, [actualUser]);

    useEffect(() => {
        if (!books) return;
        let isContainEBook = books.find((book) => book.type === 1);

        setHasEBook(isContainEBook);
    }, [books]);

    useEffect(() => {
        if (authChecking) return;
        if (!actualUser) {
            router.replace('/');
            return;
        }

        if (!actualUser.customer.cart.cart_items.length) {
            router.push('/');
        }
    }, [authChecking, actualUser]);

    // Analytics
    useEffect(() => {
        if (!queryCart.isFetched) return;

        let firstCartResponse = getResponseById(queryCart.data, CART_ITEMS_REQUEST_ID);

        window.gtag('event', 'page_view', {
            dynx_itemid: firstCartResponse.body.cart_items.map((item) => String(item.id)),
            dynx_pagetype: 'conversionintent',
            dynx_totalvalue: firstCartResponse.body.total_amount,
        });
    }, [queryCart.isFetched]);

    return (
        <KosarPageWrapper>
            <DynamicHead metadata={metadata} />
            <PageHead></PageHead>
            <Header promo={HeaderPromo}></Header>
            {overlayEbookWarningOpen && (
                <Overlay fixed={false}>
                    <OverlayCard onClose={handleOverlayEbookWarningClose}>
                        <OverlayCardContentGeneral
                            title="Kedves Vásárlónk!"
                            text="A kosaradba raktál e-könyvet. Az elektronikus vagy digitális könyvet (a vásárlás után) csak letölteni tudod, azt papír könyvként nem küldjük el neked. Az e-könyv a hatályos jogszabályok szerint szolgáltatásnak minősül, arra nem vonatkozik a 14 napos elállás joga."
                            submitText="Értem, így szeretném"
                            cancelText="Vissza a kosaramhoz"
                            onSubmit={handleOverlayEbookWarningSubmit}
                            onCancel={handleOverlayEbookWarningClose}
                        ></OverlayCardContentGeneral>
                    </OverlayCard>
                </Overlay>
            )}
            <Content>
                <SiteColContainer>
                    <PageTitle mbd={12} mbm={26}>
                        Kosár
                    </PageTitle>

                    <PageContainer className="row">
                        {!isMinMd && (
                            <>
                                {bestsellers.length !== 0 ? (
                                    <BestsellersMobileWrapper isOpen={booksOpen}>
                                        <Title color={'monza'} mtm={20} mbd={50} mbm={40}>
                                            Sikerkönyvek szuperolcsón!
                                        </Title>
                                        <DetailsRevealWrapper isOpen={booksOpen}>
                                            <BookScrollList isCart={true} defaultConfig={defaultConfig} books={bestsellers}></BookScrollList>
                                            {!booksOpen && <DetailsReveal></DetailsReveal>}
                                        </DetailsRevealWrapper>
                                        <TextRevealButtonWrapper isOpen={booksOpen}>
                                            {!booksOpen && (
                                                <Button
                                                    onClick={() => {
                                                        setBooksOpen(true);
                                                    }}
                                                    buttonHeight={'50px'}
                                                    type={'secondary'}
                                                >
                                                    Megnézem az akciós könyveket
                                                </Button>
                                            )}
                                        </TextRevealButtonWrapper>
                                    </BestsellersMobileWrapper>
                                ) : queryPromotions.isFetching ? (
                                    <SideLoader>Töltöm a legjobb ajánlatokat...</SideLoader>
                                ) : null}
                            </>
                        )}

                        <CartItemWrapper className="col-md-6">
                            <PageWarning>
                                Kedves Vásárlóink!
                                <br />
                                Szeretnénk tájékoztatni, hogy a mindenhol megkezdődő leltározások miatt a rendelések teljesítésének ideje néhány nappal meghosszabbodhat.
                            </PageWarning>
                            <Title color={'mineShaft'}>A kosarad tartalma</Title>
                            {queryCart.isFetching && <CartLoader>Töltöm a kosarad tartalmát...</CartLoader>}

                            {!queryCart.isFetching && (
                                <>
                                    {books.map((book) => (
                                        <CartItem
                                            key={book.id}
                                            {...book}
                                            amountBook={book.amount}
                                            incrementAmount={(e) => {
                                                handleChangeAmountItem(e, book, INCREMENT_REQUEST_ID);
                                            }}
                                            decrementAmount={(e) => {
                                                handleChangeAmountItem(e, book, DECREMENT_REQUEST_ID);
                                            }}
                                        ></CartItem>
                                    ))}
                                    {hasEbook && (
                                        <EBookNotificationWrapper>
                                            <EBookNotificationContenrWrapper>
                                                <EBookNotificationTitle>Kosarad e-könyvet tartalmaz</EBookNotificationTitle>
                                                <EBookNotificationBody>Ne feledd, ezt csak digitálisan tudjuk számodra elküldeni!</EBookNotificationBody>
                                            </EBookNotificationContenrWrapper>
                                        </EBookNotificationWrapper>
                                    )}
                                    {orderOnlyAlone && (
                                        <EBookNotificationWrapper>
                                            <EBookNotificationContenrWrapper>
                                                <EBookNotificationTitle>Kosarad együtt meg nem vásárolható tételeket tartalmaz</EBookNotificationTitle>
                                                <EBookNotificationBody>{orderOnlyAlone?.title} csak önmagában vásárolható meg!</EBookNotificationBody>
                                            </EBookNotificationContenrWrapper>
                                        </EBookNotificationWrapper>
                                    )}
                                    <CartWrapper>
                                        <Title color={'mineShaft'}>Kosár összesen</Title>
                                        <FinalPriceWrapper>
                                            {!!actualPrice && <FinalPrice>{Currency.format(grossPrice)}</FinalPrice>}
                                            <FinalPriceTotal>{Currency.format(actualPrice)}</FinalPriceTotal>
                                        </FinalPriceWrapper>
                                    </CartWrapper>
                                    <ButtonWrapper>
                                        <Button
                                            type="primary"
                                            buttonWidth={isMinMd ? '253px' : '100%'}
                                            buttonHeight="50px"
                                            onClick={handleCheckoutButtonClick}
                                            disabled={orderOnlyAlone !== null}
                                        >
                                            Tovább a számlázáshoz
                                        </Button>
                                    </ButtonWrapper>
                                </>
                            )}
                        </CartItemWrapper>

                        <RecommendationWrapper className="col-md-6">
                            {isMinMd && (
                                <>
                                    {bestsellers.length !== 0 ? (
                                        <>
                                            <SideTitle mbd={50} mbm={40}>
                                                Sikerkönyvek szuperolcsón!
                                            </SideTitle>
                                            <BookScrollList isCart={true} defaultConfig={defaultConfig} books={bestsellers}></BookScrollList>
                                        </>
                                    ) : queryPromotions.isFetching ? (
                                        <SideLoader>Töltöm a legjobb ajánlatokat...</SideLoader>
                                    ) : null}
                                    {recommendedBooks.length !== 0 ? (
                                        <>
                                            <SideTitle mbd={50} mtd={70} mtm={52} mbm={40}>
                                                Mások ezeket is megvették
                                            </SideTitle>
                                            <BookScrollList isCart={true} defaultConfig={defaultConfig} books={recommendedBooks}></BookScrollList>
                                        </>
                                    ) : (
                                        ''
                                    )}
                                </>
                            )}
                        </RecommendationWrapper>
                    </PageContainer>
                </SiteColContainer>
            </Content>
            <Footer></Footer>
        </KosarPageWrapper>
    );
}

KosarPage.getInitialProps = async () =>
{
    const metadata = await getMetadata('/kereses')
    return { metadata: metadata.length > 0 ? metadata[0].data : null }
}
