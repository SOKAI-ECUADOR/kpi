<template>
  <vx-card class="mt-10">
    <div class="flex flex-wrap justify-between items-center mb-3">
      <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"></div>
      <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
        <vs-input
          class="mb-4 md:mb-0 mr-4"
          v-model="buscar"
          @keyup="listarformula(1,buscar)"
          v-bind:placeholder="i18nbuscar"
        />
                            <div  v-if="crearrol">
                        <vs-button
                            class="btnx"
                            type="filled"
                            to="/produccion/formula/agregar"
                            >Agregar</vs-button
                        >
                    </div>
      </div>
    </div>
    <vs-table stripe :data="contenido">
      <template slot="thead">
        <vs-th>Código</vs-th>
        <vs-th>Nombre Fórmula</vs-th>
        <!--<vs-th>Producto</vs-th>-->
        <vs-th>Opciones</vs-th>
      </template>
      <template slot-scope="{data}">
        <vs-tr :key="datos.id_formula_produccion" v-for="datos in data">
          <vs-td
            v-if="datos.codigo_produccion"
            style="width:30%!important;"
          >{{datos.codigo_produccion}}</vs-td>
          <vs-td v-else>-</vs-td>
          <vs-td v-if="datos.nombre_form" style="width:60%!important;">{{datos.nombre_form}}</vs-td>
          <vs-td v-else>-</vs-td>
          <vs-td class="whitespace-no-wrap" style="width:10%!important;">
                                        <vx-tooltip
                                text="Editar Fórmula"
                                position="top"
                                style="display: inline-flex;"
                            >
            <feather-icon
              v-if="editarrol"
              icon="EditIcon"
              svgClasses="w-5 h-5 hover:text-primary stroke-current"
              class="pointer"
              @click.stop="editar(datos.id_formula_produccion)"
            /></vx-tooltip>
                                        <vx-tooltip
                                text="Borrar Fórmula"
                                position="top"
                                style="display: inline-flex;"
                            >
            <feather-icon
              v-if="eliminarrol"
              icon="TrashIcon"
              svgClasses="w-5 h-5 hover:text-danger stroke-current"
              class="ml-2 pointer"
              @click.stop="ideliminar=datos.id_formula_produccion,eliminar=true"
            /></vx-tooltip>
          </vs-td>
        </vs-tr>
      </template>
    </vs-table>
    <!-- Eliminar popup-->
    <vs-popup title="eliminar registro" :class="'peque'" :active.sync="eliminar">
      <vx-card>
        <div class="vx-col sm:w-full w-full mb-6">
          <div class="vx-row">
            <div class="vx-col sm:w-full w-full mb-6 text-center">
              <label class="text-center">
                Esta seguro que desea eliminar este registro
                <br />¡Se eliminará de forma permanente!
              </label>
            </div>
            <div class="vx-col sm:w-full w-full text-center">
              <vs-button color="danger" type="filled" @click="borrar(ideliminar)">Eliminar Fórmula</vs-button>
            </div>
          </div>
        </div>
      </vx-card>
    </vs-popup>
  </vx-card>
</template>

<script>
import { AgGridVue } from "ag-grid-vue";
const axios = require("axios");
export default {
  components: {
    AgGridVue
  },
  data() {
    return {
      //buscador
      buscar: "",
      i18nbuscar: this.$t("i18nbuscar"),

      eliminar: false,
      //listar
      contenido: []
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
      if (this.usuario.id_rol == 1) {
        res = 1;
      } else {
        res = this.$store.state.Roles[23].crear;
      }
      return res;
    },
    editarrol() {
      var res = 0;
      if (this.usuario.id_rol == 1) {
        res = 1;
      } else {
        res = this.$store.state.Roles[23].editar;
      }
      return res;
    },
    eliminarrol() {
      var res = 0;
      if (this.usuario.id_rol == 1) {
        res = 1;
      } else {
        res = this.$store.state.Roles[23].eliminar;
      }
      return res;
    }
  },
  methods: {
    listarformula(page, buscar) {
      var url =
        "/api/formula/" +
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
    editar(id) {
      this.$router.push(`/produccion/formula/${id}/editar`);
    },
    borrar(id) {
      axios.delete("/api/eliminarformula/" + id);
      this.$vs.notify({
        title: "Registro eliminado",
        text: "Este registro ha sido eliminado exitosamente",
        color: "success"
      });
      this.eliminar = false;
      this.listarformula(1, this.buscar);
    }
  },

  mounted() {
    this.listarformula(1, this.buscar);
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
  width: 1060px !important;
}
.peque .vs-popup {
  width: 600px !important;
}
.full .vs-popup {
  width: 1060px !important;
}
.peque .vs-popup {
  width: 600px !important;
}
/*@media screen and (min-width:1200px){
  .vs-popup{
    margin-left: 20%!important;
  }
