<template>
    <div class="vx-row">
        <div class="vx-col w-full mb-base">
            <vx-card title="Información General">
                <div class="vx-row">
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-input
                            class="w-full txt-center"
                            label="Código"
                            v-model="codigo"
                            disabled
                        />
                    </div>
                    <div class="vx-col sm:w-2/5 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Nombres"
                            v-model="nombre"
                            @input="nombreaddicional(nombre)"
                        />
                        <div v-show="error">
                            <span
                                class="text-danger"
                                v-for="err in errornombre"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-2/5 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Nombre Adicional"
                            v-model="nombre_adicional"
                        />
                    </div>

                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-select
                            placeholder="Seleccione"
                            class="selectExample w-full"
                            v-model="tipo_identificacion"
                            label="Tipo de Identificación"
                            @change="cambio(tipo_identificacion)"
                        >
                            <vs-select-item
                                :key="index"
                                :value="item.value"
                                :text="item.text"
                                v-for="(item,
                                index) in tipo_identificacion_array"
                            />
                        </vs-select>
                        <div v-show="error">
                            <span
                                class="text-danger"
                                v-for="err in errortipo_identificacion"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>

                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="C.I"
                            v-model="ruc_ci"
                            v-if="tipo_identificacion == 'Cédula de Identidad'"
                            @keyup="validarrepresentante"
                            maxlength="10"
                        />
                        <vs-input
                            class="w-full"
                            label="Ruc"
                            v-model="ruc_ci"
                            v-else-if="tipo_identificacion == 'Ruc'"
                            @keyup="validarruc"
                            maxlength="13"
                        />

                        <vs-input
                            class="w-full"
                            label="Identificación"
                            v-model="ruc_ci"
                            v-else
                        />
                        <div v-show="error">
                            <span
                                class="text-danger"
                                v-for="err in errorcedula"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>

                    <div
                        v-if="tipopasaporte"
                        class="vx-col sm:w-1/5 w-full mb-6"
                    >
                        <label class="vs-input--label">País</label>
                        <vs-select
                            autocomplete
                            class="selectExamplen w-full"
                            v-model="codigopais"
                        >
                            <vs-select-item
                                :key="res.id_codigopais"
                                :value="res.codigo_ISO_alpha_2"
                                :text="res.nombre_pais"
                                v-for="res in contenidocodigopais"
                            />
                        </vs-select>
                    </div>
                    <!--codigo_ISO_alpha_2-->
                    <div
                        v-if="tipopasaporte"
                        class="vx-col sm:w-1/5 w-full mb-6"
                    >
                        <vs-input
                            class="w-full"
                            label="Código País"
                            v-model="codigopais"
                            disabled
                        />
                    </div>

                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <label class="vs-input--label">Grupo Cliente</label>
                        <vs-select
                            placeholder="buscar"
                            autocomplete
                            class="selectExample w-full"
                            v-model="grupo_cliente"
                        >
                            <vs-select-item
                                :key="res.id_grupo_cliente"
                                :value="res.id_grupo_cliente"
                                :text="res.nombre_grupo"
                                v-for="res in grupo_cliente2"
                            />
                        </vs-select>
                        <div v-show="error">
                            <span
                                class="text-danger"
                                v-for="err in errorgrupo_cliente"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>

                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <label class="vs-input--label">Tipo Cliente</label>
                        <vs-select
                            placeholder="buscar tipo"
                            autocomplete
                            class="selectExample w-full"
                            v-model="tipo_cliente"
                        >
                            <vs-select-item
                                :key="res.id_tipo_cliente"
                                :value="res.id_tipo_cliente"
                                :text="res.descripcion_tipo_cliente"
                                v-for="res in grupo_cliente4"
                            />
                        </vs-select>
                    </div>

                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <label class="vs-input--label">Grupo Tributario</label>
                        <vs-select
                            placeholder="buscar grupo"
                            autocomplete
                            class="selectExample w-full"
                            v-model="grupo_tributario"
                        >
                            <vs-select-item
                                :key="index"
                                :value="item.value"
                                :text="item.text"
                                v-for="(item, index) in grupo_tributario_array"
                            />
                        </vs-select>
                        <div v-show="error">
                            <span
                                class="text-danger"
                                v-for="err in errorgrupo_tributario"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>

                    <div class="vx-col sm:w-1/2 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Dirección"
                            v-model="direccion"
                        />
                        <div v-show="error">
                            <span
                                class="text-danger"
                                v-for="err in errordireccion"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>

                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <label class="vs-input--label">Provincia</label>
                        <vs-select
                            placeholder="buscar"
                            autocomplete
                            class="selectExample w-full"
                            v-model="provincia"
                            @change="
                                getCiudades();
                                canton = '';
                                parroquia = '';
                            "
                        >
                            <vs-select-item
                                v-for="data in provincias2"
                                :key="data.id_provincia"
                                :value="data.id_provincia"
                                :text="data.nombre"
                            />
                        </vs-select>

                        <div v-show="error">
                            <span
                                class="text-danger"
                                v-for="err in errorprovincia"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>

                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <label class="vs-input--label">Cantón</label>
                        <vs-select
                            placeholder="buscar"
                            autocomplete
                            class="selectExample w-full"
                            v-model="canton"
                            @change="getParroquias"
                        >
                            <vs-select-item
                                v-for="data in ciudades2"
                                :key="data.id_ciudad"
                                :value="data.id_ciudad"
                                :text="data.nombre"
                            />
                        </vs-select>

                        <div v-show="error">
                            <span
                                class="text-danger"
                                v-for="err in errorcanton"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>

                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <label class="vs-input--label">Parroquia</label>
                        <vs-select
                            placeholder="buscar"
                            autocomplete
                            class="selectExample w-full"
                            v-model="parroquia"
                            @change="getParroquias()"
                        >
                            <vs-select-item
                                v-for="data in parroquias2"
                                :key="data.id_parroquia"
                                :value="data.id_parroquia"
                                :text="data.nombre_parroquia"
                            />
                        </vs-select>
                        <div v-show="error">
                            <span
                                class="text-danger"
                                v-for="err in errorparroquia"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <div
                        class="vx-col sm:w-1/4 w-full mb-6"
                        style="margin-top: 30px; margin-bottom: 0.2rem !important;"
                    >
                        <vs-checkbox
                            icon-pack="feather"
                            icon="icon-check"
                            v-model="radios1"
                        >
                            <template v-if="radios1">
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
                            | Parte relacionada
                        </vs-checkbox>
                    </div>

                    <div class="vx-col sm:w-1/4 w-full mb-6">
                       <!-- <vx-tooltip
                            text="Borrar Producto"
                            position="top"
                            style="display: inline-flex;"
                        >-->
                            <label class="vs-input--label"
                                >E-mails:</label
                            ><!--</vx-tooltip
                        >-->
                        <!-- <vs-input
                            class="w-full"
                            label="E-mail"
                            v-model="email"
                        />
                        <div v-show="error">
                            <span
                                class="text-danger"
                                v-for="err in erroremail"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>-->
                        <template>
                            <vs-chips
                                color="rgb(145, 32, 159)"
                                v-model="email"
                                icon-pack="feather"
                                remove-icon="icon-trash-2"
                            >
                                <vs-chip
                                    :key="data"
                                    @click="remove_chip_correo(data)"
                                    v-for="data in email"
                                    closable
                                    icon-pack="feather"
                                    close-icon="icon-trash-2"
                                >
                                    {{ data }}
                                </vs-chip>
                            </vs-chips>
                        </template>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Teléfono"
                            v-model="telefono"
                            @keypress="solonumeros($event)"
                            maxlength="10"
                        />
                        <div v-show="error">
                            <span
                                class="text-danger"
                                v-for="err in errortelefono"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Contacto"
                            v-model="contacto"
                        />
                        <div v-show="error">
                            <span
                                class="text-danger"
                                v-for="err in errorcontacto"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>

                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <label class="vs-input--label">Vendedor</label>
                        <vs-select
                            placeholder="buscar"
                            autocomplete
                            class="selectExample w-full"
                            v-model="vendedor"
                        >
                            <vs-select-item
                                :key="res.id_vendedor"
                                :value="res.id_vendedor"
                                :text="res.nombre_vendedor"
                                v-for="res in grupo_cliente3"
                            />
                        </vs-select>
                    </div>

                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <label class="vs-input--label">Estado</label>
                        <vs-select
                            placeholder="seleccione estado"
                            autocomplete
                            class="selectExample w-full"
                            v-model="estado"
                        >
                            <vs-select-item
                                :key="index"
                                :value="item.value"
                                :text="item.text"
                                v-for="(item, index) in estado_array"
                            />
                        </vs-select>
                        <div v-show="error">
                            <span
                                class="text-danger"
                                v-for="err in errorestado"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>

                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Descuento %"
                            v-model="descuento"
                            @keypress="solonumeros($event)"
                            maxlength="3"
                        />
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <label class="vs-input--label">Cuenta Contable</label>
                        <vx-input-group>
                            <vs-input
                                disabled
                                class="w-full"
                                v-model="cuenta_contable"
                            />
                            <template slot="append">
                                <div class="append-text btn-addon">
                                    <vs-button
                                        color="primary"
                                        @click="popupActive = true"
                                        >Buscar</vs-button
                                    >
                                </div>
                            </template>
                        </vx-input-group>
                    </div>
                    <!--===============================Popup cuenta contable===================================-->

                    <vs-popup
                        title="Seleccione una Cuenta Contable"
                        :active.sync="popupActive"
                    >
                        <div class="con-exemple-prompt">
                            <vs-input
                                class="mb-4 md:mb-0 mr-4 w-full"
                                v-model="buscar1"
                                @keyup="listarcuenta(buscar1)"
                                v-bind:placeholder="i18nbuscar"
                            />
                            <vs-table
                                stripe
                                v-model="cuentaarray3"
                                @selected="handleSelected"
                                :data="contenidocuenta"
                            >
                                <template slot="thead">
                                    <vs-th>No.Cuenta</vs-th>
                                    <vs-th>Tipo Cuenta</vs-th>
                                </template>
                                <template slot-scope="{ data }">
                                    <vs-tr
                                        :data="tr"
                                        :key="indextr"
                                        v-for="(tr, indextr) in data"
                                    >
                                        <vs-td :data="data[indextr].codcta">{{
                                            data[indextr].codcta
                                        }}</vs-td>

                                        <vs-td :data="data[indextr].nomcta">{{
                                            data[indextr].nomcta
                                        }}</vs-td>
                                    </vs-tr>
                                </template>
                            </vs-table>
                        </div>
                    </vs-popup>

                    <!--======================================== Fin popup cuenta contable ==================================== -->

                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Número Pagos"
                            v-model="num_pago"
                            @keypress="solonumeros($event)"
                            maxlength="3"
                        />
                    </div>

                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <label class="vs-input--label">Lista de Precios</label>
                        <vs-select
                            placeholder="seleccione uno"
                            autocomplete
                            class="selectExample w-full"
                            v-model="lista_precios"
                        >
                            <vs-select-item
                                :key="index"
                                :value="item.value"
                                :text="item.text"
                                v-for="(item, index) in lista_precios_array"
                            />
                        </vs-select>
                    </div>

                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <label class="vs-input--label">Forma de Pago</label>
                        <vs-select
                            placeholder="seleccione"
                            autocomplete
                            class="selectExample w-full"
                            v-model="forma_pago"
                        >
                            <vs-select-item
                                :key="data.id_forma_pagos"
                                :value="data.id_forma_pagos"
                                :text="data.descripcion"
                                v-for="data in listarFormaPago"
                            />
                        </vs-select>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Límite de Credito"
                            v-model="limite_credito"
                            @keypress="solonumeros($event)"
                            maxlength="10"
                        />
                    </div>
                    <div class="vx-col sm:w-full w-full mb-6">
                        <div>
                            <label class="vs-input--label">Comentario:</label>
                            <vs-textarea v-model="comentario" rows="3" />
                        </div>
                    </div>

                    <div class="vx-col w-full">
                        <vs-button
                            color="success"
                            type="filled"
                            @click="editar()"
                            v-if="$route.params.id"
                            >GUARDAR</vs-button
                        >
                        <vs-button
                            color="success"
                            type="filled"
                            @click="guardar()"
                            v-else
                            >GUARDAR</vs-button
                        >
                        <vs-button
                            color="danger"
                            type="filled"
                            to="/facturacion/clientes"
                            >CANCELAR</vs-button
                        >
                    </div>
                </div>
            </vx-card>
        </div>
    </div>
