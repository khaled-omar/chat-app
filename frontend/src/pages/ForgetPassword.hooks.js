import {useForm} from "react-hook-form";
import {yupResolver} from "@hookform/resolvers/yup";
import {useAuthContext} from "../contexts/AuthContext.jsx";
import {useNavigate} from "react-router-dom";
import {forgetPasswordSchema, loginSchema} from "../validations/schema.js";
import UserService from "../services/UserService.js";


export default function useForgetPasswordHooks() {
    const {
        register,
        setError,
        formState: {errors, isSubmitting},
        handleSubmit
    } = useForm({resolver: yupResolver(forgetPasswordSchema)})

    const navigate = useNavigate()

    const onSubmit = async (data) => {
        try {
            data['g-recaptcha-response'] = 'abcd';
            await UserService.forgetPassword(data)
            navigate('/login')
            // eslint-disable-next-line no-unused-vars
        } catch (e) {
            setError('email', {
                type: 'manual',
                message: 'The selected email is invalid',
            })
        }
    }

    return {register, setError, errors, isSubmitting, handleSubmit, onSubmit}
}


