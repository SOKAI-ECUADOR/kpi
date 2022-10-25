<template>
  <div id="ag-grid-demo">
    <vx-card>
      <div class="flex flex-wrap justify-between items-center">
        <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"></div>
        <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
          <vs-input
            class="mb-4 md:mb-0 mr-4"
            v-model="buscar"
            @keyup="listar(1,buscar)"
            v-bind:placeholder="i18nbuscar"
          />
          <div class="dropdown-button-container mr-3">
            <vs-dropdown>
              <vs-button class="btn-drop" type="filled" icon="settings" style="border-radius: 5px;"></vs-button>
              <vs-dropdown-menu style="width:13em;">
                <vs-dropdown-item class="text-center" divider @click="verCaja()">Caja Chica</vs-dropdown-item>
              </vs-dropdown-menu>
            </vs-dropdown>
          </div>
          <div class="dropdown-button-container" v-if="crearrol">
            <vs-button class="btnx" type="filled" @click="abrirModal('agregar')">Agregar</vs-button>
            <vs-dropdown>
              <vs-button class="btn-drop" type="filled" icon="expand_more"></vs-button>
              <vs-dropdown-menu style="width:13em;">
                <vs-dropdown-item
                  class="text-center"
                  divider
                  @click="importar=true"
                >Importar registros</vs-dropdown-item>
                <vs-dropdown-item class="text-center" @click="exportar=true">Exportar registros</vs-dropdown-item>
                <vs-dropdown-item
                  class="text-center"
                  divider
                  @click="reporte_balance()"
                >Balance Comprobacion PDF</vs-dropdown-item>
                <vs-dropdown-item class="text-center">Generar XML</vs-dropdown-item>
              </vs-dropdown-menu>
            </vs-dropdown>
          </div>
        </div>
      </div>
      <br />
      <vs-table stripe :data="contenido">
        <template slot="thead">
          <vs-th>No.Cuenta</vs-th>
          <vs-th>Tipo Cuenta</vs-th>
          <vs-th>Moneda</vs-th>
          <vs-th>Grupo</vs-th>

          <vs-th>Acciones</vs-th>
        </template>
        <template slot-scope="{data}">
          <vs-tr v-for="datos in data" :key="datos.id_plan_cuentas">
            <vs-td>{{datos.codcta}}</vs-td>
            <vs-td v-if="datos.nomcta">{{datos.nomcta }}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.nomb_moneda">{{datos.nomb_moneda}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.nomb_grupo">{{datos.nomb_grupo}}</vs-td>
            <vs-td v-else>-</vs-td>

            <vs-td class="whitespace-no-wrap">
              <feather-icon
                v-if="editarrol"
                icon="EditIcon"
                class="cursor-pointer"
                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                @click="abrirModal('editar', datos)"
              />
              <feather-icon
                v-if="eliminarrol"
                icon="TrashIcon"
                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                class="ml-2 cursor-pointer"
                @click="eliminar(datos.id_plan_cuentas)"
              />
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
      
      <!--Modal para Agregar plan de ctas-->
      <vs-popup :title="titulomodal" :active.sync="activarmodal">
        <div class="vx-row">
          
                      <div class="vx-col sm:w-1/4 w-full mb-2">
                      <label class="vs-input--label">Cuenta Bancaria</label>
                      <br>
                        <vs-checkbox v-model="exist_cuenta_banco" @click="existe_cuenta()"></vs-checkbox>
                        <div v-show="errorplc">
                          <div v-for="err in errorexist_cuenta_banco" :key="err" v-text="err" class="text-danger"></div>
                        </div>
                      </div>

                    

          <div class="vx-col sm:w-1/4 w-full" v-if="exist_cuenta_banco==true">
            <vs-select
              placeholder="Seleccionar banco"
              class="selectExample w-full"
              label="Cuenta Banco"
              vs-multiple
              autocomplete
              v-model="bansel"
            >
              <vs-select-item value="1" text="Cuenta Corriente" />
              <vs-select-item value="2" text="Cuenta Ahorros" />
              
            </vs-select>
            <div v-show="errorplc" v-if="!bansel">
                <div v-for="err in errorbansel" :key="err" v-text="err" class="text-danger"></div>
              </div>
          </div>
          <div class="vx-col sm:w-1/4 w-full" v-if="bansel">
            <vs-input
              class="inputx mb-3 w-full"
              name="cod"
              label="Nro Cuenta"
              v-model="num_cuenta"
              maxlength="20"
              @keypress="solonumeros($event)"
            />
            <div v-show="errorplc" v-if="!num_cuenta">
              <div v-for="err in errornum_cuenta" :key="err" v-text="err" class="text-danger"></div>
            </div>
          </div>
          <div class="vx-col sm:w-1/4 w-full" v-if="bansel">
            <vs-select
              placeholder="Seleccionar banco"
              class="selectExample w-full"
              label="Banco"
              vs-multiple
              autocomplete
              v-model="id_banco"
            >
              <vs-select-item
                v-for="data in bancos"
                :key="data.id_moneda"
                :value="data.id_banco"
                :text="data.nombre_banco"
              />
            </vs-select>
            <div v-show="errorplc" v-if="!id_banco">
              <div v-for="err in errorid_banco" :key="err" v-text="err" class="text-danger"></div>
            </div>
          </div>
          <div class="vx-col sm:w-1/2 w-full" >
            <vs-input
              class="inputx mb-3 w-full"
              name="cod"
              label="Codigo"
              v-model="codcta"
              maxlength="20"
              @keypress="solodecimales($event)"
            />
            <div v-show="errorplc" v-if="!codcta">
              <div v-for="err in errorcodigo" :key="err" v-text="err" class="text-danger"></div>
            </div>
          </div>
          <div class="vx-col sm:w-1/2 w-full">
            <vs-input
              class="inputx mb-3 w-full"
              name="cod"
              label="Nombre de Cuenta"
              v-model="nomcta"
              maxlength="199"
            />
            <div v-show="errorplc" v-if="!nomcta">
              <div v-for="err in errornomcta" :key="err" v-text="err" class="text-danger"></div>
            </div>
          </div>
          <div class="vx-col sm:w-1/2 w-full">
            <vs-select
              placeholder="Selecciona moneda"
              class="selectExample w-full"
              label="Moneda"
              vs-multiple
              autocomplete
              v-model="id_moneda"
            >
              <vs-select-item
                v-for="data in monedas"
                :key="data.id_moneda"
                :value="data.id_moneda"
                :text="data.nomb_moneda"
              />
            </vs-select>
            <div v-show="errorplc" v-if="!id_moneda">
              <div v-for="err in errormonedaplc" :key="err" v-text="err" class="text-danger"></div>
            </div>
          </div>
          <div class="vx-col sm:w-1/2 w-full" v-if="bansel">
              <vs-select
                placeholder="Seleccionar grupo"
                class="selectExample w-full"
                label="Grupo"
                vs-multiple
                autocomplete
                v-model="id_grupo"
              >
                <vs-select-item
                  v-for="data in grupos"
                  :key="data.id_grupo"
                  :value="data.id_grupo"
                  :text="data.nomb_grupo"
                />
              </vs-select>
              <div v-show="errorplc" v-if="!id_grupo">
                <div v-for="err in errorgrupo" :key="err" v-text="err" class="text-danger"></div>
              </div>
          </div>
          <div class="vx-col sm:w-full w-full" v-else>
              <vs-select
                placeholder="Seleccionar grupo"
                class="selectExample w-full"
                label="Grupo"
                vs-multiple
                autocomplete
                v-model="id_grupo"
              >
                <vs-select-item
                  v-for="data in grupos"
                  :key="data.id_grupo"
                  :value="data.id_grupo"
                  :text="data.nomb_grupo"
                />
              </vs-select>
              <div v-show="errorplc" v-if="!id_grupo">
                <div v-for="err in errorgrupo" :key="err" v-text="err" class="text-danger"></div>
              </div>
          </div>
        </div>
        <div class="vx-col w-full mt-6">
          
          <vs-button color="success" type="filled" @click="guardar()" v-if="!id">GUARDAR</vs-button>
          <vs-button color="success" type="filled" @click="editar()" v-else>GUARDAR</vs-button>
          <vs-button color="danger" type="filled" @click="activarmodal=false">CANCELAR</vs-button>
        </div>
      </vs-popup>
      <!--Modal que muestra todos los registros de caja-->
      <vs-popup :title="titulocaja" :active.sync="modalcaja">
        <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
          <vs-input
            class="mb-4 md:mb-0 mr-4 w-3/5"
            v-model="buscarcaja"
            @keyup="listarcaja(1,buscarcaja)"
            v-bind:placeholder="i18nbuscar"
          />
          <vs-button class="btnx" type="filled" @click="abrirModalcaja()">Agregar</vs-button>
        </div>
        <vs-table stripe :data="contenidocaja">
          <template slot="thead">
            <!--<vs-th>No.Cuenta</vs-th>-->
            <vs-th>Tipo Cuenta</vs-th>
            <vs-th>Grupo</vs-th>
            <vs-th>Acciones</vs-th>
          </template>
          <template slot-scope="{data}">
            <vs-tr v-for="datos in data" :key="datos.id_caja">
              <!--<vs-td>{{datos.cuenta_contable}}</vs-td>-->
              <vs-td v-if="datos.descrip_caja">{{datos.descrip_caja }}</vs-td>
              <vs-td v-else>-</vs-td>
              <vs-td v-if="datos.moneda">{{datos.moneda}}</vs-td>
              <vs-td v-else>-</vs-td>
              <vs-td class="whitespace-no-wrap">
                <feather-icon
                  icon="EditIcon"
                  class="cursor-pointer"
                  svgClasses="w-5 h-5 hover:text-primary stroke-current"
                  @click.stop="leercaja(datos.id_caja)"
                />
                <feather-icon
                  icon="TrashIcon"
                  svgClasses="w-5 h-5 hover:text-danger stroke-current"
                  class="ml-2 cursor-pointer"
                  @click.stop="eliminarcaja(datos.id_caja)"
                />
              </vs-td>
            </vs-tr>
          </template>
        </vs-table>
        <!--Modal para eliminar registros de caja-->
        <vs-popup :title="titulomodal" :active.sync="modaleliminarcaja">
          <input v-model="id_caja" hidden />
          <p>Desea eliminar este registro</p>
          <div class="vx-row">
            <div class="vx-col w-full">
              <vs-button color="warning" type="filled" @click="acceptAlertCaja(id_caja)">BORRAR</vs-button>
            </div>
          </div>
        </vs-popup>
        <!--Modal para agregar registros de caja-->
        <vs-popup :title="titulocaja" :active.sync="activecaja" class="peque">
          <div class="vx-row">
            <div class="vx-col sm:w-1/3 w-full">
              <vs-input
                class="inputx mb-3 w-full"
                name="cod"
                label="Descripcion"
                v-model="descrip_caja"
                maxlength="199"
              />
              <div v-show="errorcaja" v-if="!descrip_caja">
                <div v-for="err in errordescripcion" :key="err" v-text="err" class="text-danger"></div>
              </div>
            </div>
            <div class="vx-col sm:w-1/3 w-full mb-6">
              <label class="vs-input--label">Cuenta Contable</label>
              <vx-input-group class>
                <vs-input
                  class="w-full"
                  v-model="cuenta_contable"
                  :value="idContable"
                  maxlength="20"
                  disabled
                />
                <template slot="append">
                  <div class="append-text btn-addon">
                    <vs-button color="primary" @click="activePrompt3 = true">Buscar</vs-button>
                  </div>
                </template>
              </vx-input-group>
              
              <!--<div class="row mb-2">
                            <div
                                class="col-xl-6 col-lg-12 justify-content-end clicker"
                                style="position: relative;"
                            >
                                <div
                                    class="input-group mb-3"
                                    style="width: 100%;"
                                >
                                    <vs-input
                                        label="Cuenta Contable"
                                        type="text"
                                        class="w-full"
                                        placeholder="Buscar Cuenta Contable"
                                        v-model="cuenta_contable"
                                        @keyup="
                                            listarcuenta(cuenta_contable),
                                                abrirlista()
                                        "
                                    />
                                </div>
                                <div
                                    class="menuescoger"
                                    style="display:none"
                                    id="menuescoger"
                                    v-if="cuenta_contable"
                                >
                                    <template v-if="contenidocuenta.length">
                                        <ul
                                            v-for="(tr,
                                            index) in contenidocuenta"
                                            :key="index"
                                            @click="handleSelected(tr)"
                                        >
                                            <li>
                                                {{ tr.nomcta }}
                                                <span class="posicion">
                                                    <template v-if="tr.codcta"
                                                        ><span
                                                            >Codigo:
                                                            {{ tr.codcta }}
                                                        </span>
                                                    </template>
                                                </span>
                                            </li>
                                        </ul>
                                    </template>
                                    <template v-else>
                                        <ul
                                            style="padding: 7px;text-align: center;"
                                        >
                                            <li>
                                                Cuenta Contable no existe
                                            </li>
                                        </ul>
                                    </template>
                                </div>
                            </div>
                        </div>-->
              <div v-show="errorcaja" v-if="!cuenta_contable">
                <div v-for="err in errorctacontable" :key="err" v-text="err" class="text-danger"></div>
              </div>
            </div>
            <div class="vx-col sm:w-1/4 w-full mb-6">
              <vs-select
                placeholder="Selecciona moneda"
                class="selectExample w-full"
                label="Moneda"
                vs-multiple
                autocomplete
                v-model="id_moneda2"
              >
                <vs-select-item
                  v-for="data in monedas2"
                  :key="data.id_moneda"
                  :value="data.id_moneda"
                  :text="data.nomb_moneda"
                />
              </vs-select>
              <div v-show="errorcaja" v-if="!id_moneda2">
                <div v-for="err in errormonedacaja" :key="err" v-text="err" class="text-danger"></div>
              </div>
            </div>
          </div>
          <div class="vx-col w-full mt-6">
            <vs-button
              color="success"
              type="filled"
              @click="guardarcaja()"
              v-if="!idrecupera"
            >GUARDAR</vs-button>
            <vs-button color="success" type="filled" @click="editarcaja()" v-else>GUARDAR</vs-button>
            <vs-button color="danger" type="filled" @click="activecaja=false">CANCELAR</vs-button>
          </div>
          <!--Modal lista todas los planes de cuentas-->
          <vs-popup title="Plan Cuentas" class="peque" :active.sync="activePrompt3">
            <div class="con-exemple-prompt">
              <vs-input
                class="mb-4 md:mb-0 mr-4 w-full"
                v-model="buscar"
                @keyup="listar(1,buscar)"
                v-bind:placeholder="i18nbuscar"
              />
              <vs-table stripe v-model="cuentaarray3" @selected="handleSelected3" :data="contenido">
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
        </vs-popup>
      </vs-popup>
    </vx-card>

    <!--Modal para exportar excel-->
    <vs-popup :class="'peque1'" title="Exportar Excel" :active.sync="exportar">
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
                <span class="mr-4">Celda con ancho predefinido:</span>
                <vs-switch v-model="cellancho">Ancho de los campos del archivo</vs-switch>
              </div>
            </div>
            <div class="vx-col sm:w-full w-full mt-5">
              <vs-button color="success" type="filled" @click="exportardatos">Descargar Excel</vs-button>
              <vs-button color="danger" type="filled" @click="exportar=false">Cancelar</vs-button>
            </div>
          </div>
        </div>
      </vx-card>
    </vs-popup>
    <!--fin modal de exportar-->

    <!--Modal para importar excel-->
    <vs-popup :class="'peque2'" title="Importar Excel" :active.sync="importar">
      <vx-card>
        <div class="vx-col sm:w-full w-full mb-6">
          <div class="vx-row">
            <!---->
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
              <vs-button color="success" @click="importardatos()">Subir Archivo</vs-button>
              <vs-button color="danger" type="filled" @click="cancelar1()">Cancelar</vs-button>
            </div>
          </div>
        </div>
      </vx-card>
    </vs-popup>
    <!--fin modal de importar-->
    <!--modal para exportar pdf balance de comprobacion-->
    <vs-popup classContent="popup-example" title="Generar Reportes" :active.sync="generarreporte">
      <div class="vx-row">
        <div class="vx-col sm:w-full mb-6">
          <label class="vs-input--label">Defina el tipo de búsqueda</label>
          <vs-select
            placeholder="Escoga el tipo de búsqueda"
            @change="recargar_reporte()"
            class="selectExample w-full"
            vs-multiple
            v-model="tipo_busqueda"
          >
            <vs-select-item value="1" text="Fechas" />
            <vs-select-item value="2" text="Proveedor" />
            <vs-select-item value="3" text="RUC Proveedor" />
          </vs-select>
        </div>
        <div class="vx-col sm:w-full mb-6" v-if="tipo_busqueda==1">
          <label class="vs-input--label">Escoga el rango de fechas</label>
          <div class="vx-row">
            <div class="vx-col sm:w-1/2 mb-3">
              <label class="vs-input--label mt-3">Fecha de inicio</label>
              <flat-pickr
                :config="configdateTimePicker"
                class="w-full mt-1"
                v-model="dateinicio"
                placeholder="Seleccionar"
              ></flat-pickr>
            </div>
            <div class="vx-col sm:w-1/2 mb-3">
              <label class="vs-input--label mt-3">Fecha final</label>
              <flat-pickr
                :config="configdateTimePicker"
                class="w-full mt-1"
                v-model="datefin"
                placeholder="Seleccionar"
              ></flat-pickr>
            </div>
          </div>
        </div>
        <div class="vx-col w-full mt-6">
          <vs-button color="success" type="filled" @click="reporte_balance()">GENERAR</vs-button>
          <vs-button color="danger" type="filled" @click="generarreporte=false">CANCELAR</vs-button>
        </div>
      </div>
    </vs-popup>
  </div>
