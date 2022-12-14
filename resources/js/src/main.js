/*=========================================================================================
  File Name: main.js
  Description: main vue(js) file
  ----------------------------------------------------------------------------------------
  Item Name: Vuesax Admin - VueJS Dashboard Admin Template
  Author: Pixinvent
  Author URL: hhttp://www.themeforest.net/user/pixinvent
==========================================================================================*/


import Vue from 'vue';
import App from './App.vue';

// Vuesax Component Framework
import Vuesax from 'vuesax';

// Vue select
import vSelect from 'vue-select';

import CKEditor from 'ckeditor4-vue';

import { LMap, LTileLayer, LMarker, LPopup } from 'vue2-leaflet';
import 'leaflet/dist/leaflet.css';

// Components
Vue.component('v-select',vSelect);
Vue.use(CKEditor);
Vue.component('l-map', LMap);
Vue.component('l-tile-layer', LTileLayer);
Vue.component('l-marker', LMarker);
Vue.component('l-popup', LPopup);
Vue.use(Vuesax);

// axios
import axios from "./axios.js";
Vue.prototype.$http = axios;

// mock
import "./fake-db/index.js";

// Theme Configurations
import '../themeConfig.js';

// ACL
import acl from './acl/acl';

// Globally Registered Components
import './globalComponents.js';

// Vue Router
import router from './router';

// Vuex Store
import store from './store/store';

// i18n
import i18n from './i18n/i18n';

// Vuesax Admin Filters
import './filters/filters';

// Clipboard
import VueClipboard from 'vue-clipboard2';
Vue.use(VueClipboard);


// Tour
import VueTour from 'vue-tour';
Vue.use(VueTour);
require('vue-tour/dist/vue-tour.css');


// VeeValidate
import VeeValidate from 'vee-validate';
Vue.use(VeeValidate);


// Google Maps
import * as VueGoogleMaps from 'vue2-google-maps';
Vue.use(VueGoogleMaps, {
    load: {
        // Add your API key here
        key: 'AIzaSyB4DDathvvwuwlwnUu7F4Sow3oU22y5T1Y',
        libraries: 'places', // This is required if you use the Auto complete plug-in
    },
});

// Vuejs - Vue wrapper for hammerjs
import { VueHammer } from 'vue2-hammer';
Vue.use(VueHammer);

import Toast from "vue-toastification";
// Import the CSS or use your own!
import "vue-toastification/dist/index.css";
Vue.use(Toast);

import VueCurrencyFilter from 'vue-currency-filter';
Vue.use(VueCurrencyFilter, {
    symbol: '$', // El s??mbolo, por ejemplo ???
    thousandsSeparator: ',', // Separador de miles
    fractionCount: 2, // ??Cu??ntos decimales mostrar?
    fractionSeparator: '.', // Separador de decimales
    symbolPosition: 'front', // Posici??n del s??mbolo. Puede ser al inicio ('front') o al final ('') es decir, si queremos que sea al final, en lugar de front ponemos una cadena vac??a ''
    symbolSpacing: true // Indica si debe poner un espacio entre el s??mbolo y la cantidad
  });

// PrismJS
import 'prismjs';
// import 'prismjs/themes/prism-tomorrow.css';

// Feather font icon
require('@assets/css/iconfont.css');

// vue - select styles
import 'vue-select/dist/vue-select.css'

Vue.config.productionTip = false;

new Vue({
    router,
    store,
    i18n,
    acl,
    render: h => h(App)
}).$mount('#app');
