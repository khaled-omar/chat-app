import React, {useEffect, useState} from 'react'
import LockOutlinedIcon from '@mui/icons-material/LockOutlined'
import {useForm} from 'react-hook-form'
import * as yup from 'yup'
import {yupResolver} from '@hookform/resolvers/yup'
import {toast} from 'material-react-toastify'
import SendToMobileIcon from '@mui/icons-material/SendToMobile'

import {
    Avatar,
    Box,
    Button,
    Checkbox, Container,
    FormControlLabel, FormGroup,
    Grid,
    Link,
    TextField,
    Typography,
} from '@mui/material'
import UserService from '../services/UserService'
import CountrySelect from '../components/CountrySelect'
import AlertDialogSlide from '../components/AlertDialogSlide'
import SettingService from '../services/SettingService'
import LoadingSpinner from '../components/LoadingSpinner'
import OtpFormDialog from '../components/OtpFormDialog'
import OtpService from '../services/OtpService'
import {Link as RouterLink} from "react-router-dom";

const schema = yup
    .object({
        company_name: yup.string().required().min(5),
        country_code: yup.string(),
        business_nature: yup.array().required().min(1),
        position: yup.string().required().min(5),
        name: yup.string().required().min(5),
        email: yup.string().required().email().min(5),
        password: yup.string().required().min(5),
        password_confirmation: yup.string().required().oneOf([yup.ref('password'), null], 'Passwords must match'),
        agree_terms: yup.boolean().oneOf([true], 'You must accept the terms and conditions'),
    }).required()

