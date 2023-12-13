export default function IconChevronRightSmall({ className, iconColor = '#3A5897' }) {
  return (
    <div className={className}>
      <svg viewBox="0 0 12 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path
          d="M9.81094 3.98438H12V0.16875C11.625 0.117187 10.3219 0 8.80781 0C5.64844 0 3.4875 1.9875 3.4875 5.63906V9H0V13.2656H3.4875V24H7.7625V13.2656H11.1094L11.6391 9H7.7625V6.06094C7.75781 4.82812 8.09062 3.98438 9.81094 3.98438Z"
          fill={iconColor}
        />
      </svg>
    </div>
  );
}
