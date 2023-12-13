import { memo, useEffect, useRef, useState } from 'react';
import dynamic from 'next/dynamic';
import SideModalInfo from '@components/sideModalInfo/sideModalInfo';
import Overlay from '@components/overlay/overlay';
import {
  Close,
  CloseIcon,
  Content,
  Header,
  SideModalContentWrapper,
  SideModalInfoWrapper,
  SideModalWrapper,
  Title,
} from '@components/sideModal/sideModal.styled';

let LoginContent = dynamic(() => {
  return import('@components/sideModalLogin/sideModalLogin').then((mod) => mod.default);
});

let RegistrationContent = dynamic(() => {
  return import('@components/sideModalRegistration/sideModalRegistration').then((mod) => mod.default);
});

let ForgottenPassContent = dynamic(() => {
  return import('@components/sideModalForgottenPass/sideModalForgottenPass').then((mod) => mod.default);
});

let NewPassContent = dynamic(() => {
  return import('@components/sideModalNewPass/sideModalNewPass').then((mod) => mod.default);
});

let FeedbackContent = dynamic(() => {
  return import('@components/sideModalFeedback/sideModalFeedback').then((mod) => mod.default);
});

export default memo(function SideModal(props) {
  let { type, data, out, onClose = () => {}, onSetSidebar = () => {} } = props;

  let contentRef = useRef();
  let timeoutRef = useRef();

  let [overlayVisible, setOverlayVisible] = useState(false);
  let [overlayDisplay, setOverlayDisplay] = useState(false);

  useEffect(() => {
    if (out) {
      setOverlayDisplay(true);
      setOverlayVisible(true);
    } else {
      setOverlayVisible(false);

      timeoutRef.current = setTimeout(() => {
        setOverlayDisplay(false);
      }, 300);
    }

    return () => clearTimeout(timeoutRef.current);
  }, [out]);

  let getTitle = (type) => {
    switch (type) {
      case 'login':
        return 'Belépés';
      case 'registration':
        return 'Regisztráció';
      case 'forgottenpass':
        return 'Elfelejtett jelszó';
      default:
        break;
    }
  };

  return (
    <SideModalWrapper>
      <Content out={out} ref={contentRef}>
        {(type === 'login' || type === 'registration') && (
          <SideModalInfoWrapper>
            <SideModalInfo></SideModalInfo>
          </SideModalInfoWrapper>
        )}
        <Header>
          <Close>
            <CloseIcon onClick={onClose}>&times;</CloseIcon>
          </Close>
          {(type === 'login' || type === 'registration') && <Title>{getTitle(type)}</Title>}
        </Header>
        <SideModalContentWrapper>
          {type === 'login' && <LoginContent onSetSidebar={onSetSidebar} onClose={onClose}></LoginContent>}
          {type === 'registration' && <RegistrationContent onSetSidebar={onSetSidebar}></RegistrationContent>}
          {type === 'forgottenpass' && <ForgottenPassContent onSetSidebar={onSetSidebar} onClose={onClose}></ForgottenPassContent>}
          {type === 'newpass' && <NewPassContent onSetSidebar={onSetSidebar} data={data}></NewPassContent>}
          {type === 'feedback' && <FeedbackContent onSetSidebar={onSetSidebar} onClose={onClose} data={data}></FeedbackContent>}
        </SideModalContentWrapper>
      </Content>
      {overlayDisplay && (
        <Overlay
          overlayVisible={overlayVisible}
          onClick={() => {
            if (out) onClose();
          }}
        ></Overlay>
      )}
    </SideModalWrapper>
  );
});
