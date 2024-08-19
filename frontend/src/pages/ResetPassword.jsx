import React from 'react'
import {
    Avatar,
    Box,
    Button,
    Container,
    TextField,
    Typography
} from "@mui/material";
import LockOutlinedIcon from "@mui/icons-material/LockOutlined";
import {useForm} from "react-hook-form";
import {yupResolver} from "@hookform/resolvers/yup";
import {resetPasswordSchema} from "../validations/schema";
import UserService from "../services/UserService";
import {useNavigate, useSearchParams} from "react-router-dom";
import {toast} from "material-react-toastify";


function ResetPassword() {
    const {
        register,
        formState: {errors, isSubmitting},
        handleSubmit,
    } = useForm({resolver: yupResolver(resetPasswordSchema)})

    const [searchParams] = useSearchParams();
    const navigate = useNavigate();

    const email = searchParams.get('email');
    const token = searchParams.get('token');

    if (!email || !token) {
        window.location.href = '/login'
    }

    const onSubmit = async (data) => {
        data['email'] = email;
        data['token'] = token;
        data['g-recaptcha-response'] = 'abcd';
        await UserService.resetPassword(data)
            .then(() => {
                toast.success('Password Reset successfully')
                navigate('/login')
            })
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
                <Typography variant="overline"  sx={{color: 'error.main'}}>{errors.password?.message}</Typography>

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
                <Typography variant="overline"  sx={{color: 'error.main'}}>{errors.password_confirmation?.message}</Typography>

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
