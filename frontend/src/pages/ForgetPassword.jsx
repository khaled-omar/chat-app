import {
    Avatar,
    Box,
    Button,
    Container, Grid, Link,
    TextField,
    Typography
} from "@mui/material";
import LockOutlinedIcon from "@mui/icons-material/LockOutlined";
import {Link as RouterLink} from "react-router-dom";
import useForgetPasswordHooks from "./ForgetPassword.hooks.js";


function ForgetPassword() {
    const {register, errors, isSubmitting, handleSubmit, onSubmit} = useForgetPasswordHooks()

    return (<Container maxWidth="sm">
        <Box
            sx={{
                mt: 8, display: 'flex', flexDirection: 'column', alignItems: 'center',
            }}>
            <Avatar sx={{m: 1, backgroundColor: 'primary.main'}}>
                <LockOutlinedIcon/>
            </Avatar>
            <Typography component="h1" variant="h5">
                Forget your password!
            </Typography>
            <Box component="form" onSubmit={handleSubmit(onSubmit)} noValidate sx={{mt: 1, width: '100%'}}>
                <TextField
                    {...register('email')}
                    margin="normal"
                    fullWidth
                    label="Email Address"
                    name="email"
                    autoComplete="email"
                    autoFocus
                />
                <Typography variant="overline" sx={{color: 'error.main'}}>{errors.email?.message}</Typography>
                <Button
                    disabled={isSubmitting}
                    type="submit"
                    fullWidth
                    variant="contained"
                    sx={{mt: 1, width: '100%'}}
                >
                    {isSubmitting ? 'Submitting ...' : 'Sign In'}
                </Button>
            </Box>
            <Grid container sx={{mt: 2}}>
                <Grid item xs>
                    <Link component={RouterLink} to={"/login"} variant="body2">
                        {'Already have an account? Sign In'}
                    </Link>
                </Grid>
                <Grid item>
                    <Link component={RouterLink} to={"/register"} variant="body2">
                        {'Don\'t have an account? Sign Up'}
                    </Link>
                </Grid>
            </Grid>
        </Box>
    </Container>)
}

export default ForgetPassword
