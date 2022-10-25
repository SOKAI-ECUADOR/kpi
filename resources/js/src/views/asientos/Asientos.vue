<template>
  <vx-card title="Asientos Contables">
    <vs-tabs>
      <vs-tab title="BasicInfo" label="Cabecera" icon-pack="feather" icon="icon-user">
        <div class="tab-text">
          <span>
            <vs-collapse>
              <vs-collapse-item>
                <div slot="header">Agregar Cabecera</div>
                <div class="vx-row">
                  <div class="vx-col sm:w-1/3 w-full mb-6">
                    <vs-select
                      placeholder="Seleccione"
                      class="selectExample w-full"
                      label="Comprobante:"
                      vs-multiple
                      autocomplete
                      v-model="comprobante"
                    >
                      <vs-select-item
                        :key="index"
                        :value="item.text"
                        :text="item.text"
                        v-for="(item,index) in comprobante_array"
                      />
                    </vs-select>
                    <div v-show="error">
                      <span
                        class="text-danger"
                        v-for="err in errorcomprobante"
                        :key="err"
                        v-text="err"
                      ></span>
                    </div>
                  </div>
                  <div class="vx-col sm:w-1/3 w-full mb-6">
                    <vs-input
                      class="w-full"
                      label="Número:"
                      v-model="numero"
                      @keypress="solonumeros($event) "
                    />
                    <div v-show="error">
                      <span class="text-danger" v-for="err in errornumero" :key="err" v-text="err"></span>
                    </div>
                  </div>
                  <div class="vx-col sm:w-1/3 w-full mb-6">
                    <label class="vs-input--label">Fecha:</label>

                    <flat-pickr
                      class="w-full"
                      :config="configdateTimePicker"
                      v-model="fecha"
                      placeholder="Elegir Fecha"
                    />
                    <div v-show="error">
                      <span class="text-danger" v-for="err in errorfecha" :key="err" v-text="err"></span>
                    </div>
                  </div>

                  <vs-divider></vs-divider>
                  <div class="vx-col sm:w-1/3 w-full mb-6">
                    <vs-input
                      class="selectExample w-full"
                      label="Razón Social:"
                      vs-multiple
                      autocomplete
                      v-model="razon_social"
                      @keypress="sololetras($event)"
                    />
                    <div v-show="error">
                      <span
                        class="text-danger"
                        v-for="err in errorrazon_social"
                        :key="err"
                        v-text="err"
                      ></span>
                    </div>
                  </div>
                  <div class="vx-col sm:w-1/3 w-full mb-6">
                    <vs-input
                      class="selectExample w-full"
                      label="Ruc:"
                      vs-multiple
                      autocomplete
                      @keypress="solonumeros($event) "
                      v-model="ruc_ci"
                      @keyup="validarruc"
                    />
                    <div v-show="error">
                      <span class="text-danger" v-for="err in errorruc_ci" :key="err" v-text="err"></span>
                    </div>
                  </div>
                  <div class="vx-col sm:w-1/3 w-full mb-6">
                    <vs-input
                      class="selectExample w-full"
                      label="Concepto:"
                      vs-multiple
                      autocomplete
                      v-model="concepto"
                      @keypress="sololetras($event)"
                    />
                    <div v-show="error">
                      <span
                        class="text-danger"
                        v-for="err in errorconcepto"
                        :key="err"
                        v-text="err"
                      ></span>
                    </div>
                  </div>

                  <!--BOTONES
                  -->
                  <div class="vx-col w-full">
                    <vs-button
                      color="success"
                      type="filled"
                      @click="editarcabecera()"
                      v-if="traer.id"
                    >Guardar</vs-button>
                    <vs-button color="success" type="filled" @click="guardar()" v-else>Guardar</vs-button>
                    <vs-button color="warning" type="filled" @click="borrar()">Borrar</vs-button>
                    <vs-button color="danger" type="filled" @click="cancelar()">Cancelar</vs-button>
                  </div>

                  <vs-divider></vs-divider>
                </div>
              </vs-collapse-item>
            </vs-collapse>

            <vs-table stripe :data="contenido">
              <template slot="thead">
                <vs-th>N°</vs-th>
                <vs-th>Comprobrante</vs-th>
                <vs-th>Numero</vs-th>
                <vs-th>Fecha</vs-th>
                <vs-th>Razón Social:</vs-th>
                <vs-th>Ruc/Ci:</vs-th>
                <vs-th>Concepto:</vs-th>
                <vs-th>Acciones:</vs-th>
              </template>
              <template slot-scope="{data}">
                <vs-tr :key="datos.id_asientos" v-for="datos in data">
                  <vs-td v-if="datos.id_asientos">{{datos.id_asientos}}</vs-td>
                  <vs-td v-else>-</vs-td>
                  <vs-td v-if="datos.comprobante">{{datos.comprobante}}</vs-td>
                  <vs-td v-else>-</vs-td>
                  <vs-td v-if="datos.numero">{{datos.numero}}</vs-td>
                  <vs-td v-else>-</vs-td>
                  <vs-td v-if="datos.fecha">{{datos.fecha}}</vs-td>
                  <vs-td v-else>-</vs-td>
                  <vs-td v-if="datos.razon_social">{{datos.razon_social}}</vs-td>
                  <vs-td v-else>-</vs-td>
                  <vs-td v-if="datos.ruc_ci">{{datos.ruc_ci}}</vs-td>
                  <vs-td v-else>-</vs-td>
                  <vs-td v-if="datos.concepto">{{datos.concepto}}</vs-td>
                  <vs-td v-else>-</vs-td>

                  <vs-td class="whitespace-no-wrap">
                    <feather-icon
                      icon="EditIcon"
                      svgClasses="w-5 h-5 hover:text-primary stroke-current"
                      class="pointer"
                      @click.stop="listarasiento(datos.id_asientos)"
                    />
                    <feather-icon
                      icon="TrashIcon"
                      svgClasses="w-5 h-5 hover:text-danger stroke-current"
                      class="ml-2 pointer"
                      @click.stop="eliminar(datos.id_asientos)"
                    />
                    <!--@click.stop="eliminar(datos.id_empresa)"-->
                  </vs-td>
                </vs-tr>
              </template>
            </vs-table>
          </span>
        </div>
      </vs-tab>
      <vs-tab
        title="JobInfo"
        id="JobInfo"
        label="Detalles"
        icon-pack="feather"
        icon="icon-briefcase"
      >
        <div class="tab-text">
          <span>
            <vs-collapse>
              <vs-collapse-item>
                <div slot="header" id="salir">Agregar Detalle</div>
                <div class="vx-row">
                  <div class="vx-col md:w-2/3 w-full mb-6">
                    <div class="vx-row"></div>
                  </div>

                  <div class="vx-col w-1/2 mb-6">
                    <label class="vs-input--label">Proyecto:</label>
                    <vx-input-group class="mb-base">
                      <vs-input class="w-full" v-model="proyecto" />
                      <template slot="append">
                        <div class="append-text btn-addon">
                          <vs-button color="primary" @click="abrirlinea()">Buscar</vs-button>
                        </div>
                      </template>
                    </vx-input-group>
                  </div>

                  <!-- Modales-->
                  <vs-popup :title="titulomodal" :active.sync="modal">
                    <div class="vx-row">
                      <!-- Modal principal Grupo cliente-->
                      <div class="vx-col sm:w-full w-full mb-6" v-if="tipoaccion==1">
                        <vx-card>
                          <div class="vx-row">
                            <div class="vx-col md:w-full w-full mb-6" id="ag-grid-demo">
                              <div class="flex flex-wrap justify-between items-center mb-3">
                                <!-- ITEMS PER PAGE -->
                                <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"></div>
                                <div
                                  class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"
                                >
                                  <vs-input
                                    class="mb-4 md:mb-0 mr-4"
                                    v-model="buscar1"
                                    @keyup.enter="listarlinea(1,buscar1)"
                                    v-bind:placeholder="i18nbuscar"
                                  />
                                  <div>
                                    <vs-button
                                      class="btnx"
                                      type="filled"
                                      divider
                                      @click="agregar('lineas','guardar')"
                                    >Agregar Nuevo</vs-button>
                                  </div>
                                </div>
                              </div>
                              <vs-table stripe :data="contenidolinea">
                                <template slot="thead">
                                  <vs-th>id</vs-th>
                                  <vs-th>Codigo</vs-th>
                                  <vs-th>Descripción</vs-th>
                                  <vs-th>Ubicación</vs-th>
                                  <vs-th>Opciones</vs-th>
                                </template>
                                <template slot-scope="{data}">
                                  <vs-tr :key="datos.id_proyecto" v-for="datos in data">
                                    <vs-td v-if="datos.id_proyecto">{{datos.id_proyecto}}</vs-td>
                                    <vs-td v-else>-</vs-td>
                                    <vs-td v-if="datos.codigo">{{datos.codigo}}</vs-td>
                                    <vs-td v-else>-</vs-td>
                                    <vs-td v-if="datos.descripcion">{{datos.descripcion}}</vs-td>
                                    <vs-td v-else>-</vs-td>
                                    <vs-td v-if="datos.ubicacion">{{datos.ubicacion}}</vs-td>
                                   
                                    <vs-td v-else>-</vs-td>

                                    <vs-td class="whitespace-no-wrap">
                                      <feather-icon
                                        icon="EditIcon"
                                        class="cursor-pointer"
                                        svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                        @click.stop="agregar('lineas','editar',datos)"
                                      />
                                      <feather-icon
                                        icon="TrashIcon"
                                        svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                        class="ml-2 cursor-pointer"
                                        @click.stop="ideliminar=datos.id_proyecto;eliminar=true;tipoaccionmodal=1"
                                      />
                                    </vs-td>
                                  </vs-tr>
                                </template>
                              </vs-table>
                            </div>
                          </div>
                        </vx-card>
                      </div>
                    </div>
                    <!-- Opciones para agregar-->
                    <!-- Modal Agregar grupo cliente-->
                    <vs-popup  :title="titulo" :active.sync="agregarlinea">
                      <div class="vx-col sm:w-full w-full mb-6">
                        <div class="vx-row">
                          
                          <div class="vx-col sm:w-1/3 w-full mb-6">
                            <vs-input
                              class="selectExample w-full"
                              label="Código:"
                              vs-multiple
                              autocomplete
                              v-model="codigo"
                              @keypress="sololetras($event)"
                            />
                            <div v-show="error">
                              <span
                                class="text-danger"
                                v-for="err in errorrcodigo"
                                :key="err"
                                v-text="err"
                              ></span>
                            </div>
                          </div>

                          <div class="vx-col sm:w-1/3 w-full mb-6">
                            <vs-input
                              class="selectExample w-full"
                              label="Descripción:"
                              vs-multiple
                              autocomplete
                              v-model="descripcion"
                              @keypress="sololetras($event)"
                            />
                            <div v-show="error">
                              <span
                                class="text-danger"
                                v-for="err in errordescripcion"
                                :key="err"
                                v-text="err"
                              ></span>
                            </div>
                          </div>
                          <div class="vx-col sm:w-1/3 w-full mb-6">
                            <vs-input
                              class="selectExample w-full"
                              label="Ubicación:"
                              vs-multiple
                              autocomplete
                              v-model="ubicacion"
                              @keypress="sololetras($event)"
                            />
                            <div v-show="error">
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
                              type="border"
                              v-if="tipoaccionmodal==1"
                              @click="guardarlinea()"
                            >Guardar</vs-button>
                            <vs-button
                              color="success"
                              type="border"
                              v-else
                              @click="editargrupo()"
                            >Guardar</vs-button>
                            <vs-button
                              color="danger"
                              type="border"
                              @click="agregarlinea=false"
                            >Cancelar</vs-button>
                          </div>
                        </div>
                      </div>
                    </vs-popup>

                   
                  </vs-popup>

                  <div class="vx-col w-1/2 mb-6">
                    <label class="vs-input--label">Cuenta</label>
                    <vx-input-group class="mb-base">
                      <vs-input class="w-full" v-model="cuenta" @keypress="solonumeros($event) " />
                      <template slot="append">
                        <div class="append-text btn-addon">
                          <vs-button color="primary" @click="popupActive=true">Buscar</vs-button>
                        </div>
                      </template>
                    </vx-input-group>
                  </div>

                  <!-- Popup cuenta contable de cuenta-->
                  <vs-popup title="Seleccione una Cuenta Contable" :active.sync="popupActive">
                    <div class="con-exemple-prompt">
                      <vs-input
                        class="mb-4 md:mb-0 mr-4 w-full"
                        v-model="buscar"
                        @keyup="listarcuenta(1,buscar)"
                        v-bind:placeholder="i18nbuscar"
                      />
                      <vs-table
                        stripe
                        v-model="cuentaarray3"
                        @selected="handleSelected"
                        :data="contenidocuenta"
                      >
                        <template slot="thead">
                          <vs-th>No.Cuenta</vs-th>
                          <vs-th>Tipo Cuenta</vs-th>
                        </template>
                        <template slot-scope="{data}">
                          <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                            <vs-td :data="data[indextr].codcta">{{ data[indextr].codcta }}</vs-td>

                            <vs-td :data="data[indextr].nomcta">{{ data[indextr].nomcta }}</vs-td>
                          </vs-tr>
                        </template>
                      </vs-table>
                    </div>
                  </vs-popup>
                  <!-- Fin popup cuenta contable -->

                  <div class="vx-col sm:w-1/2 w-full mb-6">
                    <vs-input
                      class="selectExample w-full"
                      label="Debe:"
                      vs-multiple
                      autocomplete
                      v-model="debe"
                      @keypress="solonumeros($event)"
                      maxlength="13"
                    />
                  </div>
                  <div class="vx-col sm:w-1/2 w-full mb-6">
                    <vs-input
                      class="selectExample w-full"
                      label="Haber:"
                      vs-multiple
                      autocomplete
                      v-model="haber"
                      @keypress="solonumeros($event)"
                      maxlength="13"
                    />
                  </div>

                  <!--BOTONES
                  -->
                  <div class="vx-col w-full">
                    <vs-button color="success" type="filled" @click="guardar1()">Guardar</vs-button>
                    <vs-button color="warning" type="filled" @click="borrar1()">Borrar</vs-button>
                    <vs-button color="danger" type="filled" @click="cancelar1()">Cancelar</vs-button>
                  </div>
                </div>
              </vs-collapse-item>
            </vs-collapse>

            <vs-table stripe :data="contenidodetalle">
              <template slot="thead">
                <vs-th>N°</vs-th>
                <vs-th>Proyecto</vs-th>
                <vs-th>Código</vs-th>
                <vs-th>Debe</vs-th>
                <vs-th>Haber</vs-th>
                <vs-th>Acciones</vs-th>
              </template>
              <template slot-scope="{data}">
                <vs-tr :key="datos.ida_detalle" v-for="datos in data">
                  <vs-td v-if="datos.codigo">{{datos.codigo}}</vs-td>
                  <vs-td v-else>-</vs-td>
                  <vs-td v-if="datos.proyecto">{{datos.proyecto}}</vs-td>
                  <vs-td v-else>-</vs-td>
                  <vs-td v-if="datos.cuenta">{{datos.cuenta}}</vs-td>
                  <vs-td v-else>-</vs-td>
                  <vs-td v-if="datos.debe">{{datos.debe}}</vs-td>
                  <vs-td v-else>-</vs-td>
                  <vs-td v-if="datos.haber">{{datos.haber}}</vs-td>
                  <vs-td v-else>-</vs-td>
                  <vs-td class="whitespace-no-wrap">
                    <feather-icon
                      icon="EditIcon"
                      class="cursor-pointer"
                      svgClasses="w-5 h-5 hover:text-primary stroke-current"
                      @click.stop="agregar ('lineas','editar',datos)"
                    />
                    <feather-icon
                      icon="TrashIcon"
                      svgClasses="w-5 h-5 hover:text-danger stroke-current"
                      class="ml-2 cursor-pointer"
                      @click.stop="ideliminar=datos.ida_detalle;eliminar=true;tipoaccionmodal=1"
                    />
                  </vs-td>
                </vs-tr>
              </template>
            </vs-table>
          </span>
        </div>
      </vs-tab>
    </vs-tabs>
  </vx-card>
