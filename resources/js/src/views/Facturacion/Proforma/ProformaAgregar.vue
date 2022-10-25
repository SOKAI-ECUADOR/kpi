<template>
    <div id="invoice-page">
        <vx-card>
            <vs-divider position="left">
                <h3>Proforma</h3>
            </vs-divider>
            <div class="vx-row leading-loose p-base">
                <div class="vx-col sm:w-1/5 w-full mb-2 ml-auto">
                    <h6>Fecha Emisión:</h6>
                    <flat-pickr
                        :config="configdateTimePicker"
                        class="w-full mt-3"
                        v-model="factura.fecha_emision"
                        placeholder="Seleccionar"
                    />
                    <div v-show="error" v-if="!factura.fecha_emision">
                        <div
                            v-for="err in error.factura.fecha_emision"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-2">
                    <h6>Fecha Expiración:</h6>
                    <flat-pickr
                        :config="configdateTimePicker"
                        class="w-full mt-3"
                        v-model="factura.fecha_expiracion"
                        placeholder="Seleccionar Fecha"
                    />
                    <div v-show="error" v-if="!factura.fecha_expiracion">
                        <div
                            v-for="err in error.factura.fecha_expiracion"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-2">
                    <h6>Formas de Pago:</h6>
                    <vs-select
                        autocomplete
                        class="selectExample w-full mt-3"
                        v-model="factura.forma_pago"
                    >
                        <vs-select-item
                            v-for="(tr, index) in formapagos"
                            :key="index"
                            :value="tr.id_forma_pagos"
                            :text="tr.descripcion"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!factura.forma_pago">
                        <div
                            v-for="err in error.factura.forma_pago"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-2 mr-auto">
                    <h6>Vendedor:</h6>
                    <vs-select
                        :disabled="!user_admin"
                        autocomplete
                        class="selectExample w-full mt-3"
                        v-model="factura.vendedor"
                    >
                        <vs-select-item
                            v-for="(tr, index) in vendedor_menu"
                            :key="index"
                            :value="tr.id_vendedor"
                            :text="tr.nombre_vendedor"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!factura.vendedor">
                        <div
                            v-for="err in error.factura.vendedor"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <!--   <div class="vx-col sm:w-1/5 w-full mb-2">
                    <h6 class="mb-1">Proyectos:</h6>
                    <vs-select
                        class="selectExample w-full"
                        placeholder="Seleccione un proyecto"
                        v-model="factura.proyectos"
                    >
                        <vs-select-item
                            :key="index"
                            :value="item.id_proyecto"
                            :text="item.descripcion"
                            v-for="(item, index) in proyectos_menu"
                        />
                    </vs-select>
                </div>-->
            </div>
            <vs-divider position="left">
                <h3>Cliente</h3>
            </vs-divider>
            <div class="vx-row leading-loose p-base">
                <div
                    class="vx-col sm:w-full w-full mb-6 relative"
                    v-if="cliente.tipo"
                >
                    <div class="vx-row" style="display: flex!important;">
                        <a
                            class="flex items-center buscar_otro"
                            @click="cliente.tipo = false"
                        >
                            Agregar otro Cliente
                        </a>
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input
                                class="w-full"
                                label="Nombre:"
                                disabled
                                v-bind:value="cliente.nombre"
                            />
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input
                                class="w-full"
                                label="Teléfono:"
                                disabled
                                v-bind:value="cliente.telefono"
                            />
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input
                                class="w-full"
                                label="Email:"
                                disabled
                                v-bind:value="cliente.email"
                            />
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input
                                class="w-full"
                                label="Tipo de Identificación:"
                                disabled
                                v-bind:value="cliente.tipo_identificacion"
                            />
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input
                                class="w-full"
                                label="Identificación:"
                                disabled
                                v-bind:value="cliente.identificacion"
                            />
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input
                                class="w-full"
                                label="Dirección:"
                                disabled
                                v-bind:value="cliente.direccion"
                            />
                        </div>
                    </div>
                </div>
                <div class="vx-col sm:w-full w-full mb-6 relative" v-else>
                    <vs-input
                        class="w-full busqueda_cliente"
                        placeholder="Escoge un cliente Para agregar un Comprobante"
                        v-model="cliente.busqueda"
                        @keyup="listar_cliente(cliente.busqueda)"
                    />
                    <feather-icon
                        icon="SearchIcon"
                        svgClasses="w-8 h-8 hover:text-primary stroke-current cursor-pointer"
                        class="busqueda_cliente_icono"
                    />
                    <div
                        class="busqueda_lista busqueda_cliente_ls"
                        style="display:none"
                    >
                        <div v-if="preloader.cliente">
                            <ul
                                class="ul_busqueda_lista"
                                v-if="cliente.clientes.length"
                            >
                                <li
                                    v-for="(tr, index) in cliente.clientes"
                                    :key="index"
                                    @click="seleccionar_cliente(tr)"
                                >
                                    {{ tr.nombre }}
                                </li>
                            </ul>
                            <ul class="ul_busqueda_lista" v-else>
                                <li @click="modal.abrir = true">
                                    ESTE CLIENTE NO SE ENCUENTRA REGISTRADO,
                                    AGREGAR NUEVO CLIENTE
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
                <div v-show="error" v-if="!cliente.tipo">
                    <div
                        v-for="err in error.cliente.tipo"
                        :key="err"
                        v-text="err"
                        class="text-danger"
                    ></div>
                </div>
            </div>
            <vs-divider position="left">
                <h3>Productos</h3>
            </vs-divider>
            <div class="vx-row leading-loose p-base">
                <div
                    class="vx-col sm:w-full w-full relative"
                    v-if="producto.tipo"
                >
                    <vs-table
                        hoverFlat
                        :data="producto.lista_productos"
                        style="font-size: 12px;"
                    >
                        <template slot="thead">
                            <vs-th >CÓDIGO</vs-th>
                            <vs-th>NOMBRE</vs-th>
                            <vs-th
                                class="text-center"
                                style="width:10%!important;"
                                >PROYECTO</vs-th
                            >
                            <vs-th>CANTIDAD</vs-th>
                            <vs-th>PRECIO</vs-th>
                            <vs-th style="width: 110px;">ICE</vs-th>
                            <vs-th>DESCUENTO</vs-th>
                            <vs-th>SUBTOTAL</vs-th>
                            <vs-th
                                class="text-center"
                                style="width:10%!important;"
                                >TIEMPO ENTREGA</vs-th
                            ><vs-th
                                class="text-center"
                                style="width:10%!important;"
                                >CPC</vs-th
                            >
                            <vs-th style="width:3%!important;"></vs-th>
                        </template>
                        <template slot-scope="{ data }">
                            <vs-tr
                                v-for="(tr, index) in data"
                                :key="index"
                                class="fila_lista"
                            >
                                <vs-td v-if="tr.cod_alterno">{{
                                    tr.cod_alterno
                                }}</vs-td>
                                <vs-td v-else>{{ tr.cod_principal }}</vs-td>
                                <vs-td>
                                    <vs-input class="w-full derecha font-small" v-model="tr.nombre"/>
                                </vs-td>
                                <vs-td>
                                    <vs-select
                                        class="selectExample w-full"
                                        v-model="tr.proyecto"
                                    >
                                        <vs-select-item
                                            :key="index"
                                            :value="item.id_proyecto"
                                            :text="item.descripcion"
                                            v-for="(item,
                                            index) in proyectos_menu"
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
                                <vs-td style="width:150px!important;">
                                    <vs-input
                                        class="w-full"
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
                                <vs-td style="width:150px!important;">
                                    <vs-input
                                        class="w-full"
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
                                <vs-td
                                    ><span
                                        class="vs-inputx vs-input--input normal hasValue"
                                        style="font-size: 13.5px;font-family: inherit;"
                                        >${{
                                            (
                                                tr.total_ice * tr.cantidad
                                            ).toFixed(2)
                                        }}</span
                                    ></vs-td
                                >
                                <vs-td style="width:200px!important;">
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
                                                        elejido:
                                                            tr.p_descuento == 1
                                                    }"
                                                    @click="tr.p_descuento = 1"
                                                >
                                                    $
                                                </button>
                                                <button
                                                    class="botonstl"
                                                    :class="{
                                                        elejido:
                                                            tr.p_descuento == 0
                                                    }"
                                                    @click="tr.p_descuento = 0"
                                                >
                                                    %
                                                </button>
                                            </div>
                                        </template>
                                    </vx-input-group>
                                </vs-td>
                                <vs-td style="width:130px!important;">
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
                                <vs-td>
                                    <vs-input
                                        class="w-full txt-center"
                                        v-model="tr.tiempo_entrega"
                                /></vs-td>
                                <vs-td>
                                    <vs-input
                                        class="w-full txt-center"
                                        v-model="tr.cpc"
                                /></vs-td>
                                <vs-td>
                                    <vx-tooltip
                                        text="Información Categoría"
                                        position="top"
                                        style="display: inline-flex;"
                                    >
                                        <feather-icon
                                            v-if="tr.categoria == 'Cortinas'"
                                            icon="ShoppingBagIcon"
                                            svgClasses="w-5 h-5 hover:text-primary stroke-current cursor-pointer"
                                            class="pointer txt-center"
                                            @click="abrircategoria(index)"
                                        />
                                    </vx-tooltip>
                                    <feather-icon
                                        icon="TrashIcon"
                                        svgClasses="w-5 h-5 hover:text-primary stroke-current cursor-pointer"
                                        class="pointer txt-center"
                                        @click="eliminar_producto(index)"
                                /></vs-td>
                            </vs-tr>
                        </template>
                    </vs-table>
                </div>
                <div class="vx-col sm:w-full w-full mb-6 relative">
                    <vs-input
                        class="w-full busqueda_cliente focuspr"
                        placeholder="Agrega productos a esta proforma"
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
                                    <span
                                        v-if="tr.cod_alterno"
                                        style="font-weight: bold;"
                                        >CodAlt: {{ tr.cod_alterno }} -
                                    </span>
                                    <span v-else style="font-weight: bold;"
                                        >CodPrin: {{ tr.cod_principal }} -
                                    </span>
                                    <span style="font-weight: bold;">{{
                                        tr.nombre
                                    }}</span>
                                    <span
                                        v-if="tr.presentacion"
                                        style="font-weight: bold;"
                                    >
                                        - Presentación: {{ tr.presentacion }}
                                    </span>
                                    -
                                    <span
                                        style="font-size: 12px;"
                                        v-if="tr.cantidad == null"
                                        >stock no disponible</span
                                    >
                                    <span style="font-size: 12px;" v-else
                                        >stock total: {{ tr.cantidad }}</span
                                    >
                                    <!--<span v-if="tr.nombrebodega">
                                        -
                                        <span style="font-size: 12px;"
                                            >Código:
                                            {{ tr.cod_principal }}</span
                                        >-
                                        <span style="font-size: 12px;"
                                            >Bodega: {{ tr.nombrebodega }}</span
                                        ></span
                                    >-->
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
                                rows="2"
                            />
                            <h6>Lugar de entrega:</h6>
                            <vs-input
                                class="w-full mb-5"
                                v-model="factura.lugarDeEntrega"
                            />
                            <h6>Condiciones de pago:</h6>
                            <vs-input
                                class="w-full"
                                v-model="factura.condicionesDePago"
                            />
                        </div>
                        <div class="vx-col sm:w-1/2 w-full">
                            <div class="cabezera_total">
                                <div>
                                    SUBTOTAL FINAL
                                    <span>$ {{ formulas.subtotal }}</span>
                                </div>
                                <div v-if="formulas.valorice > 0">
                                    Valor ICE
                                    <span>$ {{ formulas.valorice }}</span>
                                </div>

                                <div v-if="formulas.subtotal12 > 0">
                                    SUBTOTAL IVA 12%
                                    <span>$ {{ formulas.subtotal12 }}</span>
                                </div>
                                <div v-if="formulas.valor12 > 0">
                                    Valor IVA 12%
                                    <span>$ {{ formulas.valor12 }}</span>
                                </div>
                                <div v-if="formulas.subtotal0 > 0">
                                    SUBTOTAL IVA 0%
                                    <span>$ {{ formulas.subtotal0 }}</span>
                                </div>
                                <div v-if="formulas.no_impuesto > 0">
                                    NO OBJETO DE IMPUESTO
                                    <span>$ {{ formulas.no_impuesto }}</span>
                                </div>
                                <div v-if="formulas.exento > 0">
                                    EXENTO DE IVA
                                    <span>$ {{ formulas.exento }}</span>
                                </div>
                                <div>
                                    TOTAL DESCUENTO
                                    <span>$ {{ formulas.descuento }}</span>
                                </div>
                                <div>
                                    VALOR TOTAL
                                    <span>$ {{ formulas.total }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="vx-col w-full mt-5">
                <vs-button
                    color="success"
                    type="border"
                    @click="guardar_proforma()"
                    v-if="!$route.params.id"
                    >GUARDAR</vs-button
                >
                <vs-button
                    color="success"
                    type="filled"
                    @click="editar_proforma()"
                    v-else
                    >GUARDAR</vs-button
                >
                <vs-button
                    color="danger"
                    type="border"
                    to="/facturacion/proforma"
                    >CANCELAR</vs-button
                >
            </div>
            <!-- Crear Cliente -->
            <vs-popup
                :title="modal.titulo"
                :active.sync="modal.abrir"
                class="modal-xl"
            >
                <ClientStore
                    :modalactive="true"
                    @CloseCLient="sendClient"
                ></ClientStore>
            </vs-popup>
            <!-- Popup Categorias de Producto -->
            <vs-popup
                title="Información Producto"
                :active.sync="popupprodcategoria"
                class="peque"
            >
                <div class="vx-row">
                    <div class="vx-col md:w-1/3 sm:w-full w-full ml-auto mb-3">
                        <vs-input
                            class="w-full txt-center"
                            label="Color:"
                            v-model="color"
                        />
                    </div>
                    <div class="vx-col md:w-1/3 sm:w-full w-full mb-3">
                        <vs-input
                            class="w-full txt-center"
                            label="Detalle:"
                            v-model="detalle"
                        />
                    </div>
                    <div class="vx-col md:w-1/3 sm:w-full w-full mr-auto mb-3">
                        <vs-input
                            class="w-full txt-center"
                            label="Mando:"
                            v-model="mando"
                        />
                    </div>
                    <div class="vx-col md:w-1/4 sm:w-full w-full ml-auto mb-3">
                        <vs-input
                            class="w-full txt-center"
                            label="Ancho:"
                            v-model="ancho"
                            @keypress="solonumeros($event)"
                        />
                    </div>
                    <div class="vx-col md:w-1/4 sm:w-full w-full  mb-3">
                        <vs-input
                            class="w-full txt-center"
                            label="Alto:"
                            v-model="alto"
                            @keypress="solonumeros($event)"
                        />
                    </div>
                    <div class="vx-col md:w-1/4 sm:w-full w-full mr-auto mb-3">
                        <span style="display:none;">
                            {{ (total = ancho * alto) }}
                        </span>
                        <vs-input
                            disabled
                            class="w-full txt-center"
                            label="M2:"
                            v-model="total"
                            @keypress="solonumeros($event)"
                        />
                    </div>
                    <div class="vx-col w-full ml-auto mr-auto mt-2 text-center">
                        <vs-button
                            color="success"
                            type="filled"
                            @click="guardar_cantidad()"
                            >Guardar</vs-button
                        >
                    </div>
                </div>
            </vs-popup>
        </vx-card>
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
import { log } from "util";
import ClientStore from "../Clientes/ClientesAgregar.vue";
const axios = require("axios");
const {
    rutasEmpresa: { DATA_EMPRESA }
} = require("../../../../../../config-routes/config");
import script_comprobantes from "../../../../factura.js";

export default {
    components: {
        flatPickr,
        "v-select": vSelect,
        ClientStore
    },
    data() {
        return {
            user_admin: null,
            configdateTimePicker: {
                locale: SpanishLocale
            },
            popupprodcategoria: false,
            modal: {
                abrir: false,
                titulo: "Agregar Cliente",
                tipo: 0
            },
            factura: {
                fecha_emision: moment().format("YYYY-MM-DD"),
                fecha_expiracion: "",
                observacion: "",
                lugarDeEntrega: "",
                condicionesDePago: "",
                vendedor: "",
                proyectos: "",
                forma_pago: ""
            },
            cliente: {
                tipo: false,
                busqueda: "",
                clientes: [],
                id_cliente: "",
                nombre: "",
                telefono: "",
                email: "",
                tipo_identificacion: "",
                identificacion: "",
                direccion: ""
            },
            producto: {
                tipo: false,
                busqueda: "",
                productos: [],
                lista_productos: [],
                id_producto: null,
                codigo: "",
                nombre: "",
                cantidad: "",
                precio: 0,
                descuento: 0,
                subtotal: 0,
                tiempo_entrega: "",
                cpc: ""
            },
            tipo_identificacion_menu: [
                { label: "Cédula Identidad", value: 1 },
                { label: "Ruc", value: 2 },
                { label: "Pasaporte", value: 3 },
                { label: "Consumidor Final", value: 4 }
            ],
            grupo_cliente_menu: [],
            tipo_cliente_menu: [],
            grupo_tributario_menu: [
                { label: "Persona Natural", value: "Persona Natural" },
                { label: "Persona Jurídica", value: "Persona Jurídica" }
            ],
            provincia_menu: [],
            canton_menu: [],
            parroquia_menu: [],
            vendedor_menu: [],
            estado_menu: [
                { label: "Activo", value: "Activo" },
                { label: "Inactivo", value: "Inactivo" }
            ],
            forma_pago_menu: [],
            cuenta_contable_menu: [],
            plan_cuenta: {
                buscar: "",
                lista: []
            },
            proyectos_menu: [],
            empresa: [],
            formapagos: [],
            totalef: 0,
            propinapr: null,
            pp_descuento: 1,

            error: {
                error: 0,
                factura: {
                    fecha_emision: [],
                    fecha_expiracion: [],
                    forma_pago: [],
                    vendedor: []
                },
                cliente: {
                    tipo: []
                },
                producto: {
                    busqueda: []
                }
            },
            preloader: {
                cliente: false,
                productos: false
            },
            timeout: null,
            indexprod: null,
            color: "",
            detalle: "",
            mando: "",
            ancho: "",
            alto: "",
            total: ""
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
            var subtotal = 0;
            var subtotalice = 0;
            var valorice = 0;
            var subtotal12 = 0;
            var valor12 = 0;
            var subtotal0 = 0;
            var valor0 = 0;
            var no_impuesto = 0;
            var exento = 0;
            var descuento = 0;
            var total = 0;
            var propina = 0;

            this.producto.lista_productos.forEach(el => {
                if (el.p_descuento == 1) {
                    subtotal += el.precio * el.cantidad - el.descuento;

                    if (el.total_ice) {
                        subtotalice += el.precio * el.cantidad - el.descuento;
                    }
                    if (el.total_ice) {
                        valorice += el.total_ice * el.cantidad;
                    }

                    if (el.iva == 2) {
                        subtotal12 +=
                            el.precio * el.cantidad -
                            el.descuento +
                            el.total_ice * el.cantidad;
                    }
                    if (el.iva == 1) {
                        subtotal0 +=
                            el.precio * el.cantidad -
                            el.descuento +
                            el.total_ice * el.cantidad;
                    }
                    if (el.iva == 3) {
                        no_impuesto +=
                            el.precio * el.cantidad -
                            el.descuento +
                            el.total_ice * el.cantidad;
                    }
                    if (el.iva == 4) {
                        exento +=
                            el.precio * el.cantidad -
                            el.descuento +
                            el.total_ice * el.cantidad;
                    }
                    if (isNaN(parseFloat(el.descuento))) {
                        descuento += 0;
                    } else {
                        descuento += parseFloat(el.descuento);
                    }
                } else {
                    subtotal +=
                        el.precio * el.cantidad -
                        (el.cantidad * el.precio * el.descuento) / 100;

                    if (el.total_ice) {
                        subtotalice +=
                            el.precio * el.cantidad -
                            (el.cantidad * el.precio * el.descuento) / 100;
                    }
                    if (el.total_ice) {
                        valorice += el.total_ice * el.cantidad;
                    }

                    if (el.iva == 2) {
                        subtotal12 +=
                            el.precio * el.cantidad -
                            (el.cantidad * el.precio * el.descuento) / 100 +
                            el.total_ice * el.cantidad;
                    }
                    if (el.iva == 1) {
                        subtotal0 +=
                            el.precio * el.cantidad -
                            (el.cantidad * el.precio * el.descuento) / 100 +
                            el.total_ice * el.cantidad;
                    }
                    if (el.iva == 3) {
                        no_impuesto +=
                            el.precio * el.cantidad -
                            (el.cantidad * el.precio * el.descuento) / 100 +
                            el.total_ice * el.cantidad;
                    }
                    if (el.iva == 4) {
                        exento +=
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
                        descuento += 0;
                    } else {
                        descuento +=
                            (parseFloat(el.precio) *
                                parseFloat(el.cantidad) *
                                parseFloat(el.descuento)) /
                            100;
                    }
                }
                valor12 = subtotal12 * 0.12;
            });
            total += subtotal + valor12 + valorice;

            if (this.pp_descuento == 1) {
                if (parseFloat(this.propinapr) >= 0) {
                    propina = parseFloat(this.propinapr);
                }
            } else {
                if (parseFloat(this.propinapr) >= 0) {
                    propina =
                        (parseFloat(total) * parseFloat(this.propinapr)) / 100;
                }
            }
            total = total - propina;
            return {
                subtotal: subtotal.toFixed(2),
                subtotalice: subtotalice.toFixed(2),
                valorice: valorice.toFixed(2),
                subtotal12: subtotal12.toFixed(2),
                valor12: valor12.toFixed(2),
                subtotal0: subtotal0.toFixed(2),
                valor0: valor0.toFixed(2),
                no_impuesto: no_impuesto.toFixed(2),
                exento: exento.toFixed(2),
                descuento: descuento.toFixed(2),
                total: total.toFixed(2)
            };
        }
    },
    methods: {
        seleccionar_vendedor() {
            //console.log(this.usuario);
            if (this.usuario) {
                axios
                    .get("/api/proforma-vendedor/" + this.usuario.id)
                    .then(({ data }) => {
                        this.user_admin = data[0].user_admin;
                        this.factura.vendedor = data[0].id_vendedor;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        },
        listar_cliente(buscar) {
            this.preloader.cliente = false;
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.timeout = setTimeout(() => {
                axios
                    .get(
                        "/api/notacredito/listar_cliente?buscar=" +
                            buscar +
                            "&empresa=" +
                            this.usuario.id_empresa
                    )
                    .then(({ data }) => {
                        this.cliente.clientes = data;
                        $(".busqueda_cliente_ls").show();
                        setTimeout(() => {
                            this.preloader.cliente = true;
                        }, 100);
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }, 600);
        },
        listar_productos(buscar) {
            this.preloader.productos = false;
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.timeout = setTimeout(() => {
                axios
                    .post("/api/proforma/listar_productos", {
                        buscar: buscar,
                        id_empresa: this.usuario.id_empresa,
                        cliente: this.cliente.id_cliente
                    })
                    .then(({ data }) => {
                        // console.log(data);
                        this.producto.productos = data;
                        $(".busqueda_producto_ls").show();
                        setTimeout(() => {
                            this.preloader.productos = true;
                        }, 100);
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }, 600);
        },
        listarproformaedit() {
            if (this.$route.params.id) {
                axios
                    .get("/api/abrirproforma/" + this.$route.params.id)
                    .then(({ data }) => {
                        (this.factura = {
                            fecha_emision: data.factura.fecha_emision,
                            fecha_expiracion: data.factura.fecha_expiracion,
                            forma_pago: data.factura.id_forma_pagos,
                            vendedor: data.factura.id_vendedor,
                            observacion: data.factura.observacion,
                            lugarDeEntrega: data.factura.lugar_de_entrega,
                            condicionesDePago: data.factura.condiciones_de_pago,
                            proyectos: data.factura.id_proyecto
                        }),
                            (this.cliente = {
                                tipo: true,
                                busqueda: "",
                                clientes: [],
                                id_cliente: data.cliente.id_cliente,
                                nombre: data.cliente.nombre,
                                telefono: data.cliente.telefono,
                                email: data.cliente.email,
                                tipo_identificacion:
                                    data.cliente.tipo_identificacion,
                                identificacion: data.cliente.identificacion,
                                direccion: data.cliente.direccion
                            });
                        data.productos.forEach(el => {
                            this.producto.lista_productos.push({
                                id_detalle:el.id_detalle,
                                id_producto: el.id_producto,
                                cod_alterno: el.cod_alterno,
                                cod_principal: el.cod_principal,
                                nombre: el.nombre,
                                proyecto: el.id_proyecto,
                                cantidad: el.cantidad,
                                cantidadreal: el.cantidad,
                                precio: el.precio,
                                descuento: el.descuento,
                                p_descuento: el.p_descuento,
                                subtotal: el.total,
                                tiempo_entrega: el.tiempo_entrega,
                                cpc: el.cpc,
                                iva: el.id_iva,
                                ice: el.id_ice,
                                total_ice: el.total_ice,
                                categoria: el.categoria,
                                color: el.color,
                                detalle: el.detalle,
                                mando: el.mando,
                                alto: el.alto,
                                ancho: el.ancho
                            });
                        });
                        this.producto.tipo = true;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        },
        sendClient(client) {
            if (client != null) {
                this.seleccionar_cliente(client);
            }
            this.modal.abrir = false;
        },
        seleccionar_cliente(tr) {
            this.cliente.clientes = [];
            this.cliente.busqueda = "";
            this.cliente.tipo = true;
            this.cliente.id_cliente = tr.id_cliente;
            this.cliente.nombre = tr.nombre;
            this.cliente.telefono = tr.telefono;
            this.cliente.email = tr.email;
            this.cliente.tipo_identificacion = tr.tipo_identificacion;
            this.cliente.identificacion = tr.identificacion;
            this.cliente.direccion = tr.direccion;
        },
        seleccionar_productos(tr) {
            //console.log(tr);
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
            this.producto.lista_productos.push({
                id_producto: tr.id_producto,
                cod_alterno: tr.cod_alterno,
                cod_principal: tr.cod_principal,
                nombre: tr.nombre,
                proyecto: this.proyectos_menu[0].id_proyecto,
                cantidad: 1,
                precio: tr.precio,
                descuento: tr.descuento,
                p_descuento: 1,
                subtotal: subtotal,
                tiempo_entrega: tr.tiempo_entrega,
                cpc: tr.cpc,
                iva: tr.iva,
                ice: tr.ice,
                total_ice: tr.total_ice,
                categoria: tr.categoria
            });
            //console.log(this.producto.lista_productos);
            $(".focuspr").focus();
        },
        eliminar_producto(id) {
            this.producto.lista_productos.splice(id, 1);
            if (!this.producto.lista_productos.length) {
                this.producto.tipo = false;
            }
        },
        guardar_proforma() {
            if (this.validar()) {
                return;
            }
            axios
                .post("/api/crearproforma", {
                    factura: this.factura,
                    productos: this.producto.lista_productos,
                    empresa: this.empresa,
                    usuario: this.usuario,
                    cliente: this.cliente.id_cliente,
                    subtotal: this.formulas.subtotal,
                    subtotal12: this.formulas.subtotal12,
                    valor12: this.formulas.valor12,
                    subtotal0: this.formulas.subtotal0,
                    valor0: this.formulas.valor0,
                    no_impuesto: this.formulas.no_impuesto,
                    descuento: this.formulas.descuento,
                    total: this.formulas.total
                    //propinapr: this.propinapr,
                })
                .then(res => {
                    console.log(res.data);
                    this.$vs.notify({
                        title: "Proforma Guardada",
                        text: "Proforma agregada con éxito",
                        color: "success"
                    });
                    this.$router.push("/facturacion/proforma");
                })
                .catch(err => {
                    this.$vs.notify({
                        title: "Error al Guardar",
                        text: "Proforma no ha podido ser guardada",
                        color: "danger"
                    });
                });
        },
        editar_proforma() {
            if (this.validar()) {
                return;
            }
            axios
                .put("/api/editarproforma", {
                    id: this.$route.params.id,
                    //factura
                    factura: this.factura,
                    productos: this.producto.lista_productos,
                    empresa: this.empresa,
                    usuario: this.usuario,
                    cliente: this.cliente.id_cliente,
                    subtotal: this.formulas.subtotal,
                    subtotal12: this.formulas.subtotal12,
                    valor12: this.formulas.valor12,
                    subtotal0: this.formulas.subtotal0,
                    valor0: this.formulas.valor0,
                    no_impuesto: this.formulas.no_impuesto,
                    descuento: this.formulas.descuento,
                    total: this.formulas.total
                    //propinapr: this.propinapr,
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Proforma Editada",
                        text: "Proforma editada con éxito",
                        color: "success"
                    });
                    this.$router.push("/facturacion/proforma");
                })
                .catch(err => {
                    this.$vs.notify({
                        title: "Error al Guardar",
                        text: "Proforma no ha podido ser guardada",
                        color: "danger"
                    });
                });
        },
        zeroFill(number, width) {
            width -= number.toString().length;
            if (width > 0) {
                return (
                    new Array(width + (/\./.test(number) ? 2 : 1)).join("0") +
                    number
                );
            }
            return number + "";
        },
        listarformapagos() {
            axios
                .get("/api/facturaformapagos/" + this.usuario.id_empresa)
                .then(res => {
                    this.formapagos = res.data;
                })
                .catch(err => {
                    console.log(err);
                });
        },
        //Funcion lista contenido de proyectos para select
        listproyect() {
            var url = "/api/getproyect/" + this.usuario.id_empresa;
            axios.get(url).then(res => {
                this.proyectos_menu = res.data;
            });
        },
        //Funcion lista contenido de vendedores para select
        listvendedor() {
            var url = "/api/listarvendedorcliente/" + this.usuario.id_empresa;
            axios.get(url).then(res => {
                this.vendedor_menu = res.data.recupera;
            });
        },
        //valida imputs solo admita numeros
        solonumeros: function($event) {
            //  return /^-?(?:\d+(?:,\d*)?)$/.test($event);
            var num = /^\d*\.?\d*$/;
            if (
                $event.charCode === 0 ||
                num.test(String.fromCharCode($event.charCode))
            ) {
                return true;
            } else {
                $event.preventDefault();
            }
        },
        validar() {
            this.error = {
                error: 0,
                factura: {
                    fecha_emision: [],
                    fecha_expiracion: [],
                    forma_pago: [],
                    vendedor: []
                },
                cliente: {
                    tipo: []
                },
                producto: {
                    busqueda: []
                }
            };

            if (!this.factura.fecha_emision) {
                this.error.factura.fecha_emision.push(
                    "Debe agregar la fecha de emisión"
                );
                this.error.error = 1;
            }
            if (!this.factura.fecha_expiracion) {
                this.error.factura.fecha_expiracion.push(
                    "Debe agregar la fecha de expiración"
                );
                this.error.error = 1;
            }
            if (!this.factura.forma_pago) {
                this.error.factura.forma_pago.push(
                    "Debe agregar la forma de pago"
                );
                this.error.error = 1;
            }
            if (!this.factura.vendedor) {
                this.error.factura.vendedor.push("Debe agregar un vendedor");
                this.error.error = 1;
            }
            if (!this.cliente.tipo) {
                this.error.cliente.tipo.push(
                    "Debe agregar un cliente al comprobante"
                );
                this.error.error = 1;
            }
            if (!this.producto.tipo) {
                this.error.producto.busqueda.push(
                    "Debe agregar un producto al comprobante"
                );
                this.error.error = 1;
            }

            for (var i = 0; i < this.producto.lista_productos.length; i++) {
                this.producto.lista_productos[i].errorproyecto = [];
                this.producto.lista_productos[i].errorcantidad = [];
                this.producto.lista_productos[i].errorprecio = [];
                if (!this.producto.lista_productos[i].proyecto) {
                    this.producto.lista_productos[i].errorproyecto.push(
                        "Obligatorio"
                    );
                    this.error.error = 1;
                }
                if (!this.producto.lista_productos[i].cantidad) {
                    this.producto.lista_productos[i].errorcantidad.push(
                        "Obligatorio"
                    );
                    this.error.error = 1;
                }
                if (!this.producto.lista_productos[i].precio) {
                    this.producto.lista_productos[i].errorprecio.push(
                        "Obligatorio"
                    );
                    this.error.error = 1;
                }
            }

            if (this.error.error) {
                setTimeout(() => {
                    var valor =
                        $(".text-danger:first-child").offset().top - 300;
                    $("html, body").animate(
                        {
                            scrollTop: valor
                        },
                        500
                    );
                }, 50);
            }
            return this.error.error;
        },
        // -----------------IMPLEMENTACION DE CATEGORIAS DE PRODUCTO----------------------------//
        abrircategoria(index) {
            if (this.producto.lista_productos[index].color) {
                this.color = this.producto.lista_productos[index].color;
            } else {
                this.color = "";
            }
            if (this.producto.lista_productos[index].detalle) {
                this.detalle = this.producto.lista_productos[index].detalle;
            } else {
                this.detalle = "";
            }
            if (this.producto.lista_productos[index].mando) {
                this.mando = this.producto.lista_productos[index].mando;
            } else {
                this.mando = "";
            }
            if (this.producto.lista_productos[index].ancho) {
                this.ancho = this.producto.lista_productos[index].ancho;
            } else {
                this.ancho = "";
            }
            if (this.producto.lista_productos[index].alto) {
                this.alto = this.producto.lista_productos[index].alto;
            } else {
                this.alto = "";
            }
            this.total = "";
            this.popupprodcategoria = true;
            this.indexprod = index;
        },
        guardar_cantidad() {
            this.producto.lista_productos[this.indexprod].color = this.color;
            this.producto.lista_productos[
                this.indexprod
            ].detalle = this.detalle;
            this.producto.lista_productos[this.indexprod].mando = this.mando;
            this.producto.lista_productos[this.indexprod].alto = this.alto;
            this.producto.lista_productos[this.indexprod].ancho = this.ancho;
            this.producto.lista_productos[this.indexprod].cantidad = this.total;
            this.popupprodcategoria = false;
            this.indexprod = null;
        },
        //Facturación
        enviado() {
            this.$router.push("/facturacion/proforma");
        },
        intlRound(numero, decimales = 2, usarComa = false) {
            var opciones = {
                maximumFractionDigits: decimales,
                useGrouping: false
            };
            usarComa = usarComa ? "es" : "en";
            return new Intl.NumberFormat(usarComa, opciones).format(numero);
        }
    },
    mounted() {
        this.listvendedor();
        this.seleccionar_vendedor();
        this.listproyect();
        this.listarformapagos();
        this.listarproformaedit();
        $(document).on("click", function(e) {
            var container = $(".busqueda_lista");
            if (
                !container.is(e.target) &&
                container.has(e.target).length === 0
            ) {
                $(".busqueda_lista").hide();
            }
        });
    }
};
</script>
<style lang="scss">
@import "@sass/vuexy/extraComponents/agGridStyleOverride.scss";
.txt-center > div > input {
    text-align: center;
}
.text-center > .vs-table-text {
    text-align: center !important;
    display: block;
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
    position: absolute !important;
    right: 0px;
    margin-top: 18px;
    display: none;
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
    width: 800px !important;
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

.font-small input{
    font-size: 11px;
}
.font-small span{
    font-size: 11px;
}
</style>
