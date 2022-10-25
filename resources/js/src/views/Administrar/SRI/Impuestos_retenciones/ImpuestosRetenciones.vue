<template>
    <div id="ag-grid-demo">
        <vx-card>
            <div class="flex flex-wrap justify-between items-center">
                <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left">
                </div>
                <div
                    class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"
                >
                    <vs-input class="mb-4 md:mb-0 mr-4" v-model="buscar" @keyup="listar(1, buscar)" v-bind:placeholder="i18nbuscar" />
                    <div class="dropdown-button-container" v-if="crearrol">
                        <vs-button class="btnx" type="filled" @click="agregarRet()">Agregar</vs-button>
                        <vs-dropdown>
                            <vs-button class="btn-drop" type="filled" icon="expand_more"></vs-button>
                            <vs-dropdown-menu style="width:13em;">
                                <vs-dropdown-item class="text-center" divider  @click="importar = true">Importar registros</vs-dropdown-item>
                                <vs-dropdown-item class="text-center" @click="exportar = true">Exportar registros</vs-dropdown-item>
                                <vs-dropdown-item class="text-center" divider>Generar PDF</vs-dropdown-item>
                                <vs-dropdown-item class="text-center">Generar XML</vs-dropdown-item>
                            </vs-dropdown-menu>
                        </vs-dropdown>
                    </div>
                </div>
            </div>
            <br />
            <vs-table stripe :data="contenido">
                <template slot="thead">
                    <vs-th>Descripcion</vs-th>
                    <vs-th>%</vs-th>
                    <vs-th>Tipo</vs-th>
                    <vs-th>Cuenta contable</vs-th>
                    <vs-th>Acciones</vs-th>
                </template>
                <template slot-scope="{ data }">
                    <vs-tr :key="datos.id" v-for="datos in data">
                        <vs-td v-if="datos.descrip_retencion">{{datos.descrip_retencion}}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.porcen_retencion">{{datos.porcen_retencion}}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.tipo_retencion">{{datos.tipo_retencion}}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.nomcta">{{datos.nomcta}}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td class="whitespace-no-wrap">
                            <feather-icon v-if="editarrol"  icon="EditIcon" class="cursor-pointer" svgClasses="w-5 h-5 hover:text-primary stroke-current" @click.stop="edit(datos.id_retencion)"/>
                            <feather-icon v-if="eliminarrol" icon="TrashIcon" class="cursor-pointer" svgClasses="w-5 h-5 hover:text-danger stroke-current" @click.stop="eliminar(datos.id_retencion)"/>
                        </vs-td>
                    </vs-tr>
                </template>
            </vs-table>
           
            <vs-popup :title="titulomodal" :active.sync="activePrompt">
                <div class="vx-row">
                    <div class="vx-col sm:w-1/5 w-full mb-6" hidden>
                        <vs-input class="w-full" label="Codigo" v-model="cod_retencion" maxlength="10"/>
                        <div v-show="error" v-if="!cod_retencion">
                            <div v-for="err in errorcodigo" :key="err" v-text="err" class="text-danger"></div>
                        </div>
                    </div>
                    <div class="vx-col sm:w-full w-full mb-6">
                        <vs-input class="w-full" label="Descripcion" v-model="descrip_retencion" maxlength="99"/>
                        <div v-show="error" v-if="!descrip_retencion">
                            <div v-for="err in errordescripcion" :key="err" v-text="err" class="text-danger"></div>
                        </div>
                    </div>
                </div>
                <div class="vx-row">
                    <div class="vx-col sm:w-1/2 w-full mb-6">
                        <vs-select placeholder="Seleccione la retención" class="selectExample w-full" label="Tipo Retencion" vs-multiple autocomplete v-model="tipo_retencion">
                            <vs-select-item value="Retencion Fuente Compras" text="Retencion Fuente Compras"/>
                            <vs-select-item value="Retencion IVA Compras" text="Retencion IVA Compras"/>
                            <vs-select-item value="Retencion Fuente Ventas" text="Retencion Fuente Ventas"/>
                            <vs-select-item value="Retencion IVA Ventas" text="Retencion IVA Ventas"/>
                        </vs-select>
                        <div v-show="error" v-if="!tipo_retencion">
                            <div v-for="err in errortiporetencion" :key="err" v-text="err" class="text-danger"></div>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-input class="w-full" @change="getImp()" @keypress="solodecimales" label="Porcentaje" v-model="porcen_retencion" maxlength="9"/>
                        <div v-show="error" v-if="!porcen_retencion">
                            <div v-for="err in errormporcentaje" :key="err" v-text="err" class="text-danger"></div>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-select class="selectExample w-full" placeholder="Seleccione el IVA" label="Tipo IVA" vs-multiple autocomplete v-model="tipoiva_retencion">
                            <vs-select-item value="Credito Tributario" text="Credito Tributario"/>
                            <vs-select-item value="Activos fijos" text="Activos fijos"/>
                            <vs-select-item value="No aplica Credito Tributario" text="No aplica Credito Tributario"/>
                        </vs-select>
                        <div v-show="error" v-if="!tipoiva_retencion">
                            <div v-for="err in errortipoiva" :key="err" v-text="err" class="text-danger"></div>
                        </div>
                    </div>
                </div>
                <div class="vx-row">
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <label class="vs-input--label">Cuenta Contable</label>
                        <vx-input-group class>
                            <vs-input disabled class="w-full" v-model="cta_contable" :value="id_cuenta" maxlength="200" />
                            <template slot="append">
                                <div class="append-text btn-addon">
                                    <vs-button color="primary" @click="activePrompt3 = true">Buscar</vs-button>
                                </div>
                            </template>
                        </vx-input-group>
                        <!--<div class="row mb-2">
                            <div
                                class="col-xl-6 col-lg-12 justify-content-end clicker"
                                style="position: relative;"
                            >
                                <div
                                    class="input-group mb-3"
                                    style="width: 100%;"
                                >
                                    <vs-input
                                        label="Cuenta Contable"
                                        type="text"
                                        class="w-full"
                                        placeholder="Buscar Cuenta Contable"
                                        v-model="cta_contable"
                                        @keyup="
                                            listarcuenta(cta_contable),
                                                abrirlista()
                                        "
                                    />
                                </div>
                                <div
                                    class="menuescoger"
                                    style="display:none"
                                    id="menuescoger"
                                    v-if="cta_contable"
                                >
                                    <template v-if="contenidocuenta.length">
                                        <ul
                                            v-for="(tr,
                                            index) in contenidocuenta"
                                            :key="index"
                                            @click="handleSelectedCuentaContable(tr)"
                                        >
                                            <li>
                                                {{ tr.nomcta }}
                                                <span class="posicion">
                                                    <template v-if="tr.codcta"
                                                        ><span
                                                            >Codigo:
                                                            {{ tr.codcta }}
                                                        </span>
                                                    </template>
                                                </span>
                                            </li>
                                        </ul>
                                    </template>
                                    <template v-else>
                                        <ul
                                            style="padding: 7px;text-align: center;"
                                        >
                                            <li>
                                                Cuenta Contable no existe
                                            </li>
                                        </ul>
                                    </template>
                                </div>
                            </div>
                        </div>-->
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-select placeholder="Seleccione una moneda" class="selectExample w-full" label="Moneda" vs-multiple autocomplete v-model="id_moneda">
                            <vs-select-item v-for="data in monedas" :key="data.id_moneda" :value="data.id_moneda" :text="data.nomb_moneda"/>
                        </vs-select>
                        <div v-show="error" v-if="!id_moneda">
                            <div v-for="err in errormoneda" :key="err" v-text="err" class="text-danger"></div>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-select placeholder="Seleccione un código SRI" class="selectExample w-full" label="Codigo SRI" vs-multiple autocomplete v-model="id_impuesto">
                            <vs-select-item v-for="data in imps" :key="data.id_imp" :value="data.id_imp" :text="data.cod_imp"/>
                        </vs-select>
                    </div>
                </div>
                <div class="vx-col w-full">
                    <vs-button color="success" type="filled" @click="guardarRet()" v-if="!idrecupera">GUARDAR</vs-button>
                    <vs-button color="success" type="filled" @click="editarRet()" v-else>GUARDAR</vs-button>
                    <vs-button color="warning" type="filled" @click="vaciarRet()">BORRAR</vs-button>
                    <vs-button color="danger"  type="filled" @click="cancelarRet()">CANCELAR</vs-button>
                </div>
                <vs-popup title="Plan Cuentas" :active.sync="activePrompt3">
                    <div class="con-exemple-prompt">
                        <vs-input class="mb-4 md:mb-0 mr-4 w-full" v-model="buscar3" @keyup="listar3(1, buscar3, criterio3, cantidadp3)" v-bind:placeholder="i18nbuscar3"/>
                        <vs-table stripe v-model="cuentaarray3" @selected="handleSelected3" :data="contenido3">
                            <template slot="thead">
                                <vs-th>No.Cuenta</vs-th>
                                <vs-th>Tipo Cuenta</vs-th>
                            </template>
                            <template slot-scope="{ data }">
                                <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                                    <vs-td :data="data[indextr].codcta">{{data[indextr].codcta}}</vs-td>
                                    <vs-td :data="data[indextr].nomcta">{{data[indextr].nomcta}}</vs-td>
                                </vs-tr>
                            </template>
                        </vs-table>
                    </div>
                </vs-popup>
            </vs-popup>
        </vx-card>
        <!-- =============================================Modal para exportar excel=======================================-->
        <vs-popup
            :class="'peque1'"
            title="Exportar Excel"
            :active.sync="exportar"
        >
            <vx-card>
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        <div class="vx-col sm:w-full w-full mt-5">
                            <vs-input
                                v-model="nombreexportar"
                                placeholder="Nombre del archivo..."
                                class="w-full"
                            />

                            <div class="flex mb-4">
                                <span class="mr-4"
                                    >Celda con ancho predefinido:</span
                                >
                                <vs-switch v-model="cellancho"
                                    >Ancho de los campos del archivo</vs-switch
                                >
                            </div>
                        </div>
                        <div class="vx-col sm:w-full w-full mt-5">
                            <vs-button
                                color="success"
                                type="filled"
                                @click="exportardatos"
                                >Descargar Excel</vs-button
                            >
                            <vs-button
                                color="danger"
                                type="filled"
                                @click="exportar = false"
                                >Cancelar</vs-button
                            >
                        </div>
                    </div>
                </div>
            </vx-card>
        </vs-popup>
        <!--===================================================fin modal de exportar=================================================== -->

        <!-- ================================================== Modal para importar excel ============================================== -->
        <vs-popup
            :class="'peque2'"
            title="Importar Excel"
            :active.sync="importar"
        >
            <vx-card>
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        <div class="vx-col sm:w-full w-full mb-6">
                            <label class="vs-input--label">Subir Archivo</label>

                            <div class="vx-col md:w-full w-full mb-6"  >
                                <div style="display:none" >
                                    <input
                                        id="input-upload"
                                        type="file"
                                        class="custom-file-input inputexcel"
                                        @change="subirArchivo($event)"
                                        accept=".XLSX, .CSV"
                                         
                                    />
                                </div>

                                <div 
                                    class="centimg vx-card input"
                                    @click="importarexcel()"
                                    
                                >
                                    <img src="/images/upload.png" />
                                     <div v-if="file.length === 0" style="position:absolute;margin-top:60px;color:#000" >Click para subir Archivo</div>
                                    <div v-else  style="position:absolute;margin-top:60px;color:#000">{{file[0].name}}</div>
                           
                                </div>
                            </div>  
                        </div>
                      
                        <div class="vx-col sm:w-full w-full mb-6">
                            <vs-button color="success" @click="importardatos()"
                                >Subir Archivo</vs-button
                            >
                            <vs-button
                                color="danger"
                                type="filled"
                                @click="cancelar1()"
                                >Cancelar</vs-button
                            >
                        </div>
           
                    </div>
                </div>
            </vx-card>
        </vs-popup>
        <!-- =============================================== fin modal de importar ===================================================== -->
   
    </div>
