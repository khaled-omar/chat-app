import HttpClient from '../utils/HttpClient'
import { toast } from 'material-react-toastify'

class SettingService {
  static async find(keys) {
    try {
      const response = await HttpClient.get('/setting', {
        params: {
          keys: keys,
        },
      })
      return response.data.data
    } catch (error) {
      toast.error('General Error')
    }
  }
}

export default SettingService
