import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

// import { isEqual } from "fast-deep-equal";
import VueGoogleMaps from "@fawmi/vue-google-maps";

const app = createApp(App)

app.use(createPinia())

app.use(router)

app.use(VueGoogleMaps, {
  load: {
    key: "EMPTY",
    libraries: "places",
  },
});

app.mount('#app')
