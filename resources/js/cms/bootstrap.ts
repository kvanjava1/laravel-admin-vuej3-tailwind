import axios from 'axios';
import type { AxiosStatic } from 'axios';

interface WindowWithAxios extends Window {
  axios: AxiosStatic;
}

declare const window: WindowWithAxios;

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';