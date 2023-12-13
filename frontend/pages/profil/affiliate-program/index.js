import dynamic from 'next/dynamic';
import React, { useState, useEffect, useCallback } from 'react';
import PageHead from '@components/pageHead/pageHead';
import Header from '@components/header/header';
import Content from '@components/content/content';
import Footer from '@components/footer/footer';
import useProtectedRoute from '@hooks/useProtectedRoute/useProtectedRoute';
import SiteColContainer from '@components/siteColContainer/siteColContainer';
import ProfileNavigator from '@components/profileNavigator/profileNavigator';
import PageTitle from '@components/pageTitle/pageTitle';
import InputText from '@components/inputText/inputText';
import ProfileDataTitle from '@components/profileDataTitle/profileDataTitle';
import Button from '@components/button/button';
import Overlay from '@components/overlay/overlay';
import OverlayCard from '@components/overlayCard/overlayCard';
import OverlayCardContentConfirmation from '@components/overlayCardContentConfirmation/overlayCardContentConfirmation';
import { handleApiRequest, getResponseById } from '@libs/api';
import useInputs from '@hooks/useInputs/useInputs';
import useRequest from '@hooks/useRequest/useRequest';
import { useQuery, useMutation } from 'react-query';
import { getSiteCode } from '@libs/site';
import AlertBox from '@components/alertBox/alertBox';
const ProfileEmpty = dynamic(() => import('@components/profileEmpty/profileEmpty'));
import {
    ProfilAffiliateProgramPageComponent,
    PageContent,
    ProfileNavigatorWrapper,
    DataWrapper,
    BillingInfo,
    BillingInfoHeader,
    BillingInfoTitle,
    BillingInfoData,
    AffiliateCodeSection,
    AffiliateCodeSectionTitle,
    AffiliateCodeWrapper,
    AffiliateCode,
    UrlGeneratorTitle,
    UrlGeneratorInputWrapper,
    GenerateButtonWrapper,
    GeneratedUrlInput,
    BalanceSection,
    BalanceSectionTitle,
    BalanceWrapper,
    Balance,
    BalancePretext,
    BalanceAmount,
    RedeemInfo,
    RedeemSection,
    RedeemSectionTitle,
    RedeemInputWrapper,
    Form,
    InputWrapper,
    Actions,
    Highlight,
    RedeemError,
    RedeemErrorClose,
    RedeemSuccess,
    RedeemSuccessClose,
    SeparatorTbody,
    Table,
    TableWrapper,
    TableSection,
    Th,
    Td,
    Thead,
    Tbody,
    Tr,
    SeparatorTd,
    Link,
    NotificationText,
    ModalNotification,
    AdditionalInfo,

} from '@components/pages/profilAffiliateProgramPage';
import url from '@libs/url';
import settingsVars from "@vars/settingsVars";


let requestTemplates = {
    headers: {
        Accept: 'application/json; charset=utf-8',
        'Content-type': 'application/json; charset=utf-8',
    },
    requests: {
        affiliateInfo: {
            method: 'GET',
            path: '/profile/affiliate-info',
            ref: 'affiliateInfo',
            request_id: 'affiliate-info',
            body: {},
        },
        affiliateUpdate: {
            method: 'POST',
            path: '/profile/affiliate-info',
            ref: 'affiliateUpdate',
            request_id: 'affiliate-update',
            body: {},
        },
        affiliateRedeem: {
            method: 'GET',
            path: '/profile/affiliate-redeem',
            ref: 'affiliateRedeem',
            request_id: 'affiliate-redeem',
            body: {},
        },
        affiliateRedeems: {
            method: 'GET',
            path: '/profile/affiliate-redeems',
            ref: 'affiliateRedeems',
            request_id: 'affiliate-redeems',
            body: {},
        },
    },
};
let inputsDefaults = {
    code: '',
    name: '',
    country: '',
    zip: '',
    city: '',
    address: '',
    vat: '',
};

