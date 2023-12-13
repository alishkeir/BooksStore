import Link from 'next/link';
import AddButton from '@components/addButton/addButton';
import {
  Action,
  Author,
  AuthorImage,
  AuthorMeta,
  AuthorName,
  AuthorTitle,
  BookAuthorSectionWrapper,
  Col,
  Container,
  Description,
  Row,
  WishlistButtonWrapper,
} from '@components/bookAuthorSection/bookAuthorSection.styled';
import OptimizedImage from '@components/Images/OptimizedImage';

export default function BookAuthorSection({ author, inCart }) {
  if (!author) return null;

  return (
    <BookAuthorSectionWrapper>
      <Container className="container">
        <Row className="row">
          <Col className="col-xl-6 offset-xl-3">
            <Row className="row">
              <AuthorTitle className="col-sm">A szerzőről</AuthorTitle>
            </Row>
            <Row className="row align-items-center">
              <AuthorMeta className="col-sm order-1">
                <Author>
                  <AuthorImage>
                    {author.cover && (
                      <OptimizedImage src={author.cover} width="50" height="50" layout="intrinsic" alt={author.title} objectFit="cover"></OptimizedImage>
                    )}
                  </AuthorImage>
                  <AuthorName>
                    <Link href={`/szerzo/${author.slug}`} passHref>
                      {author.title}
                    </Link>
                  </AuthorName>
                </Author>
              </AuthorMeta>
              <Action className="col-sm order-4 order-md-2">
                <WishlistButtonWrapper>
                  <AddButton
                    buttonHeight="40px"
                    buttonWidth="100%"
                    fontSize="16px"
                    type="author"
                    text="Feliratkozom a szerzőre"
                    textIcon="plus"
                    afterText="Feliratkozva a szerzőre"
                    inCart={inCart}
                    itemId={author.id}
                  ></AddButton>
                </WishlistButtonWrapper>
              </Action>
              <Description className="col-12 order-3" dangerouslySetInnerHTML={{ __html: author.description }}></Description>
            </Row>
          </Col>
        </Row>
      </Container>
    </BookAuthorSectionWrapper>
  );
}
