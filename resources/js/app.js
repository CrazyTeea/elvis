import './bootstrap';
import '../scss/app.scss'
import '@mdi/font/css/materialdesignicons.css'
import {createApp, ref} from "vue";
import App from "./App.vue"
import router from "./router.js"
import 'vuetify/styles'
import {createVuetify} from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import {createPinia} from "pinia";
import {PiniaSharedState} from "pinia-shared-state";


const vuetify = createVuetify({
    components,
    directives,
    theme: {
        defaultTheme: 'myCustomTheme',
        themes: {
            myCustomTheme: {
                dark: true,
                colors: {
                    background: 'rgba(44, 47, 51, 1)',
                    surface: 'rgba(44, 47, 51, 1)',

                },
                variables: {
                    'border-color': '#000000',
                    'border-opacity': '0.2',
                    'card-background': 'rgba(44, 47, 51, 1)'
                }
            }
        }

    }
})
const pinia = createPinia()
pinia.use(PiniaSharedState({enable: true, initialize: true}),)

const app = createApp(App)
app.use(vuetify).use(router).use(pinia)
app.mount('#app')
