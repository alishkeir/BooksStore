export default function IconSliders({ className, iconColor = '#353535' }) {
  return (
    <div className={className}>
      <svg width="14" height="12" viewBox="0 0 14 12" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path
          d="M13.0044 1.33852H5.69333C5.58444 1.33852 5.49111 1.29183 5.42889 1.19844L4.82222 0.404669C4.63556 0.155642 4.34 0 4.02889 0H0.995556C0.451111 0 0 0.451362 0 0.996109V11.0039C0 11.5642 0.451111 12 0.995556 12H12.9889C13.5489 12 13.9844 11.5486 13.9844 11.0039V2.33463C14 1.78988 13.5489 1.33852 13.0044 1.33852ZM0.995556 0.669261H3.90444C4.10667 0.669261 4.30889 0.762646 4.43333 0.933852L5.04 1.72763C5.16444 1.89883 5.36667 1.99222 5.56889 1.99222H12.9889C13.1756 1.99222 13.3156 2.14786 13.3156 2.31907V3.31518H0.668889V0.980545C0.668889 0.809339 0.808889 0.669261 0.995556 0.669261ZM13.0044 11.3463H0.995556C0.808889 11.3463 0.668889 11.1907 0.668889 11.0195V4.01556H13.3311V11.0195C13.3311 11.1907 13.1911 11.3463 13.0044 11.3463Z"
          fill={iconColor}
        />
      </svg>
    </div>
  );
}