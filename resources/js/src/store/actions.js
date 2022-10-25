const axios = require('axios');

const actions = {

    // /////////////////////////////////////////////
    // COMPONENTS
    // /////////////////////////////////////////////

    // Vertical NavMenu
    updateVerticalNavMenuWidth({ commit }, width) {
      commit('UPDATE_VERTICAL_NAV_MENU_WIDTH', width)
    },

    // VxAutoSuggest
    updateStarredPage({ commit }, payload) {
      commit('UPDATE_STARRED_PAGE', payload)
    },

    // The Navbar
    arrangeStarredPagesLimited({ commit }, list) {
      commit('ARRANGE_STARRED_PAGES_LIMITED', list)
    },
    arrangeStarredPagesMore({ commit }, list) {
      commit('ARRANGE_STARRED_PAGES_MORE', list)
    },

    // /////////////////////////////////////////////
    // UI
    // /////////////////////////////////////////////

    toggleContentOverlay({ commit }) {
      commit('TOGGLE_CONTENT_OVERLAY')
    },
    updateTheme({ commit }, val) {
      commit('UPDATE_THEME', val)
    },

    // /////////////////////////////////////////////
    // User/Account
    // /////////////////////////////////////////////

    updateUserInfo({ commit }, payload) {
      commit('UPDATE_USER_INFO', payload);
    },
    updateUserToken({ commit }, payload) {
      commit('UPDATE_USER_TOKEN', payload);
    },
    updateUserRoles({ commit }, payload) {
      commit('UPDATE_USER_ROLES', payload);
    },

    updateUserRole({ dispatch }, payload) {
      payload.aclChangeRole(payload.userRole);
      dispatch('updateUserInfo', {userRole: payload.userRole});
    },

    loginAction({commit}, payload){
      return new Promise( (resolve, reject) => {
          var datos = {email:payload.userDetails.email,password:payload.userDetails.password};
          var url ='/api/auth/login';
          axios.post(url,datos).then(res => {
            commit('UPDATE_USER_TOKEN', res.data.accessToken);
            commit('UPDATE_USER_ROLES', res.data.roles);
            commit('UPDATE_USER_INFO', res.data.userData);
            resolve(res);
          }).catch(err => {
            reject(err);
            commit('UPDATE_USER_TOKEN', null); 
            commit('UPDATE_USER_INFO', null);
            commit('UPDATE_USER_ROLES', null);
          });
      });
  },
  logoutAction({commit, state}){
    return new Promise((resolve, reject) => {
        axios.post("/api/auth/logout",{token:state.Token}).then(res => {
            commit('UPDATE_USER_TOKEN', null);
            commit('UPDATE_USER_INFO', null);
            commit('UPDATE_USER_ROLES', null);
            localStorage.removeItem("userInfo");
            localStorage.removeItem("Token");
            localStorage.removeItem("Roles");
            localStorage.removeItem("userInfos");
            localStorage.removeItem("Datosselect");
            resolve(res);
        }).catch(err => {
            reject(err);
            commit('UPDATE_USER_TOKEN', null);
            commit('UPDATE_USER_INFO', null);
            commit('UPDATE_USER_ROLES', null);
            localStorage.removeItem("userInfo");
            localStorage.removeItem("Token");
            localStorage.removeItem("Roles");
            localStorage.removeItem("userInfos");
            localStorage.removeItem("Datosselect");
        });
    });
  }
};

export default actions;
