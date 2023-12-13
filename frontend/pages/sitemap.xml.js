import {getSiteCode} from "@libs/site";
import settingsVars from "@vars/settingsVars";
import url from "@libs/url";

function generateSiteMap(posts) {
  return `<?xml version="1.0" encoding="UTF-8"?>
   <sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
     ${posts
      .map((val) => {
        return `
       <sitemap>
           <loc>${val}</loc>
       </sitemap>
     `;
      })
      .join('')}
   </sitemapindex>
 `;
}

function SiteMap() {
  // getServerSideProps will do the heavy lifting
}

export async function getServerSideProps({ res }) {
    let settings = settingsVars.get(url.getHost());

    // We make an API call to gather the URLs for our site
  const request = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/v1/${getSiteCode(settings.key)}/sitemap`);
    const posts = await request.json();

  // We generate the XML sitemap with the posts data
  const sitemap = generateSiteMap(posts);

  res.setHeader('Content-Type', 'text/xml');
  // we send the XML to the browser
  res.write(sitemap);
  res.end();

  return {
    props: {},
  };
}

export default SiteMap;