function RegisterForm() {

    const {
        register,
        watch,
        formState: {errors, isSubmitting},
        handleSubmit,
        getValues,
        setError,
        clearErrors
    } = useForm({mode: 'onChange', resolver: yupResolver(schema)})
    const [settings, setSettings] = useState([])
    const [isLoading, setIsLoading] = useState(false)
    const [openDialog, setOpenDialog] = React.useState(false)
    const [dialogContent, setDialogContent] = React.useState({})
    const [displayOtpForm, setDisplayOtpForm] = React.useState(false)
    const [otp, setOtp] = React.useState({})

    useEffect(() => {
         const fetchOnPageLoadData = async () => {
             SettingService.find(['terms_and_conditions', 'privacy_policy']).then((settingsResponse) => {
                 setSettings(settingsResponse)
                 setIsLoading(false)
             })
        }
         // fetchOnPageLoadData()
    }, [])

    const handleOnSuccess = () => {
        // setCookie('access_token', response.data.access_token)
        // setCookie('refresh_token', response.data.refresh_token)
        toast.success('User logged in successfully')
    }
    const handleOnError = (error) => {
        toast.error(error.status.errors)
    }

    const handleClickOpenTerms = async () => {
        const content = settings.find((item) => item.key === 'terms_and_conditions')
        setDialogContent({'title': 'Terms and conditions', 'description': content.value})
        setOpenDialog(true)
    }

    const handleClickOpenPrivacy = async () => {
        const content = settings.find((item) => item.key === 'privacy_policy')
        setDialogContent({'title': 'Privacy Policy', 'description': content.value})
        setOpenDialog(true)
    }

    const handleOtpButtonClick = async () => {
        clearErrors('email')
        const email = getValues('email')
        if (email.length === 0) {
            setError('email', {type: 'manual', message: 'Please enter email to send OTP'})
        } else {
            const data = await OtpService.sendOtp(email)
            if (!data.ref) {
                return;
            }
            setOtp({...otp, ref: data.ref})
            setDisplayOtpForm(true)
        }
    }

    const onSubmit = async (data) => {
        data['g-recaptcha-response'] = 'abcd'
        data.country_code = country.code
        data.otp = otp
        data.otp.code && data.otp.ref ? sendRegisterRequest(data) : handleOtpButtonClick() && setDisplayOtpForm(true)
    }
    const sendRegisterRequest = async (data) => {
        await UserService.register(data, handleOnSuccess, handleOnError)
    }
    if (isLoading) {
        return (<LoadingSpinner/>)
    }

    return (
        <Container maxWidth="sm">
            <Box
                sx={{
                    marginTop: 8, display: 'flex', flexDirection: 'column', alignItems: 'center',
                }}>
                <Avatar sx={{m: 1, bgcolor: 'primary.main'}}>
                    <LockOutlinedIcon/>
                </Avatar>
                <Typography component="h1" variant="h5">
                    Sign up
                </Typography>
                <Box component="form" onSubmit={handleSubmit(onSubmit)} noValidate
                     sx={{mt: 1, width: '100%'}}>
                    <TextField
                        {...register('username')}
                        margin="normal"
                        fullWidth
                        label="Username"
                        name="username"
                        autoComplete="username"
                        autoFocus
                    />
                    <Typography variant="overline"
                                sx={{color: 'error.main'}}>{errors.username?.message}</Typography>

                    <TextField
                        {...register('name')}
                        fullWidth
                        margin="normal"
                        label="Name"
                        name="name"
                        autoComplete="name"
                        autoFocus
                    />
                    <Typography variant="overline"
                                sx={{color: 'error.main'}}>{errors.name?.message}</Typography>

                    <Grid container sx={{gap: 1}}>
                        <Grid item xs={8}>
                            <TextField
                                {...register('email')}
                                margin="normal"
                                fullWidth
                                label="Email Address"
                                name="email"
                                autoComplete="email"
                                autoFocus

                            />
                        </Grid>
                        <Grid item sx={{mx: '0.5rem', alignSelf: 'center', justifySelf: 'end'}}>
                            <Button disabled={!!errors.email || !watch('email')} endIcon={<SendToMobileIcon/>}
                                    onClick={handleOtpButtonClick}>
                                Send Otp
                            </Button>
                        </Grid>
                    </Grid>
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
                        autoComplete="password"
                    />
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

                    {!isLoading &&
                        <Box>
                            <FormControlLabel
                                control={<Checkbox {...register('agree_terms')}
                                                   name="agree_terms"
                                                   color="primary"/>}
                                label={<p>I agree <Link href="#" onClick={(e) => {
                                    e.preventDefault()
                                    handleClickOpenTerms()
                                }} variant="body2">Terms Of
                                    service </Link>
                                    and <Link href="#" onClick={(e) => {
                                        e.preventDefault()
                                        handleClickOpenPrivacy()
                                    }} variant="body2">Privacy Policy</Link></p>}
                            />
                            <Typography variant="overline"
                                        sx={{
                                            display: 'block',
                                            color: 'error.main'
                                        }}>{errors.agree_terms?.message}</Typography>
                        </Box>
                    }

                    <Button
                        disabled={isSubmitting}
                        type="submit"
                        fullWidth
                        variant="contained"
                        sx={{mt: 1, mb: 2, borderRadius: '10px', textTransform: 'UPPER'}}
                    >
                        {isSubmitting ? 'Submitting ...' : 'Sign In'}
                    </Button>
                </Box>
                <Grid container sx={{mb: 2}}>
                    <Grid item xs>
                        <Link component={RouterLink} to="/login" variant="body2">
                            {'Already have an account? Sign In'}
                        </Link>
                    </Grid>
                    <Grid item>
                        <Link component={RouterLink} to="/forgot-password" variant="body2">
                            Forgot password?
                        </Link>
                    </Grid>
                </Grid>
                <AlertDialogSlide openDialog={openDialog} setOpenDialog={setOpenDialog}
                                  title={dialogContent.title}
                                  description={dialogContent.description}/>
                <OtpFormDialog otp={otp} setOtp={setOtp} displayOtpForm={displayOtpForm}
                               setDisplayOtpForm={setDisplayOtpForm}/>
            </Box>
        </Container>
    )
}

export default RegisterForm
