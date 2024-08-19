import RegisterForm from "../pages/RegisterForm";
import LoginForm from "../pages/LoginForm";
import ForgetPassword from "../pages/ForgetPassword";
import React from "react";
import ResetPassword from "../pages/ResetPassword";
import {AuthRoute} from "./AuthRoute";


export const authRoutes = [
    {
        path: "/register",
        element: <AuthRoute><RegisterForm/></AuthRoute>,
    },
    {
        path: "/login",
        element: <AuthRoute><LoginForm/></AuthRoute>,
    },
    {
        path: "/forgot-password",
        element: <AuthRoute><ForgetPassword/></AuthRoute>,
    },
    {
        path: "/reset-password",
        element: <AuthRoute><ResetPassword/></AuthRoute>,
    }
]