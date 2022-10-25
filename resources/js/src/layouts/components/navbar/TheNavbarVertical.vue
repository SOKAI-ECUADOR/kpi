<!-- =========================================================================================
  File Name: TheNavbar.vue
  Description: Navbar component
  Component Name: TheNavbar
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->

<template>
    <div class="relative">
        <div class="vx-navbar-wrapper" :class="classObj">
            <vs-navbar
                class="vx-navbar navbar-custom navbar-skelton"
                :color="navbarColorLocal"
            >
                <!-- SM - OPEN SIDEBAR BUTTON -->
                <feather-icon
                    class="sm:inline-flex xl:hidden cursor-pointer mr-1"
                    icon="MenuIcon"
                    @click.stop="showSidebar"
                ></feather-icon>
                <h2 v-if="!estadoper"></h2>
                <h2
                    class="sm:inline-flex xl:hidden cursor-pointer mr-1 mt-5"
                    style="color: rgb(99, 99, 99);position: absolute;left: 20%;top: -6px;width: 7em;text-align: center;"
                    v-else
                >
                    <vs-select
                        placeholder="Seleccione Empresa"
                        class="selectExample w-full"
                        vs-multiple
                        autocomplete
                        v-model="usuarioroot.datos"
                        @change="seleccionusuario()"
                    >
                        <vs-select-item
                            v-for="(data, index) in usuarioroot.empresas"
                            :key="index"
                            :value="data.id"
                            :text="data.nombre_empresa"
                        />
                    </vs-select>
                </h2>
                <template v-if="windowWidth >= 992">
                    <div
                        class="vx-navbar__starred-pages--more-dropdown"
                        v-if="starredPagesMore.length"
                    >
                        <vs-dropdown vs-custom-content vs-trigger-click>
                            <feather-icon
                                icon="ChevronDownIcon"
                                svgClasses="h-4 w-4"
                                class="cursor-pointer p-2"
                            ></feather-icon>
                            <vs-dropdown-menu>
                                <ul class="vx-navbar__starred-pages-more--list">
                                    <draggable
                                        v-model="starredPagesMore"
                                        :group="{ name: 'pinList' }"
                                        class="cursor-move"
                                    >
                                        <li
                                            class="starred-page--more flex items-center cursor-pointer"
                                            v-for="page in starredPagesMore"
                                            :key="page.url"
                                            @click="
                                                $router
                                                    .push(page.url)
                                                    .catch(() => {})
                                            "
                                        >
                                            <feather-icon
                                                svgClasses="h-5 w-5"
                                                class="ml-2 mr-1"
                                                :icon="page.labelIcon"
                                            ></feather-icon>
                                            <span class="px-2 pt-2 pb-1">{{
                                                page.label
                                            }}</span>
                                        </li>
                                    </draggable>
                                </ul>
                            </vs-dropdown-menu>
                        </vs-dropdown>
                    </div>
                    <div class="bookmark-container">
                        <h2
                            style="color: #636363; width: 80%;"
                            v-if="!estadoper"
                        >
                            {{ nombreEmpresa }}
                        </h2>
                        <h2
                            style="color: rgb(99, 99, 99);position: absolute;left: 30%;top: -6px;width: 13em;text-align: center;"
                            v-else
                        >
                            <vs-select
                                placeholder="Seleccione Empresa"
                                label="Selecciona la empresa"
                                class="selectExample w-full"
                                vs-multiple
                                autocomplete
                                v-model="usuarioroot.datos"
                                @change="seleccionusuario()"
                            >
                                <vs-select-item
                                    v-for="(data,
                                    index) in usuarioroot.empresas"
                                    :key="index"
                                    :value="data.id"
                                    :text="data.nombre_empresa"
                                />
                            </vs-select>
                        </h2>
                        <feather-icon
                            icon="SearchIcon"
                            @click="showFullSearch = true"
                            class="cursor-pointer navbar-fuzzy-search ml-4 mr-4"
                        ></feather-icon>
                        <div>
                            <pre style="font-family: initial;">
 {{ currentDate }}, {{ displayCurrentTime }}</pre
                            >
                        </div>
                    </div>
                </template>
                <vs-spacer />
                <!-- SEARCHBAR -->
                <div
                    class="search-full-container w-full h-full absolute left-0"
                    :class="{ flex: showFullSearch }"
                    v-show="showFullSearch"
                >
                    <vx-auto-suggest
                        class="w-full"
                        inputClassses="w-full vs-input-no-border vs-input-no-shdow-focus"
                        :autoFocus="showFullSearch"
                        :data="navbarSearchAndPinList"
                        icon="SearchIcon"
                        placeholder="Search..."
                        ref="navbarSearch"
                        @closeSearchbar="showFullSearch = false"
                        @selected="selected"
                        background-overlay
                    />
                    <div class="absolute right-0 h-full z-50">
                        <feather-icon
                            icon="XIcon"
                            class="px-4 cursor-pointer h-full close-search-icon"
                            @click="showFullSearch = false"
                        ></feather-icon>
                    </div>
                </div>
                <!-- USER META -->
                <div class="the-navbar__user-meta flex items-center">
                    <div class="text-right leading-tight hidden sm:block">
                        <p class="font-semibold">
                            {{ usuario.nombres + " " + usuario.apellidos }}
                        </p>
                        <small>{{ $t("Disponible") }}</small>
                    </div>
                    <vs-dropdown
                        vs-custom-content
                        vs-trigger-click
                        class="cursor-pointer"
                    >
                        <div class="con-img ml-3">
                            <img
                                v-if="usuario.foto"
                                key="onlineImg"
                                :src="usuario.foto"
                                alt="user-img"
                                width="40"
                                height="40"
                                class="rounded-full shadow-md cursor-pointer block"
                            />
                            <img
                                v-else
                                key="onlineImg"
                                src="/images/null.png"
                                alt="user-img"
                                width="40"
                                height="40"
                                class="rounded-full shadow-md cursor-pointer block"
                            />
                        </div>
                        <vs-dropdown-menu class="vx-navbar-dropdown">
                            <ul style="min-width: 9rem">
                                <li
                                    class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white"
                                    @click="
                                        $router
                                            .push('/menu/perfil')
                                            .catch(() => {})
                                    "
                                >
                                    <feather-icon
                                        icon="UserIcon"
                                        svgClasses="w-4 h-4"
                                    />
                                    <span class="ml-2">{{ $t("Perfil") }}</span>
                                </li>
                                <li
                                    class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white"
                                    @click="
                                        $router
                                            .push('/menu/configuracion')
                                            .catch(() => {})
                                    "
                                >
                                    <feather-icon
                                        icon="MailIcon"
                                        svgClasses="w-4 h-4"
                                    />
                                    <span class="ml-2">{{
                                        $t("Configuracion")
                                    }}</span>
                                </li>
                                <vs-divider class="m-1"></vs-divider>
                                <li
                                    class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white"
                                    @click="logout"
                                >
                                    <feather-icon
                                        icon="LogOutIcon"
                                        svgClasses="w-4 h-4"
                                    />
                                    <span class="ml-2">{{ $t("Salir") }}</span>
                                </li>
                            </ul>
                        </vs-dropdown-menu>
                    </vs-dropdown>
                </div>
            </vs-navbar>
        </div>
    </div>
