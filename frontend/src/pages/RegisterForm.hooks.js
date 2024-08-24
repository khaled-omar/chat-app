import {useForm} from "react-hook-form";
import {yupResolver} from "@hookform/resolvers/yup";
import {registerationSchema} from "../validations/schema.js";
import {toast} from "material-react-toastify";
import {useAuthContext} from "../contexts/AuthContext.jsx";
import useSettings from "../hooks/useSettings.js";
import useDialog from "../hooks/useDialog.js";
import useOtp from "../hooks/useOtp.js";
import {useNavigate} from "react-router-dom";

const settingKeys = ['terms_and_conditions', 'privacy_policy'];

export default function useRegisterFormHooks() {
    const {
        register,
        watch,
        formState: {errors, isSubmitting},
        handleSubmit,
        getValues,
        clearErrors,
        setError
    } = useForm({mode: 'onChange', resolver: yupResolver(registerationSchema)})

    const {openDialog, setOpenDialog, dialogContent, setDialogContent} = useDialog()
    const {displayOtpForm, setDisplayOtpForm, otp, setOtp, sendOtp} = useOtp()
    const {registerNewUser} = useAuthContext()
    const {isPending: isLoading, settings} = useSettings(settingKeys)
    const navigate = useNavigate()


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
        sendOtp(getValues('email'))
    }

    const onSubmit = async (data) => {
        data['g-recaptcha-response'] = 'abcd'
        data.otp = otp
        !!data.otp.code && !!data.otp.ref ?
            await handleRegisterSubmit(data) :
            await handleOtpButtonClick() && setDisplayOtpForm(true);
    }

    const handleRegisterSubmit = async (data) => {
        try {
            await registerNewUser(data)
            toast.success('User successfully registered')
            navigate('/')
        } catch (error) {
            setError('serverError', {
                type: 'root',
                message: error.response.data?.status?.errors,
            })
        }
    }

    return {
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
    }
}


