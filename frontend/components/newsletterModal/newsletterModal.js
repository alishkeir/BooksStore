import React from 'react';
import Overlay from '@components/overlay/overlay';
import OverlayCard from '@components/overlayCard/overlayCard';
import {
  IconWrapper,
  ModalNotification,
  NewsletterIcon,
  NotificationText,
  NotificationTitle,
} from '@components/newsletterModal/newsletterModal.styled';

export default function NewsletterModal(props) {
  let { title, text, setModal = () => {} } = props;

  return (
    <Overlay fixed>
      <OverlayCard
        onClick={() => {
          setModal();
        }}
      >
        <ModalNotification>
          <IconWrapper>
            <NewsletterIcon type="newsletter"></NewsletterIcon>
          </IconWrapper>
          <NotificationTitle>{title}</NotificationTitle>
          <NotificationText>{text}</NotificationText>
        </ModalNotification>
      </OverlayCard>
    </Overlay>
  );
}
