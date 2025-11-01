import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true;

// Inertia.js handles CSRF tokens automatically via XSRF-TOKEN cookie
// Laravel automatically sets this cookie and axios reads it automatically
