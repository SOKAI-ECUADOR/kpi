<template>
    <div class="vx-row leading-loose p-base">
        <div class="vx-col sm:w-full w-full relative" v-if="producto.tipo">
            <span>{{ formulas.hi }}</span>
            <vs-table
                hoverFlat
                :data="producto.lista_productos"
                style="font-size: 12px;"
            >
                <template slot="thead">
                    <vs-th>CÓDIGO</vs-th>
                    <vs-th>NOMBRE</vs-th>
                    <vs-th>PROYECTO</vs-th>
                    <vs-th>CANTIDAD</vs-th>
                    <vs-th>PRECIO</vs-th>
                    <vs-th>ICE</vs-th>
                    <vs-th>DESCUENTO</vs-th>

                    <vs-th>VALOR</vs-th>
                    <vs-th>SUBTOTAL</vs-th>
                </template>
                <template slot-scope="{ data }">
                    <vs-tr
                        v-for="(tr, index) in data"
                        :key="index"
                        class="fila_lista"
                    >
                        <vs-td>{{ tr.cod_principal }}</vs-td>
                        <vs-td class="nombrearreglo">
                            <vs-input
                                class="w-full derecha font-small"
                                v-model="tr.nombre"
                            />
                        </vs-td>
                        <vs-td>
                            <vs-select
                                class="selectExample w-full"
                                placeholder="Seleccione un proyecto"
                                v-model="tr.proyecto"
                            >
                                <vs-select-item
                                    :key="index"
                                    :value="item.id_proyecto"
                                    :text="item.descripcion"
                                    v-for="(item, index) in proyectos_menu"
                                />
                            </vs-select>
                            <div v-show="error" v-if="!tr.proyecto">
                                <div
                                    v-for="err in tr.errorproyecto"
                                    :key="err"
                                    v-text="err"
                                    class="text-danger"
                                ></div>
                            </div>
                        </vs-td>
                        <vs-td style="width:70px!important;">
                            <vs-input
                                class="w-full derecha"
                                v-model="tr.cantidad"
                            />
                            <div v-show="error" v-if="!tr.cantidad">
                                <div
                                    v-for="err in tr.errorcantidad"
                                    :key="err"
                                    v-text="err"
                                    class="text-danger"
                                ></div>
                            </div>
                        </vs-td>
                        <vs-td
                            style="width:70px!important;"
                            v-if="
                                typeof tr.precio_sin_iva == 'undefined' ||
                                    tr.precio_sin_iva == ''
                            "
                        >
                            <vs-input
                                class="w-full derecha"
                                v-model="tr.precio"
                            />
                            <div v-show="error" v-if="!tr.precio">
                                <div
                                    v-for="err in tr.errorprecio"
                                    :key="err"
                                    v-text="err"
                                    class="text-danger"
                                ></div>
                            </div>
                        </vs-td>
                        <vs-td style="width:70px!important;" v-else>
                            {{ tr.precio | currency }}
                            <div v-show="error" v-if="!tr.precio">
                                <div
                                    v-for="err in tr.errorprecio"
                                    :key="err"
                                    v-text="err"
                                    class="text-danger"
                                ></div>
                            </div>
                        </vs-td>
                        <vs-td style="width:70px!important;"
                            ><span
                                class="vs-inputx vs-input--input normal hasValue"
                                style="font-size: 13.5px;font-family: inherit;"
                                >${{
                                    (tr.total_ice * tr.cantidad).toFixed(2)
                                }}</span
                            ></vs-td
                        >
                        <vs-td style="width:170px!important;">
                            <vx-input-group>
                                <vs-input
                                    class="w-full derecha"
                                    placeholder="$0.00"
                                    v-model="tr.descuento"
                                />
                                <template slot="append">
                                    <div class="append-text btn-addon">
                                        <button
                                            class="botonstl"
                                            :class="{
                                                elejido: tr.p_descuento == 1
                                            }"
                                            @click="tr.p_descuento = 1"
                                        >
                                            $
                                        </button>
                                        <button
                                            class="botonstl"
                                            :class="{
                                                elejido: tr.p_descuento == 0
                                            }"
                                            @click="tr.p_descuento = 0"
                                        >
                                            %
                                        </button>
                                    </div>
                                </template>
                            </vx-input-group>
                        </vs-td>

                        <vs-td style="width:70px!important;">
                            <vs-input
                                class="w-full derecha"
                                v-if="tr.iva !== 2"
                                :disabled="true"
                                v-model="tr.precio_sin_iva"
                                @keyup="cambiarivas(index, tr.precio_sin_iva)"
                            />
                            <vs-input
                                class="w-full derecha"
                                v-else
                                v-model="tr.precio_sin_iva"
                                @keyup="cambiarivas(index, tr.precio_sin_iva)"
                            />
                        </vs-td>
                        <vs-td style="width:70px!important;">
                            <template v-if="tr.p_descuento == 1">
                                $
                                {{
                                    (tr.subtotal = (
                                        tr.cantidad * tr.precio -
                                        tr.descuento
                                    ).toFixed(2))
                                }}
                            </template>
                            <template v-else>
                                $
                                {{
                                    (tr.subtotal = (
                                        tr.cantidad * tr.precio -
                                        (tr.cantidad *
                                            tr.precio *
                                            tr.descuento) /
                                            100
                                    ).toFixed(2))
                                }}
                            </template>
                        </vs-td>
                        <feather-icon
                            icon="TrashIcon"
                            svgClasses="w-6 h-6 hover:text-primary stroke-current cursor-pointer"
                            class="eliminar_producto_icono"
                            @click="eliminar_producto(index)"
                        />
                    </vs-tr>
                </template>
            </vs-table>
        </div>
        <div class="vx-col sm:w-full w-full mb-6 relative">
            <vs-input
                class="w-full busqueda_cliente focuspr"
                placeholder="Agrega productos a esta factura"
                v-model="producto.busqueda"
                @keyup="listar_productos(producto.busqueda)"
            />
            <feather-icon
                icon="SearchIcon"
                svgClasses="w-8 h-8 hover:text-primary stroke-current cursor-pointer"
                class="busqueda_cliente_icono"
            />
            <div
                class="busqueda_lista busqueda_producto_ls"
                style="display: none;"
            >
                <div v-if="preloader.productos">
                    <ul class="ul_busqueda_lista">
                        <li
                            v-for="(tr, index) in producto.productos"
                            :key="index"
                            @click="seleccionar_productos(tr)"
                        >
                            <span style="font-weight: bold;">{{
                                tr.nombre
                            }}</span>
                            <span v-if="tr.nombrebodega">
                                -
                                <span style="font-size: 12px;"
                                    >Código: {{ tr.cod_principal }}</span
                                >-
                                <span style="font-size: 12px;"
                                    >Bodega: {{ tr.nombrebodega }}</span
                                >
                                -
                                <span style="font-size: 12px;"
                                    >cantidad: {{ tr.cantidad }}</span
                                ></span
                            >
                        </li>
                    </ul>
                </div>
                <div v-else>
                    <ul class="ul_busqueda_lista lista_preloader">
                        <div class="preloader"></div>
                    </ul>
                </div>
            </div>
            <div v-show="error" v-if="!producto.busqueda">
                <div
                    v-for="err in error.producto.busqueda"
                    :key="err"
                    v-text="err"
                    class="text-danger"
                ></div>
            </div>
        </div>
        <div class="vx-col w-full">
            <div class="vx-row" v-if="producto.tipo">
                <div class="vx-col sm:w-1/2 w-full">
                    <h6>Observaciones:</h6>
                    <vs-textarea
                        class="w-full"
                        v-model="factura.observacion"
                        rows="5"
                    />
                </div>
                <div class="vx-col sm:w-1/2 w-full">
                    <div class="cabezera_total">
                        <div>
                            SUBTOTAL FINAL
                            <span>$ {{ producto.subtotal }}</span>
                        </div>
                        <!--<div v-if="producto.subtotalice>0">SUBTOTAL ICE <span>$ {{ producto.subtotalice }}</span></div>-->
                        <div v-if="producto.valorice > 0">
                            Valor ICE
                            <span>$ {{ producto.valorice }}</span>
                        </div>
                        <div v-if="producto.subtotal12 > 0">
                            SUBTOTAL IVA 12%
                            <span>$ {{ producto.subtotal12 }}</span>
                        </div>
                        <div v-if="producto.valor12 > 0">
                            Valor IVA 12%
                            <span>$ {{ producto.valor12 }}</span>
                        </div>
                        <div v-if="producto.subtotal0 > 0">
                            SUBTOTAL IVA 0%
                            <span>$ {{ producto.subtotal0 }}</span>
                        </div>
                        <div v-if="producto.no_impuesto > 0">
                            NO OBJETO DE IMPUESTO
                            <span>$ {{ producto.no_impuesto }}</span>
                        </div>
                        <div v-if="producto.exento > 0">
                            EXENTO DE IVA
                            <span>$ {{ producto.exento }}</span>
                        </div>
                        <div>
                            TOTAL DESCUENTO
                            <span>$ {{ producto.descuento }}</span>
                        </div>
                        <div>
                            VALOR TOTAL
                            <span>$ {{ producto.total }}</span>
                        </div>
                    </div>
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
    props: ["clientes"],
    data() {
        return {
            proyectos_menu: [],
            producto: {
                tipo: false,
                busqueda: "",
                productos: [],
                lista_productos: [],
                subtotal: 0,
                subtotalice: 0,
                valorice: 0,
                subtotal12: 0,
                valor12: 0,
                subtotal0: 0,
                valor0: 0,
                no_impuesto: 0,
                exento: 0,
                descuento: 0,
                total: 0,
                propina: 0
            },
            factura: {
                orden_compra: "",
                migo: "",
                fecha_emision: moment().format("YYYY-MM-DD"),
                ambiente: "",
                tipo_emision: "Emision Normal",
                clave_acceso: "Generando Clave de acceso",
                observacion: "",
                proyectos: null,
                forma_pago: "",
                vendedor: null
            },
            preloader: {
                productos: false
            },
            error: {
                error: 0,
                producto: {
                    busqueda: []
                }
            }
        };
    },
    computed: {
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        },
        formulas() {
            var hi = 0;
            this.producto.subtotal = 0;
            this.producto.subtotalice = 0;
            this.producto.valorice = 0;
            this.producto.subtotal12 = 0;
            this.producto.valor12 = 0;
            this.producto.subtotal0 = 0;
            this.producto.valor0 = 0;
            this.producto.no_impuesto = 0;
            this.producto.exento = 0;
            this.producto.descuento = 0;
            this.producto.total = 0;
            this.producto.propina = 0;
            this.producto.lista_productos.forEach(el => {
                if (el.p_descuento == 1) {
                    this.producto.subtotal +=
                        el.precio * el.cantidad - el.descuento;

                    if (el.total_ice) {
                        this.producto.subtotalice +=
                            el.precio * el.cantidad - el.descuento;
                    }
                    if (el.total_ice) {
                        this.producto.valorice += el.total_ice * el.cantidad;
                    }

                    if (el.iva == 2) {
                        this.producto.subtotal12 +=
                            el.precio * el.cantidad -
                            el.descuento +
                            el.total_ice * el.cantidad;
                    }
                    if (el.iva == 1) {
                        this.producto.subtotal0 +=
                            el.precio * el.cantidad -
                            el.descuento +
                            el.total_ice * el.cantidad;
                    }
                    if (el.iva == 3) {
                        this.producto.no_impuesto +=
                            el.precio * el.cantidad -
                            el.descuento +
                            el.total_ice * el.cantidad;
                    }
                    if (el.iva == 4) {
                        this.producto.exento +=
                            el.precio * el.cantidad -
                            el.descuento +
                            el.total_ice * el.cantidad;
                    }
                    if (isNaN(parseFloat(el.descuento))) {
                        this.producto.descuento += 0;
                    } else {
                        this.producto.descuento += parseFloat(el.descuento);
                    }
                } else {
                    this.producto.subtotal +=
                        el.precio * el.cantidad -
                        (el.cantidad * el.precio * el.descuento) / 100;

                    if (el.total_ice) {
                        this.producto.subtotalice +=
                            el.precio * el.cantidad -
                            (el.cantidad * el.precio * el.descuento) / 100;
                    }
                    if (el.total_ice) {
                        this.producto.valorice += el.total_ice * el.cantidad;
                    }

                    if (el.iva == 2) {
                        this.producto.subtotal12 +=
                            el.precio * el.cantidad -
                            (el.cantidad * el.precio * el.descuento) / 100 +
                            el.total_ice * el.cantidad;
                    }
                    if (el.iva == 1) {
                        this.producto.subtotal0 +=
                            el.precio * el.cantidad -
                            (el.cantidad * el.precio * el.descuento) / 100 +
                            el.total_ice * el.cantidad;
                    }
                    if (el.iva == 3) {
                        this.producto.no_impuesto +=
                            el.precio * el.cantidad -
                            (el.cantidad * el.precio * el.descuento) / 100 +
                            el.total_ice * el.cantidad;
                    }
                    if (el.iva == 4) {
                        this.producto.exento +=
                            el.precio * el.cantidad -
                            (el.cantidad * el.precio * el.descuento) / 100 +
                            el.total_ice * el.cantidad;
                    }
                    if (
                        isNaN(
                            (parseFloat(el.precio) *
                                parseFloat(el.cantidad) *
                                parseFloat(el.descuento)) /
                                100
                        )
                    ) {
                        this.producto.descuento += 0;
                    } else {
                        this.producto.descuento +=
                            (parseFloat(el.precio) *
                                parseFloat(el.cantidad) *
                                parseFloat(el.descuento)) /
                            100;
                    }
                }
                this.producto.valor12 = this.producto.subtotal12 * 0.12;
            });
            this.producto.total +=
                this.producto.subtotal +
                this.producto.valor12 +
                this.producto.valorice;

            if (this.pp_descuento == 1) {
                if (parseFloat(this.propinapr) >= 0) {
                    this.producto.propina = parseFloat(this.propinapr);
                }
            } else {
                if (parseFloat(this.propinapr) >= 0) {
                    this.producto.propina =
                        (parseFloat(this.producto.total) *
                            parseFloat(this.propinapr)) /
                        100;
                }
            }
            this.producto.total = this.producto.total - this.producto.propina;

            this.producto.subtotal = this.producto.subtotal.toFixed(2);
            this.producto.subtotalice = this.producto.subtotalice.toFixed(2);
            this.producto.valorice = this.producto.valorice.toFixed(2);
            this.producto.subtotal12 = this.producto.subtotal12.toFixed(2);
            this.producto.valor12 = this.producto.valor12.toFixed(2);
            this.producto.subtotal0 = this.producto.subtotal0.toFixed(2);
            this.producto.valor0 = this.producto.valor0.toFixed(2);
            this.producto.no_impuesto = this.producto.no_impuesto.toFixed(2);
            this.producto.exento = this.producto.exento.toFixed(2);
            this.producto.descuento = this.producto.descuento.toFixed(2);
            this.producto.total = this.producto.total.toFixed(2);
            this.$emit("input", this.producto);
            this.agregar_product_clientes();
            return (hi = 1);
        }
    },
    props: ["clientes"],
    methods: {
        agregar_product_clientes() {
            this.clientes.forEach(el => {
                el.producto = [];
                el.producto.push(this.producto);
            });
            this.$emit("input", this.producto);
        },
        listarProyecto() {
            var url = "/api/listarproyecto/" + this.usuario.id_empresa;
            axios
                .get(url)
                .then(res => {
                    this.proyectos_menu = res.data.recupera;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        listar_productos(buscar) {
            this.preloader.productos = false;
            $(".busqueda_producto_ls").show();
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.timeout = setTimeout(() => {
                axios
                    .post("/api/factura_venta/listar_productos", {
                        buscar: buscar,
                        id_empresa: this.usuario.id_empresa,
                        id_establecimiento: this.usuario.id_establecimiento,
                        id_pto_emision: this.usuario.id_punto_emision
                    })
                    .then(({ data }) => {
                        this.preloader.productos = true;
                        if (data.length >= 1) {
                            if (
                                data[0].codigo_barras == buscar &&
                                data[0].codigo_barras.length >= 1
                            ) {
                                this.seleccionar_productos(data[0]);
                                this.producto.busqueda = "";
                                return;
                            } else {
                                this.producto.productos = data;
                                return;
                            }
                        } else {
                            this.producto.productos = [];
                            return;
                        }
                    })
                    .catch(error => {
                        console.log(error);
                        this.preloader.productos = true;
                    });
            }, 800);
        },
        seleccionar_productos(tr) {
            this.producto.productos = [];
            this.producto.busqueda = "";
            this.producto.tipo = true;
            var subtotal = (tr.precio - tr.descuento).toFixed(2);
            var cantidad = 1;

            if (isNaN(parseInt(tr.existencia_total))) {
                tr.existencia_total = "";
            }
            if (isNaN(parseFloat(tr.precio))) {
                tr.precio = "";
            }
            if (isNaN(parseFloat(tr.descuento))) {
                tr.descuento = "";
            }
            if (
                tr.sector == 1 &&
                (tr.id_producto_bodega === "undefined" ||
                    tr.id_producto_bodega == null)
            ) {
                cantidad = 1;
                tr.cantidad = 1;
                tr.id_producto_bodega = null;
                tr.nombrebodega = null;
            }
            if (tr.cantidad <= 1) {
                cantidad = 0;
            }
            this.producto.lista_productos.push({
                id_producto_bodega: tr.id_producto_bodega,
                nombrebodega: tr.nombrebodega,
                id_producto: tr.id_producto,
                cod_principal: tr.cod_principal,
                nombre: tr.nombre,
                cantidad: cantidad,
                cantidadreal: tr.cantidad,
                precio: tr.precio,
                descuento: tr.descuento,
                p_descuento: 1,
                subtotal: subtotal,
                iva: tr.iva,
                ice: tr.ice,
                sector: tr.sector,
                nombreice: tr.nombreice,
                total_ice: tr.total_ice,
                proyecto: this.proyectos_menu[0].id_proyecto
            });
            $(".focuspr").focus();
            this.$emit("input", this.producto);
        },
        eliminar_producto(id) {
            this.producto.lista_productos.splice(id, 1);
            if (!this.producto.lista_productos.length) {
                this.producto.tipo = false;
            }
            this.$emit("input", this.producto);
        }
    },
    mounted() {
        this.listarProyecto();
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
