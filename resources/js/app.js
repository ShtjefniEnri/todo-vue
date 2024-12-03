import './bootstrap';
import { createApp } from 'vue'
import '../../vue/src/style.css'
import App from '../../vue/src/App.vue'
import ToDo from "../../vue/src/components/ToDo.vue";

createApp(App).component('ToDo', ToDo).mount('#app')
