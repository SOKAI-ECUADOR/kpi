/*=========================================================================================
  File Name: mutations.js
  Description: Vuex Store - mutations
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


const mutations = {


  // /////////////////////////////////////////////
  // COMPONENTS
  // /////////////////////////////////////////////

  // Vertical NavMenu

  TOGGLE_IS_VERTICAL_NAV_MENU_ACTIVE(state, value) {
    state.isVerticalNavMenuActive = value
  },
  TOGGLE_REDUCE_BUTTON(state, val) {
    state.reduceButton = val
  },
  UPDATE_MAIN_LAYOUT_TYPE(state, val) {
    state.mainLayoutType = val
  },
  UPDATE_VERTICAL_NAV_MENU_ITEMS_MIN(state, val) {
    state.verticalNavMenuItemsMin = val
  },
  UPDATE_VERTICAL_NAV_MENU_WIDTH(state, width) {
    state.verticalNavMenuWidth = width
  },


  // VxAutoSuggest

  UPDATE_STARRED_PAGE(state, payload) {

    // find item index in search list state
    const index = state.navbarSearchAndPinList.data.findIndex((item) => item.index == payload.index)

    // update the main list
    state.navbarSearchAndPinList.data[index].highlightAction = payload.val

    // if val is true add it to starred else remove
    if (payload.val) {
      state.starredPages.push(state.navbarSearchAndPinList.data[index])
    }
    else {
      // find item index from starred pages
      const index = state.starredPages.findIndex((item) => item.index == payload.index)

      // remove item using index
      state.starredPages.splice(index, 1)
    }
  },

  // Navbar-Vertical

  ARRANGE_STARRED_PAGES_LIMITED(state, list) {
    const starredPagesMore = state.starredPages.slice(10)
    state.starredPages     = list.concat(starredPagesMore)
  },
  ARRANGE_STARRED_PAGES_MORE(state, list) {
    let downToUp                 = false
    let lastItemInStarredLimited = state.starredPages[10]
    const starredPagesLimited    = state.starredPages.slice(0, 10)
    state.starredPages           = starredPagesLimited.concat(list)

    state.starredPages.slice(0, 10).map((i) => {
      if (list.indexOf(i) > -1) downToUp = true
    })

    if (!downToUp) {
      state.starredPages.splice(10, 0, lastItemInStarredLimited)
    }
  },


  // ////////////////////////////////////////////
  // UI
  // ////////////////////////////////////////////

  TOGGLE_CONTENT_OVERLAY(state, val) { state.bodyOverlay       = val },
  UPDATE_PRIMARY_COLOR(state, val)   { state.themePrimaryColor = val },
  UPDATE_THEME(state, val)           { state.theme             = val },
  UPDATE_WINDOW_WIDTH(state, width)  { state.windowWidth       = width },
  UPDATE_WINDOW_SCROLL_Y(state, val) { state.scrollY = val },


  // /////////////////////////////////////////////
  // User/Account
  // /////////////////////////////////////////////

  // Updates user info in state and localstorage
  UPDATE_USER_INFO(state, payload) {
      if(payload){
        let userInfo = JSON.parse(localStorage.getItem("userInfo")) || state.AppActiveUser;
        for (const property of Object.keys(payload)) {
          if (payload[property] != null) {
            state.AppActiveUser[property] = payload[property];
            userInfo[property] = payload[property];
          }
        }
        localStorage.setItem("userInfo", JSON.stringify(userInfo));
        if(userInfo.id_rol == 3){
          localStorage.setItem("userInfos", true);
        }
      }else{
        state.AppActiveUser = {};
        localStorage.removeItem("userInfo");
        localStorage.removeItem("userInfos");
      }
  },
  UPDATE_USER_TOKEN(state, payload) {
    if(payload){
      if(payload != null){
        state.Token = payload;
      }
      let userToken = localStorage.getItem("Token") || state.Token;
      localStorage.setItem("Token", userToken);
    }else{
      state.Token = "";
      localStorage.removeItem("Token");
    }
  },
  UPDATE_USER_ROLES(state, payload) {
    if(payload){
      if(payload != null){
        state.Roles = payload;
      }
      let userRoles = JSON.parse(localStorage.getItem("Roles")) || state.Roles;
      for (const property of Object.keys(payload)) {
        if (payload[property] != null) {
          state.Roles[property] = payload[property];
          userRoles[property] = payload[property];
        }
      }
      localStorage.setItem("Roles", JSON.stringify(userRoles));
    }else{
      state.Roles = "";
      localStorage.removeItem("Roles");
    }
  },
};

export default mutations;

