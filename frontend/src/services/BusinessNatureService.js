import HttpClient from '../utils/HttpClient'
import { toast } from 'material-react-toastify'

class BusinessNatureService {
  static async findAll() {
    try {
      const response = await HttpClient.get('/business-natures');
      return response.data.data;
    } catch (error) {
      toast.error("General Error");
    }
  }
}

export default BusinessNatureService
