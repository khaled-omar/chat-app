import {useForm} from "react-hook-form";
import {yupResolver} from "@hookform/resolvers/yup";
import {useAuthContext} from "../contexts/AuthContext.jsx";
import {useNavigate} from "react-router-dom";
import {loginSchema} from "../validations/schema.js";


export default function useLoginFormHooks() {
    const {
        register,
        setError,
        formState: {errors, isSubmitting},
        handleSubmit
    } = useForm({resolver: yupResolver(loginSchema)})

    const {login} = useAuthContext()
    const navigate = useNavigate();

    const onSubmit = async (data) => {
        try {
            const loginData = {'email': data.email, 'password': data.password, 'g-recaptcha-response': 'abcd'}
            await login(loginData)
            navigate('/')
        } catch (e) {
            setError('email', {
                type: 'manual',
                message: 'Invalid email or password',
            })
        }
    }

    return {register, setError, errors, isSubmitting, handleSubmit, onSubmit}
}


