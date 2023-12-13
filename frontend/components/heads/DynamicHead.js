import Head from 'next/head';
import { useRouter } from 'next/router';
import settingsVars from "@vars/settingsVars";
import url from "@libs/url";

function DynamicHead({ title, description, image, children, noTitle = false, metadata })
{
  let settings = settingsVars.get(url.getHost());
  const router = useRouter();

  const getTitle = (title) => `${!title ? settings.pages[router.route]?.title : title} | ${settings.common.meta.title}`

  const getDescription = (description) =>
  {
    if (!description)
    {
      return settings.common.meta.description;
    } else
    {
      const stripTags = description.replace(/(<([^>]+)>)/ig, '');
      let limitString = stripTags.slice(0, 200);
      if (stripTags.length > 197)
      {
        limitString = limitString + '...';
      }
      return limitString;
    }
  }

  title = (metadata && metadata.title) ? getTitle(metadata.title) : getTitle(title);
  description = (metadata && metadata.description) ? getDescription(metadata.description) : getDescription(description);

  return (
    <Head>
      {!noTitle && (
        <title>
          {title}
        </title>
      )}

      <meta name="title" content={title} />
      <meta name="description" content={description} />

      <meta property="og:image" content={image ? image : settings.common.meta.image.src} />
      <meta property="og:title" content={title} />
      <meta property="og:description" content={description} />

      <meta property="twitter:title" content={title} />
      <meta property="twitter:description" content={description} />
      <meta property="twitter:image" content={image ? image : settings.common.meta.image.src} />
      {children}
    </Head>
  )
}

export default DynamicHead;
