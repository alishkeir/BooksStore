import { useEffect } from 'react';
import { useRouter } from 'next/router';
import settingsVars from "@vars/settingsVars";
import url from "@libs/url";

export default function AppRoutes({ children }) {
  let router = useRouter();
  let settings = settingsVars.get(url.getHost());

  useEffect(() => {
    if (settings.pages[router.route]?.accessible === false) {
      router.replace('/');
    }
  }, [router.route]);

  return settings.pages[router.route]?.accessible === false ? null : children;
}
