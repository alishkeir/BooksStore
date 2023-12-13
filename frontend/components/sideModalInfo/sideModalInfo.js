import { SideModalInfoWrapper } from '@components/sideModalInfo/sideModalInfo.styled';

export default function SideModalInfo() {
  return (
    <SideModalInfoWrapper>
      Kérjük, hogy ha korábban {' '}
      <a href="https://google.com" target="_blank" rel="noreferrer noopener">
        Google
      </a>{' '}
      segítségével léptél be, akkor a jövőben is minden alkalommal azt használd a belépéshez.
    </SideModalInfoWrapper>
  );
}
