export function getSiteCode(siteId) {
  switch (siteId) {
    case 'ALOMGYAR':
      return 0;
    case 'OLCSOKONYVEK':
      return 1;
    case 'NAGYKER':
      return 2;

    default:
      return 0;
  }
}
