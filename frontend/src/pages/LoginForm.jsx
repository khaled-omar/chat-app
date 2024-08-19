import LockOutlinedIcon from '@mui/icons-material/LockOutlined'
import {useForm} from 'react-hook-form'
import * as yup from 'yup'
import {yupResolver} from '@hookform/resolvers/yup'
import {toast} from 'material-react-toastify'
import {Link as RouterLink, useNavigate} from 'react-router-dom'
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
import UserService from '../services/UserService'

const schema = yup
    .object({
        email: yup.string().required().email().min(5),
        password: yup.string().required().min(5),
    }).required()

function LoginForm() {
    const {
        register,
        setError,
        formState: {errors, isSubmitting},
        handleSubmit
    } = useForm({resolver: yupResolver(schema)})
    const navigate = useNavigate();
    const handleOnSuccess = async (response) => {
        localStorage.setItem('access_token', response.data.access_token)
        localStorage.setItem('refresh_token', response.data.refresh_token)
        const responseUser = await UserService.me()
        localStorage.setItem('current_user', responseUser)
        toast.success('User logged in successfully')
        navigate('/')
    }
    const handleOnError = () => {
        toast.error('Invalid email or password')
        setError('email', {
            type: 'manual',
            message: 'Invalid email or password',
        })
    }

    const onSubmit = async (data) => {
        let loginData = {'email': data.email, 'password': data.password, 'g-recaptcha-response': 'abcd'}
        await UserService.login(loginData, handleOnSuccess, handleOnError)
    }

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
                    { ...register('email') }
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
