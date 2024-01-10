/**
 * plugins/index.js
 *
 * Automatically included in `./src/main.js`
 */

// Plugins
import vuetify from './vuetify'
import router from '../router'
import store from '@/store/store'
import axios from 'axios'
import PrimeVue from 'primevue/config';
import VueApexCharts from "vue3-apexcharts";

export function registerPlugins (app) {
  app.config.globalProperties.$axios = axios;
  app
    .use(vuetify)
    .use(router)
    .use(store)
    .use(PrimeVue)
    .use(VueApexCharts)
}
