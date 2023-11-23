/**
 * main.js
 *
 * Bootstraps Vuetify and other plugins then mounts the App`
 */
//devExtreme
import 'devextreme/dist/css/dx.light.css';

//PrimeVue
import "primeflex/primeflex.css";
import 'primevue/resources/themes/lara-light-green/theme.css'
import 'primevue/resources/primevue.min.css';
import 'primeicons/primeicons.css';

// Plugins
import { registerPlugins } from '@/plugins'

// Components
import App from './App.vue'

// Composables
import { createApp } from 'vue'

// Bootstrap
import 'bootstrap/dist/css/bootstrap.css';

const app = createApp(App)

registerPlugins(app)

app.mount('#app')
