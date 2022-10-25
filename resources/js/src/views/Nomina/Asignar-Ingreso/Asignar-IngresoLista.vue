<template>
  <div id="ag-grid-demo">
    <vx-card>
      <div class="mb-4 flex flex-wrap justify-between items-center">
        <div class="mr-4 ag-grid-table-actions-left"></div>
        <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
          <vs-input
            class="mb-4 md:mb-0 mr-4"
            v-model="buscar"
            @keyup.enter="listar(1,buscar)"
            v-bind:placeholder="i18nbuscar"
          />
          <!--<vs-button class="mb-4 md:mb-0" @click="gridApi.exportDataAsCsv()">Export as CSV</vs-button>-->
          <div class="dropdown-button-container" v-if="crearrol">
            <vs-button class="btnx" type="filled" @click="crear()">Agregar</vs-button>
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

      <vs-table stripe :data="contenidolistar">
        <template slot="thead">
          <vs-th>Mes</vs-th>
          <vs-th>Departamento</vs-th>
           <vs-th>Acciones</vs-th>
        </template>
        <template slot-scope="{data}">
          <vs-tr :key="datos.id_asignar_ingresos" v-for="datos in data">
            <vs-td v-if="datos.fecha_asignar">{{datos.fecha_asignar |fecha |upper}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.decripcion">{{datos.decripcion}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td class="whitespace-no-wrap">
              <feather-icon
                v-if="editarrol && datos.tiene_detalle==0"
                icon="EditIcon"
                class="cursor-pointer"
                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                @click.stop="edit(datos.cod_asignar_ingresos)"
              />
              <feather-icon
                v-if="editarrol && datos.tiene_detalle==1"
                icon="EditIcon"
                class="cursor-pointer"
                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                @click.stop="edit_nuevo(datos.cod_asignar_ingresos)"
              />
              <feather-icon
                v-if="eliminarrol && datos.tiene_detalle==0"
                icon="TrashIcon"
                class="cursor-pointer"
                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                @click.stop="eliminar(datos.cod_asignar_ingresos)"
              />
              <feather-icon
                v-if="eliminarrol && datos.tiene_detalle==1"
                icon="TrashIcon"
                class="cursor-pointer"
                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                @click.stop="eliminar_nuevo(datos.cod_asignar_ingresos)"
              />
              <!--@click.stop="eliminar(datos.id_empresa)"-->
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
      
    </vx-card>
  </div>
</template>
<script>
import { AgGridVue } from "ag-grid-vue";
const axios = require("axios");
import moment from "moment";
moment.locale("es");
export default {
  components: {
    AgGridVue
  },
  data() {
    return {
      //mapeo de datos
      contenidolistar:[],
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
      i18nbuscar: this.$t("i18nbuscar")
    };
  },
  filters:{
        fecha(data){
            return moment(data).format("MMMM YYYY");
        },
        upper: function (value) {
            return value.toUpperCase();
        }
    },
  computed: {
    usuario() {
      return this.$store.state.AppActiveUser;
    },
    token() {
      return this.$store.state.Token;
    },
    // crearrol() {
    //   var res = 0;
    //   if(this.usuario.id_rol==1){
    //     res=1
    //   }else{
    //     res = this.$store.state.Roles[32].crear;
    //   }
    //   return res;
    // },
    // editarrol(){
    //   var res = 0;
    //   if(this.usuario.id_rol==1){
    //     res=1
    //   }else{
    //     res = this.$store.state.Roles[32].editar;
    //   }
    //   return res;
    // },
    // eliminarrol(){
    //   var res = 0;
    //   if(this.usuario.id_rol==1){
    //     res=1
    //   }else{
    //     res = this.$store.state.Roles[32].eliminar;
    //   }
    //   return res;
    // }
        crearrol() {
            var res = 0;
            // if (this.usuario.id_rol == 1) {
            //     res = 1;
            // } else {
            //     res = this.$store.state.Roles[15].crear;
            // }
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                this.$store.state.Roles.forEach(el => {
                    if(el.nombre == "Asignar-Ingresos"){
                        res = el.crear;
                        return res;
                    }
                });
            }
            console.log(res+"Asignar-Ingresos");
            return res;
            
        },
        editarrol() {
            var res = 0;
            // if (this.usuario.id_rol == 1) {
            //     res = 1;
            // } else {
            //     res = this.$store.state.Roles[15].editar;
            // }
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                this.$store.state.Roles.forEach(el => {
                    if(el.nombre == "Asignar-Ingresos"){
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
                    //     res = this.$store.state.Roles[15].eliminar;
                    // }
                    if (this.usuario.id_rol == 1) {
                        res = 1;
                    } else {
                        this.$store.state.Roles.forEach(el => {
                            if(el.nombre == "Asignar-Ingresos"){
                                res = el.eliminar;
                                return res;
                            }
                        });
                    }
                    return res;
        },
  },
  methods: {
        crear(){
            this.$router.push("/nomina/asignar-ingreso/agregar");
        },
        edit(id){
          this.$router.push(`/nomina/asignar-ingreso/${id}/editar`);
        } , 
        edit_nuevo(id){
          this.$router.push(`/nomina/asignar-ingreso/${id}/editar_nuevo`);
        } , 
        eliminar(cd) {
          this.$vs.dialog({
            type: "confirm",
            color: "danger",
            title: `Confirmar`,
            text: `¿Desea Eliminar este registro?`,
            acceptText: "Aceptar",
            cancelText: "Cancelar",
            accept: this.acceptAlert,
            parameters: cd,
            
          });
        },
        acceptAlert(parameters,otro) {
          axios.delete("/api/asignaringresos/eliminar/" + parameters);
          this.$vs.notify({
            color: "success",
            title: "Registro Eliminado",
            text: "Registro eliminado con exito"
          });
          this.listar(1, this.buscar);
        },
        eliminar_nuevo(cd) {
          this.$vs.dialog({
            type: "confirm",
            color: "danger",
            title: `Confirmar`,
            text: `¿Desea Eliminar este registro?`,
            acceptText: "Aceptar",
            cancelText: "Cancelar",
            accept: this.acceptAlert_nuevo,
            parameters: cd,
            
          });
        },
        acceptAlert_nuevo(parameters,otro) {
          axios.delete("/api/asignaringresos/eliminar_nuevo/" + parameters);
          this.$vs.notify({
            color: "success",
            title: "Registro Eliminado",
            text: "Registro eliminado con exito"
          });
          this.listar(1, this.buscar);
        },
          listar(page, buscar) {
            var url = "/api/asignarinregos/listar/" +
                this.usuario.id_empresa +
                "?page=" +
                page +
                "&buscar=" +
                buscar;
            axios
                .get(url)
                .then(res => {
                    this.contenidolistar = res.data.recupera;
                    //console.log(res)
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
  },
  mounted() {
 this.listar();
  }
};
</script>
<style lang="scss">
@import "@sass/vuexy/extraComponents/agGridStyleOverride.scss";

</style>
