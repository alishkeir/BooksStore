import styled from '@emotion/styled';
import Icon from '@components/icon/icon';
import breakpoints from '@vars/breakpoints';

export let ModalNotification = styled.div`
  padding: 70px 60px 60px;
`;

export let NotificationTitle = styled.div`
  font-size: 24px;
  font-weight: 600;
  text-align: center;
  margin-bottom: 20px;

  @media (max-width: ${breakpoints.max.md}) {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 24px;
  }
`;

export let NotificationText = styled.div`
  font-size: 16px;
  font-weight: 300;
  text-align: center;

  @media (max-width: ${breakpoints.max.md}) {
    font-size: 14px;
    font-weight: 300;
  }
`;

export let NewsletterIcon = styled(Icon)`
  height: 58px;
  width: 57px;
  margin: auto 0;
`;

export let IconWrapper = styled.div`
  display: flex;
  justify-content: center;
`;
