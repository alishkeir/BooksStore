import { useSelector, useDispatch } from 'react-redux';
import get from 'lodash/get';

export default function useStoreInputs(
  inputsPath,
  errorsPath,
  inputActionCreator,
  inputsActionCreator,
  inputsResetActionCreator,
  errorActionCreator,
  errorsActionCreator,
  errorsResetActionCreator,
) {
  let dispatch = useDispatch();
  let inputs = useSelector((store) => get(store, inputsPath));
  let errors = useSelector((store) => get(store, errorsPath));

  function setInput(key, value) {
    dispatch(inputActionCreator({ key, value }));

    if (errors[key]) {
      dispatch(errorActionCreator({ key, value: '' }));
    }
  }

  function setInputs(newInputs) {
    dispatch(inputsActionCreator(newInputs));
  }

  function resetInputs() {
    dispatch(inputsResetActionCreator());
  }

  function setError(key, value) {
    dispatch(errorActionCreator({ key, value }));
  }

  function setErrors(newErrors) {
    dispatch(errorsActionCreator(newErrors));
  }

  function resetErrors() {
    dispatch(errorsResetActionCreator());
  }

  return {
    inputs,
    setInput,
    setInputs,
    resetInputs,
    errors,
    setError,
    setErrors,
    resetErrors,
  };
}
