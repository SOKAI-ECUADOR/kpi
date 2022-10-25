 <template>
  <div id="ag-grid-demo">
    <vx-card>
      <div class="flex flex-wrap justify-between items-center mb-4">
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
                <vs-dropdown-item class="text-center" divider @click="verGrupo()">Grupo Proveedor</vs-dropdown-item>
                <!--<vs-dropdown-item class="text-center" divider @click="verTipocomprob()">Tipo Comprobante</vs-dropdown-item>-->
                <!--<vs-dropdown-item class="text-center" divider @click="abrirbodega()">Bodega</vs-dropdown-item>-->
              </vs-dropdown-menu>
            </vs-dropdown>
          </div>
          <!--<vs-button class="mb-4 md:mb-0" @click="gridApi.exportDataAsCsv()">Export as CSV</vs-button>-->
          <div class="dropdown-button-container" v-if="crearrol">
            <vs-button class="btnx" type="filled" to="/compras/proveedor/agregar">Agregar</vs-button>
            <vs-dropdown>
              <vs-button class="btn-drop" type="filled" icon="expand_more"></vs-button>
              <vs-dropdown-menu style="width:13em;">
                <vs-dropdown-item class="text-center" @click="importar=true">Importar registros</vs-dropdown-item>
                <vs-dropdown-item class="text-center" @click="exportar=true">Exportar registros</vs-dropdown-item>
                <vs-dropdown-item class="text-center" divider>Generar PDF</vs-dropdown-item>
                <vs-dropdown-item class="text-center">Generar XML</vs-dropdown-item>
              </vs-dropdown-menu>
            </vs-dropdown>
          </div>
        </div>
      </div>

      <vs-table stripe max-items=25 pagination :data="contenido">
        <template slot="thead">
          <vs-th>Codigo</vs-th>
          <vs-th>Nombre</vs-th>
          <vs-th>Ruc/Ci</vs-th>
          <vs-th>Direccion</vs-th>
          <vs-th>Telefono</vs-th>
          <vs-th>Acciones</vs-th>
        </template>
        <template slot-scope="{data}">
          <vs-tr :key="datos.id" v-for="datos in data">
            <vs-td>{{datos.cod_proveedor}}</vs-td>
            <vs-td v-if="datos.nombre_proveedor   ">{{datos.nombre_proveedor }}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.identif_proveedor ">{{datos.identif_proveedor }}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.direccion_prov  ">{{datos.direccion_prov }}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.telefono_prov  ">{{datos.telefono_prov }}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td class="whitespace-no-wrap">
              <vx-tooltip text="Editar Proveedor" style="display: inline-flex;">
                <feather-icon
                  v-if="editarrol"
                  icon="EditIcon"
                  class="cursor-pointer"
                  svgClasses="w-5 h-5 hover:text-primary stroke-current"
                  @click.stop="edit(datos.id_proveedor)"
                />
              </vx-tooltip>
              <vx-tooltip text="Eliminar Proveedor" style="display: inline-flex;">
                <feather-icon
                  v-if="eliminarrol"
                  icon="TrashIcon"
                  class="cursor-pointer"
                  svgClasses="w-5 h-5 hover:text-danger stroke-current"
                  @click.stop="eliminar(datos.id_proveedor)"
                />
                <!--@click.stop="eliminar(datos.id_empresa)"-->
              </vx-tooltip>
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
    </vx-card>
    <vs-popup :title="titulomodal" :active.sync="modal">
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
                    @keyup="listargrupo(1,buscar1)"
                    v-bind:placeholder="i18nbuscar"
                  />
                  <div>
                    <vs-button
                      class="btnx"
                      type="filled"
                      divider
                      @click="abrilmodalGrupo()"
                    >Agregar Nuevo</vs-button>
                  </div>
                </div>
              </div>
              <vs-table stripe :data="contenidogrupo">
                <template slot="thead">
                  <vs-th>Nombre</vs-th>
                  <vs-th>Cuenta</vs-th>
                  <vs-th>Cuenta Dscto</vs-th>
                  <vs-th>Cuenta Anticipo</vs-th>

                  <vs-th>Opciones</vs-th>
                </template>
                <template slot-scope="{data}">
                  <vs-tr :key="datos.id_producto" v-for="datos in data">
                    <vs-td v-if="datos.nombre_grupoprov">{{datos.nombre_grupoprov}}</vs-td>
                    <vs-td v-else>-</vs-td>
                    <vs-td v-if="datos.cta_contable">{{datos.cta_contable}}</vs-td>
                    <vs-td v-else>-</vs-td>
                    <vs-td v-if="datos.cta_descuento">{{datos.cta_descuento}}</vs-td>
                    <vs-td v-else>-</vs-td>
                    <vs-td v-if="datos.cta_anticipo">{{datos.cta_anticipo}}</vs-td>
                    <vs-td v-else>-</vs-td>
                    <vs-td class="whitespace-no-wrap">
                      <feather-icon
                        icon="EditIcon"
                        class="cursor-pointer"
                        svgClasses="w-5 h-5 hover:text-primary stroke-current"
                        @click.stop="editGrupo(datos.id_grupoprov)"
                      />
                      <!--ideliminar=datos.id_linea_producto;eliminar=true;tipoaccionmodal=1-->
                      <feather-icon
                        icon="TrashIcon"
                        svgClasses="w-5 h-5 hover:text-danger stroke-current"
                        class="ml-2 cursor-pointer"
                        @click.stop="elimiGrupo(datos.id_grupoprov)"
                      />
                    </vs-td>
                  </vs-tr>
                </template>
              </vs-table>
            </div>
          </div>
        </vx-card>
      </div>
      <vs-popup :title="titulomodal" :active.sync="modaleliminar">
        <input v-model="id_grupoproveedor" hidden />
        <p>Desea eliminar Este reguistro</p>
        <div class="vx-row">
          <div class="vx-col w-full">
            <vs-button
              color="warning"
              type="filled"
              @click="acceptAlertGrupo(id_grupoproveedor)"
            >BORRAR</vs-button>
          </div>
        </div>
      </vs-popup>
      <vs-popup :title="titulomodal" :active.sync="modalgrupo">
        <div class="vx-row">
          <!--<div class="vx-col sm:w-1/5 w-full mb-6">
            <vs-input class="w-1/2" label="Codigo" placeholder="00" v-model="cod_grupoprov" />
          </div>-->
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input class="w-full" label="Nombre" v-model="nombre_grupoprov" />
            <div v-show="error" v-if="!nombre_grupoprov">
              <span
                class="text-danger"
                v-for="err in errornombre_grupoprov"
                :key="err"
                v-text="err"
              ></span>
            </div>
          </div>

          <div class="vx-col sm:w-1/5 w-full mb-6">
            <label class="vs-input--label">Importador</label>
            <vs-checkbox v-model="importador" vs-value="Si"></vs-checkbox>
          </div>
        </div>
        <div class="vx-row">
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <label class="vs-input--label">Cuenta Contable</label>
            <vx-input-group class>
              <vs-input class="w-full" v-model="cta_contable" :value="id_cta_contable" disabled/>
              <template slot="append">
                <div class="append-text btn-addon">
                  <!-- -->
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
                                        v-model="cta_contable"
                                        @keyup="
                                            listarcuenta(cta_contable),
                                                abrirlista()
                                        "
                  />
                </div>
                <div class="menuescoger" style="display:none" id="menuescoger" v-if="cta_contable">
                  <template v-if="contenidocuenta.length">
                    <ul
                      v-for="(tr,
                                            index) in contenidocuenta"
                                            :key="index"
                                            @click="handleSelectedCuentaContable(tr)"
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
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <label class="vs-input--label">Cuenta Dscto</label>
            <vx-input-group class>
              <vs-input class="w-full" v-model="cta_descuento" :value="id_cta_descuento" disabled/>
              <template slot="append">
                <div class="append-text btn-addon">
                  <!-- -->
                  <vs-button color="primary" @click="activePrompt4 = true">Buscar</vs-button>
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
                                        label="Cuenta Descuento"
                                        type="text"
                                        class="w-full"
                                        placeholder="Buscar Cuenta Descuento"
                                        v-model="cta_descuento"
                                        @keyup="
                                            listarcuentadesc(cta_descuento),
                                                abrirlistadesc()
                                        "
                  />
                </div>
                <div
                  class="menuescogerdesc"
                  style="display:none"
                  id="menuescogerdesc"
                  v-if="cta_descuento"
                >
                  <template v-if="contenidocuentadesc.length">
                    <ul
                      v-for="(tr,
                                            index) in contenidocuentadesc"
                                            :key="index"
                                            @click="handleSelectedCuentaDesc(tr)"
                                        >
                                            <li>
                                                {{ tr.nomcta }}
                                                <span class="posiciondesc">
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
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
          <label class="vs-input--label">Cuenta Anticipo</label>
            <vx-input-group class>
              <vs-input class="w-full" v-model="cta_anticipo" :value="id_cta_anticipo" disabled/>
              <template slot="append">
                <div class="append-text btn-addon">
                  
                  <vs-button color="primary" @click="activePrompt5 = true">Buscar</vs-button>
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
                                        label="Cuenta Anticipo"
                                        type="text"
                                        class="w-full"
                                        placeholder="Buscar Cuenta Anticipo"
                                        v-model="cta_anticipo"
                                        @keyup="
                                            listarcuentaantcp(cta_anticipo),
                                                abrirlistaantcp()
                                        "
                  />
                </div>
                <div
                  class="menuescogerantcp"
                  style="display:none"
                  id="menuescogerantcp"
                  v-if="cta_anticipo"
                >
                  <template v-if="contenidocuentaantcp.length">
                    <ul
                      v-for="(tr,
                                            index) in contenidocuentaantcp"
                                            :key="index"
                                            @click="handleSelectedCuentaAntcp(tr)"
                                        >
                                            <li>
                                                {{ tr.nomcta }}
                                                <span class="posicionantcp">
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
          </div>
        </div>

        <vs-divider />
        <div class="vx-row">
          <div class="vx-col w-full">
            <vs-button color="success" type="filled" @click="guardar()" v-if="!idrecupera">GUARDAR</vs-button>
            <vs-button color="success" type="filled" @click="editarGrupo()" v-else>GUARDAR</vs-button>
            <vs-button color="warning" type="filled" @click="vaciar()">BORRAR</vs-button>
            <vs-button color="danger" type="filled" @click="cancelar()">CANCELAR</vs-button>
          </div>
        </div>
        <vs-popup title="Plan Cuentas" :active.sync="activePrompt3">
          <div class="con-exemple-prompt">
            <vs-input
              class="mb-4 md:mb-0 mr-4 w-full"
              v-model="buscar3"
              @keyup="listar3(1,buscar3)"
              v-bind:placeholder="i18nbuscar3"
            />
            <vs-table stripe v-model="cuentaarray3" @selected="handleSelected3" :data="contenido3">
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
        <vs-popup title="Plan Cuentas" :active.sync="activePrompt4">
          <div class="con-exemple-prompt">
            <vs-input
              class="mb-4 md:mb-0 mr-4 w-full"
              v-model="buscar3"
              @keyup="listar3(1,buscar3)"
              v-bind:placeholder="i18nbuscar3"
            />
            <vs-table stripe v-model="cuentaarray4" @selected="handleSelected4" :data="contenido3">
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
        <vs-popup title="Plan Cuentas" :active.sync="activePrompt5">
          <div class="con-exemple-prompt">
            <vs-input
              class="mb-4 md:mb-0 mr-4 w-full"
              v-model="buscar3"
              @keyup="listar3(1,buscar3,criterio3,cantidadp3)"
              v-bind:placeholder="i18nbuscar3"
            />
            <vs-table stripe v-model="cuentaarray5" @selected="handleSelected5" :data="contenido3">
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

    <!--Modal para exportar excel-->
    <vs-popup  :class="'peque1'" title="Exportar Excel" :active.sync="exportar">
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
    <!--fin modal de exportar-->
  </div>
