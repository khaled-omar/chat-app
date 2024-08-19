import {useAuthContext} from "../contexts/AuthContext";
import {Navigate} from "react-router-dom";

// eslint-disable-next-line react/prop-types
export const AuthRoute = ({ children }) => {
    const {isAuthenticated} = useAuthContext();
    if (isAuthenticated()) {
        return <Navigate to='/' replace />;
    }

    return <>{children}</>;
};