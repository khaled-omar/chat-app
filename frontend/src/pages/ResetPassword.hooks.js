import {useForm} from "react-hook-form";
import {yupResolver} from "@hookform/resolvers/yup";
import {useAuthContext} from "../contexts/AuthContext.jsx";
import {useNavigate, useSearchParams} from "react-router-dom";
import {forgetPasswordSchema, loginSchema, resetPasswordSchema} from "../validations/schema.js";
import UserService from "../services/UserService.js";
import {toast} from "material-react-toastify";
import {useEffect} from "react";


export default function useResetPasswordHooks() {
    const {
        register,
        formState: {errors, isSubmitting},
        handleSubmit,
    } = useForm({resolver: yupResolver(resetPasswordSchema)})

    const [searchParams] = useSearchParams();
    const navigate = useNavigate();

    const email = searchParams.get('email');
    const token = searchParams.get('token');

    useEffect(() => {
        if (!email || !token) {
            navigate('/login')
        }
    }, [email, token]);


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

    return {email, register, errors, isSubmitting, handleSubmit, onSubmit}
}


