import { HeaderTopMessageComponent } from './headerTopMessage.styled';

export default function headerTopMessage({ children, ...rest }) {
  return <HeaderTopMessageComponent {...rest}>{children}</HeaderTopMessageComponent>;
}
