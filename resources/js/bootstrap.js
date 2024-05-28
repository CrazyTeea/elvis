import axios from 'axios';

window.axios = axios;
const token = document.head.querySelector('meta[name="csrf-token"]');

window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
