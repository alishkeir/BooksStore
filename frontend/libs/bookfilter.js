import lilUri from 'lil-uri';

export function buildLink(id, value, baseURL, pageUrl, config) {
  let currentUri = lilUri(process.browser ? window.location.href : pageUrl);
  let newUri = lilUri();
  let uriPath = currentUri.path();
  let uriQuery = currentUri.query();
  let filterConfig = config.controls.parameters.find((item) => item.id === id);

  if (filterConfig.type === 'path') {
    let uriPathArray = getPathArray(baseURL, uriPath);
    uriPathArray[filterConfig.pathIndex] = value;

    newUri.query({ ...uriQuery });
    newUri.path(`/${uriPathArray.join('/')}`);
  } else if (filterConfig.type === 'param') {
    newUri.path(uriPath);

    if (uriQuery) {
      newUri.query({ ...uriQuery, [filterConfig.name]: value });
    } else {
      newUri.query({ [filterConfig.name]: value });
    }
  }

  return newUri.build();
}

function getPathArray(baseURL, path) {
  return path.split('/').filter((item) => (item ? true : false));
}
