import {createBrowserRouter} from "react-router-dom";
import {authRoutes} from "./authRoutes";
import {chatRoutes} from "./chatRoutes";

const router = createBrowserRouter([
    ...authRoutes,
    ...chatRoutes
]);

export default router