<template>
  <div id="ag-grid-demo">
    <vx-card>
      <div class="flex flex-wrap justify-between items-center">
        <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left">
        </div>
        <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
          <vs-input class="mb-4 md:mb-0 mr-4" v-model="buscar" @keyup="listar(1,buscar)" v-bind:placeholder="i18nbuscar"/>
          <div class="dropdown-button-container" v-if="crearrol">
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
      
      <vs-table stripe :data="contenidotipo">
        <template slot="thead">
          <vs-th>Cod. Sri</vs-th>
          <vs-th>Descripcion</vs-th>
          <vs-th>Acciones</vs-th>
        </template>
        <template slot-scope="{data}">
          <vs-tr :key="datos.id_tipcomprob" v-for="datos in data">
            <vs-td style="width: 80px;" v-if="datos.cod_tipcomprob">{{datos.cod_tipcomprob}}</vs-td>
            <vs-td style="width: 80px;" v-else>-</vs-td>
            <vs-td v-if="datos.descrip_tipcomprob">{{datos.descrip_tipcomprob}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td class="whitespace-no-wrap">
              <feather-icon v-if="editarrol" icon="EditIcon" class="cursor-pointer" svgClasses="w-5 h-5 hover:text-primary stroke-current" @click.stop="verTipo(datos.id_tipcomprobante)" />
              <feather-icon v-if="eliminarrol" icon="TrashIcon" class="cursor-pointer" svgClasses="w-5 h-5 hover:text-danger stroke-current"  @click.stop="eliminarTipo(datos.id_tipcomprobante)"/>
            </vs-td>
            </vs-tr>
          </template>
      </vs-table>
      <!--Modal para agregar Comprobante-->
      <vs-popup :title="titulomodal" :active.sync="activePrompt">
          <div class="vx-row">
              <div class="vx-col sm:w-full w-full mb-6">
                <vs-input class="w-full" label="Codigo Sri" v-model="cod_tipcomprob"   maxlength="10"/>
                <div v-show="error" v-if="!cod_tipcomprob">
                    <div v-for="err in errorcodigo" :key="err" v-text="err" class="text-danger"></div>
                </div>
              </div>
              <div class="vx-col sm:w-full w-full mb-6">
                <label class="vs-input--label">Descripción</label>
                <vs-input class="w-full" v-model="descrip_tipcomprob" rows="3" maxlength="199"/>
                <div v-show="error" v-if="!descrip_tipcomprob">
                    <div v-for="err in errordescripcion" :key="err" v-text="err" class="text-danger"></div>
                </div>
              </div>
          </div>
          <div class="vx-col w-full">
              <vs-button color="success" type="filled" @click="guardarTipo()" v-if="!idrecupera">GUARDAR</vs-button>
              <vs-button color="success" type="filled" @click="editarTipo()" v-else>GUARDAR</vs-button>
              <vs-button color="warning" type="filled" @click="vaciarTipo()">BORRAR</vs-button>
              <vs-button color="danger" type="filled" @click="cancelarTipo()">CANCELAR</vs-button>
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
    ImportExcel,
  },
  data() {
    return {
      //mapeo de datos
     //buscador
      buscar: "", 
     //otros valores
      contenidotipo: [],
      activePrompt: false,
     //lenguaje
      i18nbuscar:this.$t("i18nbuscar"), 
     //modal imp
      activePrompt:false,
      titulomodal:"",
      idrecupera:null,
      cod_tipcomprob:"",
      descrip_tipcomprob:"",
      //errores
      error:0,
      errorcodigo:[],
      errordescripcion:[],
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
        "id_tipcomprobante",
        "cod_tipcomprob",
        "descrip_tipcomprob",
        "id_empresa"
      ],
      //campos elegidos a exportar
      indexs: [
        "cod_tipcomprob",
        "descrip_tipcomprob",
        "id_empresa"
      ],
      //cabezera que imprimira en el excel
      cabezera: [
       "codigo_sri",
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
    crearrol() {
      var res = 0;
      if(this.usuario.id_rol==1){
        res=1
      }else{
        res = this.$store.state.Roles[5].crear;
      }
      return res;
    },
    editarrol(){
      var res = 0;
      if(this.usuario.id_rol==1){
        res=1
      }else{
        res = this.$store.state.Roles[5].editar;
      }
      return res;
    },
    eliminarrol(){
      var res = 0;
      if(this.usuario.id_rol==1){
        res=1
      }else{
        res = this.$store.state.Roles[5].eliminar;
      }
      return res;
    }
  },
  methods: {
    //exportar archivos
  exportardatos() {
    import("../../../../vendor/Export2TipoComprobanteExcel").then(excel => {

      const list = this.contenidotipo;
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
          .post("/api/ImportarTipoComprobanteExcel", formData, {})
          .then((res) => {
             document.getElementById("input-upload").value = ""
              this.$vs.notify({
                text: "Archivo Importado con exito",
                color: "success",
              });
              this.importar = false;
              this.listar(1, this.buscar);
              this.file = []; 
            })
            .catch((err) => {
              this.$vs.notify({
              text: "Archivo Importado 'Sin Exito'",
              color: "danger",
            });
            this.importar = false;
            this.listar(1, this.buscar);
            this.file = [];
            });
  },
  
  //modal para agregar comprobante
    agregarImp(){
      this.activePrompt=true,
      this.titulomodal="Agregar",
      this.idrecupera=null,
      this.cod_tipcomprob="",
      this.descrip_tipcomprob="",
      this.error=0,
      this.errorcodigo=[],
      this.errordescripcion=[]
    },
  //guardar tipo comprobante
    guardarTipo(){
      if(this.validar()){
        return;
      }
      axios.post("/api/agregartipcomprob",{ 
        cod_tipcomprob:this.cod_tipcomprob,
        descrip_tipcomprob:this.descrip_tipcomprob,
        id_empresa:this.usuario.id_empresa
       }).then(res => {
         this.$vs.notify({
              title:'Registro Guardado',
              text: 'Registro Guardado exitosamente',
              color:'success',
            })
            this.activePrompt=false;
            this.listar(1,this.buscar);
       }).catch(err=>{
         this.$vs.notify({
              title:'Error al Gualdar',
              text: 'Compruebe que escribio todos los campos',
              color:'danger',
            })
       });
    },
  //editar tipo comprobante
    editarTipo(){
      if(this.validar()){
        return;
      }
      axios.put("/api/actualizartipcomprob",{ 
        id:this.idrecupera,
        cod_tipcomprob:this.cod_tipcomprob,
        descrip_tipcomprob:this.descrip_tipcomprob,
        id_empresa:this.usuario.id_empresa
       }).then(res => {
         this.$vs.notify({
              title:'Registro Editado',
              text: 'Registro Editado exitosamente',
              color:'success',
            })
            this.activePrompt=false;
            this.listar(1,this.buscar);
       }).catch(err=>{
         this.$vs.notify({
              title:'Error al Editar',
              text: 'Revise bien sus datos antes de editar',
              color:'danger',
            })
       });
    },
  //borrar campos llenos
    vaciarTipo(){
        this.cod_tipcomprob="",
        this.descrip_tipcomprob=""
    },
  //listar tipo comprobante
    verTipo(id){
       if(id){
        this.idrecupera = id;
        this.activePrompt=true;
        this.titulomodal="Editar";
        var url = "/api/abrirtipcomprob/"+id;
        axios.put(url).then( res => {
          let data = res.data[0];
          this.cod_tipcomprob = data.cod_tipcomprob;
          this.descrip_tipcomprob = data.descrip_tipcomprob;
        }).catch( err => {
          //console.log(err);
        });
      }else{
        this.idrecupera = null;
        
      }
    },
  //cancela tipo comprobante
    cancelarTipo(){
      this.activePrompt=false,
      this.cod_tipcomprob="",
      this.descrip_tipcomprob=""
    },
  //modal eliminar tipo comproante
    eliminarTipo(cd) {
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
  //eliminar tipo comprobante
    acceptAlert(parameters) {
      axios.delete("/api/eliminartipcomprob/" + parameters)
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
                text: "Este Reguistro esta siendo utilizado"
                });
            });
      this.listar(1, this.buscar);
    },
  //listar tipo comprobante
    listar(page, buscar) {
      let me = this;
      var url =
        "/api/tipcomprob?id_empresa=" +
        this.usuario.id_empresa +
        "&buscar=" +
        buscar;
        
      axios
        .get(url)
        .then(function(response) { 
          var respuesta = response.data;
          me.contenidotipo = respuesta.recupera;
        })
        .catch(function(error) {
          this.$vs.notify({
           title:'Error al Abrir',
           text: error,
           color:'danger',
         })
        });
    },
    validar(){
    this.error=0,
    this.errorcodigo=[],
    this.errordescripcion=[]
    if(!this.cod_tipcomprob){
      this.errorcodigo.push("Campo Obligatorio");
      this.error=1;
    }
    if(!this.descrip_tipcomprob){
      this.errordescripcion.push("Campo Obligatorio");
      this.error=1;
    }
    return this.error;
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
/*
.vs-dialog {
  max-width: 1024px !important;
}*/
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