import useResetPasswordHooks from "./ResetPassword.hooks.js";
import LockOutlinedIcon from "@mui/icons-material/LockOutlined";
import {
    Avatar,
    Box,
    Button,
    Container,
    TextField,
    Typography
} from "@mui/material";


function ResetPassword() {
    const {email, register, errors, isSubmitting, handleSubmit, onSubmit} = useResetPasswordHooks()

    return (<Container maxWidth="sm">
        <Box
            sx={{
                mt: 8, display: 'flex', flexDirection: 'column', alignItems: 'center',
            }}>
            <Avatar sx={{m: 1, backgroundColor: 'primary.main'}}>
                <LockOutlinedIcon/>
            </Avatar>
            <Typography component="h1" variant="h5">
                Reset your password!
            </Typography>
            <Box component="form" onSubmit={handleSubmit(onSubmit)} noValidate sx={{mt: 1, width: '100%'}}>
                <TextField
                    {...register('email')}
                    defaultValue={email}
                    margin="normal"
                    fullWidth
                    label="Email Address"
                    name="email"
                    autoComplete="email"
                    disabled={true}
                />
                <Typography variant="overline" sx={{color: 'error.main'}}>{errors.email?.message}</Typography>

                <TextField
                    {...register('password')}
                    margin="normal"
                    required
                    fullWidth
                    name="password"
                    label="Password"
                    type="password"
                    autoComplete="password"
                />
                <Typography variant="overline" sx={{color: 'error.main'}}>{errors.password?.message}</Typography>

                <TextField
                    {...register('password_confirmation')}
                    margin="normal"
                    required
                    fullWidth
                    name="password_confirmation"
                    label="Password Confirmation"
                    type="password"
                    autoComplete="password_confirmation"
                />
                <Typography variant="overline"
                            sx={{color: 'error.main'}}>{errors.password_confirmation?.message}</Typography>

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
        </Box>
    </Container>)
}

export default ResetPassword
