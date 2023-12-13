export default function IconSearch({ className, iconColor = '#353535' }) {
  return (
    <svg className={className} width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path
        d="M10.083 18.3333C14.6394 18.3333 18.333 14.6396 18.333 10.0833C18.333 5.5269 14.6394 1.83325 10.083 1.83325C5.52666 1.83325 1.83301 5.5269 1.83301 10.0833C1.83301 14.6396 5.52666 18.3333 10.083 18.3333Z"
        stroke={iconColor}
        strokeWidth="2"
        strokeLinecap="round"
        strokeLinejoin="round"
      />
      <path d="M20.1667 20.1667L16.5 16.5" stroke={iconColor} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );
}
