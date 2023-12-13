import Link from 'next/link';
import { LinkIcon, LinkIconWrapper, LinkWrapper, ListHeaderWrapper, Title } from './listHeader.styled';

export default function ListHeader(props) {
  let { link, border, title = '' } = props;

  return (
    <ListHeaderWrapper border={border}>
      <Title>{title}</Title>
      {link && (
        <LinkWrapper>
          <Link href={link} passHref>Teljes lista</Link>
          <LinkIconWrapper>
            <LinkIcon type="chevron-right-small" iconWidth="8px" iconHeight="13px"></LinkIcon>
          </LinkIconWrapper>
        </LinkWrapper>
      )}
    </ListHeaderWrapper>
  );
}
