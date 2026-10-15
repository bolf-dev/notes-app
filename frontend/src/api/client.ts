import axios from 'axios'

// Shared HTTP client – relative base URL, the Vite dev server proxies /api to the backend
export const apiClient = axios.create({
  baseURL: '/api',
})
