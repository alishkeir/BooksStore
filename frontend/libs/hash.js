import { arrayToObjectKeys } from '@libs/helpers';

export function getHashParamValues(parameters, hash) {
  if (!hash.replace('#', '')) return arrayToObjectKeys(parameters);
  let hashArray = hash.replace('#', '').split('|');

  let parsedParams = {};
  let requestedParams = {};

  hashArray.forEach((hashArrayItem) => {
    if (hashArrayItem) {
      let hashArrayItemParam = hashArrayItem.split(':');

      if (hashArrayItemParam[0]) {
        parsedParams[hashArrayItemParam[0]] = hashArrayItemParam[1];
      }
    }
  });

  parameters.forEach((parameter) => {
    requestedParams[parameter] = parsedParams[parameter] ? parsedParams[parameter] : '';
  });

  return requestedParams;
}
