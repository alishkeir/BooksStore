export function arrayToObjectKeys(array) {
  let object = {};
  array.forEach((item) => (object[item] = ''));
  return object;
}

export function nullInObjectToEmpty(object) {
  let newObject = {};

  for (let key in object) {
    newObject[key] = object[key] ? object[key] : '';
  }

  return newObject;
}
