import axios from 'axios';
window.axios = axios;
axios.defaults.withCredentials = true;
axios.defaults.baseXSRFToken = true;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


export default axios;
