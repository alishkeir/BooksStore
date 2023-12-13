import { useState } from 'react';
import Icon from '@components/icon/icon';
import colors from '@vars/colors';
import { Body, Header, HeaderIcon, IconWrapper, SummaryDrawerComponent, Title, Value } from '@components/summaryDrawer/summaryDrawer.styled';

export default function SummaryDrawer({ children, title, value }) {
  let [open, setOpen] = useState(false);

  return (
    <SummaryDrawerComponent>
      <Header onClick={() => setOpen(!open)}>
        <Title>{title}</Title>
        <Value>{value}</Value>
        <HeaderIcon>
          <IconWrapper open={open}>
            <Icon type="chevron-right" iconWidth="10px" iconColor={colors.monza}></Icon>
          </IconWrapper>
        </HeaderIcon>
      </Header>
      {open && <Body>{children}</Body>}
    </SummaryDrawerComponent>
  );
}
