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
                    </template>  
        </div>
        <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
          <vs-input class="mb-4 md:mb-0 mr-4" v-model="buscar" @keyup="listar(1,buscar)" v-bind:placeholder="i18nbuscar"/>
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
          <div class="dropdown-button-container" v-if="crearrol">
            <vs-button class="btnx" type="filled" to="/compras/nota-debito/agregar">Agregar</vs-button>
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
          <vs-th>Documento</vs-th>
          <vs-th>Proveedor</vs-th>
          <vs-th>Fecha de Emisión</vs-th>
          <vs-th class="text-center">Valor Total</vs-th>
          <vs-th class="text-center">Opciones</vs-th>
        </template>
        <template slot-scope="{data}">
          <vs-tr v-for="(datos,index) in data" :key="index">
            <vs-td v-if="datos.autorizacionfactura">{{datos.numerofacturacompra}}</vs-td>
            <vs-td v-if="datos.nombre_proveedor">{{datos.nombre_proveedor}}</vs-td><vs-td v-else>-</vs-td>
            <vs-td v-if="datos.fecha_emision">{{datos.fecha_emision | fecha}}</vs-td><vs-td v-else>-</vs-td>
            <vs-td v-if="datos.valor_total" class="text-center">{{datos.valor_total | currency}}</vs-td>
            <vs-td class="whitespace-no-wrap text-center estilosacciones">
              <vs-dropdown vs-custom-content vs-trigger-click>
                  <vs-button class="btn-drop" type="filled" icon="expand_more">Acciones</vs-button>
                  <vs-dropdown-menu style="width:13em;">
                      <!--<vs-dropdown-item class="text-center" @click.stop="enviocorreootro(datos)"><feather-icon icon="MailIcon" svgClasses="w-5 h-5"></feather-icon> Enviar a un correo</vs-dropdown-item>
                      <vs-dropdown-item divider class="text-center" @click.stop="descargarpdf(datos)"><feather-icon icon="DownloadIcon" svgClasses="w-5 h-5"></feather-icon> Descargar PDF</vs-dropdown-item>-->
                      <!--<vs-dropdown-item divider class="text-center" @click="ver(datos.id_nota_debito_compra)"><feather-icon icon="EyeIcon" svgClasses="w-5 h-5"></feather-icon> Visualizar</vs-dropdown-item>-->
                      <!--<vs-dropdown-item class="text-center" v-if="datos.contabilidad==null" @click="editar(datos.id_nota_debito_compra)"><feather-icon icon="EditIcon" svgClasses="w-5 h-5"></feather-icon> Editar</vs-dropdown-item>-->
                      <vs-dropdown-item divider class="text-center" v-if="datos.contabilidad==null" @click="eliminar(datos.id_nota_debito_compra, datos.autorizacionfactura)"><feather-icon icon="TrashIcon" svgClasses="w-5 h-5"></feather-icon> Cancelar</vs-dropdown-item>
                  </vs-dropdown-menu>
              </vs-dropdown>
                  <feather-icon icon="SlidersIcon" class="cursor-pointer" v-if="datos.estadof>0 && datos.contabilidad==null" svgClasses="w-5 h-5 fill-current text-primary"  @click="Contabilidad(datos.id_nota_debito_compra)"/>
                  <feather-icon icon="SlidersIcon" class="cursor-pointer" v-if="datos.estadof>0 && datos.contabilidad!==null" svgClasses="w-5 h-5 fill-current text-success"  @click="Contabilidad(datos.id_nota_debito_compra)"/>
                      <feather-icon icon="CheckIcon"  v-if="datos.estadof>0 && datos.contabilidad!==null" svgClasses="w-5 h-5"/>
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
            :key="data.id_plan_cuentas"
        >
        <div class="vx-col xs:w-10/12 sm:w-11/12">
                <div class="vx-row">
                    <!--CUENTA CONTABLE-->
                    <div class="vx-col sm:w-1/3 w-full mb-6" v-if="data.exist_plc_cl=='si'">
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="data.nombre_cuenta_cl"
                                disabled
                            />
                
                        </vx-input-group>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6" v-else>
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
                    
                    
                </div>
            </div>
        </div>
        <div
            id="fig-row"
            class="vx-row"
            v-for="(data,index) in pagos_sin_plc"
            :key="data.id_forma_pagos"
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
                    <div class="vx-col sm:w-2/12 w-full mb-2" v-if="data.haber>0">
                        <vs-input
                            class="w-full"
                            v-model="data.haber"
                            disabled

                        />
                    </div>
                    <div class="vx-col sm:w-1/12 w-full mb-2" v-if="data.haber>0 && data.fecha_pago!==null">
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
        <div
            id="fig2-row"
            class="vx-row"
            v-for="(data,index) in pagos_con_plc"
            :key="data.id_forma_pagos"
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
                    <div class="vx-col sm:w-1/12 w-full mb-6" v-if="data.haber>0 && data.fecha_pago!==null">
                        <vs-button
                            type="filled"
                            style="height: 1.5em;padding: 0px;width: 1.5em; margin:5px 5px;"
                            color="success"
                            @click="agregarcampoConciliacion(index,'plc')"
                            >C</vs-button
                        >
                    </div>
                    
                    
                </div>
            </div>
        </div>
        <!--{{cambioDecimales}}
        {{sumar_iguales}}
        {{igualar}}
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
                 <!----> 
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
    //comp contabiliizar
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
            
            if(this.pagos_sin_plc.length>0){
                this.pagos_sin_plc.forEach(el=>{
                    total+=parseFloat(el.haber);
                });
            }
            if(this.pagos_con_plc.length>0){
                this.pagos_con_plc.forEach(el=>{
                    total+=parseFloat(el.haber);
                });
            }
            if(this.pagos_anticipo.length>0){
                this.pagos_anticipo.forEach(el=>{
                    total+=parseFloat(el.haber);
                });
            }
            
            this.total_haber=total.toFixed(2);
        },
    cambioDecimales(){
            if(this.creditos.length>0){
                this.creditos.forEach(el => {
                    if(el.haber !==null){
                        el.haber=parseFloat(el.haber).toFixed(2);
                    }
                    
                });
            }
            
            if(this.pagos_sin_plc.length>0){
                this.pagos_sin_plc.forEach(el=>{
                    el.haber=parseFloat(el.haber).toFixed(2);
                });
            }
            if(this.pagos_con_plc.length>0){
                this.pagos_con_plc.forEach(el=>{
                    el.haber=parseFloat(el.haber).toFixed(2);
                });
            }
            if(this.pagos_anticipo.length>0){
                this.pagos_anticipo.forEach(el=>{
                    el.haber=parseFloat(el.haber).toFixed(2);
                });
            }
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
            
        },
    Diferencia(){
            if(this.total_debe>this.total_haber){
                this.diferencia_debe=this.total_haber-this.total_debe;
                console.log("Diferebcia en el debe:"+this.diferencia_debe);
            }
            if(this.total_debe<this.total_haber){
                this.diferencia_haber=this.total_debe-this.total_haber;
                console.log("Diferebcia en el haber:"+this.diferencia_haber);
            }
            var diferencia=this.total_debe-this.total_haber;
            if(this.productos_asiento.length>0){
                if(diferencia!==0){
                    var total_dif=parseFloat(this.productos_asiento[0].haber)+parseFloat(diferencia);
                    this.productos_asiento[0].haber=total_dif;
                    console.log("Producto1: "+total_dif);
                }
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
                                debe: parseFloat(elemento.debe) + parseFloat(valorActual.debe)
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
                                    debe: parseFloat(elemento.debe) + parseFloat(valorActual.debe)
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
                                    debe: parseFloat(elemento.debe) + parseFloat(valorActual.debe)
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
                        debe: parseFloat(elemento.debe) + parseFloat(valorActual.debe)
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
                    if(valorActual.exist_plc_cl==="si"){
                        const elementoYaExiste = acumulador.find(elemento => elemento.id_proyecto === valorActual.id_proyecto  && elemento.id_plan_cuentas_cl === valorActual.id_plan_cuentas_cl );
                        if (elementoYaExiste) {
                            return acumulador.map((elemento) => {
                            if (elemento.id_proyecto === valorActual.id_proyecto && elemento.id_plan_cuentas_cl === valorActual.id_plan_cuentas_cl) {
                                return {
                                ...elemento,
                                haber: parseFloat(elemento.haber) + parseFloat(valorActual.haber)
                                }
                            }

                            return elemento;
                            });
                        }

                        return [...acumulador, valorActual];
                    }else{
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
                    }
                
                }, []);
            }
            if(this.pagos_sin_plc.length>0){
                this.pagos_sin_plc = this.pagos_sin_plc.reduce((acumulador, valorActual) => {
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
            if(this.pagos_con_plc.length>0){
                this.pagos_con_plc = this.pagos_con_plc.reduce((acumulador, valorActual) => {
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
            if(this.pagos_anticipo.length>0){
                this.pagos_anticipo = this.pagos_anticipo.reduce((acumulador, valorActual) => {
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
      tipofactura:"Notadebito",
      recueidfact:null,

      datosg:{},
      popupcorreo:false,
      nombrecliente: "",
      correocliente: "",
      errorenvio:0,
      errornombrecliente:[],
      errorcorreocliente:[],
      //variables Contabilizar
            disabled_asiento:false,
            modalAsiento:false,
            nombre_proyecto:"",
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
            }
    };
  },
  methods: {
        filtertabla() {
            var contvar = this.filterstable.contrue;
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
            } else {
                this.filterstable.filt_asientos = true;
                this.filterstable.filt_noasientos = true;
            }
            this.contenido = contvar;
        },
    listar(page, buscar) {
      var url = "/api/nota_debito_compra";
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
    editar(id) {
      this.$router.push(`/compras/nota-debito/${id}/editar`);
    },
    ver(id) {
      this.$router.push(`/compras/nota-debito/${id}/ver`);
    },
    facturaenvio(dat) {
      this.$vs.notify({
        time:8000,
        title: "Validando Nota de Débito al SRI",
        text: "Por favor Espere...",
        color: "primary",
      });
      this.recueidfact = dat.id_nota_debito;
      this.claveacceso = dat.clave_acceso;
      var url = "/api/factura/xml_nota_debito";
      axios.post(url, dat).then(res => {
        var password = res.data.recupera.pass_firma;
        var firma = DATA_EMPRESA + this.usuario.id_empresa + "/firma/" + res.data.recupera.firma;
        var factura = DATA_EMPRESA + this.usuario.id_empresa + "/comprobantes/notadebito/" +this.claveacceso +".xml";
        var tipo = "nota_debito_venta";
        var carpeta = DATA_EMPRESA + this.usuario.id_empresa + "/comprobantes/notadebito/";
        var fecha_actual = moment(dat.fecha_autorizacion).format('LL');
        this.crearfacturacion(firma, password, factura, tipo, this.usuario, this.recueidfact, carpeta, fecha_actual, dat.valor_total, dat.logo, dat.nombre_empresa);
      });
    },
    eliminar(cd) {
      //console.log("1", cd);
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
      axios.delete("/api/eliminardebitocompra/" + parameters);
      this.$vs.notify({
        color: "warning",
        title: "Comprobante Cancelado",
        text: "El comprobante selecionado fue cancelado con exito"
      });
      this.listar(1, this.buscar);
    },
    descargarpdf(datos){
        window.open('/'+datos.id_empresa+'/vistapdf/nota_debito/'+datos.clave_acceso, '_top');
    },
    enviocorreo(datos){
      this.$vs.notify({
          time: 3000,
          title: "Enviando comprobantes",
          text: "Espere por favor, enviando comprobantes",
          color: "warning"
      }); 
      var fecha_actual = moment(datos.fecha_autorizacion).format('LL');
      axios.post('/api/nota_debito/enviarcorreo', {
          tipo:'Notadebito',
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
        axios.post('/api/nota_debito/enviarcorreo', {
            tipo:'Notadebito',
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
      axios.get('/api/notadebito_compra/verasiento/' + id+
                        "?id_empresa=" +
                        this.usuario.id_empresa).then( ({data}) => {
                          var serie1=data.nota_credito_fact.autorizacionfactura.substring(24,27);
                          var serie2=data.nota_credito_fact.autorizacionfactura.substring(27,30);
                          var documento=data.nota_credito_fact.autorizacionfactura.substring(30,39);
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
                              this.codigo="NDC-"+data.codigo_anterior;
                              this.contabilizado=data.nota_credito_fact.contabilidad;
                          }else{
                              this.codigo="NDC-"+data.codigo;
                              this.contabilizado=null;
                          }
                              this.concepto="Debito Compra "+data.nota_credito_fact.autorizacionfactura+" Proveedor: "+this.razon_social;
                              
                              this.id_factura=id;
                              this.id_proyecto=data.proyecto;
                              this.productos_asiento=data.producto_asientos;
                              this.iva_asiento=data.doce_iva_asiento;
                              this.creditos=data.creditos;
                              this.pagos_sin_plc=data.pagos_asientos_sin_plc;
                              this.pagos_con_plc=data.pagos_asientos_con_plc;
                              this.modalAsiento=true;
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
                          // this.modalAsiento=true;
                          // this.bodegas=data.bodegas;
                          // this.cuentas=data.cuenta;
                          // this.id_factura=id;
                          // this.id_proyecto=data.proyecto;
                          
                        }).catch( error => {
                            console.log(error);
                        });
    },
    agregarcampoConciliacion(index,tipo){
            this.modal_conciliacion=true;
            this.indextipoarreglo = index;
            if(tipo=="anticipo"){
                this.fecha_pago=this.pagos_anticipo[index].fecha_pago;
                this.nombre_pago=this.pagos_anticipo[index].nombre_pago;
                if(this.pagos_anticipo[index].numero_transaccion!==0){
                    this.nro_documento=this.pagos_anticipo[index].numero_transaccion;
                }
                
            }else{
                if(tipo=="forma_pago"){
                    this.fecha_pago=this.pagos_sin_plc[index].fecha_pago;
                    this.nombre_pago=this.pagos_sin_plc[index].nombre_pago;
                    if(this.pagos_sin_plc[index].numero_transaccion!==0){
                        this.nro_documento=this.pagos_sin_plc[index].numero_transaccion;
                    }
                }else{
                    this.fecha_pago=this.pagos_con_plc[index].fecha_pago;
                    this.nombre_pago=this.pagos_con_plc[index].nombre_pago;
                    if(this.pagos_con_plc[index].numero_transaccion!==0){
                        this.nro_documento=this.pagos_con_plc[index].numero_transaccion;
                    }
                }
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
            if(this.total_debe<=0){
                this.$vs.notify({
                    color: "danger",
                    title: "El Debe no puede ser 0",
                });
                this.disabled_asiento=false;
                return;
            }
            if(this.total_haber<=0){
                this.$vs.notify({
                    color: "danger",
                    title: "El Haber no puede ser 0",
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
            axios.post("/api/nota_debito_comp/agregar/asiento",{
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
    validacion_asiento(){
            var error=0;
            //console.log(this.productos_asiento.length);
            if(this.productos_asiento.length>0){
                this.productos_asiento.forEach(el=>{
                    if(el.sector=="producto" && el.iva=="cero"){
                        if(el.id_plan_cuentas_iva_0==null){
                            error++;
                        }
                    }
                    if(el.sector=="producto" && el.iva=="doce"){
                        if(el.id_plan_cuentas_iva_12==null){
                            error++;
                        }
                    }
                    if(el.sector=="servicio"){
                        if(el.id_plan_cuentas_servicio==null){
                            error++;
                        }
                    }
                    if(el.id_proyecto==null){
                            error++;
                        }
                });
            }
            if(this.iva_asiento.length>0){
                this.iva_asiento.forEach(el=>{
                    if(el.id_plan_cuentas==null){
                        error++;
                    }
                    if(el.id_proyecto==null){
                            error++;
                        }
                });
            }
            if(this.pagos_sin_plc.length>0){
                this.pagos_sin_plc.forEach(el=>{
                    if(el.id_plan_cuentas==null){
                        error++;
                    }
                    if(el.id_proyecto==null){
                            error++;
                        }
                });
            }
            if(this.pagos_con_plc.length>0){
                this.pagos_con_plc.forEach(el=>{
                    if(el.id_plan_cuentas==null){
                        error++;
                    }
                    if(el.id_proyecto==null){
                            error++;
                        }
                });
            }
            if(this.pagos_anticipo.length>0){
                this.pagos_anticipo.forEach(el=>{
                    if(el.id_plan_cuentas==null){
                        error++;
                    }
                    if(el.id_proyecto==null){
                            error++;
                        }
                });
            }
            if(this.creditos.length>0){
                this.creditos.forEach(el=>{
                    if(el.exist_plc_cl=='si'){
                        if(el.id_plan_cuentas_cl==null){
                            error++;
                        }
                    }else{
                        if(el.id_plan_cuentas==null){
                            error++;
                        }
                    }
                    
                    if(el.id_proyecto==null){
                            error++;
                    }
                });
            }
            
            return error;
    },
    crearasientoDetalle(id){
            axios.post("/api/nota_debito_comp/agregar/asiento_detalle",{
                proyecto:this.nombre_proyecto,
                productos:this.productos_asiento,
                iva_12:this.iva_asiento,
                pagos_sin_plc:this.pagos_sin_plc,
                pagos_con_plc:this.pagos_con_plc,
                pagos_anticipo:this.pagos_anticipo,
                creditos:this.creditos,
                ucrea:this.usuario.id,
                id_asientos:id,
            }).then(res=>{
                this.$vs.notify({
                color: "success",
                title: "Asiento Agregado",
                text: "Asiento agregado con exito"
                });
                this.modalAsiento=false;
                this.modal_conciliacion=false;
                this.estado_asiento="";
                this.listar(1, this.buscar);
                this.id_factura="";
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