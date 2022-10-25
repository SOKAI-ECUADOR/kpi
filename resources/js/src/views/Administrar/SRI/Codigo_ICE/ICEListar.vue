<template>
    <vx-card class="mt-10">
        <div class="flex flex-wrap justify-between items-center mb-3">
            <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"></div>
            <div
                class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"
            >
                <vs-input
                    class="mb-4 md:mb-0 mr-4"
                    v-model="buscar"
                    @keyup="listarice(buscar)"
                    v-bind:placeholder="i18nbuscar"
                />
                <div class="dropdown-button-container mr-3">
                    <vs-dropdown>
                        <vs-button
                            class="btn-drop"
                            type="filled"
                            icon="settings"
                            style="border-radius: 5px;"
                        ></vs-button>
                        <vs-dropdown-menu style="width: 13em;">
                            <vs-dropdown-item
                                class="text-center"
                                divider
                                @click="
                                    (popupformulaice = true),
                                        listarformulaice(buscarformula)
                                "
                                >Fórmulas de ICE</vs-dropdown-item
                            >
                        </vs-dropdown-menu>
                    </vs-dropdown>
                </div>
                <div class="dropdown-button-container" v-if="crearrol">
                    <vs-button
                        class="btnx"
                        type="filled"
                        @click="abrir('agregar')"
                        >Agregar</vs-button
                    >
                    <vs-dropdown>
                        <vs-button
                            class="btn-drop"
                            type="filled"
                            icon="expand_more"
                        ></vs-button>
                        <vs-dropdown-menu style="width:13em;">
                            <vs-dropdown-item class="text-center" divider
                                >Importar registros</vs-dropdown-item
                            >
                            <vs-dropdown-item class="text-center"
                                >Exportar registros</vs-dropdown-item
                            >
                        </vs-dropdown-menu>
                    </vs-dropdown>
                </div>
            </div>
        </div>
        <vs-table stripe :data="contenido">
            <template slot="thead">
                <vs-th>Código</vs-th>
                <vs-th>Nombre</vs-th>
                <vs-th>Valor</vs-th>
                <vs-th>Fórmula</vs-th>
                <vs-th class="text-center">Opciones</vs-th>
            </template>
            <template slot-scope="{ data }">
                <vs-tr :key="datos.codigo" v-for="datos in data">
                    <vs-td v-if="datos.codigo">{{ datos.codigo }} </vs-td>
                    <vs-td v-else>-</vs-td>
                    <vs-td v-if="datos.nombre">{{ datos.nombre }}</vs-td>
                    <vs-td v-else>-</vs-td>
                    <vs-td v-if="datos.valor">{{ datos.valor }}</vs-td>
                    <vs-td v-else>-</vs-td>
                    <vs-td v-if="datos.formula">{{ datos.formula }}</vs-td>
                    <vs-td v-else>-</vs-td>
                    <vs-td class="whitespace-no-wrap text-center">
                        <vx-tooltip
                            text="Editar Código ICE"
                            position="top"
                            style="display: inline-flex;"
                        >
                            <feather-icon
                                v-if="editarrol"
                                icon="EditIcon"
                                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                class="pointer"
                                @click.stop="abrir('editar', datos)"
                            />
                        </vx-tooltip>
                        <vx-tooltip
                            text="Borrar Código ICE"
                            position="top"
                            style="display: inline-flex;"
                        >
                            <feather-icon
                                v-if="eliminarrol"
                                icon="TrashIcon"
                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                class="ml-2 pointer"
                                @click.stop="
                                    (ideliminarice = datos.id_ice),
                                        (eliminar = true)
                                "
                            />
                        </vx-tooltip>
                    </vs-td>
                </vs-tr>
            </template>
        </vs-table>
        <!--Popup Agregar ICE-->
        <vs-popup title="Formulario ICE" :active.sync="popupActive">
            <div class="vx-col sm:w-full w-full mb-6">
                <div class="vx-row">
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Código:"
                            v-model="codigo"
                        />
                        <div v-show="error" v-if="!codigo">
                            <div
                                v-for="err in errorcodigo"
                                :key="err"
                                v-text="err"
                                class="text-danger"
                            ></div>
                        </div>
                    </div>
                    <div class="vx-col sm:w-2/3 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Nombre:"
                            v-model="nombre"
                        />
                        <div v-show="error" v-if="!nombre">
                            <div
                                v-for="err in errornombre"
                                :key="err"
                                v-text="err"
                                class="text-danger"
                            ></div>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Valor ICE"
                            v-model="valor"
                        />
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-select
                            placeholder="Seleccione uno"
                            class="selectExample w-full"
                            label="Fórmula de Cálculo:"
                            vs-multiple
                            v-model="formula"
                        >
                            <vs-select-item
                                :key="res.id_ice_formula"
                                :value="res.id_ice_formula"
                                :text="res.nombre"
                                v-for="res in contselectformulaice"
                            />
                        </vs-select>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <label class="vs-input--label">Cuenta Contable</label>
                        <vx-input-group>
                            <vs-input
                                disabled
                                class="w-full"
                                v-model="cta_cont"
                            />
                            <template slot="append">
                                <div class="append-text btn-addon">
                                    <vs-button
                                        color="primary"
                                        @click="
                                            popupcuentacontable = true;
                                            listarcuenta(buscarccontable);
                                        "
                                        >Buscar</vs-button
                                    >
                                </div>
                            </template>
                        </vx-input-group>
                    </div>
                    <div class="vx-col sm:w-full w-full mb-3">
                        <label class="vs-input--label">Observación:</label>
                        <vs-textarea v-model="observacion" rows="3" />
                    </div>
                    <div class="vx-row sm:w-full w-full">
                        <div
                            class="vx-col w-1/3 ml-auto mt-6 text-center"
                            v-if="id_ice == null"
                        >
                            <vs-button
                                color="success"
                                type="filled"
                                @click="guardarice()"
                                >Guardar</vs-button
                            >
                        </div>
                        <div
                            class="vx-col w-1/3 ml-auto mt-6 text-center"
                            v-else
                        >
                            <vs-button
                                color="success"
                                type="filled"
                                @click="editarice()"
                                >Guardar</vs-button
                            >
                        </div>
                        <div class="vx-col w-1/3 mr-auto mt-6 text-center">
                            <vs-button
                                color="danger"
                                type="filled"
                                @click="(popupActive = false), cancelar()"
                                >Cancelar</vs-button
                            >
                        </div>
                    </div>
                </div>
            </div>
            <!-- Popup cuenta contable -->
            <vs-popup
                title="Plan de Cuentas"
                class="peque"
                :active.sync="popupcuentacontable"
            >
                <div class="con-exemple-prompt">
                    <vs-input
                        class="mb-4 md:mb-0 mr-4 w-full"
                        v-model="buscarccontable"
                        @keyup="listarcuenta(buscarccontable)"
                        v-bind:placeholder="i18nbuscar"
                    />
                    <vs-table
                        stripe
                        @selected="selectcta"
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
        </vs-popup>
        <!-- Eliminar popup ICE-->
        <vs-popup
            title="Eliminar Registro"
            :class="'peque'"
            :active.sync="eliminar"
        >
            <vx-card>
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        <div class="vx-col sm:w-full w-full mb-6 text-center">
                            <label class="text-center">
                                Esta seguro que desea eliminar este registro
                                <br />¡Se eliminará de forma permanente!
                            </label>
                        </div>
                        <div class="vx-col sm:w-full w-full text-center">
                            <vs-button
                                color="danger"
                                type="filled"
                                @click="eliminarice(ideliminarice)"
                                >Eliminar Código ICE</vs-button
                            >
                        </div>
                    </div>
                </div>
            </vx-card>
        </vs-popup>
        <!-- Eliminar popup FORMULA -->
        <vs-popup
            title="Eliminar Registro"
            :class="'peque'"
            :active.sync="eliminarformice"
        >
            <vx-card>
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        <div class="vx-col sm:w-full w-full mb-6 text-center">
                            <label class="text-center">
                                Esta seguro que desea eliminar este registro
                                <br />¡Se eliminará de forma permanente!
                            </label>
                        </div>
                        <div class="vx-col sm:w-full w-full text-center">
                            <vs-button
                                color="danger"
                                type="filled"
                                @click="eliminarformulaice(ideliminarformula)"
                                >Eliminar Fórmula ICE</vs-button
                            >
                        </div>
                    </div>
                </div>
            </vx-card>
        </vs-popup>
        <!--Popup Listar Formula ICE-->
        <vs-popup
            title="Fórmula para Calcular ICE"
            :active.sync="popupformulaice"
            ><vx-card class="mt-1">
                <div class="flex flex-wrap justify-between items-center mb-3">
                    <div
                        class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"
                    ></div>
                    <div
                        class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"
                    >
                        <vs-input
                            class="mb-4 md:mb-0 mr-4"
                            v-model="buscarformula"
                            @keyup="listarformulaice(buscarformula)"
                            v-bind:placeholder="i18nbuscar"
                        />
                        <div class="button-container" v-if="crearrol">
                            <vs-button
                                class="btnx"
                                type="filled"
                                @click="
                                    abrirformula('agregar'), listcodformice()
                                "
                                >Agregar Fórmula</vs-button
                            >
                        </div>
                    </div>
                </div>
                <vs-table stripe :data="contenidoformula">
                    <template slot="thead">
                        <vs-th>Código</vs-th>
                        <vs-th>Nombre</vs-th>
                        <vs-th>Fórmula</vs-th>
                        <vs-th class="text-center">Opciones</vs-th>
                    </template>
                    <template slot-scope="{ data }">
                        <vs-tr :key="datos.codigo" v-for="datos in data">
                            <vs-td v-if="datos.codigo"
                                >{{ datos.codigo }}
                            </vs-td>
                            <vs-td v-else>-</vs-td>
                            <vs-td v-if="datos.nombre">{{
                                datos.nombre
                            }}</vs-td>
                            <vs-td v-else>-</vs-td>
                            <vs-td v-if="datos.formula">{{
                                datos.formula
                            }}</vs-td>
                            <vs-td v-else>-</vs-td>
                            <vs-td class="whitespace-no-wrap text-center">
                                <vx-tooltip
                                    text="Editar Fórmula ICE"
                                    position="top"
                                    style="display: inline-flex;"
                                >
                                    <feather-icon
                                        icon="EditIcon"
                                        svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                        class="pointer"
                                        @click.stop="
                                            abrirformula('editar', datos)
                                        "
                                    />
                                </vx-tooltip>
                                <vx-tooltip
                                    text="Borrar Fórmula ICE"
                                    position="top"
                                    style="display: inline-flex;"
                                >
                                    <feather-icon
                                        icon="TrashIcon"
                                        svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                        class="ml-2 pointer"
                                        @click.stop="
                                            (ideliminarformula =
                                                datos.id_ice_formula),
                                                (eliminarformice = true)
                                        "
                                    />
                                </vx-tooltip>
                            </vs-td>
                        </vs-tr>
                    </template> </vs-table
            ></vx-card>
            <!--popup agregar o editar formula ice-->
            <vs-popup
            class="mediano"
                title="Formulario Fórmula ICE"
                :active.sync="popupformulaiceagregar"
            >
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/3 w-full mb-6">
                            <vs-input
                                disabled
                                class="w-full txt-center"
                                label="Código:"
                                v-model="codigoform"
                            />
                            <div v-show="error" v-if="!codigoform">
                                <div
                                    v-for="err in errorcodigoform"
                                    :key="err"
                                    v-text="err"
                                    class="text-danger"
                                ></div>
                            </div>
                        </div>
                        <div class="vx-col sm:w-2/3 w-full mb-6">
                            <vs-input
                                class="w-full"
                                label="Nombre:"
                                v-model="nombreform"
                            />
                            <div v-show="error" v-if="!nombreform">
                                <div
                                    v-for="err in errornombreform"
                                    :key="err"
                                    v-text="err"
                                    class="text-danger"
                                ></div>
                            </div>
                        </div>
                        <div class="vx-col sm:w-full w-full mb-6">
                            <label class="vs-input--label"
                                >Fórmula de Cálculo ICE:</label
                            >
                            <v-select
                                multiple
                                :closeOnSelect="false"
                                v-model="iceformulaform"
                                :options="option"
                                @input="selecrformulaice(iceformulaform)"
                            />
                        </div>
                        <div class="vx-row sm:w-full w-full">
                            <div
                                class="vx-col w-1/3 ml-auto mt-6 text-center"
                                v-if="id_ice_formula == null"
                            >
                                <vs-button
                                    color="success"
                                    type="filled"
                                    @click="guardarformulaice()"
                                    >Guardar</vs-button
                                >
                            </div>
                            <div
                                class="vx-col w-1/3 ml-auto mt-6 text-center"
                                v-else
                            >
                                <vs-button
                                    color="success"
                                    type="filled"
                                    @click="editarformulaice()"
                                    >Guardar</vs-button
                                >
                            </div>
                            <div class="vx-col w-1/3 mr-auto mt-6 text-center">
                                <vs-button
                                    color="danger"
                                    type="filled"
                                    @click="
                                        (popupformulaiceagregar = false),
                                            cancelar()
                                    "
                                    >Cancelar</vs-button
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </vs-popup>
        </vs-popup>
    </vx-card>
