import Link from 'next/link';
import Icon from '@components/icon/icon';
import colors from '@vars/colors';

import {
  Actionscol,
  IconWrapper,
  ImageCol,
  ImageWrapper,
  ProfileAuthorLineComponent,
  TextCol,
} from '@components/profileAuthorLine/profileAuthorLine.styled';
import OptimizedImage from '@components/Images/OptimizedImage';

export default function ProfileAuthorLine(props) {
  let { onDelete = () => {} } = props;

  return (
    <ProfileAuthorLineComponent>
      <ImageCol>
        <ImageWrapper>
          <Link href={`/szerzo/${props.slug}`} passHref>
            <OptimizedImage src="https://source.unsplash.com/PvQPK0KQqvY/50x50" width="50" height="50" layout="intrinsic" alt={props.title}></OptimizedImage>
          </Link>
        </ImageWrapper>
      </ImageCol>
      <TextCol>
        <Link href={`/szerzo/${props.slug}`} passHref>
          {props.title}
        </Link>
      </TextCol>
      <Actionscol>
        <IconWrapper onClick={() => onDelete(props.id)}>
          <Icon type="delete" iconWidth="17px" iconHeight="18px" iconColor={colors.monza}></Icon>
        </IconWrapper>
      </Actionscol>
    </ProfileAuthorLineComponent>
  );
}
