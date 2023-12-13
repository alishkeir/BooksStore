import Icon from '@components/icon/icon';
import { CloseIconWrapper, OverlayCardComponent } from './overlayCard.styled';

export default function OverlayCard(props) {
  let {
    children,
    type = 'box',
    mobile = 'box',
    align = 'center',
    maxHeight,
    exWidth = '38px',
    maxWidth = '650px',
    onClick = () => {},
    onClose = () => {},
  } = props;

  function handleCardClick(e) {
    e.stopPropagation();
    onClick();
  }

  return (
    <OverlayCardComponent maxWidth={maxWidth} maxHeight={maxHeight} type={type} mobile={mobile} align={align} onClick={handleCardClick}>
      <CloseIconWrapper onClick={onClose}>
        <Icon type="ex-thin" iconWidth={exWidth}></Icon>
      </CloseIconWrapper>

      {children}
    </OverlayCardComponent>
  );
}
