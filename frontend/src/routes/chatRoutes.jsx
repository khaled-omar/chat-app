import Profile from "../pages/Profile";
import {ProtectedRoute} from "./ProtectedRoute";

export const chatRoutes = [
    {
        path: "/",
        element: <ProtectedRoute><Profile/></ProtectedRoute>,
    },
]