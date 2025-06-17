import axios from 'axios';

const apiClient = axios.create({
  baseURL: 'http://127.0.0.1:8000',
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  withCredentials: true 
})

apiClient.interceptors.response.use(
  response => response,
  error => {
    if (error.response?.status === 401) {
      alert('Unauthorized access. Redirecting to login page.' + error);
      window.location.href = '/login'
    }
    return Promise.reject(error)
  }
)

export default apiClient