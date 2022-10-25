<template>
    <vx-card title="Agregar Fórmnula de Producción">
        <div class="vx-row">
            <vs-divider position="left">Información de Fórmula</vs-divider>
            <div class="vx-col md:w-1/5 sm:w-full w-full mb-6">
                <vs-input
                    disabled
                    class="w-full txt-center"
                    label="Código de Producción:"
                    v-model="cod_pro"
                />
                <div v-show="error" v-if="!cod_pro">
                    <div
                        v-for="err in errorcod_pro"
                        :key="err"
                        v-text="err"
                        class="text-danger"
                    ></div>
                </div>
            </div>

            <div class="vx-col md:w-3/5 sm:w-full w-full mb-6">
                <vs-input
                    class="w-full"
                    label="Nombre de Fórmula:"
                    v-model="nom_pro"
                />
                <div v-show="error" v-if="!nom_pro">
                    <div
                        v-for="err in errornom_pro"
                        :key="err"
                        v-text="err"
                        class="text-danger"
                    ></div>
                </div>
            </div>
            <vs-divider />
            <vs-divider position="left">Productos a Producir</vs-divider>
            <!--Inbicio Agregar Productos-->
            <!-- INVOICE TASKS TABLE -->
            <a
                class="flex items-center cursor-pointer mb-4 ml-4 mr-4"
                @click="abrirproductos()"
                >Añadir Productos</a
            >
            <div v-show="error" v-if="!continprod.length">
                <div
                    v-for="err in errorproduct"
                    :key="err"
                    v-text="err"
                    class="text-danger"
                ></div>
            </div>
            <div class="vx-col md:w-full sm:w-full w-full mb-6">
                <vs-table :data="continprod">
                    <template slot="thead">
                        <vs-th>Código Producto</vs-th>
                        <vs-th class="text-center">Nombre Producto</vs-th>
                        <vs-th class="text-center">
                            <!--<feather-icon disabled icon="TrashIcon" svgClasses="w-5 h-5 stroke-current" />-->
                        </vs-th>
                    </template>
                    <template slot-scope="{ data }">
                        <vs-tr :key="indextr" v-for="(tr, indextr) in data">
                            <vs-td
                                style="width:30%!important;"
                                v-if="data[indextr].cod_alterno"
                                >{{ data[indextr].cod_alterno }}</vs-td
                            ><vs-td style="width:30%!important;" v-else>{{
                                data[indextr].cod_principal
                            }}</vs-td>
                            <vs-td
                                class="text-center"
                                style="width:50%!important;"
                                :data="data[indextr].nombre"
                                >{{ data[indextr].nombre }}</vs-td
                            >
                            <vs-td
                                class="text-center"
                                style="width=20%!important;"
                            >
                                <vx-tooltip
                                    text="Eliminar"
                                    position="top"
                                    style="display: inline-flex;"
                                >
                                    <feather-icon
                                        icon="TrashIcon"
                                        svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                        class="pointer"
                                        @click="eliminarp(indextr)"
                                    />
                                </vx-tooltip>
                            </vs-td>
                        </vs-tr>
                    </template>
                </vs-table>
            </div>

            <!-- Inicio Agregar Ingredientes-->
            <vs-divider />
            <vs-divider position="left">Ingredientes de Producto</vs-divider>
            <!-- INVOICE TASKS TABLE -->
            <a
                class="flex items-center cursor-pointer mb-4 ml-4 mr-4"
                @click="abriringredientes()"
                >Añadir Ingredientes</a
            >
            <div v-show="error" v-if="!contingred.length">
                <div
                    v-for="err in erroringred"
                    :key="err"
                    v-text="err"
                    class="text-danger"
                ></div>
            </div>
            <div class="vx-col md:w-full sm:w-full w-full mb-6">
                <vs-table :data="contingred">
                    <template slot="thead">
                        <vs-th>Código Producto</vs-th>
                        <vs-th class="text-center">Nombre Ingrediente</vs-th>
                        <vs-th class="text-center"
                            >Catidad por Unidad de Formula</vs-th
                        >
                        <vs-th class="text-center">
                            <!--<feather-icon disabled icon="TrashIcon" svgClasses="w-5 h-5 stroke-current" />-->
                        </vs-th>
                    </template>

                    <template slot-scope="{ data }">
                        <vs-tr :key="indextr" v-for="(tr, indextr) in data">
                            <vs-td
                                style="width:30%!important;"
                                v-if="data[indextr].cod_alterno"
                                >{{ data[indextr].cod_alterno }}</vs-td
                            ><vs-td style="width:30%!important;" v-else>{{
                                data[indextr].cod_principal
                            }}</vs-td>
                            <vs-td
                                class="text-center"
                                style="width:50%!important;"
                                :data="data[indextr].nombre"
                                >{{ data[indextr].nombre }}</vs-td
                            >

                            <vs-td
                                class="text-center"
                                style="width:20%!important;"
                                :data="data[indextr].cant_form"
                            >
                                <vs-input
                                    class="w-full txt-center"
                                    placeholder="0.000000"
                                    v-model="tr.cant_form"
                                />
                                <div v-show="error" v-if="!tr.cant_form">
                                    <div
                                        v-for="err in tr.errorcant_form"
                                        :key="err"
                                        v-text="err"
                                        class="text-danger"
                                    ></div>
                                </div>
                            </vs-td>

                            <vs-td
                                class="text-center"
                                style="width=10!important;"
                            >
                                <vx-tooltip
                                    text="Eliminar"
                                    position="top"
                                    style="display: inline-flex;"
                                >
                                    <feather-icon
                                        icon="TrashIcon"
                                        svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                        class="pointer"
                                        @click="eliminari(indextr)"
                                    />
                                </vx-tooltip>
                            </vs-td>
                        </vs-tr>
                    </template>
                </vs-table>
            </div>
            <div class="vx-col w-full">
                <vs-button
                    color="success"
                    type="border"
                    @click="guardar()"
                    v-if="!$route.params.id"
                    >Guardar</vs-button
                >
                <vs-button
                    color="success"
                    type="border"
                    @click="editar()"
                    v-else
                    >Guardar</vs-button
                >
                <vs-button color="danger" type="border" to="/produccion/formula"
                    >Cancelar</vs-button
                >
            </div>
            <!-- Popup Agregar Producto-->
            <vs-popup
                classContent="popup-example"
                title="Seleccione el Producto"
                :active.sync="popupprod"
            >
                <div class="vx-col w-full" v-if="tipot == 1">
                    <vs-input
                        class="mb-4 mr-4 w-full"
                        v-model="buscarp"
                        @keyup="listarp(buscarp)"
                        v-bind:placeholder="i18nbuscar"
                    />
                    <vs-table
                        stripe
                        max-items="25"
                        pagination
                        v-model="contenidoingred"
                        @selected="handleSelectedp"
                        :data="arrayprodprodu"
                    >
                        <template slot="thead">
                            <vs-th>Código</vs-th>
                            <vs-th>Nombre</vs-th>
                            <vs-th>Marca</vs-th>
                            <vs-th>Modelo</vs-th>
                            <vs-th>Costo</vs-th>
                        </template>
                        <template slot-scope="{ data }">
                            <vs-tr
                                :data="tr"
                                :key="indextr"
                                v-for="(tr, indextr) in data"
                            >
                                <vs-td v-if="tr.cod_alterno">{{
                                    tr.cod_alterno
                                }}</vs-td
                                ><vs-td v-else>{{ tr.cod_principal }}</vs-td>
                                <vs-td v-if="tr.nombre">{{ tr.nombre }}</vs-td>
                                <vs-td v-else>-</vs-td>
                                <vs-td v-if="tr.nombremarca">{{
                                    tr.nombremarca
                                }}</vs-td>
                                <vs-td v-else>-</vs-td>
                                <vs-td v-if="tr.nombremodelo">{{
                                    tr.nombremodelo
                                }}</vs-td>
                                <vs-td v-else>-</vs-td>
                                <vs-td v-if="tr.costo_total">{{
                                    tr.costo_total
                                }}</vs-td>
                                <vs-td v-else>-</vs-td>
                            </vs-tr>
                        </template>
                    </vs-table>
                </div>
                <div class="vx-col w-full" v-if="tipot == 2">
                    <vs-input
                        class="mb-4 mr-4 w-full"
                        v-model="buscari"
                        @keyup="listari(buscari)"
                        v-bind:placeholder="i18nbuscar"
                    />
                    <vs-table
                        stripe
                        max-items="25"
                        pagination
                        v-model="contenidoingred"
                        @selected="handleSelectedi"
                        :data="arrayingrediente"
                    >
                        <template slot="thead">
                            <vs-th>Código</vs-th>
                            <vs-th>Nombre</vs-th>
                            <vs-th>Descripcion</vs-th>
                            <vs-th>Marca</vs-th>
                            <vs-th>Modelo</vs-th>
                            <vs-th>Costo</vs-th>
                        </template>
                        <template slot-scope="{ data }">
                            <vs-tr
                                :data="tr"
                                :key="indextr"
                                v-for="(tr, indextr) in data"
                            >
                                <vs-td v-if="tr.cod_alterno">{{
                                    tr.cod_alterno
                                }}</vs-td
                                ><vs-td v-else>{{ tr.cod_principal }}</vs-td>
                                <vs-td v-if="tr.nombre">{{ tr.nombre }}</vs-td>
                                <vs-td v-else>-</vs-td>
                                <vs-td v-if="tr.descripcion">{{
                                    tr.descripcion
                                }}</vs-td>
                                <vs-td v-else>-</vs-td>
                                <vs-td v-if="tr.nombremarca">{{
                                    tr.nombremarca
                                }}</vs-td>
                                <vs-td v-else>-</vs-td>
                                <vs-td v-if="tr.nombremodelo">{{
                                    tr.nombremodelo
                                }}</vs-td>
                                <vs-td v-else>-</vs-td>
                                <vs-td v-if="tr.costo_total">{{
                                    tr.costo_total
                                }}</vs-td>
                                <vs-td v-else>-</vs-td>
                            </vs-tr>
                        </template>
                    </vs-table>
                </div>
            </vs-popup>
        </div>
    </vx-card>
