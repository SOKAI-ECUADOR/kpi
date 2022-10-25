<template>
    <div id="ag-grid-demo">
        <vx-card>
            <div class="flex flex-wrap justify-between items-center mb-3">
                <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"></div>
                <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
                    <vs-input class="mb-4 md:mb-0 mr-4" v-model="buscar" @keyup="listar(buscar)" placeholder="Buscar..."/>
                    <div class="dropdown-button-container">
                        <vs-button class="btnx" type="filled" @click="abrirmodal('agregar')">Agregar</vs-button>
                        <vs-dropdown>
                            <vs-button class="btn-drop" type="filled" icon="expand_more"></vs-button>
                            <vs-dropdown-menu style="width:13em;">
                                <vs-dropdown-item class="text-center" @click="importar = true">Importar registros</vs-dropdown-item>
                                <vs-dropdown-item class="text-center" @click="exportar = true">Exportar registros</vs-dropdown-item>
                            </vs-dropdown-menu>
                        </vs-dropdown>
                    </div>
                </div>
            </div>
            <vs-table stripe :data="contenido">
                <template slot="thead">
                    <vs-th>Código</vs-th>
                    <vs-th>Descripción</vs-th>
                    <vs-th>Opciones</vs-th>
                </template>
                <template slot-scope="{ data }">
                    <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                        <vs-td v-if="tr.codigo">{{ tr.codigo }}</vs-td>
                        <vs-td v-if="tr.descripcion">{{ tr.descripcion }}</vs-td>
                        <vs-td class="whitespace-no-wrap">
                            <vx-tooltip text="Editar Proyecto" style="display: inline-flex;">
                                <feather-icon icon="EditIcon" class="cursor-pointer" svgClasses="w-5 h-5 hover:text-primary stroke-current" @click="abrirmodal('editar', tr)" />
                            </vx-tooltip>
                            <vx-tooltip text="Eliminar Proyecto" style="display: inline-flex;">
                                <feather-icon icon="TrashIcon" svgClasses="w-5 h-5 hover:text-danger stroke-current" class="ml-2 cursor-pointer" @click="eliminar(tr.id_forma_pagos_sri)" />
                            </vx-tooltip>
                        </vs-td>
                    </vs-tr>
                </template>
            </vs-table>

            <vs-popup :title="modal.titulo" :active.sync="modal.abrir">
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        <div class="vx-col sm:w-full w-full mb-6">
                            <vs-input class="selectExample w-full" label="Código:" v-model="tabla.codigo" />
                            <div v-show="validacion.estado">
                                <span class="text-danger" v-for="err in validacion.valores.codigo" :key="err" v-text="err"></span>
                            </div>
                        </div>
                        <div class="vx-col sm:w-full w-full mb-6">
                            <label for="" class="vs-input--label">Descripción:</label>
                            <vs-textarea class="selectExample w-full" v-model="tabla.descripcion" rows="3" />
                            <div v-show="validacion.estado" v-if="!tabla.descripcion">
                                <span class="text-danger" v-for="err in validacion.valores.descripcion" :key="err" v-text="err"></span>
                            </div>
                        </div>
                    </div>
                    <vs-button color="success" v-if="modal.tipo==1" @click="guardar()">GUARDAR</vs-button>
                    <vs-button color="success" v-if="modal.tipo==2" @click="editar()">GUARDAR</vs-button>
                    <vs-button color="danger" @click="modal.abrir = false">CANCELAR</vs-button>
                </div>
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
                                    
                                       
                                        type="file"
                                        class="custom-file-input inputexcel"
                                        @change="obtenerimagen"
                                        accept=".XLSX, .CSV"
                                         
                                    />
                                </div>

                                <div 
                                    class="centimg vx-card input"
                                    @click="importarexcel()"
                                    
                                >
                                    <img src="/images/upload.png" />
                                    <div class="demo-alignment"
                                        
                                        style="position:absolute;margin-top:60px;color:#000"
                                    >
                                        Click para subir Archivo
                                    </div>
                                    
                                </div>
                            </div>
                            <!--preload-->
                            <!--fin preload-->
                            
                        </div>
                      
                        <div class="vx-col sm:w-full w-full mb-6">
                            <vs-button color="success" @click="importardatos()"
                                >Subir Archivo</vs-button
                            >
                            <vs-button
                                color="danger"
                                type="filled"
                                @click="importar = false"
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
    },
    data() {
        return {
            contenido:[],
            buscar:'',
            modal:{
                abrir:false,
                tipo:0,
                titulo:'',
            },
            tabla:{
                id:null,
                codigo:'',
                descripcion:'',
            },
            validacion:{
                estado:0,
                valores:{
                    codigo:[],
                    descripcion:[]
                }
            },
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
                "id_forma_pagos_sri",
                "codigo",
                "descripcion",
                "id_empresa"
            ],
            //campos elegidos a exportar
            indexs: [
                "codigo",
                "descripcion",
                "id_empresa"
                
            ],
            //cabezera que imprimira en el excel
            cabezera: [
                "codigo",
                "descripcion",
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
    },
    methods: {
        /*
         * exportar archivo
         */
        exportardatos() {
            import("../../../../vendor/Export2FormaPasoSriExcel").then(excel => {
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
         obtenerimagen(e) {
            let file = e.target.files[0];
            var allowedExtensions = /(.csv|.xls|.xlsx)$/i;
            if (!allowedExtensions.exec(file.name)) {
                this.$vs.notify({
                    title: "Tipo de archivo no compatible",
                    text: "Formatos aceptados: .csv, .xls, .xlsx",
                    color: "danger"
                });
                return;
            }
            this.file = file;
        },
        loadDataInTable({ results, header, meta }) {
            this.header = header;
            this.tableData = results;
            this.sheetName = meta.sheetName;
        },
        importarexcel() {
            $(".inputexcel").click();
            
        },
        importardatos() {
            let formData = new FormData();

            formData.append("id_empresa", this.usuario.id_empresa);
            formData.append("file", this.file);
            axios
                .post("/api/ImportarFormasPagoSriExcel", formData, {})
                .then((res) => {
                    
                    try {
                        if (res === 'err'){
                            throw 'error1'
                        
                        }else{
                            throw 'error2'
                        }
                    }   
                    catch (e) {
                        if(e === "error1"){
                            this.$vs.notify({
                            text: "Archivo Importado 'Sin Exito'",
                            color: "danger",
                            });
                        }
                        if(e === "error2"){
                            this.$vs.notify({
                                text: "Archivo Importado con exito",
                                color: "success",
                            });
                            this.importar = false;
                            this.listar(this.buscar);
                        }
                    }
                })
                .catch((err) => {
                    /*
                    this.$vs.notify({
                        text: "Archivo Importado 'Sin Exito'",
                        color: "danger",
                    });
                    */
                    
                   //console.log(err);
                });
        },
        listar(buscar){
            axios.get("/api/forma_pagos_sri/listar?buscar=" + buscar + "&empresa=" + this.usuario.id_empresa).then( ({data}) => {
                this.contenido = data;
            }).catch( err => {
                console.log(err);
            });
        },
        abrirmodal(tipo, data){
            switch (tipo) {
                case "agregar": {
                    this.modal = {
                        abrir:true,
                        tipo:1,
                        titulo:'Agregar Forma de pago',
                    },
                    this.tabla = {
                        id:null,
                        codigo:'',
                        descripcion:'',
                    } 
                    break;
                }
                case "editar": {
                    this.modal = {
                        abrir:true,
                        tipo:2,
                        titulo:'Editar Forma de pago',
                    },
                    this.tabla = {
                        id:data.id_forma_pagos_sri,
                        codigo:data.codigo,
                        descripcion:data.descripcion,
                    }
                    break;
                }
            }
        },
        guardar(){
            if(this.validar()){return;}
            axios.post("/api/forma_pagos_sri/guardar", {tabla:this.tabla,empresa:this.usuario.id_empresa}).then( () => {
                this.$vs.notify({
                    title: "Registro Exitoso",
                    text: "Registro agregado con éxito",
                    color: "success"
                });
                this.modal.abrir = false;
                this.listar(this.buscar);
            }).catch( err => {
                console.log(err);
            });
        },
        editar(){
            if(this.validar()){return;}
            axios.put("/api/forma_pagos_sri/editar", {tabla:this.tabla}).then( () => {
                this.$vs.notify({
                    title: "Registro Exitoso",
                    text: "Registro actualizado con éxito",
                    color: "success"
                });
                this.modal.abrir = false;
                this.listar(this.buscar);
            }).catch( err => {
                console.log(err);
            });
        },
        eliminar(id){
            this.$vs.dialog({
                type: "confirm",
                color: "danger",
                title: `Confirmar`,
                text: `¿Desea Eliminar este registro?`,
                acceptText: "Aceptar",
                cancelText: "Cancelar",
                accept: this.acceptAlert,
                parameters: id
            });
        },
        acceptAlert(parameters) {
            axios.delete("/api/forma_pagos_sri/eliminar/" + parameters);
            this.$vs.notify({
                title: "Registro eliminado",
                text: "Este registro ha sido eliminado exitosamente",
                color: "success"
            });
             this.listar(this.buscar);
        },
        validar(){
            this.validacion = {
                estado:0,
                valores:{
                    codigo:[],
                    descripcion:[]
                }
            }

            if(this.tabla.codigo.length<=1){
                this.validacion.valores.codigo.push("Debe Ingresar un Código Válido");
                this.validacion.estado = 1;
            }

            if(!this.tabla.descripcion){
                this.validacion.valores.descripcion.push("Debe Ingresar una Descripcion");
                this.validacion.estado = 1;
            }

            return this.validacion.estado;
        }   
    },
    mounted() {
        this.listar(this.buscar);
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
