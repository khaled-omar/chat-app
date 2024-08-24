import LockOutlinedIcon from '@mui/icons-material/LockOutlined'
import {Link as RouterLink} from 'react-router-dom'
import useLoginFormHooks from "./LoginForm.hooks.js";
import {
    Avatar,
    Box,
    Button,
    Checkbox, Container,
    FormControlLabel,
    Grid,
    Link,
    TextField,
    Typography,
} from '@mui/material'


function LoginForm() {

    const {register, errors, isSubmitting, handleSubmit, onSubmit} = useLoginFormHooks()

    return (<Container maxWidth="sm">
        <Box
            sx={{
                mt: 8, display: 'flex', flexDirection: 'column', alignItems: 'center',
            }}>
            <Avatar sx={{m: 1, backgroundColor: 'primary.main'}}>
                <LockOutlinedIcon/>
            </Avatar>
            <Typography component="h1" variant="h5">
                Sign in
            </Typography>
            <Box component="form" onSubmit={handleSubmit(onSubmit)} noValidate
                 sx={{mt: 1, width: '100%'}}>
                <TextField
                    {...register('email')}
                    margin="normal"
                    fullWidth
                    label="Email Address"
                    name="email"
                    autoComplete="email"
                    autoFocus
                />
                <Typography variant="overline"
                            sx={{color: 'error.main'}}>{errors.email?.message}</Typography>
                <TextField
                    {...register('password')}
                    margin="normal"
                    required
                    fullWidth
                    name="password"
                    label="Password"
                    type="password"
                    autoComplete="current-password"
                />

                <Typography variant="overline" sx={{color: 'error.main'}}>{errors.password?.message}</Typography>

                <FormControlLabel sx={{display: 'block'}}
                                  control={<Checkbox {...register('remember')} value="remember"
                                                     color="primary"/>} label="Remember me"
                />

                <Button
                    disabled={isSubmitting}
                    type="submit"
                    fullWidth
                    variant="contained"
                    sx={{mt: 3, mb: 2}}
                >
                    {isSubmitting ? 'Submitting ...' : 'Sign In'}
                </Button>

                <Grid container>
                    <Grid item xs>
                        <Link component={RouterLink} to={"/register"} variant="body2">
                            {'Don\'t have an account? Sign Up'}
                        </Link>

                    </Grid>
                    <Grid item>
                        <Link component={RouterLink} to={"/forgot-password"} variant="body2">
                            Forgot password?
                        </Link>
                    </Grid>
                </Grid>
            </Box>
        </Box>
    </Container>)
}

export default LoginForm
