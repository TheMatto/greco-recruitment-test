import '../scss/index.scss';
import { createApp } from 'vue';
import App from './vue/App.vue';

const app = createApp(App);

app.mount(document.getElementById('app'));
