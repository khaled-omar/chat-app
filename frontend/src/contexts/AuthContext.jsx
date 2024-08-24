import {createContext, useState, useEffect, useContext} from 'react';
import {useCookies} from "react-cookie";
import UserService from "../services/UserService.js";
import {getDate} from "../utils/Helpers.js";

export const AuthContext = createContext();

// eslint-disable-next-line react/prop-types
export const AuthProvider = ({children}) => {
    const [cookies, setCookie] = useCookies(['user', 'access_token', 'refresh_token'])


    const login = async (loginData) => {
        const body = await UserService.login(loginData)
        setCookie('access_token', body.data.access_token, {expires: getDate(7)})
        setCookie('refresh_token', body.data.refresh_token, {expires: getDate(30)})
        const user = await UserService.me()
        setCookie('user', JSON.stringify(user), {expires: getDate(7)})
    };

    const registerNewUser = async (data) => {
        const body = await UserService.register(data)
        setCookie('access_token', body.data.access_token, {expires: getDate(7)})
        setCookie('refresh_token', body.data.refresh_token, {expires: getDate(30)})
        const user = await UserService.me()
        setCookie('user', JSON.stringify(user), {expires: getDate(7)})
    };

    const isAuthenticated = () => {
        return !!cookies.access_token && !!cookies.refresh_token
    };

    const logout = () => {
        localStorage.removeItem('current_user');
        localStorage.removeItem('access_token');
        localStorage.removeItem('refresh_token');
    };

    return (
        <AuthContext.Provider
            value={{
                isAuthenticated,
                login,
                registerNewUser,
                logout,
            }}
        >
            {children}
        </AuthContext.Provider>
    );
};

export function useAuthContext() {
    return useContext(AuthContext);
}
