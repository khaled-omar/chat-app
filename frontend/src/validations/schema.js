import * as yup from "yup";

export const forgetPasswordSchema = yup.object({
    email: yup.string().required().email().min(5),
}).required()


export const resetPasswordSchema = yup.object({
    // email: yup.string().required().email().min(5),
    password: yup.string().required().min(5),
    password_confirmation: yup.string().oneOf([yup.ref('password'), null], 'Passwords must match'),
}).required()