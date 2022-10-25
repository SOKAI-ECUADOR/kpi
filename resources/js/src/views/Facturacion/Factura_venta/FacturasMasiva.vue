<template>
    <vx-card>
        <div class="vx-row">
            <div class="vx-col md:w-full w-full mb-6" id="ag-grid-demo">
                <div class="flex flex-wrap justify-between items-center mb-3">
                    <div
                        class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"
                    ></div>
                    <div
                        class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"
                    >
                        <vs-input
                            class="mb-4 md:mb-0 mr-4"
                            v-model="buscarfact_masiv"
                            @keyup="listfact_masiva(buscarfact_masiv)"
                            v-bind:placeholder="i18nbuscar"
                        />
                        <div>
                            <div class="dropdown-button-container">
                                <vs-button
                                    class="btnx"
                                    type="filled"
                                    @click="popupfact('Guardar', null)"
                                    >Agregar Nueva</vs-button
                                >
                                <vs-dropdown>
                                    <vs-button
                                        class="btn-drop"
                                        type="filled"
                                        icon="expand_more"
                                    ></vs-button>
                                    <vs-dropdown-menu style="width: 13em;">
                                        <vs-dropdown-item class="text-center"
                                            >Importar
                                            registros</vs-dropdown-item
                                        >
                                    </vs-dropdown-menu>
                                </vs-dropdown>
                            </div>
                        </div>
                    </div>
                </div>
                <vs-table stripe :data="listfact_masiva">
                    <template slot="thead">
                        <vs-th>Cod</vs-th>
                        <vs-th>Nombre</vs-th>
                        <vs-th>Grupo Cliente</vs-th>
                        <vs-th>Opciones</vs-th>
                    </template>
                    <template slot-scope="{ data }">
                        <vs-tr :key="datos.codigo" v-for="datos in data">
                            <vs-td v-if="datos.codigo">{{
                                datos.codigo
                            }}</vs-td>
                            <vs-td v-else>-</vs-td>
                            <vs-td v-if="datos.nombre">{{
                                datos.nombre
                            }}</vs-td>
                            <vs-td v-else>-</vs-td>
                            <vs-td v-if="datos.grupo_cliente">{{
                                datos.grupo_cliente
                            }}</vs-td>
                            <vs-td v-else>-</vs-td>
                            <vs-td
                                class="whitespace-no-wrap text-center"
                                style="width: 5%;"
                            >
                                <feather-icon
                                    icon="SendIcon"
                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                    class="cursor-pointer"
                                />
                                <feather-icon
                                    icon="EditIcon"
                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                    class="cursor-pointer"
                                    @click="popupfact('Editar', datos)"
                                />
                                <feather-icon
                                    icon="TrashIcon"
                                    svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                    class="ml-2 cursor-pointer"
                                />
                            </vs-td>
                        </vs-tr>
                    </template>
                </vs-table>
            </div>
        </div>
        <!-- POPUP PARA AÑADIR Y EDITAR FACTURA MASIVA -->
        <vs-popup
            :title="titlepopupfact"
            :active.sync="popupfactura"
            class="modalfact"
            ><vx-card>
                <vs-divider position="center">
                    <h3>Factura Masiva</h3>
                </vs-divider>
                <div class="vx-row leading-loose p-base">
                    <vs-divider position="left">
                        <h3>Grupo de Clientes</h3>
                    </vs-divider>
                    <div class="vx-col sm:w-full w-full mb-6">
                        <clientseach
                            v-model="facturam.clientes"
                            :producto="facturam.producto"
                        />
                        {{ facturam.clientes }}
                    </div>
                </div>
                <vs-divider position="left">
                    <h3>Productos</h3>
                </vs-divider>
                <div class="vx-col sm:w-full w-full mb-6">
                    <div
                        v-if="facturam.producto.lista_productos.length >= 1"
                        class="vx-col sm:w-full w-full mb-2"
                        style="margin-top: 20px; margin-bottom: 0.2rem !important;"
                    >
                        <vs-checkbox
                            icon-pack="feather"
                            icon="icon-check"
                            v-model="product_unico"
                        >
                            <template v-if="product_unico">
                                <label
                                    class="vs-input--label"
                                    style="font-size: 14px;font-weight: bold;"
                                    >Si</label
                                >
                            </template>
                            <template v-else>
                                <label
                                    class="vs-input--label"
                                    style="font-size: 14px;font-weight: bold;"
                                    >No</label
                                >
                            </template>
                            | Ajustar precios diferentes por cliente
                        </vs-checkbox>
                    </div>
                    <div
                        class="vx-col sm:w-full w-full mb-6"
                        v-show="product_unico == false"
                    >
                        <productseach
                            v-model="facturam.producto"
                            :clientes="facturam.clientes"
                        />
                    </div>
                    <div
                        class="vx-col sm:w-full w-full mb-6"
                        v-show="product_unico == true"
                    >
                        <vs-list
                            v-for="(tr, index) in facturam.clientes"
                            :key="index"
                        >
                            <vs-list-item :title="tr.nombre">
                                <vx-tooltip
                                    text="Editar Valores"
                                    position="top"
                                    style="display: inline-flex;"
                                >
                                    <feather-icon
                                        icon="EditIcon"
                                        class="cursor-pointer"
                                        svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                        @click="
                                            modalfactdetalles(tr)
                                        "/></vx-tooltip
                            ></vs-list-item>
                        </vs-list>
                        <vs-popup
                            :title="titlepopupfactdetalle"
                            :active.sync="popupfactdetalle"
                            class="peque"
                            ><vx-card
                                >{{ popupdata }}
                                <productonlyseach
                                    :clientes="popupdata"/></vx-card
                        ></vs-popup>
                    </div>
                    {{ facturam.producto }}
                </div>
            </vx-card></vs-popup
        >
    </vx-card>
