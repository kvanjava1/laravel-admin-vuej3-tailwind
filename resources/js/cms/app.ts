import './bootstrap'
import { createApp } from 'vue'
import App from './App.vue'
import router from './routers/indexRouter'
import { createPinia } from 'pinia';

const app = createApp(App)
const pinia = createPinia();

app.use(pinia)
app.use(router)
app.mount('#app')