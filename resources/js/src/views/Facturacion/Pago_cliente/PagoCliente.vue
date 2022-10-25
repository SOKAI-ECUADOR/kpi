<template>
  <div id="ag-grid-demo">
    <vx-card>
      <div class="flex flex-wrap justify-between items-center mb-3">
        <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-left">
          <div class="row mb-2" style="display: flex;">
            <div class="col-xl-6 col-lg-12 justify-content-end clicker" style="position: relative;">
                <div class="input-group mb-3" style="width: 100%;">
                    <input type="text" class="vs-inputx vs-input--input normal" style="width:450px;max-width:100%;" placeholder="Buscar Clientes..." aria-describedby="basic-addon2"  v-model="buscar" @keyup="listar(buscar)"/>
                    <div class="input-group-append">
                    <span class="input-group-text" id="basic-addon2">
                        <i class="fas fa-search"></i>
                    </span>
                    </div>
                </div>
                <div class="menuescoger busqueda_lista busqueda_cliente_ls" id="menuescoger" v-if="buscar">
                    <template v-if="contenido1.length">
                        <ul v-for="(tr,index) in contenido1" :key="index" @click="abrir(tr),listarrecibonro()">
                            <li>
                                {{tr.nombre}}
                                <span class="posicion">
                                    <template v-if="tr.identificacion"><span>Cédula:  {{tr.identificacion}} </span> | </template>
                                    <template v-if="tr.email"><span>Email:  {{tr.email}} </span> | </template>
                                    <template v-if="tr.telefono"><span>Telefono: {{tr.telefono}} </span> </template>
                                </span>
                            </li>
                        </ul>
                    </template>
                    <template v-else>
                        <ul style="padding: 7px;text-align: center;">
                            <li>
                            ESTE CLIENTE NO EXISTE EN NUESTROS REGISTROS
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
          <vs-input class="mb-4 md:mb-0 mr-4" v-model="buscarpago" @keyup="listarcuentas(buscarpago)" placeholder="Buscar pago realizado..."/>
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
                    <vs-th class="text-center">Cliente</vs-th>
                    <vs-th class="text-center">Fecha Pago</vs-th>
                    <vs-th class="text-center">Forma Pago</vs-th>
                    <vs-th class="text-center">Descuento</vs-th>
                    <vs-th class="text-center">Pago</vs-th>
                    <vs-th class="text-center">Opciones</vs-th>
                </template>
                <template slot-scope="{ data }">
                    <vs-tr v-for="(datos, index) in data" :key="index">
                        <template v-if="datos.tipo==3">
                            <vs-td class="text-center">{{datos.posicion}}</vs-td>
                            <vs-td class="text-center">Anticipo</vs-td>
                            <vs-td class="text-center" v-if="datos.nombre">{{ datos.nombre }}</vs-td><vs-td class="text-center" v-else>-</vs-td>
                            <vs-td class="text-center" v-if="datos.fecha_registro">{{datos.fecha_registro | fecha}}</vs-td>
                            <vs-td class="text-center" v-else>{{datos.fecha_pago | fecha}}</vs-td>
                            <vs-td class="text-center" v-if="datos.descripcion">{{ datos.descripcion }}</vs-td><vs-td class="text-center" v-else>-</vs-td>
                            <vs-td class="text-center">Anticipo</vs-td>
                            <vs-td class="text-center">${{ datos.valor_cuota }}</vs-td>
                            <vs-td class="text-center">
                                <vs-dropdown vs-custom-content vs-trigger-click class="pointer">
                                    <vs-button class="btn-drop pointer" type="filled" icon="expand_more">Acciones</vs-button>
                                    <vs-dropdown-menu style="width:13em;">
                                        <vs-dropdown-item class="text-center" @click="editar_abono(datos)">
                                            <feather-icon icon="EditIcon" svgClasses="w-5 h-5"></feather-icon> Editar
                                        </vs-dropdown-item>
                                        <vs-dropdown-item divider class="text-center" @click="eliminarabono(datos.id_ctascobrar)">
                                            <feather-icon icon="TrashIcon" svgClasses="w-5 h-5"></feather-icon> Eliminar
                                        </vs-dropdown-item>
                                        <vs-dropdown-item divider class="text-center"  @click="reciboCobro(datos.id_ctascobrar,index,'Anticipo')">
                                          <feather-icon icon="ArchiveIcon" svgClasses="w-5 h-5"></feather-icon> Recibo
                                        </vs-dropdown-item>
                                    </vs-dropdown-menu>
                                </vs-dropdown>
                            </vs-td>
                            <vs-td class="text-center">
                                <feather-icon icon="SlidersIcon" class="cursor-pointer" v-if="datos.contabilidad!==null" svgClasses="w-5 h-5 fill-current text-success" @click="ContabilidadAnticipo(datos.id_ctascobrar)"/>
                                <feather-icon icon="SlidersIcon" class="cursor-pointer" v-else svgClasses="w-5 h-5 fill-current text-primary" @click="ContabilidadAnticipo(datos.id_ctascobrar)"/>
                                <feather-icon icon="CheckIcon"  v-if="datos.contabilidad!==null" svgClasses="w-5 h-5"/>
                            </vs-td>
                        </template>
                        <template v-else>
                            <vs-td class="text-center">{{datos.posicion}}</vs-td>
                            <vs-td class="text-center" v-if="datos.pagos_por">{{ datos.pagos_por }}</vs-td><vs-td class="text-center" v-else>-</vs-td>
                            <vs-td class="text-center" v-if="datos.nombrecliente">{{ datos.nombrecliente }}</vs-td><vs-td class="text-center" v-else>-</vs-td>
                            <vs-td class="text-center" v-if="datos.fecha_registro">{{datos.fecha_registro | fecha}}</vs-td>
                            <vs-td class="text-center" v-else>{{datos.fecha_pago | fecha}}</vs-td>
                            <vs-td class="text-center" v-if="datos.descripcionsri">{{ datos.descripcionsri }}</vs-td><vs-td class="text-center" v-else>-</vs-td>
                            <vs-td class="text-center" v-if="datos.descuento_pago">${{ datos.descuento_pago }}</vs-td><vs-td class="text-center" v-else>-</vs-td>
                            <vs-td class="text-center" v-if="datos.valor_real_pago">${{ datos.valor_real_pago }}</vs-td><vs-td class="text-center" v-else>-</vs-td>
                            <vs-td class="text-center">
                                <vs-dropdown vs-custom-content vs-trigger-click class="pointer">
                                    <vs-button class="btn-drop pointer" type="filled" icon="expand_more">Acciones</vs-button>
                                    <vs-dropdown-menu style="width:13em;">
                                        <vs-dropdown-item class="text-center" v-if="datos.contabilidad==null" @click="editarpago(datos)"><feather-icon icon="EditIcon" svgClasses="w-5 h-5"></feather-icon> Editar</vs-dropdown-item>
                                        <vs-dropdown-item divider class="text-center" @click="descargaPdf(datos.id_ctas_cobrar_pagos,index)"><feather-icon icon="DownloadIcon" svgClasses="w-5 h-5"></feather-icon> Descargar PDF</vs-dropdown-item>
                                        <vs-dropdown-item divider class="text-center" v-if="datos.contabilidad==null" @click="eliminarcxc(datos.id_ctas_cobrar_pagos)"><feather-icon icon="TrashIcon" svgClasses="w-5 h-5"></feather-icon> Eliminar</vs-dropdown-item>
                                        <vs-dropdown-item divider class="text-center"  @click="reciboCobro(datos.id_ctas_cobrar_pagos,index,'Cobro')"><feather-icon icon="ArchiveIcon" svgClasses="w-5 h-5"></feather-icon> Recibo</vs-dropdown-item>
                                    </vs-dropdown-menu>
                                </vs-dropdown>

                            </vs-td>
                            <vs-td class="text-center">
                            <feather-icon icon="SlidersIcon" class="cursor-pointer" v-if="datos.contabilidad!==null" svgClasses="w-5 h-5 fill-current text-success" @click="Contabilidad(datos.id_ctas_cobrar_pagos)"/>
                            <feather-icon icon="SlidersIcon" class="cursor-pointer" v-else svgClasses="w-5 h-5 fill-current text-primary" @click="Contabilidad(datos.id_ctas_cobrar_pagos)"/>
                            <feather-icon icon="CheckIcon"  v-if="datos.contabilidad!==null" svgClasses="w-5 h-5"/>
                            </vs-td>
                        </template>
                    </vs-tr>
                </template>
              </vs-table>
          </div>
        </div>
      </div>
      <div v-else>
          <div class="vx-row mt-5">
            <vs-divider border-style="solid" color="dark">Agregar pago de las cuentas por cobrar</vs-divider>
            <div class="vx-col sm:w-full w-full mb-6 text-center">
                <h6 class="mt-4">Nro Comprobante: {{nro_secuencial}}</h6>
            </div>
            <!-- <div class="vx-col sm:w-1/12 w-full mb-2">
              <h6>Anticipos</h6>
              <vs-checkbox v-model="exist_anticipos" @click="listar_anticipos_cliente()"></vs-checkbox>
            </div> -->
            <div class="vx-col xl:w-1/5 md:w1/2 sm:w-full mb-2">
              <h6>Pagos Por:</h6>
              <vs-select autocomplete class="selectExample w-full" v-model="pagos_por">
                <vs-select-item :key="index" :value="item.value" :text="item.text" v-for="(item,index) in pago_por_array"/>
              </vs-select>
              <div v-show="errores.error">
                <span class="text-danger" v-for="(err, index) in errores.errorpagos_por" :key="index" v-text="err"></span>
              </div>
            </div>
            <div class="vx-col sm:w-1/5 w-full mb-2">
              <h6>Forma de Pago:</h6>
              <vs-select placeholder="buscar" autocomplete class="selectExample w-full" v-model="forma_pago">
                <vs-select-item v-for="(tr,index) in formapagos" :key="index" :value="tr.id_forma_pagos" :text="tr.descripcion"/>
              </vs-select>
              <div v-show="errores.error">
                <span class="text-danger" v-for="(err,index) in errores.errorforma_pago" :key="index" v-text="err"></span>
              </div>
            </div>
            <div class="vx-col xl:w-1/5 md:w1/2 sm:w-full mb-2">
              <h6>Banco:</h6>
              <vs-select class="selectExample w-full" vs-multiple v-model="banco">
                  <vs-select-item value="" text="Seleccione el banco"/>
                  <vs-select-item v-for="data in bancos" :key="data.id_banco" :value="data.id_banco" :text="data.nombre_banco"/>
              </vs-select>
            </div>
            <div class="vx-col xl:w-1/5 md:w1/2 sm:w-full mb-2">
              <h6>Nro. Cheque tarjeta:</h6>
              <vs-input class="w-full" v-model="numero_tarjeta" />
            </div>
            <div class="vx-col xl:w-1/5 md:w1/2 sm:w-full mb-2">
              <h6>Fecha:</h6>
              <flat-pickr :config="configdateTimePicker" class="w-full" :disabled="usuario.id_empresa==34 && usuario.id_rol==2" v-model="fecha_registro" placeholder="Seleccionar"></flat-pickr>
            </div>
            <div class="vx-col xl:w-1/6 mt-6 md:w1/2 sm:w-full mb-2" :class="{'xl:w-1/6':pagos_por=='Anticipo'}">
              <h6>Valor Selec.:</h6>
              <vs-input class="w-full text-center" placeholder="0.00" v-bind:value="valor_select" disabled/>
            </div>
            <div class="vx-col xl:w-1/6 mt-6 md:w1/2 sm:w-full mb-2">
              <h6>% Desc:</h6>
              <vs-input class="w-full text-center" placeholder="0.00" :disabled="valor_select<=0" v-model="descuento_porcentaje" @keyup="descuentop()"/>
            </div>
            <div class="vx-col xl:w-1/6 mt-6 md:w1/2 sm:w-full mb-2">
              <h6>Desc. Pago:</h6>
              <vs-input class="w-full text-center" placeholder="0.00" :disabled="valor_select<=0" v-model="descuento_pago" @keyup="descuentod()"/>
            </div>
            <div class="vx-col xl:w-1/6 mt-6 md:w1/2 sm:w-full mb-2" :class="{'xl:w-1/6':pagos_por=='Anticipo'}">
              <h6>Valor real Pago:</h6>
              <vs-input class="w-full text-center" placeholder="0.00" :disabled="valor_select<=0" v-model="valor_real" @blur="valortotalr()"/>
            </div>
            <div class="vx-col xl:w-1/6 mt-6 md:w1/2 sm:w-full mb-2" :class="{'xl:w-1/6':pagos_por=='Anticipo'}" v-if="pagos_por=='Anticipo'">
              <h6>Anticipo existente:</h6>
              <vs-input class="w-full text-center" disabled :value="anticipoexistente"/>
            </div>
            <div class="vx-col xl:w-1/5 mt-6 md:w1/2 sm:w-full mb-2" :class="{'xl:w-1/6':pagos_por=='Anticipo'}" style="margin-top: 2.6rem!important;">
              <vs-button color="success" type="filled" :disabled="disabled_guardar_pago" @click="guardarpago()">GUARDAR</vs-button>
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
              <vs-table stripe max-items="25" pagination :data="contenido">
                <template slot="thead">
                  <vs-th class="text-center">N° factura</vs-th>
                  <vs-th class="text-center">Cliente</vs-th>
                  <vs-th class="text-center">Num_cuota</vs-th>
                  <vs-th class="text-center">Fecha Pago</vs-th>
                  <vs-th class="text-center">Valor Cuota</vs-th>
                  <vs-th class="text-center">Seleccionar</vs-th>
                </template>
                <template slot-scope="{data}">
                  <vs-tr v-for="(datos,index) in data" :key="index">
                    <vs-td class="text-center" v-if="datos.clave_acceso">{{ datos.clave_acceso | formatofactura }}</vs-td><vs-td class="text-center" v-else-if="datos.referencias">{{datos.referencias}}</vs-td><vs-td class="text-center" v-else>-</vs-td>
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
                  <vs-th class="text-center">Cliente</vs-th>
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
    <vs-popup classContent="popup-example" title="Agregar Cobro clientes" :active.sync="cobroclientes">
        <div class="vx-row">
          <div class="vx-col sm:w-2/5 mb-6">
            <label class="vs-input--label">Número de factura</label>
            <vs-input class="inputx w-full" :disabled="ccliente.anticipo" maxlength='15' placeholder="Ingresar el número de factura" v-model="ccliente.factura"/>
            <div v-show="errorccliente.error">
              <span class="text-danger" v-for="(err,index) in errorccliente.factura" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="col-xl-6 sm:w-2/5 col-lg-12 justify-content-end clicker mb-6" style="position: relative;">
              <label class="vs-input--label">Cliente</label>
              <div class="input-group" style="width: 100%;">
                  <input type="text" class="vs-inputx vs-input--input normal" style="width:450px;max-width:100%;" placeholder="Buscar Pacientes para crear consulta..." aria-describedby="basic-addon2"  v-model="buscar_cliente" @keyup="listarClienteAgregar(buscar_cliente)"/>
                  <div class="input-group-append">
                  <span class="input-group-text" id="basic-addon2">
                      <i class="fas fa-search"></i>
                  </span>
                  </div>
              </div>
              <div class="menuescoger busqueda_lista busqueda_cliente_ls" id="menuescoger1" v-if="buscar_cliente">
                  <template v-if="contenido2.length">
                      <ul v-for="(tr,index) in contenido2" :key="index" @click="abrirCliente(tr)" class="ul_busqueda_lista">
                          <li>
                              {{tr.nombre}}
                              <span class="posicion">
                                  <template v-if="tr.identificacion"><span>Cédula:  {{tr.identificacion}} </span> | </template>
                                  <template v-if="tr.email"><span>Email:  {{tr.email}} </span> | </template>
                                  <template v-if="tr.telefono"><span>Telefono: {{tr.telefono}} </span> </template>
                              </span>
                          </li>
                      </ul>
                  </template>
                  <template v-else>
                      <ul style="padding: 7px;text-align: center;">
                          <li>
                          ESTE CLIENTE NO EXISTE EN NUESTROS REGISTROS
                          </li>
                      </ul>
                  </template>
              </div>
              <div v-show="errorccliente.error">
                <span class="text-danger" v-for="(err,index) in errorccliente.cliente" :key="index" v-text="err"></span>
              </div>
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <label class="vs-input--label">Anticipo</label>
            <vs-checkbox icon-pack="feather" class="mt-3" icon="icon-check" v-model="ccliente.anticipo" @click="anticipo()">
                <template v-if="ccliente.anticipo">
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
            <vs-select placeholder="Selecciona el periodo de pago" :disabled="ccliente.anticipo" autocomplete class="selectExample w-full" v-model="ccliente.periodo">
                <vs-select-item value="Dias" text="Dias" />
                <vs-select-item value="Semanas" text="Semanas" />
                <vs-select-item value="Meses" text="Meses" />
                <vs-select-item value="Años" text="Años" />
            </vs-select>
            <div v-show="errorccliente.error">
              <span class="text-danger" v-for="(err,index) in errorccliente.periodo" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/5 mb-6" v-if="!ccliente.anticipo">
            <label class="vs-input--label">Tiempos Pago</label>
            <vs-input class="inputx w-full" :disabled="ccliente.anticipo" placeholder="Ingresar los tiempos" v-model="ccliente.tiempos"/>
            <div v-show="errorccliente.error">
              <span class="text-danger" v-for="(err,index) in errorccliente.tiempos" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/5 mb-6" v-if="!ccliente.anticipo">
            <label class="vs-input--label">Plazos de pago</label>
            <vs-select placeholder="Seleccione" :disabled="ccliente.anticipo" autocomplete class="selectExample w-full" v-model="ccliente.plazo">
                <vs-select-item v-for="(v, index) in 24" :key="index" :value="v" :text="v + ' Periodos'"/>
            </vs-select>
            <div v-show="errorccliente.error">
              <span class="text-danger" v-for="(err,index) in errorccliente.plazo" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-2/6 mb-6" v-if="ccliente.anticipo">
            <label class="vs-input--label">Forma de pago</label>
            <vs-select placeholder="buscar" autocomplete class="selectExample w-full" v-model="ccliente.formapago">
              <vs-select-item v-for="(tr,index) in formapagos" :key="index" :value="tr.id_forma_pagos" :text="tr.descripcion"/>
            </vs-select>
            <div v-show="errorccliente.error">
              <span class="text-danger" v-for="(err,index) in errorccliente.formapago" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/5 mb-6" v-if="ccliente.anticipo">
            <label class="vs-input--label">Nro. Cheque</label>
            <vs-input class="inputx w-full" placeholder="Ingresar el monto" v-model="ccliente.nrocheque"/>
          </div>
          <div class="vx-col sm:w-1/6 mb-6">
            <label class="vs-input--label">Monto de pago</label>
            <vs-input class="inputx w-full" placeholder="Ingresar el monto" v-model="ccliente.monto"/>
            <div v-show="errorccliente.error">
              <span class="text-danger" v-for="(err,index) in errorccliente.monto" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col xl:w-1/5 md:w1/2 sm:w-full mb-2">
              <label class="vs-input--label">Fecha:</label>
              <flat-pickr :config="configdateTimePicker" class="w-full" v-model="fecha_registro_pago" placeholder="Seleccionar"></flat-pickr>
            </div>
        </div>
        <div class="vx-col w-full mt-6">
          <vs-button color="success" type="filled" :disabled="disabled_agregar_cobro" @click="guardar()">GUARDAR</vs-button>
          <vs-button color="danger" type="filled" @click="cobroclientes=false">CANCELAR</vs-button>
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
                                <!-- prettier-ignore -->
                                <vs-input
                                    class="w-full"
                                    v-model="data.nombre_cuenta_cliente"
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
      <div class="vx-col sm:w-full w-full mb-6 text-center">
          <h6 class="mt-4">Nro Comprobante: {{nro_secuencial}}</h6>
      </div>
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
              <vs-input class="w-full" v-model="pago_editar.numero_tarjeta" />
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
              <vs-input class="w-full text-center" placeholder="0.00" v-model="pago_editar.valor_real_pago"/>
            </div>
            <div class="vx-col w-full mt-6" hidden>
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
            </div>
            <div class="vx-col w-full mt-6">
              <vs-table stripe max-items="25" pagination :data="contenido2">
                <template slot="thead">
                  <vs-th class="text-center">N° factura</vs-th>
                  <vs-th class="text-center">Cliente</vs-th>
                  <vs-th class="text-center">Num_cuota</vs-th>
                  <vs-th class="text-center">Fecha Pago</vs-th>
                  <vs-th class="text-center">Valor Cuota</vs-th>
                  <vs-th class="text-center">Seleccionar</vs-th>
                </template>
                <template slot-scope="{data}">
                  <vs-tr v-for="(datos,index) in data" :key="index">
                    <vs-td class="text-center" v-if="datos.clave_acceso">{{ datos.clave_acceso | formatofactura }}</vs-td><vs-td class="text-center" v-else-if="datos.referencias">{{datos.referencias}}</vs-td><vs-td class="text-center" v-else>-</vs-td>
                    <vs-td class="text-center" v-if="datos.nombre">{{datos.nombre}}</vs-td><vs-td class="text-center" v-else>-</vs-td>
                    <vs-td class="text-center" v-if="datos.num_cuota"><vs-alert color="danger" active="true">{{datos.num_cuota}}</vs-alert></vs-td><vs-td class="text-center" v-else>-</vs-td>
                    <vs-td class="text-center" v-if="datos.fecha_pago">{{datos.fecha_pago | fecha}}</vs-td><vs-td class="text-center" v-else>-</vs-td>
                    <vs-td class="text-center" v-if="datos.valor_cuota">$ {{parseFloat(datos.valor_cuota).toFixed(2)}}</vs-td><vs-td class="text-center" v-else>-</vs-td>
                    <vs-td class="text-center" style="width: 1px;">
                      <vs-switch v-model="datos.agregar" @click="valoragregadoeditar()"  class="text-center">
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
          <vs-button color="success" :disabled="disabled" @click="guardar_edicion_pago()">Actualizar</vs-button>
          <vs-button color="danger" type="filled" @click="pago_edicion=!pago_edicion">Cancelar</vs-button>
        </div>
      </div>
    </vs-popup>
    <!--Editar abono-->
    <vs-popup classContent="popup-example" title="Actualizar abono" :active.sync="pago_abono.estado">
        <div class="vx-col sm:w-full w-full mb-6 text-center">
            <h6 class="mt-4">Nro Comprobante: {{nro_secuencial}}</h6>
        </div>
        <div class="vx-row">
          <div class="vx-col sm:w-2/3 mb-6" style="position: relative;">
              <label class="vs-input--label">Cliente</label>
              <div class="input-group" style="width: 100%;">
                  <input type="text" class="vs-inputx vs-input--input normal w-full" style="width:450px;max-width:100%;" placeholder="Buscar Pacientes para crear consulta..." aria-describedby="basic-addon2"  v-model="pago_abono.buscar" @keyup="listar(buscar)"/>
                  <div class="input-group-append">
                  <span class="input-group-text" id="basic-addon2">
                      <i class="fas fa-search"></i>
                  </span>
                  </div>
              </div>
              <div class="menuescoger busqueda_lista busqueda_cliente_ls" id="menuescoger1" v-if="pago_abono.buscar">
                  <template v-if="contenido1.length">
                      <ul v-for="(tr,index) in contenido1" :key="index" @click="abrir(tr)" class="ul_busqueda_lista">
                          <li>
                              {{tr.nombre}}
                              <span class="posicion">
                                  <template v-if="tr.identificacion"><span>Cédula:  {{tr.identificacion}} </span> | </template>
                                  <template v-if="tr.email"><span>Email:  {{tr.email}} </span> | </template>
                                  <template v-if="tr.telefono"><span>Telefono: {{tr.telefono}} </span> </template>
                              </span>
                          </li>
                      </ul>
                  </template>
                  <template v-else>
                      <ul style="padding: 7px;text-align: center;">
                          <li>
                          ESTE CLIENTE NO EXISTE EN NUESTROS REGISTROS
                          </li>
                      </ul>
                  </template>
              </div>
              <div v-show="pago_abono.error.error">
                <span class="text-danger" v-for="(err,index) in pago_abono.error.cliente" :key="index" v-text="err"></span>
              </div>
          </div>
          <div class="vx-col sm:w-1/3 mb-6">
            <label class="vs-input--label">Forma de pago</label>
            <vs-select placeholder="buscar" autocomplete class="selectExample w-full" v-model="pago_abono.formapago">
              <vs-select-item v-for="(tr,index) in formapagos" :key="index" :value="tr.id_forma_pagos" :text="tr.descripcion"/>
            </vs-select>
            <div v-show="pago_abono.error.error">
              <span class="text-danger" v-for="(err,index) in pago_abono.error.formapago" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 mb-6">
            <label class="vs-input--label">Nro. Cheque</label>
            <vs-input class="inputx w-full" placeholder="Ingresar el monto" v-model="pago_abono.nrocheque"/>
          </div>
          <div class="vx-col sm:w-1/3 mb-6">
            <label class="vs-input--label">Monto de pago</label>
            <vs-input class="inputx w-full" placeholder="Ingresar el monto" v-model="pago_abono.monto"/>
            <div v-show="pago_abono.error.error">
              <span class="text-danger" v-for="(err,index) in pago_abono.error.monto" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col xl:w-1/3 md:w1/2 sm:w-full mb-2">
            <label class="vs-input--label">Fecha:</label>
            <flat-pickr :config="configdateTimePicker" class="w-full" v-model="pago_abono.fecha" placeholder="Seleccionar"></flat-pickr>
          </div>
        </div>
        <div class="vx-col w-full mt-6">
          <vs-button color="success" type="filled" :disabled="disabled_guardar_abono" @click="guardar_abono()">GUARDAR</vs-button>
          <vs-button color="danger" type="filled" @click="pago_abono.estado = false">CANCELAR</vs-button>
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
const {rutasEmpresa: { DATA_EMPRESA }} = require("../../../../../../config-routes/config.js");
export default {
  configdateTimePicker: {
    locale: SpanishLocale
  },
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
      buscar_cliente:"",
      contenido:[],
      contenido1:[],
      contenido2:[],
      cliente:{},
      //valores
      pagos_por:"",
      forma_pago:"",
      banco:"",
      numero_tarjeta:"",
      descuento_porcentaje:"",
      descuento_pago:"",
      valor_real:"",
      bancos:[],
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
      cobroclientes:false,
      ccliente:{
        factura:'',
        cliente:'',
        periodo:'',
        tiempos:null,
        plazo:null,
        monto:null,
        anticipo:false,
        formapago:"",
        nrocheque:null
      },
      // nro secuencial recibo
      nro_secuencial:"",
      //////

      //valores listar anticipos
      exist_anticipos:false,
      cliente_id:null,
      list_anticipos:[],
      ///////////
      errorccliente:{
        error:0,
        factura:[],
        cliente:[],
        periodo:[],
        tiempos:[],
        plazo:[],
        monto:[],
        formapago:[],
      },
      errores:{
        error:0,
        errorpagos_por:[],
        errorforma_pago:[],
      },
      anticipoexistente:0,
      listarcuentaslista:[],
      //variables Contabilizar
      modalAsiento:false,
      disabled_asiento:false,
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
      anticipo_asiento:0,
      nombre_pago:"",
      id_pago:"",
      fecha_pago:"",
      nro_documento:"",
      diferencia_debe:0,
      diferencia_haber:0,
      id_forma_pago:"",
      estado_asiento:"",
      //importar
      importar:false,
      file:[],
      fecha_registro:moment().format('YYYY-MM-DD'),
      fecha_registro_pago:null,
      pago_edicion:false,
      pago_editar:{
        id_ctas_cobrar_pagos:null,
        pagos_por:"",
        nro_tarjeta:"",
        fecha_pago:"",
        valor_seleccionado:"",
        descuento_porcentaje:"",
        descuento_pago:"",
        valor_real_pago:"",
        id_forma_pagos:null,
        id_banco:null,
        id_user:null,
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
      disabled:false,
      disabled_guardar_pago:false,
      disabled_agregar_cobro:false,
      disabled_guardar_abono:false,
      buscarpago:"",
      timeout:"",
      contenido2:[],
        pago_abono:{
            id:null,
            estado: false,
            buscar: "",
            id_cliente:null,
            formapago: null,
            nrocheque: null,
            monto: null,
            fecha: null,
            error:{
                error: 0,
                cliente: [],
                formapago: [],
                monto: []
            }
        },
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
    fechaactual(data){
      var hoy = moment().format('YYYY-MM-DD');
      if(data<hoy){
        return true;
      }else{
        return false;
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
            total += parseFloat(el.valor_cuota - el.valor_pagado);
        });
      }
      
      return total.toFixed(2);
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
            total += parseFloat(el.valor_cuota - el.valor_pagado);
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
    //total cobro cliente actualizar
    total_cobro_actualizar(){
      var total=0
      this.contenido2.forEach(el => {
        total+=parseFloat(el.valor_cuota);
      });
      this.pago_editar.valor_seleccionado=total;
      return total;
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
            console.log(this.total_debe-this.total_haber);
    },
    suma_debe(){
            var total=0;
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
        if(this.productos_asiento.length>0){
            this.productos_asiento.forEach(el => {
                if(el.haber !==null){
                    total+=parseFloat(el.haber);
                }

            });
        }


        this.total_haber=total.toFixed(2);
    },
    igualar(){
      var cambio=0;
      var cambio_2=0;
      if(this.productos_asiento.length>0){
        if(this.productos_asiento[this.productos_asiento.length-1].haber_talves!==this.total_haber){
          cambio=this.total_haber-this.productos_asiento[this.productos_asiento.length-1].haber_talves;
          this.productos_asiento[this.productos_asiento.length-1].haber=this.productos_asiento[this.productos_asiento.length-1].haber-cambio;
        }
      }
      if(this.iva_asiento.length>0){
        if(this.iva_asiento[this.iva_asiento.length-1].debe_tal!==this.total_debe){
          cambio_2=this.total_debe-this.iva_asiento[this.iva_asiento.length-1].debe_tal;
          this.iva_asiento[this.iva_asiento.length-1].debe=this.iva_asiento[this.iva_asiento.length-1].debe-cambio_2;
        }
      }
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
                        debe: parseFloat(elemento.debe) + parseFloat(valorActual.debe)
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
                          haber: parseFloat(elemento.haber) + parseFloat(valorActual.haber)
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
                    if(el.haber !==null){
                        el.haber=parseFloat(el.haber).toFixed(2);
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
      this.pago_edicion=true;
      this.pago_editar = {
        id_ctas_cobrar_pagos:data.id_ctas_cobrar_pagos,
        pagos_por:data.pagos_por,
        nro_tarjeta:data.nro_tarjeta,
        fecha_pago:data.fecha_pago,
        valor_seleccionado:data.valor_real_pago,
        descuento_porcentaje:data.descuento_porcentaje,
        descuento_pago:data.descuento_pago,
        valor_real_pago:data.valor_real_pago,
        id_forma_pagos:data.id_forma_pagos,
        id_banco:data.id_banco,
        
      }
      this.nro_secuencial=data.posicion;
      this.contenido2 =[];
      this.llamartablavalores(data);
    },
    llamartablavalores(datos){
      
      axios.post("/api/llamartablavalores", datos).then(({data}) => {
        console.log(data);
        data.forEach((el,index) => {
          el.agregar = false;
        });
        data.forEach((el1,index1) => {
          datos.referencia.forEach((el,index) => {
              if(index%4==1){
                  if(el == el1.id_ctascobrar){
                    el1.agregar = true;
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
      this.disabled=true;
      if(this.validaredicionpago()){this.disabled=false;return;}
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
      this.pago_editar.id_user=this.usuario.id;
      this.pago_editar.contenido = this.contenido2;
      axios.post("/api/guardar_edicion_pago", this.pago_editar).then((res) => {
        if(res=='id_user'){
          this.$vs.notify({
            title: "No se pudo Actualizar",
            text: "No existe usuario para guardar",
            color: "danger"
          });
          return;
        }

        this.pago_edicion=false;
        this.$vs.notify({
          time: 5000,
          title: "Datos actualizados",
          text: "Datos actualizados exitosamente",
          color: "success"
        });
        //this.listar(this.buscar);
        this.listarcuentas(this.buscarpago);
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
      this.pago_editar.descuento_pago = ((this.pago_editar.valor_seleccionado * this.pago_editar.descuento_porcentaje) / 100).toFixed(3);
      this.pago_editar.valor_real_pago = this.pago_editar.valor_seleccionado - this.pago_editar.descuento_pago;
    },
    cambio_pago_pagp(){
      this.pago_editar.descuento_porcentaje = ((this.pago_editar.descuento_pago * 100) / this.pago_editar.valor_seleccionado).toFixed(3);
      this.pago_editar.valor_real_pago = this.pago_editar.valor_seleccionado - this.pago_editar.descuento_pago;
    },
    listar(buscar) {
      $(".menuescoger").show();
      var url = "/api/pagocliente?buscar=" + buscar + "&id=" + this.usuario.id_empresa;
      axios.get(url).then( res => {
        this.contenido1 = res.data;
      }).catch(function(error) {
        console.log(error);
      });
    },
    listarClienteAgregar(buscar) {
      $(".menuescoger").show();
      var url = "/api/pagocliente?buscar=" + buscar + "&id=" + this.usuario.id_empresa;
      axios.get(url).then( res => {
        this.contenido2 = res.data;
      }).catch(function(error) {
        console.log(error);
      });
    },
    abrir(tr){
      $(".menuescoger").hide();
      this.visualizar = true;
      this.cliente = tr;
      this.buscar = tr.nombre;
      this.cliente_id=tr.id_cliente;
      console.log("ID del CLIENTE:");
      console.log(this.cliente_id);
      this.llamar(tr.id_cliente);
      this.anticipover(tr.id_cliente);
    },
    abrirCliente(tr){
      $(".menuescoger").hide();
      //this.visualizar = true;
      this.cliente = tr;
        
      this.buscar_cliente = tr.nombre;
      this.llamar(tr.id_cliente);
      this.anticipover(tr.id_cliente);
    },
    llamar(id){
      this.idrec = id;
      axios.get("/api/pago/"+id).then( res => {
        this.contenido = res.data.recupera;
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
      this.disabled_guardar_pago=true;
      if(this.validarpago()){this.disabled_guardar_pago=false;return; }
      if(this.valor_select<=0){
        this.$vs.notify({
          time: 5000,
          title: "Debe ingresar un valor a cobrar",
          text: "No hay un valor seleccionado",
          color: "danger"
        });
        this.disabled_guardar_pago=false;
        return;
      }
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
          this.disabled_guardar_pago=false;
          return;
        }
      }
      if(this.pagos_por=='Anticipo'){
        if(this.valor_real>this.anticipoexistente){
          this.$vs.notify({
            time: 5000,
            title: "El pago es mayor al anticipo existente",
            text: "El pago debe estar en el rango del anticipo",
            color: "danger"
          });
          this.disabled_guardar_pago=false;
          return;
        }
      }
      axios.post("/api/agregarpagos",{
        id_cliente:this.cliente.id_cliente,
        pagos_por: this.pagos_por,
        forma_pago: this.forma_pago,
        banco: this.banco,
        numero_tarjeta: this.numero_tarjeta,
        descuento_pago: this.descuento_pago,
        valor_real: this.valor_real,
        tabla: this.contenido,
        descuento_porcentaje: this.descuento_porcentaje,
        valor_select: this.valor_select,
        fecha_registro:this.fecha_registro,
        exist_anticipos:this.exist_anticipos,
        anticipos:this.list_anticipos,
        id_user:this.usuario.id
      }).then( res => {
        if(res=='id_user'){
          this.$vs.notify({
            title: "No se guardo el pago",
            text:"Se necesita un usuario para guardar",
            color: "danger"
          });
          this.disabled_guardar_pago=false;
          return;
        }
        this.visualizar = false;
        this.$vs.notify({
          time: 5000,
          title: "Pago guardado",
          text:"Se guardo el pago correctamente",
          color: "success"
        });
        this.disabled_guardar_pago=false;
        //this.abrir(this.cliente);
        this.pagos_por ="";
        this.forma_pago ="";
        this.banco ="";
        this.numero_tarjeta ="";
        this.descuento_porcentaje ="";
        this.descuento_pago ="";
        this.valor_real ="";
        //if(this.usuario.id_empresa==34){
          this.recibo(res.data,this.usuario.id_empresa);
        //}
        this.listarcuentas("");
        this.visualizar=false;
        this.buscar='';
      }).catch( err => {
        console.log(err);
        this.$vs.notify({
          title: "Error Guardar Pago",
          text:"Se produjo un error al guardar el Pago",
          color: "danger"
        });
      })
    },
    //funcion pdf recibo
    recibo(id,empresa){
      console.log("ID:"+id);
      var url3="/api/recibo_cuenta_cobrar/"+id+"/"+empresa+"/v";
      axios.get(url3)
      .then(resp=>{
        window.open(
                        '/'+empresa+'/vistapdfrecibo_cobro/'+id,"_blank"
                    );
      })
    },
    reciboCobro(id,index,tipo){
      // var url="/api/recibo_cuenta_cobrar/"+id+"/"+this.usuario.id_empresa+"/d";
      // axios.get(url)
      // .then(resp=>{

      // })
      console.log("Entro al recibo")
      if(tipo=='Cobro'){
        var url2="/api/recibo_cuenta_cobrar/"+id+"/"+this.usuario.id_empresa+"/d";
      }else{
        var url2="/api/recibo_cuenta_cobrar_anticipo/"+id+"/"+this.usuario.id_empresa+"/d";
      }
      
      axios({
                    url: url2,
                    method: "GET",
                    responseType: "arraybuffer"
                
                }).then(resp=>{
                    console.log("ejecutado cheque");
                //this.contenidopr=res.data;
                console.log("resp:"+resp);
                  console.log("resp data:"+resp.data);

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
                    title: "Reporte Generado",
                    text: "Su reporte esta siendo descargado exitosamente!",
                    color: "success"
                });
                }).catch(err=>{
                  console.log("ERROR"+err);
                });
    },
    /////////////
    //ASientos Pago cliente
    Contabilidad(id){
      axios.get('/api/cuentacobrarvercontabilidad/' + id+
                        "?id_empresa=" +
                        this.usuario.id_empresa).then( ({data}) => {
                          // <span v-for="(tr, index1) in datos.referencia" :key="index1">
                          //   <span v-if="index1%4==0">
                          //     {{tr}}
                          //   </span> <br v-if="((datos.referencia.length)%4==0) != ((index1)%4==0)+1">
                          // </span>
                          var referencia=data.ctas_cobrar.referencia.split(';');
                          var conteo_ref=referencia.length/4;
                          var salto = 0;
                          var factura=[];
                          for(var f=0; f<conteo_ref; f++){
                            factura.push(referencia[0+salto]);
                            salto+=4;
                          }
                          var nros_factura=factura.join();
                          //console.log(nros_factura+":facturas");
                          if(data.ctas_cobrar.fecha_registro!==null){
                            this.fecha_rol=moment(data.ctas_cobrar.fecha_registro).format("Y-MM-DD");
                          }else{
                            this.fecha_rol=moment(data.ctas_cobrar.fecha_pago).format("Y-MM-DD");
                          }

                          var fecha=moment(this.fecha_rol).format("MMMM YYYY");
                          this.razon_social=data.ctas_cobrar.nombre;
                          this.ruc_empresa=data.ctas_cobrar.identificacion;
                          if(data.ctas_cobrar.tipo_identificacion=="Cédula de Identidad"){
                              this.tipo_identificacion="Cedula";
                          }else{
                              this.tipo_identificacion=data.ctas_cobrar.tipo_identificacion;
                          }
                          if(data.ctas_cobrar.contabilidad==1){
                              this.codigo="CC-"+data.codigo_anterior;
                              this.contabilizado=data.ctas_cobrar.contabilidad;
                          }else{
                              this.codigo="CC-"+data.codigo;
                              this.contabilizado=null;
                          }
                          this.concepto="Cobro Cliente "+nros_factura;
                          this.modalAsiento=true;
                          this.productos_asiento=data.cliente;
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
                          console.log(data.ctas_cobrar.contabilidad+"HOLA");
                // this.lista.factura = data.factura;
                // this.lista.cliente = data.cliente;
                // //this.lista.productos = data.productos;
                // this.lista.creditos = data.creditos;
                // this.lista.iva = data.iva;
                // this.lista.renta = data.renta;
                // this.fecha_rol=data.factura.fecha_emision;
                // var fecha=moment(this.fecha_rol).format("MMMM YYYY");
                // this.razon_social=data.empresa.nombre;
                // this.ruc_empresa=data.empresa.identificacion;
                // if(data.empresa.tipo_identificacion=="Cédula de Identidad"){
                //     this.tipo_identificacion="Cedula";
                // }else{
                //     this.tipo_identificacion=data.empresa.tipo_identificacion;
                // }
                // if(data.factura.contabilidad!==null){
                //     this.codigo="CC-"+data.codigo_anterior;
                //     this.contabilizado=data.factura.contabilidad;
                // }else{
                //     this.codigo="CC-"+data.codigo;
                // }
                // this.concepto="Factura Venta "+fecha;
                // this.productos_asiento=data.producto_asientos;
                // this.iva_asiento=data.doce_iva_asiento;
                // this.pagos_sin_plc=data.pagos_asientos_sin_plc;
                // this.pagos_con_plc=data.pagos_asientos_con_plc;
                // this.pagos_anticipo=data.pagos_asientos_anticipo;
                // this.creditos=data.cliente;
                // this.retencion_iva=data.iva_retencion_asiento;
                // this.retencion_renta=data.retencion_asiento;
                // this.modalAsiento=true;
                // this.id_factura=id;
                // this.id_proyecto=data.id_proyecto;
            }).catch( error => {
                console.log(error);
            });
    },
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
            axios.post("/api/cuenta_cobrar/agregar/asiento",{
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
                anticipo:this.anticipo_asiento
            }).then(res=>{
                this.crearasientoDetalle(res.data);
                this.anticipo_asiento=0;
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
        axios.post("/api/cuenta_cobrar/agregar/asiento_detalle",{
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
            error++
          }else{
            this.productos_asiento.forEach(el=>{
                    if(el.exist_plan_cuenta_cliente=="si"){
                        if(el.id_plan_cuentas_cliente==null){
                            error++;
                        }
                    }else{
                      if(el.id_plan_cuentas_grupo==null){
                            error++;
                        }
                    }
                    if(el.id_proyecto==null){
                            error++;
                        }
                });
          }
          if(this.iva_asiento.length<=0){
            error++
          }else{
            this.iva_asiento.forEach(el=>{
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
    //

    //asiento anticipo
    ContabilidadAnticipo(id){
      axios.get('/api/cuentacobrar_anticipo_vercontabilidad/' + id+
                        "?id_empresa=" +
                        this.usuario.id_empresa).then( ({data}) => {
                          // <span v-for="(tr, index1) in datos.referencia" :key="index1">
                          //   <span v-if="index1%4==0">
                          //     {{tr}}
                          //   </span> <br v-if="((datos.referencia.length)%4==0) != ((index1)%4==0)+1">
                          // </span>
                          // var referencia=data.ctas_cobrar.referencia.split(';');
                          // var conteo_ref=referencia.length/4;
                          // var salto = 0;
                          // var factura=[];
                          // for(var f=0; f<conteo_ref; f++){
                          //   factura.push(referencia[0+salto]);
                          //   salto+=4;
                          // }
                          // var nros_factura=factura.join();
                          //console.log(nros_factura+":facturas");
                          if(data.ctas_cobrar.fecha_registro!==null){
                            this.fecha_rol=moment(data.ctas_cobrar.fecha_registro).format("Y-MM-DD");
                          }else{
                            this.fecha_rol=moment(data.ctas_cobrar.fecha_pago).format("Y-MM-DD");
                          }

                          var fecha=moment(this.fecha_rol).format("MMMM YYYY");
                          this.razon_social=data.ctas_cobrar.nombre;
                          this.ruc_empresa=data.ctas_cobrar.identificacion;
                          if(data.ctas_cobrar.tipo_identificacion=="Cédula de Identidad"){
                              this.tipo_identificacion="Cedula";
                          }else{
                              this.tipo_identificacion=data.ctas_cobrar.tipo_identificacion;
                          }
                          if(data.ctas_cobrar.contabilidad==1){
                              this.codigo="CC-"+data.codigo_anterior;
                              this.contabilizado=data.ctas_cobrar.contabilidad;
                          }else{
                              this.codigo="CC-"+data.codigo;
                              this.contabilizado=null;
                          }
                          this.concepto="Cobro Cliente "+data.ctas_cobrar.nombre+" Anticipo";
                          this.modalAsiento=true;
                          this.productos_asiento=data.cliente;
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

                          console.log(data.ctas_cobrar.contabilidad+"HOLA");
                          this.anticipo_asiento=1;
                // this.lista.factura = data.factura;
                // this.lista.cliente = data.cliente;
                // //this.lista.productos = data.productos;
                // this.lista.creditos = data.creditos;
                // this.lista.iva = data.iva;
                // this.lista.renta = data.renta;
                // this.fecha_rol=data.factura.fecha_emision;
                // var fecha=moment(this.fecha_rol).format("MMMM YYYY");
                // this.razon_social=data.empresa.nombre;
                // this.ruc_empresa=data.empresa.identificacion;
                // if(data.empresa.tipo_identificacion=="Cédula de Identidad"){
                //     this.tipo_identificacion="Cedula";
                // }else{
                //     this.tipo_identificacion=data.empresa.tipo_identificacion;
                // }
                // if(data.factura.contabilidad!==null){
                //     this.codigo="CC-"+data.codigo_anterior;
                //     this.contabilizado=data.factura.contabilidad;
                // }else{
                //     this.codigo="CC-"+data.codigo;
                // }
                // this.concepto="Factura Venta "+fecha;
                // this.productos_asiento=data.producto_asientos;
                // this.iva_asiento=data.doce_iva_asiento;
                // this.pagos_sin_plc=data.pagos_asientos_sin_plc;
                // this.pagos_con_plc=data.pagos_asientos_con_plc;
                // this.pagos_anticipo=data.pagos_asientos_anticipo;
                // this.creditos=data.cliente;
                // this.retencion_iva=data.iva_retencion_asiento;
                // this.retencion_renta=data.retencion_asiento;
                // this.modalAsiento=true;
                // this.id_factura=id;
                // this.id_proyecto=data.id_proyecto;
            }).catch( error => {
                console.log(error);
            });
    },
    //
    guardar(){
      this.disabled_agregar_cobro=true;
      if(this.validarguardado()){this.disabled_agregar_cobro=false; return; }
      axios.post("/api/guardarpagos",{
        ccliente: this.ccliente,
        cliente: this.cliente,
        fecha_registro_pago: this.fecha_registro_pago,
        empresa:this.usuario.id_empresa,
        id_user:this.usuario.id
      }).then( res => {
        console.log(res);
        if(res.data=='error'){
          this.$vs.notify({
            time: 5000,
            title: "Factura inexistente",
            text:"Ingrese una factura existente en el sistema",
            color: "danger"
          });
          this.disabled_agregar_cobro=false;
          return;
        }
        this.$vs.notify({
          time: 5000,
          title: "Registro guardado",
          text:"El registro se guardo exitosamente",
          color: "success"
        });
        
        //if(this.usuario.id_empresa==34){
          var number1=parseInt(res.data);
          if(Number.isInteger(number1)){
            this.recibo(res.data,this.usuario.id_empresa);
          }
        //}
        this.abrir(this.cliente);
        this.ccliente ={
          factura:'',
          cliente:'',
          periodo:'',
          tiempos:null,
          plazo:null,
          monto:null,
          anticipo:false,
        };
        this.disabled_agregar_cobro=false;
        this.cobroclientes=false;
        
      }).catch( err => {
        console.log(err);
        this.$vs.notify({
          title: "Error Guardar Registro",
          text:"Se produjo un error al guardar registro",
          color: "danger"
        });
      })
    },
    validarguardado(){
      this.errorccliente ={
        error:0,
        factura:[],
        cliente:[],
        periodo:[],
        tiempos:[],
        plazo:[],
        monto:[],
        formapago:[]
      };
      if(!this.ccliente.anticipo){
        if(this.ccliente.factura.length<15){
          this.errorccliente.factura.push("agrege una factura correcta");
          this.errorccliente.error = 1;
        }
        if(!this.ccliente.periodo){
          this.errorccliente.periodo.push("Escoga periodo");
          this.errorccliente.error = 1;
        }
        if(!this.ccliente.tiempos){
          this.errorccliente.tiempos.push("Escoga tiempo");
          this.errorccliente.error = 1;
        }
        if(!this.ccliente.plazo){
          this.errorccliente.plazo.push("Escoga plazo");
          this.errorccliente.error = 1;
        }
      }else{
        if(!this.ccliente.formapago){
          this.errorccliente.formapago.push("Escoga forma de pago");
          this.errorccliente.error = 1;
        }
      }
      if(!this.cliente.id_cliente){
        this.errorccliente.cliente.push("Escoga cliente");
        this.errorccliente.error = 1;
      }
      if(!this.ccliente.monto){
        this.errorccliente.monto.push("Escoga monto");
        this.errorccliente.error = 1;
      }

      return this.errorccliente.error;
    },
    anticipo(){
      if(!this.ccliente.anticipo){
        this.ccliente = {
          factura:'',
          periodo:'',
          tiempos:null,
          plazo:null,
        }
      }
      this.errorccliente ={
        error:0,
        factura:[],
        cliente:[],
        periodo:[],
        tiempos:[],
        plazo:[],
        monto:[],
      };
    },
    validarpago(){
      this.errores = {
        error:0,
        errorpagos_por:[],
        errorforma_pago:[],
      }

      if(!this.pagos_por) {
        this.errores.errorpagos_por.push("Campo obligatorio");
        this.errores.error = 1;
      }
      if(!this.forma_pago) {
        this.errores.errorforma_pago.push("Ingrese una forma de pago");
        this.errores.error = 1;
      }

      return this.errores.error;
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
      
        if(parseFloat(this.valor_real) > parseFloat(this.valor_select)){
          this.$vs.notify({
            time: 5000,
            title: "El valor pagado no puede ser mayor al valor seleccionado a pagar",
            color: "danger"
          });
          this.valor_real = this.valor_select;
        }
      
      
    },
    reportes_factura() {
      window.open("/api/reportes/cuentas-por-cobrar?tipo_busqueda=" + this.tipo_busqueda + "&dateinicio=" + this.dateinicio + "&datefin=" + this.datefin + "&cliente_busqueda=" + this.cliente_busqueda + "&vendedor_busqueda=" + this.vendedor_busqueda, "_top");
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
    getClientes: function() {
      axios.get("/api/clientes").then(
        function(response) {
          this.clientes2 = response.data;
        }.bind(this)
      );
    },
    listarformapagos(){
      axios.get("/api/facturaformapagos/" + this.usuario.id_empresa).then( res => {
          this.formapagos = res.data;
      }).catch( err => {
          console.log(err);
      });
    },
    abrirModal(){
      this.cobroclientes = true;
    },
    anticipover(id){
      axios.get("/api/anticipototal?id="+id).then( ({data}) => {
          this.anticipoexistente = data.toFixed(2);
      }).catch( err => {
          console.log(err);
      });
    },
    listarcuentas(buscarpago){
      if (this.timeout) {
        clearTimeout(this.timeout);
      }
      this.timeout =  setTimeout(() => {
        axios.get("/api/listarcuentaslista?id=" + this.usuario.id_empresa+"&buscar="+buscarpago).then(({data}) => {
            data.sort(function (a, b) {
              if(a.posicion!==null && b.posicion!==null){
                  return  b.posicion - a.posicion;
              }else{
                  return new Date(b.fechageneral).getTime() - new Date(a.fechageneral).getTime();
              }
                
            });
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
        axios.delete("/api/eliminarcxc/" + parameters);
        this.$vs.notify({
            color: "warning",
            title: "Cobro Cancelado",
            text: "El Cobro selecionado fue cancelado con exito"
        });
        this.listarcuentas(this.buscarpago);
    },
    //pdf
    descargaPdf($id,$index,destinatario=null,email=null){
      var index_ctas=$index+1;
      axios({
                    url: "/api/pdf/ctaxcobrar",
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
      axios.post("/api/importarcuentascobrar", formData, {}).then((res) => {
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
    eliminarabono(id){
        axios.delete("/api/eliminar/abonos/"+id).then( res => {
            this.$vs.notify({text: "Archivo eliminado exitosamente",color: "success",});
            this.listarcuentas(this.buscarpago);
        }).catch( error => {
            console.log(error);
        });
    },
    editar_abono(tr){
        this.pago_abono.estado = true;
        this.pago_abono.id = tr.id_ctascobrar;
        this.pago_abono.buscar = tr.nombre;
        this.pago_abono.id_cliente = tr.id_cliente;
        this.pago_abono.formapago = tr.id_forma_pagos;
        this.pago_abono.nrocheque = tr.numero_transaccion;
        this.pago_abono.monto = tr.valor_cuota;
        this.pago_abono.fecha = tr.fecha_pago;
        this.nro_secuencial=tr.posicion;
    },
    guardar_abono(){
      this.disabled_guardar_abono=true;
        if(this.validar_abono()){this.disabled_guardar_abono=false;return;}
        axios.post("/api/editar/abonos", this.pago_abono).then( res => {
            this.$vs.notify({text: "Archivo Actualizado exitosamente",color: "success",});
            this.listarcuentas(this.buscarpago);
            this.pago_abono.estado = false;
            this.disabled_guardar_abono=false;
        }).catch( error => {
            console.log(error);
             this.$vs.notify({text: " Error Actualizar Archivo",color: "danger",});
        });
    },
    validar_abono(){
        this.pago_abono.error = {
            error: 0,
            cliente: [],
            formapago: [],
            monto: []
        }
        if(!this.pago_abono.id_cliente){
            this.pago_abono.error.cliente.push("Cliente obligatorio");
            this.pago_abono.error.error = 1;
            console.log(1);
        }
        if(!this.pago_abono.formapago){
            this.pago_abono.error.formapago.push("Forma de pago obligatorio");
            this.pago_abono.error.error = 1;
            console.log(2);
        }
        if(!this.pago_abono.monto){
            this.pago_abono.error.monto.push("Monto obligatorio");
            this.pago_abono.error.error = 1;
            console.log(3);
        }
        return this.pago_abono.error.error;
    },
    // listar nro secuencial recibo
    listarrecibonro(){
      axios.get("/api/listarsecuencia_recibo/"+this.usuario.id).then(resp=>{
        if(resp.data.length>0){
          this.nro_secuencial=resp.data[0].secuencial_recibo;
        }
        
      }).catch(err=>{
          this.$vs.notify({text: " Error Traer Secuencial",color: "danger"});
      });
    },
    /////////////
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
        this.fecha_registro=moment().format('YYYY-MM-DD');
        this.llamar(this.cliente_id);
        this.anticipover(this.cliente_id);
      }else{
          this.list_anticipos=[];
          this.pagos_por="";
          this.forma_pago="";
          this.banco="";
          this.numero_tarjeta="";
          this.descuento_porcentaje="";
          this.descuento_pago="";
          this.fecha_registro=moment().format('YYYY-MM-DD');
        axios.get("/api/listar_anticipos/cliente/"+this.cliente_id)
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
    this.listarrecibonro();
    this.listarbanco();
    this.getClientes();
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
.peque2 .vs-popup {
        width: 1090px !important;
    }
// .vs-popup{
//         width: 600px !important;
// }
.vs-popup {
  width: 1060px !important;
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
