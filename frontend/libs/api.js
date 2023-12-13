import mitt from 'mitt';
import _cloneDeep from 'lodash/cloneDeep';
import { getSiteCode } from '@libs/site';
import settingsVars from "@vars/settingsVars";
import urlManager from "@libs/url";
import url from "@libs/url";

export function getRequestById(query, id)
{
  return query.request.find((item) => item.request_id === id);
}

export function getResponseById(response, id)
{
  return response?.response.find((item) => item.request_id === id);
}

export function handleApiRequest(requestBuild)
{
  // Create a new AbortController
  const controller = new AbortController();
  const signal = controller.signal;

  requestBuild.headers = requestBuild.headers
    ? requestBuild.headers
    : {
      Accept: 'application/json; charset=utf-8',
      'Content-type': 'application/json; charset=utf-8',
    };

  let settings = settingsVars.get(url.getHost());

  let promise = fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/v1/${getSiteCode(settings.key)}/composite`, {
    method: 'POST',
    headers: requestBuild.headers,
    body: JSON.stringify(requestBuild.body),
    signal,
  })
    .then((response) =>
    {
      if (!response.ok) throw new Error(`API response: ${response.status}`);
      return response.json();
    })
    .then((data) => data)
    .catch((error) => console.log(error));

  // Cancel
  promise.cancel = () => controller.abort();

  return promise;
}

export class Request
{
  constructor(template)
  {
    this.template = {
      headers: template.headers,
      requests: template.requests,
    };
    this.draft = {
      headers: _cloneDeep(template.headers),
      requests: {},
    };

    this.events = mitt();
  }

  resetRequest()
  {
    this.draft.requests = {};
  }

  addRequest()
  {
    let args = Array.from(arguments);

    args.forEach((arg) =>
    {
      if (this.template.requests[arg])
      {
        this.draft.requests[arg] = _cloneDeep(this.template.requests[arg]);
      }
    });
  }

  removeRequest()
  {
    let args = Array.from(arguments);

    args.forEach((arg) =>
    {
      if (this.draft.requests[arg])
      {
        delete this.draft.requests[arg];
      }
    });
  }

  modifyRequest(key, callback)
  {
    if (this.draft.requests[key])
    {
      callback(this.draft.requests[key]);
    }
  }

  modifyHeaders(callback)
  {
    callback(this.draft.headers);
  }

  commit(events = {})
  {
    this.events.emit('commit', events);
  }

  build()
  {
    return {
      headers: this.draft.headers,
      body: { request: Object.values(this.draft.requests) },
    };
  }
}

export async function getMetadata(url) {
  let settings = settingsVars.get(urlManager.getHost());

  const metadataRes = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/v1/${getSiteCode(settings.key)}/metadata`, {
    method: 'POST',
    body: JSON.stringify({
      pageUrl: url
    }),
    headers: {
      'Content-Type': 'application/json'
    }
  });

  const metadata = await metadataRes.json();
  return metadata;
}
