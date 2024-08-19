import HttpClient from '../utils/HttpClient'
import { toast } from 'material-react-toastify'

class OtpService {
  static async sendOtp(email) {
    const data = { 'address': email, 'channel': 'email', 'model': 'user' }
    return await HttpClient.post('/otp', data)
      .then((response) => {return response.data.data})
      .catch((error) => toast.error(error.response.data.status.errors))
  }
}

export default OtpService
