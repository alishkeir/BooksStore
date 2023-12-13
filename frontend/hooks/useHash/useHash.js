import { useEffect, useState } from 'react';

export default function useHash(hash, parameters = []) {
  let [hashData, setHashData] = useState({});

  useEffect(() => {
    let hashCode = getActionCode(parameters, hash);
    setHashData(hashCode);
  }, [hash]);

  function arrayToObject(array) {
    let object = {};
    array.forEach((item) => (object[item] = ''));
    return object;
  }

  function getActionCode(parameters, hash) {
    if (!hash.replace('#', '')) return arrayToObject(parameters);
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

  return hashData;
}
