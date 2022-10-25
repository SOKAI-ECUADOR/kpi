<template>
  <div id="ag-grid-demo">
    <vx-card>
      <div class="flex flex-wrap justify-between items-center">
        <div class="flex flex-wrap justify-between ag-grid-table-actions-left">
            <template v-if="filterstable.filtertab">
                        <label
                            style="position: absolute; top: 0.2%; left: 0.4%;"
                            ><b>Mostrar:</b></label
                        >
                        <div
                            class="flex flex-wrap justify-between items-center mb-1 mr-5"
                        >
                            <label class="vs-input--label mr-1"
                                >Contabilizados</label
                            >
                            <vs-switch
                                color="success"
                                @input="filtertabla()"
                                v-model="filterstable.filt_asientos"
                            />
                        </div>
                        <div
                            class="flex flex-wrap justify-between items-center mb-1 mr-5"
                        >
                            <label class="vs-input--label mr-1"
                                >No-contabilizados</label
                            >
                            <vs-switch
                                color="success"
                                @input="filtertabla()"
                                v-model="filterstable.filt_noasientos"
                            />
                        </div>
                        <div
                            class="flex flex-wrap justify-between items-center mb-1 mr-5"
                        >
                            <label class="vs-input--label mr-1"
                                >Iniciales</label
                            >
                            <vs-switch
                                color="success"
                                @input="filtertabla()"
                                v-model="filterstable.filt_inicial"
                            />
                        </div>
                        <div
                            class="flex flex-wrap justify-between items-center mb-1 mr-5"
                        >
                            <label class="vs-input--label mr-1"
                                >Liquidados</label
                            >
                            <vs-switch
                                color="success"
                                @input="filtertabla()"
                                v-model="filterstable.filt_liquid"
                            />
                        </div>
                    </template>         
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
            <div
                        class="flex flex-wrap justify-between items-center mr-1 ml-1"
                    >
                        <vx-tooltip
                            text="Filtrar"
                            position="top"
                            style="display: inline-flex;"
                        >
                            <vs-button
                                v-if="filterstable.filtertab == false"
                                color="primary"
                                type="filled"
                                icon="filter_list"
                                @click="filterstable.filtertab = true"
                            ></vs-button>
                            <vs-button
                                v-else
                                color="success"
                                type="filled"
                                icon="filter_list"
                                @click="
                                    (filterstable.filtertab = false),
                                        filtertabla()
                                "
                            ></vs-button
                        ></vx-tooltip>
                    </div>         
          <div class="dropdown-button-container mr-3">
                        <vs-dropdown>
                            <vs-button
                                class="btn-drop"
                                type="filled"
                                icon="settings"
                                style="border-radius: 5px;"
                            ></vs-button>
                            <vs-dropdown-menu style="width:13em;">
                                <vs-dropdown-item
                                    class="text-center"
                                    divider
                                    @click="abrirCtaImport()"
                                    >Cuenta de Importacion</vs-dropdown-item
                                >
                            </vs-dropdown-menu>
                        </vs-dropdown>
          </div>
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
      <vs-table stripe max-items="25" pagination :data="contenido">
        <template slot="thead">
          <vs-th>No.Importacion</vs-th>
          <vs-th>Proveedor</vs-th>
          <vs-th>Fecha Liquidacion</vs-th>
          <vs-th>Estado</vs-th>
          <vs-th>Total</vs-th>
          <vs-th>Acciones</vs-th>
        </template>
        <template slot-scope="{data}">
          <vs-tr v-for="datos in data" :key="datos.id_importacion">
            <vs-td v-if="datos.cod_importacion">{{datos.cod_importacion}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.nombre_porveedor">{{datos.nombre_porveedor}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.fech_importacion">{{datos.fech_importacion}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.estado=='Liquidado'"><div style="color:green;">Liquidado</div></vs-td>
            <vs-td v-else><div style="color:red;">Inicial</div></vs-td>
            <vs-td v-if="datos.estado=='Inicial'">{{datos.totales | currency}}</vs-td>
            <vs-td v-else>{{datos.total_importacion |currency}}</vs-td>
            <vs-td class="whitespace-no-wrap">
               <vx-tooltip text="Ver Liquidacion" style="display: inline-flex;">
              <feather-icon
                v-if="editarrol"
                icon="EyeIcon"
                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                @click="editar(datos.id_importacion)"
              />
              </vx-tooltip>
              <feather-icon
                v-if="editarrol"
                icon="PrinterIcon"
                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                @click="reporte_liquidacion(datos.id_importacion)"
              />
              <feather-icon icon="SlidersIcon" class="cursor-pointer" v-if="datos.contabilidad!==null && datos.estado=='Liquidado'"  svgClasses="w-5 h-5 fill-current text-success"  @click="Contabilidad(datos.id_importacion)"/>
              <feather-icon icon="SlidersIcon" class="cursor-pointer" v-else-if="datos.contabilidad==null && datos.estado=='Liquidado'"  svgClasses="w-5 h-5 fill-current text-primary"  @click="Contabilidad(datos.id_importacion)"/>
              <feather-icon icon="CheckIcon"  v-if="datos.contabilidad!==null && datos.estado=='Liquidado'" svgClasses="w-5 h-5"/>
              <!--<feather-icon
                v-if="eliminarrol && datos.estado=='Inicial'"
                icon="SendIcon"
                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                class="ml-2"
                @click="liquidar(datos.total_importacion,datos.id_importacion,datos.totales)"
              />-->
              <!--parseFloat(datos.total_importacion)+parseFloat(datos.totales),-->
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
    <!------Modal Cuenta Importacion----->
    <vs-popup
            :class="'peque2'"
            title="Cuenta de Importacion"
            :active.sync="modal_cta_import"
        >
        <vx-card>
             <div class="vx-row">
                 <div class="vx-col md:w-full w-full mb-6" id="ag-grid-demo">
                     <div class="flex flex-wrap justify-between items-center mb-3">
                                    <!-- ITEMS PER PAGE -->
                                    <div
                                        class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"
                                    ></div>
                                    <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
                                        <vs-input
                                            class="mb-4 md:mb-0 mr-4"
                                            v-model="buscar2"
                                            @keyup="
                                                listarCtas(1, buscar2)
                                            "
                                            v-bind:placeholder="i18nbuscar2"
                                        />
                                        <div>
                                            <vs-button
                                                class="btnx"
                                                type="filled"
                                                divider
                                                @click="agregarCta()"
                                                >Agregar Nuevo</vs-button
                                            >
                                        </div>
                                    </div>
                                    
                    </div>
                    <vs-table stripe :data="ctas_importacion">
                                        <template slot="thead">
                                            <vs-th>Código</vs-th>
                                            <vs-th>Nombre</vs-th>
                                            <vs-th>Opciones</vs-th>
                                        </template>
                                        <template slot-scope="{ data }">
                                            <vs-tr
                                            :key="datos.id_cuenta_importacion"
                                            v-for="datos in data"
                                            >
                                                <vs-td
                                                    v-if="datos.cod_cuenta"
                                                    >{{
                                                        datos.cod_cuenta
                                                    }}</vs-td
                                                >
                                                <vs-td v-else>-</vs-td>
                                                <vs-td
                                                    v-if="datos.nombre_cuenta"
                                                    >{{
                                                        datos.nombre_cuenta
                                                    }}</vs-td
                                                >
                                                <vs-td v-else>-</vs-td>
                                                <vs-td class="whitespace-no-wrap">
                                                    <feather-icon
                                                    icon="EditIcon"
                                                    class="cursor-pointer"
                                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                                    @click.stop="verCta(datos.id_cuenta_importacion)"
                                                />
                                                <feather-icon
                                                    icon="TrashIcon"
                                                    svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                                    class="ml-2 cursor-pointer"
                                                    @click.stop="eliminarCta(datos.id_cuenta_importacion)"
                                                />
                                                </vs-td>
                                            </vs-tr>
                                        </template>
                                    </vs-table>
                 </div>
             </div>
             <vs-popup
                    :class="'peque3'"
                    title="Eliminar Cuenta"
                    :active.sync="modal_eliminar_cta"
                >
                 <p>Desea eliminar Este reguistro</p>
                 <div class="vx-col w-full">
                   <br>
                 <vs-button color="warning" type="filled" @click="acceptAlertCta(idrecupera_cta)">BORRAR</vs-button>
                 </div>
             </vs-popup>
             <vs-popup
                    :class="'peque2'"
                    title="Agregar Cuenta"
                    :active.sync="modal_agregar_cta"
                >
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                      <div class="vx-col sm:w-1/3 w-full mb-6">
                            <label for="" class="vs-input--label">Cuenta contable:</label>
                            <vx-input-group>
                                <vs-input class="w-full" v-model="cod_cta_contable" :value="id_cta_contable" disabled/>
                                <template slot="append">
                                    <div class="append-text btn-addon">
                                        <vs-button color="primary" @click="popupActive = true">Buscar</vs-button>
                                    </div>
                                </template>
                            </vx-input-group>
                        </div>
                        <div class="vx-col sm:w-2/3 w-full mb-6">
                                <label for="" class="vs-input--label">Nombre Cuenta:</label>
                                <vs-input class="w-full" v-model="cta_contable" :value="id_cta_contable" disabled/>
                        </div>
                    </div>
                    <br>
                    <div class="vx-col w-full">
                      <vs-button color="success" type="filled" @click="guardarCta()" v-if="!idrecupera_cta">GUARDAR</vs-button>
                      <vs-button color="success" type="filled" @click="editarCta()" v-else>GUARDAR</vs-button>
                      <vs-button color="warning" type="filled" @click="vaciarCta()">BORRAR</vs-button>
                      <vs-button color="danger"  type="filled" @click="cancelarCta()">CANCELAR</vs-button>
                    </div>
                </div>
                <vs-popup title="Plan Cuentas" :active.sync="popupActive">
                    <div class="con-exemple-prompt">
                        <vs-input class="mb-4 md:mb-0 mr-4 w-full" v-model="listarpc" @keyup="listar3(1, listarpc, criterio3, cantidadp3)" v-bind:placeholder="i18nbuscar3"/>
                        <vs-table stripe v-model="cuentaarray3" @selected="handleSelected3" :data="contenido3">
                            <template slot="thead">
                                <vs-th>No.Cuenta</vs-th>
                                <vs-th>Tipo Cuenta</vs-th>
                            </template>
                            <template slot-scope="{ data }">
                                <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                                    <vs-td :data="data[indextr].codcta">{{data[indextr].codcta}}</vs-td>
                                    <vs-td :data="data[indextr].nomcta">{{data[indextr].nomcta}}</vs-td>
                                </vs-tr>
                            </template>
                        </vs-table>
                    </div>
                </vs-popup>
            </vs-popup>
        </vx-card>
    </vs-popup>
    <vs-popup title="Asiento Contable" :class="'peque4'" :active.sync="modalAsiento">
      <div class="vx-row">
          <div class="vx-col sm:w-1/6 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Número:"
                            :disabled="true"
                            v-model="codigo"

                        />
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6" hidden>
                        <label class="vs-input--label">Proyecto:</label>
                        <vx-input-group>
                            <vs-input
                                class="w-full"
                                v-model="nombre_proyecto"
                                disabled
                            />
                            
                        </vx-input-group>
              
          </div>
          <div class="vx-col sm:w-1/6 w-full mb-6">
                          <label class="vs-input--label">Fecha:</label>
                          <vx-input-group>
                              <vs-input
                                  class="w-full"
                                  v-model="fecha_rol"
                                  disabled
                              />
                              
                          </vx-input-group>
                
          </div>
        </div>
        <div class="vx-row">
          <div class="vx-col sm:w-1/3 w-full mb-6">
                        <label class="vs-input--label">Razon Social:</label>
                        <vx-input-group>
                            <vs-input
                                class="w-full"
                                v-model="razon_social"
                                disabled
                            />
                            
                        </vx-input-group>
              
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
                          <label class="vs-input--label">Tipo Identificacion:</label>
                          <vx-input-group>
                              <vs-input
                                  class="w-full"
                                  v-model="tipo_identificacion"
                                  disabled
                              />
                              
                          </vx-input-group>
                
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
                          <label class="vs-input--label">Identificacion:</label>
                          <vx-input-group>
                              <vs-input
                                  class="w-full"
                                  v-model="ruc_empresa"
                                  disabled
                              />
                              
                          </vx-input-group>
                
          </div>
          <div class="vx-col sm:w-4/11 w-full mb-6">
                          <label class="vs-input--label">Concepto:</label>
                          <vx-input-group>
                              <vs-input
                                  class="w-full"
                                  v-model="concepto"
                                  disabled
                              />
                              
                          </vx-input-group>
                
          </div>
        </div>
        <h4 style="color: #636363; display:flex; align-items: center;">
            <span>Detalle</span>
        </h4>
        <div class="vx-row">
            <div class="vx-col xs:w-10/12 sm:w-11/12">
                <div class="vx-row">
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <label class="vs-input--label valoresc">Cuenta Contable</label>
                    </div>
                    <div class="vx-col sm:w-2/12 w-full mb-6">
                        <label class="vs-input--label valoresc">Proyecto</label>
                    </div>
                    <div class="vx-col sm:w-2/12 w-full mb-6">
                        <label class="vs-input--label valoresc">Debe</label>
                    </div>
                    <div class="vx-col sm:w-2/12 w-full mb-6">
                        <label class="vs-input--label valoresc">Haber</label>
                    </div>
                </div>
            </div>
        </div>
        {{cambioDecimales}}
        {{sumar_iguales}}
        <!--{{igualar}}-->
        <div
            id="one-row"
            class="vx-row"
            v-for="(data, index1) in bodegas"
            v-bind:key="index1"
        >
          
            <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                      <div class="vx-col sm:w-1/3 w-full mb-6">
                            <vx-input-group>
                                <!-- prettier-ignore -->
                                <vs-input
                                    class="w-full"
                                    v-model="data.nombre_cuenta"
                                    disabled
                                />
                    
                            </vx-input-group>
                      </div>
                      <div class="vx-col sm:w-1/6 w-full mb-6">
                        <vs-input
                                    class="w-full"
                                    v-model="data.descripcion"
                                    disabled
                                />
                        </div>
                        <!--DEBE-->
                        <!-- prettier-ignore -->
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <vs-input
                                class="w-full valores"
                                v-model="data.debe"
                                disabled
                            />
                        </div>
                        <!--HABER-->
                        <!-- prettier-ignore -->
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <vs-input
                                class="w-full"
                                v-model="data.haber"
                                disabled

                            />
                        </div>
                        <!--<div class="vx-col sm:w-1/12 w-full mb-2" v-if="data.debe>0 && data.bansel!==null">
                            <vs-button
                                type="filled"
                                style="height: 1.5em;padding: 0px;width: 1.5em; margin:1px 1px;"
                                color="success"
                                @click="agregarcampoConciliacion(index,'cliente')"
                                >C</vs-button
                            >
                        </div>-->
                    </div>
            </div>
          
        </div>
        
        <div
            id="two-row"
            class="vx-row"
            v-for="(data,index) in cuentas"
            :key="data.id_detalle"
        >
        <div class="vx-col xs:w-10/12 sm:w-11/12">
                <div class="vx-row">
                    <!--CUENTA CONTABLE-->
                    <div class="vx-col sm:w-1/3 w-full mb-6" v-if="data.haber>0">
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="data.nombre_cuenta"
                                disabled
                            />
                
                        </vx-input-group>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-6" v-if="data.haber>0">
                    <vs-input
                                class="w-full"
                                v-model="data.descripcion"
                                disabled
                            />
                    </div>
                    <!--DEBE-->
                    <!-- prettier-ignore -->
                    <div class="vx-col sm:w-2/12 w-full mb-6" v-if="data.haber>0">
                        <vs-input
                            class="w-full valores"
                            v-model="data.debe"
                            disabled
                        />
                    </div>
                    <!--HABER-->
                    <!-- prettier-ignore -->
                    <div class="vx-col sm:w-2/12 w-full mb-6" v-if="data.haber>0">
                        <vs-input
                            class="w-full"
                            v-model="data.haber"
                            disabled

                        />
                    </div>
                    <div class="vx-col sm:w-1/12 w-full mb-2" v-if="data.debe>0 && data.bansel!==null">
                        <vs-button
                            type="filled"
                            style="height: 1.5em;padding: 0px;width: 1.5em; margin:1px 1px;"
                            color="success"
                            @click="agregarcampoConciliacion(index,'forma_pago')"
                            >C</vs-button
                        >
                    </div>
                    
                    
                </div>
                
            </div>
            
        </div>
        <div class="vx-row">
            <div class="vx-col xs:w-10/12 sm:w-11/12">
                <div class="vx-row">
                  
                    <div class="vx-col sm:w-1/2 w-full mb-6">
                        
                    </div>

                    <div class="vx-col sm:w-2/12 w-full mb-6">
                        <label class="vs-input--label center">Total</label>
                    </div>
                    <div class="vx-col sm:w-2/12 w-full mb-6">
                        <label class="vs-input--label center">Total</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="vx-row">
            <div class="vx-col xs:w-10/12 sm:w-11/12">
                <div class="vx-row">
                  
                    <div class="vx-col sm:w-1/2 w-full mb-6">
                        
                    </div>
                    
                    {{suma_debe}}
                    <div class="vx-col sm:w-2/12 w-full mb-6">
                        <vs-input
                                class="w-full"
                                v-model="total_debe"
                                disabled
                            />
                    </div>
                    {{suma_haber}}
                    <div class="vx-col sm:w-2/12 w-full mb-6">
                        <vs-input
                                class="w-full"
                                v-model="total_haber"
                                disabled
                            />
                    </div>
                </div>
            </div>
        </div>
        {{Diferencia}}
        <div class="vx-row">
            <div class="vx-col xs:w-10/12 sm:w-11/12">
                <div class="vx-row">
                  
                    <div class="vx-col sm:w-1/2 w-full mb-6">
                        
                    </div>

                    <div class="vx-col sm:w-2/12 w-full mb-6" v-if="diferencia_debe>0">
                        <label class="vs-input--label center">Diferencia</label>
                    </div>
                    <div class="vx-col sm:w-2/12 w-full mb-6" v-if="diferencia_haber>0">
                        <label class="vs-input--label center">Diferencia</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="vx-row">
            <div class="vx-col xs:w-10/12 sm:w-11/12">
                <div class="vx-row">
                  
                    <div class="vx-col sm:w-1/2 w-full mb-6">
                        
                    </div>
                    
                    
                    <div class="vx-col sm:w-2/12 w-full mb-6" v-if="diferencia_debe>0">
                        <vs-input
                                class="w-full"
                                v-model="diferencia_debe"
                                disabled
                            />
                    </div>
                    
                    <div class="vx-col sm:w-2/12 w-full mb-6" v-if="diferencia_haber>0">
                        <vs-input
                                class="w-full"
                                v-model="diferencia_haber"
                                disabled
                            />
                    </div>
                </div>
            </div>
        </div>
        <div v-if="contabilizado!==null">
          <h5> Este asiento ya ha sido registrado</h5>
        </div>
        <div v-else>
            <vs-button
                    color="success"
                    type="filled"
                    :disabled="disabled_asiento"
                    @click="crearasiento(id_factura)" 
                    >GUARDAR</vs-button
                >
        </div>
        
        <vs-popup title="Conciliacion" :active.sync="modal_conciliacion">
                      <div
                            class="vx-row"

                        >
                            <div class="vx-col sm:w-1/4 w-full mb-6" >
                            <vs-input
                                    label="Fecha Pago"
                                    class="w-full"
                                    v-model="fecha_pago"
                                    disabled
                                />
                            </div>
                            <div class="vx-col sm:w-1/3 w-full mb-6">
                                <vs-input
                                    label="Forma Pago"
                                    class="w-full"
                                    v-model="nombre_pago"
                                    disabled
                                />
                            </div>
                            <div class="vx-col sm:w-1/4 w-full mb-6">
                                <vs-input
                                    label="No Documento"
                                    class="w-full"
                                    v-model="nro_documento"
                                    disabled
                                />
                            </div>
                        </div>
                        
                </vs-popup>
    </vs-popup>
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
      productos: [],
      //campos cuenta_importacion
      modal_cta_import:false,
      buscar2:"",
      ctas_importacion:[],
      i18nbuscar2: this.$t("i18nbuscar"),
      i18nbuscar3: this.$t("i18nbuscar"),
      modal_agregar_cta:false,
      popupActive:false,
      listarpc:"",
      cantidadp3: 50,
      criterio3: "codcta",
      contenido3:[],
      cuentaarray3:[],
      id_cta_contable:"",
      cta_contable:"",
      cod_cta_contable:"",
      idrecupera_cta:null,
      errorctas:0,
      errorcta_contable:[],
      modal_eliminar_cta:false,
      disabled_asiento:false,
      //variables Contabilizar
      modalAsiento:false,
      nombre_proyecto:"",
      fecha_rol:"",
      ruc_empresa:"",
      razon_social:"",
      concepto:"",
      codigo:"",
      bodegas:[],
      cuentas:[],
      proveedores_liq:[],
      pagos_con_plc:[],
      pagos_anticipo:[],
      creditos:[],
      retencion_iva:[],
      retencion_renta:[],
      pagos:[],
      total_debe:"",
      total_haber:"",
      id_factura:"",
      id_proyecto:"",
      tipo_identificacion:"",
      contabilizado:null,
      modal_conciliacion:false,
      indextipoarreglo:"",
      nombre_pago:"",
      id_pago:"",
      fecha_pago:"",
      nro_documento:"",
      diferencia_debe:0,
      diferencia_haber:0,
      id_forma_pago:"",
      estado_asiento:"",
      //datos
      descripcion_factura:"",
            valorfactura1:"",
            descripcion_factura2:"",
            valorfactura2:"",
            descripcion_factura3:"",
            valorfactura3:"",
            descripcion_factura4:"",
            valorfactura4:"",
            descripcion_factura5:"",
            valorfactura5:"",
            descripcion_factura6:"",
            valorfactura6:"",
            descripcion_factura7:"",
            valorfactura7:"",
            descripcion_factura8:"",
            valorfactura8:"",
            descripcion_factura9:"",
            valorfactura9:"",
            descripcion_factura10:"",
            valorfactura10:"",
            descripcion_factura11:"",
            valorfactura11:"",
            descripcion_factura12:"",
            valorfactura12:"",
            descripcion_factura13:"",
            valorfactura13:"",
            descripcion_factura14:"",
            valorfactura14:"",
            descripcion_factura15:"",
            valorfactura15:"",
            descripcion_factura16:"",
            valorfactura16:"",
            descripcion_factura17:"",
            valorfactura17:"",
            descripcion_factura18:"",
            valorfactura18:"",
            descripcion_factura19:"",
            valorfactura19:"",
            descripcion_factura20:"",
            valorfactura20:"",
            campo:"",
            estado:"",
            codigo_import:"",
            fech_liquidacion:"",
            tipo_calculo:"",
            //fitrar tabla
            filterstable: {
                contrue: [],
                filtertab: false,
                filt_asientos: true,
                filt_noasientos: true,
                filt_inicial: true,
                filt_liquid: true,

            }
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
    },
    
    Diferencia(){
            if(this.total_debe>this.total_haber){
                this.diferencia_debe=this.total_haber-this.total_debe;
                console.log(this.total_debe);
            }
            if(this.total_debe<this.total_haber){
                this.diferencia_haber=this.total_debe-this.total_haber;
                console.log(this.total_haber);
            }
            console.log(this.total_debe-this.total_haber);
    },
    suma_debe(){
        var total=0;
        if(this.bodegas.length>0){
            this.bodegas.forEach(el => {
                if(el.debe !==null){
                    total+=parseFloat(el.debe);
                }
                
            });
        }
        
        this.total_debe=total.toFixed(2);
    },
    suma_haber(){
            var total=0;
            if(this.cuentas.length>0){
                this.cuentas.forEach(el => {
                    if(el.haber !==null){
                        total+=parseFloat(el.haber);
                    }
                    
                });
            }
            
            
            this.total_haber=total.toFixed(2);
    },
    // igualar(){
    //   var cambio=0;
    //   var cambio_2=0;
    //   if(this.productos_asiento.length>0){
    //     if(this.productos_asiento[this.productos_asiento.length-1].debe_talves!==this.total_debe){
    //       cambio=this.total_debe-this.productos_asiento[this.productos_asiento.length-1].debe_talves;
    //       this.productos_asiento[this.productos_asiento.length-1].debe=this.productos_asiento[this.productos_asiento.length-1].debe-cambio;
    //     }
    //   }
    //   if(this.iva_asiento.length>0){
    //     if(this.iva_asiento[this.iva_asiento.length-1].haber_tal!==this.total_haber){
    //       cambio_2=this.total_haber-this.iva_asiento[this.iva_asiento.length-1].haber_tal;
    //       this.iva_asiento[this.iva_asiento.length-1].haber=this.iva_asiento[this.iva_asiento.length-1].haber-cambio_2;
    //     }
    //   }
    // },
    sumar_iguales(){
            if(this.cuentas.length>0){
                this.cuentas = this.cuentas.reduce((acumulador, valorActual) => {
                const elementoYaExiste = acumulador.find(elemento => elemento.id_proyecto === valorActual.id_proyecto  && elemento.id_plan_cuentas === valorActual.id_plan_cuentas );
                if (elementoYaExiste) {
                    return acumulador.map((elemento) => {
                    if (elemento.id_proyecto === valorActual.id_proyecto && elemento.id_plan_cuentas === valorActual.id_plan_cuentas) {
                        return {
                        ...elemento,
                        haber: parseFloat(elemento.haber) + parseFloat(valorActual.haber)
                        }
                    }

                    return elemento;
                    });
                }

                return [...acumulador, valorActual];
                }, []);
            }
            if(this.bodegas.length>0){
              
                  this.bodegas = this.bodegas.reduce((acumulador, valorActual) => {
                  const elementoYaExiste = acumulador.find(elemento => elemento.id_proyecto === valorActual.id_proyecto  && elemento.id_plan_cuentas === valorActual.id_plan_cuentas );
                  if (elementoYaExiste) {
                      return acumulador.map((elemento) => {
                      if (elemento.id_proyecto === valorActual.id_proyecto && elemento.id_plan_cuentas === valorActual.id_plan_cuentas) {
                          return {
                          ...elemento,
                          debe: parseFloat(elemento.debe) + parseFloat(valorActual.debe)
                          }
                      }

                      return elemento;
                      });
                  }

                  return [...acumulador, valorActual];
                  }, []);
              
            }
    },
    cambioDecimales(){
            if(this.bodegas.length>0){
                this.bodegas.forEach(el => {
                    if(el.debe !==null){
                        el.debe=parseFloat(el.debe).toFixed(2);
                    }
                    
                });
            }
            if(this.cuentas.length>0){
                this.cuentas.forEach(el => {
                    if(el.haber !==null){
                        el.haber=parseFloat(el.haber).toFixed(2);
                    }
                    
                });
            }
            console.log(this.cuentas.length+"xhola");
    },
  },
 methods: {
        filtertabla() {
            var contvar = this.filterstable.contrue;
            var filt = "";
            if (this.filterstable.filtertab == true) {
                if (this.filterstable.filt_asientos == false) {
                    contvar = contvar.filter(
                        contvar => contvar.contabilidad == null
                    );
                }
                if (this.filterstable.filt_noasientos == false) {
                    contvar = contvar.filter(
                        contvar => contvar.contabilidad == 1
                    );
                }
                if (this.filterstable.filt_inicial == true) {
                    filt =
                        filt +
                        'contvar.estado == "Inicial"';
                }
                if (this.filterstable.filt_liquid == true) {
                    if (filt.length > 0) {
                        filt = filt + " || ";
                    }
                    filt =
                        filt +
                        'contvar.estado == "Liquidado"';
                }

                contvar = contvar.filter(contvar => eval(filt));
            } else {
                this.filterstable.filt_asientos = true;
                this.filterstable.filt_noasientos = true;
                this.filterstable.filt_inicial = true;
                this.filterstable.filt_liquid = true;

            }
            this.contenido = contvar;
        },
    editar(id) {
      this.$router.push(`/importacion/liquidacion/${id}/editar`);
    },
    liquidar(liq,id,fac){
      axios.put("/api/liquidar",{
                id:id,
                total:liq,
                totalfac:fac
            }).then(res =>{
                console.log("LIquidado");
                this.listar(1, this.buscar, this.cantidadp);
            }).catch(err =>{
              console.log(err);
            });
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
        "/api/liquid/" +this.usuario.id_punto_emision+
        "?page=" +
        page +
        "&buscar=" +
        buscar;
      axios.get(url).then(res => {
        var respuesta = res.data;
        this.contenido = respuesta.recupera;
        this.filterstable.contrue = respuesta.recupera;
        this.filtertabla();
      });
    },
    //metodos cuenta_importacion
        abrirCtaImport(){
            this.modal_cta_import=true;
            this.listarCtas(1,this.buscar2);
            this.listar3(1, this.listarpc, this.criterio3, this.cantidadp3);
        },
        listarCtas(page,buscar2){
            let me = this;
            var url =
                "/api/cuenta_importacion/" +
                this.usuario.id_empresa +
                "?page=" +
                page +
                "&buscar=" +
                buscar2;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.ctas_importacion = respuesta.recupera;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        agregarCta(){
          this.modal_agregar_cta=true;
        },
        reporte_liquidacion($id){
            var url="/api/creacion_liquidacion_import_pdf/"+$id;
            axios.get(url)
            .then(resp=>{
                window.open(url,"_blank");
            })
            .catch(err=>{

            });
        },
        listar3(page3, buscar3, criterio3, cantidadp3) {
            let me = this;
            var url =
                "/api/notacredito/listar_cuenta_contable" 
                // +
                // this.usuario.id_empresa +
                // "?page=" +
                // page3 +
                // "&buscar=" +
                // buscar3 +
                // "&criterio=" +
                // criterio3 +
                // "&cantidadp=" +
                // cantidadp3
                ;
            axios.get(url,{
                params:{
                    empresa:this.usuario.id_empresa,
                    buscar:buscar3
                }
            })
                .then(({data})=> {
                    var respuesta = data;
                    me.contenido3 = respuesta;
                })
                .catch(function(error) {
                });
        },
        handleSelected3(tr) {
          
            (this.cta_contable = `${tr.nomcta}`),
            (this.id_cta_contable =`${tr.id_plan_cuentas}`),
            (this.cod_cta_contable =`${tr.codcta}`),
             (this.popupActive = false);
        },
        guardarCta() {
          if(this.validar()){
            return;
          }
            axios.post("/api/agregarcuenta_importacion", {
                    cod_cuenta:this.cod_cta_contable,
                    nombre_cuenta:this.cta_contable,
                    id_plan_cuentas:this.id_cta_contable,
                    ucrea:this.usuario.id,
                    id_empresa:this.usuario.id_empresa
                })
                .then(res => {
                        this.popupActive = false;
                        this.modal_agregar_cta=false;
                        this.$vs.notify({
                            title: "Registro Guardado",
                            text: "Registro Guardado exitosamente",
                            color: "success"
                        });
                        this.listarCtas(1,this.buscar2);
                        this.vaciarCta();
                    
                })
                .catch(err => {});
        },
        editarCta() {
            if(this.validar()){
                    return;
                }
            axios.put("/api/actualizarcuenta_importacion", {
                    id: this.idrecupera_cta,
                    cod_cuenta:this.cod_cta_contable,
                    nombre_cuenta:this.cta_contable,
                    id_plan_cuentas:this.id_cta_contable,
                    umodifica:this.usuario.id,
                    id_empresa:this.usuario.id_empresa
                })
                .then(res => {
                    this.popupActive = false;
                    this.modal_agregar_cta=false;
                    this.$vs.notify({
                        title: "Registro Editado",
                        text: "Registro Editado exitosamente",
                        color: "success"
                    });
                    this.listarCtas(1,this.buscar2);
                    this.vaciarCta();
                })
                .catch(err => {
                    this.$vs.notify({
                        title: "Error al Editar",
                        text: "Revise sus datos antes de editar",
                        color: "danger"
                    });
                });
        },
        vaciarCta() {
                (this.cod_cta_contable = ""),
                (this.cta_contable = ""),
                (this.id_cta_contable = ""),
                (this.idrecupera_cta = null);
        },
      //cancela lo que se esta hacien en impuestos
        cancelarCta() {
                (this.popupActive = false),
                (this.cod_cta_contable = ""),
                (this.cta_contable = ""),
                (this.id_cta_contable = ""),
                (this.idrecupera_cta = null);
        },
        validar(){
          this.errorctas=0;
          this.errorcta_contable=[];
          if(!this.id_cta_contable){
            this.errorcta_contable.push("Campo Obligatorio");
            this.errorctas=1;
            console.log("descrip_retencion");
          }
          return this.errorctas;
        },
        verCta($id){
          let me = this;
            var url =
                "/api/abrircuenta_importacion/" +
                $id;
                
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.id_cta_contable=respuesta.recupera[0].id_plan_cuentas;
                    me.cta_contable=respuesta.recupera[0].nombre_cuenta;
                    me.cod_cta_contable=respuesta.recupera[0].cod_cuenta;
                    me.idrecupera_cta=respuesta.recupera[0].id_cuenta_importacion;
                    console.log(respuesta.recupera[0].id_plan_cuentas+":id_plan_ctas");
                    me.modal_agregar_cta=true;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        eliminarCta(cd) {
            this.modal_eliminar_cta=true;
            this.idrecupera_cta=cd;
        },
      //eliminar un reguistro
        acceptAlertCta(parameters) {
            axios.delete("/api/eliminarcuenta_importacion/" + parameters)
            .then(res =>{
                this.$vs.notify({
                color: "success",
                title: "Reguistro Eliminado  ",
                text: "El reguistro selecionado fue eliminado con exito"
                });
                this.modal_eliminar_cta=false;
                this.idrecupera_cta=null;
                this.listarCtas(1,this.buscar2);
            }).catch(err => {
                this.$vs.notify({
                color: "danger",
                title: "Error al eliminar",
                text: "Ha ocurrido un error al momento de eliminar reguistro"
                });
            });
            
        },
    //methodos asientos
    Contabilidad(id){
      axios.get('/api/liquidvercontabilidad/' + id+
                        "?id_empresa=" +
                        this.usuario.id_empresa).then( ({data}) => {
                          var nombre_provs=[]
                          for(const prod of data.proveedores){
                            nombre_provs.push(
                              prod.nombre
                            );
                          }
                          var nros_factura=nombre_provs.join();
                          this.fecha_rol=moment(data.importacion.fech_importacion).format("Y-MM-DD");
                          var fecha=moment(this.fecha_rol).format("MMMM YYYY");
                          this.razon_social=nros_factura;
                          this.ruc_empresa=data.proveedores[0].identificacion;
                          if(data.proveedores[0].tipo_identificacion=="Cédula de Identidad"){
                              this.tipo_identificacion="Cedula";
                          }else{
                              this.tipo_identificacion=data.proveedores[0].tipo_identificacion;
                          }
                          if(data.importacion.contabilidad==1){
                              this.codigo="IP-"+data.codigo_anterior;
                              this.contabilizado=data.importacion.contabilidad;
                          }else{
                              this.codigo="IP-"+data.codigo;
                              this.contabilizado=null;
                          }
                          this.concepto="Importacion "+data.importacion.cod_importacion;
                          this.modalAsiento=true;
                          this.bodegas=data.bodegas;
                          this.cuentas=data.cuenta;
                          this.id_factura=id;
                          this.id_proyecto=data.proyecto;
                          this.estado_asiento=data.asiento_permitido;

                          // var referencia=data.ctas_pagar.referencia.split(';');
                          // var conteo_ref=referencia.length/4;
                          // var salto = 0; 
                          // var factura=[];
                          // for(var f=0; f<conteo_ref; f++){ 
                          //   factura.push(referencia[0+salto]);
                          //   salto+=4; 
                          // }
                          // this.modalAsiento=true;
                          // this.productos_asiento=data.proveedor;
                          // this.iva_asiento=data.forma_pago;
                          // if(this.iva_asiento.length>0){
                          //   this.fecha_pago=moment(this.iva_asiento[0].fecha_pago).format("Y-MM-DD");
                          //   this.nombre_pago=this.iva_asiento[0].nombre_pago;
                          //   this.nro_documento=this.iva_asiento[0].nro_tarjeta;
                          //   this.id_forma_pago=this.iva_asiento[0].id_forma_pagos;
                          //   
                          // }
                          
                          //console.log(data.ctas_pagar.contabilidad+"HOLA");
                        }).catch( error => {
                            console.log(error);
                        });
    },
    listarFactura(){
            //if (this.$route.params.id) {
              this.idrecupera = this.$route.params.id;
              var url = "/api/traerfactliquid/"+this.idrecupera;
              axios
                .get(url)
                .then(res => {
                  let data = res.data[0];
                  this.cabecera = res.data.recupera1;
                  //this.codigo_proveedor = "PR0" + data.id_proveedor;
                this.total_factura=res.data;
                // console.log("hola"+res.data[0].nombre);
                this.descripcion_factura=res.data[0].nombre;
                //var cantidad=this.totalcantidad;
                //console.log("hola"+cantidad);
                this.valorfactura1=parseFloat(res.data[0].total);
                this.descripcion_factura2=res.data[1].nombre;
                this.valorfactura2=parseFloat(res.data[1].total); 
                this.descripcion_factura3=res.data[2].nombre;
                this.valorfactura3=parseFloat(res.data[2].total);
                this.descripcion_factura4=res.data[3].nombre;
                this.valorfactura4=parseFloat(res.data[3].total);
                this.descripcion_factura5=res.data[4].nombre;
                this.valorfactura5=parseFloat(res.data[4].total);
                this.descripcion_factura6=res.data[5].nombre;
                this.valorfactura6=parseFloat(res.data[5].total);
                this.descripcion_factura7=res.data[6].nombre;
                this.valorfactura7=parseFloat(res.data[6].total);
                this.descripcion_factura8=res.data[7].nombre;
                this.valorfactura8=parseFloat(res.data[7].total);
                this.descripcion_factura9=res.data[8].nombre;
                this.valorfactura9=parseFloat(res.data[8].total);
                this.descripcion_factura10=res.data[9].nombre;
                this.valorfactura10=parseFloat(res.data[9].total);
                this.descripcion_factura11=res.data[10].nombre;
                this.valorfactura11=parseFloat(res.data[10].total);
                this.descripcion_factura12=res.data[11].nombre;
                this.valorfactura12=parseFloat(res.data[11].total);
                this.descripcion_factura13=res.data[12].nombre;
                this.valorfactura13=parseFloat(res.data[12].total);
                this.descripcion_factura14=res.data[13].nombre;
                this.valorfactura14=parseFloat(res.data[13].total);
                this.descripcion_factura15=res.data[14].nombre;
                this.valorfactura15=parseFloat(res.data[14].total);
                this.descripcion_factura16=res.data[15].nombre;
                this.valorfactura16=parseFloat(res.data[15].total);
                this.descripcion_factura17=res.data[16].nombre;
                this.valorfactura17=parseFloat(res.data[16].total);
                this.descripcion_factura18=res.data[17].nombre;
                this.valorfactura18=parseFloat(res.data[17].total);
                this.descripcion_factura19=res.data[18].nombre;
                this.valorfactura19=parseFloat(res.data[18].total);
                this.descripcion_factura20=res.data[19].nombre;
                this.valorfactura20=parseFloat(res.data[19].total);
                })
                .catch(err => {
                  //console.log(err);
                });
            // } else {
            //   this.idrecupera = null;
            // }
        },
    crearasiento(id){
            this.disabled_asiento=true;
            var total=0;
            total=this.total_debe-this.total_haber;
            console.log(total+":total diferencia");
            if(this.validacion_asiento()){
              this.$vs.notify({
                    color: "danger",
                    title: "Verifique los datos antes de guardar el Asiento",
                });
                this.disabled_asiento=false;
                return;
            }
            
            var total=0;
            total=this.total_debe-this.total_haber;
            if(total!==0){
                this.$vs.notify({
                    color: "danger",
                    title: "No esta cuadrado el Asiento",
                });
                this.disabled_asiento=false;
                return;
            }
            if(this.estado_asiento=='no'){
                this.$vs.notify({
                    color: "danger",
                    title: "Por Cierre del Periodo no se puede guardar este Asiento con esta fecha",
                });
                this.disabled_asiento=false;
                return;
            }
            var codigo_asiento = this.codigo.substr(3,this.codigo.length);
            var fecha_hoy=new Date();
            axios.post("/api/liquid/agregar/asiento",{
                cod_rol:id,
                numero:codigo_asiento,
                codigo:this.codigo,
                fecha:this.fecha_rol+" "+fecha_hoy.getHours()+":"+fecha_hoy.getMinutes()+":"+fecha_hoy.getSeconds(),
                razon_social:this.razon_social,
                tipo_identificacion:this.tipo_identificacion,
                ruc_ci:this.ruc_empresa,
                concepto:this.concepto,
                ucrea:this.usuario.id,
                id_proyecto:this.id_proyecto
            }).then(res=>{
                this.crearasientoDetalle(res.data);
            }).catch(err=>{
                this.$vs.notify({
                    color: "danger",
                    title: "Asiento No Agregado",
                    text: err
                });
                this.disabled_asiento=false;
            });
    },
    crearasientoDetalle(id){
        axios.post("/api/liquid/agregar/asiento_detalle",{
            bodegas:this.bodegas,
            cuentas:this.cuentas,
            // pagos_sin_plc:this.pagos_sin_plc,
            // pagos_con_plc:this.pagos_con_plc,
            // pagos_anticipo:this.pagos_anticipo,
            // creditos:this.creditos,
            // retencion_iva:this.retencion_iva,
            // retencion_renta:this.retencion_renta,
            ucrea:this.usuario.id,
            id_asientos:id,
        }).then(res=>{
            this.$vs.notify({
            color: "success",
            title: "Asiento Agregado",
            text: "Asiento agregado con exito"
            });
            

            this.listar(1, this.buscar, this.cantidadp);
            this.modalAsiento=false;
            this.estado_asiento="";
            this.disabled_asiento=false;
        }).catch(err=>{
            this.$vs.notify({
            color: "danger",
            title: "Asiento No Agregado",
            text: err
            });
            this.disabled_asiento=false;
        });
    },
    validacion_asiento(){
          var error=0;
          if(this.bodegas.length<=0){
            error++
          }else{
            this.bodegas.forEach(el=>{
                    
                    if(el.id_plan_cuentas==null){
                            error++;
                    }
                    
                    if(el.id_proyecto==null){
                            error++;
                    }
                });
          }
          if(this.cuentas.length<=0){
            error++
          }else{
            this.cuentas.forEach(el=>{
              if(el.id_plan_cuentas==null){
                error++;
              }
              if(el.id_proyecto==null){
                error++;
              }
            });
          }
          return error;
          
    },
  },
  mounted() {
    this.listar(1, this.buscar, this.criterio, this.cantidadp);
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
.peque .vs-popup {
    width: 600px !important;
}
.peque2 .vs-popup {
    width: 900px !important;
}
.peque3 .vs-popup {
    width: 400px !important;
}
.peque4 .vs-popup {
    width: 1080px !important;
}
</style>
