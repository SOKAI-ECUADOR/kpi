<template>
    <div id="ag-grid-demo">
        <vx-card>
            <div class="flex flex-wrap justify-between items-center mb-3">
                <!-- ITEMS PER PAGE -->
                <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"></div>
                <div
                    class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"
                >
                    <vs-input
                        class="mb-4 md:mb-0 mr-4"
                        v-model="buscar2"
                        @keyup="listarproyecto(1, buscar2)"
                        v-bind:placeholder="i18nbuscar"
                    />

                    <!--Fin de bóton de herramientas-->
                    <div class="dropdown-button-container">
                        <vs-button
                            v-if="crearrol"
                            class="btnx"
                            type="filled"
                            @click="agregar('lineas', 'guardar')"
                            >Agregar</vs-button
                        >
                        <vs-dropdown>
                            <vs-button
                                class="btn-drop"
                                type="filled"
                                icon="expand_more"
                            ></vs-button>
                            <vs-dropdown-menu style="width:13em;">
                                <vs-dropdown-item
                                    class="text-center"
                                    @click="importar = true"
                                    >Importar registros</vs-dropdown-item
                                >
                                <vs-dropdown-item
                                    class="text-center"
                                    @click="exportar = true"
                                    >Exportar registros</vs-dropdown-item
                                >
                                
                            </vs-dropdown-menu>
                        </vs-dropdown>
                    </div>
                </div>
            </div>
            <vs-table stripe v-model="cuentaarray4" :data="contenidoproyecto">
                <template slot="thead">
                    <!--<vs-th>id</vs-th>-->
                    <vs-th>Código</vs-th>
                    <vs-th>Descripción</vs-th>
                    <vs-th>Ubicación</vs-th>
                    <vs-th>Opciones</vs-th>
                </template>
                <template slot-scope="{ data }">
                    <vs-tr
                        :data="tr"
                        :key="indextr"
                        v-for="(tr, indextr) in data"
                    >
                        <!--<vs-td v-if="tr.id_proyecto">{{tr.id_proyecto}}</vs-td>
                                  <vs-td v-else>-</vs-td>-->
                        <vs-td v-if="tr.codigo">{{ tr.codigo }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="tr.descripcion">{{
                            tr.descripcion
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="tr.ubicacion">{{ tr.ubicacion }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td class="whitespace-no-wrap">
                            <vx-tooltip
                                text="Editar Proyecto"
                                style="display: inline-flex;"
                            >
                                <feather-icon
                                v-if="editarrol"
                                    icon="EditIcon"
                                    class="cursor-pointer"
                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                    @click.stop="
                                        agregar(
                                            'lineas',
                                            'editar',
                                            tr.id_proyecto
                                        )
                                    "
                                />
                            </vx-tooltip>
                            <vx-tooltip
                                text="Eliminar Proyecto"
                                style="display: inline-flex;"
                            >
                                <feather-icon
                                v-if="eliminarrol"
                                    icon="TrashIcon"
                                    svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                    class="ml-2 cursor-pointer"
                                    @click.stop="
                                        eliminarproyec(tr.id_proyecto);
                                        
                                    "
                                />
                            </vx-tooltip>
                        </vs-td>
                    </vs-tr>
                </template>
            </vs-table>

            <vs-popup :title="titulo" :active.sync="agregarlinea">
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/3 w-full mb-6" hidden>
                            <vs-input
                                class="selectExample w-full"
                                label="Código:"
                                vs-multiple
                                autocomplete
                                v-model="codigo"
                            />
                            <div v-show="error" v-if="!codigo">
                                <span
                                    class="text-danger"
                                    v-for="err in errorrcodigo"
                                    :key="err"
                                    v-text="err"
                                ></span>
                            </div>
                        </div>

                        <div class="vx-col sm:w-1/2 w-full mb-6">
                            <vs-input
                                class="selectExample w-full"
                                label="Descripción:"
                                vs-multiple
                                autocomplete
                                v-model="descripcion"
                                @keypress="sololetras($event)"
                            />
                            <div v-show="error" v-if="!descripcion">
                                <span
                                    class="text-danger"
                                    v-for="err in errordescripcion"
                                    :key="err"
                                    v-text="err"
                                ></span>
                            </div>
                        </div>
                        <div class="vx-col sm:w-1/2 w-full mb-6">
                            <vs-input
                                class="selectExample w-full"
                                label="Ubicación:"
                                vs-multiple
                                autocomplete
                                v-model="ubicacion"
                                @keypress="sololetras($event)"
                            />
                            <div v-show="error" v-if="!ubicacion">
                                <span
                                    class="text-danger"
                                    v-for="err in errorubicacion"
                                    :key="err"
                                    v-text="err"
                                ></span>
                            </div>
                        </div>

                        <div class="vx-col sm:w-full w-full">
                            <vs-button
                                color="success"
                                type="filled"
                                v-if="tipoaccionmodal == 1"
                                @click="guardarproyecto()"
                                >Guardar</vs-button
                            >
                            <vs-button
                                color="success"
                                type="filled"
                                v-else
                                @click="editarproyecto()"
                                >Guardar</vs-button
                            >
                            <vs-button
                                color="danger"
                                type="filled"
                                @click="agregarlinea = false"
                                >Cancelar</vs-button
                            >
                        </div>
                    </div>
                </div>
            </vs-popup>
            <vs-popup title="eliminar registro" :class="'peque'" :active.sync="eliminar">
                <vx-card>
                    <div class="vx-col sm:w-full w-full mb-6">
                        <div class="vx-row">
                            <div class="vx-col sm:w-full w-full mb-6 text-center">
                                <label class="text-center"> Esta seguro que desea eliminar este registro
                                    <br/>
                                </label>
                            </div>
                            <div class="vx-col sm:w-full w-full text-center">
                                <vs-button color="danger" type="filled" v-if="tipoaccionmodal == 1" @click="eliminargrupo(ideliminar)">Eliminar</vs-button>
                                <vs-button color="primary" type="filled" @click="eliminar = false">Cancelar</vs-button>
                            </div>
                        </div>
                    </div>
                </vx-card>
            </vs-popup>
        </vx-card>

        <!-- =============================================== Modal para exportar excel ============================================= -->
        <vs-popup  :class="'peque1'" title="Exportar Excel" :active.sync="exportar">
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
        <!-- ============================================== ===============fin ================================================== -->

        <!-- ================================================== Modal para importar excel ============================================== -->
        <vs-popup :class="'peque2'"  title="Importar Excel" :active.sync="importar">
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
import vSelect from "vue-select";
import ImportExcel from "@/components/excel/ImportExcel.vue";
import $ from "jquery";
const axios = require("axios");

export default {
    components: {
        AgGridVue
    },
    computed: {
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        },
        // crearrol() {
        //     var res = 0;
        //     if (this.usuario.id_rol == 1) {
        //         res = 1;
        //     } else {
        //         res = this.$store.state.Roles[15].crear;
        //     }
        //     return res;
        // },
        // editarrol() {
        //     var res = 0;
        //     if (this.usuario.id_rol == 1) {
        //         res = 1;
        //     } else {
        //         res = this.$store.state.Roles[15].editar;
        //     }
        //     return res;
        // },
        // eliminarrol() {
        //     var res = 0;
        //     if (this.usuario.id_rol == 1) {
        //         res = 1;
        //     } else {
        //         res = this.$store.state.Roles[15].eliminar;
        //     }
        //     return res;
        // }
        crearrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                this.$store.state.Roles.forEach(el => {
                    if(el.nombre == "Proyecto"){
                        res = el.crear;
                        return res;
                    }
                });
            }
            
            return res;
            
        },
        editarrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                this.$store.state.Roles.forEach(el => {
                    if(el.nombre == "Proyecto"){
                        res = el.editar;
                        return res;
                    }
                });
            }
            return res;
        },
        eliminarrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                this.$store.state.Roles.forEach(el => {
                    if(el.nombre == "Proyecto"){
                        res = el.eliminar;
                        return res;
                    }
                });
            }
            return res;
        }
    },
    data() {
        return {
            contenidoproyecto: [],
            buscar2: "",
            cuentaarray4: [],
            i18nbuscar: this.$t("i18nbuscar"),
            //modal agregar
            agregarlinea: false,
            titulomodal: "",
            titulo: "",
            modal: false,
            tipoaccion: 0,
            tipoaccionmodal: 0,
            traer: {},
            id: null,
            codigo: "",
            descripcion: "",
            ubicacion: "",
            ideliminar: 0,
            eliminar: false,
            //errores
            error: 0,
            errorubicacion: [],
            errordescripcion: [],
            errorrcodigo: [],
            //excel import
            file: [],
            tableData: [],
            header: [],
            sheetName: "",
            //Datos para la importaciond de archivos
            importar: false,
            //Datos para la Exportacion de archivos
            exportar: false,
            nombreexportar: "",
            formatoexportar: ["xlsx", "csv", "txt"],
            cellancho: true,
            tipoformatoexportar: "xlsx",
            //campos que existen para exportar
            campos: [
                "id_proyecto",
                "codigo",
                "descripcion",
                "ubicacion",
                "id_empresa",
                "fcrea",
                "fmodifica",
                "ucrea",
                "umodifica"
            ],
            //campos elegidos a exportar
            indexs: [
                "codigo",
                "descripcion",
                "ubicacion",
                "id_empresa"
            ],
            //cabezera que imprimira en el excel
            cabezera: [
                "codigo",
                "descripcion",
                "ubicacion",
                "id_empresa"
            ]
        };
    },
    methods: {
        /* exportar archivo
         */
        exportardatos() {
            import("../../../vendor/Export2ProyectosExcel").then(excel => {
                const list = this.contenidoproyecto;
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
        importarexcel() {
            $(".inputexcel").click();
        },
        importardatos() {
            let formData = new FormData();

            formData.append("id_empresa", this.usuario.id_empresa);
            formData.append("file", this.file[0]);
            axios
                .post("/api/ImportarProyectosExcel", formData, {})
                .then(res => {
                    this.$vs.notify({
                        text: "Archivo Importado con exito",
                        color: "success"
                    });
                    this.importar = false;
                    this.listarproyecto(1, this.buscar2);
                    this.file = [];
                })
                .catch(err => {
                    this.$vs.notify({
                        text: "Archivo Importado no tuvo exito",
                        color: "danger"
                    });
                    this.importar = false;
                    this.listarproyecto(1, this.buscar2);
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
                    text: "Formatos aceptados: .csv, .xls, .xlsx",
                    color: "danger"
                });
                return;
            }
            this.file.push(tempFile);
        },

        listarproyecto(page2, buscar2) {
            var url =
                "/api/listarproyecto/" +
                this.usuario.id_empresa +
                "?page=" +
                page2 +
                "&buscar=" +
                buscar2;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidoproyecto = respuesta.recupera;
            });
        },
        guardarproyecto() {
            if (this.validar()) {
                return;
            }
            axios
                .post("/api/guardarproyecto", {
                    codigo: this.codigo,
                    descripcion: this.descripcion,
                    ubicacion: this.ubicacion,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    if(res.data=='existe'){
                        this.$vs.notify({
                        title: "Codigo Existe",
                        text: "Este codigo ya existe",
                        color: "danger"
                         });
                    }else{
                        this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                        });
                        this.agregarlinea = false;
                        this.listarproyecto(1, this.buscar2);
                        this.todaslinea();
                    }
                })
                .catch(err => {});
        },
        editarproyecto() {
            if (this.validar()) {
                return;
            }
            axios
                .post("/api/editarproyecto", {
                    id: this.id,
                    codigo: this.codigo,
                    descripcion: this.descripcion,
                    ubicacion: this.ubicacion,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    if(res.data=='existe'){
                        this.$vs.notify({
                            title: "Codigo Existente",
                            text: "Este Codigo ya Existe",
                            color: "danger"
                        });
                    }else{
                        this.$vs.notify({
                            title: "Registro Exitoso",
                            text: "Registro agregado con éxito",
                            color: "success"
                        });
                        this.agregarlinea = false;
                        this.listarproyecto(1, this.buscar2);
                    }
                    
                });
        },
        eliminarproyec(cd){
            this.$vs.dialog({
                type: "confirm",
                color: "danger",
                title: `Confirmar`,
                text: `¿Desea Eliminar este registro?`,
                acceptText: "Aceptar",
                cancelText: "Cancelar",
                accept: this.acceptAlert,
                parameters: cd
            });
        },
        acceptAlert(parameters) {
            axios.delete("/api/eliminarproyecto/" + parameters);
            this.$vs.notify({
                title: "Registro eliminado",
                text: "Este registro ha sido eliminado exitosamente",
                color: "success"
            });
             this.listarproyecto(1, this.buscar2);
        },
        eliminargrupo(id) {
            axios.delete("/api/eliminarproyecto/" + id);
            this.$vs.notify({
                title: "Registro eliminado",
                text: "Este registro ha sido eliminado exitosamente",
                color: "success"
            });
            this.eliminar = false;
            this.listarproyecto(1, this.buscar2);
        },
        agregar(tipo, accion, dato) {
            switch (tipo) {
                case "lineas": {
                    switch (accion) {
                        case "guardar": {
                            this.agregarlinea = true;
                            this.tipoaccionmodal = 1;
                            this.titulo = "Agregar Proyecto";
                            this.codigo = "";
                            this.descripcion = "";
                            this.ubicacion = "";
                            this.id = null;
                            break;
                        }
                        case "editar": {
                            this.agregarlinea = true;
                            this.tipoaccionmodal = 2;
                            this.titulo = "Editar Proyecto";
                            //console.log("ñ"+dato);
                            var url = "/api/verproyecto/" + dato;
                            axios
                                .put(url)
                                .then(res => {
                                    let data = res.data[0];
                                    (this.id = data.id_proyecto),
                                        (this.codigo = data.codigo),
                                        (this.descripcion = data.descripcion),
                                        (this.ubicacion = data.ubicacion);
                                })
                                .catch();
                            break;
                        }
                    }
                    break;
                }
            }
        },
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
        sololetras: function($event) {
            var letra = /^\d*\.?\d*$/;
            if (
                $event.charCode === 0 ||
                !letra.test(String.fromCharCode($event.charCode))
            ) {
                return true;
            } else {
                $event.preventDefault();
            }
        },
        validar() {
            this.error = 0;
            this.errorubicacion = [];
            this.errordescripcion = [];
            this.errorrcodigo = [];
            /*if (!this.codigo) {
                this.errorrcodigo.push("Campo Obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }*/
            if (!this.descripcion) {
                this.errordescripcion.push("Campo Obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }
            if (!this.ubicacion) {
                this.errorubicacion.push("Campo Obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }
            return this.error;
        }
    },
    mounted() {
        this.listarproyecto(1, this.buscar2);
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
/*
.vs-dialog {
  max-width: 1024px !important;
}*/
.vs-popup {
    width: 1060px !important;
}
.peque .vs-popup {
    width: 600px !important;
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
