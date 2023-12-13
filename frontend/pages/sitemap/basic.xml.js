import { getSiteCode } from '@libs/site';
import settingsVars from '@vars/settingsVars';
import url from '@libs/url';

function generateSiteMap(posts) {
  return `<?xml version="1.0" encoding="UTF-8"?>
   <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
     ${posts
       .map((val) => {
         return `
       <url>
           <loc>${val.loc}</loc>
           ${val?.changefreq ? `<changefreq>${val.changefreq}</changefreq>` : ''}
           ${val?.lastmod ? `<lastmod>${val.lastmod}</lastmod>` : ''}
       </url>
     `;
       })
       .join('')}
   </urlset>
 `;
}

function SiteMap() {
  // getServerSideProps will do the heavy lifting
}

export async function getServerSideProps(context) {
  let settings = settingsVars.get(url.getHost());

  // We make an API call to gather the URLs for our site
  const request = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/v1/${getSiteCode(settings.key)}/sitemap/basic`);
  let posts = [];
  try {
    posts = await request.json();
  } catch (e) {
    return { notFound: true };
  }

  // We generate the XML sitemap with the posts data
  const sitemap = generateSiteMap(posts);

  context.res.setHeader('Content-Type', 'text/xml');
  // we send the XML to the browser
  context.res.write(sitemap);
  context.res.end();

  return {
    props: {},
  };
}

export default SiteMap;
