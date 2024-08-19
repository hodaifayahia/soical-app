import axios from "axios";

const instance = axios.create();

// Add a request interceptor
instance.interceptors.request.use(function (config) {
    // Do something before the request is sent
    // e.g., add headers, modify config, etc.

    // Return the config object to proceed with the request
    return config;
});

// Export the configured Axios instance
export default instance;
