<template>
  <div id="ag-grid-demo">
    <vx-card>
      <div class="flex flex-wrap justify-between items-center mb-3">
        <div class="flex flex-wrap items-center ag-grid-table-actions-left">
          <div class="row mb-2" style="display: flex;">
            <div class="col-xl-6 col-lg-12 justify-content-end clicker" style="position: relative;">
                <div class="input-group mb-3" style="width: 100%;">
                    <input type="text" class="vs-inputx vs-input--input normal" style="width:450px;max-width:100%;" placeholder="Buscar Proveedores para crear consulta..." aria-describedby="basic-addon2"  v-model="buscar" @keyup="listar(buscar,1)"/>
                    <div class="input-group-append">
                    <span class="input-group-text" id="basic-addon2">
                        <i class="fas fa-search"></i>
                    </span>
                    </div>
                </div>
                <div class="menuescoger" id="menuescoger" v-if="buscar">
                    <template v-if="contenido1.length">
                        <ul v-for="(tr,index) in contenido1" :key="index" @click="abrir(tr)">
                            <li>
                                {{tr.nombre_proveedor}}
                                <span class="posicion">
                                    <template v-if="tr.tipo_identificacion"><span>Cédula:  {{tr.tipo_identificacion}} </span> | </template>
                                    <template v-if="tr.cod_proveedor"><span>Cod. Proveedor:  {{tr.cod_proveedor}} </span> | </template>
                                    <template v-if="tr.contacto"><span>Contacto: {{tr.contacto}} </span> </template>
                                </span>
                            </li>
                        </ul>
                    </template>
                    <template v-else>
                        <ul style="padding: 7px;text-align: center;">
                            <li>
                            ESTE PACIENTE NO EXISTE EN NUESTROS REGISTROS
                            </li>
                        </ul>
                    </template>
                </div>
            </div>
            <vs-button class="btn-drop ml-3" type="filled" icon="cancel" v-if="visualizar" @click="listarcuentas(buscarpago),visualizar=false,buscar=''"></vs-button>
                <div class="col-xl-6 col-lg-12 flex flex-wrap items-center justify-between ml-3" style="position: relative;">  
                    <template v-if="filterstable.filtertab">
                        <label
                            style="position: absolute; top: -20%; left: 1%;"
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
          </div>
        </div>
        <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right" style="margin-bottom: 10px;">
          <vs-input class="mb-4 md:mb-0 mr-4" v-model="buscarpago" @keyup="listarcuentas(buscarpago)" placeholder="Buscar pago relizado..."/>
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
          <div class="dropdown-button-container">
            <vs-button class="btnx" type="filled" @click="abrirModal()">Agregar</vs-button>
            <vs-dropdown>
              <vs-button class="btn-drop" type="filled" icon="expand_more"></vs-button>
              <vs-dropdown-menu style="width:13em;">
                <vs-dropdown-item class="text-center" @click="importar = true">Importar Excel</vs-dropdown-item>
                <vs-dropdown-item class="text-center" @click="generarreporte=true" divider>Generar Reporte</vs-dropdown-item>
              </vs-dropdown-menu>
            </vs-dropdown>
          </div>
        </div>
      </div>
      <div v-if="!visualizar">
        <div class="vx-row leading-loose p-base">
          <div class="vx-col sm:w-full w-full relative">
            <vs-table stripe max-items="25" pagination :data="listarcuentaslista">
              <template slot="thead">
                  <vs-th class="text-center" style="width: 14rem;">Nro. comprobantes</vs-th>
                  <vs-th class="text-center">Pagos Por</vs-th>
                  <vs-th class="text-center">Proveedor</vs-th>
                  <vs-th class="text-center">Fecha Pago</vs-th>
                  <vs-th class="text-center">Forma Pago</vs-th>
                  <vs-th class="text-center">Descuento</vs-th>
                  <vs-th class="text-center">Pago</vs-th>
                  <vs-th class="text-center">Opciones</vs-th>
              </template>
              <template slot-scope="{ data }">
                  <vs-tr v-for="(datos, index) in data" :key="index">
                      <!--<vs-td class="text-center">
                        <span v-for="(tr, index1) in datos.referencia" :key="index1">
                          <span v-if="index1%4==0">
                            {{tr}}
                          </span> <br v-if="((datos.referencia.length)%4==0) != ((index1)%4==0)+1">
                        </span> 
                      </vs-td>-->
                      <vs-td class="text-center" v-if="datos.posicion==null">{{listarcuentaslista.length-index}}</vs-td>
                      <vs-td class="text-center" v-else>{{datos.posicion}}</vs-td>
                      <vs-td class="text-center" v-if="datos.pagos_por">{{ datos.pagos_por }}</vs-td><vs-td class="text-center" v-else>-</vs-td>
                      <vs-td class="text-center" v-if="datos.nombreproveedor">{{ datos.nombreproveedor }}</vs-td><vs-td class="text-center" v-else>-</vs-td>
                      <vs-td class="text-center" v-if="datos.fecha_registro">{{datos.fecha_registro | fecha}}</vs-td>
                      <vs-td class="text-center" v-else>{{datos.fecha_pago | fecha}}</vs-td>
                      <vs-td class="text-center" v-if="datos.descripcionsri">{{ datos.descripcionsri }}</vs-td><vs-td class="text-center" v-else>-</vs-td>
                      <vs-td class="text-center" v-if="datos.descuento_pago">{{ datos.descuento_pago }}</vs-td><vs-td class="text-center" v-else>-</vs-td>
                      <vs-td class="text-center" v-if="datos.valor_real_pago">{{ datos.valor_real_pago }}</vs-td><vs-td class="text-center" v-else>-</vs-td>
                      <vs-td class="text-center">
                          <vs-dropdown vs-custom-content vs-trigger-click class="pointer">
                              <vs-button class="btn-drop pointer" type="filled" icon="expand_more">Acciones</vs-button>
                              <vs-dropdown-menu style="width:13em;">
                                  <vs-dropdown-item class="text-center" v-if="datos.contabilidad==null" @click="editarpago(datos)"><feather-icon icon="TrashIcon" svgClasses="w-5 h-5"></feather-icon> Editar</vs-dropdown-item>
                                  <vs-dropdown-item class="text-center" divider @click="descargaPdf(datos.id_ctas_pagar_pagos,index,datos.pagos_por)"><feather-icon icon="DownloadIcon" svgClasses="w-5 h-5"></feather-icon> Descargar PDF</vs-dropdown-item>
                                  <vs-dropdown-item class="text-center" v-if="datos.cheque!==0" divider @click="descargaCheque(datos.id_ctas_pagar_pagos,index)"><feather-icon icon="DownloadIcon" svgClasses="w-5 h-5"></feather-icon> Descargar Cheque</vs-dropdown-item>
                                  <vs-dropdown-item class="text-center" divider v-if="datos.contabilidad==null" @click="eliminarcxc(datos.id_ctas_pagar_pagos)"><feather-icon icon="TrashIcon" svgClasses="w-5 h-5"></feather-icon> Eliminar</vs-dropdown-item>
                              </vs-dropdown-menu>
                          </vs-dropdown>
                      </vs-td>
                      <vs-td class="text-center" v-if="datos.pagos_por!=='Anticipo'">
                          <feather-icon icon="SlidersIcon" class="cursor-pointer" v-if="datos.contabilidad!==null" svgClasses="w-5 h-5 fill-current text-success" @click="Contabilidad(datos.id_ctas_pagar_pagos,datos.pago_anticipo)"/>
                          <feather-icon icon="SlidersIcon" class="cursor-pointer" v-else svgClasses="w-5 h-5 fill-current text-primary" @click="Contabilidad(datos.id_ctas_pagar_pagos,datos.pago_anticipo)"/>
                          <feather-icon icon="CheckIcon"  v-if="datos.contabilidad!==null" svgClasses="w-5 h-5"/>
                      </vs-td>
                      <vs-td class="text-center" v-else>
                          <feather-icon icon="SlidersIcon" class="cursor-pointer" v-if="datos.contabilidad!==null" svgClasses="w-5 h-5 fill-current text-success" @click="ContabilidadAnticipo(datos.referencia,datos.id_ctas_pagar_pagos)"/>
                          <feather-icon icon="SlidersIcon" class="cursor-pointer" v-else svgClasses="w-5 h-5 fill-current text-primary" @click="ContabilidadAnticipo(datos.referencia,datos.id_ctas_pagar_pagos)"/>
                          <feather-icon icon="CheckIcon"  v-if="datos.contabilidad!==null" svgClasses="w-5 h-5"/>
                      </vs-td>
                  </vs-tr>
              </template>
            </vs-table>
          </div>
        </div>
      </div>
      <div v-else> 
          <div class="vx-row mt-5">
            <vs-divider border-style="solid" color="dark">Agregar pago de las cuentas por pagar.</vs-divider>
            <div class="vx-col sm:w-1/12 w-full mb-2">
              <h6>Cierre Anticipos</h6>
              
              <vs-checkbox v-model="exist_anticipos" @click="listar_anticipos_cliente()"></vs-checkbox>
            </div>
            <div class="vx-col xl:w-1/6 md:w1/2 sm:w-full mb-2">
              <h6>Pagos Por:</h6>
              <vs-select autocomplete class="selectExample w-full" v-model="pagos_por">
                <vs-select-item :key="index" :value="item.value" :text="item.text" v-for="(item,index) in pago_por_array"/>
              </vs-select>
            </div>
            <div class="vx-col sm:w-1/6 w-full mb-2">
              <h6>Forma de Pago:</h6>
              <vs-select placeholder="buscar" autocomplete class="selectExample w-full" v-model="forma_pago">
                <vs-select-item v-for="(tr,index) in formapagos" :key="index" :value="tr.id_forma_pagos" :text="tr.descripcion"/>
              </vs-select>
            </div>
            <div class="vx-col xl:w-1/6 md:w1/2 sm:w-full mb-2">
              <h6>Banco:</h6>
              <vs-select class="selectExample w-full" vs-multiple v-model="banco">
                  <vs-select-item value="" text="Seleccione el banco"/>
                  <vs-select-item v-for="data in bancos" :key="data.id_banco" :value="data.id_banco" :text="data.nombre_banco"/>
              </vs-select>
            </div>  
            <div class="vx-col xl:w-1/6 md:w1/2 sm:w-full mb-2">
              <h6>Nro. Cheque tarjeta:</h6>
              <vs-input class="w-full" v-model="numero_tarjeta" />
            </div>
            <div class="vx-col xl:w-1/6 md:w1/2 sm:w-full mb-2">
              <h6>Fecha:</h6>
              <flat-pickr :config="configdateTimePicker" class="w-full" v-model="fecha_registro" placeholder="Seleccionar"></flat-pickr>
            </div>

            <div class="vx-col xl:w-1/5 mt-6 md:w1/2 sm:w-full mb-2">
              <h6>Valor Selec.:</h6>
              <vs-input class="w-full text-center" placeholder="0.00" v-bind:value="valor_select" disabled/>
            </div>
            <div class="vx-col xl:w-1/5 mt-6 md:w1/2 sm:w-full mb-2">
              <h6>% Desc:</h6>
              <vs-input class="w-full text-center" placeholder="0.00" :disabled="valor_select<=0" v-model="descuento_porcentaje" @keyup="descuentop()"/>
            </div>
            <div class="vx-col xl:w-1/5 mt-6 md:w1/2 sm:w-full mb-2">
              <h6>Desc. Pago:</h6>
              <vs-input class="w-full text-center" placeholder="0.00" :disabled="valor_select<=0" v-model="descuento_pago" @keyup="descuentod()"/>
            </div>
            <div class="vx-col xl:w-1/5 mt-6 md:w1/2 sm:w-full mb-2 mr-auto">
              <h6>Valor real Pago:</h6>
              <vs-input class="w-full text-center" placeholder="0.00" :disabled="valor_select<=0" v-model="valor_real" @keyup="valortotalr()"/>
            </div>
            <div class="vx-col xl:w-1/5 mt-6 md:w1/2 sm:w-full mb-2 ml-auto" style="margin-top: 2.6rem!important;">
              <vs-button color="success" type="filled" @click="guardarpago()" :disabled="disabled_guardarpago">GUARDAR</vs-button>
            </div>
            <div class="vx-col w-full mt-6">
              <div class="vx-row">
                <div class="vx-col sm:w-1/3 w-full ml-auto text-center">
                    <label class="vs-input--label">SALDO TOTAL DEUDA</label>
                    <h1>$ {{totaldeuda}}</h1>
                </div>
                <div class="vx-col sm:w-1/3 w-full mr-auto text-center">
                    <label class="vs-input--label" style="color:red">SALDO TOTAL VENCIDO</label>
                    <h1 style="color:red">$ {{vencido}}</h1>
                </div>
              </div>
            </div>
            
            <div class="vx-col w-full mt-6" v-if="exist_anticipos==false">
              <vs-table stripe :data="contenido">
                <template slot="thead">
                  <vs-th class="text-center">N° factura</vs-th>
                  <vs-th class="text-center">Proveedor</vs-th>
                  <vs-th class="text-center">Num_cuota</vs-th>
                  <vs-th class="text-center">Fecha Pago</vs-th>
                  <vs-th class="text-center">Valor Cuota</vs-th>
                  <vs-th class="text-center">Seleccionar</vs-th>
                </template>
                <template slot-scope="{data}">
                  <vs-tr v-for="(datos,index) in data" :key="index">
                    <vs-td class="text-center" v-if="datos.clave_acceso">{{(datos.clave_acceso).substring(0,3)}}-{{(datos.clave_acceso).substring(3,6)}}-{{(datos.clave_acceso).substring(6,15)}}</vs-td><vs-td class="text-center" v-else-if="datos.referencias>=15">{{datos.referencias}}</vs-td><vs-td v-else-if="datos.referencias">001-001-{{datos.referencias | ceros}}</vs-td> <vs-td v-else>-</vs-td> 
                    <vs-td class="text-center" v-if="datos.nombre">{{datos.nombre}}</vs-td><vs-td class="text-center" v-else>-</vs-td>
                    <vs-td class="text-center" v-if="datos.num_cuota"><vs-alert color="danger" active="true">{{datos.num_cuota}}</vs-alert></vs-td><vs-td class="text-center" v-else>-</vs-td>
                    <vs-td class="text-center" v-if="datos.fecha_pago">{{datos.fecha_pago | fecha}}</vs-td><vs-td class="text-center" v-else>-</vs-td>
                    <vs-td class="text-center" v-if="datos.valor_cuota">$ {{(datos.valor_cuota - datos.valor_pagado).toFixed(2)}}</vs-td><vs-td class="text-center" v-else>-</vs-td>
                    <vs-td class="text-center" style="width: 1px;">
                      <vs-switch v-model="datos.agregar" class="text-center">
                        <span slot="on">Agregado</span>
                        <span slot="off">No agregado</span>
                      </vs-switch>
                    </vs-td>
                  </vs-tr>
                </template>
              </vs-table>
            </div>
            <div class="vx-col w-full mt-6" v-else>
              <vs-table stripe max-items="25" pagination :data="list_anticipos">
                <template slot="thead">
                  <vs-th class="text-center">N° Recibo</vs-th>
                  <vs-th class="text-center">Proveedor</vs-th>
                  <vs-th class="text-center">Comprobante</vs-th>
                  <vs-th class="text-center">Fecha Pago</vs-th>
                  <vs-th class="text-center">Valor Cuota</vs-th>
                  <vs-th class="text-center">Seleccionar</vs-th>
                </template>
                <template slot-scope="{data}">
                  <vs-tr v-for="(datos,index) in data" :key="index">
                    <vs-td class="text-center" v-if="datos.posicion">{{ datos.posicion }}</vs-td><vs-td class="text-center" v-else-if="datos.referencias">{{datos.referencias}}</vs-td><vs-td class="text-center" v-else>-</vs-td>
                    <vs-td class="text-center" v-if="datos.nombre">{{datos.nombre}}</vs-td><vs-td class="text-center" v-else>-</vs-td>
                    <vs-td class="text-center" >Anticipo</vs-td>
                    <vs-td class="text-center" v-if="datos.fecha_emision">{{datos.fecha_emision | fecha}}</vs-td><vs-td class="text-center" v-else>-</vs-td>
                    <vs-td class="text-center" v-if="datos.abono">$ {{parseFloat(datos.abono).toFixed(2)}}</vs-td><vs-td class="text-center" v-else>-</vs-td>
                    <vs-td class="text-center" style="width: 1px;">
                      <vs-switch v-model="datos.agregar" class="text-center">
                        <span slot="on">Agregado</span>
                        <span slot="off">No agregado</span>
                      </vs-switch>
                    </vs-td>
                  </vs-tr>
                </template>
              </vs-table>
            </div>
          </div>
      </div>
    </vx-card>
    <vs-popup classContent="popup-example" title="Generar Reporte" :active.sync="generarreporte">
      <div class="vx-row">
        <div class="vx-col sm:w-full mb-6">
          <label class="vs-input--label">Defina el tipo de búsqueda</label>
          <vs-select placeholder="Escoga el tipo de búsqueda" @change="recargar_reporte()" class="selectExample w-full" vs-multiple v-model="tipo_busqueda">
            <vs-select-item value="1" text="Fechas" />
            <vs-select-item value="2" text="Cliente" />
            <vs-select-item value="3" text="Vendedor" />
            <vs-select-item value="4" text="Producto" />
          </vs-select>
        </div>
        <div class="vx-col sm:w-full mb-6" v-if="tipo_busqueda==1">
          <label class="vs-input--label">Escoga el rango de fechas</label>
          <div class="vx-row">
            <div class="vx-col sm:w-1/2 mb-3">
              <label class="vs-input--label mt-3">Fecha de inicio</label>
              <flat-pickr :config="configdateTimePicker" class="w-full mt-1" v-model="dateinicio" placeholder="Seleccionar"></flat-pickr>
            </div>
            <div class="vx-col sm:w-1/2 mb-3">
              <label class="vs-input--label mt-3">Fecha final</label>
              <flat-pickr :config="configdateTimePicker" class="w-full mt-1" v-model="datefin" placeholder="Seleccionar"></flat-pickr>
            </div>
          </div>
        </div>
        <div class="vx-col sm:w-full mb-6" v-if="tipo_busqueda==2">
          <label class="vs-input--label">Escoga el cliente</label>
          <vs-select placeholder="Escoga el cliente" class="selectExample w-full" vs-multiple v-model="cliente_busqueda">
            <vs-select-item v-for="data in clientes2" :key="data.id_cliente" :value="data.id_cliente" :text="data.nombre"/>
          </vs-select>
        </div>
        <div class="vx-col sm:w-full mb-6" v-if="tipo_busqueda==3">
          <label class="vs-input--label">Escoga el vendedor</label>
          <vs-select placeholder="Escoga el vendedor" class="selectExample w-full" vs-multiple v-model="vendedor_busqueda">
            <vs-select-item v-for="data in vendedores2" :key="data.id" :value="data.id" :text="data.nombres+' '+data.apellidos"/>
          </vs-select>
        </div>
        <div class="vx-col w-full mt-6">
          <vs-button color="success" type="filled" @click="reportes_factura()">GENERAR</vs-button>
          <vs-button color="danger" type="filled" @click="generarreporte=false">CANCELAR</vs-button>
        </div>
      </div>
    </vs-popup>
    <vs-popup classContent="popup-example" title="Agregar Pago Proveedores" :active.sync="cobroclientes">
        <div class="vx-row">
          <div class="vx-col sm:w-2/5 mb-6">
            <label class="vs-input--label">Número de factura</label>
            <vs-input class="inputx w-full" :disabled="cproveedor.anticipo" maxlength='15' placeholder="Ingresar el número de factura" v-model="cproveedor.factura"/>
            <div v-show="errorcproveedor.error">
              <span class="text-danger" v-for="(err,index) in errorcproveedor.factura" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="col-xl-6 sm:w-2/5 col-lg-12 justify-content-end clicker mb-6" style="position: relative;">
              <label class="vs-input--label">Proveedor</label>
              <div class="input-group" style="width: 100%;">
                  <input type="text" class="vs-inputx vs-input--input normal" style="width:450px;max-width:100%;" placeholder="Buscar proveedor para consultar..." aria-describedby="basic-addon2"  v-model="buscar" @keyup="listar(buscar,2)"/>
                  <div class="input-group-append">
                  <span class="input-group-text" id="basic-addon2">
                      <i class="fas fa-search"></i>
                  </span>
                  </div>
              </div>
              <div class="menuescoger busqueda_lista busqueda_cliente_ls" id="menuescoger1" v-if="buscar">
                  <template v-if="contenido1.length">
                      <ul v-for="(tr,index) in contenido1" :key="index" @click="abrir(tr)" class="ul_busqueda_lista">
                          <li>
                              {{tr.nombre_proveedor}}
                              <span class="posicion">
                                  <template v-if="tr.tipo_identificacion"><span>Cédula:  {{tr.tipo_identificacion}} </span> | </template>
                                  <template v-if="tr.cod_proveedor"><span>Cod. Proveedor:  {{tr.cod_proveedor}} </span> | </template>
                                  <template v-if="tr.contacto"><span>Contacto: {{tr.contacto}} </span> </template>
                              </span>
                          </li>
                      </ul>
                  </template>
                  <template v-else>
                      <ul style="padding: 7px;text-align: center;">
                          <li>
                          ESTE PACIENTE NO EXISTE EN NUESTROS REGISTROS
                          </li>
                      </ul>
                  </template>
              </div>
              <div v-show="errorcproveedor.error">
                <span class="text-danger" v-for="(err,index) in errorcproveedor.proveedor" :key="index" v-text="err"></span>
              </div>
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <label class="vs-input--label">Anticipo</label>
            <vs-checkbox icon-pack="feather" class="mt-3" icon="icon-check" v-model="cproveedor.anticipo" @click="anticipo()">
                <template v-if="cproveedor.anticipo">
                    <label class="vs-input--label" style="font-size: 14px;font-weight: bold;">Si</label>
                </template>
                <template v-else>
                    <label class="vs-input--label" style="font-size: 14px;font-weight: bold;">No</label>
                </template>
                | es anticipo
            </vs-checkbox>
          </div>
          <div class="vx-col sm:w-1/5 mb-6">
            <label class="vs-input--label">Periodo de pago</label>
            <vs-select placeholder="Selecciona el per. pago" :disabled="cproveedor.anticipo" autocomplete class="selectExample w-full" v-model="cproveedor.periodo">
                <vs-select-item value="Dias" text="Dias" />
                <vs-select-item value="Semanas" text="Semanas" />
                <vs-select-item value="Meses" text="Meses" />
                <vs-select-item value="Años" text="Años" />
            </vs-select>
            <div v-show="errorcproveedor.error">
              <span class="text-danger" v-for="(err,index) in errorcproveedor.periodo" :key="index" v-text="err"></span>
            </div>
          </div>
          <!-- <div class="vx-col sm:w-1/6 mb-6" v-if="!cproveedor.anticipo">
              <label class="vs-input--label">N° Transacción</label>
              <vs-input class="inputx w-full"  placeholder="Ingresar los tiempos" v-model="cproveedor.transaccion"/>
          </div> -->
          <div class="vx-col sm:w-1/6 mb-6" v-if="!cproveedor.anticipo">
            <label class="vs-input--label">Tiempos Pago</label>
            <vs-input class="inputx w-full" :disabled="cproveedor.anticipo" placeholder="Ingresar los tiempos" v-model="cproveedor.tiempos"/>
            <div v-show="errorcproveedor.error">
              <span class="text-danger" v-for="(err,index) in errorcproveedor.tiempos" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/6 mb-6" v-if="!cproveedor.anticipo">
            <label class="vs-input--label">Plazos de pago</label>
            <vs-select placeholder="Seleccione" :disabled="cproveedor.anticipo" autocomplete class="selectExample w-full" v-model="cproveedor.plazo">
                <vs-select-item v-for="(v, index) in 24" :key="index" :value="v" :text="v + ' Periodos'"/>
            </vs-select>
            <div v-show="errorcproveedor.error">
              <span class="text-danger" v-for="(err,index) in errorcproveedor.plazo" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-2/6 mb-6" v-if="cproveedor.anticipo">
            <label class="vs-input--label">Forma de pago</label>
            <vs-select placeholder="buscar" autocomplete class="selectExample w-full" v-model="cproveedor.formapago">
              <vs-select-item v-for="(tr,index) in formapagos" :key="index" :value="tr.id_forma_pagos" :text="tr.descripcion"/>
            </vs-select>
            <div v-show="errorcproveedor.error">
              <span class="text-danger" v-for="(err,index) in errorcproveedor.formapago" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/5 mb-6" v-if="cproveedor.anticipo">
            <label class="vs-input--label">Nro. Cheque</label>
            <vs-input class="inputx w-full" placeholder="Ingresar el monto" v-model="cproveedor.nrocheque"/>
          </div>
          <div class="vx-col xl:w-1/5 md:w1/2 sm:w-full mb-2" v-if="cproveedor.anticipo">
              <label class="vs-input--label">Fecha:</label>
              <flat-pickr :config="configdateTimePicker" class="w-full" v-model="fecha_registro_pago" placeholder="Seleccionar"></flat-pickr>
          </div>
          <div class="vx-col xl:w-1/6 md:w1/2 sm:w-full mb-2" v-else>
              <label class="vs-input--label">Fecha:</label>
              <flat-pickr :config="configdateTimePicker" class="w-full" v-model="fecha_registro_pago" placeholder="Seleccionar"></flat-pickr>
          </div>
          <div class="vx-col sm:w-1/6 mb-6">
            <label class="vs-input--label">Monto de pago</label>
            <vs-input class="inputx w-full" placeholder="Ingresar el monto" v-model="cproveedor.monto"/>
            <div v-show="errorcproveedor.error">
              <span class="text-danger" v-for="(err,index) in errorcproveedor.monto" :key="index" v-text="err"></span>
            </div>
          </div>
        </div>
        <div class="vx-col w-full mt-6">
          <vs-button color="success" type="filled" @click="guardar()">GUARDAR</vs-button>
          <vs-button color="danger" type="filled" @click="cobroclientes=false">CANCELAR</vs-button>
        </div>
    </vs-popup>
    <!-- modal asiento pago proveedores-->
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
        {{igualar}}
        <div
            id="one-row"
            class="vx-row"
            v-for="(data, index1) in productos_asiento"
            v-bind:key="index1"
        >
          
            <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                      <div class="vx-col sm:w-1/3 w-full mb-6" v-if="data.exist_plan_cuenta_proveedor=='si'">
                      <vx-input-group>
                                <!-- prettier-ignore -->
                                <vs-input
                                    class="w-full"
                                    v-model="data.nombre_cuenta_proveedor"
                                    disabled
                                />
                    
                            </vx-input-group>
                      </div>
                      <div class="vx-col sm:w-1/3 w-full mb-6" v-else>
                            <vx-input-group>
                                <!-- prettier-ignore -->
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
                    <div class="vx-col sm:w-1/12 w-full mb-2" v-if="data.haber>0 && data.bansel!==null">
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
    <!--modal asiento cierre anticipo-->
    <vs-popup title="Asiento Contable" :class="'peque3'" :active.sync="modalAsiento_anticipos">
      <div class="vx-row">
          <div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Número:"
                            :disabled="true"
                            v-model="codigo_anticipos"

                        />
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6" hidden>
                        <label class="vs-input--label">Proyecto:</label>
                        <vx-input-group>
                            <vs-input
                                class="w-full"
                                v-model="nombre_proyecto_anticipos"
                                disabled
                            />
                            
                        </vx-input-group>
              
          </div>
          <div class="vx-col sm:w-1/6 w-full mb-6">
                          <label class="vs-input--label">Fecha:</label>
                          <vx-input-group>
                              <vs-input
                                  class="w-full"
                                  v-model="fecha_rol_anticipos"
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
                                v-model="razon_social_anticipos"
                                disabled
                            />
                            
                        </vx-input-group>
              
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
                          <label class="vs-input--label">Tipo Identificacion:</label>
                          <vx-input-group>
                              <vs-input
                                  class="w-full"
                                  v-model="tipo_identificacion_anticipos"
                                  disabled
                              />
                              
                          </vx-input-group>
                
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
                          <label class="vs-input--label">Identificacion:</label>
                          <vx-input-group>
                              <vs-input
                                  class="w-full"
                                  v-model="ruc_empresa_anticipos"
                                  disabled
                              />
                              
                          </vx-input-group>
                
          </div>
          <div class="vx-col sm:w-4/11 w-full mb-6">
                          <label class="vs-input--label">Concepto:</label>
                          <vx-input-group>
                              <vs-input
                                  class="w-full"
                                  v-model="concepto_anticipos"
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
        {{igualar}}
        <div
            id="one-row"
            class="vx-row"
            v-for="(data, index1) in productos_asiento_anticipos"
            v-bind:key="index1"
        >
          
            <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                      <div class="vx-col sm:w-1/3 w-full mb-6" v-if="data.exist_plan_cuenta_proveedor=='si'">
                      <vx-input-group>
                                <!-- prettier-ignore -->
                                <vs-input
                                    class="w-full"
                                    v-model="data.nombre_cuenta_proveedor"
                                    disabled
                                />
                    
                            </vx-input-group>
                      </div>
                      <div class="vx-col sm:w-1/3 w-full mb-6" v-else>
                            <vx-input-group>
                                <!-- prettier-ignore -->
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
                        <div class="vx-col sm:w-1/12 w-full mb-2" v-if="data.bansel!==null">
                        <vs-button
                            type="filled"
                            style="height: 1.5em;padding: 0px;width: 1.5em; margin:1px 1px;"
                            color="success"
                            @click="agregarcampoConciliacionPago(index,'cliente')"
                            >C</vs-button
                        >
                    </div>
                    </div>
            </div>
          
        </div>
        
        <div
            id="two-row"
            class="vx-row"
            v-for="(data,index) in iva_asiento_anticipos"
            :key="data.id_detalle"
        >
        <div class="vx-col xs:w-10/12 sm:w-11/12">
                <div class="vx-row">
                    <!--CUENTA CONTABLE-->
                    <div class="vx-col sm:w-1/3 w-full mb-6" >
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
                    <div class="vx-col sm:w-1/12 w-full mb-2" v-if="data.bansel!==null">
                        <vs-button
                            type="filled"
                            style="height: 1.5em;padding: 0px;width: 1.5em; margin:1px 1px;"
                            color="success"
                            @click="agregarcampoConciliacionPago(index,'forma_pago')"
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
                                v-model="total_debe_anticipos"
                                disabled
                            />
                    </div>
                    {{suma_haber}}
                    <div class="vx-col sm:w-2/12 w-full mb-6">
                        <vs-input
                                class="w-full"
                                v-model="total_haber_anticipos"
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

                    <div class="vx-col sm:w-2/12 w-full mb-6" v-if="diferencia_debe_anticipos>0">
                        <label class="vs-input--label center">Diferencia</label>
                    </div>
                    <div class="vx-col sm:w-2/12 w-full mb-6" v-if="diferencia_haber_anticipos>0">
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
                    
                    
                    <div class="vx-col sm:w-2/12 w-full mb-6" v-if="diferencia_debe_anticipos>0">
                        <vs-input
                                class="w-full"
                                v-model="diferencia_debe_anticipos"
                                disabled
                            />
                    </div>
                    
                    <div class="vx-col sm:w-2/12 w-full mb-6" v-if="diferencia_haber_anticipos>0">
                        <vs-input
                                class="w-full"
                                v-model="diferencia_haber_anticipos"
                                disabled
                            />
                    </div>
                </div>
            </div>
        </div>
        <div v-if="contabilizado_anticipos!==null">
          <h5> Este asiento ya ha sido registrado</h5>
        </div>
        <div v-else>
            <vs-button
                    color="success"
                    type="filled"
                    :disabled="disabled_asiento_anticipos"
                    @click="crearasiento_anticipos(id_factura_anticipos)" 
                    >GUARDAR</vs-button
                >
        </div>
        <vs-popup title="Conciliacion" :active.sync="modal_conciliacion_anticipos">
                      <div
                            class="vx-row"

                        >
                            <div class="vx-col sm:w-1/4 w-full mb-6" >
                            <vs-input
                                    label="Fecha Pago"
                                    class="w-full"
                                    v-model="fecha_pago_anticipos"
                                    disabled
                                />
                            </div>
                            <div class="vx-col sm:w-1/3 w-full mb-6">
                                <vs-input
                                    label="Forma Pago"
                                    class="w-full"
                                    v-model="nombre_pago_anticipos"
                                    disabled
                                />
                            </div>
                            <div class="vx-col sm:w-1/4 w-full mb-6">
                                <vs-input
                                    label="No Documento"
                                    class="w-full"
                                    v-model="nro_documento_anticipos"
                                    disabled
                                />
                            </div>
                        </div>
                        
                </vs-popup>
    </vs-popup>
    <!--Importar excel-->
    <vs-popup :class="'peque2'" title="Importar Excel" :active.sync="importar">
      <vx-card>
        <div class="vx-col sm:w-full w-full mb-6">
          <div class="vx-row">
            <div class="vx-col sm:w-full w-full mb-6">
              <label class="vs-input--label">Subir Archivo</label>
                <div class="vx-col md:w-full w-full mb-6">
                  <div style="display: none;">
                    <input id="input-upload" type="file" class="custom-file-input inputexcel1" @change="subirArchivo($event)" accept=".XLSX, .CSV"/>
                  </div>
                  <div class="centimg vx-card input" @click="importarexcel1()">
                    <img src="/images/upload.png" />
                    <div v-if="file.length === 0" style="position:absolute;margin-top:60px;color:#000" >Click para subir Archivo</div>
                    <div v-else  style="position:absolute;margin-top:60px;color:#000">{{file[0].name}}</div>
                  </div>
                </div>
              </div> 
              <div class="vx-col sm:w-full w-full mb-6">
                <vs-button color="success" @click="crear_importacion()">Subir Archivo</vs-button>
                <vs-button color="danger" type="filled" @click="cancelar_importacion()">Cancelar</vs-button>
              </div>
            </div>
          </div>
      </vx-card>
    </vs-popup>
    <!--Editar pago-->
    <vs-popup title="Editar pago" :active.sync="pago_edicion">
      <div class="vx-row">
        <div class="vx-col sm:w-full w-full mb-6">
          <div class="vx-row">
            <div class="vx-col xl:w-1/4 md:w1/2 sm:w-full mb-2">
              <h6>Pagos Por:</h6>
              <vs-select autocomplete class="selectExample w-full" v-model="pago_editar.pagos_por">
                <vs-select-item :key="index" :value="item.value" :text="item.text" v-for="(item,index) in pago_por_array"/>
              </vs-select>
              <div v-show="errorpagoedicion">
                <span class="text-danger" v-for="(err, index) in error_pago_editar.pagos_por" :key="index" v-text="err"></span>
              </div>
            </div>
            <div class="vx-col xl:w-1/4 md:w1/2 sm:w-full mb-2">
              <h6>Nro. Cheque tarjeta:</h6>
              <vs-input class="w-full" v-model="pago_editar.nro_tarjeta" />
            </div>
            <div class="vx-col sm:w-1/4 w-full mb-2">
              <h6>Forma de Pago:</h6>
              <vs-select placeholder="buscar" autocomplete class="selectExample w-full" v-model="pago_editar.id_forma_pagos">
                <vs-select-item v-for="(tr,index) in formapagos" :key="index" :value="tr.id_forma_pagos" :text="tr.descripcion"/>
              </vs-select>
              <div v-show="errorpagoedicion">
                <span class="text-danger" v-for="(err,index) in error_pago_editar.id_forma_pagos" :key="index" v-text="err"></span>
              </div>
            </div>
            <div class="vx-col xl:w-1/4 md:w1/2 sm:w-full mb-2">
              <h6>Banco:</h6>
              <vs-select class="selectExample w-full" vs-multiple v-model="pago_editar.id_banco">
                  <vs-select-item value="" text="Seleccione el banco"/>
                  <vs-select-item v-for="data in bancos" :key="data.id_banco" :value="data.id_banco" :text="data.nombre_banco"/>
              </vs-select>
            </div>  

            <div class="vx-col xl:w-1/5 mt-6 md:w1/2 sm:w-full mb-2">
              <h6>Fecha:</h6>
              <flat-pickr :config="configdateTimePicker" class="w-full" v-model="pago_editar.fecha_pago" placeholder="Seleccionar"></flat-pickr>
            </div>
            <div class="vx-col xl:w-1/5 mt-6 md:w1/2 sm:w-full mb-2">
              <h6>Valor Selec.:</h6>
              <vs-input class="w-full text-center" placeholder="0.00" v-bind:value="pago_editar.valor_seleccionado" disabled/>
            </div>
            <div class="vx-col xl:w-1/5 mt-6 md:w1/2 sm:w-full mb-2">
              <h6>% Desc:</h6>
              <vs-input class="w-full text-center" placeholder="0.00" v-model="pago_editar.descuento_porcentaje" @keyup="cambio_pago_pagd()"/>
            </div>
            <div class="vx-col xl:w-1/5 mt-6 md:w1/2 sm:w-full mb-2">
              <h6>Desc. Pago:</h6>
              <vs-input class="w-full text-center" placeholder="0.00" v-model="pago_editar.descuento_pago" @keyup="cambio_pago_pagp()"/>
            </div>
            <div class="vx-col xl:w-1/5 mt-6 md:w1/2 sm:w-full mb-2">
              <h6>Valor real Pago:</h6>
              <vs-input class="w-full text-center" placeholder="0.00"  v-model="pago_editar.valor_real_pago"/>
            </div>
            <!-- <div class="vx-col w-full mt-6">
              <div class="vx-row">
                <div class="vx-col sm:w-1/3 w-full ml-auto text-center">
                    <label class="vs-input--label">SALDO TOTAL DEUDA</label>
                    <h1>$ {{totaldeuda2}}</h1>
                </div>
                <div class="vx-col sm:w-1/3 w-full mr-auto text-center">
                    <label class="vs-input--label" style="color:red">SALDO TOTAL VENCIDO</label>
                    <h1 style="color:red">$ {{vencido2}}</h1>
                </div>
              </div>
            </div> -->
            
            <div class="vx-col w-full mt-6">
              <vs-table stripe :data="contenido2">
                <template slot="thead">
                  <vs-th class="text-center" v-if="exist_anticipos_editar==false">N° factura</vs-th>
                  <vs-th class="text-center" v-else>N° recibo</vs-th>
                  <vs-th class="text-center">Cliente</vs-th>
                  <vs-th class="text-center" v-if="exist_anticipos_editar==false">Num_cuota</vs-th>
                  <vs-th class="text-center" v-else>Comprobante</vs-th>
                  <vs-th class="text-center">Fecha Pago</vs-th>
                  <vs-th class="text-center">Valor Cuota</vs-th>
                  <vs-th class="text-center">Seleccionar</vs-th>
                </template>
                <template slot-scope="{data}">
                  <vs-tr v-for="(datos,index) in data" :key="index">
                    <vs-td class="text-center" v-if="datos.clave_acceso && datos.pago_anticipo==null">{{(datos.clave_acceso).substring(0,3)}}-{{(datos.clave_acceso).substring(3,6)}}-{{(datos.clave_acceso).substring(6,15)}}</vs-td>
                    <vs-td class="text-center" v-else-if="datos.referencias && datos.pago_anticipo==null">{{datos.referencias}}</vs-td>
                    <vs-td class="text-center" v-else>{{datos.clave_acceso}}</vs-td>
                    <vs-td class="text-center" v-if="datos.nombre">{{datos.nombre}}</vs-td><vs-td class="text-center" v-else>-</vs-td>
                    <vs-td class="text-center" v-if="datos.num_cuota && datos.pago_anticipo==null"><vs-alert color="danger" active="true">{{datos.num_cuota}}</vs-alert></vs-td>
                    <vs-td class="text-center" v-else-if="datos.pago_anticipo!==null">Anticipo</vs-td>
                    <vs-td class="text-center" v-if="datos.fecha_pago">{{datos.fecha_pago | fecha}}</vs-td><vs-td class="text-center" v-else>-</vs-td>
                    <vs-td class="text-center" v-if="datos.valor_cuota">$ {{parseFloat(datos.valor_cuota).toFixed(2)}}</vs-td><vs-td class="text-center" v-else>-</vs-td>
                    <vs-td class="text-center" style="width: 1px;">
                      <vs-switch v-model="datos.agregar" disabled @click="valoragregadoeditar()" class="text-center">
                        <span slot="on">Agregado</span>
                        <span slot="off">No agregado</span>
                      </vs-switch>
                    </vs-td>
                  </vs-tr>
                </template>
              </vs-table>
            </div>
          </div>
        </div>
        <div class="vx-col sm:w-full w-full mb-6">
          <vs-button color="success" @click="guardar_edicion_pago()">Actualizar</vs-button>
          <vs-button color="danger" type="filled" @click="pago_edicion=!pago_edicion">Cancelar</vs-button>
        </div>
      </div>
    </vs-popup>
  </div>