let errorsDefaults = {
    code: '',
    name: '',
    country: '',
    zip: '',
    city: '',
    address: '',
    vat: '',
};
export default function ProfilAffiliateProgramPage() {
    let { user, authChecking } = useProtectedRoute();
    let [affiliateLink, setAffiliateLink] = useState('');
    let [affiliateBalance, setAffiliateBalance] = useState(0);
    let [formattedAffiliateBalance, setFormattedAffiliateBalance] = useState('0 Ft');
    let [defaultInputs, setDefaultInputs] = useState('');
    let [generatedAffiliateLink, setGeneratedAffiliateLink] = useState('');
    let [redeemConfirmOpen, setRedeemConfirmOpen] = useState(false);
    let [redeemError, setRedeemError] = useState(false);
    let [redeemSuccess, setRedeemSuccess] = useState(false);
    let [redeems, setRedeems] = useState([]);
    let [textCopiedModalOpen, setTextCopiedModalOpen] = useState(false);

    let [editBillingInfo, setEditBillingInfo] = useState(false);
    let [responseErrors, setResponseErrors] = useState(null);
    let { inputs, setInput, setInputs, errors } = useInputs(inputsDefaults, errorsDefaults);
    let affiliateInfoGetQuery = useQuery('affiliate-info', () => handleAffiliateInfo(requestGet.build()), {
        enabled: false,
        refetchOnWindowFocus: false,
        refetchOnMount: true,
        staleTime: 0,
        onSuccess: (data) => {
            let affiliateInfoResponse = getResponseById(data, 'affiliate-info');
            if (affiliateInfoResponse) {
                if (!affiliateInfoResponse.success) {
                    setResponseErrors(Object.values(affiliateInfoResponse.body.errors));
                } else {
                    // Success
                    let newInputs = {};
                    for (let key in affiliateInfoResponse.body.inputs) {
                        newInputs[key] = affiliateInfoResponse.body.inputs[key] ? affiliateInfoResponse.body.inputs[key] : '';
                    }
                    setInputs({ ...newInputs });
                    setAffiliateBalance(affiliateInfoResponse.body.balance);
                    setFormattedAffiliateBalance(hungarianMoneyStyle(affiliateInfoResponse.body.balance));
                    setDefaultInputs({ ...newInputs });
                }
            }
        },
    });
    let affiliateInfoUpdateQuery = useMutation('affiliate-update', (requestUpdateBuild) => handleAffiliateInfo(requestUpdateBuild), {
        onSuccess: (data) => {
            let affiliateInfoResponse = getResponseById(data, 'affiliate-update');

            if (affiliateInfoResponse) {
                if (!affiliateInfoResponse.success) {
                    setResponseErrors(Object.values(affiliateInfoResponse.body.errors));
                } else {
                    // Success
                    let newInputs = {};
                    for (let key in affiliateInfoResponse.body.inputs) {
                        newInputs[key] = affiliateInfoResponse.body.inputs[key] ? affiliateInfoResponse.body.inputs[key] : '';
                    }
                    setInputs({ ...newInputs });
                    setDefaultInputs({ ...newInputs });
                    setEditBillingInfo(false);
                }
            }
        },
    });
    let affiliateRedeemQuery = useQuery(['affiliate-redeem'], () => handleApiRequest(requestRedeem.build()), {
        enabled: false,
        refetchOnWindowFocus: false,
        refetchOnMount: false,
        staleTime: 0,
        onSuccess: (data) => {
            let redeemResponse = getResponseById(data, 'affiliate-redeem');
            if (!redeemResponse?.success) {
                setRedeemError(true);
                setRedeemSuccess(false);
            } else {
                // success
                setAffiliateBalance(redeemResponse.body.balance);
                setFormattedAffiliateBalance(hungarianMoneyStyle(redeemResponse.body.balance));
                setRedeemError(false);
                setRedeemSuccess(true);
                requestGetRedeems.commit();
            }
        },
    });
    let affiliateRedeemsGetQuery = useMutation(['affiliate-redeems'], () => handleApiRequest(requestGetRedeems.build()), {
        enabled: false,
        refetchOnWindowFocus: false,
        refetchOnMount: false,
        staleTime: 0,
        onSuccess: (data) => {
            let redeemsResponse = getResponseById(data, 'affiliate-redeems');
            if (!redeemsResponse?.success) {
                console.log(redeemsResponse.body.errors);
            } else {
                // success
                setRedeems(redeemsResponse.body);
            }
        },
    });

    let requestGet = useRequest(requestTemplates, affiliateInfoGetQuery);
    let requestUpdate = useRequest(requestTemplates, affiliateInfoUpdateQuery);
    let requestRedeem = useRequest(requestTemplates, affiliateRedeemQuery);
    let requestGetRedeems = useRequest(requestTemplates, affiliateRedeemsGetQuery);
    requestGet.addRequest('affiliateInfo');
    requestUpdate.addRequest('affiliateUpdate');
    requestRedeem.addRequest('affiliateRedeem');
    requestGetRedeems.addRequest('affiliateRedeems');
    function cancelHideOverlay() {
        setInputs(defaultInputs);
        setEditBillingInfo(false);
    }
    function saveAffiliateInfo() {
        requestUpdate.modifyHeaders((currentHeader) => {
            currentHeader['Authorization'] = `Bearer ${user.token}`;
        });
        requestUpdate.modifyRequest('affiliateUpdate', (currentRequest) => {
            let modifiedInputs = { ...inputs };
            delete modifiedInputs.code;

            currentRequest.body = modifiedInputs;
        });
        requestUpdate.commit();
    }
    function hungarianMoneyStyle($str) {
        const formatter = new Intl.NumberFormat('hu-HU', {
            style: 'currency',
            currency: 'HUF',
            minimumFractionDigits: 0,

        });
        return formatter.format($str);
    }
    let affilaiteMinimumRedeemAmount = hungarianMoneyStyle(user?.customer.affiliate_settings?.minimum_redeem_amount);
    let affilaiteRedeemsPerYear = user?.customer.affiliate_settings?.redeems_per_year;

    let handleRedeemConfirmClose = useCallback(() => {
        setRedeemConfirmOpen(false);
    }, []);
    let handleRedeemClick = useCallback(() => {
        setRedeemSuccess(false);
        if (affiliateBalance >= user?.customer.affiliate_settings?.minimum_redeem_amount) {
            setRedeemConfirmOpen(true);
            setRedeemError(false);
        } else {
            setRedeemError(true);
        }
    }, [affiliateBalance, user]);

    let handleRedeem = useCallback(() => {
        requestRedeem.modifyHeaders((currentHeader) => {
            currentHeader['Authorization'] = `Bearer ${user.token}`;
        });
        requestRedeem.commit();

        setRedeemConfirmOpen(false);
    }, [user, requestRedeem]);
    let generateLink = function () {
        let appendSign = '?';
        if (affiliateLink.includes('?')) {
            appendSign = '&';
        }
        setGeneratedAffiliateLink(affiliateLink + appendSign + 'aff=' + inputs.code);
    };
    let copyToClipboard = function () {
        navigator.clipboard.writeText(generatedAffiliateLink);
        setTextCopiedModalOpen(true);
    };

    useEffect(() => {
        if (!user) return;

        requestGet.modifyHeaders((currentHeader) => {
            currentHeader['Authorization'] = `Bearer ${user.token}`;
        });

        requestGet.commit();
    }, [user]);
    useEffect(() => {
        if (!user) return;
        requestGetRedeems.modifyHeaders((currentHeader) => {
            currentHeader['Authorization'] = `Bearer ${user.token}`;
        });
        requestGetRedeems.commit();
    }, [user]);
    if (!user || authChecking) return <div>checking</div>;
    return (
        <ProfilAffiliateProgramPageComponent>
            <PageHead></PageHead>
            <Header></Header>
            {editBillingInfo && (
                <Overlay onClick={cancelHideOverlay} fixed>
                    <OverlayCard onClose={cancelHideOverlay}>
                        <Form>
                            <InputWrapper>
                                <InputText
                                    value={inputs.name}
                                    error={errors.name}
                                    onChange={(e) => setInput('name', e.target.value)}
                                    label="Számlázási név"
                                ></InputText>
                            </InputWrapper>
                            <InputWrapper>
                                <InputText
                                    value={inputs.country}
                                    error={errors.country}
                                    onChange={(e) => setInput('country', e.target.value)}
                                    label="Számlázási ország"
                                ></InputText>
                            </InputWrapper>
                            <InputWrapper>
                                <InputText
                                    value={inputs.zip}
                                    error={errors.zip}
                                    onChange={(e) => setInput('zip', e.target.value)}
                                    label="Számlázási irányítószám"
                                ></InputText>
                            </InputWrapper>
                            <InputWrapper>
                                <InputText
                                    value={inputs.city}
                                    error={errors.city}
                                    onChange={(e) => setInput('city', e.target.value)}
                                    label="Számlázási város"
                                ></InputText>
                            </InputWrapper>
                            <InputWrapper>
                                <InputText
                                    value={inputs.address}
                                    error={errors.address}
                                    onChange={(e) => setInput('address', e.target.value)}
                                    label="Számlázási cím"
                                ></InputText>
                            </InputWrapper>
                            <InputWrapper>
                                <InputText
                                    value={inputs.vat}
                                    error={errors.vat}
                                    onChange={(e) => setInput('vat', e.target.value)}
                                    label="Adószám"
                                ></InputText>
                            </InputWrapper>
                            {responseErrors && <AlertBox responseErrors={responseErrors}></AlertBox>}
                            <Actions>
                                <Button
                                    buttonHeight="50px"
                                    onClick={saveAffiliateInfo}
                                    loading={affiliateInfoUpdateQuery.isLoading}
                                    disabled={affiliateInfoUpdateQuery.isLoading}
                                >
                                    Mentés
                                </Button>
                                <Button
                                    buttonHeight="50px"
                                    onClick={cancelHideOverlay}
                                >
                                    Mégse
                                </Button>
                            </Actions>
                        </Form>
                    </OverlayCard>
                </Overlay>
            )}
            {redeemConfirmOpen && (
                <Overlay fixed={false}>
                    <OverlayCard onClose={handleRedeemConfirmClose}>
                        <OverlayCardContentConfirmation
                            title={'Biztosan szeretnéd igényelni a ' + formattedAffiliateBalance + ' összegű jóváírást?'}
                            submitText="Igen"
                            cancelText="Mégsem"
                            onSubmit={handleRedeem}
                            onCancel={handleRedeemConfirmClose}
                        ></OverlayCardContentConfirmation>
                    </OverlayCard>
                </Overlay>
            )}
            {textCopiedModalOpen && (<Overlay fixed>
                <OverlayCard
                    onClick={() => {
                        setTextCopiedModalOpen(false);
                    }}
                >
                    <ModalNotification>
                    <NotificationText>Link másolása sikeres!</NotificationText>
                    </ModalNotification>
                </OverlayCard>
            </Overlay>)}
            <Content>
                <SiteColContainer>
                    <PageTitle className="d-none d-md-block">Profilom</PageTitle>
                    <PageContent className="row">
                        <ProfileNavigatorWrapper className="col-md-4 col-lg-3 d-none d-md-block">
                            <ProfileNavigator selected={9}></ProfileNavigator>
                        </ProfileNavigatorWrapper>
                        <DataWrapper className="col-md-8 col-lg-7 offset-0 offset-lg-1">
                            <ProfileDataTitle>Affiliate program</ProfileDataTitle>
                            <BillingInfo>
                                <BillingInfoHeader>
                                    <BillingInfoTitle>Számlázási adatok</BillingInfoTitle>
                                    <Button
                                        buttonHeight="50px"
                                        onClick={() => setEditBillingInfo(true)}
                                    >
                                        Szerkesztés
                                    </Button>
                                </BillingInfoHeader>
                                <BillingInfoData>Számlázási név: {inputs.name}</BillingInfoData>
                                <BillingInfoData>{inputs.country}</BillingInfoData>
                                <BillingInfoData>{inputs.zip}</BillingInfoData>
                                <BillingInfoData>{inputs.city}</BillingInfoData>
                                <BillingInfoData>{inputs.adddress}</BillingInfoData>
                                <BillingInfoData>Adószám: {inputs.vat}</BillingInfoData>
                            </BillingInfo>
                            <AffiliateCodeSection>
                                <AffiliateCodeSectionTitle>Egyedi affiliate kód</AffiliateCodeSectionTitle>
                                <AffiliateCodeWrapper>
                                    <AffiliateCode>
                                        {inputs.code}
                                    </AffiliateCode>
                                </AffiliateCodeWrapper>
                                <UrlGeneratorTitle>URL generátor</UrlGeneratorTitle>
                                <UrlGeneratorInputWrapper>
                                    <InputText
                                        value={affiliateLink}
                                        onChange={(e) => setAffiliateLink(e.target.value)}
                                        label="Megosztani kívánt Álomgyár oldal linkje"
                                    ></InputText>
                                </UrlGeneratorInputWrapper>
                                <GenerateButtonWrapper>
                                    <Button
                                        buttonHeight="50px"
                                        onClick={generateLink}
                                    >
                                        Link generálása
                                    </Button>
                                </GenerateButtonWrapper>
                                <GeneratedUrlInput>
                                    <InputText
                                        value={generatedAffiliateLink}
                                        disabled
                                        label="Megosztandó AFFILIATE link"
                                    ></InputText>
                                    <Button
                                        buttonHeight="50px"
                                        onClick={copyToClipboard}
                                    >
                                        Másolás!
                                    </Button>
                                </GeneratedUrlInput>
                            </AffiliateCodeSection>
                            <BalanceSection>
                                <BalanceSectionTitle>Aktuális affiliate egyenleg</BalanceSectionTitle>
                                <BalanceWrapper>
                                    <Balance>
                                        <BalancePretext>
                                            nettó
                                        </BalancePretext>
                                        <BalanceAmount>
                                            {formattedAffiliateBalance}
                                        </BalanceAmount>
                                    </Balance>
                                </BalanceWrapper>
                                <RedeemInfo>
                                    Amint ez az összeg eléri a <Highlight>{affilaiteMinimumRedeemAmount}</Highlight>, igényelheted az affiliate jutalék kifizetését. Figyelem: évente maximum <Highlight>{affilaiteRedeemsPerYear} alkalom-mal</Highlight> igényelhetsz kifizetést, és mindkét alkalommal szükséges elérned a minimum igénylési összeget.
                                </RedeemInfo>
                            </BalanceSection>
                            <RedeemSection>
                                <RedeemSectionTitle>Kifizetés igénylése</RedeemSectionTitle>
                                <RedeemInputWrapper>
                                    <Button
                                        buttonHeight="50px"
                                        onClick={handleRedeemClick}
                                    >
                                        Igénylés
                                    </Button>
                                </RedeemInputWrapper>
                                {redeemError && (
                                    <RedeemError>
                                        <div>
                                            Az egyenleged: {formattedAffiliateBalance}.
                                            Legalább {affilaiteMinimumRedeemAmount} egyenleg szükséges a jóváírás igényléséhez.
                                        </div>
                                        <RedeemErrorClose onClick={()=> { setRedeemError(false) }}>&times;</RedeemErrorClose>
                                    </RedeemError>
                                )}
                                {redeemSuccess && (
                                    <RedeemSuccess>
                                      <div>beváltás sikeres!</div>
                                      <RedeemSuccessClose onClick={()=> { setRedeemSuccess(false) }}>&times;</RedeemSuccessClose>
                                    </RedeemSuccess>
                                )}
                            </RedeemSection>
                            <TableSection>
                                <TableWrapper >
                                    {affiliateRedeemsGetQuery.isFetching && !redeems && <ProfileEmpty>Töltődik...</ProfileEmpty>}
                                    {typeof redeems !== 'undefined' && redeems.length < 1 && <ProfileEmpty>Még nem igényeltél jóváírást</ProfileEmpty>}
                                    {typeof redeems !== 'undefined' && redeems.length > 0 && (
                                        <>
                                        <Table>
                                            <Thead>
                                            <Tr>
                                                <Th>Igénylés azonosítója</Th>
                                                <Th>Igényelt összeg</Th>
                                                <Th>Igénylés dátuma</Th>
                                                <Th>PDF letöltése</Th>
                                            </Tr>
                                            </Thead>
                                            {redeems.map((redeem) => (
                                            <React.Fragment key={redeem.id}>
                                                <Tbody>
                                                    <Tr>
                                                        <Td><span>{redeem.name}</span></Td>
                                                        <Td><span>{hungarianMoneyStyle(redeem.amount)}</span></Td>
                                                        <Td><span>{redeem.date}</span></Td>
                                                        <Td><span><Link href={redeem.link} target="_blank" passHref>letöltés</Link></span></Td>
                                                    </Tr>
                                                </Tbody>
                                                <SeparatorTbody>
                                                    <Tr>
                                                        <SeparatorTd></SeparatorTd>
                                                    </Tr>
                                                </SeparatorTbody>
                                            </React.Fragment>
                                            ))}
                                        </Table>
                                        </>
                                    )}
                                </TableWrapper>
                            </TableSection>
                            <AdditionalInfo>
                                <p><b>Az Affiliate jóváírás igénylés PDF</b> alapján szükséges kiállítani a számlát.</p>
                                <p><b>Számlázási adataink:</b></p>
                                <p>Cégnév: Publish and More Kft.</p>
                                <p>Adószám: 23845338-2-41</p>
                                <p>Székhely: 1137 Budapest, Pozsonyi út 10. 1/4.</p>


                                <p>Számla kelte: a kiállítás napja</p>
                                <p>Teljesítés: a számla kelte +30 nap</p>
                                <p>Fizetési határidö: a számla kelte +30 nap</p>


                                <p><b><a href='mailto:penzugy@alomgyar.hu'>E-mail: penzugy@alomgyar.hu</a></b></p>
                                <p>Tárgy: alomgyar.hu affiliate jutalék + Affiliate jóváírás <b>igénylés (PDF) azonosítója</b></p>
                                <p>Iroda (postán ide várjuk a számlákat): 1065 Budapest, Bajcsy-Zsilinszky út 57., A épület 3. emelet</p>


                                <p>Fontos: Az általunk megjelölt jutalék nettó (áfa mentes) összeg. Amennyiben a számlát kiállító áfakörös, akkor 27% áfával növelt értéken kell kiállítani. Minden esetben javasoljuk, hogy könyvelövel egyeztessen a partner.
                                A számlát hibás adattartalommal (például a számla tárgyából hiányzik az elszámolás egyedi kódja) nem tudjuk elfogadni.
                                Évente legfeljebb {affilaiteRedeemsPerYear} elszámolást tudunk befogadni, amennyiben az adott elszámolás legalább nettó {affilaiteMinimumRedeemAmount}.</p>
                            </AdditionalInfo>
                        </DataWrapper>
                    </PageContent>
                </SiteColContainer>
            </Content>
            <Footer></Footer>
        </ProfilAffiliateProgramPageComponent>
    );
}

function handleAffiliateInfo(requestBuild) {
    let settings = settingsVars.get(url.getHost());
    return fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/v1/${getSiteCode(settings.key)}/composite`, {
        method: 'POST',
        headers: requestBuild.headers,
        body: JSON.stringify(requestBuild.body),
    })
        .then((response) => {
            if (!response.ok) throw new Error(`API response: ${response.status}`);
            return response.json();
        })
        .then((data) => data)
        .catch((error) => console.log(error));
}
