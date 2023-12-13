export default function IconTime({ className, iconColor = '#E30613' }) {
  return (
    <div className={className}>
      <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path
          fillRule="evenodd"
          clipRule="evenodd"
          d="M9.9987 1.58337C5.3503 1.58337 1.58203 5.35164 1.58203 10C1.58203 14.6484 5.3503 18.4167 9.9987 18.4167C14.6471 18.4167 18.4154 14.6484 18.4154 10C18.4154 5.35164 14.6471 1.58337 9.9987 1.58337ZM0.0820312 10C0.0820312 4.52322 4.52187 0.083374 9.9987 0.083374C15.4755 0.083374 19.9154 4.52322 19.9154 10C19.9154 15.4769 15.4755 19.9167 9.9987 19.9167C4.52187 19.9167 0.0820312 15.4769 0.0820312 10Z"
          fill={iconColor}
        />
        <path
          fillRule="evenodd"
          clipRule="evenodd"
          d="M10 5.08331C10.4142 5.08331 10.75 5.4191 10.75 5.83331V10.5227L12.6137 12.3863C12.9066 12.6792 12.9066 13.1541 12.6137 13.447C12.3208 13.7399 11.8459 13.7399 11.553 13.447L9.46967 11.3636C9.32902 11.223 9.25 11.0322 9.25 10.8333V5.83331C9.25 5.4191 9.58579 5.08331 10 5.08331Z"
          fill={iconColor}
        />
      </svg>
    </div>
  );
}
