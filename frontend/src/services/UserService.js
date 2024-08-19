import HttpClient from '../utils/HttpClient'
import {toast} from "material-react-toastify";

class UserService {
    static async register(data, onSuccess, onError) {
        await HttpClient.post('/auth/register', data)
            .then((response) => onSuccess(response))
            .catch((error) => onError(error.response.data))
    }

    static async login(data, onSuccess, onError) {
        await HttpClient.post('/auth/login', data)
            .then((response) => onSuccess(response.data))
            .catch((error) => onError(error.response.data))
    }

    static async me() {
        await HttpClient.get('/auth/me')
            .then((response) => response.data.data)
            .catch((error) => toast.error(error.response.data?.status?.errors))
    }

    static async forgetPassword(data) {
        return await HttpClient.post('/auth/forgot-password', data)
            .then(() => toast.success('Reset password email sent to your email address'))
            .catch((error) => toast.error(error.response.data?.status?.errors))
    }
    static async resetPassword(data) {
        return await HttpClient.post('/auth/reset-password', data)
            .catch((error) => toast.error(error.response.data?.status?.errors ? error.response.data?.status?.errors : error.response.data?.status?.message))
    }
}

export default UserService
