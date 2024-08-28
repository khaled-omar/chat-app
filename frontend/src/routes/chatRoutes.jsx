import ChatPage from "../pages/ChatPage.jsx";
import {ProtectedRoute} from "./ProtectedRoute";

export const chatRoutes = [
    {
        path: "/",
        element: <ProtectedRoute><ChatPage/></ProtectedRoute>,
    },
]