</template>
<script>
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import { AgGridVue } from "ag-grid-vue";

import { FormWizard, TabContent } from "vue-form-wizard";
import "vue-form-wizard/dist/vue-form-wizard.min.css";
import { concat } from "bytebuffer";
import { Script } from "vm";
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
    }
  },
  data() {
    return {
      fecha: null,
      configdateTimePicker: {
        locale: SpanishLocale
      },
      traer: {},
      //cabecera
      comprobante: "",
      numero: "",
      fecha: "",
      razon_social: "",
      ruc_ci: "",
      concepto: "",

      comprobante_array: "",
      comprobante_array: [
        { text: "Seleccione", value: 0 },
        { text: "Ingresos", value: "Ingresos" },
        { text: "Egresos", value: "Egresos" },
        { text: "Diarios", value: "Diarios" }
      ],
      //ERRORES
      error: 0,
      errorcomprobante: [],
      errornumero: [],
      errorfecha: [],
      errorrazon_social: [],
      errorruc_ci: [],
      errorconcepto: [],
      errorrcodigo: [],
      errordescripcion: [],
      errorubicacion: [],
      //detalle
      proyecto: "",
      cuenta: "",
      debe: "",
      haber: "",
      contenido: "",
      contenidodetalle: "",

      //variables para traer una columna plan ctas
      cuentaarray3: [],
      //cuenta contable listar
      contenidocuenta: [],
      popupActive: false,
      //buscador
      buscar: "",
      cuenta_contable: "",
      //lenguaje
      i18nbuscar: this.$t("i18nbuscar"),
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
      pagina: 1,
      pagina1: 1,
      cantidadp1: 1000000,
      // cantidadp11: 10,
      offset: 3,
      //buscador
      buscar: "",
      buscar1: "",
      //buscador
      criterio1: "codcta",
      criterio11: "codcta",
      //otros valores
      gridApi: null,
      contenido1: [],
      //lenguaje
      i18nbuscar: this.$t("i18nbuscar"),
      //variables modal
      titulomodal: "",
      modal: false,
      tipoaccion: 0,
      tipoaccionmodal: 0,
      titulo: "",
      id: null,
      agregarlinea: false,
      eliminar: false,
      ideliminar: 0,
      tipoeliminar: null,
      contenidolinea: [],
      codigo: "",
      descripcion: "",
      ubicacion:""
    };
  },
  components: {
    flatPickr
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
      if (this.usuario == 1) {
        res = 1;
      } else {
        res = this.$store.state.Roles[9].crear;
      }
      return res;
    },
    editarrol() {
      var res = 0;
      if (this.usuario == 1) {
        res = 1;
      } else {
        res = this.$store.state.Roles[9].editar;
      }
      return res;
    },
    eliminarrol() {
      var res = 0;
      if (this.usuario == 1) {
        res = 1;
      } else {
        res = this.$store.state.Roles[9].eliminar;
      }
      return res;
    }
  },
  methods: {
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

    listar(page, buscar, cantidadp) {
      let me = this;
      var url =
        "/api/asientoscontables?page=" +
        page +
        "&buscar=" +
        buscar +
        "&cantidadp=" +
        cantidadp;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.contenido = respuesta.recupera.data;
          me.pagination = respuesta.pagination;
          if (cantidadp > me.pagination.total) {
            cantidadp = me.pagination.total;
          }
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    guardar() {
      if (this.validar()) {
        return;
      }

      axios
        .post("/api/guardarasiento", {
          comprobante: this.comprobante,
          numero: this.numero,
          fecha: this.fecha,
          razon_social: this.razon_social,
          ruc_ci: this.ruc_ci,
          concepto: this.concepto
        })
        .then(res => {
          this.$vs.notify({
            text: "Registro guardado exitosamente",
            color: "primary"
          });
          //this.$router.push("/plan/asientos");
          $(".vs-collapse-item--header").click();
          this.listar(1, this.buscar);
          this.borrar();
        })
        .catch(err => {
          console.log(err);
        });
    },
    editarcabecera() {
      if (this.validar()) {
        return;
      }

      axios
        .put("/api/editarasiento", {
          id: this.traer.id,
          comprobante: this.comprobante,
          numero: this.numero,
          fecha: this.fecha,
          razon_social: this.razon_social,
          ruc_ci: this.ruc_ci,
          concepto: this.concepto
        })
        .then(res => {
          //this.$router.push("/plan/asientos");
          $(".vs-collapse-item--header").click();
          this.listar(1, this.buscar);
          this.borrar();
          this.traer.id = null;
        })
        .catch(err => {
          console.log(err);
        });
    },
    listarasiento(id) {
      axios
        .put("/api/asiento/verasiento/", { id: id })
        .then(res => {
          this.traer.id = id;
          let data = res.data[0];
          this.comprobante = data.comprobante;
          this.numero = data.numero;
          this.fecha = data.fecha;
          this.razon_social = data.razon_social;
          this.ruc_ci = data.ruc_ci;
          this.concepto = data.concepto;
        })
        .then(res => {
          //this.$router.push("/plan/asientos");
          $(".vs-collapse-item--header").click();
          this.listar(1, this.buscar);
        })

        .catch(err => {
          console.log(err);
        });
    },

    eliminar(id) {
      //metodo eliminar
      axios
        //Envia id
        .delete("/api/eliminarasientos/" + id);
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: `Confirmar`,
        text: "¿Desea Elimnar este registro?",
        accept: this.acceptAlert
      });
    },
    acceptAlert() {
      this.$vs.notify({
        color: "danger",
        title: "Asiento Eliminado  ",
        text: "El asiento selecionado fue eliminado con exito"
      });
      this.listar(1, this.buscar, this.cantidadp);
    },
    listarcuenta(page1, buscar1) {
      var url =
        "/api/cuentas/" +
        this.usuario.id_empresa +
        "?page=" +
        page1 +
        "&buscar=" +
        buscar1;
      axios.get(url).then(res => {
        var respuesta = res.data;
        this.contenidocuenta = respuesta.recupera;
      });
    },

    handleSelected(tr) {
      (this.popupActive = false), (this.cuenta = `${tr.codcta}`);
    },

    borrar() {
      this.numero = "";
      this.razon_social = "";
      this.ruc_ci = "";
      this.concepto = "";
      this.fecha = "";
      this.comprobante = "";
    },
    borrarDetalle() {
      this.proyecto = "";
      this.cuenta = "";
      this.debe = "";
      this.haber = "";
    },
    validar() {
      var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      this.error = 0;
      this.errorcomprobante = [];
      this.errornumero = [];
      this.errorfecha = [];
      this.errorrazon_social = [];
      this.errorruc_ci = [];
      this.errorconcepto = [];

      if (!this.comprobante) {
        this.errorcomprobante.push("Campo obligatorio");
        this.error = 1;
      }

      if (!this.numero) {
        this.errornumero.push("Campo obligatorio");
        this.error = 1;
      }
      if (!this.fecha) {
        this.errorfecha.push("Campo obligatorio");
        this.error = 1;
      }

      if (!this.razon_social) {
        this.errorrazon_social.push("Campo obligatorio");
        this.error = 1;
      }

      if (!this.ruc_ci) {
        this.errorruc_ci.push("Campo obligatorio");
        this.error = 1;
      }

      if (!this.concepto) {
        this.errorconcepto.push("Campo obligatorio");
        this.error = 1;
      }

      return this.error;
    },
    abrirlinea() {
      this.tipoaccion = 1;
      this.modal = true;
      this.titulomodal = "Proyectos";
    },
    //guardar editar grupo,tipo,vendedor
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
              
              break;
            }
            case "editar": {
              this.agregarlinea = true;
              this.tipoaccionmodal = 2;
              this.titulo = "Editar Proyecto";
              this.id = dato.id_proyecto;
              this.codigo = "";
              this.descripcion = "";
              this.ubicacion = "";

              break;
            }
          }
          break;
        }
      }
    },
    //guardar grupo cliente
    guardarlinea() {
      axios
        .post("/api/guardargrupo", {
          codigo : this.codigo,
          descripcion : this.descripcion,
          ubicacion : this.ubicacion,
          id_empresa: this.usuario.id_empresa
        })
        .then(res => {
          this.$vs.notify({
            title: "Registro Exitoso",
            text: "Registro agregado con éxito",
            color: "success"
          });
          this.agregarlinea = false;
          this.listarlinea(1, this.buscar1);
          this.todaslinea();
        })
        .catch(err => {});
    },
    //editar grupo cliente
    editargrupo() {
      axios
        .post("/api/editargrupo", {
          id: this.id,
          codigo : this.codigo,
          descripcion : this.descripcion,
          ubicacion : this.ubicacion,
          id_empresa: this.usuario.id_empresa
        })
        .then(res => {
          this.$vs.notify({
            title: "Registro Exitoso",
            text: "Registro agregado con éxito",
            color: "success"
          });
          this.agregarlinea = false;
          this.listarlinea(1, this.buscar1);
        });
    },
     //listar grupo clientes
    listarlinea(page1, buscar1) {
      var url =
        "/api/proyecto/" +
        this.usuario.id_empresa +
        "?page=" +
        page1 +
        "&buscar=" +
        buscar1;
      axios.get(url).then(res => {
        var respuesta = res.data;
        this.contenidolinea = respuesta.recupera;
      });
    },

    //validacionvalida de ruc
    validarruc($event) {
      this.error = 0;
      this.errorruc_ci = [];
      var numero = this.ruc_ci;
      var suma = 0;
      var residuo = 0;
      var pri = false;
      var pub = false;
      var nat = false;
      var numeroProvincias = 22;
      var modulo = 11;

      /* Verifico que el campo no contenga letras */
      var ok = 1;

      /* Aqui almacenamos los digitos de la cedula en variables. */
      var d1 = numero.substr(0, 1);
      var d2 = numero.substr(1, 1);
      var d3 = numero.substr(2, 1);
      var d4 = numero.substr(3, 1);
      var d5 = numero.substr(4, 1);
      var d6 = numero.substr(5, 1);
      var d7 = numero.substr(6, 1);
      var d8 = numero.substr(7, 1);
      var d9 = numero.substr(8, 1);
      var d10 = numero.substr(9, 1);

      /* El tercer digito es: */
      /* 9 para sociedades privadas y extranjeros */
      /* 6 para sociedades publicas */
      /* menor que 6 (0,1,2,3,4,5) para personas naturales */

      if (d3 == 7 || d3 == 8) {
        //console.log('El tercer dígito ingresado es inválido');
        this.errorruc_ci.push("El tercer dígito ingresado es inválido");
        this.error = 1;
        return false;
      }

      /* Solo para personas naturales (modulo 10) */
      if (d3 < 6) {
        nat = true;
        p1 = d1 * 2;
        if (p1 >= 10) p1 -= 9;
        p2 = d2 * 1;
        if (p2 >= 10) p2 -= 9;
        p3 = d3 * 2;
        if (p3 >= 10) p3 -= 9;
        p4 = d4 * 1;
        if (p4 >= 10) p4 -= 9;
        p5 = d5 * 2;
        if (p5 >= 10) p5 -= 9;
        p6 = d6 * 1;
        if (p6 >= 10) p6 -= 9;
        p7 = d7 * 2;
        if (p7 >= 10) p7 -= 9;
        p8 = d8 * 1;
        if (p8 >= 10) p8 -= 9;
        p9 = d9 * 2;
        if (p9 >= 10) p9 -= 9;
        modulo = 10;
      } else if (d3 == 6) {
        /* Solo para sociedades publicas (modulo 11) */
        /* Aqui el digito verficador esta en la posicion 9, en las otras 2 en la pos. 10 */
        pub = true;
        p1 = d1 * 3;
        p2 = d2 * 2;
        p3 = d3 * 7;
        p4 = d4 * 6;
        p5 = d5 * 5;
        p6 = d6 * 4;
        p7 = d7 * 3;
        p8 = d8 * 2;
        p9 = 0;
      } else if (d3 == 9) {
        /* Solo para entidades privadas (modulo 11) */
        var pri = true;
        var p1 = d1 * 4;
        var p2 = d2 * 3;
        var p3 = d3 * 2;
        var p4 = d4 * 7;
        var p5 = d5 * 6;
        var p6 = d6 * 5;
        var p7 = d7 * 4;
        var p8 = d8 * 3;
        var p9 = d9 * 2;
      }

      var suma = p1 + p2 + p3 + p4 + p5 + p6 + p7 + p8 + p9;
      var residuo = suma % modulo;

      /* Si residuo=0, dig.ver.=0, caso contrario 10 - residuo*/
      var digitoVerificador = residuo == 0 ? 0 : modulo - residuo;

      /* ahora comparamos el elemento de la posicion 10 con el dig. ver.*/
      if (pub == true) {
        if (digitoVerificador != d9) {
          //console.log('El ruc de la empresa del sector público es incorrecto.');
          this.errorruc_ci.push("Ruc invalido");
          this.error = 1;
          return false;
        }
        /* El ruc de las empresas del sector publico terminan con 0001*/
        if (numero.substr(9, 4) != "0001") {
          this.errorruc_ci.push("Ruc invalido");
          this.error = 1;
          return false;
        }
      } else if (pri == true) {
        if (digitoVerificador != d10) {
          this.errorruc_ci.push("Ruc invalido");
          this.error = 1;
          return false;
        }
        if (numero.substr(10, 3) != "001") {
          this.errorruc_ci.push("Ruc invalido");
          this.error = 1;
          return false;
        }
      } else if (nat == true) {
        if (digitoVerificador != d10) {
          //console.log('El número de cédula de la persona natural es incorrecto.');
          this.errorruc_ci.push("Ruc invalido");
          this.error = 1;
          return false;
        }
        if (numero.length < 14 && numero.substr(10, 12) != "001") {
          //console.log('El ruc de la persona natural debe terminar con 001');
          this.errorruc_ci.push("Ruc invalido");
          this.error = 1;
          return false;
        }
      }
      return true;
    }
  },
  mounted() {
    this.listar(1, this.buscar, this.cantidadp);
    this.listarcuenta(1, this.buscar1);

    if (this.traer.id) {
      var id = this.traer.id;
      this.listarasiento(id);
    }
  }
};
</script>
<style lang="scss">
@import "@sass/vuexy/extraComponents/agGridStyleOverride.scss";
.vs-popup {
  width: 600px !important;
}

</style>