</template>
<script>
import vSelect from 'vue-select'
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import { AgGridVue } from "ag-grid-vue";
import $ from 'jquery';
const axios = require("axios");
import moment from "moment";
moment.locale("es");
export default {
  components: {
    AgGridVue,
    'v-select': vSelect,
    flatPickr
  },
  data() {
    return {
      visualizar:false,
      i18nbuscar: this.$t("i18nbuscar"),
      buscar:"",
      contenido:[],
      contenido1:[],
      cliente:{},
      anticipo_asiento:0,
      //valores
      pagos_por:"",
      forma_pago:"",
      banco:"",
      //disabled buttons
      disabled_guardarpago:false,
      //
      numero_tarjeta:"",
      descuento_porcentaje:"",
      descuento_pago:"",
      valor_real:"",
      bancos:[],
      fecha_registro_pago:"",
      forma_pago_array: [
          { text: "Seleccione forma", value: "" },
          { text: "Efectivo", value: "Efectivo" },
          { text: "Cheque", value: "Cheque" },
          { text: "Tarjeta", value: "Tarjeta" },
      ],
      pago_por_array: [
          { text: "Seleccione pago por", value: "" },
          { text: "Abono", value: "Abono" },
          { text: "Cancelación", value: "Cancelación" },
          { text: "Otros", value: "Otros" }
      ],
      idre: 0,
      generarreporte: false,
      tipo_busqueda: "",
      fecha_comprobante:"",
      configdateTimePicker: {
        locale: SpanishLocale
      },
      dateinicio: "",
      datefin: "",
      cliente_busqueda: null,
      vendedor_busqueda: null,
      vendedores2: [],
      clientes2: [],
      listarcuentaslista: [],
      cobroclientes:false,
      cproveedor:{
        factura:'',
        proveedor:'',
        periodo:'',
        tiempos:null,
        plazo:null,
        monto:null,
        anticipo:false,
        formapago:null,
        transaccion:null,
        nrocheque:null,
      },
      errorcproveedor:{
        error:0,
        factura:[],
        proveedor:[],
        periodo:[],
        tiempos:[],
        plazo:[],
        monto:[],
        formapago:[],
        formapagos:[]
      },
      //valores listar anticipos
      exist_anticipos:false,
      cliente_id:null,
      list_anticipos:[],
      //editar listar anticipos
      exist_anticipos_editar:false,
      ///////////
      //variables Contabilizar
      modalAsiento:false,
      disabled_asiento:false,
      ide_ctas_pagar_pagos:"",
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
      id_forma_pago:"",
      estado_asiento:"",
      //variables Contabilizar pagos Anticipo
      modalAsiento_anticipos:false,
      disabled_asiento_anticipos:false,
      ide_ctas_pagar_pagos_anticipos:"",
      nombre_proyecto_anticipos:"",
      fecha_rol_anticipos:"",
      ruc_empresa_anticipos:"",
      razon_social_anticipos:"",
      concepto_anticipos:"",
      codigo_anticipos:"",
      productos_asiento_anticipos:[],
      iva_asiento_anticipos:[],
      pagos_sin_plc_anticipos:[],
      pagos_con_plc_anticipos:[],
      pagos_anticipo_anticipos:[],
      creditos_anticipos:[],
      retencion_iva_anticipos:[],
      retencion_renta_anticipos:[],
      pagos_anticipos:[],
      total_debe_anticipos:"",
      total_haber_anticipos:"",
      id_factura_anticipos:"",
      id_proyecto_anticipos:"",
      tipo_identificacion_anticipos:"",
      contabilizado_anticipos:null,
      modal_conciliacion_anticipos:false,
      indextipoarreglo_anticipos:"",
      nombre_pago_anticipos:"",
      id_pago_anticipos:"",
      fecha_pago_anticipos:"",
      nro_documento_anticipos:"",
      diferencia_debe_anticipos:0,
      diferencia_haber_anticipos:0,
      id_forma_pago_anticipos:"",
      estado_asiento_anticipos:"",
      //////////
      //importar
      importar:false,
      file:[],
      fecha_registro:"",

      pago_edicion:false,
      pago_editar:{
        id_ctas_pagar_pagos:null,
        pagos_por:"",
        nro_tarjeta:"",
        fecha_pago:"",
        valor_seleccionado:"",
        descuento_porcentaje:"",
        descuento_pago:"",
        valor_real_pago:"",
        id_forma_pagos:null,
        id_banco:null,
        contenido:[]
      },
      error_pago_editar:{
        pagos_por:[],
        nro_tarjeta:[],
        fecha_pago:[],
        valor_seleccionado:[],
        descuento_porcentaje:[],
        descuento_pago:[],
        valor_real_pago:[],
        id_forma_pagos:[],
        id_banco:[]
      },
      formapagos:[],
      errorpagoedicion:false,
      buscarpago:"",
      timeout:"",
      contenido2: [],
      //fitrar tabla
      filterstable: {
          contrue: [],
          filtertab: false,
          filt_asientos: true,
          filt_noasientos: true,
      }
    };
  },
  filters: {
    fecha(data) {
      return moment(data).format("LL");
    },
    decimal(data) {
      return data.toFixed(3);
    },
    formatofactura(data){
      var est = data.substring(24,27);
      var pe = data.substring(27,30);
      var fact = data.substring(30,39);
      var res = est+"-"+pe+"-"+fact
      return res;
    },
    ceros(data){
      if(data.length==4){
        return '00000'+''+data;
      }else if(data.length==3){
        return '000000'+''+data;
      }else if(data.length==2){
        return '0000000'+''+data;
      }else{
        return '00000000'+''+data;
      }
    }
  },
  computed:{
    usuario() {
      return this.$store.state.AppActiveUser;
    },
    token() {
      return this.$store.state.Token;
    },
    valor_select(){
      var total = 0;
      var fecha_comp='';
      if(this.exist_anticipos==true && this.list_anticipos.length>0){
        this.list_anticipos.forEach(el => {
          if (el.agregar) {
            total += parseFloat(el.abono);
            fecha_comp=moment(el.fecha_emision).format('YYYY-MM-DD');
          }
        });
        this.valor_real = total.toFixed(2);
        this.fecha_comprobante=fecha_comp;
        if(total.toFixed(2)<=0){
          this.descuento_porcentaje = "";
          this.descuento_pago = "";
        }
      }else{
        this.contenido.forEach(el => {
          if (el.agregar) {
            total += parseFloat(el.valor_cuota) - parseFloat(el.valor_pagado);
            fecha_comp=moment(el.fecha_factura).format('YYYY-MM-DD');
          }
        });
        this.valor_real = total.toFixed(2);
        this.fecha_comprobante=fecha_comp;
        if(total.toFixed(2)<=0){
          this.descuento_porcentaje = "";
          this.descuento_pago = "";
        }
      }
      return total.toFixed(2);
    },
    totaldeuda(){
      var total = 0;
      if(this.exist_anticipos==true){
        this.list_anticipos.forEach(el=>{
          total +=parseFloat(el.abono);
        });
      }else{
        this.contenido.forEach(el => {
          total += parseFloat(el.valor_cuota-el.valor_pagado);
        });
      }
      
      return total.toFixed(2);
    },
    //total pago proveedor actualizar
    total_cobro_actualizar(){
      var total=0
      this.contenido2.forEach(el => {
        total+=parseFloat(el.valor_cuota);
      });
      return total;
    },
    vencido(){
      var total = 0;
      if(this.exist_anticipos==true){
        this.list_anticipos.forEach(el => {
          if(el.fecha_emision < moment().format("YYYY-MM-DD")){
            total += parseFloat(el.abono);
          }
        });
      }else{
        this.contenido.forEach(el => {
          if(el.fecha_pago < moment().format("YYYY-MM-DD")){
            total += parseFloat(el.valor_cuota-el.valor_pagado);
          }
        });
      }
      
    
      return total.toFixed(2);
    },
    totaldeuda2(){
      var total = 0;
      this.contenido2.forEach(el => {
          total += parseFloat(el.valor_cuota - el.valor_pagado);
      });
      return total.toFixed(2);
    },
    vencido2(){
      var total = 0;

      this.contenido2.forEach(el => {
        if(el.fecha_pago < moment().format("YYYY-MM-DD")){
          total += parseFloat(el.valor_cuota - el.valor_pagado);
        }
      });
    
      return total.toFixed(2);
    },
    //asiento
    Diferencia(){
            if(this.total_debe>this.total_haber){
                this.diferencia_debe=this.total_haber-this.total_debe;
                console.log(this.total_debe);
            }
            if(this.total_debe<this.total_haber){
                this.diferencia_haber=this.total_debe-this.total_haber;
                console.log(this.total_haber);
            }
            if(this.total_debe_anticipos>this.total_haber_anticipos){
                this.diferencia_debe_anticipos=this.total_haber_anticipos-this.total_debe_anticipos;
                console.log(this.total_debe_anticipos);
            }
            if(this.total_debe_anticipos<this.total_haber_anticipos){
                this.diferencia_haber_anticipos=this.total_debe_anticipos-this.total_haber_anticipos;
                console.log(this.total_haber_anticipos);
            }
            console.log(this.total_debe-this.total_haber_anticipos);
        },
    suma_debe(){
        var total=0;
        var total_2=0;
        if(this.productos_asiento.length>0){
            this.productos_asiento.forEach(el => {
                if(el.debe !==null){
                    total+=parseFloat(el.debe);
                }
                
            });
        }
        if(this.iva_asiento_anticipos.length>0){
                this.iva_asiento_anticipos.forEach(el => {
                    if(el.debe !==null){
                        total_2+=parseFloat(el.debe);
                    }
                    
                });
            }
        
        this.total_debe=total.toFixed(2);
        this.total_debe_anticipos=total_2.toFixed(2);
    },
    suma_haber(){
            var total=0;
            var total_2=0;
            if(this.iva_asiento.length>0){
                this.iva_asiento.forEach(el => {
                    if(el.haber !==null){
                        total+=parseFloat(el.haber);
                    }
                    
                });
            }
            if(this.productos_asiento_anticipos.length>0){
                this.productos_asiento_anticipos.forEach(el => {
                    if(el.haber !==null){
                        total_2+=parseFloat(el.haber);
                    }
                    
                });
            }
            
            this.total_haber=total.toFixed(2);
            this.total_haber_anticipos=total_2.toFixed(2);
        },
    igualar(){
      var cambio=0;
      var cambio_2=0;
      if(this.productos_asiento.length>0){
        if(this.productos_asiento[this.productos_asiento.length-1].debe_talves!==this.total_debe){
          cambio=this.total_debe-this.productos_asiento[this.productos_asiento.length-1].debe_talves;
          this.productos_asiento[this.productos_asiento.length-1].debe=this.productos_asiento[this.productos_asiento.length-1].debe-cambio;
        }
      }
      if(this.iva_asiento.length>0){
        if(this.iva_asiento[this.iva_asiento.length-1].haber_tal!==this.total_haber){
          cambio_2=this.total_haber-this.iva_asiento[this.iva_asiento.length-1].haber_tal;
          this.iva_asiento[this.iva_asiento.length-1].haber=this.iva_asiento[this.iva_asiento.length-1].haber-cambio_2;
        }
      }
      // if(this.productos_asiento_anticipos.length>0){
      //   if(this.productos_asiento_anticipos[this.productos_asiento_anticipos.length-1].porcentaje!==this.total_haber_anticipos){
      //     cambio=this.total_haber_anticipos-this.productos_asiento_anticipos[this.productos_asiento_anticipos.length-1].porcentaje;
      //     this.productos_asiento_anticipos[this.productos_asiento_anticipos.length-1].haber=this.productos_asiento_anticipos[this.productos_asiento_anticipos.length-1].haber-cambio;
      //   }
      // }
      // if(this.iva_asiento_anticipos.length>0){
      //   if(this.iva_asiento_anticipos[this.iva_asiento_anticipos.length-1].porcentaje!==this.total_debe_anticipos){
      //     cambio_2=this.total_debe_anticipos-this.iva_asiento_anticipos[this.iva_asiento_anticipos.length-1].porcentaje;
      //     this.iva_asiento_anticipos[this.iva_asiento_anticipos.length-1].debe=this.iva_asiento_anticipos[this.iva_asiento_anticipos.length-1].debe-cambio_2;
      //   }
      // }
    },
    sumar_iguales(){
            if(this.iva_asiento.length>0){
                this.iva_asiento = this.iva_asiento.reduce((acumulador, valorActual) => {
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
            if(this.productos_asiento.length>0){
              if(this.productos_asiento[0].exist_plan_cuenta_cliente=="no"){
                  this.productos_asiento = this.productos_asiento.reduce((acumulador, valorActual) => {
                  const elementoYaExiste = acumulador.find(elemento => elemento.id_proyecto === valorActual.id_proyecto  && elemento.id_plan_cuentas_grupo === valorActual.id_plan_cuentas_grupo );
                  if (elementoYaExiste) {
                      return acumulador.map((elemento) => {
                      if (elemento.id_proyecto === valorActual.id_proyecto && elemento.id_plan_cuentas_grupo === valorActual.id_plan_cuentas_grupo) {
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
              }else{
                  this.productos_asiento = this.productos_asiento.reduce((acumulador, valorActual) => {
                  const elementoYaExiste = acumulador.find(elemento => elemento.id_proyecto === valorActual.id_proyecto  && elemento.id_plan_cuentas_cliente === valorActual.id_plan_cuentas_cliente );
                  if (elementoYaExiste) {
                      return acumulador.map((elemento) => {
                      if (elemento.id_proyecto === valorActual.id_proyecto && elemento.id_plan_cuentas_cliente === valorActual.id_plan_cuentas_cliente) {
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
            }
            if(this.iva_asiento_anticipos.length>0){
                this.iva_asiento_anticipos = this.iva_asiento_anticipos.reduce((acumulador, valorActual) => {
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
            if(this.productos_asiento_anticipos.length>0){
              if(this.productos_asiento_anticipos[0].exist_plan_cuenta_cliente=="no"){
                  this.productos_asiento_anticipos = this.productos_asiento_anticipos.reduce((acumulador, valorActual) => {
                  const elementoYaExiste = acumulador.find(elemento => elemento.id_proyecto === valorActual.id_proyecto  && elemento.id_plan_cuentas_grupo === valorActual.id_plan_cuentas_grupo );
                  if (elementoYaExiste) {
                      return acumulador.map((elemento) => {
                      if (elemento.id_proyecto === valorActual.id_proyecto && elemento.id_plan_cuentas_grupo === valorActual.id_plan_cuentas_grupo) {
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
              }else{
                  this.productos_asiento_anticipos = this.productos_asiento_anticipos.reduce((acumulador, valorActual) => {
                  const elementoYaExiste = acumulador.find(elemento => elemento.id_proyecto === valorActual.id_proyecto  && elemento.id_plan_cuentas_cliente === valorActual.id_plan_cuentas_cliente );
                  if (elementoYaExiste) {
                      return acumulador.map((elemento) => {
                      if (elemento.id_proyecto === valorActual.id_proyecto && elemento.id_plan_cuentas_cliente === valorActual.id_plan_cuentas_cliente) {
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
            }
    },
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
                    if(el.haber !==null){
                        el.haber=parseFloat(el.haber).toFixed(2);
                    }
                    
                });
            }
            if(this.productos_asiento_anticipos.length>0){
                this.productos_asiento_anticipos.forEach(el => {
                    if(el.haber !==null){
                        el.haber=parseFloat(el.haber).toFixed(2);
                    }
                    
                });
            }
            if(this.iva_asiento_anticipos.length>0){
                this.iva_asiento_anticipos.forEach(el => {
                    if(el.debe !==null){
                        el.debe=parseFloat(el.debe).toFixed(2);
                    }
                    
                });
            }
    }
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
            this.listarcuentaslista = contvar;
        }, 
    editarpago(data){
      if(data.pago_anticipo!==null){
        this.exist_anticipos_editar=true;
        console.log("entro lista anticipo");
      }else{
        this.exist_anticipos_editar=false;
        console.log("entro lista pagos");
      }
      
      this.pago_editar = {
        id_ctas_pagar_pagos:data.id_ctas_pagar_pagos,
        pagos_por:data.pagos_por,
        nro_tarjeta:data.nro_tarjeta,
        fecha_pago:data.fechageneral,
        valor_seleccionado:data.valor_real_pago,
        descuento_porcentaje:data.descuento_porcentaje,
        descuento_pago:data.descuento_pago,
        valor_real_pago:data.valor_real_pago,
        id_forma_pagos:data.id_forma_pagos,
        pago_anticipo:data.pago_anticipo,
        exist_pago_anticipo:this.exist_anticipos_editar,
        id_user:this.usuario.id,
        id_banco:data.id_banco
      }
      
      this.contenido2 =[];
      this.pago_edicion=true;
      this.llamartablavalores(data);
    },
    llamartablavalores(datos){
      
      axios.post("/api/llamartablavaloresd", datos).then(({data}) => {
        console.log(data);
        data.forEach((el,index) => {
          el.agregar = false;
        });
        data.forEach((el1,index1) => {
          datos.referencia.forEach((el,index) => {
            if(this.exist_anticipos_editar==true){
              console.log("entro detalle anticipo");
              if(index%4==1){
                  if(el == 'pp:'+el1.id_ctaspagar){
                    el1.agregar = true;
                  }
              }
            }else{
              console.log("entro detalle pagos");
              if(index%4==1){
                
                  if(el == el1.id_ctaspagar){
                    el1.agregar = true;
                  }
              }
            }
              
          });
        });
        this.contenido2 = data;
      });
    },
    valoragregadoeditar(){
      setTimeout(() => {
        var total = null;
        var select = null;
        this.contenido2.forEach(el => {
          if(el.agregar){
            var valor = parseFloat(el.valor_cuota);
            var selec = parseFloat(el.valor_cuota);
            total = total + valor;
            select = select + selec;
          }
        });
        if(total){
          this.pago_editar.valor_seleccionado = total.toFixed(2);
        }else{
          this.pago_editar.valor_seleccionado = (0).toFixed(2);
        }
        if(select){
          this.pago_editar.valor_real_pago = select.toFixed(2);
        }else{
          this.pago_editar.valor_real_pago = (0).toFixed(2);
        }
        
      }, 100);
    },
    guardar_edicion_pago(){
      if(this.validaredicionpago()){return;}
      var dsco = 0;
      if(this.pago_editar.descuento_pago){
        dsco = this.pago_editar.descuento_pago;
      }
      var pagado_total = parseFloat(dsco) + parseFloat(this.pago_editar.valor_real_pago);
      console.log(this.total_cobro_actualizar+":total cobro actualizar");
      //console.log((this.pago_editar.valor_seleccionado)+":valor_seleccionado"+(pagado_total.toFixed(2)));
      // if(this.pago_editar.valor_seleccionado < (pagado_total.toFixed(2))){
      //   console.log("El valor selecciónado es inferior al valor pagado");
      // }else{
      //   console.log("El valor selecciónado NO es inferior al valor pagado");
      // }
      // if(parseFloat(this.total_cobro_actualizar) < (pagado_total.toFixed(2))){
      //   console.log("El valor selecciónado es inferior al valor pagado");
      // }
      // return;
      if(parseFloat(this.total_cobro_actualizar) < (pagado_total.toFixed(2))){
        this.$vs.notify({
          time: 5000,
          title: "Valores erroneos",
          text: "El valor selecciónado es inferior al valor pagado",
          color: "danger"
        });
        return;
      }
      this.pago_editar.contenido = this.contenido2;
      axios.post("/api/guardar_edicion_pago_compra", this.pago_editar).then(() => {
        this.listar(this.buscar);
        this.pago_edicion=false;
        this.$vs.notify({
          time: 5000,
          title: "Datos actualizados",
          text: "Datos actualizados exitosamente",
          color: "success"
        });
      }).catch(function(error) {
        console.log(error);
      });
    },
    validaredicionpago(){
      this.error_pago_editar = {
        pagos_por:[],
        nro_tarjeta:[],
        fecha_pago:[],
        valor_seleccionado:[],
        descuento_porcentaje:[],
        descuento_pago:[],
        valor_real_pago:[],
        id_forma_pagos:[],
        id_banco:[]
      }
      this.errorpagoedicion = false;

      if(!this.pago_editar.pagos_por){
        this.error_pago_editar.pagos_por.push("Debe escoger pago");
        this.errorpagoedicion = true;
      }
      if(!this.pago_editar.id_forma_pagos){
        this.error_pago_editar.id_forma_pagos.push("Debe escoger forma de pago");
        this.errorpagoedicion = true;
      }

      return this.errorpagoedicion;
    },
    cambio_pago_pagd(){
      this.pago_editar.descuento_pago = ((this.pago_editar.valor_real_pago * this.pago_editar.descuento_porcentaje) / 100).toFixed(3);
    },
    cambio_pago_pagp(){
      this.pago_editar.descuento_porcentaje = ((this.pago_editar.descuento_pago * 100) / this.pago_editar.valor_real_pago).toFixed(3);
    },
    listar(buscar,n) {
      if(n==1){
        $("#menuescoger").show();
      }else{
        $("#menuescoger1").show();
      }
      var url = "/api/pagoproveedor?buscar=" + buscar + "&id=" + this.usuario.id_empresa;
      axios.get(url).then( res => {
        this.contenido1 = res.data;
      }).catch(function(error) {
        console.log(error);
      });
    },
    abrir(tr){
      this.visualizar = true;
      this.cliente = tr;
      this.cliente_id=tr.id_proveedor;
      console.log("ID del PROVEEDOR:");
      console.log(this.cliente_id);
      this.buscar = tr.nombre_proveedor;
      this.llamar(tr.id_proveedor);
    },
    llamar(id){
      this.idrec = id;
      axios.get("/api/cobro/"+id).then( res => {
        this.contenido = res.data;
      }).catch( err => {
        console.log(err);
      });
    },
    listarbanco() {
      axios.get("/api/traerbancofactcomp").then(response => {
        this.bancos = response.data;
      });
    },
    guardarpago(){
      this.disabled_guardarpago=true;
      console.log("Fecha Fact:"+this.fecha_comprobante);
      console.log("Fecha PAgo:"+this.fecha_registro);
      if(this.fecha_registro && this.fecha_comprobante){
        if(this.fecha_comprobante>this.fecha_registro){
          this.$vs.notify({
            time: 5000,
            title: "Debe ingresar una fecha correcta",
            text: "La fecha ingresada es anterior al de la factura",
            color: "danger"
          });
          this.disabled_guardarpago=false;
          return;
        }
      }
      axios.post("/api/agregarcobros",{
        id_cliente:this.cliente.id_proveedor,
        pagos_por: this.pagos_por,
        forma_pago: this.forma_pago,
        banco: this.banco,
        numero_tarjeta: this.numero_tarjeta,
        descuento_pago: this.descuento_pago,
        valor_real: this.valor_real,
        tabla: this.contenido,
        descuento_porcentaje: this.descuento_porcentaje,
        valor_select: this.valor_select,
        fecha_registro: this.fecha_registro,
        exist_anticipos:this.exist_anticipos,
        anticipos:this.list_anticipos,
        id_user:this.usuario.id
      }).then( res => {
        this.$vs.notify({
          time: 5000,
          title: "Pago guardado",
          text:"Se guardo el pago correctamente",
          color: "success"
        });
        this.pagos_por="";
        this.forma_pago="";
        this.banco="";
        this.numero_tarjeta="";
        this.descuento_porcentaje="";
        this.descuento_pago="";
        this.fecha_registro="";
        this.disabled_guardarpago=false;
        this.visualizar=false;
        //this.fecha_registro=moment().format('YYYY-MM-DD');
        this.exist_anticipos=false;
        this.list_anticipos=[];
        this.llamar(this.idrec);
        this.buscar='';
        this.listarcuentas("");
      }).catch( err => {
        console.log(err);
      })
    },
    //ASientos Pago cliente
    Contabilidad(id,tipo){
      if(tipo!==null){
        console.log("Entro pago anticipo");
        axios.get('/api/cuentapagar_pago_anticipo_vercontabilidad/' + id+
                        "?id_empresa=" +
                        this.usuario.id_empresa).then( ({data}) => {
                          this.anticipo_asiento=0;
                          var referencia_anticipo=data.ctas_pagar.referencia.split(';');
                          var conteo_ref_anticipo=referencia_anticipo.length/4;
                          var salto_anticipo = 0; 
                          var anticipos_array=[];
                          for(var f=0; f<conteo_ref_anticipo; f++){ 
                              anticipos_array.push(referencia_anticipo[0+salto_anticipo]);
                              salto_anticipo+=4; 
                          }
                          var nros_anticipos=anticipos_array.join();
                          if(data.ctas_pagar.fecha_registro!==null){
                            this.fecha_rol_anticipos=moment(data.ctas_pagar.fecha_registro).format("Y-MM-DD");
                          }else{
                            this.fecha_rol_anticipos=moment(data.ctas_pagar.fecha_pago).format("Y-MM-DD");
                          }
                          //this.fecha_rol=moment(data.ctas_pagar.fecha_pago).format("Y-MM-DD");
                          //var fecha=moment(this.fecha_rol).format("MMMM YYYY");
                          this.razon_social_anticipos=data.ctas_pagar.nombre;
                          this.ruc_empresa_anticipos=data.ctas_pagar.identificacion;
                          if(data.ctas_pagar.tipo_identificacion=="Cédula de Identidad"){
                              this.tipo_identificacion_anticipos="Cedula";
                          }else{
                              this.tipo_identificacion_anticipos=data.ctas_pagar.tipo_identificacion;
                          }
                          if(data.ctas_pagar.contabilidad==1){
                              this.codigo_anticipos="CAP-"+data.codigo_anterior;
                              this.contabilizado_anticipos=data.ctas_pagar.contabilidad;
                          }else{
                              this.codigo_anticipos="CAP-"+data.codigo;
                              this.contabilizado_anticipos=null;
                          }
                          this.concepto_anticipos="Cierre Anticipos Proveedor "+nros_anticipos;
                          this.modalAsiento_anticipos=true;
                          this.productos_asiento_anticipos=data.proveedor;
                          this.id_factura_anticipos=id;
                          this.iva_asiento_anticipos=data.forma_pago;
                          this.ide_ctas_pagar_pagos_anticipos=id;
                          if(this.iva_asiento_anticipos.length>0){
                            if(this.iva_asiento_anticipos[0].fecha_registro){
                              
                              this.fecha_pago_anticipos=moment(this.iva_asiento_anticipos[0].fecha_registro).format("Y-MM-DD");
                            }else{
                              
                              this.fecha_pago_anticipos=moment(this.iva_asiento[0].fecha_pago).format("Y-MM-DD");
                            }
                            this.nombre_pago_anticipos=this.iva_asiento_anticipos[0].nombre_pago;
                            this.nro_documento_anticipos=this.iva_asiento_anticipos[0].nro_tarjeta;
                            this.id_forma_pago_anticipos=this.iva_asiento_anticipos[0].id_forma_pagos;
                            this.id_proyecto_anticipos=this.iva_asiento_anticipos[0].id_proyecto;
                          }
                          //this.estado_asiento=data.asiento_permitido;

                          console.log(data.ctas_pagar.contabilidad+"HOLA");
                          //this.anticipo_asiento=1;
                
            }).catch( error => {
                console.log(error);
            });
      }else{
        this.vaciar_pago_anticipo();
        axios.get('/api/cuentapagarvercontabilidad/' + id+
                        "?id_empresa=" +
                        this.usuario.id_empresa).then( ({data}) => {
                          this.anticipo_asiento=0;
                          var referencia=data.ctas_pagar.referencia.split(';');
                          var conteo_ref=referencia.length/4;
                          var salto = 0; 
                          var factura=[];
                          var liquidacion=[];
                          var exist_facturacion="";
                          var exist_liquidacion="";
                          for(var f=0; f<conteo_ref; f++){ 
                            //console.log(referencia[3+salto]);
                            if(referencia[3+salto].indexOf("lc:")!==-1){
                              exist_liquidacion="Liquidacion";
                              liquidacion.push(referencia[0+salto]);
                            }else{
                              exist_facturacion="Factura";
                              factura.push(referencia[0+salto]);
                            }
                            
                            salto+=4; 
                          }
                          var nros_factura=factura.join();
                          var nros_lic=liquidacion.join();
                          if(data.ctas_pagar.fecha_registro!==null){
                            this.fecha_rol=moment(data.ctas_pagar.fecha_registro).format("Y-MM-DD");
                          }else{
                            this.fecha_rol=moment(data.ctas_pagar.fecha_pago).format("Y-MM-DD");
                          }
                          //this.fecha_rol=moment(data.ctas_pagar.fecha_pago).format("Y-MM-DD");
                          var fecha=moment(this.fecha_rol).format("MMMM YYYY");
                          this.razon_social=data.ctas_pagar.nombre;
                          this.ruc_empresa=data.ctas_pagar.identificacion;
                          if(data.ctas_pagar.tipo_identificacion=="Cédula de Identidad"){
                              this.tipo_identificacion="Cedula";
                          }else{
                              this.tipo_identificacion=data.ctas_pagar.tipo_identificacion;
                          }
                          if(data.ctas_pagar.contabilidad==1){
                              this.codigo="PP-"+data.codigo_anterior;
                              this.contabilizado=data.ctas_pagar.contabilidad;
                          }else{
                              this.codigo="PP-"+data.codigo;
                              this.contabilizado=null;
                          }
                          if(exist_liquidacion!==""){
                            this.concepto="Pago "+""+exist_facturacion+" "+nros_factura+","+exist_liquidacion+" "+nros_lic;
                          }else{
                            this.concepto="Pago "+""+exist_facturacion+" "+nros_factura;
                          }
                          
                          this.modalAsiento=true;
                          this.productos_asiento=data.proveedor;
                          this.id_factura=id;
                          this.iva_asiento=data.forma_pago;
                          if(this.iva_asiento.length>0){
                            if(this.iva_asiento[0].fecha_registro){
                              
                              this.fecha_pago=moment(this.iva_asiento[0].fecha_registro).format("Y-MM-DD");
                            }else{
                              
                              this.fecha_pago=moment(this.iva_asiento[0].fecha_pago).format("Y-MM-DD");
                            }
                            this.nombre_pago=this.iva_asiento[0].nombre_pago;
                            this.nro_documento=this.iva_asiento[0].nro_tarjeta;
                            this.id_forma_pago=this.iva_asiento[0].id_forma_pagos;
                            this.id_proyecto=this.iva_asiento[0].id_proyecto;
                          }
                          this.estado_asiento=data.asiento_permitido;
                          console.log(data.ctas_pagar.contabilidad+"HOLA");
                        }).catch( error => {
                            console.log(error);
                        });
      }
      
    },
    //asientos anticipo
    ContabilidadAnticipo(id,cta){
      console.log("ANticipo:"+id);
      this.vaciar_pago_anticipo();
      axios.get('/api/cuentapagar_anticipo_vercontabilidad/' + id+
                        "?id_empresa=" +
                        this.usuario.id_empresa).then( ({data}) => {
                          // var referencia=data.ctas_pagar.referencia.split(';');
                          // var conteo_ref=referencia.length/4;
                          // var salto = 0; 
                          // var factura=[];
                          // for(var f=0; f<conteo_ref; f++){ 
                          //   factura.push(referencia[0+salto]);
                          //   salto+=4; 
                          // }
                          // var nros_factura=factura.join();
                          if(data.ctas_pagar.fecha_registro!==null){
                            this.fecha_rol=moment(data.ctas_pagar.fecha_registro).format("Y-MM-DD");
                          }else{
                            this.fecha_rol=moment(data.ctas_pagar.fecha_pago).format("Y-MM-DD");
                          }
                          //this.fecha_rol=moment(data.ctas_pagar.fecha_pago).format("Y-MM-DD");
                          //var fecha=moment(this.fecha_rol).format("MMMM YYYY");
                          this.razon_social=data.ctas_pagar.nombre;
                          this.ruc_empresa=data.ctas_pagar.identificacion;
                          if(data.ctas_pagar.tipo_identificacion=="Cédula de Identidad"){
                              this.tipo_identificacion="Cedula";
                          }else{
                              this.tipo_identificacion=data.ctas_pagar.tipo_identificacion;
                          }
                          if(data.ctas_pagar.contabilidad==1){
                              this.codigo="PP-"+data.codigo_anterior;
                              this.contabilizado=data.ctas_pagar.contabilidad;
                          }else{
                              this.codigo="PP-"+data.codigo;
                              this.contabilizado=null;
                          }
                          this.concepto="Pago Factura "+data.ctas_pagar.nombre+" Anticipo";
                          this.modalAsiento=true;
                          this.productos_asiento=data.proveedor;
                          this.id_factura=id;
                          this.iva_asiento=data.forma_pago;
                          this.ide_ctas_pagar_pagos=cta;
                          if(this.iva_asiento.length>0){
                            if(this.iva_asiento[0].fecha_registro){
                              
                              this.fecha_pago=moment(this.iva_asiento[0].fecha_registro).format("Y-MM-DD");
                            }else{
                              
                              this.fecha_pago=moment(this.iva_asiento[0].fecha_pago).format("Y-MM-DD");
                            }
                            this.nombre_pago=this.iva_asiento[0].nombre_pago;
                            this.nro_documento=this.iva_asiento[0].nro_tarjeta;
                            this.id_forma_pago=this.iva_asiento[0].id_forma_pagos;
                            this.id_proyecto=this.iva_asiento[0].id_proyecto;
                          }
                          //this.estado_asiento=data.asiento_permitido;

                          console.log(data.ctas_pagar.contabilidad+"HOLA");
                          this.anticipo_asiento=1;
                
            }).catch( error => {
                console.log(error);
            });
    },
    //
    // funciones asientos pago anticipo
    agregarcampoConciliacionPago(index,tipo){
            this.modal_conciliacion_anticipos=true;
            this.indextipoarreglo_anticipos = index;
            console.log(index);
            //if(tipo=='forma_pago'){
              if(this.iva_asiento_anticipos[index].fecha_registro){
                this.fecha_pago_anticipos=moment(this.iva_asiento_anticipos[index].fecha_registro).format("Y-MM-DD");
              }else{
                this.fecha_pago_anticipos=moment(this.iva_asiento_anticipos[index].fecha_pago).format("Y-MM-DD");
              }
              this.nombre_pago_anticipos=this.iva_asiento_anticipos[index].nombre_pago;
              this.nro_documento_anticipos=this.iva_asiento_anticipos[index].nro_tarjeta;
              this.id_forma_pago_anticipos=this.iva_asiento_anticipos[index].id_forma_pagos;
            //}
             
    },
    crearasiento_anticipos(id){
            this.disabled_asiento_anticipos=true;
            var total_anticipos=0;
            total_anticipos=this.total_debe_anticipos-this.total_haber_anticipos;
            console.log("total_diferencia_anticipos:"+total_anticipos);
            if(this.validacion_asiento_anticipos()){
              this.$vs.notify({
                    color: "danger",
                    title: "Verifique los datos antes de guardar el Asiento",
                });
                this.disabled_asiento_anticipos=false;
                return;
            }
            
            if(total_anticipos!==0){
                this.$vs.notify({
                    color: "danger",
                    title: "No esta cuadrado el Asiento",
                });
                this.disabled_asiento_anticipos=false;
                return;
            }
            if(this.estado_asiento_anticipos=='no'){
                this.$vs.notify({
                    color: "danger",
                    title: "Por Cierre del Periodo no se puede guardar este Asiento con esta fecha",
                });
                this.disabled_asiento_anticipos=false;
                return;
            }
            var codigo_asiento_anticipos = this.codigo_anticipos.substr(4,this.codigo_anticipos.length);
            var fecha_hoy_anticipos=new Date();
            axios.post("/api/cuenta_pagar/agregar_anticipos_pago/asiento",{
                cod_rol:id,
                numero:codigo_asiento_anticipos,
                codigo:this.codigo_anticipos,
                fecha:this.fecha_rol_anticipos+" "+fecha_hoy_anticipos.getHours()+":"+fecha_hoy_anticipos.getMinutes()+":"+fecha_hoy_anticipos.getSeconds(),
                razon_social:this.razon_social_anticipos,
                tipo_identificacion:this.tipo_identificacion_anticipos,
                ruc_ci:this.ruc_empresa_anticipos,
                concepto:this.concepto_anticipos,
                ucrea:this.usuario.id,
                id_proyecto:this.id_proyecto_anticipos,
                anticipo:this.anticipo_asiento_anticipos,
                ide_ctas_pagar_pagos:this.ide_ctas_pagar_pagos_anticipos
            }).then(res=>{
                if(res.data=="ERROR ASIENTO"){
                        this.$vs.notify({
                            color: "danger",
                            title: "Asiento No Agregado",
                            text: "ERROR al agregar asiento"
                        });
                        this.disabled_asiento_anticipos=false;
                        return;
                }
                
                this.crearasientoDetalle_anticipos(res.data);
                this.ide_ctas_pagar_pagos_anticipos="";
            }).catch(err=>{
                this.$vs.notify({
                    color: "danger",
                    title: "Asiento No Agregado",
                    text: err
                });
                this.disabled_asiento_anticipos=false;
            });
    },
    crearasientoDetalle_anticipos(id){
        axios.post("/api/cuenta_pagar/agregar_pago_asiento/asiento_detalle",{
            proyecto:this.nombre_proyecto_anticipos,
            productos:this.productos_asiento_anticipos,
            iva_12:this.iva_asiento_anticipos,
            fecha_pago:this.fecha_pago_anticipos,
            nombre_pago:this.nombre_pago_anticipos,
            nro_documento:this.nro_documento_anticipos,
            id_forma_pago:this.id_forma_pago_anticipos,
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
            this.modalAsiento_anticipos=false;
            this.estado_asiento_anticipos="";
            this.vaciar_pago_anticipo();
            this.listarcuentas("");
            this.disabled_asiento_anticipos=false;
            //this.listar(1, this.buscar);
        }).catch(err=>{
            this.$vs.notify({
            color: "danger",
            title: "Asiento No Agregado",
            text: err
            });
            this.disabled_asiento_anticipos=false;
        });
    },
    validacion_asiento_anticipos(){
          var error=0;
          if(this.productos_asiento_anticipos.length<=0){
            error++;
            this.$vs.notify({
              color: "danger",
              title: "No existe cuenta",
            });
            console.log("productos_asiento_anticipos no hay");
          }else{
            this.productos_asiento_anticipos.forEach(el=>{
                    if(el.exist_plan_cuenta_proveedor=="si"){
                        if(el.id_plan_cuentas_proveedor==null){
                            error++;
                            this.$vs.notify({
                              color: "danger",
                              title: "No existe cuenta contable",
                            });
                            console.log("productos_asiento_anticipos cuenta_prov");
                        }
                        
                    }else{
                      if(el.id_plan_cuentas_grupo==null){
                            error++;
                            this.$vs.notify({
                              color: "danger",
                              title: "No existe cuenta contable",
                            });
                            console.log("productos_asiento_anticipos grupo_prov");
                        }
                        
                    }
                    if(el.id_proyecto==null){
                            error++;
                            this.$vs.notify({
                              color: "danger",
                              title: "No existe proyecto",
                            });
                            console.log("productos_asiento_anticipos proyecto");
                    }
                    if(el.haber==null){
                            error++;
                            console.log("productos_asiento_anticipos haber");
                    }
                });
          }
          if(this.iva_asiento_anticipos.length<=0){
            error++;
            this.$vs.notify({
              color: "danger",
              title: "No existe cuenta",
            });
            console.log("iva_asiento_anticipos no hay");
          }else{
            this.iva_asiento_anticipos.forEach(el=>{
              if(el.id_plan_cuentas==null){
                error++;
                this.$vs.notify({
                    color: "danger",
                    title: "No existe cuenta contable",
                });
                console.log("iva_asiento_anticipos cuenta");
              }
              if(el.id_proyecto==null){
                error++;
                this.$vs.notify({
                    color: "danger",
                    title: "No existe proyecto",
                });
                console.log("iva_asiento_anticipos proyecto");
              }
              if(el.debe==null){
                error++;
                console.log("productos_asiento_anticipos debe");
              }
            });
          }
          return error;
    },
    vaciar_pago_anticipo(){
      this.modalAsiento_anticipos=false;
      this.disabled_asiento_anticipos=false;
      this.ide_ctas_pagar_pagos_anticipos="";
      this.nombre_proyecto_anticipos="";
      this.fecha_rol_anticipos="";
      this.ruc_empresa_anticipos="";
      this.razon_social_anticipos="";
      this.concepto_anticipos="";
      this.codigo_anticipos="";
      this.productos_asiento_anticipos=[];
      this.iva_asiento_anticipos=[];
      this.pagos_sin_plc_anticipos=[];
      this.pagos_con_plc_anticipos=[];
      this.pagos_anticipo_anticipos=[];
      this.creditos_anticipos=[];
      this.retencion_iva_anticipos=[];
      this.retencion_renta_anticipos=[];
      this.pagos_anticipos=[];
      this.total_debe_anticipos="";
      this.total_haber_anticipos="";
      this.id_factura_anticipos="";
      this.id_proyecto_anticipos="";
      this.tipo_identificacion_anticipos="";
      this.contabilizado_anticipos=null;
      this.modal_conciliacion_anticipos=false;
      this.indextipoarreglo_anticipos="";
      this.nombre_pago_anticipos="";
      this.id_pago_anticipos="";
      this.fecha_pago_anticipos="";
      this.nro_documento_anticipos="";
      this.diferencia_debe_anticipos=0;
      this.diferencia_haber_anticipos=0;
      this.id_forma_pago_anticipos="";
      this.estado_asiento_anticipos="";
    },
    ////
    agregarcampoConciliacion(index,tipo){
            this.modal_conciliacion=true;
            this.indextipoarreglo = index;
            console.log(index);
            //if(tipo=='forma_pago'){
              if(this.iva_asiento[index].fecha_registro){
                this.fecha_pago=moment(this.iva_asiento[index].fecha_registro).format("Y-MM-DD");
              }else{
                this.fecha_pago=moment(this.iva_asiento[index].fecha_pago).format("Y-MM-DD");
              }
              this.nombre_pago=this.iva_asiento[index].nombre_pago;
              this.nro_documento=this.iva_asiento[index].nro_tarjeta;
              this.id_forma_pago=this.iva_asiento[index].id_forma_pagos;
            //}
             
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
            var codigo_asiento = this.codigo.substr(3,this.codigo.length);
            var fecha_hoy=new Date();
            axios.post("/api/cuenta_pagar/agregar/asiento",{
                cod_rol:id,
                numero:codigo_asiento,
                codigo:this.codigo,
                fecha:this.fecha_rol+" "+fecha_hoy.getHours()+":"+fecha_hoy.getMinutes()+":"+fecha_hoy.getSeconds(),
                razon_social:this.razon_social,
                tipo_identificacion:this.tipo_identificacion,
                ruc_ci:this.ruc_empresa,
                concepto:this.concepto,
                ucrea:this.usuario.id,
                id_proyecto:this.id_proyecto,
                anticipo:this.anticipo_asiento,
                ide_ctas_pagar_pagos:this.ide_ctas_pagar_pagos
            }).then(res=>{
                if(res.data=="ERROR ASIENTO"){
                        this.$vs.notify({
                            color: "danger",
                            title: "Asiento No Agregado",
                            text: "ERROR al agregar asiento"
                        });
                        this.disabled_asiento=false;
                        return;
                }
                this.crearasientoDetalle(res.data);
                this.ide_ctas_pagar_pagos="";
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
        axios.post("/api/cuenta_pagar/agregar/asiento_detalle",{
            proyecto:this.nombre_proyecto,
            productos:this.productos_asiento,
            iva_12:this.iva_asiento,
            fecha_pago:this.fecha_pago,
            nombre_pago:this.nombre_pago,
            nro_documento:this.nro_documento,
            id_forma_pago:this.id_forma_pago,
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
            this.modalAsiento=false;
            this.estado_asiento="";
            this.listarcuentas(this.buscarpago);
            this.disabled_asiento=false;
            //this.listar(1, this.buscar);
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
          if(this.productos_asiento.length<=0){
            error++;
            this.$vs.notify({
              color: "danger",
              title: "No existe cuenta",
            });
            console.log("producto_asientos no hay");
          }else{
            this.productos_asiento.forEach(el=>{
                    if(el.exist_plan_cuenta_proveedor=="si"){
                        if(el.id_plan_cuentas_proveedor==null){
                            error++;
                            this.$vs.notify({
                              color: "danger",
                              title: "No existe cuenta contable",
                            });
                            console.log("producto_asientos cuenta_prov");
                        }
                        
                    }else{
                      if(el.id_plan_cuentas_grupo==null){
                            error++;
                            this.$vs.notify({
                              color: "danger",
                              title: "No existe cuenta contable",
                            });
                            console.log("producto_asientos grupo_prov");
                        }
                        
                    }
                    if(el.id_proyecto==null){
                            error++;
                            this.$vs.notify({
                              color: "danger",
                              title: "No existe proyecto",
                            });
                            console.log("producto_asientos proyecto");
                    }
                });
          }
          if(this.iva_asiento.length<=0){
            error++;
            this.$vs.notify({
              color: "danger",
              title: "No existe cuenta",
            });
            console.log("iva_asiento no hay");
          }else{
            this.iva_asiento.forEach(el=>{
              if(el.id_plan_cuentas==null){
                error++;
                this.$vs.notify({
                  color: "danger",
                  title: "No existe cuenta contable",
                });
                console.log("iva_asiento cuenta");
              }
              if(el.id_proyecto==null){
                error++;
                this.$vs.notify({
                  color: "danger",
                  title: "No existe proyecto",
                });
                console.log("iva_asiento proyecto");
              }
            });
          }
          return error;
          
        },
    //
    //funcion generar Pdf
    descargaPdf($id,$index,pagos_por,destinatario=null,email=null){
      var index_ctas=$index+1;
      axios({
                    url: "/api/pdf/ctaxpagar",
                    method: "GET",
                    responseType: "arraybuffer",
                    params:{
                      id_cta_cobrar_pago:$id,
                      id_empresa:this.usuario.id_empresa,
                      id_user:this.usuario.id,
                      index:index_ctas,
                      destinatario:destinatario,
                      email:email,
                      pagos_por:pagos_por
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
    descargaCheque($id,$index,destinatario=null,email=null){
      var index_ctas=$index+1;
      axios({
                    url: "/api/pdf/cheque/ctaxpagar",
                    method: "GET",
                    responseType: "arraybuffer",
                    params:{
                      id_cta_cobrar_pago:$id,
                      id_empresa:this.usuario.id_empresa,
                      id_user:this.usuario.id,
                      index:index_ctas,
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
    descuentop(){
      if(this.descuento_porcentaje>100){
        this.$vs.notify({
          time: 5000,
          title: "El porcentaje de descuento no puede ser mayor al 100%",
          color: "danger"
        });
        this.descuento_porcentaje = 100;
      }
      var respuesta = ((this.valor_select * this.descuento_porcentaje) / 100).toFixed(2);
      this.descuento_pago = respuesta;

      this.valor_real = (this.valor_select - this.descuento_pago).toFixed(2);
    },
    descuentod(){
      if(this.descuento_pago > this.valor_select){
        this.$vs.notify({
          time: 5000,
          title: "El descuento no puede ser mayor al valor de pago total",
          color: "danger"
        });
        this.descuento_pago = this.valor_select;
      }
      var respuesta = ((this.descuento_pago * 100) / this.valor_select).toFixed(2);
      this.descuento_porcentaje = respuesta;
      
      this.valor_real = (this.valor_select - this.descuento_pago).toFixed(2);  
    },
    valortotalr(){
      var n1=Number(this.valor_real);
      var n2=Number(this.valor_select);
      // n1=parseFloat(n1).toFixed(2);
      // n2=parseFloat(n2).toFixed(2);
      
      if(n1 > n2){
        console.log(this.valor_real +" valor_seleccionado: "+this.valor_select);
        this.$vs.notify({
          time: 5000,
          title: "El valor pagado no puede ser mayor al valor seleccionado a pagar",
          color: "danger"
        });
        this.valor_real = this.valor_select;
      }
    },
    reportes_factura() {
      window.open(
        "/api/reportes/cuentas-por-pagar?tipo_busqueda=" +
          this.tipo_busqueda +
          "&dateinicio=" +
          this.dateinicio +
          "&datefin=" +
          this.datefin +
          "&cliente_busqueda=" +
          this.cliente_busqueda +
          "&vendedor_busqueda=" +
          this.vendedor_busqueda,
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
      this.cliente_busqueda = null;
      this.vendedor_busqueda = null;
    },
    /*getClientes() {
      axios.get("/api/clientes/" + this.usuario.id_empresa).then( res => {
          this.formapagos = res.data;
      }).catch( err => {
          console.log(err);
      });
    },*/
    listarformapagos(){
      axios.get("/api/facturaformapagos/" + this.usuario.id_empresa).then( res => {
          this.formapagos = res.data;
      }).catch( err => {
          console.log(err);
      });
    },

    listarcuentas(buscarpago){
      if (this.timeout) {  
        clearTimeout(this.timeout);
      }
      this.timeout =  setTimeout(() => {
        axios.get("/api/listarcuentasplista?id=" + this.usuario.id_empresa+"&buscar="+buscarpago).then(({data}) => {
          this.listarcuentaslista = data;
            this.filterstable.contrue = data;
            this.filtertabla();
        }).catch( error => {
          console.log(error);
        });
      }, 800);
    },
    eliminarcxc(id) {
        this.$vs.dialog({
            type: "confirm",
            color: "danger",
            title: `¿Desea Eliminar este registro?`,
            text: `Este registro de pago sera cancelado del sistema`,
            acceptText: "Aceptar",
            cancelText: "Cancelar",
            accept: this.aceptarborrado,
            parameters: id
        });
    },
    aceptarborrado(parameters) {
        axios.delete("/api/eliminarcxp/" + parameters);
        this.$vs.notify({
            color: "warning",
            title: "Cobro Cancelado",
            text: "El Cobro selecionado fue cancelado con exito"
        });
        this.listarcuentas(this.buscarpago);
    },

    abrirModal(){
      this.cobroclientes = true;
    },
    guardar(){
      if(this.validarguardado()){ return; }
      axios.post("/api/guardarpagoscompra",{
        cproveedor: this.cproveedor,
        proveedor: this.cliente,
        fecha_registro_pago: this.fecha_registro_pago,
        empresa:this.usuario.id_empresa,
        id_user:this.usuario.id
      }).then( res => {
        if(res.data=='error'){
          this.$vs.notify({
            time: 5000,
            title: "Factura inexistente",
            text:"Ingrese una factura existente en el sistema",
            color: "danger"
          });
          return;
        }
        this.$vs.notify({
          time: 5000,
          title: "Registro guardado",
          text:"El registro se guardo exitosamente",
          color: "success"
        });
        this.abrir(this.cliente);
        this.cproveedor = {
          factura:'',
          proveedor:'',
          periodo:'',
          tiempos:null,
          plazo:null,
          monto:null,
          anticipo:false,
          formapago:null,
        },
        this.cobroclientes=false;
      }).catch( err => {
        console.log(err);
      })
    },
    validarguardado(){
      this.errorcproveedor ={
        error:0,
        factura:[],
        proveedor:[],
        periodo:[],
        tiempos:[],
        plazo:[],
        monto:[],
        formapago:[]
      };
      if(!this.cproveedor.anticipo){
        if(this.cproveedor.factura.length<15){
          this.errorcproveedor.factura.push("agrege una factura correcta");
          this.errorcproveedor.error = 1;
        }
        if(!this.cproveedor.periodo){
          this.errorcproveedor.periodo.push("Escoga periodo");
          this.errorcproveedor.error = 1;
        }
        if(!this.cproveedor.tiempos){
          this.errorcproveedor.tiempos.push("Escoga tiempo");
          this.errorcproveedor.error = 1;
        }
        if(!this.cproveedor.plazo){
          this.errorcproveedor.plazo.push("Escoga plazo");
          this.errorcproveedor.error = 1;
        }
      }else{
        if(!this.cproveedor.formapago){
          this.errorcproveedor.formapago.push("Escoga forma de pago");
          this.errorcproveedor.error = 1;
        }
      }
      if(!this.cliente.id_proveedor){
        this.errorcproveedor.proveedor.push("Escoga proveedor");
        this.errorcproveedor.error = 1;
      }
      if(!this.cproveedor.monto){
        this.errorcproveedor.monto.push("Escoga monto");
        this.errorcproveedor.error = 1;
      }

      return this.errorcproveedor.error;
    },
    anticipo(){
      if(!this.cproveedor.anticipo){
        this.cproveedor = {
          factura:'',
          periodo:'',
          tiempos:null,
          plazo:null,
        }
      }
      this.errorcproveedor ={
        error:0,
        factura:[],
        proveedor:[],
        periodo:[],
        tiempos:[],
        plazo:[],
        monto:[],
      };
    },
    //importar excel
    subirArchivo(e) {
      this.file = []
      let tempFile = e.target.files[0];
      var allowedExtensions = /(.csv|.xls|.xlsx)$/i;
      if (!allowedExtensions.exec(tempFile.name)) {
          this.$vs.notify({
              title: "Tipo de archivo no compatible",
              text: "Formatos aceptados: .csv, .xls, .xlsx",
              color: "danger",
          });
          return;
      }
      this.file.push(tempFile);
    },
    importarexcel1() {
      $(".inputexcel1").click();
    },
    crear_importacion() {
      let formData = new FormData();
      formData.append("id_empresa", this.usuario.id_empresa);
      formData.append("file", this.file[0]);
      axios.post("/api/importarcuentaspagar", formData, {}).then((res) => {
        document.getElementById("input-upload").value = "";
        this.$vs.notify({text: "Archivo Importado con exito",color: "success",});
        this.importar = false;
        this.listar(this.buscar);
        this.file = [];
      }).catch((err) => {
        console.log(err);
        this.$vs.notify({text: "Archivo Importado 'sin exito'",color: "danger",});
        this.importar = false;
        this.listar(this.buscar);
        this.file = [];
      });
    },
    cancelar_importacion() {
      this.importar = false;
      this.file = [];
      document.getElementById("input-upload").value = "";
    },
    //listar los anticipos del cliente
    listar_anticipos_cliente(){
      if(this.exist_anticipos==true){
          this.list_anticipos=[];
          this.pagos_por="";
          this.forma_pago="";
          this.banco="";
          this.numero_tarjeta="";
          this.descuento_porcentaje="";
          this.descuento_pago="";
          this.fecha_registro="";
          this.llamar(this.cliente_id);
      }else{
          this.list_anticipos=[];
          this.pagos_por="";
          this.forma_pago="";
          this.banco="";
          this.numero_tarjeta="";
          this.descuento_porcentaje="";
          this.descuento_pago="";
          this.fecha_registro="";
        axios.get("/api/listar_anticipos/proveedor/"+this.cliente_id)
        .then(resp=>{
            this.list_anticipos=resp.data;
            console.log(this.list_anticipos);
        }).catch(err=>{
          console.log("[ERROR:: traer anticipos]:"+err);
        });
      }
      
    }
  },
  mounted(){
    this.listarbanco();
    //this.getClientes();
    this.listarformapagos();
    this.listarcuentas(this.buscarpago);
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
.peque2 .vs-popup {
        width: 1080px !important;
    }
.menuescoger{
    position: absolute;
    margin-top: -11px;
    width: 100%;
    background: #fff;
    z-index: 999;
    border: 1px solid #dfdfdf;
    border-radius: 0 0 8px 8px;
}

.menuescoger ul{
    list-style: none;
    padding: 8px 15px 25px 15px;
    margin: 0;
    cursor: pointer;
    color: #848484;
    font-weight: 600;
    font: 14px arial,sans-serif;
    position: relative;
    border-bottom: 1px solid #eaeaea;
}

.menuescoger ul:hover{
    background: rgba(0,0,0,.1);
}

.menuescoger span{
    font-size: 12px;
}
.posicion{ 
  position: absolute;
  left: 15px;
  top: 27px;
}
.posicion span{
  font-size: 10px;
}
.text-center .vs-table-text{
  text-align: center;
  display: block;
}
.text-center input{
  text-align: center;
}
//importar
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
.con-vs-dropdown--menu {
    z-index: 9999999999999999;
}
.peque2 .vs-popup {
    width: 500px !important;
}
.peque3 .vs-popup {
    width: 1080px !important;
}
</style>
