<template>
    <div class="vx-row">
        <div class="vx-col w-full mb-base">
            <vx-card title="Información General">
                <!--====================Start Form Punto eminsio===============================-->
                <div class="vx-row">
                    <div class="vx-col sm:w-1/2 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Nombre"
                            v-model="nombre"
                            maxlength="45"
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

                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Punto de emisión"
                            v-model="codigo"
                            placeholder="Eje:001"
                            @keypress="solonumeros($event)"
                            maxlength="3"
                        />
                        <div v-show="error">
                            <span
                                class="text-danger"
                                v-for="err in errorcodigo"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Sec. Factura"
                            placeholder="Eje:000000001"
                            v-model="secuencial_factura"
                            @keypress="solonumeros($event)"
                            maxlength="9"
                        />
                        <div v-show="error">
                            <span
                                class="text-danger"
                                v-for="err in errorsecuencial_factura"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Sec. Nota Credito"
                            v-model="secuencial_nota_credito"
                            @keypress="solonumeros($event)"
                            maxlength="9"
                        />
                        <div v-show="error">
                            <span
                                class="text-danger"
                                v-for="err in errorsecuencial_nota_credito"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Sec. Nota Debito"
                            v-model="secuencial_nota_debito"
                            @keypress="solonumeros($event)"
                            maxlength="9"
                        />
                        <div v-show="error">
                            <span
                                class="text-danger"
                                v-for="err in errorsecuencial_nota_debito"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Sec. Guia"
                            v-model="secuencial_guia"
                            @keypress="solonumeros($event)"
                            maxlength="9"
                        />
                        <div v-show="error">
                            <span
                                class="text-danger"
                                v-for="err in errorsecuencial_guia"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Sec. Retencion"
                            v-model="secuencial_retencion"
                            @keypress="solonumeros($event)"
                            maxlength="9"
                        />
                        <div v-show="error">
                            <span
                                class="text-danger"
                                v-for="err in errorsecuencial_retencion"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Sec. Liquidación"
                            v-model="secuencial_l"
                            @keypress="solonumeros($event)"
                            maxlength="9"
                        />
                        <div v-show="error">
                            <span
                                class="text-danger"
                                v-for="err in errorsecuencial_l"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Sec. Nota Venta"
                            v-model="secuencial_nota_venta"
                            @keypress="solonumeros($event)"
                            maxlength="9"
                        />
                        <!-- <div v-show="error">
                            <span
                                class="text-danger"
                                v-for="err in errorsecuencial_l"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div> -->
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Sec. Recibo"
                            v-model="secuencial_recibo"
                            @keypress="solonumeros($event)"
                            maxlength="9"
                        />
                        <!-- <div v-show="error">
                            <span
                                class="text-danger"
                                v-for="err in errorsecuencial_l"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div> -->
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <label class="vs-input--label">Estado</label>
                        <vs-checkbox
                            icon-pack="feather"
                            class="mt-3"
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
                            <template v-if="radios1">
                                | Activo
                            </template>
                            <template v-else>
                                | Inactivo
                            </template>
                        </vs-checkbox>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-select
                            placeholder="Selecione el establecimiento"
                            class="selectExample w-full"
                            label="Establecimiento"
                            vs-multiple
                            autocomplete
                            v-model="establecimientoe"
                        >
                            <vs-select-item
                                :key="index"
                                :value="item.id_establecimiento"
                                :text="item.nombre"
                                v-for="(item, index) in establecimiento"
                            />
                        </vs-select>
                        <div v-show="error">
                            <span
                                class="text-danger"
                                v-for="err in errorestablecimiento"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-select
                            placeholder="Selecione Bodega"
                            class="selectExample w-full"
                            label="Bodega"
                            vs-multiple
                            autocomplete
                            v-model="id_bodega"
                        >
                            <vs-select-item
                                :key="index"
                                :value="item.id_bodega"
                                :text="item.nombre"
                                v-for="(item, index) in bodegas"
                            />
                        </vs-select>
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
                            color="warning"
                            type="filled"
                            @click="borrar()"
                            >BORRAR</vs-button
                        >
                        <vs-button
                            color="danger"
                            type="filled"
                            to="/administrar/emision"
                            v-if="1"
                            @click="cancelar(1)"
                            >CANCELAR</vs-button
                        >
                        <vs-button
                            color="danger"
                            type="filled"
                            to="/agregar/factura"
                            v-else
                            >CANCELAR</vs-button
                        >
                    </div>
                </div>
                <!--========================END==================================================-->
            </vx-card>
        </div>
    </div>
