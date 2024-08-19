import {useAuthContext} from "../contexts/AuthContext";
import {Navigate} from "react-router-dom";
import React from "react";

export const ProtectedRoute = ({children}) => {
    const {isAuthenticated} = useAuthContext();
    if (!isAuthenticated()) {
        return <Navigate to='/login' replace/>;
    }

    return <>{children}</>;
};
