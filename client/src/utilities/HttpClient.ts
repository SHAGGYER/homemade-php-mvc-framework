import axios from "axios";

axios.defaults.baseURL = import.meta.env.VITE_API_URL as string;

const HttpClient = () => {
  const token = localStorage.getItem("token");

  const defaultSettings = {
    headers: {
      authorization: token ? `Bearer ${token}` : "",
    },
  };

  return {
    get: <T>(url: string, options = {}) =>
      axios.get<T>(url, { ...defaultSettings, ...options }),
    post: <T>(url: string, data: any, options = {}) =>
      axios.post<T>(url, data, { ...defaultSettings, ...options }),
    put: <T>(url: string, data: any, options = {}) =>
      axios.put<T>(url, data, { ...defaultSettings, ...options }),
    delete: <T>(url: string, options = {}) =>
      axios.delete<T>(url, { ...defaultSettings, ...options }),
  };
};

export default HttpClient;