</template>

<script>
import { AgGridVue } from "ag-grid-vue";
import $ from "jquery";
import vSelect from "vue-select";
const axios = require("axios");
export default {
    components: {
        AgGridVue,
        "v-select": vSelect
    },
    data() {
        return {
            //mapeo de datos
            //paginacion
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
            offset: 3,
            //buscador
            buscar: "",
            buscarformula: "",
            buscarccontable: "",
            //otros valores
            contenido: [],
            contenidoformula: [],
            contselectformulaice: [],
            contenidocuenta: [],
            eliminar: false,
            eliminarformice: false,
            //lenguaje
            i18nbuscar: this.$t("i18nbuscar"),
            //popup agregar ice
            popupActive: false,
            //popup listar formula ice
            popupformulaice: false,
            //popup editar guardar formula ice
            popupformulaiceagregar: false,
            //popu listar plan de cuentas
            popupcuentacontable: false,
            //variables ice
            id_ice: null,
            codigo: "",
            nombre: "",
            valor: "",
            formula: "",
            id_cta_cont: "",
            cta_cont: "",
            observacion: "",
            //variables formula ice
            id_ice_formula: null,
            codigoform: "",
            nombreform: "",
            iceformulaform: "",
            //variables elemento de seleccion de fomrula de ice
            option: [
                { id: 1, label: "+" },
                { id: 2, label: "-" },
                { id: 3, label: "*" },
                { id: 4, label: "/" },
                { id: 5, label: "(" },
                { id: 6, label: ")" },
                { id: 7, label: "Valor_ICE" },
                { id: 8, label: "Grados_de_alcohol" },
                { id: 9, label: "Numero_unidad" }
            ],
            //variables validacion
            error: 0,
            errorcodigo: [],
            errornombre: [],
            //variables validacion formula
            errorformula: 0,
            errorcodigoform: [],
            errornombreform: []
        };
    },
    computed: {
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        },
        crearrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[16].crear;
            }
            return res;
        },
        editarrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[16].editar;
            }
            return res;
        },
        eliminarrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[16].eliminar;
            }
            return res;
        }
    },
    methods: {
        /* ------------------------------------FUNCIONES CODIGO DE ICE------------------------------------*/
        listarice(buscar) {
            var url =
                "/api/listice/" + this.usuario.id_empresa + "?buscar=" + buscar;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenido = respuesta.recupera;
            });
        },
        listselectformice() {
            var url = "/api/iceformula/" + this.usuario.id_empresa;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contselectformulaice = respuesta;
            });
        },
        //lista cotenido de cuenta contable
        listarcuenta(buscarccontable) {
            axios
                .get(
                    "/api/selcuentas/" +
                        this.usuario.id_empresa +
                        "?buscar=" +
                        buscarccontable
                )
                .then(res => {
                    this.contenidocuenta = res.data.recupera;
                });
        },
        selectcta(tr) {
            this.id_cta_cont = `${tr.id_plan_cuentas}`;
            this.cta_cont = `${tr.nomcta}`;
            this.popupcuentacontable = false;
        },
        guardarice() {
            if (this.validar()) {
                return;
            }
            axios
                .post("/api/guardarice", {
                    codigo: this.codigo,
                    nombre: this.nombre,
                    valor: this.valor,
                    formula: this.formula,
                    observacion: this.observacion,
                    id_plan_cuentas: this.id_cta_cont,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    (this.popupActive = false), this.listarice(this.buscar);
                })
                .catch(err => {});
        },
        abrir(tipo, datos) {
            this.listselectformice();
            this.popupActive = true;
            switch (tipo) {
                case "agregar": {
                    this.id_ice = null;
                    this.codigo = "";
                    this.nombre = "";
                    this.valor = "";
                    this.formula = "";
                    this.id_cta_cont = "";
                    this.cta_cont = "";
                    this.observacion = "";
                    break;
                }
                case "editar": {
                    this.id_ice = datos.id_ice;
                    this.codigo = datos.codigo;
                    this.nombre = datos.nombre;
                    this.valor = datos.valor;
                    this.formula = datos.id_ice_formula;
                    this.id_cta_cont = datos.id_plan_cuentas;
                    this.cta_cont = datos.cta_cont;
                    this.observacion = datos.observacion;
                    break;
                }
            }
        },
        editarice() {
            if (this.validar()) {
                return;
            }
            axios
                .post("/api/editarice", {
                    id: this.id_ice,
                    codigo: this.codigo,
                    nombre: this.nombre,
                    valor: this.valor,
                    formula: this.formula,
                    observacion: this.observacion,
                    id_plan_cuentas: this.id_cta_cont,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Edición Exitosa",
                        text: "Registro editado con éxito",
                        color: "success"
                    });
                    this.popupActive = false;
                    this.listarice(this.buscar);
                    this.id_ice = null;
                })
                .catch(err => {});
        },
        eliminarice(id) {
            axios.delete("/api/eliminarice/" + id);
            this.$vs.notify({
                title: "Registro eliminado",
                text: "Este registro ha sido eliminado exitosamente",
                color: "success"
            });
            this.eliminar = false;
            this.listarice(this.buscar);
        },
        /* ------------------------------------FUNCIONES FORMULA DE ICE------------------------------------*/
        //Funcion Lista codigo de formula ice
        listcodformice() {
            var url = "/api/codformice/" + this.usuario.id_empresa;
            axios.get(url).then(res => {
                this.codigoform = res.data.codigo_formula_ice;
            });
        },
        listarformulaice(buscarformula) {
            var url =
                "/api/listiceformula/" +
                this.usuario.id_empresa +
                "?buscar=" +
                buscarformula;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidoformula = respuesta.recupera;
            });
        },
        abrirformula(tipo, datos) {
            this.popupformulaiceagregar = true;
            switch (tipo) {
                case "agregar": {
                    this.id_ice_formula = null;
                    this.codigoform = "";
                    this.nombreform = "";
                    this.iceformulaform = "";
                    break;
                }
                case "editar": {
                    this.id_ice_formula = datos.id_ice_formula;
                    this.codigoform = datos.codigo;
                    this.nombreform = datos.nombre;
                    this.iceformulaform = datos.formula;
                    break;
                }
            }
        },
        guardarformulaice() {
            /* if (this.validarformula()) {
                return;
            }*/
            //transforma array de formula en string
            var formstring = [];
            this.iceformulaform.forEach(element => {
                formstring.push(element.label);
            });

            axios
                .post("/api/guardariceformula", {
                    codigo: this.codigoform,
                    nombre: this.nombreform,
                    formula: formstring.join(" "),
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.popupformulaiceagregar = false;
                    this.listarformulaice(this.buscarformula);
                })
                .catch(err => {});
        },
        editarformulaice() {
            /*if (this.validar()) {
                return;
            }*/
            //transforma array de formula en string
            var formstring = [];
            this.iceformulaform.forEach(element => {
                formstring.push(element.label);
            });

            axios
                .post("/api/editariceformula", {
                    id: this.id_ice_formula,
                    codigo: this.codigoform,
                    nombre: this.nombreform,
                    formula: formstring.join(" "),
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Edición Exitosa",
                        text: "Registro editado con éxito",
                        color: "success"
                    });
                    this.popupformulaiceagregar = false;
                    this.listarformulaice(this.buscarformula);
                    this.id_ice_formula = null;
                })
                .catch(err => {});
        },
        eliminarformulaice(id) {
            axios.delete("/api/eliminariceformula/" + id);
            this.$vs.notify({
                title: "Registro eliminado",
                text: "Este registro ha sido eliminado exitosamente",
                color: "success"
            });
            this.eliminarformice = false;
            this.listarformulaice(this.buscarformula);
        },
        selecrformulaice(option) {
            const caracteres = ["+", "-", "*", "/", "(", ")"];
            option.forEach(ele => {
                caracteres.forEach((el, index) => {
                    if (el == ele.label) {
                        var aleat =
                            Math.floor(Math.random() * 99999999999) + 999;
                        this.option.splice(index, 1, { id: aleat, label: el });
                    }
                });
            });
        },
        //validacion codigo ice
        validar() {
            this.error = 0;
            this.errorcodigo = [];
            this.errornombre = [];

            if (!this.codigo) {
                this.errorcodigo.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }
            if (!this.nombre) {
                this.errornombre.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }

            return this.error;
        },
        cancelar() {
            this.id_ice = null;
            this.codigo = "";
            this.nombre = "";
            this.valor = "";
            this.formula = "";
            this.observacion = "";
            this.id_ice_formula = null;
            this.codigoform = "";
            this.nombreform = "";
            this.iceformulaform = "";
            this.error = 0;
            this.errorcodigo = [];
            this.errornombre = [];
            this.errorformula = 0;
            this.errorcodigoform = [];
            this.errornombreform = [];
        }
    },
    mounted() {
        this.listarice(this.buscar);
    }
};
</script>

<style lang="scss">
@import "@sass/vuexy/extraComponents/agGridStyleOverride.scss";
.dropdown-button-container {
    display: flex;
    align-items: center;

    .btnx {
        border-radius: 5px 0px 0px 5px;
    }

    .btn-drop {
        border-radius: 0px 5px 5px 0px;
        border-left: 1px solid rgba(255, 255, 255, 0.2);
    }
}
.txt-center > div > input {
    text-align: center;
}
.text-center > .vs-table-text {
    text-align: center !important;
    display: block;
}
.vs-popup {
    width: 1060px !important;
}
.mediano .vs-popup {
    width: 800px !important;
}
.peque .vs-popup {
    width: 600px !important;
}
.peque1 .vs-popup {
    width: 500px !important;
}
.peque2 .vs-popup {
    width: 400px !important;
}
.full .vs-popup {
    width: 1060px !important;
}
.demo-alignment > * {
    margin-right: 1.5rem;
    margin-top: 0.8rem;
}
</style>
