import './bootstrap';

import { createApp } from 'vue';
import App from './components/App.vue';
import router from './router';
import vuetify from './vuetify'
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'

createApp(App)
    .use(vuetify)
    .use(router)
    .mount('#app');
