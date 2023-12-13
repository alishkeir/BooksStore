export default function IconPlus({ className, iconColor = '#353535' }) {
  return (
    <div className={className}>
      <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M6 6.00003L18.7742 18.7742" vectorEffect="non-scaling-stroke" stroke={iconColor} strokeWidth="2" strokeLinejoin="round" />
        <path d="M6 18.7742L18.7742 6.00001" vectorEffect="non-scaling-stroke" stroke={iconColor} strokeWidth="2" strokeLinejoin="round" />
      </svg>
    </div>
  );
}
