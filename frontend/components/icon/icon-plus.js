export default function IconPlus({ className, iconColor = '#353535' }) {
  return (
    <svg className={className} width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path fillRule="evenodd" clipRule="evenodd" d="M7 5H12V7H7V12H5V7H0V5H5V0H7V5Z" fill={iconColor} />
    </svg>
  );
}
