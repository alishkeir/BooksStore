import { useState } from 'react';

export default function useInputs(inputsDefaults, errorsDefaults) {
  let [inputs, setInputs] = useState(inputsDefaults);
  let [errors, setErrors] = useState(errorsDefaults);

  function setInput(key, value) {
    setInputs({ ...inputs, [key]: value });
    if (errors[key]) setError(key, '');
  }

  function setError(key, value) {
    setErrors({ ...errors, [key]: value });
  }

  return {
    inputs,
    setInput,
    setInputs,
    errors,
    setError,
    setErrors,
  };
}
