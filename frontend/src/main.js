import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import '@fortawesome/fontawesome-free/css/all.css'
const app = createApp(App)

import axios from 'axios';

axios.defaults.withCredentials=true;
axios.defaults.baseURL = 'http://localhost:8000';
axios.defaults.withXSRFToken = true;

app.use(createPinia())
app.use(router)

app.mount('#app')
