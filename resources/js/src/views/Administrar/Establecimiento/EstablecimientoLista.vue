<template>
    <div id="ag-grid-demo">
        <vx-card>
            <div class="mb-4 flex flex-wrap justify-between items-center">
                <div class="mr-4 ag-grid-table-actions-left"></div>
                <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
                    <vs-input class="mb-4 md:mb-0 mr-4" v-model="buscar" @keyup.enter="listar(1, buscar)" v-bind:placeholder="i18nbuscar"/>
                    <div class="dropdown-button-container" v-if="crearrol">
                        <vs-button class="btnx" type="filled" @click="crear()">Agregar</vs-button>
                        <vs-dropdown>
                            <vs-button class="btn-drop" type="filled" icon="expand_more"></vs-button>
                            <vs-dropdown-menu style="width:13em;">
                                <vs-dropdown-item class="text-center" divider @click="importar = true">Importar registros</vs-dropdown-item>
                                <vs-dropdown-item class="text-center" @click="exportar = true">Exportar registros</vs-dropdown-item>
                                <vs-dropdown-item class="text-center" divider>Generar PDF</vs-dropdown-item>
                                <vs-dropdown-item class="text-center">Generar XML</vs-dropdown-item>
                            </vs-dropdown-menu>
                        </vs-dropdown>
                    </div>
                </div>
            </div>
            <vs-table stripe :data="contenido">
                <template slot="thead">
                    <vs-th>Nombre</vs-th>
                    <vs-th>NºSerie</vs-th>
                    <vs-th>Emisor</vs-th>
                    <vs-th>Dirección</vs-th>
                    <vs-th>Estado</vs-th>
                    <vs-th>Acciones</vs-th>
                </template>
                <template slot-scope="{ data }">
                    <vs-tr :key="datos.id" v-for="datos in data">
                        <vs-td>{{ datos.nombre }}</vs-td>
                        <vs-td v-if="datos.codigo">{{ datos.codigo }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.nombre_comercial">{{ datos.nombre_comercial }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.direccion">{{ datos.direccion }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.estado == 1">
                            <div style="color:green;">Activo</div>
                        </vs-td>
                        <vs-td v-else>
                            <div style="color:red;">Inactivo</div>
                        </vs-td>
                        <vs-td class="whitespace-no-wrap">
                            <feather-icon v-if="editarrol" icon="EditIcon" class="cursor-pointer" svgClasses="w-5 h-5 hover:text-primary stroke-current" @click.stop="edit(datos.id_establecimiento)"/>
                            <feather-icon v-if="eliminarrol" icon="TrashIcon" class="cursor-pointer" svgClasses="w-5 h-5 hover:text-danger stroke-current" @click.stop="eliminar(datos.id_establecimiento)"/>
                        </vs-td>
                    </vs-tr>
                </template>
            </vs-table>
        </vx-card>
        <vs-popup :class="'peque1'" title="Exportar Excel" :active.sync="exportar">
            <vx-card>
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        <div class="vx-col sm:w-full w-full mt-5">
                            <vs-input v-model="nombreexportar" placeholder="Nombre del archivo..." class="w-full"/>
                            <div class="flex mb-4">
                                <span class="mr-4">Celda con ancho predefinido:</span>
                                <vs-switch v-model="cellancho">Ancho de los campos del archivo</vs-switch>
                            </div>
                        </div>
                        <div class="vx-col sm:w-full w-full mt-5">
                            <vs-button color="success" type="filled" @click="exportardatos">Descargar Excel</vs-button>
                            <vs-button color="danger" type="filled" @click="exportar = false">Cancelar</vs-button>
                        </div>
                    </div>
                </div>
            </vx-card>
        </vs-popup>
        <vs-popup :class="'peque2'" title="Importar Excel" :active.sync="importar">
            <vx-card>
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        <div class="vx-col sm:w-full w-full mb-6">
                            <label class="vs-input--label">Subir Archivo</label>
                            <div class="vx-col md:w-full w-full mb-6">
                                <div style="display:none">
                                    <input
                                    id="input-upload"
                                     type="file" 
                                     class="custom-file-input inputexcel" 
                                     @change="subirArchivo($event)"
                                     accept=".XLSX, .CSV"
                                     />
                                </div>
                                <div class="centimg vx-card input" @click="importarexcel()">
                                    <img src="/images/upload.png" />
                                    <div v-if="file.length === 0" style="position:absolute;margin-top:60px;color:#000" >Click para subir Archivo</div>
                                    <div v-else  style="position:absolute;margin-top:60px;color:#000">{{file[0].name}}</div>
                                
                                </div>
                            </div>
                        </div>
                       
                        <div class="vx-col sm:w-full w-full mb-6">
                            <vs-button color="success" @click="importardatos()">Subir Archivo</vs-button>
                            <vs-button color="danger" type="filled" @click="cancelar1()">Cancelar</vs-button>
                        </div>
                    </div>
                </div>
            </vx-card>
        </vs-popup> 
    </div>
</template>
<script>
import { AgGridVue } from "ag-grid-vue";
import ImportExcel from "@/components/excel/ImportExcel.vue";
import $ from "jquery";
const axios = require("axios");
export default {
    components: {
        AgGridVue,
        ImportExcel
    },
    data() {
        return {
            /********************
             *
             * @variables
             * mapeo de datos
             *
             ********************/
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
            cantidadp: 10,
            offset: 0,
            //buscador
            buscar: "",
            criterio: "codCta",
            //otros valores
            gridApi: null,
            contenido: [],
            activePrompt: false,
            val: "",
            //lenguaje
            i18nbuscar: this.$t("i18nbuscar"),
            //Datos para la importaciond de archivos
            //excel import
            file: [],
            tableData: [],
            header: [],
            sheetName: "",
            importar: false,
            nombreimportar: "",
            //Datos para la Exportacion de archivos
            exportar: false,
            nombreexportar: "",
            formatoexportar: ["xlsx", "csv", "txt"],
            cellancho: true,
            tipoformatoexportar: "xlsx",
            //campos que existen para exportar
            campos: [
                "id_establecimiento",
                "nombre",
                "codigo",
                "urlweb",
                "nombre_comercial",
                "direccion",
                "estado",
                "fcrea",
                "fmodifica",
                "umodifica",
                "ucrea",
                "id_empresa"
            ],
            //campos elegidos a exportar
            indexs: [
                "nombre",
                "codigo",
                "urlweb",
                "nombre_comercial",
                "direccion",
                "estado",
                "id_empresa"
            ],
            //cabezera que imprimira en el excel
            cabezera: [
                "nombre",
                "codigo",
                "urlweb",
                "nombre_comercial",
                "direccion",
                "estado",
                "id_empresa"
            ]
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
            // if (this.usuario.id_rol == 1) {
            //     res = 1;
            // } else {
            //     res = this.$store.state.Roles[1].crear;
            // }
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                this.$store.state.Roles.forEach(el => {
                    if(el.nombre == "Establecimiento"){
                        res = el.crear;
                        return res;
                    }
                });
            }
            return res;
        },
        editarrol() {
            var res = 0;
            // if (this.usuario.id_rol == 1) {
            //     res = 1;
            // } else {
            //     res = this.$store.state.Roles[1].editar;
            // }
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                this.$store.state.Roles.forEach(el => {
                    if(el.nombre == "Establecimiento"){
                        res = el.editar;
                        return res;
                    }
                });
            }
            return res;
        },
        eliminarrol() {
            var res = 0;
            // if (this.usuario.id_rol == 1) {
            //     res = 1;
            // } else {
            //     res = this.$store.state.Roles[1].eliminar;
            // }
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                this.$store.state.Roles.forEach(el => {
                    if(el.nombre == "Establecimiento"){
                        res = el.eliminar;
                        return res;
                    }
                });
            }
            return res;
        }
    },
    methods: {
        /*
         * exportar archivo
         */
        exportardatos() {
            import("../../../vendor/Export2EstablecimientoExcel").then(
                excel => {
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
                }
            );
        },
        formatJson(filterVal, jsonData) {
            return jsonData.map(v =>
                filterVal.map(j => {
                    return v[j];
                })
            );
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
        cancelar1() {
            this.importar = false;
            this.file = [];
            document.getElementById("input-upload").value = ""
        },

        importardatos() {
            let formData = new FormData();

            formData.append("id_empresa", this.usuario.id_empresa);
            formData.append("file", this.file[0]);
            axios
                .post("/api/ImportarEstablecimientosExcel", formData, {})
                .then(res => {
                    document.getElementById("input-upload").value = ""
                    this.$vs.notify({
                        text: "Archivo Importado con exito",
                        color: "success"
                    });
                    this.importar = false;
                    this.listar(1, this.buscar);
                    this.file = [];
                })
                .catch(err => {
                     this.$vs.notify({
                        text: "Archivo Importado no tuvo exito",
                        color: "danger"
                    });
                    this.importar = false;
                    this.listar(1, this.buscar);
                    this.file = [];
                });
        },
        subirArchivo(e) {
            this.file = []
            let tempFile = e.target.files[0];
            var allowedExtensions = /(.csv|.xls|.xlsx)$/i;
            if (!allowedExtensions.exec(tempFile.name)) {
                this.$vs.notify({
                    title: "Tipo de archivo no compatible",
                    text: "Formatos aceptados: .xlsx, .csv",
                    color: "danger"
                });
                return;
            }
            this.file.push(tempFile);
        },
        
        importarexcel() {
            $(".inputexcel").click();
        },
        /********************************
         *
         *@function edit
         * envia a formulario para editar
         *
         ********************************/
        edit(id) {
            this.$router.push(`/administrar/establecimiento/${id}/editar`);
        },
        /********************************
         *
         *@function crear
         * envia a formulario para crear
         *
         ********************************/
        crear() {
            this.$router.push("/administrar/establecimiento/agregar");
        },
        /*************************
         *
         * @function eliminar
         * elimnar un registro por id
         * enviar id por cd a
         * acceptAlert
         *
         *************************/
        eliminar(cd) {
            this.$vs.dialog({
                type: "confirm",
                color: "danger",
                title: `Confirmar`,
                text: `¿Desea Elimnar este registro?:`,
                acceptText: "Aceptar",
                cancelText: "Cancelar",
                accept: this.acceptAlert,
                parameters: cd
            });
        },
        /**************************
         *
         * @function acceptAlert
         * recibe id por cd
         * de la function eliminar
         * @method detele
         *
         **************************/
        acceptAlert(parameters) {
            axios.delete("/api/establecimientoeliminar/" + parameters);
            this.$vs.notify({
                color: "success",
                title: "Establecimiento Eliminada  ",
                text: "Registro eliminado con exito"
            });
            this.listar(1, this.buscar);
        },
        /*********************************
         *
         *@function listar
         * lista datos de establecimiento
         *@method get
         *
         ********************************/
        listar(page, buscar) {
            let me = this;
            var url =
                "/api/establecimiento/" +
                this.usuario.id_empresa +
                "?page=" +
                page +
                "&buscar=" +
                buscar;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.contenido = respuesta.recupera;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        /***
         *
         *@function updateSearchQuery
         *consulta setQuickFilter
         */
        updateSearchQuery(val) {
            this.gridApi.setQuickFilter(val);
        }
    },
    mounted() {
        this.listar(1, this.buscar);
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
    .vs-switch--input {
        width: 25%;
        height: 10px;
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
