import HttpClient from '../utils/HttpClient'
import {toast} from "material-react-toastify";

class UserService {
    static async register(data) {
       return await HttpClient.post('/auth/register', data)
            .then((response) => response.data)
    }

    static async login(data) {
        return await HttpClient.post('/auth/login', data)
            .then((response) => response.data)
    }

    static async me() {
        return await HttpClient.get('/auth/me')
            .then((response) => response.data.data)
    }

    static async forgetPassword(data) {
        return await HttpClient.post('/auth/forgot-password', data)
            .then(() => toast.success('Reset password email sent to your email address'))
    }
    static async resetPassword(data) {
        return await HttpClient.post('/auth/reset-password', data)
            .catch((error) => toast.error(error.response.data?.status?.errors ? error.response.data?.status?.errors : error.response.data?.status?.message))
    }
}

export default UserService
