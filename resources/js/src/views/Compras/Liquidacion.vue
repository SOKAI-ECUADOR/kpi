<template>
  <div id="ag-grid-demo">
    <vx-card>
      <div class="flex flex-wrap justify-between items-center">
        <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left">
          <vs-dropdown vs-trigger-click class="cursor-pointer"></vs-dropdown>
        </div>
        <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
          
          <div class="dropdown-button-container" v-if="crearrol">
           <!-- <vs-button class="btnx" type="filled" to="/compras/AgregarImportacion">Agregar</vs-button>-->
            <vs-input
            class="mb-4 md:mb-0 mr-4"
            v-model="buscar"
            @keyup="listar(1,buscar,criterio,cantidadp)"
            v-bind:placeholder="i18nbuscar"
          />
            <vs-dropdown>
              <vs-button class="btn-drop" type="filled" icon="expand_more"></vs-button>
              <vs-dropdown-menu style="width:13em;">
                <vs-dropdown-item
                  class="text-center"
                  divider
                  to="/app/agregarEjemplo"
                >Importar registros</vs-dropdown-item>
                <vs-dropdown-item class="text-center">Exportar registros</vs-dropdown-item>
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
          <vs-th>No.Factura</vs-th>
          <vs-th>Total Import</vs-th>
          <vs-th>Total Factura</vs-th>
          <vs-th>Estado</vs-th>
          <vs-th>Total</vs-th>
          <vs-th>Acciones</vs-th>
        </template>
        <template slot-scope="{data}">
          <vs-tr v-for="datos in data" :key="datos.id_importacion">
            <vs-td v-if="datos.id_importacion">{{datos.id_importacion}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.total_importacion">{{datos.total_importacion }}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.totales">{{datos.totales}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.estado">{{datos.estado}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="parseFloat(datos.total_importacion)!=parseFloat(datos.totales)">{{parseFloat(datos.total_importacion)+parseFloat(datos.totales)}}</vs-td>
            <vs-td v-else>{{datos.total_importacion }}</vs-td>
            <vs-td class="whitespace-no-wrap">
              <feather-icon
                v-if="editarrol"
                icon="EyeIcon"
                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                @click="editar(datos.id_importacion)"
              />
              <feather-icon
                v-if="eliminarrol"
                icon="SendIcon"
                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                class="ml-2"
                @click="liquidar(parseFloat(datos.total_importacion)+parseFloat(datos.totales),datos.id_importacion)"
              />
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
      <!--<vs-pagination :total="pagination.count"
        v-model="pagina"
        @change="listar(pagina,buscar,criterio,cantidadp)"
        prev-icon="arrow_back"
        next-icon="arrow_forward"
      ></vs-pagination>-->
    </vx-card>
  </div>
</template>
<script>
import { FormWizard, TabContent } from "vue-form-wizard";
import "vue-form-wizard/dist/vue-form-wizard.min.css";
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import { AgGridVue } from "ag-grid-vue";
import moment from "moment";

const axios = require("axios");
export default {
  components: {
    AgGridVue
  },
  data() {
    return {
      //mapeo de datos
      //paginacion
      /*pagination: {
        total: 0, 
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
        count:0,
      },*/
      pagina: 1,
      cantidadp: 50,
      offset: 3,
      //buscador
      buscar: "",
      criterio: "codcta",
      //otros valores
      gridApi: null,
      contenido: [],
      //lenguaje
      i18nbuscar: this.$t("i18nbuscar"),
      //Modal
      popupActive3: false,
      //campos

      cod_importacion: "",
      nro_orden: "",
      estado: "",
      fech_inicio: "",
      fech_fin: "",
      fech_embarque: "",
      fech_arribo: "",
      //liquidar: "Si",
      cantidad: "",
      costo_unit: "",
      costo_total: "",
      id_proveedor: "",
      id_producto: "",
      //id_user:"",
      //fecult:"",
      configdateTimePicker: {
        locale: SpanishLocale
      },
      //tabla empresa
      empresas: [],
      monedas: [],
      idrecupera: null,
      //modal
      titulomodal: "",
      modal: false,
      //traer proveedor
      proveedors: [],
      //traer productos
      productos: []
    };
  },
  components: {
    flatPickr,
    FormWizard,
    TabContent
  },
  filters: {
    fechasimple(val) {
      return moment(String(val))
        .locale("es")
        .format("LL");
    }
  },
  computed: {
    usuario() {
      return this.$store.state.AppActiveUser;
    },
    token() {
      return this.$store.state.Token;
    },
    saldot() {
      this.costo_total =
        parseFloat(this.cantidad) * parseFloat(this.costo_unit);
    },
    crearrol() {
      var res = 0;
      if(this.usuario.id_rol==1){
        res=1
      }else{
        res = this.$store.state.Roles[21].crear;
      }
      return res;
    },
    editarrol(){
      var res = 0;
      if(this.usuario.id_rol==1){
        res=1
      }else{
        res = this.$store.state.Roles[21].editar;
      }
      return res;
    },
    eliminarrol(){
      var res = 0;
      if(this.usuario.id_rol==1){
        res=1
      }else{
        res = this.$store.state.Roles[21].eliminar;
      }
      return res;
    }
  },
 methods: {
    editar(id) {
      this.$router.push(`/compras/liquidacion/${id}`);
    },
    liquidar(liq,id){
      console.log(liq+"-"+id);
      /*axios.put("",{
        id:id,

      }).then().catch();*/
    },
    eliminar(id) {
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: `Confirmar`,
        text: `¿Desea Elimnar este registro?:`,
        acceptText: "Aceptar",
        cancelText: "Cancelar",
        accept: this.acceptAlert,
        parameters: id
      });
    },
    acceptAlert(parameters) {
      //console.log(parameters);
      axios.delete("/api/eliminarimportacion/" + parameters);
      this.$vs.notify({
        color: "danger",
        title: "Reguistro Eliminado  ",
        text: "El Reguistro selecionado fue eliminado con exito"
      });
      this.listar(1, this.buscar, this.cantidadp);
    },
    listar(page, buscar) {
      var url =
        "/api/liquid" +
        "?page=" +
        page +
        "&buscar=" +
        buscar;
      axios.get(url).then(res => {
        var respuesta = res.data;
        this.contenido = respuesta.recupera;
      });
    },
    
  },
  mounted() {
    this.listar(1, this.buscar, this.criterio, this.cantidadp);
    this.liquidar();
  },
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
  width: 800px !important;
}
</style>
