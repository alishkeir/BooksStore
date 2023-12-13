import React from 'react';
import { List, ListItem } from '@components/pages/magazinPage.styled';
import MagazineCard from '@components/magazineCard/magazineCard';

export default function TopPosts(props) {
  return (
    <List className="row">
      {props.posts.map((magazine) => (
        <ListItem key={magazine.id} className="col-md-6 col-lg">
          <MagazineCard magazine={magazine} />
        </ListItem>
      ))}
    </List>
  );
}
