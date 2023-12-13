import { memo, useRef } from 'react';
import Button from '@components/button/button';
import Icon from '@components/icon/icon';
import useHash from '@hooks/useHash/useHash';
import {
  ButtonWrapper,
  CheckIcon,
  ExIcon,
  Form,
  IconWrapper,
  SideModalFeedbackWrapper,
  Text,
  Title,
} from '@components/sideModalFeedback/sideModalFeedback.styled';

export let FEEDBACK_CODES = {
  generalSuccess: 100,
  generalError: 101,
  registrationSuccess: 201,
  verificationSuccess: 301,
  verificationAlreadyDone: 302,
  forgottenPassSendSuccess: 401,
  newPassSuccess: 501,
};

export default memo(function SideModalFeedback({ data, onClose, onSetSidebar }) {
  let hashData = useHash(data, ['code']);

  let configRef = useRef({
    [FEEDBACK_CODES.generalSuccess]: {
      icon: 'check',
      title: 'Siker!',
      text: '',
      button: {
        text: 'Bezárom',
        action: () => {
          window.location.hash = '';
          onClose();
        },
      },
    },
    [FEEDBACK_CODES.generalError]: {
      icon: 'ex',
      title: 'Valami nem sikerült :(',
      text: '',
      button: {
        text: 'Bezárom',
        action: () => {
          window.location.hash = '';
          onClose();
        },
      },
    },
    [FEEDBACK_CODES.registrationSuccess]: {
      icon: 'check',
      title: 'Köszönjük!',
      text: (
        <>
          <p>Sikeresen regisztráltál és be is léptél az oldalra </p> <p> Kellemes vásárlást kívánunk!</p>
        </>
      ),
      button: {
        text: 'Bezárom',
        action: () => {
          window.location.hash = '';
          onClose();
        },
      },
    },
    [FEEDBACK_CODES.verificationSuccess]: {
      icon: 'check',
      title: 'Köszönjük!',
      text: <p>Sikeresen hitelesítetted a regisztrációd. Most már beléphetsz e-mail címed és jelszavad segítségével.</p>,
      button: {
        text: 'Bejelentkezés',
        action: () => {
          window.location.hash = '';
          onSetSidebar('login');
        },
      },
    },
    [FEEDBACK_CODES.verificationAlreadyDone]: {
      icon: 'ex',
      title: 'Már hitelesítetted regisztrációd!',
      text: '',
      button: {
        text: 'Bezárom',
        action: () => {
          window.location.hash = '';
          onClose();
        },
      },
    },
    [FEEDBACK_CODES.forgottenPassSendSuccess]: {
      icon: 'check',
      title: 'Jelszó változtatás!',
      text: <p>A megadott e-mail címre küldött linkkel tudod megváltoztani jelszavad regisztrációját. </p>,
      button: {
        text: 'Bezárom',
        action: () => {
          window.location.hash = '';
          onClose();
        },
      },
    },
    [FEEDBACK_CODES.newPassSuccess]: {
      icon: 'check',
      title: 'Köszönjük!',
      text: <p>Jelszó módosítás sikeres. Kérlek jelentkezz be új jelszavaddal</p>,
      button: {
        text: 'Bejelentkezés',
        action: () => {
          window.location.hash = '';
          onSetSidebar('login');
        },
      },
    },
  });

  let content = configRef.current[hashData.code];

  if (!content) {
    return null;
  }

  return (
    <SideModalFeedbackWrapper>
      <Form>
        {content.icon && (
          <IconWrapper>
            {content.icon === 'check' && (
              <CheckIcon>
                <Icon type="check" iconWidth="25px"></Icon>
              </CheckIcon>
            )}
            {content.icon === 'ex' && (
              <ExIcon>
                <Icon type="ex" iconWidth="30px"></Icon>
              </ExIcon>
            )}
          </IconWrapper>
        )}
        {content.title && <Title>{content.title}</Title>}
        {content.text && <Text>{content.text}</Text>}
        {content.button && (
          <ButtonWrapper>
            <Button buttonWidth="100%" buttonHeight="50px" onClick={content.button.action}>
              {content.button.text}
            </Button>
          </ButtonWrapper>
        )}
      </Form>
    </SideModalFeedbackWrapper>
  );
});
