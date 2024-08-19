import {createContext, useState, useEffect, useContext} from 'react';

export const AuthContext = createContext();

// eslint-disable-next-line react/prop-types
export const AuthProvider = ({children}) => {
    const [currentUser, setCurrentUser] = useState(null);
    const [accessToken, setAccessToken] = useState(null);
    const [refreshToken, setRefreshToken] = useState(null);

    useEffect(() => {
        //const storedUser = JSON.parse(localStorage.getItem(current_user));
        const storedAccessToken = localStorage.getItem('access_token');
        const storedRefreshToken = localStorage.getItem('refresh_token');

        if (!!storedAccessToken && !!storedRefreshToken) {
            // setCurrentUser(storedUser);
            // setIsAuthenticated(true)
            setAccessToken(storedAccessToken);
            setRefreshToken(storedRefreshToken);
        }
    }, []);

    const login = (user, accessToken, refreshToken) => {
        setCurrentUser(user);
        setAccessToken(accessToken);
        setRefreshToken(refreshToken);

        localStorage.setItem('current_user', JSON.stringify(user));
        localStorage.setItem('access_token', accessToken);
        localStorage.setItem('refresh_token', refreshToken);
    };

    const isAuthenticated = () => {
        return (!!localStorage.getItem('access_token') && !!localStorage.getItem('refresh_token'))
    };

    const logout = () => {
        setCurrentUser(null);
        setAccessToken(null);
        setRefreshToken(null);

        localStorage.removeItem('current_user');
        localStorage.removeItem('access_token');
        localStorage.removeItem('refresh_token');
    };

    return (
        <AuthContext.Provider
            value={{
                isAuthenticated,
                currentUser,
                accessToken,
                refreshToken,
                login,
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
