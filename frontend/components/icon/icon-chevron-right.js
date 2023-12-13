//width="11" height="18"

export default function IconChevronRightSmall({ className, iconColor = '#353535' }) {
  return (
    <div className={className}>
      <svg viewBox="0 0 11 18" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path
          fillRule="evenodd"
          clipRule="evenodd"
          d="M7.5858 9.00001L0.292908 1.70712L1.70712 0.292908L10.4142 9.00001L1.70712 17.7071L0.292908 16.2929L7.5858 9.00001Z"
          fill={iconColor}
        />
      </svg>
    </div>
  );
}