</template>
<script>
import ImportExcel from "@/components/excel/ImportExcel.vue";
import { AgGridVue } from "ag-grid-vue";
import $ from "jquery";
import vSelect from "vue-select";
const axios = require("axios");
export default {
  components: {
    AgGridVue,
    ImportExcel
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
      cantidadp: 20,
      offset: 0,
      //buscador
      buscar: "",
      criterio: "nombre_proveedor",
      //otros valores
      gridApi: null,
      contenido: [],
      activePrompt: false,
      val: "",
      //lenguaje
      i18nbuscar: this.$t("i18nbuscar"),
      titulomodal: "",
      modal: false,
      tipoaccion: 0,
      contenidogrupo: [],
      pagination1: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
        count: 0
      },
      cantidadp1: 10,
      offset: 3,
      pagina: 1,
      buscar1: "",
      //variables grupo-proveedor
      idrecupera: null,
      cod_grupoprov: "",
      nombre_grupoprov: "",
      importador: null,
      cta_contable: "",
      id_cta_contable: "",
      cta_descuento: "",
      id_cta_descuento: "",
      cta_anticipo: "",
      id_cta_anticipo: "",
      contenidocuenta: [],
      contenidocuentadesc: [],
      contenidocuentaantcp: [],
      pagina1: 1,
      //modal plan ctas
      activePrompt3: false,
      buscar3: "",
      pagina3: 1,
      cantidadp3: 50,
      criterio3: "codcta",
      pagination3: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
        count: 0
      },
      cuentaarray3: [],
      i18nbuscar3: this.$t("i18nbuscar"),
      contenido3: [],
      //modal plan ctas dscto
      activePrompt4: false,
      cuentaarray4: [],
      //modal plan ctas anticipo
      activePrompt5: false,
      cuentaarray5: [],
      //form grupo-proveedor
      modalgrupo: false,
      //modal eliminar grupo-proveedor
      modaleliminar: false,
      id_grupoproveedor: "",
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
        
        "id_proveedor",
        "cod_proveedor",
        "id_grupo_proveedor",
        "nombre_proveedor",
        "tipo_identificacion",
        "identif_proveedor",
        "contribuyente",
        "beneficiario",
        "contacto",
        "direccion_prov",
        "nrcasa",
        "id_provincia",
        "id_ciudad",
        "telefono_prov",
        "estado_prov",
        "id_banco",
        "tipo_cuenta",
        "cta_banco",
        "pagos",
        "plazo",
        "dias_pago",
        "id_plan_cuentas",
        "comentario",
        "tip_comprob",
        "serie",
        "fvalidez",
        "rangomin",
        "rangomax",
        "nrautorizacion",
        "contribuye_sri",
        "tip_electronico",
        "imp_retencion",
        "codsri_imp",
        "retencion_iva",
        "codsri_iva",
        "cash_manager",
        "fcrea",
        "fmodifica",
        "id_empresa"
      ],
      //campos elegidos a exportar
      indexs: [
        "cod_proveedor",
        "id_grupo_proveedor",
        "nombre_proveedor",
        "tipo_identificacion",
        "identif_proveedor",
        "contribuyente",
        "beneficiario",
        "contacto",
        "direccion_prov",
        "nrcasa",
        "id_provincia",
        "id_ciudad",
        "telefono_prov",
        "estado_prov",
        "id_banco",
        "tipo_cuenta",
        "cta_banco",
        "pagos",
        "plazo",
        "dias_pago",
        "id_plan_cuentas",
        "comentario",
        "tip_comprob",
        "serie",
        "fvalidez",
        "rangomin",
        "rangomax",
        "nrautorizacion",
        "contribuye_sri",
        "tip_electronico",
        "imp_retencion",
        "codsri_imp",
        "retencion_iva",
        "codsri_iva",
        "cash_manager",
        "id_empresa"
      ],
      //cabezera que imprimira en el excel
      cabezera: [
        "codigo",
        "id_grupo_proveedor",
        "nombre",
        "tipo_identificacion",
        "identificacion_representante",
        "contribuyente_especial",
        "beneficiario",
        "contacto",
        "direccion",
        "num_casa",
        "id_provincia",
        "id_ciudad",
        "telefono",
        "estado",
        "id_banco",
        "tipo_cuenta",
        "cuenta_banco",
        "pagos",
        "plazo",
        "dias_pago",
        "id_plan_cuentas",
        "comentario",
        "tipo_comprobante",
        "serie",
        "fecha_validez",
        "fecha_inicial",
        "fecha_final",
        "num_autorizacion",
        "contribuye_sri",
        "tipo_facturacion",
        "impuesto_retencion",
        "codigo_sri_impuesto",
        "retencion_iva",
        "codigo_sri_iva",
        "cash_manager",
        "id_empresa"
      ],
      //errores grupo proveedor
      //nombre_grupoprov,importador,cta_contable,cta_descuento,cta_anticipo-->
      error: 0,
      errornombre_grupoprov: [],
      errorcod_grupoprov: []
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
        res = this.$store.state.Roles[19].crear;
      }
      return res;
    },
    editarrol() {
      var res = 0;
      if (this.usuario.id_rol == 1) {
        res = 1;
      } else {
        res = this.$store.state.Roles[19].editar;
      }
      return res;
    },
    eliminarrol() {
      var res = 0;
      if (this.usuario.id_rol == 1) {
        res = 1;
      } else {
        res = this.$store.state.Roles[19].eliminar;
      }
      return res;
    }
  },
  methods: {
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
    importarexcel() {
      $(".inputexcel").click();
    },
    importardatos() {
      let formData = new FormData();

      formData.append("id_empresa", this.usuario.id_empresa);
      formData.append("file", this.file[0]);
      axios
        .post("/api/importarproveedorexcel", formData, {})
        .then(res => {
          document.getElementById("input-upload").value = ""
          this.$vs.notify({
            text: "Archivo Importado con exito",
            color: "success",
          });
          this.importar = false;
          this.listar(1, this.buscar);
          this.file = [];
          
          /*
          if(res.data==="existe"){
            this.$vs.notify({
            text: "Uno de los ya existeo",
            color: "danger"
          });
          
          }else{
            this.$vs.notify({
            text: "Archivo Importado con exito",
            color: "success"
          });
          this.importar = false;
          this.listar(1, this.buscar);
          }
          */
          
        })
        .catch(err => {
          this.$vs.notify({
            text: "Archivo Importado 'Sin Exito'",
            color: "danger"
          });
          this.importar = false;
          this.listar(1, this.buscar);
          this.file = [];
        });
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
    handleSelected3(tr) {
      (this.cta_contable = `${tr.codcta}`), (this.activePrompt3 = false);
    },
    handleSelected4(tr) {
      (this.cta_descuento = `${tr.codcta}`), (this.activePrompt4 = false);
    },
    handleSelected5(tr) {
      (this.cta_anticipo = `${tr.codcta}`), (this.activePrompt5 = false);
    },
    listar3(page3, buscar3) {
      var url =
        "/api/notacredito/listar_cuenta_contable" 
        // +
        // this.usuario.id_empresa +
        // "?page=" +
        // page3 +
        // "&buscar=" +
        // buscar3
        ;
      axios.get(url,{
        params:{
          empresa:this.usuario.id_empresa,
          buscar:buscar3
        }
      }).then(({data}) => {
        var respuesta = data;
        this.contenido3 = respuesta;
      });
    },
    vaciar() {
      (this.cod_grupoprov = ""),
        (this.nombre_grupoprov = ""),
        (this.importador = null),
        (this.cta_contable = ""),
        (this.cta_descuento = ""),
        (this.id_cta_contable = ""),
        (this.id_cta_descuento = ""),
        (this.id_cta_anticipo = ""),
        (this.cta_anticipo = "");
    },
    listarcuenta(buscar1) {
      axios
        .get(
          "/api/selcuentasgrupprov/" +
            this.usuario.id_empresa +
            "?buscar=" +
            buscar1
        )
        .then(res => {
          this.contenidocuenta = res.data.recupera;
          //this.contenidocuentadesc = res.data.recupera;
          //this.contenidocuentaantcp = res.data.recupera;
        });
    },
    abrirlista() {
      $(".menuescoger").show();
    },
    listarcuentadesc(buscar1) {
      axios
        .get(
          "/api/selcuentasgrupprov/" +
            this.usuario.id_empresa +
            "?buscar=" +
            buscar1
        )
        .then(res => {
          //this.contenidocuenta = res.data.recupera;
          this.contenidocuentadesc = res.data.recupera;
          //this.contenidocuentaantcp = res.data.recupera;
        });
    },
    abrirlistadesc() {
      $(".menuescogerdesc").show();
    },
    listarcuentaantcp(buscar1) {
      axios
        .get(
          "/api/selcuentasgrupprov/" +
            this.usuario.id_empresa +
            "?buscar=" +
            buscar1
        )
        .then(res => {
          //this.contenidocuenta = res.data.recupera;
          //this.contenidocuentadesc = res.data.recupera;
          this.contenidocuentaantcp = res.data.recupera;
        });
    },
    abrirlistaantcp() {
      $(".menuescogerantcp").show();
    },
    handleSelected3(tr) {
      (this.cta_contable = `${tr.nomcta}`),(this.id_cta_contable = `${tr.id_plan_cuentas}`), (this.activePrompt3 = false);
    },
    handleSelected4(tr) {
      (this.cta_descuento = `${tr.nomcta}`),(this.id_cta_descuento = `${tr.id_plan_cuentas}`), (this.activePrompt4 = false);
    },
    handleSelected5(tr) {
      (this.cta_anticipo = `${tr.nomcta}`),(this.id_cta_anticipo = `${tr.id_plan_cuentas}`), (this.activePrompt5 = false);
    },

    /*handleSelectedCuentaContable(tr){
        this.id_cta_contable = `${tr.id_plan_cuentas}`;
        this.cta_contable = `${tr.codcta}`;
    },
    handleSelectedCuentaDesc(tr) {
      this.id_cta_descuento = `${tr.id_plan_cuentas}`;
      this.cta_descuento = `${tr.codcta}`;
      $(".menuescogerdesc").hide();
    },
    handleSelectedCuentaAntcp(tr){
        this.id_cta_anticipo = `${tr.id_plan_cuentas}`;
        this.cta_anticipo = `${tr.codcta}`;
        $(".menuescogerantcp").hide();
    },*/
    guardar() {
      if (this.validargrupo()) {
        return;
      }
      axios
        .post("/api/agregargrupoprov", {
          cod_grupoprov: this.cod_grupoprov,
          nombre_grupoprov: this.nombre_grupoprov,
          importador: this.importador,
          cta_contable: this.cta_contable,
          id_cta_contable: this.id_cta_contable,
          cta_descuento: this.cta_descuento,
          id_cta_descuento: this.id_cta_descuento,
          cta_anticipo: this.cta_anticipo,
          id_cta_anticipo: this.id_cta_anticipo,
          id_empresa: this.usuario.id_empresa
        })
        .then(res => {
          if (res.data != "existe") {
            this.$vs.notify({
              title: "Registro Guardado",
              text: "Registro Guardado exitosamente",
              color: "success"
            });
            //console.log(res.data);
            this.modalgrupo = false;
            this.vaciar();
            this.listargrupo(1, this.buscar1, this.cantidadp1);
          } else {
            this.$vs.notify({
              title: "Codigo Exite",
              text: "este codigo proveedor ya existe existe",
              color: "danger"
            });
          }
        });
    },
    editarGrupo() {
      if (this.validargrupo()) {
        return;
      }
      axios
        .put("/api/actualizargrupoprov", {
          id: this.idrecupera,
          cod_grupoprov: this.cod_grupoprov,
          nombre_grupoprov: this.nombre_grupoprov,
          importador: this.importador,
          cta_contable: this.cta_contable,
          id_cta_contable: this.id_cta_contable,
          cta_descuento: this.cta_descuento,
          id_cta_descuento: this.id_cta_descuento,
          cta_anticipo: this.cta_anticipo,
          id_cta_anticipo: this.id_cta_anticipo,
          id_empresa: this.usuario.id_empresa
        })
        .then(res => {
          if (res.data != "existe") {
            this.$vs.notify({
              title: "Registro Guardado",
              text: "Registro Guardado exitosamente",
              color: "success"
            });
            //console.log(res.data);
            this.modalgrupo = false;
            this.vaciar();
            this.listargrupo(1, this.buscar1, this.cantidadp1);
          } else {
            this.$vs.notify({
              title: "Cuenta Contable",
              text: "cuenta contable no existe",
              color: "danger"
            });
          }
        });
    },
    abrilmodalGrupo() {
      (this.modalgrupo = true),
        (this.titulomodal = "Agregar Grupo"),
        (this.idrecupera = null);
      (this.cod_grupoprov = ""),
        (this.nombre_grupoprov = ""),
        (this.importador = null),
        (this.cta_contable = ""),
        (this.cta_descuento = ""),
        (this.cta_anticipo = "");
    },
    editGrupo(id) {
      if (id) {
        this.idrecupera = id;
        this.modalgrupo = true;
        this.titulomodal = "Editar Grupo";
        var url = "/api/abrirgrupoprov/" + id;
        axios
          .put(url)
          .then(res => {
            let data = res.data[0];
            this.cod_grupoprov = data.cod_grupoprov;
            this.nombre_grupoprov = data.nombre_grupoprov;
            this.importador = data.importador;
            this.cta_contable = data.cta_contable;
            this.cta_descuento = data.cta_descuento;
            this.cta_anticipo = data.cta_anticipo;
            this.id_cta_contable= data.id_plan_cuentas;
            this.id_cta_descuento= data.id_plan_cuentas_descuento;
            this.id_cta_anticipo= data.id_plan_cuentas_anticipo;
            //this.fecult = data.fecult;
          })
          .catch(err => {
            console.log(err);
          });
      } else {
        this.idrecupera = null;
      }
    },
    elimiGrupo(id_grupo) {
      this.modaleliminar = true;
      this.id_grupoproveedor = id_grupo;
    },
    acceptAlertGrupo(parameters) {
      this.modaleliminar = false;
      axios.delete("/api/eliminargrupoproveedor/" + parameters);
      this.$vs.notify({
        color: "danger",
        title: "Grupo Eliminado  ",
        text: "El Grupo selecionado fue eliminado con exito"
      });
      this.listargrupo(1, this.buscar1, this.cantidadp1);
    },
    verGrupo() {
      this.tipoaccion = 1;
      this.modal = true;
      this.titulomodal = "Grupo Proveedores";
    },
    listargrupo(page1, buscar1) {
      var url =
        "/api/grupoprov/" +
        this.usuario.id_empresa +
        "?page=" +
        page1 +
        "&buscar=" +
        buscar1;
      axios.get(url).then(res => {
        var respuesta = res.data;
        this.contenidogrupo = respuesta.recupera;
      });
    },
    agregarGrupo() {
      this.modal;
    },
    edit(id) {
      this.$router.push(`/compras/proveedor/${id}/editar`);
    },
    /*eliminar(id) {
     
      
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: `Confirmar`,
        text:
          "¿Desea Elimnar este registro?",
          
        accept: this.acceptAlert(id)
      });
      
    },*/
    cancelar() {
      this.modalgrupo = false;
      this.vaciar();
      this.listargrupo(1, this.buscar1);
    },
    eliminar(cd) {
      //console.log("1", cd);

      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: `Confirmar`,
        text: `¿Desea Elimnar este registro?:`,
        acceptText: "Aceptar",
        cancelText: "Cancelar",
        accept: this.acceptAlert,
        parameters: cd
      });
    },
    acceptAlert(parameters) {
      //console.log("2", parameters);
      axios.delete("/api/eliminarproveedor/" + parameters);
      this.$vs.notify({
        color: "danger",
        title: "Proveedor Eliminado  ",
        text: "El Proveedor selecionado fue eliminado con exito"
      });
      this.listar(1, this.buscar, this.cantidadp);
    },

    //exportar archivos

    exportardatos() {
      import("../../../vendor/Export2ProveedorExcel").then(excel => {
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

    listar(page, buscar) {
      var url =
        "/api/proveedor/" +
        this.usuario.id_empresa +
        "?page=" +
        page +
        "&buscar=" +
        buscar;
      axios
        .get(url)
        .then(res => {
          var respuesta = res.data;
          this.contenido = respuesta.recupera;
        })
        .catch(function(error) {
          this.$vs.notify({
            title: "Error al Guardar",
            text: "Verifique bien sus datos al momento de guardar",
            color: "danger"
          });
        });
    },
    validargrupo() {
      this.error = 0;
      this.errornombre_grupoprov = [];
      this.errorcod_grupoprov = [];
      /*if (!this.cod_grupoprov) {
        this.errorcod_grupoprov.push("Campo Obligatorio");
        this.error = 1;
      }*/
      if (!this.nombre_grupoprov) {
        this.errornombre_grupoprov.push("Campo Obligatorio");
        this.error = 1;
      }
      return this.error;
    },
    updateSearchQuery(val) {
      this.gridApi.setQuickFilter(val);
    }
  },
  mounted() {
    this.listar(1, this.buscar);
    this.listargrupo(1, this.buscar1);
    this.listar3(1, this.buscar3);
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
  width: 900px !important;
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
//menu escoger cuenta contable
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
  font: 14px arial, sans-serif;
  position: relative;
  border-bottom: 1px solid #eaeaea;
}

.menuescoger ul:hover {
  background: rgba(0, 0, 0, 0.1);
}

.menuescoger span {
  font-size: 12px;
}
.posicion {
  bottom: 5px;
  position: absolute;
  left: 15px;
}
.posicion span {
  font-size: 10px;
}
//menu escoger cta desc
.menuescogerdesc {
  position: absolute;
  margin-top: -11px;
  width: 100%;
  background: #fff;
  z-index: 999;
  border: 1px solid #dfdfdf;
  border-radius: 0 0 8px 8px;
}

.menuescogerdesc ul {
  list-style: none;
  padding: 8px 15px 25px 15px;
  margin: 0;
  cursor: pointer;
  color: #848484;
  font-weight: 600;
  font: 14px arial, sans-serif;
  position: relative;
  border-bottom: 1px solid #eaeaea;
}

.menuescogerdesc ul:hover {
  background: rgba(0, 0, 0, 0.1);
}

.menuescogerdesc span {
  font-size: 12px;
}
.posiciondesc {
  bottom: 5px;
  position: absolute;
  left: 15px;
}
.posiciondesc span {
  font-size: 10px;
}
//menu escoger cta anticipo
.menuescogerantcp {
  position: absolute;
  margin-top: -11px;
  width: 100%;
  background: #fff;
  z-index: 999;
  border: 1px solid #dfdfdf;
  border-radius: 0 0 8px 8px;
}

.menuescogerantcp ul {
  list-style: none;
  padding: 8px 15px 25px 15px;
  margin: 0;
  cursor: pointer;
  color: #848484;
  font-weight: 600;
  font: 14px arial, sans-serif;
  position: relative;
  border-bottom: 1px solid #eaeaea;
}

.menuescogerantcp ul:hover {
  background: rgba(0, 0, 0, 0.1);
}

.menuescogerantcp span {
  font-size: 12px;
}
.posicionantcp {
  bottom: 5px;
  position: absolute;
  left: 15px;
}
.posicionantcp span {
  font-size: 10px;
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
