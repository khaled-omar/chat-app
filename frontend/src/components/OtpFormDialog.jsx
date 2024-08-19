import * as React from 'react'
import Button from '@mui/material/Button'
import Dialog from '@mui/material/Dialog'
import DialogActions from '@mui/material/DialogActions'
import DialogContent from '@mui/material/DialogContent'
import DialogTitle from '@mui/material/DialogTitle'
import {Controller, useForm} from 'react-hook-form'
import {Box, Typography} from '@mui/material'
import {MuiOtpInput} from 'mui-one-time-password-input'

export default function OtpFormDialog({otp, setOtp, displayOtpForm, setDisplayOtpForm}) {
    const {control, reset, getValues, setError, clearErrors} = useForm({
        defaultValues: {
            otp: '',
        },
    })

    const handleClose = () => {
        setDisplayOtpForm(false)
        reset()
    }

    const handleOtpSubmit = (event) => {
        event.preventDefault()
        clearErrors('otp')
        const code = getValues('otp')
        if (otp.length !== 6) {
            setError('otp', {type: 'manual', message: 'Invalid OTP'})
        }
        setOtp({...otp, code: code})
        handleClose();
    }

    return (
        <React.Fragment>
            <Dialog
                open={displayOtpForm}
                onClose={handleClose}
                PaperProps={{
                    component: 'form',
                    onSubmit: handleOtpSubmit,
                }}
            >
                <DialogTitle>
                    <Typography variant="body1"
                                sx={{mt: 2, color: 'primary.main', textAlign: 'center'}}>{'Verify OTP'}</Typography>


                </DialogTitle>
                <DialogContent>
                    <Controller
                        name="otp"
                        control={control}
                        rules={{validate: (value) => value.length === 6}}
                        render={({field, fieldState}) => (
                            <Box>
                                <MuiOtpInput sx={{
                                    display: 'flex',
                                    flexDirection: 'row',
                                    gap: '5px',
                                    maxWidth: '350px',
                                }} {...field} length={6}/>
                                {fieldState.invalid ? (
                                    <Typography variant="subtitle1"
                                                sx={{mt: 2, color: 'error.main', textAlign: 'center'}}>OTP
                                        invalid</Typography>

                                ) : null}


                            </Box>
                        )}
                    />
                </DialogContent>
                <DialogActions>
                    <Button sx={{color: 'error.main'}}
                            onClick={handleClose}>Cancel</Button>
                    <Button type="submit">Save</Button>
                </DialogActions>
            </Dialog>
        </React.Fragment>
    )
}
