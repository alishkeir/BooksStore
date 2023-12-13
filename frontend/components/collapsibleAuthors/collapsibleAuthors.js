import { useState } from 'react';
import {
  CollapsibleAuthorsComponent,
  ListHeaderWrapper,
  Title,
  LinkIconWrapper,
  LinkIcon,
  AuthorNamesWrapper,
  AuthorNameWrapper,
  AuthorName,
} from '@components/collapsibleAuthors/collapsibleAuthors.styled';

export default function CollapsibleAuthors(props) {
  let { border, authors, title } = props;
  let [open, setOpen] = useState(true);

  return (
    <CollapsibleAuthorsComponent>
      <ListHeaderWrapper border={border} open={open}>
        <Title>{title}</Title>
        <LinkIconWrapper onClick={() => setOpen(!open)}>
          <LinkIcon open={open} type="chevron-up-small" iconWidth="18px" iconColor="red"></LinkIcon>
        </LinkIconWrapper>
      </ListHeaderWrapper>
      <AuthorNamesWrapper open={open}>
        {authors.map((author) => (
          <AuthorNameWrapper key={author.id}>
            <AuthorName>{author.name}</AuthorName>
          </AuthorNameWrapper>
        ))}
      </AuthorNamesWrapper>
    </CollapsibleAuthorsComponent>
  );
}
