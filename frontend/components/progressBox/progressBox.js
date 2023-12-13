import Icon from '@components/icon/icon';
import theme from '@vars/theme';
import { Content, IconWrapper, ProgressBoxComponent, Text, Title } from '@components/progressBox/progressBox.styled';

export default function ProgressBox(props) {
  let { finished, description, title } = props;

  return (
    <ProgressBoxComponent finished={finished}>
      {finished && (
        <IconWrapper type="finished">
          <Icon type="check" iconHeight="14px" iconColor="white"></Icon>
        </IconWrapper>
      )}
      {!finished && (
        <IconWrapper type="progress">
          <Icon type="hourglass" iconHeight="25px" iconColor={theme.main.primary}></Icon>
        </IconWrapper>
      )}
      <Content>
        <Title>{title}</Title>
        <Text>{description}</Text>
      </Content>
    </ProgressBoxComponent>
  );
}
