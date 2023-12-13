import Head from 'next/head';
import { useRouter } from 'next/router';
import settingsVars from "@vars/settingsVars";
import url from "@libs/url";

export default function PageHead() {
  let settings = settingsVars.get(url.getHost());
  let router = useRouter();

  return (
    <Head>
      <title>
        {settings.pages[router.route]?.title} | {settings.common.meta.title}
      </title>
    </Head>
  );
}