</template>
<script>
import { AgGridVue } from "ag-grid-vue";
import ImportExcel from "@/components/excel/ImportExcel.vue";
const axios = require("axios");
import Vue from "vue";
import $ from "jquery";

export default {
    components: {
        AgGridVue,
        ImportExcel
    },
    data() {
        return {
            //mapeo de datos
          //buscador
            buscar: "",
          //otros valores
            contenido: [],
          //lenguaje
            i18nbuscar: this.$t("i18nbuscar"),
          //modal imp
            activePrompt: false,
            titulomodal: "",
            idrecupera: null,
            cod_retencion: "",
            descrip_retencion: "",
            tipo_retencion: "",
            tipoiva_retencion: "",
            porcen_retencion: "1",
            cta_contable: "",
            id_cuenta:"",
            id_moneda: "",
            id_impuesto: "",
            contenidocuenta:[],
          //modal plan ctas
            activePrompt3: false,
            buscar3: "",
            cantidadp3: 50,
            criterio3: "codcta",
            cuentaarray3: [],
            i18nbuscar3: this.$t("i18nbuscar"),
            contenido3: [],
          //traer monedas
            monedas: [],
          //traer imp
            imps: [],
          //errores
            error:0,
            errorcodigo:[],
            errordescripcion:[],
            errortiporetencion:[],
            errortipoiva:[],
            errormoneda:[],
            errormporcentaje:[],
             //Datos para la importaciond de archivos
            importar: false,
            nombreimportar: "",
            //excel import
            file: [],
            tableData: [],
            header: [],
            sheetName: "",
            //Datos para la Exportacion de archivos
            exportar: false,
            nombreexportar: "",
            formatoexportar: ["xlsx", "csv", "txt"],
            cellancho: true,
            tipoformatoexportar: "xlsx",
            //campos que existen para exportar
            campos: [
                "id_retencion",
                "cod_retencion",
                "descrip_retencion",
                "porcen_retencion",
                "tipo_retencion",
                "tipoiva_retencion",
                "id_moneda",
                "id_impuesto",
                "id_proyecto",
                "id_plan_cuentas",
                "id_empresa"
            ],
            //campos elegidos a exportar
            indexs: [
                "cod_retencion",
                "descrip_retencion",
                "tipo_retencion",
                "porcen_retencion",
                "tipoiva_retencion",
                "id_plan_cuentas",
                "id_moneda",
                "id_impuesto",
                //"id_proyecto",
                "id_empresa"
            ],
            //cabezera que imprimira en el excel
            cabezera: [
                "codigo",
                "descripcion",
                "tipo_retencion",
                "porcentaje",
                "tipo_iva",
                "id_plan_cuentas",
                "id_moneda",
                "id_impuesto_sri_codigo",
                //"id_proyecto",
                "id_empresa"
            ]
        };
    },
    computed: {
      //traer datos de usuario
        usuario() {
            return this.$store.state.AppActiveUser;
        },
      //traer toker
        token() {
            return this.$store.state.Token;
        },
        crearrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[6].crear;
            }
            return res;
        },
        editarrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[6].editar;
            }
            return res;
        },
        eliminarrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[6].eliminar;
            }
            return res;
        }
    },
    methods: {
         /*
         * exportar archivo
         */
        exportardatos() {
            import("../../../../vendor/Export2RetencionesExcel").then(excel => {
                const list = this.contenido;
                const data = this.formatJson(this.indexs, list);
                excel.export_json_to_excel({
                    header: this.cabezera,
                    data,
                    filename: this.nombreexportar,
                    autoWidth: this.cellancho,
                    bookType: this.tipoformatoexportar
                });
                this.nombreexportar = "";
                this.exportar = false;
            });
        },
        formatJson(filterVal, jsonData) {
            return jsonData.map(v =>
                filterVal.map(j => {
                    return v[j];
                })
            );
        },
        cancelar1() {
            this.importar = false;
            this.file = [];
            document.getElementById("input-upload").value = ""
        },
       
        /*
         *importar archivos
         *
         */
        //preload
        readyFile ($e){
            // Este muestra una lista FileList
            console.log($e.target.files)
            // Este muestra el valor actual del input
            console.log($e.target.value)
        },
         subirArchivo(e) {
            this.file = []
            let tempFile = e.target.files[0];
            var allowedExtensions = /(.csv|.xls|.xlsx)$/i;
            if (!allowedExtensions.exec(tempFile.name)) {
                this.$vs.notify({
                    title: "Tipo de archivo no compatible",
                    text: "Formatos aceptados: .jpg, .jpeg, .png",
                    color: "danger"
                });
                return;
            }
            this.file.push(tempFile);
        },
        
        importarexcel() {
            $(".inputexcel").click();
            
        },
        importardatos() {
            let formData = new FormData();

            formData.append("id_empresa", this.usuario.id_empresa);
            formData.append("file", this.file[0]);
            axios
                .post("/api/ImportarRetencionesExcel", formData, {})
                .then((res) => {
                    document.getElementById("input-upload").value = ""
                    this.$vs.notify({
                        text: "Archivo Importado con exito",
                        color: "success",
                    });
                    this.importar = false;
                    this.listar(1,this.buscar);
                    this.file = [];
                    
                })
                .catch((err) => {
                    
                    this.$vs.notify({
                        text: "Archivo Importado 'Sin Exito'",
                        color: "danger",
                    });
                    this.importar = false;
                    this.listar(1,this.buscar);
                    this.file = [];
                });
        },
      //selecciona un campo de la tabla y le agrega a una variable
        handleSelected3(tr) {
            (this.cta_contable = `${tr.nomcta}`),
            (this.id_cuenta =`${tr.id_plan_cuentas}`),
             (this.activePrompt3 = false);
        },
      //modal para agregar una nueva retencion  
        agregarRet() {
                (this.activePrompt = true),
                (this.titulomodal = "Agregar"),
                (this.idrecupera = null),
                (this.cod_retencion = ""),
                (this.descrip_retencion = ""),
                (this.tipo_retencion = ""),
                (this.tipoiva_retencion = ""),
                (this.cta_contable = ""),
                (this.id_cuenta = ""),
                (this.porcen_retencion = ""),
                (this.id_moneda = ""),
                (this.id_impuesto = ""),
                (this.error=0),
                (this.errorcodigo=[]),
                (this.errordescripcion=[]),
                (this.errortiporetencion=[]),
                (this.errortipoiva=[]),
                (this.errormoneda=[]),
                (this.errormporcentaje=[]);
        },
        listarcuenta(buscar1){
            axios.get("/api/selcuentasgrupprov/" +
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
        handleSelectedCuentaContable(tr){
            this.id_cuenta = `${tr.id_plan_cuentas}`;
            this.cta_contable = `${tr.codcta}`;
        },
      //guardar retencion
        guardarRet() {
          if(this.validar()){
            return;
          }
            axios.post("/api/agregarretencion", {
                    cod_retencion: this.cod_retencion,
                    descrip_retencion: this.descrip_retencion,
                    tipo_retencion: this.tipo_retencion,
                    tipoiva_retencion: this.tipoiva_retencion,
                    cta_contable: this.cta_contable,
                    id_cuenta:this.id_cuenta,
                    id_moneda: this.id_moneda,
                    id_impuesto: this.id_impuesto,
                    porcen_retencion: this.porcen_retencion,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                        this.activePrompt = false;
                        this.$vs.notify({
                            title: "Registro Guardado",
                            text: "Registro Guardado exitosamente",
                            color: "success"
                        });
                        this.listar(1, this.buscar);
                        this.vaciarRet();
                    
                })
                .catch(err => {});
        },
      //recupera los datos de un impuesto
        edit(id) {
            
            if (id) {
                this.idrecupera = id;
                this.activePrompt = true;
                this.titulomodal = "Editar";
                var url = "/api/abrirretencion/" + id;
                
                axios.put(url)
                    .then(res => {
                        setTimeout(() => {
                            this.getImp();
                        },80);
                        let data = res.data[0];
                        this.cod_retencion = data.cod_retencion;
                        this.descrip_retencion = data.descrip_retencion;
                        this.tipo_retencion = data.tipo_retencion;
                        this.tipoiva_retencion = data.tipoiva_retencion;
                        this.cta_contable = data.cuenta_resultado;
                        this.id_cuenta = data.id_plan_cuentas;
                        this.id_moneda = data.id_moneda;
                        this.id_impuesto = data.id_impuesto;
                        this.porcen_retencion = data.porcen_retencion;
                    })
                    .catch(err => {
                        this.$vs.notify({
                        title: "Error al Editar",
                        text: "Revise bien sus campos antes de guardar",
                        color: "danger"
                    });
                    });
            } else {
                this.idrecupera = null;
            }
        },
      //edita los reguistro de un impuesto
        editarRet() {
            if(this.validar()){
                    return;
                }
            axios.put("/api/actualizarretencion", {
                    id: this.idrecupera,
                    cod_retencion: this.cod_retencion,
                    descrip_retencion: this.descrip_retencion,
                    tipo_retencion: this.tipo_retencion,
                    tipoiva_retencion: this.tipoiva_retencion,
                    cta_contable: this.cta_contable,
                    id_cuenta:this.id_cuenta,
                    id_moneda: this.id_moneda,
                    id_impuesto: this.id_impuesto,
                    porcen_retencion: this.porcen_retencion,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.activePrompt = false;
                    this.$vs.notify({
                        title: "Registro Editado",
                        text: "Registro Editado exitosamente",
                        color: "success"
                    });
                    this.listar(1, this.buscar);
                })
                .catch(err => {
                    this.$vs.notify({
                        title: "Error al Editar",
                        text: "Revise sus datos antes de editar",
                        color: "danger"
                    });
                });
        },
      //borra los campos llenos 
        vaciarRet() {
            (this.cod_retencion = ""),
                (this.descrip_retencion = ""),
                (this.tipo_retencion = ""),
                (this.tipoiva_retencion = ""),
                (this.cta_contable = ""),
                (this.id_cuenta = ""),
                (this.id_moneda = ""),
                (this.id_impuesto = ""),
                (this.porcen_retencion = "");
        },
      //cancela lo que se esta hacien en impuestos
        cancelarRet() {
            (this.activePrompt = false),
                (this.cod_retencion = ""),
                (this.descrip_retencion = ""),
                (this.tipo_retencion = ""),
                (this.tipoiva_retencion = ""),
                (this.cta_contable = ""),
                (this.id_cuenta = ""),
                (this.id_moneda = ""),
                (this.id_impuesto = ""),
                (this.porcen_retencion = "");
        },
      //modal para eliminar
        eliminar(cd) {
            this.$vs.dialog({
                type: "confirm",
                color: "danger",
                title: `Confirmar`,
                text: `¿Desea Eliminar este registro?:`,
                acceptText: "Aceptar",
                cancelText: "Cancelar",
                accept: this.acceptAlert,
                parameters: cd
            });
        },
      //eliminar un reguistro
        acceptAlert(parameters) {
            axios.delete("/api/eliminarretencion/" + parameters)
            .then(res =>{
                this.$vs.notify({
                color: "success",
                title: "Reguistro Eliminado  ",
                text: "El reguistro selecionado fue eliminado con exito"
                });
                this.listar(1, this.buscar);
            }).catch(err => {
                this.$vs.notify({
                color: "danger",
                title: "Error al eliminar",
                text: "Ha ocurrido un error al momento de eliminar reguistro"
                });
            });
            
        },
      //lista todas las retenciones
        listar(page, buscar) {
            let me = this;
            var url =
                "/api/retencion?id_empresa=" +
                this.usuario.id_empresa +
                "&buscar=" +
                buscar;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.contenido = respuesta.recupera;
                })
                .catch(function(error) {
                    this.$vs.notify({
                        title: "Error al listar",
                        text: "A ocurrido un error a listar",
                        color: "danger"
                    });
                });
        },
      //lista todas los planes de cuentas
        listar3(page3, buscar3, criterio3, cantidadp3) {
            let me = this;
            var url =
                "/api/notacredito/listar_cuenta_contable" 
                // +
                // this.usuario.id_empresa +
                // "?page=" +
                // page3 +
                // "&buscar=" +
                // buscar3 +
                // "&criterio=" +
                // criterio3 +
                // "&cantidadp=" +
                // cantidadp3
                ;
            axios.get(url,{
                params:{
                    empresa:this.usuario.id_empresa,
                    buscar:buscar3
                }
            })
                .then(({data})=> {
                    var respuesta = data;
                    me.contenido3 = respuesta;
                })
                .catch(function(error) {
                });
        },
      //traer todo los datos de la bd moneda
        getMonedas() {
            axios.get("/api/traermonedaret").then(
                function(response) {
                    this.monedas = response.data;
                }.bind(this)
            );
        },
      //traer todo los datos de la bd impuesto con cierto porcentaje y retencion de fuente
        getImp() {
            
            axios.get("/api/traerimpret", {
                    params: {
                        porcen_imp: this.porcen_retencion,
                        id_empresa:this.usuario.id_empresa
                    }
                })
                .then(
                    function(response) {
                        //if (response.data) {
                            this.imps = response.data;
                        /*} else {
                            this.id_impuesto = 0;
                        }*/
                    }.bind(this)
                );
        },
      //funcion para que solo teclee decimales
        solodecimales($event) {
            let keyCode = $event.keyCode ? $event.keyCode : $event.which;
            if ((keyCode < 48 || keyCode > 57) && keyCode !== 46) {
                $event.preventDefault();
            }
        },
      //funcion para validar  campos
        validar(){
         this.error=0;
         this.errorcodigo=[];
         this.errordescripcion=[];
         this.errortiporetencion=[];
         this.errortipoiva=[];
         this.errormoneda=[];
         this.errormporcentaje=[];
         /*if(!this.cod_retencion){
           this.errorcodigo.push("Campo Obligatorio");
           this.error=1;
         }*/
         if(!this.descrip_retencion){
           this.errordescripcion.push("Campo Obligatorio");
           this.error=1;
           console.log("descrip_retencion");
         }
         if(!this.tipo_retencion){
           this.errortiporetencion.push("Campo Obligatorio");
           this.error=1;
           console.log("tipo_retencion");
         }
         if(!this.tipoiva_retencion){
           this.errortipoiva.push("Campo Obligatorio");
           this.error=1;
           console.log("tipoiva_retencion");
         }
         if(!this.id_moneda){
           this.errormoneda.push("Campo Obligatorio");
           this.error=1;
           console.log("id_moneda");
         }
         if(!this.porcen_retencion){
           this.errormporcentaje.push("Campo Obligatorio");
           this.error=1;
           console.log("porcen_retencion");
         }
        return this.error;
        }
    },
    //las funciones que iniciaran al principio
    mounted(){
        this.listar(1,this.buscar);
        this.listar3(1, this.buscar3, this.criterio3, this.cantidadp3);
        this.getMonedas();
        

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
.menuescoger {
    position: absolute;
    margin-top: -11px;
    width: 100%;
    background: #fff;
    z-index: 999;
    border: 1px solid #dfdfdf;
    border-radius: 0 0 8px 8px;
}

.menuescoger ul {
    list-style: none;
    padding: 8px 15px 25px 15px;
    margin: 0;
    cursor: pointer;
    color: #848484;
    font-weight: 600;
    font: 14px arial, sans-serif;
    position: relative;
    border-bottom: 1px solid #eaeaea;
}

.menuescoger ul:hover {
    background: rgba(0, 0, 0, 0.1);
}

.menuescoger span {
    font-size: 12px;
}
.posicion {
    bottom: 5px;
    position: absolute;
    left: 15px;
}
.posicion span {
    font-size: 10px;
}
/*
.vs-dialog {
  max-width: 1024px !important;
}*/
.vs-popup {
    width: 1050px !important;
}
.peque .vs-popup {
    width: 400px !important;
}
.peque1 .vs-popup {
    width: 500px !important;
}
.peque2 .vs-popup {
    width: 500px !important;
}
input[type="”file”"]#nuestroinput {
    width: 0.1px;
    height: 0.1px;
    opacity: 0;
    overflow: hidden;
    position: absolute;
    z-index: -1;
}
label[for=" nuestroinput"] {
    font-size: 14px;
    font-weight: 600;
    color: #fff;
    background-color: #106ba0;
    display: inline-block;
    transition: all 0.5s;
    cursor: pointer;
    padding: 15px 40px !important;
    text-transform: uppercase;
    width: fit-content;
    text-align: center;
}
.imagenpre {
    height: 100%;
    cursor: pointer;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}
.centimg {
    height: 225px;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.8) !important;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}
.verimagen {
    overflow: hidden;
    padding: 0px;
    height: 300px;
    height: 300px;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.8) !important;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
    border: 5px solid rgba(0, 0, 0, 0.3);
}
.centimg {
    height: 225px;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.8) !important;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}
.centimg:hover {
    background: rgba(255, 255, 255, 0.6) !important;
    cursor: pointer;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}

.centimg img {
    max-width: 100%;
    max-height: 100px;
    cursor: pointer;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}
.demo-alignment > * {
    margin-right: 1.5rem;
    margin-top: 0.8rem;
}
</style>
