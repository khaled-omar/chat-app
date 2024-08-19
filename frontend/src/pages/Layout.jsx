import {Container, CssBaseline, ThemeProvider} from '@mui/material'
import theme from '../theme'
import {Slide, ToastContainer} from 'material-react-toastify'

import {QueryClient, QueryClientProvider} from "@tanstack/react-query";


const queryClient = new QueryClient()

function Layout({ children }) {
    return (
        <ThemeProvider theme={theme}>
            <QueryClientProvider client={queryClient}>
                    <Container component="main">
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
                    </Container>

            </QueryClientProvider>
        </ThemeProvider>
    )
}

export default Layout
