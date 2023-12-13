import { withRouter } from 'next/router';
import { useSelector } from 'react-redux';
import AuthLoginPage from '@components/auth/authLoginPage';

function AppLoginWall({ children }) {
  let user = useSelector((store) => store.user.user);
  let authChecking = useSelector((store) => store.user.authChecking);

  if (!authChecking) {
    if (!user) {
      return <AuthLoginPage />;
    } else {
      return children;
    }
  } else {
    return <AuthLoginPage />;
  }
}

export default withRouter(AppLoginWall);
