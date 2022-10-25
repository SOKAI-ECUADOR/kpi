/*=========================================================================================
  File Name: themeConfig.js
  Description: Theme configuration
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: hhttp://www.themeforest.net/user/pixinvent
==========================================================================================*/

// MAIN COLORS - VUESAX THEME COLORS
let colors = {
    primary: '#f28b2c',
    success: '#28C76F',
    danger: '#EA5455',
    warning: '#FF9F43',
    dark: '#1E1E1E',
    yellow1: '#f0b200',
    yellow2: '#f6da00',
    yellow3: '#f9f400',
    green1: '#aed424',
    green2: '#7ec540',
    green3: '#3ea2a3',
    blue1: '#4b5bb0',
    purple1: '#513ea4',
    purple2: '#76379f',
    fusia1: '#c61b83',
    orange1: '#e02523',
    orange2: '#e88208'
};

import Vue from 'vue';
import Vuesax from 'vuesax';
Vue.use(Vuesax, { theme: { colors } });


// CONFIGS
const themeConfig = {
    disableCustomizer: false, // options[Boolean] : true, false(default)
    disableThemeTour: true, // options[Boolean] : true, false(default)
    //disableThemeTour  : false,        // options[Boolean] : true, false(default)
    footerType: "static", // options[String]  : static(default) / sticky / hidden
    hideScrollToTop: false, // options[Boolean] : true, false(default)
    mainLayoutType: "vertical", // options[String]  : vertical(default) / horizontal
    navbarColor: "#fff", // options[String]  : HEX color / rgb / rgba / Valid HTML Color name - (default: #fff)
    navbarType: "floating", // options[String]  : floating(default) / static / sticky / hidden
    routerTransition: "zoom-fade", // options[String]  : zoom-fade / slide-fade / fade-bottom / fade / zoom-out / none(default)
    sidebarCollapsed: false, // options[Boolean] : true, false(default)
    theme: "semi-dark", // options[String]  : "light"(default), "dark", "semi-dark"

    // Not required yet - WIP
    userInfoLocalStorageKey: "userInfo",

    // NOTE: themeTour will be disabled in screens < 1200. Please refer docs for more info.
};


export default themeConfig;