</template>

<script>
import vSelect from "vue-select";
const axios = require("axios");
const $ = require("jquery");

export default {
    components: {
        "v-select": vSelect
    },
    computed: {
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        }
    },

    data() {
        return {
            //variables produccion
            cod_pro: "",
            nom_pro: "",
            cant_form: "",
            //popups
            popupprod: false,
            //buscar
            buscarp: "",
            buscari: "",
            i18nbuscar: this.$t("i18nbuscar"),

            //variables recuperacion
            //idrecupera: null,

            //arrays
            arrayingrediente: [],
            arrayprodprodu: [],
            contenidoingred: [],
            continprod: [],
            contingred: [],
            tipot: 0,
            //validacion errores
            error: 0,
            errorcod_pro: [],
            errornom_pro: [],
            errorproduct: [],
            erroringred: [],
            errorcant_form: []
        };
    },
    methods: {
        listarcodprod() {
            if (!this.$route.params.id) {
                var url = "/api/codfomr/" + this.usuario.id_empresa;
                axios.get(url).then(res => {
                    this.cod_pro = res.data.codigo_produccion;
                });
            }
        },
        listar() {
            if (this.$route.params.id) {
                this.idrecupera = this.$route.params.id;
                var url = "/api/traerformula/" + this.idrecupera;
                axios.get(url).then(res => {
                    this.cod_pro = res.data.codigo_produccion;
                    this.nom_pro = res.data.nombre_form;
                    this.listaringred();
                    this.listarprod();
                });
            } else {
                this.idrecupera = null;
            }
        },
        listarprod() {
            var url = "/api/traerformprod/" + this.idrecupera;
            axios
                .get(url)
                .then(res => {
                    this.continprod = res.data.datos;
                })
                .catch(err => {
                    console.log(err);
                });
        },
        listaringred() {
            var url = "/api/traerformingred/" + this.idrecupera;
            axios
                .get(url)
                .then(res => {
                    this.contingred = res.data.datos;
                })
                .catch(err => {
                    console.log(err);
                });
        },

        listarp(buscarp) {
            var url =
                "/api/traerformulaproduct/" +
                this.usuario.id_empresa +
                "?buscar=" +
                buscarp;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.arrayprodprodu = respuesta.recupera;
            });
        },
        listari(buscari) {
            var url =
                "/api/traerformulaingred/" +
                this.usuario.id_empresa +
                "?buscar=" +
                buscari;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.arrayingrediente = respuesta.recupera;
            });
        },

        guardar() {
            if (this.validar()) {
                return;
            }
            axios
                .post("/api/agregarformula", {
                    //formula
                    codigo_produccion: this.cod_pro,
                    nombre_form: this.nom_pro,
                    id_empresa: this.usuario.id_empresa,
                    //productos
                    productos: this.continprod,
                    ingredientes: this.contingred
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Fórmula Guardada",
                        text: "Fórmula agregada con éxito",
                        color: "success"
                    });
                    this.$router.push("/produccion/formula");
                });
        },

        editar() {
            if (this.validar()) {
                return;
            }
            axios
                .put("/api/editarformula", {
                    //formula
                    id_formula_produccion: this.$route.params.id,
                    codigo_produccion: this.cod_pro,
                    nombre_form: this.nom_pro,
                    id_empresa: this.usuario.id_empresa,
                    //productos
                    productos: this.continprod,
                    ingredientes: this.contingred
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Fórmula Editata",
                        text: "Fórmula editada con éxito",
                        color: "success"
                    });
                    this.$router.push("/produccion/formula");
                });
        },
        abrirproductos() {
            this.popupprod = true;
            this.tipot = 1;
        },
        abriringredientes() {
            this.popupprod = true;
            this.tipot = 2;
        },
        handleSelectedp(tr) {
            this.popupprod = false;
            this.continprod.push({
                id: tr.id_producto,
                cod_alterno: tr.cod_alterno,
                cod_principal: tr.cod_principal,
                nombre: tr.nombre
            });
        },
        handleSelectedi(tr) {
            this.popupprod = false;
            this.contingred.push({
                id: tr.id_producto,
                cod_alterno: tr.cod_alterno,
                cod_principal: tr.cod_principal,
                nombre: tr.nombre,
                cant_form: null
            });
        },
        eliminarp(id) {
            this.continprod.splice(id, 1);
        },
        eliminari(id) {
            this.contingred.splice(id, 1);
        },
        //validaciones
        validar() {
            this.error = 0;

            this.errorcod_pro = [];
            this.errornom_pro = [];
            this.errorproduct = [];
            this.erroringred = [];
            this.errorcant_form = [];

            if (!this.cod_pro) {
                this.errorcod_pro.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }
            if (!this.nom_pro) {
                this.errornom_pro.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }
            if (!this.continprod.length) {
                this.errorproduct.push("Debe añadir almenos un producto");
                this.error = 1;
                window.scrollTo(0, 0);
            }
            if (!this.contingred.length) {
                this.erroringred.push("Debe añadir almenos un ingrediente");
                this.error = 1;
                window.scrollTo(0, 0);
            }

            for (var i = 0; i < this.contingred.length; i++) {
                this.contingred[i].errorcant_form = [];
                if (!this.contingred[i].cant_form) {
                    this.contingred[i].errorcant_form.push("Campo obligatorio");
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
            }

            return this.error;
        },
        //validacion de numeros
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
        }
    },
    mounted() {
        this.listarcodprod();
        this.listar();
        this.listarp(this.buscarp);
        this.listari(this.buscari);
    }
};
</script>
<style lang="scss">
.txt-center > div > input {
    text-align: center;
}
.text-center > .vs-table-text {
    text-align: center !important;
    display: block;
}
.theme-dark .vx-card select {
    background: #262c49;
}
.vs-popup {
    width: 1060px !important;
}
</style>