</template>

<script>
import firebase from "firebase/app";
import "firebase/auth";
import VxAutoSuggest from "@/components/vx-auto-suggest/VxAutoSuggest.vue";
import VuePerfectScrollbar from "vue-perfect-scrollbar";
import draggable from "vuedraggable";
import axios from "axios";
import moment from "moment";

export default {
    name: "the-navbar",
    props: {
        navbarColor: {
            type: String,
            default: "#fff"
        }
    },
    data() {
        return {
            navbarSearchAndPinList: this.$store.state.navbarSearchAndPinList,
            searchQuery: "",
            showFullSearch: false,
            unreadNotifications: [
                {
                    index: 0,
                    title: "New Message",
                    msg: "Are your going to meet me tonight?",
                    icon: "MessageSquareIcon",
                    time: this.randomDate({ sec: 10 }),
                    category: "primary"
                },
                {
                    index: 1,
                    title: "New Order Recieved",
                    msg: "You got new order of goods.",
                    icon: "PackageIcon",
                    time: this.randomDate({ sec: 40 }),
                    category: "success"
                },
                {
                    index: 2,
                    title: "Server Limit Reached!",
                    msg: "Server have 99% CPU usage.",
                    icon: "AlertOctagonIcon",
                    time: this.randomDate({ min: 1 }),
                    category: "danger"
                },
                {
                    index: 3,
                    title: "New Mail From Peter",
                    msg: "Cake sesame snaps cupcake",
                    icon: "MailIcon",
                    time: this.randomDate({ min: 6 }),
                    category: "primary"
                },
                {
                    index: 4,
                    title: "Bruce's Party",
                    msg: "Chocolate cake oat cake tiramisu",
                    icon: "CalendarIcon",
                    time: this.randomDate({ hr: 2 }),
                    category: "warning"
                }
            ],
            settings: {
                // perfectscrollbar settings
                maxScrollbarLength: 60,
                wheelSpeed: 0.6
            },
            autoFocusSearch: false,
            showBookmarkPagesDropdown: false,
            nombreEmpresa: "",
            displayCurrentTime: "",
            currentDate: null,
            estadoper: false,
            usuarioroot: {
                datos: null,
                empresas: []
            }
        };
    },
    watch: {
        $route() {
            if (this.showBookmarkPagesDropdown)
                this.showBookmarkPagesDropdown = false;
        }
    },
    computed: {
        navbarColorLocal() {
            return this.$store.state.theme === "dark"
                ? "#10163a"
                : this.navbarColor;
        },
        // HELPER
        verticalNavMenuWidth() {
            return this.$store.state.verticalNavMenuWidth;
        },
        windowWidth() {
            return this.$store.state.windowWidth;
        },

        // NAVBAR STYLE
        classObj() {
            if (this.verticalNavMenuWidth == "default") return "navbar-default";
            else if (this.verticalNavMenuWidth == "reduced")
                return "navbar-reduced";
            else if (this.verticalNavMenuWidth) return "navbar-full";
        },

        // I18N
        getCurrentLocaleData() {
            const locale = this.$i18n.locale;
            if (locale == "es") return { flag: "es", lang: "Español" };
            else if (locale == "en") return { flag: "us", lang: "English" };
            else if (locale == "pt") return { flag: "br", lang: "Portuguese" };
            else if (locale == "fr") return { flag: "fr", lang: "French" };
            else if (locale == "de") return { flag: "de", lang: "German" };
        },
        i18n_locale_img() {
            const locale = this.$i18n.locale;

            if (locale === "es") return require("@assets/images/flags/es.png");
            else if (locale === "en")
                return require("@assets/images/flags/en.png");
            else if (locale === "de")
                return require("@assets/images/flags/de.png");
            else if (locale === "fr")
                return require("@assets/images/flags/fr.png");
            else if (locale === "pt")
                return require("@assets/images/flags/pt.png");
            else return null;
            // return ""
            // return require("@assets/images/flags/"  + x + ".png")
            // return require(`@assets/images/flags/${this.$i18n.locale}.png`)
        },

        // BOOKMARK & SEARCH
        data() {
            return this.$store.state.navbarSearchAndPinList;
        },
        starredPages() {
            return this.$store.state.starredPages;
        },
        starredPagesLimited: {
            get() {
                return this.starredPages.slice(0, 10);
            },
            set(list) {
                this.$store.dispatch("arrangeStarredPagesLimited", list);
            }
        },
        starredPagesMore: {
            get() {
                return this.starredPages.slice(10);
            },
            set(list) {
                this.$store.dispatch("arrangeStarredPagesMore", list);
            }
        },

        // PROFILE
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        }
    },
    methods: {
        updateLocale(locale) {
            this.$i18n.locale = locale;
        },
        showSidebar() {
            this.$store.commit("TOGGLE_IS_VERTICAL_NAV_MENU_ACTIVE", true);
        },
        selected(item) {
            this.$router.push(item.url).catch(() => {});
            this.showFullSearch = false;
        },
        actionClicked(item) {
            // e.stopPropogation();
            this.$store.dispatch("updateStarredPage", {
                index: item.index,
                val: !item.highlightAction
            });
        },
        showNavbarSearch() {
            this.showFullSearch = true;
        },
        showSearchbar() {
            this.showFullSearch = true;
        },
        elapsedTime(startTime) {
            let x = new Date(startTime);
            let now = new Date();
            var timeDiff = now - x;
            timeDiff /= 1000;

            var seconds = Math.round(timeDiff);
            timeDiff = Math.floor(timeDiff / 60);

            var minutes = Math.round(timeDiff % 60);
            timeDiff = Math.floor(timeDiff / 60);

            var hours = Math.round(timeDiff % 24);
            timeDiff = Math.floor(timeDiff / 24);

            var days = Math.round(timeDiff % 365);
            timeDiff = Math.floor(timeDiff / 365);

            var years = timeDiff;

            if (years > 0) {
                return years + (years > 1 ? " Years " : " Year ") + "ago";
            } else if (days > 0) {
                return days + (days > 1 ? " Days " : " Day ") + "ago";
            } else if (hours > 0) {
                return hours + (hours > 1 ? " Hrs " : " Hour ") + "ago";
            } else if (minutes > 0) {
                return minutes + (minutes > 1 ? " Mins " : " Min ") + "ago";
            } else if (seconds > 0) {
                return seconds + (seconds > 1 ? " sec ago" : "just now");
            }

            return "Just Now";
        },
        logout() {
            this.$store
                .dispatch("logoutAction")
                .then(res => {
                    this.$router.push("/login").catch(() => {});
                    //location.reload();
                })
                .catch(err => {
                    this.$router.push("/login").catch(() => {});
                    //location.reload();
                });
        },
        outside: function() {
            this.showBookmarkPagesDropdown = false;
        },
        randomDate({ hr, min, sec }) {
            let date = new Date();

            if (hr) date.setHours(date.getHours() - hr);
            if (min) date.setMinutes(date.getMinutes() - min);
            if (sec) date.setSeconds(date.getSeconds() - sec);

            return date;
        },
        currentTime() {
            let current = new Date();
            let hours = current.getHours();
            let minutes = current.getMinutes();
            let seconds = current.getSeconds();
            let newCurrentTime =
                minutes.toString().length === 1
                    ? `${hours}:0${minutes}`
                    : `${hours}:${minutes}`;

            this.displayCurrentTime = newCurrentTime;

            setTimeout(this.currentTime, 500);
        },
        listarempresas() {
            axios
                .get("/api/lista/empresas")
                .then(({ data }) => {
                    this.usuarioroot.empresas = data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        seleccionusuario() {
            axios
                .post("/api/lista/listadoroot", { id: this.usuarioroot.datos })
                .then(({ data }) => {
                    let userInfo = data.datos;
                    localStorage.setItem("userInfo", JSON.stringify(userInfo));

                    let userRoles = data.roles;
                    localStorage.setItem("Roles", JSON.stringify(userRoles));

                    let datosselect = this.usuarioroot.datos;
                    localStorage.setItem("Datosselect", datosselect);

                    setTimeout(() => {
                        location.href = "/administrar/empresa";
                    }, 500);
                })
                .catch(error => {
                    console.log(error);
                });
        }
    },
    directives: {
        "click-outside": {
            bind: function(el, binding) {
                const bubble = binding.modifiers.bubble;
                const handler = e => {
                    if (bubble || (!el.contains(e.target) && el !== e.target)) {
                        binding.value(e);
                    }
                };
                el.__vueClickOutside__ = handler;
                document.addEventListener("click", handler);
            },

            unbind: function(el) {
                document.removeEventListener("click", el.__vueClickOutside__);
                el.__vueClickOutside__ = null;
            }
        }
    },
    components: {
        VxAutoSuggest,
        VuePerfectScrollbar,
        draggable
    },
    mounted() {
        this.currentTime();
        this.currentDate = new moment().locale("es");
        this.currentDate = this.currentDate.format("LL");
        let idEmpresa = JSON.parse(localStorage.getItem("userInfo")).id_empresa;
        if (localStorage.getItem("userInfos")) {
            this.estadoper = localStorage.getItem("userInfos");
            this.usuarioroot.datos = localStorage.getItem("Datosselect");
        }
        axios
            .get(`/api/empresa/${idEmpresa}/obtener`)
            .then(
                ({ data: { nombre_empresa } }) =>
                    (this.nombreEmpresa = nombre_empresa)
            );
        this.listarempresas();
    }
};
</script>
<style scoped>
.bookmark-container {
    border-left: 2px solid black;
    padding-left: 10px;
    display: flex;
    justify-content: space-between;
    width: 100%;
    align-items: center;
}
.bookmark-container > div {
    display: flex;
    flex-direction: column;
    padding-right: 1em;
}
.bookmark-container > div > span {
    text-align: end;
}
</style>