</template>

<script>
import { log } from "util";
const $ = require("jquery");
const axios = require("axios");
export default {
    data() {
        return {
            /**
             * mapeo de datos
             */
            cuentaarray3: [],
            contenidocuenta: [],
            buscar1: "",
            popupActive: false,
            //variables paginacion de las tablas
            pagination1: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0,
                count: 0
            },
            pagina1: 1,
            cantidadp1: 10,
            offset: 3,
            gridApi: null,
            contenido: [],
            buscar: "",
            criterio1: "codcta",
            idActivo: "",
            cuenta_contable: "",
            cuenta_contable_id: "",
            tipopasaporte: 0,
            codigo1: "",
            contenidocuenta: [],
            contenidocodigopais: [],
            provincias2: [],
            grupo_cliente2: [],
            grupo_cliente3: [],
            grupo_cliente4: [],
            listarFormaPago: [],
            ciudades2: [],
            parroquias2: [],
            codigo: "",
            nombre: "",
            nombre_adicional: "",
            ruc_ci: "",
            grupo_cliente: "",
            tipo_cliente: "",
            provincia: "",
            provincia_array: "",
            direccion: "",
            telefono: "",
            email: [],
            chips: [],
            contacto: "",
            comentario: "",
            descuento: "",
            radios1: "",
            num_pago: "",
            limite_credito: "",
            tipo_identificacion: "",
            grupo_tributario: "",
            grupo_tributario_array: "",
            canton: "",
            canton_array: "",
            parroquia: "",
            vendedor: "",
            estado: "",
            estado_array: "",
            codigopais: "",
            lista_precios: "1",
            forma_pago: "",
            forma_pago_array: "",
            select2: 0,
            select3: 0,
            tipo_identificacion_array: [
                { text: "Cédula de Identidad", value: "Cédula de Identidad" },
                { text: "Ruc", value: "Ruc" },
                { text: "Pasaporte", value: "Pasaporte" },
                { text: "Consumidor Final", value: "Consumidor Final" }
            ],

            grupo_tributario_array: [
                { text: "Persona Natural", value: "Persona Natural" },
                { text: "Persona Jurídica", value: "Persona Jurídica" }
            ],

            estado_array: [
                { text: "Activo", value: "Activo" },
                { text: "Inactivo", value: "Inactivo" }
            ],

            lista_precios_array: [
                { text: "1", value: 1 },
                { text: "2", value: 2 },
                { text: "3", value: 3 },
                { text: "4", value: 4 },
                { text: "5", value: 5 }
            ],
            /*
            forma_pago_array: [
                { text: "Efectivo", value: "Efectivo" },
                { text: "Cheque", value: "Cheque" },
                { text: "Tarjeta", value: "Tarjeta" },
                { text: "Crédito", value: "Crédito" }
            ],
            */
            //variables paginacion de las tablas
            pagination: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0,
                count: 0
            },
            pagina: 1,
            cantidadp: 5,
            offset: 3,
            gridApi: null,
            contenido: [],
            criterio: "codcta",
            //lenguaje
            i18nbuscar: this.$t("i18nbuscar"),
            //variables para ventanas modales
            activePrompt3: false,
            //ERRORES
            error: 0,
            errornombre: [],
            errortipo_identificacion: [],
            errorruc_ci: [],
            errorgrupo_tributario: [],
            errorgrupo_cliente: [],
            errordireccion: [],
            errorprovincia: [],
            errorcanton: [],
            errorparroquia: [],
            erroremail: [],
            errortelefono: [],
            errorcontacto: [],
            errorestado: [],
            errorcedula: []
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
    methods: {
        /**
         * verificar codigo del ultimo cliente existente
         */
        leercodigo() {
            if (!this.$route.params.id) {
                axios
                    .get("/api/verificarcliente/" + this.usuario.id_empresa)
                    .then(res => {
                        this.codigo = res.data;
                    });
            }
        },
        /*
         * Guardar los datos del formulario
         */
        guardar() {
            if (this.validar()) {
                return;
            }
            axios
                .post("/api/cliente/guardar", {
                    codigo: this.codigo,
                    nombre: this.nombre,
                    nombre_adicional: this.nombre_adicional,
                    ruc_ci: this.ruc_ci,
                    grupo_cliente: this.grupo_cliente,
                    tipo_cliente: this.tipo_cliente,
                    provincia: this.provincia,
                    canton: this.canton,
                    direccion: this.direccion,
                    telefono: this.telefono,
                    email: this.email,
                    contacto: this.contacto,
                    comentario: this.comentario,
                    descuento: this.descuento,
                    radios1: this.radios1,
                    num_pago: this.num_pago,
                    limite_credito: this.limite_credito,
                    tipo_identificacion: this.tipo_identificacion,
                    grupo_tributario: this.grupo_tributario,
                    parroquia: this.parroquia,
                    vendedor: this.vendedor,
                    estado: this.estado,
                    id_plan_cuentas: this.cuenta_contable_id,
                    codigopais: this.codigopais,
                    lista_precios: this.lista_precios,
                    forma_pago: this.forma_pago,
                    empresa: this.usuario.id_empresa
                })
                .then(res => {
                    if (res.data == "error_identificacion") {
                        this.$vs.notify({
                            title: "Error al Guardar",
                            text: "El Cliente que esta registrando ya existe",
                            color: "danger"
                        });
                    } else {
                        this.$vs.notify({
                            title: "Cliente Guardado",
                            text: "Registro guardado exitosamente",
                            color: "success"
                        });
                        this.$router.push("/facturacion/clientes");
                    }
                })
                .catch(err => {
                    console.log(err);
                });
        },
        /*
         * edita los datos del formulario
         */
        editar() {
            if (this.validar()) {
                return;
            }
            axios
                .put("/api/cliente/editar", {
                    id: this.$route.params.id,
                    codigo: this.codigo,
                    nombre: this.nombre,
                    nombre_adicional: this.nombre_adicional,
                    codigopais: this.codigopais,
                    ruc_ci: this.ruc_ci,
                    grupo_cliente: this.grupo_cliente,
                    tipo_cliente: this.tipo_cliente,
                    provincia: this.provincia,
                    canton: this.canton,
                    direccion: this.direccion,
                    telefono: this.telefono,
                    email: this.email,
                    contacto: this.contacto,
                    comentario: this.comentario,
                    descuento: this.descuento,
                    radios1: this.radios1,
                    num_pago: this.num_pago,
                    limite_credito: this.limite_credito,
                    tipo_identificacion: this.tipo_identificacion,
                    grupo_tributario: this.grupo_tributario,
                    parroquia: this.parroquia,
                    vendedor: this.vendedor,
                    estado: this.estado,
                    id_plan_cuentas: this.cuenta_contable_id,
                    lista_precios: this.lista_precios,
                    forma_pago: this.forma_pago,
                    empresa: this.usuario.id_empresa
                })
                .then(res => {
                    if (res.data == "error_identificacion") {
                        this.$vs.notify({
                            title: "Error al Guardar",
                            text: "El Cliente ya existe",
                            color: "danger"
                        });
                    } else {
                        this.$vs.notify({
                            title: "Cliente Guardado",
                            text: "Registro actualizado exitosamente",
                            color: "success"
                        });
                        this.$router.push("/facturacion/clientes");
                    }
                })
                .catch(err => {
                    console.log(err);
                });
        },
        /*
         * elimina los campos de formulario
         */
        borrar() {
            this.nombre = "";
            this.nombre_adicional = "";
            this.codigopais = "";
            this.ruc_ci = "";
            this.grupo_cliente = "";
            this.tipo_cliente = "";
            this.provincia = "";
            this.direccion = "";
            this.telefono = "";
            this.email = "";
            this.contacto = "";
            this.comentario = "";
            this.descuento = "";
            this.radios1 = "";
            this.num_pago = "";
            this.limite_credito = "";
            this.tipo_identificacion = "";
            this.grupo_tributario = "";
            this.canton = "";
            this.parroquia = "";
            this.vendedor = "";
            this.estado = "";
            this.cuenta_contable = "";
            this.cuenta_contable_id = "";
            this.lista_precios = "";
            this.forma_pago = "";
        },
        /*
         * Lista los datos de clientes en una tabla
         */
        listarcliente(id) {
            axios
                .get("/api/cliente/vercliente/" + id)
                .then(res => {
                    let data = res.data;
                    this.codigo = data.codigo;
                    this.nombre = data.nombre;
                    this.nombre_adicional = data.nombre_adicional;
                    this.tipo_identificacion = data.tipo_identificacion;
                    this.codigopais = data.pais;
                    this.ruc_ci = data.identificacion;
                    this.grupo_cliente = data.id_grupo_cliente;
                    this.tipo_cliente = data.id_tipo_cliente;
                    this.grupo_tributario = data.grupo_tributario;
                    this.direccion = data.direccion;
                    this.provincia = data.id_provincia;
                    this.canton = data.id_cuidad;
                    this.parroquia = data.id_parroquia;
                    this.radios1 = data.parte_relacionada;
                    this.email = data.email.split(" ");
                    this.telefono = data.telefono;
                    this.contacto = data.contacto;
                    this.vendedor = data.id_vendedor;
                    this.estado = data.estado;
                    this.descuento = data.descuento;
                    this.cuenta_contable_id = data.id_plan_cuentas;
                    this.cuenta_contable = data.plan_cuentas;
                    this.num_pago = data.num_pago;
                    this.lista_precios = data.lista_precios;
                    this.forma_pago = data.id_forma_pagos;
                    this.limite_credito = data.limite_credito;
                    this.comentario = data.comentario;
                })
                .catch(err => {
                    console.log(err);
                });
        },
        /*
         * valida los datos del formulario
         */
        validar() {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            this.error = 0;
            this.errornombre = [];
            this.errortipo_identificacion = [];
            this.errorruc_ci = [];
            this.errorgrupo_tributario = [];
            this.errorgrupo_cliente = [];
            (this.errorcedula = []), (this.errordireccion = []);
            this.errorprovincia = [];
            this.errorcanton = [];
            this.errorparroquia = [];
            this.erroremail = [];
            this.errortelefono = [];
            this.errorcontacto = [];
            this.errorestado = [];

            if (!this.nombre) {
                this.errornombre.push("Campo obligatorio");
                this.error = 1;
            }

            if (!this.tipo_identificacion) {
                this.errortipo_identificacion.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }
            if (!this.ruc_ci) {
                this.errorruc_ci.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }

            if (!this.ruc_ci) {
                this.errorcedula.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }

            if (!this.grupo_tributario) {
                this.errorgrupo_tributario.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }

            if (!this.direccion) {
                this.errordireccion.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }

            /* if (!this.provincia) {
                this.errorprovincia.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }

            if (!this.canton) {
                this.errorcanton.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }

            if (!this.parroquia) {
                this.errorparroquia.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }*/

            if (!this.validaremail(this.email)) {
                this.erroremail.push("Email no valido");
                this.error = 1;
                window.scrollTo(0, 0);
            }

            if (!this.telefono) {
                this.errortelefono.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }

            if (!this.contacto) {
                this.errorcontacto.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }

            if (!this.estado) {
                this.errorestado.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }

            return this.error;
        },
        /*
         * método de validación de email
         */
        validaremail(email) {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        },
        /*
         * listar provincias
         */
        getProvincias: function() {
            axios.get("/api/provincia").then(
                function(response) {
                    this.provincias2 = response.data;
                }.bind(this)
            );
        },
        /*
         * listar cantones depende de la provincia
         */
        getCiudades: function() {
            axios
                .get("/api/ciudad", {
                    params: {
                        id_provincia: this.provincia
                    }
                })
                .then(
                    function(response) {
                        this.ciudades2 = response.data;
                    }.bind(this)
                );
        },
        /*
         * listar parroquias depende del cantón
         */
        getParroquias: function() {
            axios
                .get("/api/parroquia", {
                    params: {
                        id_ciudad: this.canton
                    }
                })
                .then(
                    function(response) {
                        this.parroquias2 = response.data;
                    }.bind(this)
                );
        },
        /*
         * listar grupo cliente
         */
        getGrupo() {
            let me = this;
            var url = "/api/grupo_cliente/" + this.usuario.id_empresa;
            axios
                .get(url)
                .then(function(response) {
                    me.grupo_cliente2 = response.data;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        /*
         * listar vendedor
         */
        getGrupovendedor() {
            let me = this;
            var url = "/api/grupo_vendedor/" + this.usuario.id_empresa;
            axios
                .get(url)
                .then(function(response) {
                    me.grupo_cliente3 = response.data;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        getFormaPago() {
            let me = this;
            var url = "/api/listarFormaDePagos/" + this.usuario.id_empresa;
            axios
                .get(url)
                .then(function(response) {
                    me.listarFormaPago = response.data;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        /*
         * listar tipo de cliente
         */
        gettipocliente() {
            let me = this;
            var url = "/api/grupotipocliente/" + this.usuario.id_empresa;
            axios
                .get(url)
                .then(function(response) {
                    me.grupo_cliente4 = response.data;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        /*
         * listar plan de cuentas
         */

        //lista cotenido de cuenta contable
        listarcuenta(buscar1) {
            axios
                .get(
                    "/api/selcuentas/" +
                        this.usuario.id_empresa +
                        "?buscar=" +
                        buscar1
                )
                .then(res => {
                    this.contenidocuenta = res.data.recupera;
                });
        },
        abrirlista() {
            $(".menuescoger").show();
        },
        //funcion selecciona y establece contendio de cuenta contable en formulario
        handleSelected(tr) {
            this.cuenta_contable_id = `${tr.id_plan_cuentas}`;
            this.cuenta_contable = `${tr.nomcta}`;
            this.popupActive = false;
        },
        /*
         * listar código del país extranjero
         */
        listarcodigopais() {
            let me = this;
            var url = "/api/listarcodigopais";
            axios
                .get(url)
                .then(function(response) {
                    me.contenidocodigopais = response.data;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        /*
         * listar plan de cuentas
         */
        listar(page, buscar) {
            var url =
                "/api/cuentas/" +
                this.usuario.id_empresa +
                "?page=" +
                page +
                "&buscar=" +
                buscar;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenido = respuesta.recupera;
            });
        },
        /*
         * validacion tipo de identificación
         */
        cambio(c) {
            this.ruc_ci = "";
            this.errorcedula = "";
            this.tipopasaporte = 0;
            //Validar cédula
            if (c === "Cédula de Identidad") {
            }
            //validar ruc
            if (c === "Ruc") {
            }
            //validar pasaporte
            if (c === "Pasaporte") {
                this.tipopasaporte = 1;
            }
            //validar consumidor final
            if (c === "Consumidor Final") {
                this.ruc_ci = "9999999999999";
            }
        },
        /**
         * validación de Cédula y ruc
         */
        validarruc($event) {
            this.error = 0;
            this.errorcedula = [];
            var numero = this.ruc_ci;
            var suma = 0;
            var residuo = 0;
            var pri = false;
            var pub = false;
            var nat = false;
            var numeroProvincias = 22;
            var modulo = 11;

            /* Verifico que el campo no contenga letras */
            var ok = 1;

            /* Aqui almacenamos los digitos de la cedula en variables. */
            var d1 = numero.substr(0, 1);
            var d2 = numero.substr(1, 1);
            var d3 = numero.substr(2, 1);
            var d4 = numero.substr(3, 1);
            var d5 = numero.substr(4, 1);
            var d6 = numero.substr(5, 1);
            var d7 = numero.substr(6, 1);
            var d8 = numero.substr(7, 1);
            var d9 = numero.substr(8, 1);
            var d10 = numero.substr(9, 1);

            /* El tercer digito es: */
            /* 9 para sociedades privadas y extranjeros */
            /* 6 para sociedades publicas */
            /* menor que 6 (0,1,2,3,4,5) para personas naturales */

            if (d3 == 7 || d3 == 8) {
                //console.log('El tercer dígito ingresado es inválido');
                this.errorcedula.push("El tercer dígito ingresado es inválido");
                this.error = 1;
                return false;
            }

            /* Solo para personas naturales (modulo 10) */
            if (d3 < 6) {
                nat = true;
                p1 = d1 * 2;
                if (p1 >= 10) p1 -= 9;
                p2 = d2 * 1;
                if (p2 >= 10) p2 -= 9;
                p3 = d3 * 2;
                if (p3 >= 10) p3 -= 9;
                p4 = d4 * 1;
                if (p4 >= 10) p4 -= 9;
                p5 = d5 * 2;
                if (p5 >= 10) p5 -= 9;
                p6 = d6 * 1;
                if (p6 >= 10) p6 -= 9;
                p7 = d7 * 2;
                if (p7 >= 10) p7 -= 9;
                p8 = d8 * 1;
                if (p8 >= 10) p8 -= 9;
                p9 = d9 * 2;
                if (p9 >= 10) p9 -= 9;
                modulo = 10;
            } else if (d3 == 6) {
                /* Solo para sociedades publicas (modulo 11) */
                /* Aqui el digito verficador esta en la posicion 9, en las otras 2 en la pos. 10 */
                pub = true;
                p1 = d1 * 3;
                p2 = d2 * 2;
                p3 = d3 * 7;
                p4 = d4 * 6;
                p5 = d5 * 5;
                p6 = d6 * 4;
                p7 = d7 * 3;
                p8 = d8 * 2;
                p9 = 0;
            } else if (d3 == 9) {
                /* Solo para entidades privadas (modulo 11) */
                var pri = true;
                var p1 = d1 * 4;
                var p2 = d2 * 3;
                var p3 = d3 * 2;
                var p4 = d4 * 7;
                var p5 = d5 * 6;
                var p6 = d6 * 5;
                var p7 = d7 * 4;
                var p8 = d8 * 3;
                var p9 = d9 * 2;
            }

            var suma = p1 + p2 + p3 + p4 + p5 + p6 + p7 + p8 + p9;
            var residuo = suma % modulo;

            /* Si residuo=0, dig.ver.=0, caso contrario 10 - residuo*/
            var digitoVerificador = residuo == 0 ? 0 : modulo - residuo;

            /* ahora comparamos el elemento de la posicion 10 con el dig. ver.*/
            if (pub == true) {
                if (digitoVerificador != d9) {
                    //console.log('El ruc de la empresa del sector público es incorrecto.');
                    this.errorcedula.push("Ruc invalido");
                    this.error = 1;
                    return false;
                }
                /* El ruc de las empresas del sector publico terminan con 0001*/
                if (numero.substr(9, 4) != "0001") {
                    this.errorcedula.push("Ruc invalido");
                    this.error = 1;
                    return false;
                }
            } else if (pri == true) {
                if (digitoVerificador != d10) {
                    this.errorcedula.push("Ruc invalido");
                    this.error = 1;
                    return false;
                }
                if (numero.substr(10, 3) != "001") {
                    this.errorcedula.push("Ruc invalido");
                    this.error = 1;
                    return false;
                }
            } else if (nat == true) {
                if (digitoVerificador != d10) {
                    //console.log('El número de cédula de la persona natural es incorrecto.');
                    this.errorcedula.push("Ruc invalido");
                    this.error = 1;
                    return false;
                }
                if (numero.length < 14 && numero.substr(10, 12) != "001") {
                    //console.log('El ruc de la persona natural debe terminar con 001');
                    this.errorcedula.push("Ruc invalido");
                    this.error = 1;
                    return false;
                }
            }
            return true;
        },
        validarrepresentante($event) {
            this.errorcedula = [];
            var cad = this.ruc_ci;
            var total = 0;
            var longitud = cad.length;
            var longcheck = longitud - 1;
            for (var i = 0; i < longcheck; i++) {
                if (i % 2 === 0) {
                    var aux = cad.charAt(i) * 2;
                    if (aux > 9) aux -= 9;
                    total += aux;
                } else {
                    total += parseInt(cad.charAt(i)); // parseInt o concatenará en lugar de sumar
                }
            }
            total = total % 10 ? 10 - (total % 10) : 0;

            if (cad.substring(0, 10).charAt(longitud - 1) == total) {
                this.errorcedula = [];
            } else {
                this.errorcedula.push("Cédula inválida");
                this.error = 1;
                return;
            }
        },
        /**
         * validación de solo números, permite solo el
         * ingreso de numeros en los formularios de registro
         */
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
        nombreaddicional(nom) {
            this.nombre_adicional = nom;
        },
        remove_chip_correo(item) {
            this.email.splice(this.email.indexOf(item), 1);
        }
    },
    mounted() {
        //this.listarcuentas();
        this.leercodigo();
        this.listarcuenta(this.buscar1);
        if (this.$route.params.id) {
            var id = this.$route.params.id;
            this.listarcliente(id);
        }

        this.listarcodigopais();
        if (this.$route.params.id) {
            var id = this.$route.params.id;
            this.listarcliente(id);
        }
        this.getGrupo();
        this.getGrupovendedor();
        this.gettipocliente();
        this.getProvincias();
        this.getCiudades();
        this.getParroquias();
        this.getFormaPago();
    }
};
</script>
<style lang="scss">
.txt-center > div > input {
    text-align: center;
}
</style>
