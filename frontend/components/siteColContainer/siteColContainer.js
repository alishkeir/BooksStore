import { SiteColContainerWrapper, Row, Col } from './siteColContainer.styled';

export default function siteColContainer({ children }) {
  return (
    <SiteColContainerWrapper className="container">
      <Row className="row">
        <Col className="col-12">{children}</Col>
      </Row>
    </SiteColContainerWrapper>
  );
}
