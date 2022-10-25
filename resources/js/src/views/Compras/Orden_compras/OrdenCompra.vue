<template>
  <div id="ag-grid-demo">
    <vx-card>
      <div class="flex flex-wrap justify-between items-center mb-3">
        <!-- ITEMS PER PAGE -->
        <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"></div>
        <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
          <vs-input
            class="mb-4 md:mb-0 mr-4"
            v-model="buscar"
            @keyup="listar(1,buscar)"
            v-bind:placeholder="i18nbuscar"
          />
          <div class="dropdown-button-container">
            <vs-button class="btnx" type="filled" to="/compras/orden-compra/agregar">Agregar</vs-button>
            <vs-dropdown>
              <vs-button class="btn-drop" type="filled" icon="expand_more"></vs-button>
              <vs-dropdown-menu style="width:13em;">
                <vs-dropdown-item class="text-center" divider>Importar registros</vs-dropdown-item>
                <vs-dropdown-item class="text-center">Exportar registros</vs-dropdown-item>
                <vs-dropdown-item class="text-center" divider>Generar PDF</vs-dropdown-item>
                <vs-dropdown-item class="text-center">Generar XML</vs-dropdown-item>
              </vs-dropdown-menu>
            </vs-dropdown>
          </div>
        </div>
      </div>

      <vs-table stripe :data="contenido">
        <template slot="thead">
          <vs-th>No.</vs-th>
          <vs-th>Cliente</vs-th>
          <vs-th>Fecha de Emisión</vs-th>
          <!--<vs-th>Estatus</vs-th>-->
          <vs-th>Valor Total</vs-th>
          <vs-th>Opciones</vs-th>
        </template>
        <template slot-scope="{data}">
          <vs-tr :key="datos.id_factcompra" v-for="datos in data">
            <vs-td v-if="datos.orden_compra">{{datos.orden_compra}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.nombre_proveedor">{{datos.nombre_proveedor}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.fech_emision">{{datos.fech_emision | fecha}}</vs-td>
            <vs-td v-else>-</vs-td>
            <!--<vs-td v-if="datos.modo==1"><div style="color:#61B633;">Facturado</div></vs-td>
            <vs-td v-else><div style="color:#CE7C3B;">Sin Facturar</div></vs-td>-->
            <vs-td v-if="datos.total_factura">{{datos.total_factura | currency}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td class="whitespace-no-wrap">
              <vx-tooltip text="Editar Orden" style="display: inline-flex;">
              <feather-icon
                icon="EditIcon"
                svgClasses="w-5 h-5 hover:text-primary stroke-current cursor-pointer"
                @click.stop="editar(datos.id_factcompra)"
              />
              </vx-tooltip>
              <vx-tooltip text="Facturar" style="display: inline-flex;">
              <feather-icon
                v-if="datos.facturado_orden!=null"
                icon="ShoppingCartIcon"
                svgClasses="w-5 h-5 fill-current text-success"
                class="ml-2 pointer"
                @click.stop="crearfactura(datos.id_factcompra)"
              />
              <feather-icon
                v-else
                icon="ShoppingCartIcon"
                svgClasses="w-5 h-5 fill-current text-primary"
                class="ml-2 pointer"
                @click.stop="crearfactura(datos.id_factcompra)"
              />
              </vx-tooltip>
              <vx-tooltip text="Eliminar Orden" style="display: inline-flex;">
              <feather-icon
                icon="TrashIcon"
                svgClasses="w-5 h-5 hover:text-danger stroke-current cursor-pointer"
                class="ml-2"
                @click.stop="eliminarprof(datos.id_factcompra)"
              />
              </vx-tooltip>
              
                            <vs-dropdown>
                                <feather-icon
                                    icon="MailIcon"
                                    svgClasses="w-5 h-5 hover:text-danger stroke-current cursor-pointer"
                                    class="ml-2"
                                />
                                <vs-dropdown-menu>
                                    <!-- prettier-ignore -->
                                    <vs-dropdown-item
                                        @click.stop="
                                            generarPdf(datos.id_factcompra,1,datos.nombre_proveedor,datos.email)"
                                    >
                                        Enviar a Proveedor
                                    </vs-dropdown-item>
                                    <!-- prettier-ignore -->
                                    <vs-dropdown-item
                                        @click.stop="
                                            enviarotrocorreo(datos.id_factcompra)"
                                    >
                                        Enviar a otro correo
                                    </vs-dropdown-item>
                                </vs-dropdown-menu>
                            </vs-dropdown>
              <vx-tooltip text="Generar PDF" style="display: inline-flex;">
                <feather-icon
                                icon="PrinterIcon"
                                svgClasses="w-5 h-5 hover:text-primary stroke-current cursor-pointer"
                                class="ml-2"
                                @click="generarPdf(datos.id_factcompra,1)"
                            />
              </vx-tooltip>
              
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
      <vs-popup title="Destinatario de Correo" :active.sync="popupcorreo">
            <div class="vx-col sm:w-full w-full mb-6 relative">
                <vs-input
                    class="w-full"
                    label="Nombre:"
                    v-model="nombrecliente"
                />
            </div>
            <div class="vx-col sm:full w-full mb-6 relative">
                <!--<vs-input
                    class="w-full"
                    label="Dirección de Correo Electrónico:"
                    v-model="correocliente"
                />-->
                <vs-chips color="rgb(145, 32, 159)" label="E-mail" placeholder="Agregue los correos" v-model="correocliente" icon-pack="feather" remove-icon="icon-trash-2">
                            <vs-chip :key="data" @click="remove_chip_correo(data)" v-for="data in correocliente" closable icon-pack="feather" close-icon="icon-trash-2"> 
                                {{ data }}
                            </vs-chip>
                </vs-chips>
                <span style="font-size: 11px;margin-left: 10px;">despues de agregar un correo pulse la tecla enter</span>
                <!-- <div v-show="errorenvio">
                          <div
                              v-for="err in errorcorreo"
                              :key="err"
                              v-text="err"
                              class="text-danger"
                          ></div>
                      </div> -->
            </div>
            <div class="vx-col w-full mt-5">
                <vs-button
                    color="success"
                    type="border"
                    @click="generarPdf(id_orden,0,nombrecliente,correocliente)"
                    >Enviar</vs-button
                >
                <vs-button color="danger" type="border" @click="cancelarenvio()"
                    >Cancelar</vs-button
                >
            </div>
        </vs-popup>
    </vx-card>
  </div>
</template>
<script>
import { AgGridVue } from "ag-grid-vue";
import moment from "moment";
moment.locale("es");
const $ = require("jquery");
const axios = require("axios");
export default {
  components: {
    AgGridVue
  },
  filters: {
    fecha(data) {
      return moment(data).format("LL");
    }
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
        res = this.$store.state.Roles[8].crear;
      }
      return res;
    },
    editarrol(){
      var res = 0;
      if(this.usuario.id_rol==1){
        res=1
      }else{
        res = this.$store.state.Roles[8].editar;
      }
      return res;
    },
    eliminarrol(){
      var res = 0;
      if(this.usuario.id_rol==1){
        res=1
      }else{
        res = this.$store.state.Roles[8].eliminar;
      }
      return res;
    }
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
      cantidadp: 10,
      offset: 3,
      id_orden:"",
      nombrecliente: "",
      correocliente: [],
      popupcorreo: false,
      //buscador
      buscar: "",
      criterio: "secuencial",
      //otros valores
      gridApi: null,
      contenido: [],
      //lenguaje
      i18nbuscar: this.$t("i18nbuscar"),
      //errores
      errorenvio:0,
      errornombre:[],
      errorcorreo:[]
    };
  },
  methods: {
    listar(page, buscar) {
      var url =
        "/api/ordencompra/" +
        this.usuario.id_empresa +
        "?page=" +
        page +
        "&buscar=" +
        buscar;
      axios.get(url).then(res => {
        var respuesta = res.data;
        this.contenido = respuesta.recupera;
      });
    },
    eliminarprof(cd) {
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
      axios.delete("/api/eliminarorden/" + parameters);
      this.$vs.notify({
        color: "success",
        title: "Orden Eliminada  ",
        text: "Orden eliminada con exito"
      });
      this.listar(1, this.buscar);
    },
    updateSearchQuery(val) {
      this.gridApi.setQuickFilter(val);
    },
    editar(id) {
      this.$router.push(`/compras/orden-compra/${id}/editar`);
    },
    crearfactura(id) {
      this.$router.push(`/compras/factura-compra/${id}/editar`);
    },
    generarPdf(cod_rol,tipo,destinatario=null,email=null){
      //this.id_orden=cod_rol;
      axios({
                    url: "/api/pdf/orden_compra",
                    method: "GET",
                    responseType: "arraybuffer",
                    params:{
                      id_orden:cod_rol,
                      id_empresa:this.usuario.id_empresa,
                      destinatario:destinatario,
                      email:email
                    }
                
                }).then(resp=>{
                  console.log("ejecutado empleado");
                //this.contenidopr=res.data;
                console.log("resp:"+resp);
                  console.log("resp data:"+resp.data);

                //   var decodedString = String.fromCharCode.apply(
                //     null,
                //     new Uint8Array(resp.data)
                // );
                // if (decodedString.includes("no-data-report")) {
                //     this.$vs.notify({
                //       title: "Sin Registros",
                //       text: "Los Datos que escogio no tienen registros",
                //       color: "danger"
                //     });
                // }
                
                let { headers } = resp;
                let nameFile = headers["content-disposition"]
                    .split(";")[1]
                    .split("=")[1]
                    .replace(/"/g, "");
                const url = window.URL.createObjectURL(
                    new Blob([resp.data], { type: "application/pdf" })
                );
                console.log("nombre:"+nameFile+"url:"+url);
                //return({ url: url, nameFile: nameFile });
                console.log("URL_NAME::", url, nameFile);
                const link = document.createElement("a");
                link.href = url;
                link.download = "Reporte.pdf";
                link.setAttribute("download", nameFile);
                document.body.appendChild(link);
                link.click();
                if(destinatario==null && email==null){
                  this.$vs.notify({
                      title: "Pdf Generado",
                      text: "Su pdf ha sido descargado exitosamente!",
                      color: "success"
                  });
                }else{
                  this.enviarorden(cod_rol,tipo,destinatario,email);
                }
                

                /*this.modal_proyecto=false;
                this.dep_rol="";
                this.fechrolprov="";
                this.datos_proyecto.selectedproyecto=[];*/
                }).catch(err=>{
                  console.log("ERROR generar pdf"+err);
                    if(destinatario==null && email==null){
                      this.$vs.notify({
                          title: "Pdf Generado",
                          text: "Su pdf ha sido descargado exitosamente!",
                          color: "success"
                      });
                    }else{
                      this.enviarorden(cod_rol,tipo,destinatario,email);
                    }
                });
    },
    enviarotrocorreo(id){
      this.id_orden=id;
      this.nombrecliente = "";
      this.correocliente = [];
      this.popupcorreo = true;
    },
    enviarorden(id,tipo,destinatario,email){
      
            this.$vs.notify({
                time: 3000,
                title: "Enviando Orden",
                text: "Por favor espere...",
                color: "warning"
            });
            axios
                .post("/api/orden_compra/enviarcorreo", { id: id, tipo: tipo,destinatario:destinatario,email:email })
                .then(() => {
                    this.$vs.notify({
                        title: "Orden enviada",
                        text: "Orden enviada exitosamente",
                        color: "success"
                    });
                    this.popupcorreo = false;
                    this.id_orden="";
                    this.nombrecliente = "";
                    this.correocliente = [];
                    
                }).catch(err=>{
                  console.log("ERROR al enviar correo al proveedor :"+err);
                });
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
</style>