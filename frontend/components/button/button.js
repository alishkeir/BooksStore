import dynamic from 'next/dynamic';
import theme from '@vars/theme';
const Icon = dynamic(() => import('@components/icon/icon'), { ssr: false });
import { ButtonWrapper, IconWrapper, LoaderWrapper } from './button.styled';

export default function Button(props) {
  let { onClick, type = 'primary', loading, buttonHeight, buttonWidth, icon, iconWidth, iconHeight, disabled, children } = props;

  let config = {};
  // Common
  config.buttonHeight = buttonHeight ? buttonHeight : '40px';
  config.buttonWidth = buttonWidth ? buttonWidth : null;
  config.iconMargin = children?.length > 0 ? '10px' : '0';
  config.iconWidth = iconWidth ? iconWidth : null;
  config.iconHeight = iconHeight ? iconHeight : null;

  // Theme
  // Type
  if (type === 'primary') {
    config.borderColor = theme.button.tertiary;
    config.backgroundColor = theme.button.tertiary;
    config.backgroundColorHover = theme.button.tertiaryHover;
    config.buttonColor = 'white';
    config.iconColor = 'white';
    config.loaderColor = 'white';

    // Disabled
    if (disabled) {
      config.buttonDisabled = true;
      config.borderColor = theme.button.inactive;
      config.backgroundColor = theme.button.inactive;
      config.backgroundColorHover = theme.button.inactive;
      config.buttonColor = 'white';
      config.iconColor = theme.button.inactive;
    }
  } else if (type === 'secondary') {
    config.borderColor = theme.button.tertiary;
    config.backgroundColor = 'white';
    config.backgroundColorHover = theme.button.secondaryHover;
    config.buttonColor = theme.button.tertiary;
    config.iconColor = theme.button.tertiary;
    config.loaderColor = theme.button.tertiary;

    // Disabled
    if (disabled) {
      config.buttonDisabled = true;
      config.borderColor = theme.button.inactive;
      config.backgroundColor = 'white';
      config.backgroundColorHover = 'white';
      config.buttonColor = theme.button.inactive;
      config.iconColor = theme.button.inactive;
      config.loaderColor = 'white';
    }
  }

  return (
    <ButtonWrapper config={config} disabled={disabled} onClick={onClick}>
      {icon && (
        <IconWrapper config={config}>
          <Icon type={icon} iconColor={config.iconColor} iconWidth={iconWidth} iconHeight={iconHeight}></Icon>
        </IconWrapper>
      )}

      {loading &&
        (() => {
          let Loader = dynamic(() => import('react-spinners/PulseLoader'));
          return (
            <LoaderWrapper>
              <Loader color={config.loaderColor} size="8px" />
            </LoaderWrapper>
          );
        })()}

      {!loading && children}
    </ButtonWrapper>
  );
}
