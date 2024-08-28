import {Container, CssBaseline, ThemeProvider} from '@mui/material'
import theme from '../theme'
import {Slide, ToastContainer} from 'material-react-toastify'

import {QueryClient, QueryClientProvider} from "@tanstack/react-query";
import {CookiesProvider} from "react-cookie";


const queryClient = new QueryClient()

function Layout({children}) {
    return (
        <ThemeProvider theme={theme}>
            <CookiesProvider>
                <QueryClientProvider client={queryClient}>
                    {/*<Container component="main" maxWidth='xl'>*/}
                        <CssBaseline/>
                        {children}
                        <ToastContainer
                            position="top-right"
                            autoClose={5000}
                            hideProgressBar={false}
                            newestOnTop={false}
                            closeOnClick
                            rtl={false}
                            pauseOnFocusLoss
                            draggable
                            pauseOnHover
                            theme="colored"
                            transition={Slide}
                        />
                    {/*</Container>*/}
                </QueryClientProvider>
            </CookiesProvider>
        </ThemeProvider>
    )
}

export default Layout
