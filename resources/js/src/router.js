import Vue from "vue";
import Router from "vue-router";
import store from "./store/store";
import { values } from "./routesAuth/values";
const { routesAndValue } = require("./routesAuth/routesAndValues");
Vue.use(Router);
var cont = [];
if (store.state.Roles.length) {
    var pep = store.state.Roles;
    console.log("pep ", pep);
    for (const rol of pep) {
        let rolValue = values.find(e => e == rol.value);
        if (rolValue) {
            let route = routesAndValue.find(e => e.value == rolValue);
            if (route) {
                if (store.state.AppActiveUser.id_rol == 3) {
                    if (route.value == 1) {
                        if (route.ver && route.ver.up && rol.ver == 1) {
                            cont.push(...route.ver.up);
                        } else if (route.ver && route.ver.down) {
                            cont.push(...route.ver.down);
                        }
                        // evalua permisos de EDITAR
                        if (route.editar && route.editar.up && rol.editar == 1) {
                            cont.push(...route.editar.up);
                        } else if (route.editar && route.editar.down) {
                            cont.push(...route.editar.down);
                        }
                        // evalua permisos de CREAR
                        if (route.crear && route.crear.up && rol.crear == 1) {
                            cont.push(...route.crear.up);
                        } else if (route.crear && route.crear.down) {
                            cont.push(...route.crear.down);
                        }
                    }
                } else {
                    // evalua permisos de VER
                    if (route.ver && route.ver.up && rol.ver == 1) {
                        if (route.value == 1) {
                            cont.push(...route.ver.ups);
                        } else {
                            cont.push(...route.ver.up);
                        }
                    } else if (route.ver && route.ver.down) {
                        cont.push(...route.ver.down);
                    }
                    // evalua permisos de EDITAR
                    if (route.editar && route.editar.up && rol.editar == 1) {
                        cont.push(...route.editar.up);
                    } else if (route.editar && route.editar.down) {
                        cont.push(...route.editar.down);
                    }
                    // evalua permisos de CREAR
                    if (route.crear && route.crear.up && rol.crear == 1) {
                        cont.push(...route.crear.up);
                    } else if (route.crear && route.crear.down) {
                        cont.push(...route.crear.down);
                    }
                }
            }
        } else {}
    }
}
cont.push({
    path: "/",
    name: "inicio",
    component: () =>
        import ("./views/Inicio.vue"),
    //import ("./views/ui-elements/card/CardAnalytics.vue"),
    meta: {
        breadcrumb: [{
                title: "Home",
                url: "/"
            },
            {
                title: "Administrar"
            },
            {
                title: "Empresa",
                active: true
            }
        ],
        pageTitle: "Empresa",
        rule: "editor",
        secure: true
    }
});

const router = new Router({
    mode: "history",
    base: "/",
    scrollBehavior() {
        return {
            x: 0,
            y: 0
        };
    },
    routes: [{
            path: "",
            component: () =>
                import ("./layouts/main/Main.vue"),
            children: cont
        },
        {
            path: "",
            component: () =>
                import ("@/layouts/full-page/FullPage.vue"),
            children: [{
                    path: "/login",
                    name: "page-login",
                    component: () =>
                        import ("@/views/Login.vue"),
                    meta: {
                        rule: "editor",
                        guest: true
                    }
                },
                {
                    path: "/registro",
                    name: "registro",
                    component: () =>
                        import ("@/views/Registro.vue"),
                    meta: {
                        rule: "editor",
                        guest: true
                    }
                },
                {
                    path: "/error404",
                    name: "error404",
                    component: () =>
                        import ("@/views/errores/Error404.vue"),
                    meta: {
                        rule: "editor",
                        guest: true
                    }
                }
            ]
        }
    ]
});
router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.secure)) {
        if (!store.state.Token) {
            next({
                path: "/login"
            });
        } else {
            next();
        }
    } else if (to.matched.some(record => record.meta.guest)) {
        if (!store.state.Token) {
            next();
        } else {
            if (store.state.AppActiveUser.id_rol != 3) {
                next({
                    path: "/"
                });
            } else {
                next({
                    path: "/administrar/empresa"
                });
            }
        }
    } else {
        next();
    }
});
export default router;