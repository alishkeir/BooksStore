import { useEffect } from 'react';
import { useRouter } from 'next/router';
import { useSelector } from 'react-redux';

export default function useProtectedRoute() {
  let router = useRouter();
  let authChecking = useSelector((store) => store.user.authChecking);
  let user = useSelector((store) => store.user.user);

  useEffect(() => {
    if (!authChecking && !user) {
      router.push('/');
    }
  });

  return {
    router,
    user,
    authChecking,
  };
}
