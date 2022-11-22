import './bootstrap';

import Vue from 'vue';
import BusesDashboard from './Components/Dashboards/BusesTable.vue';
import Lang from './lang';
import vSelect from 'vue-select'

import 'vue-select/dist/vue-select.css';

Vue.component('v-select', vSelect)
Vue.component('buses-dashboard', BusesDashboard);

Vue.prototype.$lang = Lang;
// Vue.component('buses-dashboard', require('Components/Dashboards/BusesTable'));
Lang.setLocale('ru');

Vue.filter('trans', (...args) => {
    return Lang.get(...args);
});
Vue.prototype.$onlyData = (data) => {
    return JSON.parse(JSON.stringify(data))
}

const app = new Vue({
    el: '#vue-shell'
});