import './assets/main.css'
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
import '@fortawesome/fontawesome-free/css/all.css'
import axios from 'axios';
import { createI18n } from 'vue-i18n'
import EN from './locale/en.json'
import RO from './locale/ro.json'

const i18n = createI18n({
    locale : 'EN',
    messages : {
        EN: EN,
        RO: RO
    }
});

const app = createApp(App)
const pinia = createPinia();

axios.defaults.withCredentials=true;
axios.defaults.baseURL = 'http://localhost:8000';
axios.defaults.withXSRFToken = true;
axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('auth_token')}`;

app.use(pinia)
app.use(router)
app.use(i18n)

app.mount('#app');
