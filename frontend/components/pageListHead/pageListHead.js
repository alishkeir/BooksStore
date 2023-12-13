import Head from 'next/head';
import DynamicHead from '@components/heads/DynamicHead';
import settingsVars from "@vars/settingsVars";
import url from "@libs/url";

export default function PageListHead({response, metadata}) {
  let settings = settingsVars.get(url.getHost());

  function getSelectedCategory(filters) {
    if (!filters) return '';

    for (let filter of filters) {
      if (filter.id === 'category') {
        for (let filterDataItem of filter?.data) {
          if (filterDataItem.selected) return filterDataItem;
        }
      }
    }

    return '';
  }

  function getSelectedSubCategory(filters) {
    if (!filters || typeof filters === "undefined") return '';
    for (let filter of filters) {
      if (filter.id === "subcategory") {
        if (!filter?.data || typeof filter?.data === "undefined") continue;

        for (let filterDataItem of filter?.data) {
          if (filterDataItem.selected) return filterDataItem;
        }
      }
    }

    return '';
  }

  let category = getSelectedCategory(response?.body?.filters);
  let subCategory = null;
  if(category) subCategory = getSelectedSubCategory(response?.body?.filters);

  const title = `${category.title ? category.title + ' - ' : ''} ${response.body.page_title}`;
  return (
    <>
      <Head>
        <title>
          {title} | {settings.common.meta.title}
        </title>
        <meta name="yuspCategoryId" content={`${category.id}`}/>
        <meta name="yuspCategoryPath" content={(category && subCategory) ? `${category.slug}/${subCategory.slug}` : `${category && category.slug}`}/>
      </Head>
      <DynamicHead title={title} metadata={metadata ? metadata : null} noTitle={true}/>
    </>
  );
}