</template>
<script>
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import vSelect from "vue-select";
import { AgGridVue } from "ag-grid-vue";
import moment from "moment";
import $ from "jquery";
import VxCard from "../../../components/vx-card/VxCard.vue";
import clientseach from "./clientseach.vue";
import productseach from "./productseach.vue";
import productonlyseach from "./productonlyseach.vue";
moment.locale("es");
const axios = require("axios");
export default {
    components: {
        AgGridVue,
        flatPickr,
        VxCard,
        vSelect,
        clientseach,
        productseach,
        productonlyseach
    },
    data() {
        return {
            i18nbuscar: this.$t("i18nbuscar"),
            buscarfact_masiv: "",
            listfact_masiva: [],
            titlepopupfact: "",
            popupfactura: false,
            timeout: null,
            titlepopupfactdetalle: "",
            popupfactdetalle: false,
            popupdata: [],
            //----------------------------------------- FUNCIONES DE FACTURACION -------------------------------------//
            product_unico: false,
            facturam: {
                clientes: { producto: {} },
                producto: { lista_productos: [] }
            },
            error: {
                error: 0,
                facturam: {
                    cliente: []
                },
                producto: {
                    busqueda: []
                }
            }
            //----------------------------------------- FUNCIONES DE FACTURACION -------------------------------------//
        };
    },
    computed: {
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        }
    },
    props: {},
    methods: {
        listmodalfact_masiva(buscarfact_masiv) {
            axios
                .get(
                    `/api/factura_masiva/list/${this.usuario.id_empresa}?buscar=${buscarfact_masiv}`
                )
                .then(resp => {
                    this.listfact_masiva = resp.data;
                })
                .catch(err => {
                    console.log("Error: " + err);
                });
        },
        popupfact(action, data) {
            switch (action) {
                case "Guardar": {
                    this.popupfactura = true;
                    this.titlepopupfact = "Guardar Factura Masiva";
                    break;
                }

                case "Editar": {
                    this.popupfactura = true;
                    this.titlepopupfact = "Editar Factura Masiva";
                    break;
                }
            }
        },
        modalfactdetalles(tr) {
            this.titlepopupfactdetalle =
                "Edicion Detalle Cliente: " + tr.nombre;
            this.popupfactdetalle = true;
            this.popupdata = tr;
        }
    },
    mounted() {
        this.listmodalfact_masiva(this.buscarfact_masiv);
    }
};
</script>
<style lang="scss">
.modalfact .vs-popup {
    width: 1385px !important;
}
.modal-xl .vs-popup {
    width: 1250px;
}
.vs-popup {
    width: 1060px !important;
}
.peque .vs-popup {
    width: 600px !important;
}
.vs-list {
    border-block-end: #f28b2c 2px solid;
}
</style>
