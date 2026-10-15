import 'vue-sonner/style.css'
import './assets/styles/tokens.css'
import './assets/styles/base.css'
import './assets/styles/toast.css'

import { createApp } from 'vue'
import App from './App.vue'
import { router } from './router'

createApp(App).use(router).mount('#app')
