import axios from "axios";
import urlProvider from "./url-provider";

const apiClient = axios.create({
    baseURL: `${urlProvider.getServerEndpoint()}`,
    withCredentials: true
});

export const setupInterceptors = (router) => {
  apiClient.interceptors.response.use(
    response => response,
    error => {
      if (error.response && error.response.status === 401) {
        router.push('/');
      }
      return Promise.reject(error);
    }
  );
};

export default apiClient;