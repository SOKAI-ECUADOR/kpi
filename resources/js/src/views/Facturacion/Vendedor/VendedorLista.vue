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
                        v-model="buscar"
                        @keyup.enter="listar(1, buscar, cantidadp)"
                        v-bind:placeholder="i18nbuscar"
                    />
                    <!--boton de herramientas-->
                    <div class="dropdown-button-container mr-3">
                        <vs-dropdown>
                            <vs-button
                                class="btn-drop"
                                type="filled"
                                icon="settings"
                                style="border-radius: 5px;"
                            ></vs-button>
                            <vs-dropdown-menu style="width:13em;">
                                <vs-dropdown-item class="text-center" divider
                                    >Link</vs-dropdown-item
                                >
                            </vs-dropdown-menu>
                        </vs-dropdown>
                    </div>
                    <!--Fin de bóton de herramientas-->
                    <div class="dropdown-button-container">
                        <vs-button
                            class="btnx"
                            type="filled"
                            to="/facturacion/vendedor/agregar"
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
            <vs-table stripe :data="contenido">
                <template slot="thead">
                    <vs-th>Código</vs-th>
                    <vs-th>Nombre</vs-th>
                    <vs-th>Email</vs-th>
                    <vs-th>Acciones</vs-th>
                </template>
                <template slot-scope="{ data }">
                    <vs-tr :key="datos.id_vendedor" v-for="datos in data">
                        <vs-td v-if="datos.codigo_vendedor">{{
                            datos.codigo_vendedor
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.nombre_vendedor">{{
                            datos.nombre_vendedor
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.email_vendedor">{{
                            datos.email_vendedor
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>

                        <vs-td class="whitespace-no-wrap">
                            <feather-icon
                                v-if="editarrol"
                                icon="EditIcon"
                                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                class="pointer"
                                @click.stop="editar(datos.id_vendedor)"
                            />
                            <feather-icon
                                v-if="eliminarrol"
                                icon="TrashIcon"
                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                class="ml-2 pointer"
                                @click.stop="
                                    eliminarVendedor(datos.id_vendedor)
                                "
                            />
                        </vs-td>
                    </vs-tr>
                </template>
            </vs-table>
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
                            <!--prueba-->
                          
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <label class="vs-input--label">Formato</label>
                        <vs-select
                            placeholder="buscar grupo"
                            autocomplete
                            class="selectExample w-full"
                            v-model="formato_excel"
                            @change="exportVendedor(formato_excel)"
                        >
                            <vs-select-item
                                :key="index"
                                :value="item.value"
                                :text="item.text"
                                v-for="(item, index) in grupo_formatoexcel"
                            />
                        </vs-select>
                    </div>
                            <!--prueba-->
                        </div>
                        <div class="vx-col sm:w-full w-full mt-5">
                            <vs-button
                                color="success"
                                type="filled"
                                @click="exportardatos()"
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

                                <div class="centimg vx-card input" @click="importarexcel()" >
                                    <img src="/images/upload.png" />

                                    <div v-if="file.length === 0" style="position:absolute;margin-top:60px;color:#000" >Click para subir Archivo</div>
                                    <div v-else  style="position:absolute;margin-top:60px;color:#000">{{file[0].name}}</div>
                                </div>  
                            </div> 
                        </div>

                        <!--@click="importardatos()"-->
                       
                        <!---->
                      
                        <div class="vx-col sm:w-full w-full mb-6">
                            <vs-button color="success" @click="importardatos()" >Subir Archivo</vs-button>
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
        <!--@click="importar = false" =============================================== fin modal de importar ===================================================== -->
    </div>
</template>
<script >

import { AgGridVue } from "ag-grid-vue";
import ImportExcel from "@/components/excel/ImportExcel.vue";
import $ from "jquery";
import vSelect from "vue-select";

const axios = require("axios");

export default {
    components: {
        AgGridVue,
        ImportExcel
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
                res = this.$store.state.Roles[15].crear;
            }
            return res;
        },
        editarrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[15].editar;
            }
            return res;
        },
        eliminarrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[15].eliminar;
            }
            return res;
        },
        
    },
   
    data() {
        return {
               formato_excel: "",
               grupo_formatoexcel: [
                { text: "xls", value: "xls" },
                { text: "xlsx", value: "xlsx" },
                { text: "cvs", value: "cvs" }
            ],
            
            //excel import
            file: [],
            tableData: [],
            header: [],
            sheetName: "",
            //mapeo de datos
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
            cantidadp: 1000000,
            //buscador
            buscar: "",
            //otros valores
            gridApi: null,
            contenido: [],
            //lenguaje
            i18nbuscar: this.$t("i18nbuscar"),
            //ERRORES
            error: 0,
            erroremail: [],
            //Datos para la importaciond de archivos
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
                "id_vendedor",
                "codigo_vendedor",
                "nombre_vendedor",
                "email_vendedor",
                "id_user",
                "id_empresa"
            ],
            //campos elegidos a exportar
            indexs: [
                "codigo_vendedor",
                "nombre_vendedor",
                "email_vendedor",
                "id_user",
                "id_empresa"
            ],
            //cabezera que imprimira en el excel
            cabezera: [
                "codigo_vendedor",
                "nombre_vendedor",
                "email_vendedor",
                "id_usuarios",
                "id_empresa"
            ]
        };
    },
    
    methods: {
       
        
         exportVendedor(type) {
             console.log(type)
            axios
                .get("/api/downloadExcel/" + type,  "/api/export");
        },
        exportVendedor1() {
            // console.log(type)
            axios
                .get("/api/importExport")
                
                .then(res => {
                    this.$vs.notify({
                       title: "excel decarga de Registro",
                        text: "El Vendedor ",
                        color: "success"
                    });  
                })
                .catch(err => {
                    console.log(err);
                });
        },
        
        //preload
        readyFile ($e){
            // Este muestra una lista FileList
            console.log($e.target.files)
            // Este muestra el valor actual del input
            console.log($e.target.value)
        },
      
        /**
         * Cancelar un registro
         */
        cancelar() {
            this.$router.push("/facturacion/vendedor");
        },
        cancelar1() {
            this.importar = false;
            this.file = [];
            document.getElementById("input-upload").value = ""
        },
        /**
         * Lista los datos del vendedor en una tabla
         **/
        listar(page, buscar) {
            var url =
                "/api/listarvendedorcliente/" +
                this.usuario.id_empresa +
                "?page=" +
                page +
                "&buscar=" +
                buscar;
            axios.get(url).then(res => {
                this.contenido = res.data.recupera;
            });
        },
        /*
         * exportar archivo
         */
        exportardatos() {
            import("../../../vendor/Export2VendedorExcel").then(excel => {
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
       
        /*
         *importar archivos
         *
         */
         subirArchivo(e) {
            this.file = []
             //console.log(">>",e)
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
        
        importarexcel() {
           // console.log(this.file)
            $(".inputexcel").click();
            
        },
        importardatos() {
            let formData = new FormData();

            formData.append("id_empresa", this.usuario.id_empresa);
            formData.append("file", this.file[0]);
            axios
                .post("/api/ImportarVendedoresExcel", formData, {})
                .then((res) => {
                    document.getElementById("input-upload").value = ""
                    this.$vs.notify({
                                text: "Archivo Importado con exito",
                                color: "success",
                            });
                            this.importar = false;
                            this.listar(1, this.buscar);
                            this.file = [];
                            console.log(res);
                    //console.log("error"+res);
                    /*
                    if (res == 'errores'){
                        this.$vs.notify({
                        text: "Archivo Importado 'Sin Exito'",
                        color: "danger",
                        });
                    }else{
                        this.$vs.notify({
                        text: "Archivo Importado con exito",
                        color: "success",
                        });

                        this.importar = false;
                        this.listar(1, this.buscar);

                    }
                    */
                    // try {
                    //     if (res === 'err'){
                    //         throw 'error1'
                        
                    //     }else{
                    //         throw 'error2'
                    //     }
                    // }   
                    // catch (e) {
                    //     if(e === "error1"){
                    //         this.$vs.notify({
                    //         text: "Archivo Importado 'Sin Exito'",
                    //         color: "danger",
                    //         });
                    //     }
                    //     if(e === "error2"){
                    //         this.$vs.notify({
                    //             text: "Archivo Importado con exito",
                    //             color: "success",
                    //         });
                    //         this.importar = false;
                    //         this.listar(1, this.buscar);
                    //         this.file = [];
                    //     }
                    // }
                })
                .catch((err) => {
                    console.log("error");
                    console.log(err);
                    this.$vs.notify({
                        text: "Archivo Importado 'Sin prueba Exito'",
                        color: "danger",
                    });
                    this.importar = false;
                    this.listar(1, this.buscar);
                    this.file = []
                   //console.log(err);
                });
        },
        /*
         * envia a formulario para crear
         */
        crear() {
            this.$router.push("/facturacion/vendedor/agregar");
        },
        /*
         * envia a formulario para editar
         */
        editar(id) {
            this.$router.push(`/facturacion/vendedor/${id}/editar`);
        },
        /*
         * elimina registro de los vendedores
         */
        eliminarVendedor(id) {
            // axios.delete("/api/eliminarvendedorcliente/" + id);
            // this.$vs.dialog({
            //     type: "confirm",
            //     color: "danger",
            //     title: `Confirmar`,
            //     text: "¿Desea Elimnar este registro?",
            //     accept: this.acceptAlert
            // });
            this.$vs.dialog({
                type: "confirm",
                color: "danger",
                title: `Confirmar`,
                text: `¿Desea Eliminar este registro?:`,
                acceptText: "Aceptar",
                cancelText: "Cancelar",
                accept: this.acceptAlert,
                parameters: id
            });
        },
        /**
         * mensaje de alerta
         * recibe id por cd
         * de la function eliminar
         */
        acceptAlert(id) {
            axios
            .delete("/api/eliminarvendedorcliente/" + id)
            .then(resp=>{
                this.$vs.notify({
                    color: "danger",
                    title: "Vendedor Eliminado  ",
                    text: "El vendedor selecionado fue eliminado con exito"
                });
                this.listar(1, this.buscar, this.cantidadp);
            })
            .catch(err=>{
                this.$vs.notify({
                    color: "danger",
                    title: "No se pudo eliminar el vendedor",
                });
                //this.listar(this.buscar);
            });
        }
    },
    mounted() {
        this.listar(1, this.buscar, this.cantidadp);
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