</template>
<script>
import ImportExcel from "@/components/excel/ImportExcel.vue";
import $ from "jquery";
import vSelect from "vue-select";
import { AgGridVue } from "ag-grid-vue";
const axios = require("axios");
export default {
  components: {
    AgGridVue,
    ImportExcel,
    vSelect
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
        res = this.$store.state.Roles[9].crear;
      }
      return res;
    },
    editarrol() {
      var res = 0;
      if (this.usuario.id_rol == 1) {
        res = 1;
      } else {
        res = this.$store.state.Roles[9].editar;
      }
      return res;
    },
    eliminarrol() {
      var res = 0;
      if (this.usuario.id_rol == 1) {
        res = 1;
      } else {
        res = this.$store.state.Roles[9].eliminar;
      }
      return res;
    }
    
  },
  data() {
    return {
      buscar: "",
      activarmodal: false,
      contenido: [],
      i18nbuscar: this.$t("i18nbuscar"),
      titulomodal: "",
      id: null,
      id_empresa: null,
      codcta: "",
      nomcta: "",
      id_moneda: null,
      refcon: "",
      bansel: "",
      id_grupo: null,
      empresas: [],
      monedas: [],
      bancos:[],
      grupos: [],
      num_cuenta:"",
      id_banco:"",
      //modal caja chica
      titulocaja: "Caja Chica",
      modalcaja: false,
      buscarcaja: "",
      contenidocaja: [],
      i18nbuscarcaja: this.$t("i18nbuscar"),
      exist_cuenta_banco:false,
      //form caja chica
      idrecupera: null,
      activecaja: false,
      descrip_caja: "",
      cuenta_contable: "",
      contenidocuenta:[],
      id_moneda2: "",
      ctacontable: "",
      idContable: "",
      activePrompt3: false,
      cuentaarray3: [],
      monedas2: [],
      id_caja: "",
      modaleliminarcaja: false,
      //errores
      errorplc: 0,
      errorbansel:[],
      errorcodigo: [],
      errornomcta: [],
      errormonedaplc: [],
      errorexist_cuenta_banco:[],
      errorgrupo: [],
      errorcaja: 0,
      errordescripcion: [],
      errorctacontable: [],
      errormonedacaja: [],
      errornum_cuenta:[],
      errorid_banco:[],
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
        "id_plan_cuentas",
        "id_empresa",
        "codcta",
        "num_cuenta",
        "id_banco",
        "nomcta",
        "id_moneda",
        "refcon",
        "bansel",
        "fcrea",
        "fmodifica",
        "id_grupo"
      ],
      //campos elegidos a exportar
      indexs: [
        
        "bansel",
        "num_cuenta",
        "id_banco",
        "codcta",
        "nomcta",
        "id_moneda",
        "id_grupo",
        "refcon",
        "id_empresa"
      ],
      //cabezera que imprimira en el excel
      cabezera: [
        
        "cuenta_banco",
        "numero_cuenta",
        "id_banco",
        "codigo_cuenta",
        "nombre_cuenta",
        "id_moneda",
        "id_grupo",
        "refcon",
        "id_empresa"
      ],
      generarreporte: false,
      tipo_busqueda:""
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
        res = this.$store.state.Roles[8].crear;
      }
      return res;
    },
    editarrol() {
      var res = 0;
      if (this.usuario.id_rol == 1) {
        res = 1;
      } else {
        res = this.$store.state.Roles[8].editar;
      }
      return res;
    },
    eliminarrol() {
      var res = 0;
      if (this.usuario.id_rol == 1) {
        res = 1;
      } else {
        res = this.$store.state.Roles[8].eliminar;
      }
      return res;
    }
  },
  methods: {
    //exportar archivos
    exportardatos() {
      import("../../../vendor/Export2PlanCuentaExcel").then(excel => {
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

    //importar archivos
    
    //preload
        readyFile ($e){
            // Este muestra una lista FileList
            console.log($e.target.files)
            // Este muestra el valor actual del input
            console.log($e.target.value)
        },

    importarexcel() {
      //console.log(this.file)
      $(".inputexcel").click();
    },

    importardatos() {
      let formData = new FormData();

      formData.append("id_empresa", this.usuario.id_empresa);
      formData.append("file", this.file[0]);
      axios
        .post("/api/importarplancuentaexcel", formData, {})
        .then(res => {
          document.getElementById("input-upload").value = ""
          this.$vs.notify({
            text: "Archivo Importado con exito",
            color: "success"
          });
          this.importar = false;
          this.listar(1, this.buscar);
          this.file = [];
        })
        .catch(err => {
           this.$vs.notify({
            text: "Archivo Importado 'Sin Exito'",
            color: "danger"
          });
          this.importar = false;
          this.listar(1, this.buscar);
          this.file = []
        });
    },
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
    cancelar1() {
            this.importar = false;
            this.file = [];
            document.getElementById("input-upload").value = ""
        },
    //fin importar
    verCaja() {
      this.modalcaja = true;
    },
    existe_cuenta(){
      if(this.exist_cuenta_banco==true){
        this.bansel="";
        this.num_cuenta="";
        this.id_banco="";
        console.log("No existe ");
      }
    },
    handleSelected3(tr) {
      (this.cuenta_contable = `${tr.nomcta}`),(this.idContable = `${tr.id_plan_cuentas}`), (this.activePrompt3 = false);
    },
    
    listar(page, buscar) {
      let me = this;
      var url =
        "/api/cuentas/" +
        this.usuario.id_empresa +
        "?page=" +
        page +
        "&buscar=" +
        buscar;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.contenido = respuesta;
        })
        .catch(function(error) {
          //console.log(error);
        });
    },
    leercaja(idcaja) {
      if (idcaja) {
        this.idrecupera = idcaja;
        this.activecaja = true;
        this.titulocaja = "Editar Caja";
        var url = "/api/abrircaja/" + idcaja;
        axios
          .put(url)
          .then(res => {
            let data = res.data[0];
            this.descrip_caja = data.descrip_caja;
            this.cuenta_contable = data.cuenta_contable;
            this.idContable=data.id_plan_cuentas;
            this.id_moneda2 = data.id_moneda;
          })
          .catch(err => {
            //console.log(err);
          });
      } else {
        this.idrecupera = null;
      }
    },
    eliminarcaja(idcaja) {
      (this.modaleliminarcaja = true), (this.id_caja = idcaja);
    },
    acceptAlertCaja(parameters) {
      this.modaleliminarcaja = false;
      axios.delete("/api/eliminarcaja/" + parameters);
      this.$vs.notify({
        color: "success",
        title: "Caja Eliminada  ",
        text: "La Caja selecionada fue eliminada con exito"
      });
      this.listarcaja(1, this.buscarcaja);
    },
    listarcaja(pagecaja, buscarcaja) {
      let me = this;
      var url =
        "/api/caja/" +
        this.usuario.id_empresa +
        "?page=" +
        pagecaja +
        "&buscar=" +
        buscarcaja;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.contenidocaja = respuesta.recupera;
        })
        .catch(function(error) {
          //console.log(error);
        });
    },
    abrirModalcaja() {
      (this.activecaja = true), (this.titulocaja = "Agregar Caja");
      (this.idrecupera = null),
        (this.descrip_caja = ""),
        (this.cuenta_contable = ""),
        (this.errorcaja = 0),
        (this.id_moneda2 = "");
    },
    abrirModal(tipo, dato) {
      switch (tipo) {
        case "agregar": {
          this.titulomodal = "Agregar plan de cuentas";
          this.activarmodal = true;
          this.id = null;
          this.id_empresa = null;
          this.codcta = "";
          this.nomcta = "";
          this.id_moneda = null;
          this.refcon = "";
          this.bansel = "";
          this.id_grupo = null;
          this.num_cuenta="";
          this.id_banco="";
          this.errorplc = 0;
          break;
        }
        case "editar": {
          this.titulomodal = "Editar plan de cuentas";
          this.activarmodal = true;
          this.id = dato.id_plan_cuentas;
          this.id_empresa = dato.id_empresa;
          this.codcta = dato.codcta;
          this.nomcta = dato.nomcta;
          this.id_moneda = dato.id_moneda;
          this.refcon = dato.refcon;
          if(dato.bansel!==null){
            this.exist_cuenta_banco=true;
          }else{
            this.exist_cuenta_banco=false;
          }
          this.bansel = dato.bansel;
          this.id_grupo = dato.id_grupo;
          this.num_cuenta=dato.num_cuenta;
          this.id_banco=dato.id_banco;
          break;
        }
      }
    },
    listarempresas() {
      axios.get("/api/traerempresa").then(res => {
        this.empresas = res.data;
      });
    },
    listarmonedas() {
      axios.get("/api/traermoneda").then(res => {
        this.monedas = res.data;
      });
    },
    listarbancos() {
      axios.get("/api/traerbancoprov").then(res => {
        this.bancos = res.data;
      });
    },
    listarmonedas2() {
      axios.get("/api/traermoneda").then(res => {
        this.monedas2 = res.data;
      });
    },
    listargrupos() {
      axios.get("/api/traergrupos").then(res => {
        this.grupos = res.data;
      });
    },
    guardarcaja() {
      if (this.validarcaja()) {
        return;
      }
      axios
        .post("/api/agregarcaja", {
          descrip_caja: this.descrip_caja,
          cuenta_contable: this.cuenta_contable,
          cod_cuenta:this.idContable,
          id_moneda: this.id_moneda2,
          id_empresa: this.usuario.id_empresa
        })
        .then(res => {
          if (res.data != "existe") {
            this.$vs.notify({
              title: "Registro Guardado",
              text: "Registro Guardado exitosamente",
              color: "success"
            });
            this.activecaja = false;
            this.listarcaja(1, this.buscarcaja);
          }else{
            this.$vs.notify({
              title: "Caja Existente",
              text: "Esta Caja ya existe",
              color: "danger"
            });
          }
          

        })
        .catch(err => {});
    },
    editarcaja() {
      if (this.validarcaja()) {
        return;
      }
      axios
        .put("/api/actualizarcaja", {
          id: this.idrecupera,
          descrip_caja: this.descrip_caja,
          cuenta_contable: this.cuenta_contable,
          cod_cuenta:this.idContable,
          id_moneda: this.id_moneda2,
          id_empresa:this.usuario.id_empresa
        })
        .then(res => {
          if (res.data != "existe") {
            this.$vs.notify({
              title: "Registro Editado",
              text: "Registro Editado exitosamente",
              color: "success"
            });
            this.activecaja = false;
            this.listarcaja(1, this.buscarcaja);
          }else{
            this.$vs.notify({
              title: "Caja Existente",
              text: "Esta Caja ya existe",
              color: "danger"
            });
          }
         // }
        })
        .catch(err => {});
    },
    guardar() {
      if (this.validarplancta()) {
        return;
      }
      axios
        .post("/api/agregarcuentas", {
          codcta: this.codcta,
          nomcta: this.nomcta,
          id_moneda: this.id_moneda,
          bansel: this.bansel,
          id_grupo: this.id_grupo,
          id_empresa: this.usuario.id_empresa,
          ucrea:this.usuario.id,
          num_cuenta:this.num_cuenta,
          id_banco:this.id_banco
        })
        .then(res => {
          if(this.id_grupo==1){
            if (res.data != "existe" && res.data != "NO vale") {
              this.activarmodal = false;
              this.$vs.notify({
                title: "Registro Guardado",
                text: "Registro Guardado exitosamente",
                color: "success"  
              });
              this.listar(1, this.buscar);
            }else{
              if(res.data == "existe"){
                this.$vs.notify({
                title: "Error al Guardar",
                text: "El codigo ya existe.",
                color: "danger"
              });
              }
              if(res.data == "NO vale" ){
              this.$vs.notify({
                title: "Error al Guardar",
                text: "El codigo debe terminar con '.'",
                color: "danger"
              });
            }
            }
          }else{
            if (res.data != "existe" && res.data != "mov mal") {
              this.activarmodal = false;
              this.$vs.notify({
                title: "Registro Guardado",
                text: "Registro Guardado exitosamente",
                color: "success"  
              });
              this.listar(1, this.buscar);
            }else{
              if(res.data == "existe"){
                this.$vs.notify({
                title: "Error al Guardar",
                text: "El codigo ya existe.",
                color: "danger"
              });
              }
              if(res.data == "mov mal"){
                this.$vs.notify({
                  title: "Error al Guardar",
                  text: "El codigo debe terminar con numero",
                  color: "danger"
                });
              }
          }
          }
          
          
        });
    },
    listarcuenta(buscar1) {
      axios.get("/api/selcuentascaja/" +this.usuario.id_empresa +"?buscar=" +buscar1)
        .then(res => {
          this.contenidocuenta = res.data.recupera;
        });
    },
    abrirlista() {
      $(".menuescoger").show();
    },
    handleSelected(tr){
      this.idContable = `${tr.id_plan_cuentas}`;
      this.cuenta_contable = `${tr.codcta}`;
    },
    editar() {
      if (this.validarplancta()) {
        return;
      }
      axios
        .put("/api/actualizarcta", {
          id: this.id,
          codcta: this.codcta,
          nomcta: this.nomcta,
          id_moneda: this.id_moneda,
          bansel: this.bansel,
          id_grupo: this.id_grupo,
          fecult: this.fecult,
          id_empresa: this.usuario.id_empresa,
          umodifica:this.usuario.id,
          num_cuenta:this.num_cuenta,
          id_banco:this.id_banco
        })
        .then(res => {
          if(this.id_grupo==1){
            if (res.data != "existe" && res.data != "NO vale") {
              this.activarmodal = false;
              this.$vs.notify({
                title: "Registro Editado",
                text: "Registro Editado exitosamente",
                color: "success"  
              });
              this.listar(1, this.buscar);
            }else{
              if(res.data == "existe"){
                this.$vs.notify({
                title: "Error al Editar",
                text: "El codigo ya existe.",
                color: "danger"
              });
              }
              if(res.data == "NO vale" ){
              this.$vs.notify({
                title: "Error al Editar",
                text: "El codigo debe terminar con .",
                color: "danger"
              });
            }
            }
          }else{
            if (res.data != "existe" && res.data != "mov mal") {
              this.activarmodal = false;
              this.$vs.notify({
                title: "Registro Editado",
                text: "Registro Editado exitosamente",
                color: "success"  
              });
              this.listar(1, this.buscar);
            }else{
              if(res.data == "existe"){
                this.$vs.notify({
                title: "Error al Editar",
                text: "El codigo ya existe.",
                color: "danger"
              });
              }
              if(res.data == "mov mal"){
                this.$vs.notify({
                  title: "Error al Editar",
                  text: "El codigo debe terminar con numero",
                  color: "danger"
                });
              }
          }
          }
        });
    },
    eliminar(id) {
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: "Confirmar",
        text: "¿Desea Eliminar este registro?:",
        acceptText: "Aceptar",
        cancelText: "Cancelar",
        accept: this.eliminarreg,
        parameters: id
      });
    },
    eliminarreg(parameters) {
      axios.delete("/api/eliminarcta/" + parameters);
      this.$vs.notify({
        color: "success",
        title: "Cuenta Eliminada  ",
        text: "La Cuenta selecionada fue eliminada con exito"
      });
      this.listar(1, this.buscar);
    },
    validarplancta() {
      this.errorplc = 0;
      this.errorcodigo = [];
      this.errorbansel=[];
      this.errornomcta = [];
      this.errormonedaplc = [];
      this.errorgrupo = [];
      this.errornum_cuenta=[];
      this.errorid_banco=[];
      this.errorexist_cuenta_banco=[];
      if (!this.codcta) {
        this.errorcodigo.push("Campo Obligatorio");
        this.errorplc = 1;
      }
      if (!this.nomcta) {
        this.errornomcta.push("Campo Obligatorio");
        this.errorplc = 1;
      }
      if (!this.id_moneda) {
        this.errormonedaplc.push("Campo Obligatorio");
        this.errorplc = 1;
      }
      
      if (!this.id_grupo) {
        this.errorgrupo.push("Campo Obligatorio");
        this.errorplc = 1;
      }// }else{
      //   if(this.id_grupo==2){
      //     if(this.exist_cuenta_banco==false){
      //       this.errorexist_cuenta_banco.push("Campo Obligatorio");
      //       this.errorplc=1;
      //     }else{
      //       if(!this.bansel){
      //         this.errorbansel.push("Campo Obligatorio");
      //         this.errorplc=1;
      //       }else{
      //         if(!this.num_cuenta){
      //           this.errornum_cuenta.push("Campo Obligatorio");
      //           this.errorplc = 1;
      //         }
      //         if(!this.id_banco){
      //           this.errorid_banco.push("Campo Obligatorio");
      //           this.errorplc = 1;
      //         }
      //       }
      //     }
          
      //   }
      // }
      return this.errorplc;
    },
    validarcaja() {
      this.errorcaja = 0;
      this.errordescripcion = [];
      this.errorctacontable = [];
      this.errormonedacaja = [];
      if (!this.descrip_caja) {
        this.errordescripcion.push("Campo Obligatorio");
        this.errorcaja = 1;
      }
      if (!this.cuenta_contable) {
        this.errorctacontable.push("Campo Obligatorio");
        this.errorcaja = 1;
      }
      if (!this.id_moneda2) {
        this.errormonedacaja.push("Campo Obligatorio");
        this.errorcaja = 1;
      }
      return this.errorcaja;
    },
    reporte_balance() {
      window.open(
        "/api/reportes/balance-comprobacion?tipo_busqueda=" +
          this.tipo_busqueda +
          "&dateinicio=" +
          this.dateinicio +
          "&datefin=" +
          this.datefin,
        "_top"
      );
      this.$vs.notify({
        title: "Reporte Generado",
        text: "Su reporte esta siendo descargado exitosamente!",
        color: "success"
      });
    },
    recargar_reporte() {
      this.dateinicio = "";
      this.datefin = "";
    },
    solodecimales: function($event) {
            let keyCode = $event.keyCode ? $event.keyCode : $event.which;
            if ((keyCode < 48 || keyCode > 57) && keyCode !== 46) {
                // 46 is dot
                $event.preventDefault();
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
  },
  
  mounted() {
    this.listar(1, this.buscar);
    this.listarcaja(1, this.buscarcaja);
    this.listarempresas();
    this.listarmonedas();
    this.listarmonedas2();
    this.listarbancos();
    this.listargrupos();
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
  width: 800px !important;
}
.peque .vs-popup {
  width: 1000px !important;
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
.menuescoger {
    position: absolute;
    margin-top: -11px;
    width: 100%;
    background: #fff;
    z-index: 999;
    border: 1px solid #dfdfdf;
    border-radius: 0 0 8px 8px;
}

.menuescoger ul {
    list-style: none;
    padding: 8px 15px 25px 15px;
    margin: 0;
    cursor: pointer;
    color: #848484;
    font-weight: 600;
    font: 13.3333px Arial, sans-serif;
    position: relative;
    border-bottom: 1px solid #eaeaea;
}

.menuescoger ul:hover {
    background: rgba(0, 0, 0, 0.1);
}

.menuescoger span {
    font-size: 12px;
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
  max-width: 50%;
  max-height: 50px;
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