import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';
import axios from 'axios';
import BootstrapVue3 from 'bootstrap-vue-3';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue-3/dist/bootstrap-vue-3.css';
import 'video.js/dist/video-js.css';

axios.defaults.baseURL = 'http://localhost/my_series_app/public/';
axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;

const app = createApp(App);
app.use(router);
app.use(store);
app.use(BootstrapVue3);
app.mount('#app');
