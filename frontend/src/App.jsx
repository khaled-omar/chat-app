import './App.css'
import '@fontsource/roboto/300.css'
import '@fontsource/roboto/400.css'
import '@fontsource/roboto/500.css'
import '@fontsource/roboto/700.css'
import 'material-react-toastify/dist/ReactToastify.css'

import Layout from './pages/Layout'
import router from "./routes/router";
import {RouterProvider} from "react-router-dom";
import {AuthProvider} from "./contexts/AuthContext";

function App() {
    return (
        <Layout>
            <AuthProvider>
                <RouterProvider router={router}/>
            </AuthProvider>
        </Layout>
    )
}

export default App
