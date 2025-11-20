import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import router from './router/router'
import 'normalize.css';
import axios from 'axios'

axios.defaults.withCredentials = true
axios.defaults.withXSRFToken = true

const app = createApp(App)

app.use(router)

app.mount('#app')