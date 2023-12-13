import colors from '@vars/colors';
import settingsVars from "@vars/settingsVars";
import url from '@libs/url';

let settings = settingsVars.get(url.getHost());

export default function IconChevronRightSmall({
  className,
  iconColor = settings.key === 'ALOMGYAR'
    ? colors.monza
    : settings.key === 'OLCSOKONYVEK'
    ? colors.amber
    : settings.key === 'NAGYKER'
    ? colors.dodgerBlueLight
    : colors.monza,
}) {
  return (
    <div className={className}>
      <svg viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path
          fillRule="evenodd"
          clipRule="evenodd"
          d="M6.93934 8L1.46967 2.53033L2.53033 1.46967L9.06066 8L2.53033 14.5303L1.46967 13.4697L6.93934 8Z"
          fill={iconColor}
          stroke={iconColor}
          strokeLinejoin="round"
        />
      </svg>
    </div>
  );
}
