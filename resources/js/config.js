import axios from 'axios'
import toastr from "toastr";

toastr.options.closeButton = true;
toastr.options.positionClass = 'toast-top-center';

const api = axios.create({
    baseURL:  window.location.protocol + '//' + window.location.hostname + '/api/',
    timeout: 60000,
    params: {}
});

api.interceptors.request.use(function (config) {
    const token = localStorage.getItem("token");
    if (token) {
        config.headers['Authorization'] = 'Bearer ' + token;
    }

    config.headers['Content-Type'] = 'application/json';


    return config;
}, function (err) {
    return Promise.reject(err)
});
export default api