</template>

<script>
import { log } from "util";
const axios = require("axios");
export default {
    data() {
        return {
            /*************
             * @Variables
             * ***********
             */
            nombre: "",
            codigo: "",
            secuencial_factura: "",
            secuencial_nota_credito: "",
            secuencial_nota_debito: "",
            secuencial_guia: "",
            secuencial_retencion: "",
            radios1: "",
            id_bodega: "",
            secuencial_l: "",
            establecimiento: [],
            establecimientoe: "",
            contenidoemisor: [],
            bodegas: [],
            secuencial_nota_venta:"",
            secuencial_recibo:"",
            //ERRORES
            error: 0,
            errornombre: [],
            errorcodigo: [],
            errorsecuencial_factura: [],
            errorsecuencial_nota_credito: [],
            errorsecuencial_nota_debito: [],
            errorsecuencial_guia: [],
            errorsecuencial_retencion: [],
            errorestablecimiento: [],
            errorsecuencial_l: [],
            errorsecuencial_nota_venta:[],
            asignar: []
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
        /***
         * @function listarestablecimiento
         *  lista estableciomentos
         * @method axios get
         */
        listarestablecimiento() {
            var url = "/api/abrirestablecimiento/" + this.usuario.id_empresa;
            axios.get(url).then(res => {
                this.establecimiento = res.data;
            });
        },
        /**
         * @function guardar
         *Guardar los daros del formulario punto de emision
         *@method axios post
         */
        guardar() {
            if (this.validar()) {
                return;
            }
            axios
                .post("/api/ptoemisiong", {
                    nombre: this.nombre,
                    codigo: this.codigo,
                    secuencial_factura: this.secuencial_factura,
                    secuencial_nota_credito: this.secuencial_nota_credito,
                    secuencial_nota_debito: this.secuencial_nota_debito,
                    secuencial_guia: this.secuencial_guia,
                    secuencial_retencion: this.secuencial_retencion,
                    secuencial_retencion: this.secuencial_retencion,
                    secuencial_l: this.secuencial_l,
                    secuencial_nota_venta: this.secuencial_nota_venta,
                    secuencial_recibo:this.secuencial_recibo,
                    radios1: this.radios1,
                    establecimientoe: this.establecimientoe,
                    id_empresa: this.usuario.id_empresa,
                    id_bodega: this.id_bodega,
                    ucrea:this.usuario.id
                })
                .then(res => {
                    this.$vs.notify({
                        text: "Registro guardado exitosamente",
                        color: "success"
                    });
                    this.$router.push("/administrar/emision");
                })
                .catch(err => {
                    console.log(err);
                });
        },
        cancelar() {
            this.$router.push("/administrar/emision");
        },
        /***
         * @function editar
         * editar datos
         * @method axios put
         */
        editar() {
            if (this.validar()) {
                return;
            }
            axios
                .put("/api/actualizarpt", {
                    id: this.$route.params.id,
                    nombre: this.nombre,
                    codigo: this.codigo,
                    secuencial_factura: this.secuencial_factura,
                    secuencial_nota_credito: this.secuencial_nota_credito,
                    secuencial_nota_debito: this.secuencial_nota_debito,
                    secuencial_guia: this.secuencial_guia,
                    secuencial_retencion: this.secuencial_retencion,
                    secuencial_l: this.secuencial_l,
                    secuencial_nota_venta:this.secuencial_nota_venta,
                    secuencial_recibo:this.secuencial_recibo,
                    radios1: this.radios1,
                    establecimientoe: this.establecimientoe,
                    id_empresa: this.usuario.id_empresa,
                    id_bodega: this.id_bodega,
                    umodifica:this.usuario.id
                })
                .then(res => {
                    this.$vs.notify({
                        text: "Registro actualizado exitosamente",
                        color: "primary"
                    });
                    this.$router.push("/administrar/emision");
                })
                .catch(err => {
                    console.log(err);
                });
        },
        /***
         * @function listar
         * listar datos de punto de emisio
         * @method axios put
         */
        listar() {
            if (this.$route.params.id) {
                this.idrecupera = this.$route.params.id;
                var url = "/api/abrirpt";
                axios.put(url, { id: this.idrecupera }).then(res => {
                    let data = res.data.recupera[0];
                    this.nombre = data.nombre;
                    this.codigo = data.codigo;
                    this.secuencial_factura = data.secuencial_factura;
                    this.secuencial_nota_credito = data.secuencial_nota_credito;
                    this.secuencial_nota_debito = data.secuencial_nota_debito;
                    this.secuencial_guia = data.secuencial_guia_remision;
                    this.secuencial_retencion = data.secuencial_retencion;
                    this.radios1 = data.activo;
                    this.establecimientoe = data.id_establecimiento;
                    this.secuencial_l = data.secuencial_liquidacion_compra;
                    this.secuencial_nota_venta = data.secuencial_nota_venta;
                    this.secuencial_recibo=data.secuencial_recibo;
                    this.id_bodega = data.id_bodega;
                });
            } else {
                this.idrecupera = null;
            }
        },
        borrar() {
            this.nombre = "";
            this.codigo = "";
            this.secuencial_factura = "";
            this.secuencial_nota_credito = "";
            this.secuencial_nota_debito = "";
            this.secuencial_guia = "";
            this.secuencial_retencion = "";
            this.radios1 = "";
            this.establecimiento = "";
            this.id_bodega = "";
        },
        /**
         *@function validar
         *  valida los campos del formulario
         */
        validar() {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            this.error = 0;
            this.errornombre = [];
            this.errorcodigo = [];
            this.errorsecuencial_factura = [];
            this.errorsecuencial_nota_credito = [];
            this.errorsecuencial_nota_debito = [];
            this.errorsecuencial_guia = [];
            this.errorsecuencial_retencion = [];
            //this.errorsecuencial_nota_venta=[];
            this.errorestablecimiento = [];
            if (!this.nombre) {
                this.errornombre.push("Campo obligatorio");
                this.error = 1;
            }
            if (!this.codigo) {
                this.errorcodigo.push("Campo obligatorio");
                this.error = 1;
            } else if (this.codigo.length != 3) {
                this.errorcodigo.push("Debe tener 3 dígitos");
                this.error = 1;
            }
            if (!this.secuencial_factura) {
                this.errorsecuencial_factura.push("Campo obligatorio");
                this.error = 1;
            } else if (this.codigo.length != 3) {
                this.errorsecuencial_factura.push("Debe tener 9 dígitos");
                this.error = 1;
            }

            if (!this.secuencial_nota_credito) {
                this.errorsecuencial_nota_credito.push("Campo obligatorio");
                this.error = 1;
            }
            if (!this.secuencial_nota_debito) {
                this.errorsecuencial_nota_debito.push("Campo obligatorio");
                this.error = 1;
            }
            if (!this.secuencial_guia) {
                this.errorsecuencial_guia.push("Campo obligatorio");
                this.error = 1;
            }
            if (!this.secuencial_retencion) {
                this.errorsecuencial_retencion.push("Campo obligatorio");
                this.error = 1;
            }
            if (!this.establecimientoe) {
                this.errorestablecimiento.push("Campo obligatorio");
                this.error = 1;
            }
            if (
                this.errornombre ||
                this.errorcodigo ||
                this.errorsecuencial_factura ||
                this.errorsecuencial_nota_credito ||
                this.errorsecuencial_nota_debito ||
                this.errorsecuencial_guia ||
                this.errorsecuencial_retencion ||
                this.errorestablecimiento 
            )
                return this.error;
        },
        /**
         * @function solonumeros
         * permite el ingreso de solo numeros
         */
        solonumeros: function($event) {
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
        /**
         * @function listarBodega
         * Trae todas las bodegas
         */
        listarBodega() {
            var url = "/api/bodegaall/" + this.usuario.id_empresa;
            axios
                .get(url)
                .then(resp => {
                    this.bodegas = resp.data;
                })
                .catch(err => {});
        }
    },
    mounted() {
        if (this.$route.params.id) {
            var id = this.$route.params.id;
            this.listar();
        }
        this.listarestablecimiento();
        this.listarBodega();
    }
};
</script>
