import LockOutlinedIcon from '@mui/icons-material/LockOutlined'
import SendToMobileIcon from '@mui/icons-material/SendToMobile'
import AlertDialogSlide from '../components/AlertDialogSlide'
import LoadingSpinner from '../components/LoadingSpinner'
import OtpFormDialog from '../components/OtpFormDialog'
import {Link as RouterLink} from "react-router-dom";
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
import useRegisterFormHooks from "./RegisterForm.hooks.js";


function RegisterForm() {
    const {
        watch,
        register,
        errors,
        isLoading,
        isSubmitting,
        handleSubmit,
        onSubmit,
        openDialog,
        dialogContent,
        displayOtpForm,
        handleClickOpenTerms,
        handleClickOpenPrivacy,
        handleOtpButtonClick,
        setOpenDialog,
        otp,
        setOtp,
        setDisplayOtpForm
    } = useRegisterFormHooks()


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
                    <Typography variant="overline"
                                sx={{color: 'error.main'}}>{errors.password?.message}</Typography>
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
                    <Typography variant="body2"
                                sx={{mb: 2, color: 'error.main'}}>{errors.serverError?.message}</Typography>

                </Box>
                <Grid container sx={{mb: 2}}>
                    <Grid item xs>
                        <Link component={RouterLink} to={"/login"} variant="body2">
                            {'Already have an account? Sign In'}
                        </Link>
                    </Grid>
                    <Grid item>
                        <Link component={RouterLink} to={"/forgot-password"} variant="body2">
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
