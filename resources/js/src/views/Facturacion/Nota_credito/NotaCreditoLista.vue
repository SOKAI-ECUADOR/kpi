<template>
  <div id="ag-grid-demo">
    <vx-card>
      <div class="flex flex-wrap justify-between items-center mb-3">
        <!-- ITEMS PER PAGE -->
        <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-left">
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
                                >Autorizados</label
                            >
                            <vs-switch
                                color="success"
                                @input="filtertabla()"
                                v-model="filterstable.filt_autoris"
                            />
                        </div>
                        <div
                            class="flex flex-wrap justify-between items-center mb-1 mr-5"
                        >
                            <label class="vs-input--label mr-1"
                                >No-autorizados</label
                            >
                            <vs-switch
                                color="success"
                                @input="filtertabla()"
                                v-model="filterstable.filt_noautoris"
                            />
                        </div>
                        <div
                            class="flex flex-wrap justify-between items-center mb-1 mr-5"
                        >
                            <label class="vs-input--label mr-1">Anulados</label>
                            <vs-switch
                                color="success"
                                @input="filtertabla()"
                                v-model="filterstable.filt_anul"
                            />
                        </div>
                    </template>  
        </div>
        <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
          <vs-input class="mb-4 md:mb-0 mr-4" v-model="buscar" @keyup="listar(1,buscar)" v-bind:placeholder="i18nbuscar"/>
            <div
                        class="flex flex-wrap justify-between items-center mr-1 ml-1"
                        v-if="crearrol"
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
          <div class="dropdown-button-container" v-if="crearrol">
            <vs-button class="btnx" type="filled" to="/facturacion/nota-credito/agregar">Agregar</vs-button>
            <vs-dropdown>
              <vs-button class="btn-drop" type="filled" icon="expand_more"></vs-button>
              <vs-dropdown-menu style="width:13em;">
                <vs-dropdown-item class="text-center">Generar reporte</vs-dropdown-item>
              </vs-dropdown-menu>
            </vs-dropdown>
          </div>
        </div>
      </div>
      <vs-table stripe max-items="25" pagination :data="contenido">
        <template slot="thead">
          <vs-th>No.</vs-th>
          <vs-th>Cliente</vs-th>
          <vs-th>Fecha de Emisión</vs-th>
          <vs-th>Fecha de Autorización</vs-th>
          <vs-th class="text-center">Valor Total</vs-th>
          <vs-th class="text-center">Estado</vs-th>
          <vs-th class="text-center">Opciones</vs-th>
        </template>
        <template slot-scope="{data}">
          <vs-tr v-for="(datos,index) in data" :key="index">
            <vs-td v-if="datos.clave_acceso">{{(datos.clave_acceso).substring(24,27)}}-{{(datos.clave_acceso).substring(27,30)}}-{{(datos.clave_acceso).substring(30,39)}}</vs-td><vs-td v-else>-</vs-td>
            <vs-td v-if="datos.nombre">{{datos.nombre}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.fecha_emision">{{datos.fecha_emision | fecha}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.fecha_autorizacion && datos.respuesta=='Enviado'">{{datos.fecha_autorizacion | fechayhora}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.valor_total" class="text-center">{{datos.valor_total | currency}}</vs-td>
            <vs-td v-else class="text-center">-</vs-td>
            <vs-td v-if="datos.estadof == 0" style="color: rgb(255, 0, 0);font-weight: bold;" class="text-center">ANULADO</vs-td>
            <vs-td v-else-if="datos.respuesta=='Enviado'" style="color: #28c76f;font-weight: bold;" class="text-center">AUTORIZADO</vs-td>
            <vs-td v-else style="color: rgb(206, 124, 59);;font-weight: bold;" class="text-center">
                <template v-if="datos.mensaje_sri">
                    <vx-tooltip :title="datos.mensaje_sri" :text="datos.informacion_sri" position="left" class="pointer">
                        NO AUTORIZADO
                    </vx-tooltip>
                </template>
                <template v-else>
                    <vx-tooltip title="Error en el servicio del SRI" text="El SRI se encuentra fuera de servicio, intente mas tarde" position="left" class="pointer">
                        NO AUTORIZADO
                    </vx-tooltip>
                </template>
            </vs-td>
            <vs-td class="whitespace-no-wrap text-center estilosacciones">
              <vs-dropdown vs-custom-content vs-trigger-click>
                  <vs-button class="btn-drop" type="filled" icon="expand_more">Acciones</vs-button>
                  <vs-dropdown-menu style="width:13em;">
                      <vs-dropdown-item class="text-center" @click.stop="enviocorreo(datos)" v-if="datos.respuesta=='Enviado' && editarrol && datos.estadof != 0"><feather-icon icon="MailIcon" svgClasses="w-5 h-5"></feather-icon> Enviar al correo</vs-dropdown-item>
                      <vs-dropdown-item class="text-center" @click.stop="facturaenvio(datos)" v-if="datos.respuesta!='Enviado' && editarrol && datos.estadof != 0"><feather-icon icon="SendIcon" svgClasses="w-5 h-5"></feather-icon> Reenviar</vs-dropdown-item>

                      <vs-dropdown-item divider class="text-center" @click.stop="enviocorreootro(datos)" v-if="datos.respuesta == 'Enviado' && editarrol && datos.estadof != 0"><feather-icon icon="MailIcon" svgClasses="w-5 h-5"></feather-icon> Enviar a un correo</vs-dropdown-item>
                      <vs-dropdown-item divider class="text-center" @click.stop="descargarpdf(datos)" v-if="editarrol && datos.estadof != 0"><feather-icon icon="DownloadIcon" svgClasses="w-5 h-5"></feather-icon> Descargar PDF</vs-dropdown-item>
                      <vs-dropdown-item divider class="text-center" @click.stop="descargarxml(datos)" v-if="editarrol && datos.estadof != 0"><feather-icon icon="DownloadIcon" svgClasses="w-5 h-5"></feather-icon> Descargar XML</vs-dropdown-item>
                      <vs-dropdown-item divider class="text-center" @click.stop="descargarpdfyxml(datos)" v-if="editarrol && datos.estadof != 0"><feather-icon icon="DownloadIcon" svgClasses="w-5 h-5"></feather-icon> Desc. PDF y XML</vs-dropdown-item>
                      <vs-dropdown-item v-if="datos.estadof != 0" divider class="text-center" @click.stop="descargarxmlSRI(datos,datos.respuesta)"><feather-icon icon="DownloadIcon" svgClasses="w-5 h-5" ></feather-icon> XML SRI</vs-dropdown-item>
                    
                      <vs-dropdown-item divider class="text-center" @click="ver(datos.id_nota_credito)" v-if="datos.respuesta == 'Enviado' && editarrol && datos.estadof != 0"><feather-icon icon="EyeIcon" svgClasses="w-5 h-5"></feather-icon> Visualizar</vs-dropdown-item>
                      <vs-dropdown-item class="text-center" @click="ver(datos.id_nota_credito)" v-if="datos.estadof == 0"><feather-icon icon="EyeIcon" svgClasses="w-5 h-5"></feather-icon> Visualizar</vs-dropdown-item>
                      <vs-dropdown-item divider class="text-center" @click="editar(datos.id_nota_credito)" v-if="datos.respuesta!='Enviado' && editarrol && datos.estadof != 0 && datos.contabilidad==null"><feather-icon icon="EditIcon" svgClasses="w-5 h-5"></feather-icon> Editar</vs-dropdown-item>
                      <vs-dropdown-item divider class="text-center" @click="eliminar(datos.id_nota_credito, datos.autorizacionfactura)" v-if="eliminarrol && datos.estadof != 0 && datos.contabilidad==null"><feather-icon icon="TrashIcon" svgClasses="w-5 h-5"></feather-icon> Cancelar</vs-dropdown-item>
                  </vs-dropdown-menu>
              </vs-dropdown>
              <feather-icon icon="SlidersIcon" class="cursor-pointer" v-if="datos.estadof>0 &&  datos.contabilidad==null" svgClasses="w-5 h-5 fill-current text-primary"  @click="Contabilidad(datos.id_nota_credito)"/>
              <feather-icon icon="SlidersIcon" class="cursor-pointer" v-else-if="datos.estadof>0 && datos.contabilidad!==null" svgClasses="w-5 h-5 fill-current text-success"  @click="Contabilidad(datos.id_nota_credito)"/>
                  <feather-icon icon="CheckIcon"  v-if="datos.estadof > 0 && datos.contabilidad!==null" svgClasses="w-5 h-5"/>
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
    </vx-card>
    <vs-popup title="Destinatario de Correo" :active.sync="popupcorreo">
        <div class="vx-col sm:w-full w-full mb-6 relative">
            <vs-input class="w-full" label="Nombre:" v-model="nombrecliente"/>
            <div v-show="errorenvio">
                <div v-for="err in errornombrecliente" :key="err" v-text="err" class="text-danger"></div>
            </div>
        </div>
        <div class="vx-col sm:full w-full mb-6 relative">
            <vs-input class="w-full" label="Dirección de Correo Electrónico:" v-model="correocliente"/>
            <div v-show="errorenvio">
                <div v-for="err in errorcorreocliente" :key="err" v-text="err" class="text-danger"></div>
            </div>
        </div>
        <div class="vx-col w-full mt-5">
            <vs-button color="success" type="border" @click="enviocorreootros()">Enviar</vs-button>
            <vs-button color="danger" type="border" @click="cancelarenvio()">Cancelar</vs-button>
        </div>
    </vs-popup>
    <vs-popup title="Asiento Contable" :class="'peque3'" :active.sync="modalAsiento">
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
        <div
            id="one-row"
            class="vx-row"
            v-for="(add, index1) in productos_asiento"
            v-bind:key="index1"
        >
        <div class="vx-col xs:w-10/12 sm:w-11/12">
                <div class="vx-row">
                    <!--CUENTA CONTABLE-->
                    <div class="vx-col sm:w-1/3 w-full mb-6" v-if="productos_asiento[index1].sector=='producto' && productos_asiento[index1].iva=='doce'">
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="productos_asiento[index1].nombre_cuenta_12"
                                disabled
                            />

                        </vx-input-group>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6" v-if="productos_asiento[index1].sector=='producto' && productos_asiento[index1].iva=='cero'">
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="productos_asiento[index1].nombre_cuenta_0"
                                disabled
                            />

                        </vx-input-group>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6" v-if="productos_asiento[index1].sector=='servicio'">
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="productos_asiento[index1].nombre_cuenta"
                                disabled
                            />

                        </vx-input-group>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-6">
                    <vs-input
                                class="w-full"
                                v-model="productos_asiento[index1].descripcion"
                                disabled
                            />
                    </div>
                    <!--DEBE-->
                    <!-- prettier-ignore -->
                    <div class="vx-col sm:w-2/12 w-full mb-6">
                        <vs-input
                            class="w-full valores"
                            v-model="productos_asiento[index1].debe"
                            disabled
                        />
                    </div>
                    <!--HABER-->
                    <!-- prettier-ignore -->
                    <div class="vx-col sm:w-2/12 w-full mb-6">
                        <vs-input
                            class="w-full"
                            v-model="productos_asiento[index1].haber"
                            disabled

                        />
                    </div>

                    <!--FECHA BANCO-->
                    <!-- prettier-ignore -->
                    <!--<div
                        class="vx-col sm:w-2/12 w-full mb-6"
                        v-if="listaAsientoscontables[index].detalle.fecha && listaAsientoscontables[index].detalle.haber"
                    >
                        <flat-pickr
                            class="w-full"
                            :config="configdateTimePicker"
                            v-model="listaAsientoscontables[index].detalle.fecha"
                            placeholder="Elegir Fecha de pago"
                        />
                    </div>-->
                </div>
            </div>
        </div>
        <div
            id="two-row"
            class="vx-row"
            v-for="data in iva_asiento"
            :key="data.id_detalle"
        >
        <div class="vx-col xs:w-10/12 sm:w-11/12">
                <div class="vx-row">
                    <!--CUENTA CONTABLE-->
                    <div class="vx-col sm:w-1/3 w-full mb-6" v-if="data.debe>0">
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="data.nombre_cuenta"
                                disabled
                            />

                        </vx-input-group>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-6" v-if="data.debe>0">
                    <vs-input
                                class="w-full"
                                v-model="data.descripcion"
                                disabled
                            />
                    </div>
                    <!--DEBE-->
                    <!-- prettier-ignore -->
                    <div class="vx-col sm:w-2/12 w-full mb-6" v-if="data.debe>0">
                        <vs-input
                            class="w-full valores"
                            v-model="data.debe"
                            disabled
                        />
                    </div>
                    <!--HABER-->
                    <!-- prettier-ignore -->
                    <div class="vx-col sm:w-2/12 w-full mb-6" v-if="data.debe>0">
                        <vs-input
                            class="w-full"
                            v-model="data.haber"
                            disabled

                        />
                    </div>


                </div>
            </div>
        </div>
        <div
            id="tree-row"
            class="vx-row"
            v-for="data in creditos"
            :key="data.id_detalle_nota_credito"
        >
        <div class="vx-col xs:w-10/12 sm:w-11/12">
                <div class="vx-row">
                    <!--CUENTA CONTABLE-->
                    <div class="vx-col sm:w-1/3 w-full mb-6" v-if="data.exist_plc_cl=='si'">

                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="data.nombre_cuenta_cliente"
                                disabled
                            />


                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6" v-else>

                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="data.nombre_cuenta_grupo"
                                disabled
                            />


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


                </div>
            </div>
        </div>
        <div
            id="tree-row"
            class="vx-row"
            v-for="(data, index1) in pagos_nota"
            :key="index1"
        >
        <div class="vx-col xs:w-10/12 sm:w-11/12">
                <div class="vx-row">
                    <!--CUENTA CONTABLE-->
                    <div class="vx-col sm:w-1/3 w-full mb-6" v-if="data.exist_plc_cl=='si'">
                        
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="data.nombre_cuenta_cliente"
                                disabled
                            />
                
                       
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6" v-else>
                        
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="data.nombre_cuenta_grupo"
                                disabled
                            />
                
                       
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
                    <div class="vx-col sm:w-1/12 w-full mb-2" v-if="data.haber>0 && data.exist_plc_cl=='si' && data.bansel_cliente!==null">
                        <vs-button
                            type="filled"
                            style="height: 1.5em;padding: 0px;width: 1.5em; margin:1px 1px;"
                            color="success"
                            @click="agregarcampoConciliacion(index1,'cliente')"
                            >C</vs-button
                        >
                    </div>
                    <div class="vx-col sm:w-1/12 w-full mb-2" v-else-if="data.haber>0 && data.exist_plc_cl=='no' && data.bansel_grupo!==null">
                        <vs-button
                            type="filled"
                            style="height: 1.5em;padding: 0px;width: 1.5em; margin:1px 1px;"
                            color="success"
                            @click="agregarcampoConciliacion(index1,'cliente')"
                            >C</vs-button
                        >
                    </div>
                    
                    
                </div>
            </div>
        </div>
        <!--
        <div
            id="one-row"
            class="vx-row"
            v-for="(data, index1) in productos_asiento"
            v-bind:key="index1"
        >

            <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                      <div class="vx-col sm:w-1/3 w-full mb-6" v-if="data.exist_plan_cuenta_cliente=='si'">
                      <vx-input-group>

                                <vs-input
                                    class="w-full"
                                    v-model="data.nombre_cuenta_cliente"
                                    disabled
                                />

                            </vx-input-group>
                      </div>
                      <div class="vx-col sm:w-1/3 w-full mb-6" v-else>
                            <vx-input-group>

                                <vs-input
                                    class="w-full"
                                    v-model="data.nombre_cuenta_grupo"
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

                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <vs-input
                                class="w-full valores"
                                v-model="data.debe"
                                disabled
                            />
                        </div>

                        <div class="vx-col sm:w-2/12 w-full mb-6">
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
                            @click="agregarcampoConciliacion(index,'cliente')"
                            >C</vs-button
                        >
                    </div>
                    </div>
            </div>

        </div>

        <div
            id="two-row"
            class="vx-row"
            v-for="(data,index) in iva_asiento"
            :key="data.id_detalle"
        >
        <div class="vx-col xs:w-10/12 sm:w-11/12">
                <div class="vx-row">

                    <div class="vx-col sm:w-1/3 w-full mb-6" v-if="data.debe>0">
                        <vx-input-group>

                            <vs-input
                                class="w-full"
                                v-model="data.nombre_cuenta"
                                disabled
                            />

                        </vx-input-group>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-6" v-if="data.debe>0">
                    <vs-input
                                class="w-full"
                                v-model="data.descripcion"
                                disabled
                            />
                    </div>

                    <div class="vx-col sm:w-2/12 w-full mb-6" v-if="data.debe>0">
                        <vs-input
                            class="w-full valores"
                            v-model="data.debe"
                            disabled
                        />
                    </div>

                    <div class="vx-col sm:w-2/12 w-full mb-6" v-if="data.debe>0">
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

        </div>-->
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
import { AgGridVue } from "ag-grid-vue";
import moment from "moment";
moment.locale("es");
import $ from "jquery";
const axios = require("axios");
const {rutasEmpresa:{DATA_EMPRESA}} = require("../../../../../../config-routes/config");
import script_comprobantes from '../../../../factura.js';
export default {
  components: {
    AgGridVue
  },
  filters: {
    fecha(data) {
      return moment(data).format("LL");
    },
    fechayhora(data) {
      return moment(data).format("LLL");
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
        res = this.$store.state.Roles[11].crear;
      }
      return res;
    },
    editarrol(){
      var res = 0;
      if(this.usuario.id_rol==1){
        res=1
      }else{
        res = this.$store.state.Roles[11].editar;
      }
      return res;
    },
    eliminarrol(){
      var res = 0;
      if(this.usuario.id_rol==1){
        res=1
      }else{
        res = this.$store.state.Roles[11].eliminar;
      }
      return res;
    },
    //compted contabilizar
    cambioDecimales(){
        if(this.productos_asiento.length>0){
                this.productos_asiento.forEach(el => {
                    if(el.debe !==null){
                        el.debe=parseFloat(el.debe).toFixed(2);
                    }

                });
            }
        if(this.iva_asiento.length>0){
                this.iva_asiento.forEach(el => {
                    if(el.debe !==null){
                        el.debe=parseFloat(el.debe).toFixed(2);
                    }

                });
        }
        if(this.creditos.length>0){
                this.creditos.forEach(el => {
                    if(el.haber !==null){
                        el.haber=parseFloat(el.haber).toFixed(2);
                    }

                });
        }
        if(this.pagos_nota.length>0){
                this.pagos_nota.forEach(el => {
                    if(el.haber !==null){
                        el.haber=parseFloat(el.haber).toFixed(2);
                    }
                    
                });
        }
    },
    sumar_iguales(){
        var array={};
            var hash = {};
            var hash2 = {};
            if(this.productos_asiento.length>0){
                this.productos_asiento = this.productos_asiento.reduce((acumulador, valorActual) => {
                    if(valorActual.sector==="producto" && valorActual.iva==="doce"){
                        const elementoYaExiste = acumulador.find(elemento => elemento.id_proyecto === valorActual.id_proyecto  && elemento.id_plan_cuentas_iva_12 === valorActual.id_plan_cuentas_iva_12 );
                        if (elementoYaExiste) {
                            return acumulador.map((elemento) => {
                            if (elemento.id_proyecto === valorActual.id_proyecto && elemento.id_plan_cuentas_iva_12 === valorActual.id_plan_cuentas_iva_12) {
                                return {
                                ...elemento,
                                debe: Number(elemento.debe) + Number(valorActual.debe)
                                }
                            }

                            return elemento;
                            });
                        }

                        return [...acumulador, valorActual];
                    }else{
                        if(valorActual.sector==="producto" && valorActual.iva==="cero"){
                            const elementoYaExiste = acumulador.find(elemento => elemento.id_proyecto === valorActual.id_proyecto  && elemento.id_plan_cuentas_iva_0 === valorActual.id_plan_cuentas_iva_0 );
                            if (elementoYaExiste) {
                                return acumulador.map((elemento) => {
                                if (elemento.id_proyecto === valorActual.id_proyecto && elemento.id_plan_cuentas_iva_0 === valorActual.id_plan_cuentas_iva_0) {
                                    return {
                                    ...elemento,
                                    debe: Number(elemento.debe) + Number(valorActual.debe)
                                    }
                                }

                                return elemento;
                                });
                            }

                            return [...acumulador, valorActual];
                        }else{
                            const elementoYaExiste = acumulador.find(elemento => elemento.id_proyecto === valorActual.id_proyecto  && elemento.id_plan_cuentas_servicio === valorActual.id_plan_cuentas_servicio );
                            if (elementoYaExiste) {
                                return acumulador.map((elemento) => {
                                if (elemento.id_proyecto === valorActual.id_proyecto && elemento.id_plan_cuentas_servicio === valorActual.id_plan_cuentas_servicio) {
                                    return {
                                    ...elemento,
                                    debe: Number(elemento.debe) + Number(valorActual.debe)
                                    }
                                }

                                return elemento;
                                });
                            }

                            return [...acumulador, valorActual];
                        }
                    }
                }, []);
            }
            if(this.iva_asiento.length>0){
                this.iva_asiento = this.iva_asiento.reduce((acumulador, valorActual) => {
                const elementoYaExiste = acumulador.find(elemento => elemento.id_proyecto === valorActual.id_proyecto  && elemento.id_plan_cuentas === valorActual.id_plan_cuentas );
                if (elementoYaExiste) {
                    return acumulador.map((elemento) => {
                    if (elemento.id_proyecto === valorActual.id_proyecto && elemento.id_plan_cuentas === valorActual.id_plan_cuentas) {
                        return {
                        ...elemento,
                        debe: Number(elemento.debe) + Number(valorActual.debe)
                        }
                    }

                    return elemento;
                    });
                }

                return [...acumulador, valorActual];
                }, []);
            }
            if(this.creditos.length>0){
                this.creditos = this.creditos.reduce((acumulador, valorActual) => {
                const elementoYaExiste = acumulador.find(elemento => elemento.id_proyecto === valorActual.id_proyecto  && elemento.id_plan_cuentas === valorActual.id_plan_cuentas );
                if (elementoYaExiste) {
                    return acumulador.map((elemento) => {
                    if (elemento.id_proyecto === valorActual.id_proyecto && elemento.id_plan_cuentas === valorActual.id_plan_cuentas) {
                        return {
                        ...elemento,
                        haber: Number(elemento.haber) + Number(valorActual.haber)
                        }
                    }

                    return elemento;
                    });
                }

                return [...acumulador, valorActual];
                }, []);
            }
            if(this.pagos_nota.length>0){
                this.pagos_nota = this.pagos_nota.reduce((acumulador, valorActual) => {
                    if(valorActual.exist_plc_cl==="si"){
                        const elementoYaExiste = acumulador.find(elemento => elemento.id_proyecto === valorActual.id_proyecto  && elemento.id_plan_cuentas_cliente === valorActual.id_plan_cuentas_cliente );
                        if (elementoYaExiste) {
                            return acumulador.map((elemento) => {
                            if (elemento.id_proyecto === valorActual.id_proyecto && elemento.id_plan_cuentas_cliente === valorActual.id_plan_cuentas_cliente) {
                                return {
                                ...elemento,
                                haber: Number(elemento.haber) + Number(valorActual.haber)
                                }
                            }

                            return elemento;
                            });
                        }

                        return [...acumulador, valorActual];
                    }else{
                            const elementoYaExiste = acumulador.find(elemento => elemento.id_proyecto === valorActual.id_proyecto  && elemento.id_plan_cuenta_grupo === valorActual.id_plan_cuenta_grupo );
                            if (elementoYaExiste) {
                                return acumulador.map((elemento) => {
                                if (elemento.id_proyecto === valorActual.id_proyecto && elemento.id_plan_cuenta_grupo === valorActual.id_plan_cuenta_grupo) {
                                    return {
                                    ...elemento,
                                    haber: Number(elemento.haber) + Number(valorActual.haber)
                                    }
                                }

                                return elemento;
                                });
                            }

                            return [...acumulador, valorActual];
                        
                    }
                }, []);
            }
    },
    suma_debe(){
            var total=0;
            if(this.productos_asiento.length>0){
                this.productos_asiento.forEach(el => {
                    if(el.debe !==null){
                        total+=parseFloat(el.debe);
                    }

                });
            }
            if(this.iva_asiento.length>0){
                this.iva_asiento.forEach(el => {
                    if(el.debe !==null){
                        total+=parseFloat(el.debe);
                    }

                });
            }
            
            this.total_debe=total.toFixed(2);
    },
    suma_haber(){
            var total=0;
            if(this.creditos.length>0){
                this.creditos.forEach(el => {
                    if(el.haber !==null){
                        total+=parseFloat(el.haber);
                    }

                });
            }
            if(this.pagos_nota.length>0){
                this.pagos_nota.forEach(el => {
                    if(el.haber !==null){
                        total+=parseFloat(el.haber);
                    }
                    
                });
            }
            this.total_haber=total.toFixed(2);
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
      offset: 3,
      //buscador
      buscar: "",
      criterio: "secuencial",
      //otros valores
      gridApi: null,
      contenido: [],
      //lenguaje
      i18nbuscar: this.$t("i18nbuscar"),
      claveacceso:null,
      tipofactura:"Notacredito",
      recueidfact:null,

      datosg:{},
      popupcorreo:false,
      nombrecliente: "",
      correocliente: "",
      errorenvio:0,
      errornombrecliente:[],
      errorcorreocliente:[],
      classempr:null,
      //variables Contabilizar
            modalAsiento:false,
            disabled_asiento:false,
            nombre_proyecto:"",
            total_nota_crd:0,
            fecha_rol:"",
            ruc_empresa:"",
            razon_social:"",
            concepto:"",
            codigo:"",
            productos_asiento:[],
            iva_asiento:[],
            pagos_sin_plc:[],
            pagos_con_plc:[],
            pagos_anticipo:[],
            pagos_nota:[],
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
            ice:[],
            estadocorreo: 1,
            estado_asiento:"",
            //fitrar tabla
            filterstable: {
                contrue: [],
                filtertab: false,
                filt_asientos: true,
                filt_noasientos: true,
                filt_autoris: true,
                filt_noautoris: true,
                filt_anul: true
            }
    };
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
                if (this.filterstable.filt_autoris == true) {
                    filt =
                        filt +
                        '(contvar.respuesta == "Enviado" && contvar.estadof == 1)';
                }
                if (this.filterstable.filt_noautoris == true) {
                    if (filt.length > 0) {
                        filt = filt + " || ";
                    }
                    filt =
                        filt +
                        '(contvar.respuesta != "Enviado" && contvar.estadof == 1)';
                }
                if (this.filterstable.filt_anul == true) {
                    if (filt.length > 0) {
                        filt = filt + " || ";
                    }
                    filt = filt + "contvar.estadof == 0";
                }
                contvar = contvar.filter(contvar => eval(filt));
            } else {
                this.filterstable.filt_asientos = true;
                this.filterstable.filt_noasientos = true;
                this.filterstable.filt_autoris = true;
                this.filterstable.filt_noautoris = true;
                this.filterstable.filt_anul = true;
            }
            this.contenido = contvar;
        },
    listar(page, buscar) {
      var url = "/api/nota_credito";
      var datos = {
        page: page,
        buscar: buscar,
        datos: this.usuario
      };
      axios.post(url, datos).then(res => {
        var respuesta = res.data;
        this.contenido = respuesta.recupera;
        this.filterstable.contrue = respuesta.recupera;
        this.filtertabla();
      });
    },
    updateSearchQuery(val) {
      this.gridApi.setQuickFilter(val);
    },
    editar(id) {
      this.$router.push(`/facturacion/nota-credito/${id}/editar`);
    },
    ver(id) {
      this.$router.push(`/facturacion/nota-credito/${id}/ver`);
    },
    facturaenvio(dat) {
      this.$vs.notify({
        time:8000,
        title: "Validando Nota de Crédito al SRI",
        text: "Por favor Espere...",
        color: "primary",
      });
      this.recueidfact = dat.id_nota_credito;
      this.claveacceso = dat.clave_acceso;
      var url = "/api/factura/xml_nota_credito";
      axios.post(url, dat).then(res => {
        var password = res.data.recupera.pass_firma;
        var firma = DATA_EMPRESA + this.usuario.id_empresa + "/firma/" + res.data.recupera.firma;
        var factura = DATA_EMPRESA + this.usuario.id_empresa + "/comprobantes/notacredito/" +this.claveacceso +".xml";
        var tipo = "nota_credito_venta";
        var carpeta = DATA_EMPRESA + this.usuario.id_empresa + "/comprobantes/notacredito/";
        var fecha_actual = moment(dat.fecha_autorizacion).format('LL');
        this.crearfacturacion(firma, password, factura, tipo, this.usuario, this.recueidfact, carpeta, fecha_actual, dat.valor_total, dat.logo, dat.nombre_empresa);
      });
    },
    eliminar(cd, doc) {
      this.classempr = doc;
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: `¿Desea Cancelar este comprobante?`,
        text: `Este comprobante sera cancelada del sistema`,
        acceptText: "Aceptar",
        cancelText: "Cancelar",
        accept: this.acceptAlert,
        parameters: cd
      });
    },
    acceptAlert(parameters) {
      axios.delete("/api/eliminarfactcre/" + parameters + "/" + this.classempr);
      this.$vs.notify({
        color: "warning",
        title: "Comprobante Cancelado",
        text: "El comprobante selecionado fue cancelado con exito"
      });
      this.listar(1, this.buscar);
    },
    //Facturación
    enviado(){
      this.listar(1, this.buscar);
    },
    async crearfacturacion(firma, password, factura, tipo, usuario, id_factura, carpeta, fecha, valor, logo, nombre_empresa){
        try {
            let {data:comprobante} = await script_comprobantes.obtener_comprobante_firmado.getAll({ factura:factura, id_factura:id_factura, tipo:tipo });
            let {resultado:contenido} = await script_comprobantes.lectura_firma.getAll({ firma:firma, id_factura:   id_factura, tipo:tipo });
            let {data:certificado} = await script_comprobantes.firmar_comprobante.getAll({ contenido:contenido[0], password:password, comprobante:comprobante, id_factura:id_factura, tipo:tipo });
            let {data:quefirma} = await script_comprobantes.verificar_firma.getAll({ comprobante:comprobante, mensaje:certificado, tipo:tipo, id_factura:id_factura, carpeta:carpeta });
            let {data:validado} = await script_comprobantes.validar_comprobante.getAll({ comprobante:comprobante, tipo:tipo, id_factura:id_factura, carpeta:carpeta, id_empresa:usuario.id_empresa });
            let {data:recibida} = await script_comprobantes.autorizar_comprobante.getAll({ comprobante:comprobante, validado:validado, usuario:usuario, tipo:tipo, id_factura:id_factura, carpeta:carpeta, fecha:fecha, valor:valor, logo:logo, nombre_empresa:nombre_empresa });
            let {data:registrado} = await script_comprobantes.autorizado_comprobante.getAll({ recibida:recibida, tipo:tipo, id_factura:id_factura });
            this.$vs.notify({
                time: 8000,
                title: "Nota de Crédito Enviada",
                text:"La Nota de Crédito se generó exitosamente",
                color: "success"
            });
            this.enviado();
        } catch(error) {
            this.$vs.notify({
                time: 20000,
                title: error.mensaje,
                text: error.informacion,
                color: "danger"
            });
            this.enviado();
        }
    },
    // descargarpdf(datos){
    //     window.open('/'+datos.id_empresa+'/vistapdf/nota_credito/'+datos.clave_acceso, '_top');
    // },
    descargarpdf(datos,destinatario=null,email=null){
      //var index_ctas=$index+1;
                    axios({
                        url: "/api/nota_credito_fact/pdf",
                        method: "GET",
                        responseType: "arraybuffer",
                        params:{
                        id_nota_credito:datos.id_nota_credito,
                        id_empresa:this.usuario.id_empresa,
                        id_user:this.usuario.id,
                        //index:index_ctas,
                        destinatario:destinatario,
                        email:email
                        }
                    
                    }).then(resp=>{
                        console.log("ejecutado empleado");
                        //this.contenidopr=res.data;
                        console.log("resp:"+resp);
                        console.log("resp data:"+resp.data);
                        console.log(JSON.stringify(resp.data)+"proyecto");
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
                        this.$vs.notify({
                            title: "PDF Generado",
                            text: "Su PDF esta siendo descargado exitosamente!",
                            color: "success"
                        });
                        /*this.modal_proyecto=false;
                        this.dep_rol="";
                        this.fechrolprov="";
                        this.datos_proyecto.selectedproyecto=[];*/
                    }).catch(err=>{
                        console.log("ERROR"+err);
                    });
    },
    descargarxml(datos){
        window.open('/'+datos.id_empresa+'/vistaxml/nota_credito/'+datos.clave_acceso, '_top');
    },
    descargarxmlSRI(datos, tipo) {
            // try {
            //     window.open('/'+datos.id_empresa+'/vistaxml/factura_venta/respuesta_sri/'+datos.clave_acceso, '_top');
            // } catch (error) {
            //         this.$vs.notify({
            //             color: "danger",
            //             title: "No existe el archivo",

            //         });
            // }
            var url =
                "/" +
                datos.id_empresa +
                "/vistaxml/nota_credito/respuesta_sri/" +
                datos.clave_acceso;
            try {
                axios
                    .get(url)
                    .then(resp => {
                        window.open(
                            "/" +
                                datos.id_empresa +
                                "/vistaxml/nota_credito/respuesta_sri/" +
                                datos.clave_acceso,
                            "_top"
                        );
                    })
                    .catch(err => {
                        this.$vs.notify({
                            title: "Error de envio",
                            text: "No existe el XML",
                            color: "danger"
                        });
                    });
                // .done(function() {
                //     window.open(
                //         "/" +
                //             datos.id_empresa +
                //             "/vistaxml/factura_venta/respuesta_sri/" +
                //             datos.clave_acceso,
                //         "_top"
                //     );
                // }).fail(function() {
                //         this.$vs.notify({
                //             title: "Error de envio",
                //             text:
                //                 "No existe el XML",
                //             color: "danger"
                //         });
                // });
            } catch (error) {
                this.$vs.notify({
                    title: "Error de envio",
                    text: "No existe el XML",
                    color: "danger"
                });
            }
        },
    descargarpdfyxml(datos){
        window.open('/'+datos.id_empresa+'/vistapdfyxml/nota_credito/'+datos.clave_acceso, '_top');
    },
    enviocorreo(datos){
      this.$vs.notify({
          time: 3000,
          title: "Enviando comprobantes",
          text: "Espere por favor, enviando comprobantes",
          color: "warning"
      });
      var fecha_actual = moment(datos.fecha_autorizacion).format('LL');
      axios.post('/api/nota_credito/enviarcorreo', {
          tipo:'Notacredito',
          nombre:datos.nombre,
          claveAcceso:datos.clave_acceso,
          email:datos.email,
          id_empresa:datos.id_empresa,
          empresas:datos,
          fecha_autorizacion:fecha_actual,
          valor_total:datos.valor_total,
          logo:datos.logo,
          nombre_empresa:datos.nombre_empresa
      }).then( ({data}) => {
          this.$vs.notify({
              time: 5000,
              title: "Documentos enviados",
              text: "Se ha enviado al correo exitosamente",
              color: "success"
          });
      });
    },
    enviocorreootro(datos){
        this.popupcorreo = true;
        this.nombrecliente = "";
        this.correocliente = "";
        this.datosg = datos;
    },
    cancelarenvio(){
        this.popupcorreo = false;
        this.nombrecliente = "";
        this.correocliente = "";
        this.datosg = {};
    },
    enviocorreootros(){
        if(this.validarcorreo()){return;}
        this.popupcorreo = false;
        var fecha_actual = moment(this.datosg.fecha_autorizacion).format('LL');
        this.$vs.notify({
            time: 3000,
            title: "Enviando comprobantes",
            text: "Espere por favor, enviando comprobantes",
            color: "warning"
        });
        axios.post('/api/nota_credito/enviarcorreo', {
            tipo:'Notacredito',
            nombre:this.nombrecliente,
            claveAcceso:this.datosg.clave_acceso,
            email:this.correocliente,
            id_empresa:this.datosg.id_empresa,
            empresas:this.datosg,
            fecha_autorizacion:fecha_actual,
            valor_total:this.datosg.valor_total,
            logo:this.datosg.logo,
            nombre_empresa:this.datosg.nombre_empresa
        }).then( ({data}) => {
            this.$vs.notify({
                time: 5000,
                title: "Documentos enviados",
                text: "Se ha enviado al correo exitosamente",
                color: "success"
            });
        }).catch( err => {
            this.$vs.notify({
                time: 5000,
                title: "Error de envio",
                text: "Intente nuevamente o comuniquese con el administrador",
                color: "success"
            });
        });
    },
    validarcorreo(){
        this.errorenvio = 0;
        this.errornombrecliente = [];
        this.errorcorreocliente = [];

        if(!this.nombrecliente){
            this.errornombrecliente.push("Ingrese un nombre");
            this.errorenvio = 1;
        }
        if(!this.correocliente){
            this.errorcorreocliente.push("Ingrese un correo");
            this.errorenvio = 1;
        }

        return this.errorenvio;
    },
    //methodos Asientos
    Contabilidad(id){
      axios.get('/api/notacredito/verasiento/' + id+
                        "?id_empresa=" +
                        this.usuario.id_empresa).then( ({data}) => {
                          var serie1=data.nota_credito_fact.clave_acceso.substring(24,27);
                          var serie2=data.nota_credito_fact.clave_acceso.substring(27,30);
                          var documento=data.nota_credito_fact.clave_acceso.substring(30,39);
                          this.fecha_rol=moment(data.nota_credito_fact.fecha_emision).format("Y-MM-DD");
                          var fecha=moment(this.fecha_rol).format("MMMM YYYY");
                          this.razon_social=data.nota_credito_fact.nombre;
                          this.ruc_empresa=data.nota_credito_fact.identificacion;
                          if(data.nota_credito_fact.tipo_identificacion=="Cédula de Identidad"){
                              this.tipo_identificacion="Cedula";
                          }else{
                              this.tipo_identificacion=data.nota_credito_fact.tipo_identificacion;
                          }
                          if(data.nota_credito_fact.contabilidad==1){
                              this.codigo="NCF-"+data.codigo_anterior;
                              this.contabilizado=data.nota_credito_fact.contabilidad;
                          }else{
                              this.codigo="NCF-"+data.codigo;
                              this.contabilizado=null;
                          }
                              this.concepto="Credito Facturacion "+serie1+"-"+serie2+"-"+documento+" Cliente: "+this.razon_social;

                              this.id_factura=id;
                              this.id_proyecto=data.proyecto;
                              this.productos_asiento=data.producto_asientos;
                              this.iva_asiento=data.doce_iva_asiento;
                              this.creditos=data.cliente;
                              this.pagos_nota=data.pagos;
                              this.total_nota_crd=data.nota_credito_fact.valor_total;
                              this.IgualarAsiento();
                              //this.modalAsiento=true;
                              this.estado_asiento=data.asiento_permitido;

                          //}
                          // var nombre_provs=[]
                          // for(const prod of data.proveedores){
                          //   nombre_provs.push(
                          //     prod.nombre
                          //   );
                          // }
                          // var nros_factura=nombre_provs.join();

                          // this.concepto="Importacion "+data.importacion.cod_importacion;

                          // this.bodegas=data.bodegas;
                          // this.cuentas=data.cuenta;
                          // this.id_factura=id;
                          // this.id_proyecto=data.proyecto;

                        }).catch( error => {
                            console.log(error);
                        });
    },
    IgualarAsiento(){
        this.IgualarIva()
        .then(value=>{
            return this.IgualarHaber();
            //this.modalAsiento=true;
        })
        .then(value=>{
            this.modalAsiento=true;
        });
    },
    IgualarIva() {
            return new Promise(resolve => {
                var pagos = 0;
                console.log("Entro Iva12");
                var total_diferencia_pago = 0;
                //this.pagos_anticipo.length
                if (this.iva_asiento.length > 1) {
                    this.iva_asiento.forEach(el => {
                        pagos += parseFloat(el.debe);
                    });
                    //console.log("cantidad pagos:"+this.iva_asiento.length+" diferencia pago: "+pagos+" total pago:"+this.iva_asiento[0].total);
                    var n1 = Number(this.iva_asiento[0].total_iva_12);
                    var n2 = Number(pagos);
                    var div = 0;
                    var res = 0;
                    if (n1 !== n2) {
                        res = n1 - n2;
                        div = Number(res);
                    }

                    //console.log(div+" :diferencia pago");
                    var indiceDelMayor = 0;
                    // Recorrer arreglo y ver si no es así
                    // (comenzar desde el 1 porque el 0 ya lo tenemos contemplado arriba)

                    for (var x = 1; x < this.iva_asiento.length; x++) {
                        if (
                            this.iva_asiento[x].debe >
                            this.iva_asiento[indiceDelMayor].debe
                        ) {
                            indiceDelMayor = x;
                        }
                    }

                    var n3 = Number(this.iva_asiento[indiceDelMayor].debe);
                    total_diferencia_pago = n3 + res;
                    this.iva_asiento[
                        indiceDelMayor
                    ].debe = total_diferencia_pago;
                    console.log(
                        "pago:" +
                            this.iva_asiento[indiceDelMayor].haber +
                            " diferencia pago: " +
                            res +
                            " total pago:" +
                            total_diferencia_pago
                    );
                }
                resolve(total_diferencia_pago);
            });
    },
    IgualarHaber(){
        return new Promise(resolve => {
            var total_ig_haber=0;
            if(this.creditos.length>0){
                this.creditos.forEach(el=>{
                    total_ig_haber+=Number(Number(el.haber).toFixed(2));
                });
            }
            if(this.pagos_nota.length>0){
                this.pagos_nota.forEach(el=>{
                    total_ig_haber+=Number(Number(el.haber).toFixed(2));
                });
            }
            if(this.iva){

            }
            var n1=Number(total_ig_haber);
            var n2=Number(this.total_nota_crd);
            var n3=parseFloat(n2).toFixed(2)-parseFloat(n1).toFixed(2);
            console.log("Numero 1:"+n1);
            console.log("Numero 2:"+n2);
            console.log("Numero 3:"+n3);
            if(n3>0 || n3<0){
                if(this.creditos.length>0){
                    this.creditos[0].haber=parseFloat(n3+n1);
                }else{
                if(this.pagos_nota.length>0){
                        this.pagos_nota[0].haber=parseFloat(n3+n1);
                    } 
                }
            }
            resolve(n3);
        });
        
        
    },
    agregarcampoConciliacion(index, tipo) {
            this.modal_conciliacion = true;
            this.indextipoarreglo = index;
            if(this.pagos_nota.length>0){
                    this.fecha_pago = this.pagos_nota[index].fecha_pago;
                    this.nombre_pago = this.pagos_nota[index].nombre_pago;
                    this.nro_documento = this.pagos_nota[
                        index
                    ].numero_transaccion;
            }    
            
    },
    crearasiento(id){
            this.disabled_asiento=true;
            var total=0;
            total=this.total_debe-this.total_haber;
            console.log("total_diferencia:"+total);
            if(this.validacion_asiento()){
              this.$vs.notify({
                    color: "danger",
                    title: "Verifique los datos antes de guardar el Asiento",
                });
                this.disabled_asiento=false;
                return;
            }

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
            var codigo_asiento = this.codigo.substr(4,this.codigo.length);
            var fecha_hoy=new Date();
            axios.post("/api/nota_credito_fact/agregar/asiento",{
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
            axios.post("/api/nota_credito_fact/agregar/asiento_detalle",{
                proyecto:this.nombre_proyecto,
                productos:this.productos_asiento,
                iva_12:this.iva_asiento,
                creditos:this.creditos,
                ucrea:this.usuario.id,
                id_asientos:id,
                pagos:this.pagos_nota
            }).then(res=>{
                this.$vs.notify({
                color: "success",
                title: "Asiento Agregado",
                text: "Asiento agregado con exito"
                });
                this.modalAsiento=false;
                this.id_factura="";
                this.estado_asiento="";
                this.listar(1, this.buscar);
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
            //console.log(this.productos_asiento.length);
            if(this.productos_asiento.length>0){
                this.productos_asiento.forEach(el=>{
                    if(el.sector=="producto" && el.iva=="cero"){
                        if(el.id_plan_cuentas_iva_0==null){
                            error++;
                            console.log("Error producto cero");
                        }
                    }
                    if(el.sector=="producto" && el.iva=="doce"){
                        if(el.id_plan_cuentas_iva_12==null){
                            error++;
                            console.log("Error producto doce");
                        }
                    }
                    if(el.sector=="servicio"){
                        if(el.id_plan_cuentas_servicio==null){
                            error++;
                            console.log("Error producto servicio");
                        }
                    }
                    if(el.id_proyecto==null){
                            error++;
                            console.log("Error producto proyecto");
                        }
                });
            }
            if(this.iva_asiento.length>0){
                this.iva_asiento.forEach(el=>{
                    if(el.id_plan_cuentas==null){
                        error++;
                        console.log("Error iva_asiento plan_cuentas");
                    }
                    if(el.id_proyecto==null){
                            error++;
                            console.log("Error iva_asiento proyecto");
                        }
                });
            }
            if(this.creditos.length>0){
                this.creditos.forEach(el=>{
                    if(el.exist_plc_cl=="si"){
                        if(el.id_plan_cuentas_cliente==null){
                            error++;
                            console.log("Error creditos plan_cuentas cli");
                        }
                    }else{
                        if(el.id_plan_cuenta_grupo==null){
                            error++;
                            console.log("Error creditos plan_cuentas grupo");
                        }
                    }

                    if(el.id_proyecto==null){
                            error++;
                            console.log("Error creditos proyecto");
                        }
                });
            }
            if(this.pagos_nota.length>0){
                this.pagos_nota.forEach(el=>{
                    if(el.exist_plc_cl=="si"){
                        if(el.id_plan_cuentas_cliente==null){
                            error++;
                            console.log("Error pagos_nota plan_cuentas cli");
                        }
                    }else{
                        if(el.id_plan_cuenta_grupo==null){
                            error++;
                            console.log("Error pagos_nota plan_cuentas grupo");
                        }
                    }

                    if(el.id_proyecto==null){
                            error++;
                            console.log("Error pagos_nota proyecto");
                        }
                });
            }
            return error;
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
  .text-center .vs-table-text {
      text-align: center;
  }
  .vs-table-text {
      display: block !important;
  }
  .estilosacciones .vs-button-primary{
      padding: .5rem 1rem!important;
      cursor:pointer;
  }
  .vs-con-dropdown{
      cursor: pointer;
  }
  .feather-icon{
      vertical-align: sub;
  }
  .peque3 .vs-popup {
    width: 1080px !important;
}
</style>
