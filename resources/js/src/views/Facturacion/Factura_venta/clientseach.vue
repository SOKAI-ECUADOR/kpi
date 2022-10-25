<template>
    <div class="vx-row">
        <div class="vx-col sm:w-full w-full mb-6 relative">
            <div v-if="cliente.selected" class="vs__selected-options">
                <span
                    v-for="(tr, index) in cliente.selected"
                    :key="index"
                    class="vs__selected selected_span"
                >
                    {{ tr.nombre }}
                    <button
                        type="button"
                        title="Deselect Romboid"
                        class="vs__deselect selected_span"
                        style="margin-bottom: auto;"
                        @click="borrar_client(index)"
                    >
                        <feather-icon
                            icon="XIcon"
                            svgClasses="w-5 h-5"
                        ></feather-icon>
                    </button>
                </span>
                <vs-input
                    class="w-full busqueda_cliente focuspr"
                    placeholder="Agregar clientes"
                    v-model="cliente.busqueda"
                    @keyup="listar_client(cliente.busqueda)"
                />
            </div>
            <div
                class="busqueda_lista busqueda_cliente_ls"
                style="display: none;"
            >
                <div v-if="preloader.cliente">
                    <ul class="ul_busqueda_lista">
                        <li
                            v-for="(tr, index) in cliente.data"
                            :key="index"
                            @click="seleccionar_cliente(tr)"
                        >
                            <span style="font-weight: bold;">{{
                                tr.nombre
                            }}</span>
                        </li>
                    </ul>
                </div>
                <div v-else>
                    <ul class="ul_busqueda_lista lista_preloader">
                        <div class="preloader"></div>
                    </ul>
                </div>
            </div>
        </div>
    </div>
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

