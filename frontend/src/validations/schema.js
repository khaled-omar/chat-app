import * as yup from "yup";

export const forgetPasswordSchema = yup.object({
    email: yup.string().required().email().min(10),
}).required()


export const resetPasswordSchema = yup.object({
    // email: yup.string().required().email().min(5),
    password: yup.string().required().min(5),
    password_confirmation: yup.string().oneOf([yup.ref('password'), null], 'Passwords must match'),
}).required()


export const loginSchema = yup
    .object({
        email: yup.string().required().email().min(5),
        password: yup.string().required().min(5),
    }).required()

export const registerationSchema = yup
    .object({
        username: yup.string().required().min(5),
        name: yup.string().required().min(5),
        email: yup.string().required().email().min(5),
        password: yup.string().required().min(5),
        password_confirmation: yup.string().required().oneOf([yup.ref('password'), null], 'Passwords must match'),
        agree_terms: yup.boolean().oneOf([true], 'You must accept the terms and conditions'),
    }).required()