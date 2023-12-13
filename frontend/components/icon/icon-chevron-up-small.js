//width="11" height="18"

export default function IconChevronUpSmall({ className, iconColor = '#353535' }) {
  return (
    <div className={className}>
      <svg height="10" viewBox="0 0 18 10" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path
          fillRule="evenodd"
          clipRule="evenodd"
          d="M1.70703 10.7072L0.292818 9.29294L8.99992 0.585833L17.707 9.29294L16.2928 10.7072L8.99992 3.41426L1.70703 10.7072Z"
          fill={iconColor}
          strokeLinejoin="round"
        />
      </svg>
    </div>
  );
}
