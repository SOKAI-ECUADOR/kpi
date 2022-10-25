/*=========================================================================================
  File Name: state.js
  Description: Vuex Store - state
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

import navbarSearchAndPinList from "@/layouts/components/navbar/navbarSearchAndPinList"
import themeConfig from "@/../themeConfig.js"
import colors from "@/../themeConfig.js"

// /////////////////////////////////////////////
// Helper
// /////////////////////////////////////////////

// *From Auth - Data will be received from auth provider
/*const userDefaults = {
  uid         : 0,          // From Auth
  displayName : "John Doe", // From Auth
  about       : "Dessert chocolate cake lemon drops jujubes. Biscuit cupcake ice cream bear claw brownie brownie marshmallow.",
  photoURL    : require("@assets/images/portrait/small/avatar-s-11.png"), // From Auth
  status      : "online",
  userRole    : "admin"
};*/

const userInfoLocalStorage = JSON.parse(localStorage.getItem("userInfo")) || {};
const getUserInfo = () => {
  let userInfo = {};
  Object.keys(userInfoLocalStorage).forEach((key) => {
    if(userInfo[key] == undefined && userInfoLocalStorage[key] != null) userInfo[key] = userInfoLocalStorage[key]
  });
  return userInfo;
};

const userRoles = JSON.parse(localStorage.getItem("Roles")) || [];
const getUserRoles = () => {
  let userInfo = [];
  Object.keys(userRoles).forEach((key) => {
    if(userInfo[key] == undefined && userRoles[key] != null) userInfo[key] = userRoles[key]
  });
  return userInfo;
};

const usertoken = localStorage.getItem("Token") || "";

let loggedUser = false;
if(usertoken){
  loggedUser = true;
}

const state = {
    AppActiveUser           : getUserInfo(),
    Roles                   : getUserRoles(),
    Token                   : usertoken,
    bodyOverlay             : false,
    isVerticalNavMenuActive : true,
    mainLayoutType          : themeConfig.mainLayoutType || "vertical",
    navbarSearchAndPinList  : navbarSearchAndPinList,
    reduceButton            : themeConfig.sidebarCollapsed,
    verticalNavMenuWidth    : "default",
    verticalNavMenuItemsMin : false,
    scrollY                 : 0,
    starredPages            : navbarSearchAndPinList.data.filter((page) => page.highlightAction),
    theme                   : themeConfig.theme || "light",
    themePrimaryColor       : colors.primary,
    windowWidth             : null,
    loggedIn                : loggedUser,
};

export default state;
