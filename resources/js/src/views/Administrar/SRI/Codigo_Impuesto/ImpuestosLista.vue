<template>
  <div id="ag-grid-demo">
    <vx-card>
      <div class="flex flex-wrap justify-between items-center">
        <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left">
        </div>
        <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
          <vs-input class="mb-4 md:mb-0 mr-4" v-model="buscar" @keyup="listar(1,buscar,criterio,cantidadp)" v-bind:placeholder="i18nbuscar"/>
          <div class="dropdown-button-container">
            <vs-button class="btnx" type="filled" @click="agregarImp()">Agregar</vs-button>
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
      <br>
      <vs-table stripe :data="contenido">
        <template slot="thead">
          <vs-th>Codigo.</vs-th>
          <vs-th>Descripcion </vs-th>
          <vs-th>Tipo</vs-th>
          <vs-th>Porcentaje</vs-th>
          <vs-th>Acciones</vs-th>
        </template>
        <template slot-scope="{data}">
          <vs-tr :key="datos.id" v-for="datos in data">
            <vs-td>{{datos.cod_imp }}</vs-td>
            <vs-td v-if="datos.descrip_imp">{{datos.descrip_imp}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.tipo_imp">{{datos.tipo_imp }}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.porcen_imp">{{datos.porcen_imp }}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td class="whitespace-no-wrap">
                <feather-icon icon="EditIcon" svgClasses="w-5 h-5 hover:text-primary stroke-current" @click.stop="edit(datos.id_imp)" />
                <feather-icon icon="TrashIcon" svgClasses="w-5 h-5 hover:text-danger stroke-current"  @click.stop="eliminar(datos.id_imp)"/>
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
      
      <vs-popup  :title="titulomodal" :active.sync="activePrompt">
        <div class="vx-row">
           <div class="vx-col sm:w-1/6 w-full mb-6">
              <vs-input class="w-full"  label="Codigo" v-model="cod_imp"  maxlength="10"/>
              <div v-show="error" v-if="!cod_imp">
                <div v-for="err in errorcodigo" :key="err" v-text="err" class="text-danger"></div>
              </div>
           </div>
           <div class="vx-col sm:w-1/2 w-full mb-6">
              <vs-input class="w-full"  label="Descripcion" v-model="descrip_imp" maxlength="250"/>
              <div v-show="error" v-if="!descrip_imp">
                <div v-for="err in errordescripcion" :key="err" v-text="err" class="text-danger"></div>
              </div>
           </div>
           <div class="vx-col sm:w-1/6 w-full mb-6">
              <vs-select class="selectExample w-full" label="Tipo" vs-multiple autocomplete v-model="tipo_imp">
                  <vs-select-item value="Fuente" text="Fuente" />
                  <vs-select-item value="Iva" text="Iva" />
                  <vs-select-item value="Inactivo"  text="Inactivo" />
              </vs-select>
              <div v-show="error" v-if="!tipo_imp">
                <div v-for="err in errortipo" :key="err" v-text="err" class="text-danger"></div>
              </div>
           </div>
           <div class="vx-col sm:w-1/6 w-full mb-6">
              <vs-input class="w-full"  label="Porcentaje" v-model="porcen_imp" @keypress="solodecimales($event)" maxlength="9"/>
              <div v-show="error" v-if="!porcen_imp">
                  <div v-for="err in errorporcentaje" :key="err" v-text="err" class="text-danger"></div>
              </div>
           </div>
        </div>
        <div class="vx-col w-full">
            <vs-button color="success" type="filled" @click="guardarImp()" v-if="!idrecupera">GUARDAR</vs-button>
            <vs-button color="success" type="filled" @click="editarImp()" v-else>GUARDAR</vs-button>
            <vs-button color="warning" type="filled" @click="vaciarImp()">BORRAR</vs-button>
            <vs-button color="danger" type="filled" @click="cancelarImp()">CANCELAR</vs-button>
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
import $ from "jquery";
const axios = require("axios");
export default {
  components: {
    AgGridVue,
    ImportExcel
  },
  data() {
    return {
    //mapeo de datos
      cantidadp:20,
      offset: 0,
    //buscador
      buscar: "",
      criterio: "descrip_imp",  
    //otros valores
      contenido: [],
    //lenguaje
      i18nbuscar:this.$t("i18nbuscar"), 
    //modal imp
      activePrompt:false,
      titulomodal:"",
      idrecupera:null,
      cod_imp:"",
      descrip_imp:"",
      tipo_imp:"",
      porcen_imp:"",
    //errores
      error:0,
      errorcodigo:[],
      errordescripcion:[],
      errortipo:[],
      errorporcentaje:[],
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
        "id_imp",
        "cod_imp",
        "descrip_imp",
        "tipo_imp",
        "porcen_imp",
        "id_empresa"
      ],
    //campos elegidos a exportar
      indexs: [
       "cod_imp",
        "descrip_imp",
        "tipo_imp",
        "porcen_imp",
        "id_empresa"
      ],
     //cabezera que imprimira en el excel
      cabezera: [
        "codigo",
        "descripcion",
        "tipo",
        "porcentaje",
        "id_empresa"
      ] 
    };
  },
  computed:{
    usuario() {
      return this.$store.state.AppActiveUser;
    },
    token() {
      return this.$store.state.Token;
    },
  },
  methods: {
    //exportar archivos
    exportardatos() {
        import("../../../../vendor/Export2CodigoImpuestoExcel").then(excel => {
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

    //importar archivos
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
          text: "Formatos aceptados: .csv, .xls, .xlsx",
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
          .post("/api/ImportarImpuestoExcel", formData, {})
          .then((res) => {
              document.getElementById("input-upload").value = ""
              this.$vs.notify({
                  text: "Archivo Importado con exito",
                  color: "success",
              });
              this.importar = false;
              this.listar(1, this.buscar, this.criterio,this.cantidadp);
              this.file = [];
          })
          .catch((err) => {
            this.$vs.notify({
                text: "Archivo Importado 'Sin Exito'",
                color: "danger",
          });
          this.importar = false;
          this.listar(1, this.buscar, this.criterio,this.cantidadp);
          this.file = [];
          });
    },

    
    //abrir modal para agregar Impuestos
    agregarImp(){
      this.activePrompt=true,
      this.titulomodal="Agregar",
      this.iderecupera=null,
      this.cod_imp="",
      this.descrip_imp="",
      this.tipo_imp="",
      this.porcen_imp="",
      this.error=0,
      this.errorcodigo=[],
      this.errordescripcion=[],
      this.errortipo=[],
      this.errorporcentaje=[]
    },
    //guardar impuesto
    guardarImp(){
      if(this.validar()){
            return;
      }
      axios.post("/api/agregarimpuesto",{
        cod_imp:this.cod_imp,
        descrip_imp:this.descrip_imp,
        tipo_imp:this.tipo_imp,
        porcen_imp:this.porcen_imp,
        id_empresa:this.usuario.id_empresa
      }).then(res => {
        this.activePrompt=false;
        this.$vs.notify({
              title:'Registro Guardado',
              text: 'Registro Guardado exitosamente',
              color:'success',
            })
      this.listar(1, this.buscar, this.cantidadp);
      }).catch(err => {
        this.$vs.notify({
              title:'Registro NO Guardado',
              text: 'Revise sus datos antes de guardar',
              color:'danger',
            })
      });
        
    },
    //lista el impuesto seleccionado
    edit(id){
       if(id){
        this.idrecupera = id;
        this.activePrompt=true;
        this.titulomodal="Editar";
        var url = "/api/abririmpuesto/"+id;
        axios.put(url).then( res => {
          let data = res.data[0];
          this.cod_imp = data.cod_imp;
          this.descrip_imp = data.descrip_imp;
          this.tipo_imp = data.tipo_imp;
          this.porcen_imp=data.porcen_imp;
        }).catch( err => {
         // console.log(err);
        });
      }else{
        this.idrecupera = null;
        
      }
    },
    //edita el reguistro seleccionado
    editarImp(){
      if(this.validar()){
            return;
      }
      axios.put("/api/actualizarimpuesto",{
        id:this.idrecupera,
        cod_imp:this.cod_imp,
        descrip_imp:this.descrip_imp,
        tipo_imp:this.tipo_imp,
        porcen_imp:this.porcen_imp,
        id_empresa:this.usuario.id_empresa
      }).then(res => {
        this.activePrompt=false;
        this.$vs.notify({
              title:'Registro Editado',
              text: 'Registro Editado exitosamente',
              color:'success',
            })
            this.vaciarImp();
      this.listar(1, this.buscar, this.cantidadp);
      }).catch(err => {
        this.$vs.notify({
              title:'Registro NO Editado',
              text: 'Revise sus datos antes de editar',
              color:'danger',
            })
      });
    },
    //borra los campos llenos
    vaciarImp(){
      this.cod_imp="",
      this.descrip_imp="",
      this.tipo_imp="",
      this.porcen_imp=""
    },
    //cancela y cierra el modal
    cancelarImp(){
      this.activePrompt=false,
      this.cod_imp="",
      this.descrip_imp="",
      this.tipo_imp="",
      this.porcen_imp=""
    },
    //funcion solo para ingresar decimales
    solodecimales:function($event) {
      let keyCode = $event.keyCode ? $event.keyCode : $event.which;
      if ((keyCode < 48 || keyCode > 57) && keyCode !== 46) {
        $event.preventDefault();
      }
    },
    //modal para eliminar reguistro
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
    //elimina el reguistro
    acceptAlert(parameters) {

      axios.delete("/api/eliminarimpuesto/" + parameters)
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
                text: "Este reguistro esta siendo utilizado"
                });
            });
    },
    //lista todos los reguistros de impuestos
    listar(page, buscar, criterio,cantidadp) {
        let me = this;
        var url =
        "/api/impuesto?id_empresa=" +
        this.usuario.id_empresa +
        "&buscar=" +
        buscar 
        axios.get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.contenido = response.data.recupera;
          
        })
        .catch(function(error) {
          this.$vs.notify({
                color: "danger",
                title: "Error al eliminar",
                text: error
                });
        });
    },
    //valida los campos necesarios
    validar(){
      this.error=0;
      this.errorcodigo=[];
      this.errordescripcion=[];
      this.errortipo=[];
      this.errorporcentaje=[];
      if(!this.cod_imp){
        this.errorcodigo.push("Campo Obligatorio");
        this.error=1;
      }
      if(!this.descrip_imp){
        this.errordescripcion.push("Campo Obligatorio");
        this.error=1;
      }
      if(!this.tipo_imp){
        this.errortipo.push("Campo Obligatorio");
        this.error=1;
      }
      if(!this.porcen_imp){
        this.errorporcentaje.push("Campo Obligatorio");
        this.error=1;
      }
      return this.error;
    },
  },
  //funciones que se ejecutan al iniciar
  mounted() {
    this.listar(1, this.buscar, this.criterio,this.cantidadp);
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
