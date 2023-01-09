<!-- =========================================================================================
    File Name: App.vue
    Description: Main vue file - APP
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
    Author: Pixinvent
    Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->
<!--Repositorio de Andrés-->

<template>
    <div id="app">
        <router-view></router-view>
    </div>
</template>

<script>
import themeConfig from "@/../themeConfig.js";
import axios from "axios";
import moment from "moment";
moment.locale("es");
import LoginJWTVue from "./views/app/LoginJWT.vue";

const $ = require("jquery");

export default {
    watch: {
        "$store.state.theme"(val) {
            this.toggleClassInBody(val);
        }
    },
    computed: {
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        }
    },
    methods: {
        toggleClassInBody(className) {
            if (className == "dark") {
                if (document.body.className.match("theme-semi-dark"))
                    document.body.classList.remove("theme-semi-dark");
                document.body.classList.add("theme-dark");
            } else if (className == "semi-dark") {
                if (document.body.className.match("theme-dark"))
                    document.body.classList.remove("theme-dark");
                document.body.classList.add("theme-semi-dark");
            } else {
                if (document.body.className.match("theme-dark"))
                    document.body.classList.remove("theme-dark");
                if (document.body.className.match("theme-semi-dark"))
                    document.body.classList.remove("theme-semi-dark");
            }
        },
        handleWindowResize() {
            this.$store.commit("UPDATE_WINDOW_WIDTH", window.innerWidth);
        },
        handleScroll() {
            this.$store.commit("UPDATE_WINDOW_SCROLL_Y", window.scrollY);
        },
        //el dato de la fecha de expiracion de la firma es recuperada y se verifica si esta en el rango de un mes o menos para que liste un error como alerta
        //se usa libreria de toast error dentro del node
        async verificar_firma() {
            let { data } = await axios.get(
                "/api/fecha-expiracion-firma-electronica/" +
                    this.usuario.id_empresa
            );

            let usu = this.usuario.id;
            let fecha_expiracion = data.fecha_expiracion_firma;
            let fecha_expiracion_formato = moment(
                data.fecha_expiracion_firma
            ).format("LL");
            let fecha_expiracion_mes = moment(fecha_expiracion)
                .add(30, "days")
                .format("YYYY/MM/DD");
            let fecha_actual = moment().format("YYYY/MM/DD");
            let fecha_recuperada = localStorage.getItem("fecha_actual");
           
            /*if (fecha_actual != fecha_recuperada) {
                //verifica si la fecha es un mes siguiente o menor a la fecha actual para mostrar el error
                if (fecha_expiracion_mes <= fecha_actual) {
                    this.$toast.error(
                        "La firma electrónica esta pronto a caducarse.\n fecha de expiracion " +
                            fecha_expiracion_formato,
                        {
                            position: "top-center",
                            timeout: false,
                            closeOnClick: false,
                            pauseOnFocusLoss: true,
                            pauseOnHover: true,
                            draggable: true,
                            draggablePercent: 0.6,
                            showCloseButtonOnHover: false,
                            hideProgressBar: true,
                            closeButton: "button",
                            closeButtonClassName: "cerrarboton",
                            icon: true,
                            rtl: false
                        }
                    );
                    //localStorage.setItem("fecha_actual", fecha_actual);
                }
            }*/

        },
        // sesion() {
        //     setInterval(function() {
        //         var direct = window.location.pathname;
        //         if (
        //             direct !== "/login" &&
        //             direct !== "/" &&
        //             window.location.pathname !== "/administrar/empresa"
        //         ) {
        //             window.onbeforeunload = function() {
        //                 localStorage.clear();
        //                 return "";
        //             };
        //         }
        //     }, 5000);
        // },
        redireccion() {
            if (
                localStorage.length == 0 &&
                (window.location.pathname !== "/login" ||
                    window.location.pathname !== "/")
            ) {
                this.$router.push("/login").catch(() => {});
            }
        },
        sesion() {
            let usu = this.usuario.id;
            setInterval(() => {
                var direct = window.location.pathname;
                if (direct !== "/login") {
                    axios.get(`/api/versesionuser/${usu}`).then(res => {
                        if (this.usuario.root != true) {
                            if (res.data[0].estado_empresa != 1) {
                                this.cerrar();
                            } else {
                                if (res.data[0].estado != 1) {
                                    this.cerrar();
                                }
                            }
                        }
                    });
                }
            }, 120000);
        },
        cerrar() {
            this.$store
                .dispatch("logoutAction")
                .then(res => {
                    this.$router.push("/login").catch(() => {});
                })
                .catch(err => {
                    this.$router.push("/login").catch(() => {});
                });
            localStorage.clear();
            this.$toast.error(
                "No se ha podido iniciar sesión, intentelo de nuevo, si el problema persiste comuníquese con un administrador",
                {
                    position: "top-center",
                    timeout: false,
                    closeOnClick: false,
                    pauseOnFocusLoss: true,
                    pauseOnHover: true,
                    draggable: true,
                    draggablePercent: 0.6,
                    showCloseButtonOnHover: false,
                    hideProgressBar: true,
                    closeButton: "button",
                    closeButtonClassName: "cerrarboton",
                    icon: true,
                    rtl: false
                }
            );
        },
        get_user() {
            var user = localStorage.getItem("userInfo");
            axios.post("/get_sesion_user", { user: JSON.parse(user) });
            setInterval(() => {
                var user = localStorage.getItem("userInfo");
                axios.post("/get_sesion_user", { user: JSON.parse(user) });
            }, 60000);
        }
    },
    mounted() {
        this.toggleClassInBody(themeConfig.theme);
        this.$store.commit("UPDATE_WINDOW_WIDTH", window.innerWidth);
        this.verificar_firma();
        this.redireccion();
        this.sesion();
        this.get_user();
    },
    async created() {
        window.addEventListener("resize", this.handleWindowResize);
        window.addEventListener("scroll", this.handleScroll);
    },
    destroyed() {
        window.removeEventListener("resize", this.handleWindowResize);
        window.removeEventListener("scroll", this.handleScroll);
    }
};
</script>
<style>
.pointer {
    cursor: pointer;
}
.Vue-Toastification__container {
    z-index: 99999999999999 !important;
}
</style>
