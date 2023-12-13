import { useEffect } from 'react';
import { useMutation } from 'react-query';
import { useDispatch } from 'react-redux';
import useRequest from '@hooks/useRequest/useRequest';
import { handleApiRequest, getResponseById } from '@libs/api';
import { updateCategories } from '@store/modules/ui';

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    'auth-init-categories': {
      method: 'GET',
      path: '/helpers',
      ref: 'categories',
      request_id: 'auth-init-categories',
    },
  },
};

export default function AppInit() {
  let dispatch = useDispatch();

  let appInitCategoriesQuery = useMutation('auth-init-categories', (requestUpdateBuild) => handleApiRequest(requestUpdateBuild));

  let appInitCategoriesRequest = useRequest(requestTemplates, appInitCategoriesQuery);

  // Initial auth check
  useEffect(() => {
    appInitCategoriesRequest.addRequest('auth-init-categories');

    appInitCategoriesRequest.commit({
      onSettled: (data) => {
        let appInitCategoriesResponse = getResponseById(data, 'auth-init-categories');

        if (appInitCategoriesResponse?.success) {
          dispatch(updateCategories(appInitCategoriesResponse.body.categories));
        }
      },
    });
  }, []);

  return null;
}
