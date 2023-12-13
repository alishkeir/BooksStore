import React, { useState } from 'react';
import PageHead from '@components/pageHead/pageHead';
import { PageContent, ProfilEkonyveimPageComponent } from '@components/pages/profilEkonyveimPage.styled';
import dynamic from 'next/dynamic';
import { ButtonWrapper, Form, InputPasswordAgainWrapper, InputPasswordWrapper } from '@components/sideModalNewPass/sideModalNewPass.styled';
import InputPassword from '@components/inputPassword/inputPassword';
import SideModalError from '@components/sideModalError/sideModalError';
import Button from '@components/button/button';
import useInputs from '@hooks/useInputs/useInputs';
import { Request } from '@libs/api';
import { useQuery } from 'react-query';
import { updateSidebar } from '@store/modules/ui';
import { FEEDBACK_CODES } from '@components/sideModalFeedback/sideModalFeedback';
import { useDispatch } from 'react-redux';
import { getSiteCode } from '@libs/site';
import joi from 'joi';
import { DataWrapper, ProfileNavigatorWrapper } from '@components/pages/profilSzemelyesAdataimPage.styled';
import useProtectedRoute from '@hooks/useProtectedRoute/useProtectedRoute';
import Footer from '@components/footer/footer';
import settingsVars from "@vars/settingsVars";
import urlManager from "@libs/url";

const Content = dynamic(() => import('@components/content/content'));
const PageTitle = dynamic(() => import('@components/pageTitle/pageTitle'));
const SiteColContainer = dynamic(() => import('@components/siteColContainer/siteColContainer'));
const Header = dynamic(() => import('@components/header/header'), { ssr: false });
const ProfileNavigator = dynamic(() => import('@components/profileNavigator/profileNavigator'));
const ProfileDataTitle = dynamic(() => import('@components/profileDataTitle/profileDataTitle'));

const inputsDefaults = {
    password: '',
    passwordConfirmation: '',
};

const errorsDefaults = {
    password: '',
    passwordConfirmation: '',
};

const requestTemplates = {
    headers: {
        Accept: 'application/json; charset=utf-8',
        'Content-type': 'application/json; charset=utf-8',
    },
    requests: {
        newpass: {
            method: 'PUT',
            path: '/update-current-password',
            ref: 'update-current-password',
            request_id: 'newpass',
            body: {
                email: null,
                password: null,
                password_confirmation: null,
            },
        },
    },
};

const request = new Request(requestTemplates);
request.addRequest('newpass');

function handleNewPass(request) {
    let settings = settingsVars.get(urlManager.getHost());

    const url = `${process.env.NEXT_PUBLIC_API_URL}/api/v1/${getSiteCode(settings.key)}/update-current-password`;
    return fetch(url, {
        method: 'PUT',
        headers: request.headers,
        body: JSON.stringify(request.body?.request?.[0]),
    })
        .then((response) => response.json())
        .then((data) => data)
        .catch((error) => error);
}

function getErrorMessage(error) {
    switch (error.type) {
        case 'string.empty':
            return 'Ez a mező nem lehet üres';
        case 'string.email':
            return 'Nem megfelelő e-mail cím';
        case 'string.min':
            return `Minimum ${error.context.limit} karakter lehet`;
        case 'any.only':
            switch (error.context.key) {
                case 'email2':
                    return 'A két e-mail cím nem egyezik';
                case 'passwordConfirmation':
                    return 'A két jelszó nem egyezik';
            }
            break;
        default:
            return 'Hibás mező';
    }
}

export default function PasswordChange() {
    let { user, authChecking } = useProtectedRoute();
    const { inputs, setInput, errors, setErrors } = useInputs(inputsDefaults, errorsDefaults);
    const [responseErrors, setResponseErrors] = useState(null);
    const dispatch = useDispatch();

    function handleSubmit() {
        if (responseErrors) setResponseErrors(null);

        const schema = joi.object({
            password: joi.string().required().min(8),
            passwordConfirmation: joi.ref('password'),
        });

        const validation = schema.validate(inputs, { abortEarly: false });
        const newErrorState = { ...errorsDefaults };

        if (validation.error) {
            validation.error.details.forEach((error) => {
                newErrorState[error.context.key] = getErrorMessage(error);
            });
            setErrors(newErrorState);
            return;
        }

        setErrors(newErrorState);
        request.modifyRequest('newpass', (requestObject) => {
            requestObject.body.email = user.customer.email;
            requestObject.body.password = inputs.password;
            requestObject.body.password_confirmation = inputs.passwordConfirmation;
        });
        setStartFetch(true);
        request.commit();
    }

    const [startFetch, setStartFetch] = useState(false);
    const newpassQuery = useQuery('newpass', () => handleNewPass(request.build()), {
        enabled: startFetch,
        refetchOnWindowFocus: false,
        refetchOnMount: false,
        staleTime: 0,
        onSettled(data) {
            if (data?.errors) {
                setResponseErrors(data?.errors ? Object.values(data?.errors) : null);
                return;
            }
            setStartFetch(false);
            if (responseErrors) setResponseErrors(null);
            dispatch(updateSidebar({ open: true, type: 'feedback', data: `action:feedback|code:${FEEDBACK_CODES.newPassSuccess}` }));
        },
    });

    if (!user || authChecking) return <div>checking</div>;

    return (
        <ProfilEkonyveimPageComponent>
            <PageHead />
            <Header />
            <Content>
                <SiteColContainer>
                    <PageTitle className="d-none d-md-block">Profilom</PageTitle>
                    <PageContent className="row">
                        <ProfileNavigatorWrapper className="col-md-4 col-lg-3 d-none d-md-block">
                            <ProfileNavigator selected={1} />
                        </ProfileNavigatorWrapper>
                        <DataWrapper className="col-md-8 col-lg-7 offset-0 offset-lg-1">
                            <ProfileDataTitle>Jelszó létrehozása</ProfileDataTitle>
                            <Form>
                                <InputPasswordWrapper>
                                    <InputPassword
                                        value={inputs.password}
                                        error={errors.password}
                                        sub="Min. 8 karakter, nagybetű, kisbetű, szám"
                                        label="Jelszó"
                                        onChange={(e) => setInput('password', e.target.value)}
                                    ></InputPassword>
                                </InputPasswordWrapper>
                                <InputPasswordAgainWrapper>
                                    <InputPassword
                                        value={inputs.passwordConfirmation}
                                        error={errors.passwordConfirmation}
                                        label="Jelszó megerősítése"
                                        onChange={(e) => setInput('passwordConfirmation', e.target.value)}
                                    ></InputPassword>
                                </InputPasswordAgainWrapper>
                                {responseErrors && <SideModalError responseErrors={responseErrors} />}
                                <ButtonWrapper>
                                    <Button
                                        buttonWidth="100%"
                                        buttonHeight="50px"
                                        onClick={handleSubmit}
                                        loading={newpassQuery.isFetching}
                                        disabled={newpassQuery.isFetching}
                                    >
                                        Módosítás
                                    </Button>
                                </ButtonWrapper>
                            </Form>
                        </DataWrapper>
                    </PageContent>
                </SiteColContainer>
            </Content>
            <Footer></Footer>
        </ProfilEkonyveimPageComponent>
    );
}