moment.locale("es");
const axios = require("axios");
export default {
    components: {
        AgGridVue,
        flatPickr,
        VxCard,
        vSelect
    },
    data() {
        return {
            cliente: {
                busqueda: "",
                data: [],
                selected: []
            },
            preloader: {
                cliente: false
            },
            values: this.value
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
    props: ["producto"],
    methods: {
        //----------------------------------------- FUNCIONES DE FACTURACION -------------------------------------//
        listar_client(buscar) {
            this.preloader.cliente = false;
            $(".busqueda_cliente_ls").show();
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.timeout = setTimeout(() => {
                axios
                    .get(
                        `/api/select_client?empresa=${this.usuario.id_empresa}&buscar=${buscar}`
                    )
                    .then(resp => {
                        this.preloader.cliente = true;
                        if (resp.data.length >= 1) {
                            this.cliente.data = resp.data;
                            if (this.cliente.selected.length <= 1) {
                                this.cliente.data.unshift({
                                    id_cliente: 0,
                                    nombre: "Agregar Todos"
                                });
                            }
                            return;
                        } else {
                            this.cliente.data = [];
                            return;
                        }
                    })
                    .catch(error => {
                        console.log(error);
                        this.preloader.cliente = false;
                    });
            }, 800);
        },
        seleccionar_cliente(tr) {
            if (tr.id_cliente !== 0) {
                this.cliente.selected.push(tr);
            } else {
                this.cliente.data.shift();
                this.cliente.selected = this.cliente.data;
            }
            this.cliente.busqueda = "";
            this.agregar_productos_cliente();
            this.$emit("input", this.cliente.selected);
            this.preloader.cliente = false;
        },
        agregar_productos_cliente() {
            this.cliente.selected.forEach(el => {
                el.producto = [];
                el.producto.push(this.producto);
            });
        },
        borrar_client(index) {
            this.cliente.selected.splice(index, 1);
            this.$emit("input", this.cliente.selected);
        }
    },
    mounted() {
        $(document).on("click", function(e) {
            var container = $(".busqueda_lista");
            if (
                !container.is(e.target) &&
                container.has(e.target).length === 0
            ) {
                $(".busqueda_lista").hide();
            }
        });
        $(".focuspr").focus();
    }
};
</script>
<style lang="scss">
.modalfact .vs-popup {
    width: 1385px !important;
}

@import "@sass/vuexy/extraComponents/agGridStyleOverride.scss";
.sindis .vs-input--input:focus {
    border-top: none !important;
    border-left: none !important;
    border-right: none !important;
}
.sindis .vs-input--input {
    border-top: none !important;
    border-left: none !important;
    border-right: none !important;
}
.nover > .icon-select {
    display: none;
}
.hovertrash:hover > .trasher {
    display: block !important;
}
.botonstl {
    height: 100%;
    width: 38px;
    border: 1px solid #635ace;
    background: transparent;
    color: #635ace;
    font-size: 16px;
    cursor: pointer;
}
.elejido {
    background: #635ace !important;
    color: #fff !important;
}
//Busqueda de comprobantes
.busqueda_cliente input {
    height: 50px;
    padding-left: 45px !important;
}
.busqueda_cliente_icono {
    position: absolute !important;
    top: 11px;
    left: 25px;
}
.busqueda_lista {
    position: absolute;
    width: 97%;
    z-index: 9;
}
.ul_busqueda_lista {
    min-width: 160px;
    margin: -2px 0 0;
    list-style: none;
    font-size: 13.5px;
    text-align: left;
    background-color: #fff;
    border: 1px solid #ccc;
    border: 1px solid rgba(0, 0, 0, 0.15);
    border-radius: 2px;
    -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    background-clip: padding-box;
}
.ul_busqueda_lista li {
    padding: 10px 16px;
    text-overflow: ellipsis;
    overflow: hidden;
    font-weight: 300;
    display: block;
    clear: both;
    line-height: 1.3;
    color: #333;
    white-space: nowrap;
    font-family: sans-serif;
}
.ul_busqueda_lista li:hover {
    background: rgba(16, 22, 58, 0.38);
    cursor: pointer;
    color: #fff;
}
.busqueda_cliente .input-span-placeholder {
    padding-left: 50px;
    margin-top: 3px;
}
.buscar_otro {
    position: absolute;
    margin-top: -35px;
    margin-left: 14px;
    cursor: pointer;
}
.eliminar_producto_icono {
    display: table-cell !important;
    height: 100%;
    vertical-align: middle;
    display: none;
}
.eliminar_producto_icono svg {
    margin-top: 8px;
}
.fila_lista:hover .eliminar_producto_icono {
    display: block;
}
.cabezera_total span {
    float: right;
    margin-right: 25px;
}
.cabezera_total > div {
    margin-left: 20px;
    padding: 9px 3px;
}
.cabezera_total {
    margin-top: 15px;
}
.vs-input--placeholder {
    top: 0px;
}
.modal-xl .vs-popup {
    width: 1250px;
}
.tablavista td {
    padding: 10px 15px;
}
.tablavista:hover {
    cursor: pointer;
    background: rgba(0, 0, 0, 0.2);
}

.vs-popup {
    width: 1060px !important;
}
.peque .vs-popup {
    width: 600px !important;
}
.sindis .vs-input--input:focus {
    border-top: none !important;
    border-left: none !important;
    border-right: none !important;
}
.sindis .vs-input--input {
    border-top: none !important;
    border-left: none !important;
    border-right: none !important;
}
.nover > .icon-select {
    display: none;
}
.hovertrash:hover > .trasher {
    display: block !important;
}
.botonstl {
    height: 100%;
    width: 38px;
    border: 1px solid #635ace;
    background: transparent;
    color: #635ace;
    font-size: 16px;
    cursor: pointer;
}
.elejido {
    background: #635ace !important;
    color: #fff !important;
}
.flexy > .vs-divider--text {
    display: flex;
}
.slide-fade-enter-active {
    transition: all 0.5s ease;
}
.slide-fade-leave-active {
    transition: all 0.5s cubic-bezier(1, 0.5, 0.8, 1);
}
.slide-fade-enter, .slide-fade-leave-to
    /* .slide-fade-leave-active for <2.1.8 */ {
    transform: translateX(10px);
    opacity: 0;
}
.btnmoremore {
    position: absolute;
    z-index: 9;
    right: 18px;
    margin-top: -45px;
    font-size: 31px;
    background: #fff;
    cursor: pointer;
}

.preloader {
    width: 50px;
    height: 50px;
    border: 10px solid #eee;
    border-top: 10px solid #666;
    border-radius: 50%;
    animation-name: girar;
    animation-duration: 2s;
    animation-iteration-count: infinite;
    animation-timing-function: linear;
}
@keyframes girar {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
.lista_preloader {
    padding: 8px;
    display: flex;
    justify-content: center;
    align-items: center;
}
.derecha input {
    text-align: end;
}
.derecha .vs-input--placeholder {
    text-align: end;
}
.nombrearreglo span {
    font-size: 11px;
    letter-spacing: -0.5px;
    line-height: 15px;
    display: block;
}
.font-small input {
    font-size: 11px;
}
.font-small span {
    font-size: 11px;
}
.cambios_span {
    height: 100%;
    width: 100%;
    align-items: center;
    background: #000e6f;
    color: #fff;
    border-radius: 53px;
    padding: 0 40px 0 15px;
    font-size: 12px;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
}
.selected_span {
    background-color: #f28b2c;
    color: white;
}
</style>
