<template>
  <div id="invoice-page">
    <vx-card>
        <vs-divider position="left">
        <h3>Importacion {{cod_importacion}}</h3>
      </vs-divider>
      <div class="vx-row leading-loose p-base">
        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Periodo Inicio:</h6>
          <flat-pickr
              :config="configdateTimePicker"
              class="w-full"
              placeholder="Seleccione una"
              v-model="periodo_inicio"
            />
          <div v-show="error" v-if="!periodo_inicio">
            <div v-for="err in errorperiodo_inicio" :key="err" v-text="err" class="text-danger"></div>
          </div>
        </div>
        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Periodo Fin:</h6>
          <flat-pickr
              :config="configdateTimePicker"
              class="w-full"
              placeholder="Seleccione una"
              v-model="periodo_fin"
            />
            <div v-show="error" v-if="!periodo_fin">
            <div v-for="err in errorperiodo_fin" :key="err" v-text="err" class="text-danger"></div>
          </div>
        </div>
        <div class="vx-col sm:w-1/3 w-full mb-2">
        <h6>Número Orden:</h6>
             <vs-select
              class="selectExample w-full"
              autocomplete
              v-model="nro_orden"
            >
              <vs-select-item
                v-for="data in ordens"
                :key="data.id_factcompra"
                :value="data.id_factcompra"
                :text="data.orden_compra"
              />
            </vs-select>
        </div>
      </div>
      <div class="vx-row leading-loose p-base">
          

          <div class="vx-col sm:w-1/3 w-full mb-5" hidden>
            <vs-select
              class="selectExample w-full"
              label="Estado"
              vs-multiple
              autocomplete
              v-model="estado"
            >
              <vs-select-item value="Inicial" text="Inicial" />
              <vs-select-item value="Liquidado" text="Liquidado" />
            </vs-select>
          </div>
          <div class="vx-col sm:w-1/6 w-full mb-5">
            <label class="vs-input--label">Fecha Embarque</label>
          <flat-pickr
              :config="configdateTimePicker"
              class="w-full"
              placeholder="Seleccione una"
              v-model="fech_embarque"
            />

        </div>
        <div class="vx-col sm:w-1/6 w-full mb-2">
          <label class="vs-input--label">Fecha Arribo</label>
          <flat-pickr
              :config="configdateTimePicker"
              class="w-full"
              placeholder="Seleccione una"
              v-model="fech_arribo"
            />
        </div>
        <div class="vx-col sm:w-1/5 w-full mb-2">
            <vs-select
              class="selectExample w-full"
              label="Tipo de Calculo"
              placeholder="Seleccione una"
              autocomplete
              v-model="forma_liquidacion"
            >
              <vs-select-item value="1" text="Cantidad" />
              <vs-select-item value="0" text="Valor Unitario" />
            </vs-select>
            <div v-show="error" v-if="!forma_liquidacion">
              <div v-for="err in errorforma_liquidacion" :key="err" v-text="err" class="text-danger"></div>
            </div>
        </div>

          <!--<div class="vx-col sm:w-1/4 w-full mb-2">
            <label class="vs-input--label" style="margin-left: 1px;">Liquidar</label>
            <vs-checkbox v-model="liquidar" vs-value="1" ></vs-checkbox>
          </div>-->
      </div>
      <!--<vs-divider position="left">
        <h3>Proveedor</h3>
      </vs-divider>

      <div class="vx-row leading-loose p-base" >
        <div class="vx-col w-full mb-2">
          <div class="dropdown-button-container">
            <vs-dropdown>
              <a class="flex items-center">
                Añadir Proveedor
                <i class="material-icons">expand_more</i>
              </a>
              <div v-show="error" v-if="valorproveedores.length<1">
                  <div v-for="err in errorproveedor" :key="err" v-text="err" class="text-danger"></div>
              </div>
              <vs-dropdown-menu>
                <vs-dropdown-item
                  class="text-center"
                  divider
                  @click="popupActive3=true,tipomodalprov=1"
                >Buscar Proveedor</vs-dropdown-item>
                <vs-dropdown-item class="text-center" divider @click="crear()">Crear Proveedor</vs-dropdown-item>
              </vs-dropdown-menu>
            </vs-dropdown>
          </div>
        </div>
        <div class="vx-row p-base" v-for="(tr,index) in valorproveedores" :key="index">
        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Nombre:</h6>
          <vs-input disabled class="w-full" v-bind:value="tr.nombre" />
        </div>
        <div class="vx-col sm:w-1/4 w-full mb-2">
          <h6>Teléfono:</h6>
          <vs-input disabled class="w-full" v-bind:value="tr.telefono" />
        </div>

        <div class="vx-col sm:w-1/4 w-full mb-2">
          <h6>Grupo:</h6>

          <vs-select disabled class="selectExample w-full nover" v-bind:value="tr.grupo">
            <vs-select-item
                v-for="data in grupo_menu"
                :key="data.id_grupoprov"
                :value="data.id_grupoprov"
                :text="data.nombre_grupoprov"
              />
          </vs-select>
        </div>
        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Tipo de Identificación:</h6>
          <vs-select disabled class="selectExample w-full nover" v-bind:value="tr.tipo_identificacion">
            <vs-select-item
              :key="index"
              :value="item.value"
              :text="item.text"
              v-for="(item,index) in tipo_identificacion_menu"
            />
          </vs-select>
        </div>

        <div class="vx-col sm:w-1/4 w-full mb-2">
          <h6>Identificación:</h6>
          <vs-input disabled class="w-full" v-bind:value="tr.identificacion" />
        </div>

        <div class="vx-col sm:w-1/4 w-full mb-2">
          <h6>Dirección:</h6>
          <vs-input disabled class="w-full" v-bind:value="tr.direccion" />
        </div>
        <feather-icon
                    
                    icon="TrashIcon"
                    svgClasses="w-5 h-5 hover:text-danger stroke-current"
                    class="ml-2 cursor-pointer"
                    @click="borrarprov(index)"
                  />
      </div>
      </div>-->
      
      <vs-divider position="left">
                <h3>Proveedor</h3>
            </vs-divider>
            <div class="vx-row leading-loose p-base">
                <!--<div class="vx-col sm:w-full w-full mb-6 relative" v-if="cliente.tipo">
                    <div class="vx-row">
                        <a class="flex items-center buscar_otro" @click="cliente.tipo=false"> Agregar otro Proveedor </a>
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input class="w-full" label="Nombre:" disabled v-bind:value="cliente.nombre" />
                        </div> 
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input class="w-full" label="Teléfono:" disabled v-bind:value="cliente.telefono" />
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input class="w-full" label="Email:" disabled v-bind:value="cliente.email" />
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input class="w-full" label="Tipo de Identificación:" disabled v-bind:value="cliente.tipo_identificacion" />
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input class="w-full" label="Identificación:" disabled v-bind:value="cliente.identificacion" />
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input class="w-full" label="Dirección:" disabled v-bind:value="cliente.direccion" />
                        </div>
                    </div>
                </div>-->
                <div class="vx-col sm:w-full w-full mb-6 relative">
                  <div class="vx-row p-base" v-for="(tr,index) in valorproveedores" :key="index">
                    <div class="vx-col sm:w-1/3 w-full mb-2">
                      <h6>Nombre:</h6>
                      <vs-input disabled class="w-full" v-bind:value="tr.nombre" />
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-2">
                      <h6>Teléfono:</h6>
                      <vs-input disabled class="w-full" v-bind:value="tr.telefono" />
                    </div>

                    <div class="vx-col sm:w-1/4 w-full mb-2">
                      <h6>Grupo:</h6>
                                
                      <vs-input disabled class="w-full" v-bind:value="tr.nombre_grupo" />
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-2">
                      <h6>Tipo de Identificación:</h6>
                      <vs-select disabled class="selectExample w-full nover" v-bind:value="tr.tipo_identificacion">
                        <vs-select-item
                          :key="index"
                          :value="item.value"
                          :text="item.text"
                          v-for="(item,index) in tipo_identificacion_menu"
                        />
                      </vs-select>
                    </div>

                    <div class="vx-col sm:w-1/4 w-full mb-2">
                      <h6>Identificación:</h6>
                      <vs-input disabled class="w-full" v-bind:value="tr.identificacion" />
                    </div>

                    <div class="vx-col sm:w-1/4 w-full mb-2">
                      <h6>Dirección:</h6>
                      <vs-input disabled class="w-full" v-bind:value="tr.direccion" />
                    </div>
                    <feather-icon
                              
                                icon="TrashIcon"
                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                class="ml-2 cursor-pointer"
                                @click="borrarprov(index)"
                              />
                  </div>
                </div>
                <div class="vx-col sm:w-full w-full mb-6 relative"  v-if="proveedor_tipo">
                    <vs-input class="w-full busqueda_cliente" placeholder="Escoge algun Proveedor" v-model="busqueda_cliente" @keyup="listar_cliente(busqueda_cliente)"/>
                    <feather-icon icon="SearchIcon" svgClasses="w-8 h-8 hover:text-primary stroke-current cursor-pointer" class="busqueda_cliente_icono"/>
                    <div class="busqueda_lista busqueda_lista_proveedor">
                        <ul class="ul_busqueda_lista" v-if="contenidoprov.length">
                            <li v-for="(tr,index) in contenidoprov" :key="index" @click="seleccionar_cliente(tr)"> {{ tr.nombre_proveedor }} </li>
                        </ul>
                        <ul class="ul_busqueda_lista" v-else-if="preloader_prov == true && contenidoprov.length<1">
                            <li @click="crear()">
                                    ESTE PROVEEDOR NO SE ENCUENTRA REGISTRADO,
                                    AGREGAR NUEVO CLIENTE
                            </li>
                        </ul>
                    </div>
                    <div v-show="error" v-if="valorproveedores.length<1">
                        <div v-for="err in errorproveedor" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
            </div>
          <vs-divider position="left">
        <h3>Productos</h3>
      </vs-divider>
      <div class="vx-row leading-loose p-base">
        <!--<a class="flex items-center cursor-pointer mb-4" @click="abrirproductos()">Añadir Productos</a>-->
        <div class="vx-col sm:w-full w-full relative" v-if="producto_tipo">
          <vs-table hoverFlat :data="contenidopr" style="font-size: 12px;">
              <template slot="thead">
                <vs-th>CÓDIGO</vs-th>
                <vs-th>NOMBRE</vs-th>
                <vs-th>PROYECTO</vs-th>
                <vs-th>CANTIDAD</vs-th>
                <vs-th>COSTO UNITARIO</vs-th>
                <!--<vs-th>DESCUENTO</vs-th>
                <vs-th>IVA</vs-th>-->
                <vs-th>BODEGA</vs-th>
                <vs-th>COSTO TOTAL</vs-th>
                <vs-th>ELIMINAR</vs-th>
              </template>
              <template slot-scope="{data}">
                <vs-tr v-for="(tr, index) in data" :key="index">
                  
                  <!--<vs-td :data="tr.id_prodimp>{{ tr.id_prodimp }}</vs-td>-->
                  <vs-td  >{{ tr.codigo }}</vs-td>
                  <vs-td :data="tr.nombre">{{ tr.nombre }}</vs-td>
                  <vs-td>
                      <vs-select class="selectExample w-full" placeholder="Seleccione un proyecto" v-model="tr.proyecto">
                          <vs-select-item :key="index" :value="item.id_proyecto" :text="item.descripcion" v-for="(item, index) in proyectos_menu"/>
                      </vs-select>
                      <div v-show="error" v-if="!tr.proyecto">
                          <div v-for="err in tr.errorproyecto" :key="err" v-text="err" class="text-danger"></div>
                      </div>
                  </vs-td>
                  <vs-td :data="tr.cantidad" style="width:150px!important;">
                    <vs-input class="w-full valores"   v-model="tr.cantidad" />
                    <div v-show="error" v-if="!tr.cantidad">
                      <div
                        v-for="err in tr.errorcant_ingreso"
                        :key="err"
                        v-text="err"
                        class="text-danger"
                      ></div>
                    </div>
                  </vs-td>
                  <vs-td :data="tr.precio" style="width:150px!important;">
                    <vs-input
                      class="w-full valores"
                      
                      
                      v-model="tr.precio"
                    />
                    <div v-show="error" v-if="!tr.precio">
                      <div
                        v-for="err in tr.errorcost_unit_ingreso"
                        :key="err"
                        v-text="err"
                        class="text-danger"
                      ></div>
                    </div>
                  </vs-td>
                  <!--<vs-td style="width:175px!important;" v-if="tr.id_producto_bodega">
                                    {{tr.nombrebodega}}
                  </vs-td>-->
                  <vs-td style="width:175px!important;" v-if="tr.sector == 2">
                                    SERVICIO
                  </vs-td>
                  <vs-td style="width:175px!important;" v-else>
                                    <vs-select placeholder="buscar" autocomplete class="selectExample w-full" v-model="tr.id_bodega">
                                        <vs-select-item v-for="(tr,index) in listarbodegas" :key="index" :value="tr.id_bodega" :text="tr.nombre"/>
                                    </vs-select>
                                    <div v-show="error" v-if="!tr.id_bodega">
                                        <div v-for="err in tr.errorid_bodega" :key="err" v-text="err" class="text-danger"></div>
                                    </div>
                  </vs-td>
                  <!--<vs-td :data="tr.descuento" style="width:200px!important;">
                    <vx-input-group>
                      <vs-input
                        class="w-full"
                        
                        placeholder="$0.00"
                        v-model="tr.descuento"
                      />
                      <template slot="append">
                        <div class="append-text btn-addon">
                          <button class="botonstl" :class="{'botonstl elejido':tr.p_descuento==1,'botonstl':tr.p_descuento!=1}" @click="tr.p_descuento=1">$</button>
                          <button class="botonstl" :class="{'botonstl elejido':tr.p_descuento==0,'botonstl':tr.p_descuento!=0}" @click="tr.p_descuento=0">%</button>
                        </div>
                      </template>
                    </vx-input-group>
                  </vs-td>
                  <vs-td :data="tr.iva" style="width:200px!important;">
                    <vs-select class="selectExample w-full" vs-multiple v-model="tr.iva">
                      <vs-select-item
                        :key="res.id_iva"
                        :value="res.id_iva"
                        :text="res.nombre"
                        v-for="res in contenidoiva"
                      />
                    </vs-select>
                  </vs-td>-->
                
                  <vs-td style="text-align: right;width:130px!important;">{{ (tr.cantidad*tr.precio)| currency }}</vs-td>
                  
                  <vs-td style="text-align:center!important;width:80px!important;" :data="tr.dolar">
                    <feather-icon
                      icon="TrashIcon"
                      svgClasses="w-5 h-5 hover:text-danger stroke-current"
                      class="ml-2 cursor-pointer"
                      @click="borrarproductos(index)"
                    />
                  </vs-td>
                </vs-tr>
              </template>
            </vs-table>
        </div>
        <div class="vx-col sm:w-full w-full mb-6 relative">
                    <vs-input class="w-full busqueda_cliente focuspr" placeholder="Agrega Productos" v-model="producto_busqueda" @keyup="listar_productos(producto_busqueda)"/>
                    <feather-icon icon="SearchIcon" svgClasses="w-8 h-8 hover:text-primary stroke-current cursor-pointer" class="busqueda_cliente_icono"/>
                    <div class="busqueda_lista busqueda_producto_ls" style="display:none;">
                        <div v-if="preloader_productos">
                            <ul class="ul_busqueda_lista">
                                <li v-for="(tr,index) in contenidop" :key="index" @click="seleccionar_productos(tr)"> 
                                    <span
                                        v-if="tr.cod_alterno"
                                        style="font-weight: bold;"
                                        >CodAlt: {{ tr.cod_alterno }} -
                                    </span>
                                    <span v-else style="font-weight: bold;"
                                        >CódPrin:
                                        {{ tr.cod_principal }} - </span
                                    ><span style="font-weight: bold;">{{
                                        tr.nombre
                                    }}</span>
                                    <span
                                        v-if="tr.presentacion"
                                        style="font-weight: bold;"
                                    >
                                        - Presentación: {{ tr.presentacion }}
                                    </span>
                                    <span
                                        v-if="tr.presentacion"
                                        style="font-weight: bold;"
                                    >
                                        - Presentación: {{ tr.presentacion }}
                                    </span>
                                    <span v-if="tr.nombrebodega">
                                      - 
                                      <span style="font-size: 12px;">
                                        Bodega: {{tr.nombrebodega}}
                                        </span>
                                    </span> 
                                      
                                    <span v-if="!tr.nombrebodega && tr.sector==1">
                                      - 
                                      
                                    <span style="font-size: 12px;">Producto sin Bodega</span></span> </li>
                            </ul>
                        </div>
                        <div v-else>
                            <ul class="ul_busqueda_lista lista_preloader">
                                <div class="preloader"></div>
                            </ul>
                        </div>
                    </div>
                    <div v-show="error" v-if="contenidopr.length<1">
                        <div v-for="err in errorproducto" :key="err" v-text="err" class="text-danger"></div>
                    </div>
        </div>
        <div class="vx-col w-full">
                    <div class="vx-row" v-if="producto_tipo">
                        <div class="vx-col sm:w-1/2 w-full">
                          <div class="cabezera_total">
                          </div>
                            <div v-if="liquidar">Costo Sin Liquidar <span>{{ liquidar | currency }}</span></div>
                            <div v-if="facturas">Costos Adicionales <span>{{ facturas | currency }}</span></div>
                            <div v-if="liquidar">Valor Total <span>{{ subtotalpr | currency }}</span></div>
                            <div v-else>Valor Total <span>{{ subtotalliq | currency }}</span></div>
                        </div>
                    </div>
        </div>
        <!--dividir-->
      </div>
      
      <div class="vx-col w-full">
          <!--<div class="vx-row">
            
              <vs-table class="w-full" :data="invoiceData">
                <vs-tr v-if="liquidar">
                  <vs-th>Costo Sin Liquidar</vs-th>
                  <vs-td>{{ liquidar | currency }}</vs-td>
                </vs-tr>
                <vs-tr v-if="facturas">
                  <vs-th>Costos Adicionales</vs-th>
                  <vs-td>{{ facturas | currency }}</vs-td>
                </vs-tr>
                <vs-tr v-if="liquidar">
                  <vs-th >Valor Total</vs-th>
                  <vs-td>{{ subtotalpr | currency }}</vs-td>
                </vs-tr>
                <vs-tr v-else>
                  <vs-th >Valor Total</vs-th>
                  <vs-td>{{ subtotalliq | currency }}</vs-td>
                </vs-tr>
                <vs-tr v-if="subtotalivapr12>0">
                  <vs-th>SUBTOTAL IVA 12%</vs-th>
                  <vs-td>{{ subtotalivapr12 | currency }}</vs-td>
                </vs-tr>
                <vs-tr v-if="ivapr12>0">
                  <vs-th>Valor IVA 12%</vs-th>
                  <vs-td>{{ ivapr12 | currency }}</vs-td>
                </vs-tr>
                <vs-tr v-if="subtotalivapr14>0">
                  <vs-th>SUBTOTAL IVA 14%</vs-th>
                  <vs-td>{{ subtotalivapr14 | currency }}</vs-td>
                </vs-tr>
                <vs-tr v-if="ivapr14>0">
                  <vs-th>Valor IVA 14%</vs-th>
                  <vs-td>{{ ivapr14 | currency }}</vs-td>
                </vs-tr>

                <vs-tr v-if="subtotalivapr0>0">
                  <vs-th>SUBTOTAL IVA 0%</vs-th>
                  <vs-td>{{ subtotalivapr0 | currency }}</vs-td>
                </vs-tr>
                <vs-tr v-if="ivapr0>0">
                  <vs-th>Valor IVA 0%</vs-th>
                  <vs-td>{{ ivapr0 | currency }}</vs-td>
                </vs-tr>
                <vs-tr v-if="subtotalivaprno>0">
                  <vs-th>NO OBJETO DE IMPUESTO</vs-th>
                  <vs-td>{{ subtotalivaprno | currency }}</vs-td>
                </vs-tr>
                <vs-tr v-if="ivaprno>0">
                  <vs-th>VALOR NO OBJETO DE IMPUESTO</vs-th>
                  <vs-td>{{ ivaprno | currency }}</vs-td>
                </vs-tr>
                <vs-tr v-if="subtotalivaprex>0">
                  <vs-th>EXENTO DE IVA</vs-th>
                  <vs-td>{{ subtotalivaprex | currency }}</vs-td>
                </vs-tr>
                <vs-tr v-if="ivaprex>0">
                  <vs-th>VALOR EXENTO DE IVA</vs-th>
                  <vs-td>{{ ivaprex | currency }}</vs-td>
                </vs-tr>

                <vs-tr>
                  <vs-th>TOTAL DESCUENTO</vs-th>
                  <vs-td>{{ descuentopr | currency }}</vs-td>
                </vs-tr>

                <vs-tr>
                  <vs-th>VALOR TOTAL</vs-th>
                  <vs-td>{{ totalpr | currency }}</vs-td>
                </vs-tr>
              </vs-table>
            
        </div>-->
        <vs-button color="success" type="filled" @click="guardar()" v-if="!$route.params.id">Guardar</vs-button>
        <vs-button color="success" type="filled" @click="editar()" v-else>Guardar</vs-button>
        <vs-button class="vs-con-loading__container" color="danger" type="filled" to="/importacion/registro-importacion">Cancelar</vs-button>
      </div>
       <vs-popup
            classContent="popup-example"
            title="Seleccione Proveedor"
            :active.sync="popupActive3"
          >
            <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
              <vs-input
                class="mb-4 mr-4 w-full"
                v-model="buscarprov"
                @keyup="listarproveedor(1,buscarprov,cantidadprov)"
                v-bind:placeholder="i18nbuscarprov"
              />
            </div>
            <vs-table
              stripe
              v-model="cuentaarray5"
              @selected="handleSelected5"
              :data="contenidoprov"
            >
              <template slot="thead">
                <vs-th>Nombre Proveedor</vs-th>
                <vs-th>Tipo Cuenta</vs-th>
              </template>
              <template slot-scope="{data}">
                <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                  <vs-td :data="data[indextr].nombre_proveedor">{{ data[indextr].nombre_proveedor }}</vs-td>
                  <vs-td
                    :data="data[indextr].identif_proveedor"
                  >{{ data[indextr].identif_proveedor }}</vs-td>
                </vs-tr>
              </template>
            </vs-table>
          </vs-popup>
       <div class="demo-alignment">
      <vs-popup classContent="popup-example" :title="titulomodal" :active.sync="popupActive2">
        <div class="vx-row">
          <div class="vx-col w-full" v-if="tipomodal==1">
            <vs-input
              class="mb-4 mr-4 w-full"
              v-model="buscar"
              @keyup.enter="listar(1,buscar)"
              v-bind:placeholder="i18nbuscar"
            />
            <vs-table stripe v-model="cuentaarray" @selected="handleSelected" :data="contenido">
              <template slot="thead">
                <vs-th>Nombre</vs-th>
                <vs-th>Identificación</vs-th>
                <vs-th>Dirección</vs-th>
                <vs-th>Telefono</vs-th>
              </template>
              <template slot-scope="{data}">
                <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                  <vs-td :data="data[indextr].nombre_proveedor">{{ data[indextr].nombre_proveedor }}</vs-td>
                  <vs-td :data="data[indextr].identif_proveedor">{{ data[indextr].identif_proveedor }}</vs-td>
                  <vs-td :data="data[indextr].direccion_prov">{{ data[indextr].direccion_prov }}</vs-td>
                  <vs-td :data="data[indextr].telefono_prov">{{ data[indextr].telefono_prov }}</vs-td>
                </vs-tr>
              </template>
              <template slot-scope="{data}">
                <vs-tr :data="datos" :key="index" v-for="(datos,index) in data">
                  <vs-td>{{datos.nombre_proveedor}}</vs-td>
                  <vs-td v-if="datos.identif_proveedor">{{datos.identif_proveedor}}</vs-td>
                  <vs-td v-if="datos.direccion_prov">{{datos.direccion_prov}}</vs-td>
                  <vs-td v-if="datos.telefono_prov">{{datos.telefono_prov}}</vs-td>
                  <vs-td v-else>-</vs-td>
                </vs-tr>
              </template>
            </vs-table>
          </div>
          <div class="vx-col w-full" v-else>
            <vs-input
              class="mb-4 mr-4 w-full"
              v-model="buscarp"
              @keyup.enter="listarp(1,buscarp)"
              v-bind:placeholder="i18nbuscar"
            />
            <vs-table stripe v-model="cuentaarrayp" @selected="handleSelectedp" :data="contenidop">
              <template slot="thead">
                <vs-th>Código</vs-th>
                <vs-th>Nombre</vs-th>
                <vs-th>Descripcion</vs-th>
                <vs-th>Marca</vs-th>
                <vs-th>Modelo</vs-th>
                <vs-th>Costo</vs-th>
              </template>
              <template slot-scope="{data}">
                <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                  <vs-td v-if="tr.cod_principal">{{tr.cod_principal}}</vs-td>
                  <vs-td v-else>-</vs-td>
                  <vs-td v-if="tr.nombre">{{tr.nombre}}</vs-td>
                  <vs-td v-else>-</vs-td>
                  <vs-td v-if="tr.descripcion">{{tr.descripcion}}</vs-td>
                  <vs-td v-else>-</vs-td>
                  <vs-td v-if="tr.nombremarca">{{tr.nombremarca}}</vs-td>
                  <vs-td v-else>-</vs-td>
                  <vs-td v-if="tr.nombremodelo">{{tr.nombremodelo}}</vs-td>
                  <vs-td v-else>-</vs-td>
                  <vs-td v-if="tr.costo_total">{{tr.costo_total}}</vs-td>
                  <vs-td v-else>-</vs-td>
                </vs-tr>
              </template>
            </vs-table>
          </div>
        </div>
      </vs-popup>
      <vs-popup classContent="popup-example" title="Cree Proveedor" :active.sync="popupActive4">
                <div class="con-exemple-prompt">
                    <ProveedorVue
                        v-if="popupActive4 == true"
                        factura="1"
                        :valores="datos_xml_prov"
                        @CreateProveedor="guardar_proveedor"
                        @CancelarCreate="cancelar_proveedor"
                    ></ProveedorVue>
                </div>
      </vs-popup>
    </div>
    </vx-card>
  </div>
</template>
<script>
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import vSelect from "vue-select";
import { AgGridVue } from "ag-grid-vue";
import moment from "moment";
const $ = require("jquery");
const axios = require("axios");
import ProveedorVue from "../Proveedor/ProveedorAgregar.vue";
export default {
    components: {
    flatPickr,
    "v-select": vSelect,
    ProveedorVue
  },
    data() {
        return {
          //variables
        idrecupera:null,
        cod_importacion:"",
        periodo_inicio:"",
        periodo_fin:"",
        id_proveedor:"",
        nro_orden:"",
        estado:"",
        fech_embarque:"",
        fech_arribo:"",
        liquidar:null,
        liquidacion:false,
        facturas:"",
        forma_liquidacion:"",
        configdateTimePicker: {
                locale: SpanishLocale
                },
        invoiceData: {
            tasks: [
              {
                id: 1,
                task: "Website Redesign",
                hours: 60,
                rate: 15,
                amount: 90000,
                iva: 15,
                ice: 15
              }
            ],
        subtotal: 114000,
        discountPercentage: 5,
        discountedAmount: 5700,
        total: 108300
        },
      
       //modal productos
       contenidop:[],
       contenidopr:[],
       popupActive2:false,
       titulomodal:"",
       tipomodal: 0,
       cuentaarray: [],
       cuentaarrayp: [],
       identificacion: "",
       contenidoempresa: [],
       buscarp: "",
       buscarpr: "",
       i18nbuscar:this.$t("i18nbuscar"),
       contenidoiva: [],
       proyectos_menu:[],
       listarbodegas: [],
       //errores
      //errores
      error: 0,
      errorproveedor:[],
      errorproducto:[],
      errorperiodo_inicio: [],
      errorperiodo_fin: [],
      errorcant_ingreso: [],
      errorcost_unit_ingreso: [],
      errorforma_liquidacion:[],
      //traer proveedor
      proveedors: [],
      //traer ordenes
      ordens:[],
      //modal proveedores
      paginaprov: 1,
      cantidadprov: 20,
      offset: 0,
      buscarprov: "",
      i18nbuscarprov: this.$t("i18nbuscar"),
      criterioprov: "nombre_proveedor",
      contenidoprov:[],
      cuentaarray5:[],
      producto_busqueda:"",
      preloader_productos:false,
      producto_tipo:false,
      //array proveedores
      valorproveedores: [
        // {
        //   id_importacion:null,
        //   id_proveedor:null,
        //   nombre: null,
        //   telefono: null,
        //   grupo: null,
        //   tipo_identificacion: null,
        //   identificacion: null,
        //   direccion: null,
        // }
      ],
      //variables crear cliente
      cliente: {
                tipo: false,
                busqueda: "",
                clientes: [],
                id_cliente: "",
                nombre: "",
                telefono: "",
                email: "",
                tipo_identificacion: "",
                identificacion: "",
                direccion: ""
      },
      datos_xml_prov: null,
      tipomodalprov:0,
      popupActive3:false,
      tipo_identificacion_menu: [
        { text: "Seleccione", value: 0 },
        { text: "Cédula de Identidad", value: "Cedula" },
        { text: "Ruc", value: "Ruc" },
        { text: "Pasaporte", value: "Pasaporte" },
        { text: "Extranjero", value: "Extranjero" },
        { text: "Consumidor Final", value: 4 }
      ],
      grupo_menu: [],
      //modal agregar proveedores
      preloader_prov:false,
      busqueda_cliente:"",
      proveedor_tipo:true,
      popupActive4:false,
      buscar: "",
      contenido:"",
      activePrompt3:false,
      codigo_proveedor: "",
      grupo: "",
      nombre: "",
      tipoIdent: "",
      identificacion: "",
      tipo: "",
      contribuyente: null,
      contribesp_valor:"0",
      contribuye_valor:"0",
      beneficiario: "",
      contacto: "",
      direccion: "",
      nrcasa: "",
      provincia: "",
      ciudad: "",
      telefono: "",
      estado: "",
      banco: "",
      tipCuenta: "",
      ctaBanco: "",
      idbanco: "",
      pago: "",
      plazo: "",
      dpagos: "",
      ctacontable: "",
      comentario: "",
      tcomprobante: "",
      serie: "",
      fvalidez: "",
      rangmin: "",
      ranmax: "",
      nroAutorizacion: "",
      contribuyeSri: null,
      tipElectronico: "0",
      impstRetencion: "I.R.F. Por Pagar (8%) Arriendos",
      impstRetencionporcent:"",
      retencionIva: "I.V.A. Retenido por Pagar (70%)",
      codSriImp: "",
      codSriIva: "",
      idContable: "",
      
      //traer grupo-proveedor
      grupos: [],
      //traer impuesto de retencion a la fuente
      impfuente: [],
      //traer impuesto de retencion al iva
      impiva: [],
      //traer tipo comprobante
      tipcomprob: [],
      //traer retencion fuente compra
      retfuente: [],
      ////traer retencion iva compra
      retiva: [],
      //traer
      provincias: [],
      ciudades: [],
      bancos: [],
      codigoen: 0,
      codigoprov: [],
      tipocod:0,
      contenidocuenta:[],
      retencion_nombre:"",
      retencion_iva:"",
      //errores proveedor
      errorprov: 0,
      errorcedula:0,
      errorrucprov:0,
      erroridentificacion: [],
      erroridentificacion2: false,
      erroridentificacion3: false,
      errorcodigo_proveedor: [],
      errorgrupo: [],
      errornombre: [],
      errortipoIdent: [],
      errortipo: [],
      errorcontribuyente: [],
      errorbeneficiario: [],
      errordireccion:[],
      errorprovincia:[],
      errorciudad:[],
      errorcontacto: [],
      //plan cuentas
        cuentaarray3:[],
        contenidoplanctas:[],
        i18nbuscarplanctas: this.$t("i18nbuscar"),
        buscarplactas:"",
    };
    },
  computed: {
    usuario() {
      return this.$store.state.AppActiveUser;
    },
    token() {
      return this.$store.state.Token;
    },
    // eliminar_provs(){
    //   if(this.valorproveedores){
    //     if(this.valorproveedores[0].nombre==null){
    //       this.valorproveedores.splice(0, 1);
    //       console.log("entra provs"+this.valorproveedores.length);
    //     }
    //   }
      
    // },
    validar_identificaion(){
            if(this.tipoIdent){
                if (this.tipoIdent == "Cedula") {
                    this.validarcedula();
                    //this.erroridentificacion3 =false;
                    if (this.erroridentificacion2 == true) {
                        this.validarcedula();
                        this.error = 1;
                    }
                } else {
                    //this.erroridentificacion2 =false;
                    if (this.tipoIdent == "Ruc") {
                        this.validarruc();
                        if (this.erroridentificacion3 == true) {
                            this.validarruc();
                            this.error = 1;
                        }
                    }else{
                      this.erroridentificacion3 =false;
                      this.erroridentificacion2 =false;
                    }
                }
            }
                
    },
    subtotalliq(){
      var total=0;
      this.contenidopr.forEach(el => {
        total += (el.precio*el.cantidad)
      });
      return total;
    },
    subtotalpr() {
      var total = 0;
      var total1 =0;
      this.contenidopr.forEach(el => {
        total += (el.precio*el.cantidad)
      });
      if(this.liquidar){
        total1 = parseFloat(this.facturas);
      }else{
         total1 = total;
      }
      return total1;
    },
    solonumeros($event) {
      let keyCode = $event.keyCode ? $event.keyCode : $event.which;
      if (keyCode < 48 || keyCode > 57) {
        // 46 is dot
        $event.preventDefault();
      }
    },
    solodecimales($event) {
      let keyCode = $event.keyCode ? $event.keyCode : $event.which;
      if ((keyCode < 48 || keyCode > 57) && keyCode !== 46) {
        // 46 is dot
        $event.preventDefault();
      }
    },
  },
    
   methods: {
        listarp(pagep, buscarp) {
      var url =
        "/api/productos/" +
        this.usuario.id_empresa +
        "?page=" +
        pagep +
        "&buscar=" +
        buscarp;
      axios.get(url).then(res => {
        var respuesta = res.data;
        this.contenidop = respuesta.recupera;
      });
    },
    handleSelected(tr) {
      this.popupActive2 = false;
      this.id_cliente = tr.id_proveedor;
      this.nombre = tr.nombre_proveedor;
      this.telefono = tr.telefono_prov;
      this.email = tr.grupo;
      this.tipo_identificacion = tr.tipo_identificacion;
      this.ruc_ci = tr.identif_proveedor;
      this.direccion = tr.direccion_prov;
    },
    handleSelected5(tr){
      
      this.popupActive3=false
      if(this.valorproveedores.length<1){
      this.valorproveedores.splice(0, 1);
      }
      if(this.valorproveedores.length<2){
        this.valorproveedores.push(
        {
          id_importacion:null,
          id_proveedor:tr.id_proveedor,
          nombre: tr.nombre_proveedor,
          telefono: tr.telefono_prov,
          grupo: tr.id_grupo_proveedor,
          tipo_identificacion: tr.tipo_identificacion,
          identificacion: tr.identif_proveedor,
          direccion: tr.direccion_prov,
          nombre_grupo:data.nombre_grupoprov
        },
      );
      }
    },
    // listarproveedor(pageprov, buscarprov) {
    //   var url =
    //     "/api/proveedor/" +
    //     this.usuario.id_empresa +
    //     "?page=" +
    //     pageprov +
    //     "&buscar=" +
    //     buscarprov;
    //   axios.get(url).then(res => {
    //     var respuesta = res.data;
    //     this.contenidoprov = respuesta.recupera;
        
    //   });
    // },
    listargrupprov() {
      this.idrecupera = this.$route.params.id;
      var url = "/api/abrirgrupprovorden";
      axios
        .get(url)
        .then(res => {
          this.grupo_menu = res.data;
        })
        .catch(err => {
          //console.log(err);
        });
    },
    borrarprov(id) {
      this.valorproveedores.splice(id, 1);
      if(this.valorproveedores.length<2){
        this.proveedor_tipo=true;
        this.busqueda_cliente="";
        //this.listar_cliente("");
      }else{
        this.proveedor_tipo=false;
      }
    },
    borrarproductos(id) {
      this.contenidopr.splice(id, 1);
    },
    abrirproductos() {
      this.popupActive2 = true;
      this.tipomodal = 2;
      this.titulomodal="Seleccione Producto"
      this.listarp(1, this.buscarp, this.cantidadpp);
    },
    listariva() {
      let me = this;
      var url = "/api/iva";
      axios
        .get(url)
        .then(function(response) {
          me.contenidoiva = response.data;
        })
        .catch(function(error) {
          //console.log(error);
        });
    },
    handleSelectedp(tr) {
      this.popupActive2 = false;
      this.contenidopr.push({
        id_producto: tr.id_producto,
        id_prodimp: null,
        nombre: tr.nombre,
        codigo: tr.cod_principal,
        cantidad: null,
        precio: null,
        descripcion: tr.descripcion,
        descuento: null,
        p_descuento:1,
        iva: tr.iva,
      });
    },
    guardar(){
      if (this.validarimport()) {
        return;
      }
       axios
        .post("/api/agregarimportacion", {
          cod_importacion: this.cod_importacion,
          nro_orden: this.nro_orden,
          estado: this.estado,
          periodo_inicio: this.periodo_inicio,
          periodo_fin: this.periodo_fin,
          fech_embarque: this.fech_embarque,
          fech_arribo: this.fech_arribo,

          //id_proveedor: this.id_proveedor,
          id_empresa: this.usuario.id_empresa,
          id_user: this.usuario.id,
          id_punto_emision: this.usuario.id_punto_emision,
          total_importacion: this.subtotalpr,
          forma_liquidacion:this.forma_liquidacion

        })
        .then(res => {
          this.guardarProductos(res.data);
          this.guardarProveedores(res.data);
            this.$vs.notify({
              title: "Registro Guardado",
              text: "Registro Guardado exitosamente",
              color: "success"
            });
            this.$router.push("/importacion/registro-importacion");
        })
        .catch(err => {
          this.$vs.notify({
            title: "Error al Guardar",
            text: "Verifique bien sus datos al momento de guardar",
            color: "danger"
          });
        });
    },
    guardarProductos(id){
      axios.post('/api/agregarprodimportacion',{
        id_import:id,
        productos:this.contenidopr
      });
    },
    //provedores
    listar_cliente(buscar){
            axios.get('/api/factura_compra/listar_proveedor?buscar=' + buscar + "&usuario=" + this.usuario.id_empresa).then( ({data}) => {
                $(".busqueda_lista_proveedor").show();
                this.contenidoprov = data;
                if(this.contenidoprov.length>0){
                  this.preloader_prov =false;
                }else{
                  this.preloader_prov =true;
                }
                
                //this.cliente.clientes = data;
            }).catch( error => {
                console.log(error);
            });
    },
    seleccionar_cliente(tr){
            this.contenidoprov=[];
            // if(this.valorproveedores.length<1){
            //   this.valorproveedores.splice(0, 1);
            //   this.proveedor_tipo=true;
            //   $(".busqueda_lista_proveedor").hide();
            // }
            //console.log(tr);
            //if(this.valorproveedores.length<2){
                  this.valorproveedores.push(
                  {
                      id_importacion:null,
                      id_proveedor:tr.id_proveedor,
                      nombre: tr.nombre_proveedor,
                      telefono: tr.telefono_prov,
                      grupo: tr.id_grupo_proveedor,
                      tipo_identificacion: tr.tipo_identificacion,
                      identificacion: tr.identif_proveedor,
                      direccion: tr.direccion_prov,
                      nombre_grupo:tr.nombre_grupoprov
                  },
                );
                this.proveedor_tipo=true;
                this.busqueda_cliente="";
                //this.listar_cliente("");
                $(".busqueda_lista_proveedor").show();
            //}
            // if(this.valorproveedores.length>=2){
            //   this.proveedor_tipo=false;
            //   $(".busqueda_lista_proveedor").hide();
            // }
            // this.cliente.clientes = [];
            // this.cliente.busqueda = '';
            // this.cliente.tipo = true;
            // this.cliente.id_cliente = tr.id_proveedor;
            // this.cliente.nombre = tr.nombre_proveedor;
            // this.cliente.telefono = tr.contacto;
            // this.cliente.email = '';
            // this.cliente.tipo_identificacion = tr.tipo_identificacion;
            // this.cliente.identificacion = tr.identif_proveedor;
            // this.cliente.direccion = tr.direccion_prov;
            //$(".busqueda_lista_proveedor").hide();
            //this.anticipover(tr.id_proveedor);
    },
    listar_productos(buscar){
            this.preloader_productos=false;
            $(".busqueda_producto_ls").show();
            if (this.timeout) {  
                clearTimeout(this.timeout);
            }
            this.timeout =  setTimeout(() => {
                axios.post('/api/notacredito/listar_productos1',{
                        buscar: buscar,
                        id_empresa: this.usuario.id_empresa,
                        id_establecimiento: this.usuario.id_establecimiento,
                        //cliente: this.cliente.id_cliente,
                }).then( ({data}) => {
                    this.preloader_productos=true;
                    if(data.length>=1){
                        if(data[0].codigo_barras == buscar && data[0].codigo_barras.length>=1){
                            this.seleccionar_productos(data[0]);
                            this.producto_busqueda = '';
                            return;
                        }else{
                            this.contenidop = data;
                            console.log
                            return;
                        }
                    }else{
                        this.contenidop = [];
                        return;
                    }
                }).catch( error => {
                    console.log(error);
                    this.preloader_productos=true;
                });
            }, 800);
    },
    seleccionar_productos(tr){
            this.contenidop = [];
            this.producto_busqueda = '';
            this.producto_tipo = true;
            var subtotal =  (tr.precio - tr.descuento).toFixed(2);
            var cantidad = 1;

            if(isNaN(parseInt(tr.existencia_total))){
                tr.existencia_total='';
            }
            if(isNaN(parseFloat(tr.precio))){
               tr.precio=''; 
            }
            if(isNaN(parseFloat(tr.descuento))){
                tr.descuento ='';
            }
            if(tr.sector==1 && (tr.id_producto_bodega==="undefined" || tr.id_producto_bodega == null)){
                cantidad = 0;
                tr.cantidad = 0;
                tr.id_producto_bodega = null;
                tr.nombrebodega = null;
            }
            var siiva = false;
            if(tr.iva==1){
                siiva = false;
            }else{
                siiva = true;
            }
            this.contenidopr.push({
                id_prodimp:null,
                id_producto_bodega:tr.id_producto_bodega,
                nombrebodega:tr.nombrebodega,
                id_producto: tr.id_producto,
                codigo: tr.cod_alterno!==null?tr.cod_alterno:tr.cod_principal,
                nombre: tr.nombre,
                cantidad: cantidad,
                cantidadreal: tr.cantidad,
                precio: tr.precio,
                descuento: tr.descuento,
                p_descuento: 1,
                subtotal: subtotal,
                iva: tr.iva,
                ice: tr.ice,
                sector: tr.sector,
                iva2: tr.iva,
                siiva: siiva,
                id_bodega:tr.id_bodega_prod,
                proyecto:this.proyectos_menu[0].id_proyecto
            });
            console.log(this.contenidopr);
            $(".focuspr").focus();
    },
    listarproyecto(page2, buscar2) {
            var url =
                "/api/listarproyecto/" +
                this.usuario.id_empresa +
                "?page=" +
                page2 +
                "&buscar=" +
                buscar2;
            axios.get(url).then(res => {
                var respuesta = res.data;
                // this.contenidoproyecto = respuesta.recupera;
                // console.log("Id_proyecto:"+respuesta.recupera[0].id_proyecto);
                // this.listaAsientoscontables[0].detalle.id_proyecto=respuesta.recupera[0].id_proyecto;
                // this.cabeceraAsientoContable.idProyecto=respuesta.recupera[0].id_proyecto;
                // this.id_proyecto_asiento=respuesta.recupera[0].id_proyecto;
                this.proyectos_menu=respuesta.recupera;
            });
    },
    bodegas(){
            axios.get("/api/factura_compra/traerbodegas?empresa=" + this.usuario.id_empresa + "&establecimiento=" + this.usuario.id_establecimiento).then( ({data}) => {
                this.listarbodegas = data;
            });
    },
    listarcuenta(buscar1){
            axios.get("/api/selcuentas/" +
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
        handleSelectedCuenta(tr){
            this.idContable = `${tr.id_plan_cuentas}`;
            this.ctacontable = `${tr.codcta}`;
        },
    guardarProveedores(id){
      axios.post('/api/agregarprovimportacion',{
        id_import:id,
        id_empresa:this.usuario.id_empresa,
        provds:this.valorproveedores
      });
    },
    editar() {
      if (this.validarimport()) {
        return;
      }
      axios
        .put("/api/actualizarimportacion", {
          id: this.idrecupera,
          cod_importacion: this.cod_importacion,
          nro_orden: this.nro_orden,
          estado: this.estado,
          periodo_inicio: this.periodo_inicio,
          periodo_fin: this.periodo_fin,
          fech_embarque: this.fech_embarque,
          fech_arribo: this.fech_arribo,

          cantidad: this.cantidad,
          costo_unit: this.costo_unit,
          costo_total: this.costo_total,
          //id_proveedor: this.id_proveedor,
          id_producto: this.id_producto,
          total_importacion:this.subtotalpr,
          forma_liquidacion:this.forma_liquidacion
        })
        .then(res => {
          this.editProductos(res.data);
          
        })
        .catch(err => {
          this.$vs.notify({
            title: "Error al Editar",
            text: "Verifique bien sus datos al momento de editar",
            color: "danger"
          });
        });
    },
    editProductos(id){
        
        axios.put("/api/actualizarprodimportacion",{
            id_orden:parseInt(id),
            productos: this.contenidopr
        }).then(resp=>{
          this.$vs.notify({
            title: "Registro Editado",
            text: "Registro Editado exitosamente",
            color: "success"
          });
          this.$router.push("/importacion/registro-importacion");
        }).catch(err=>{
          console.log("ERROR al guardar Productos:"+err);
        });
    },
    listarimport() {
      if (this.$route.params.id) {
        this.idrecupera = this.$route.params.id;
        this.num_prof = this.$route.params.id;
        var url = "/api/abririmportacion/"+this.idrecupera;
       
         axios
          .put(url)
          .then(res => {
            let data = res.data[0];

              (this.cod_importacion = data.cod_importacion),
              (this.nro_orden = data.id_orden),
              (this.estado = data.estado),
              (this.periodo_inicio = data.periodo_inicio),
              (this.periodo_fin = data.periodo_fin),
              (this.fech_embarque = data.fech_embarque),
              (this.fech_arribo = data.fech_arribo),
              (this.liquidar = data.total_liquidacion),
              (this.facturas = data.total_facturas),
              (this.forma_liquidacion = data.forma_liquidacion),
              (this.id_proveedor = data.id_proveedor);

          })
          .catch(err => {
            //console.log(err);
          });
      } else {
        this.idrecupera = null;
      }
    },
    listarprod() {
      if(this.$route.params.id){
        this.idrecupera = this.$route.params.id;
      }else{
        this.idrecupera = 0;
      }
      
      var url = "/api/traerproductoimport/" + this.idrecupera;
      axios
        .get(url)
        .then(res => {
                // id_producto_bodega:tr.id_producto_bodega,
                // nombrebodega:tr.nombrebodega,
                // id_producto: tr.id_producto,
                // codigo: tr.cod_principal,
                // nombre: tr.nombre,
                // cantidad: cantidad,
                // cantidadreal: tr.cantidad,
                // precio: tr.precio,
                // descuento: tr.descuento,
                // p_descuento: 1,
                // subtotal: subtotal,
                // iva: tr.iva,
                // ice: tr.ice,
                // sector: tr.sector,
                // iva2: tr.iva,
                // siiva: siiva,
                // id_bodega_prod:tr.id_bodega_prod
          this.contenidopr = res.data;
          if(this.$route.params.id){
            this.producto_tipo=true;
          }
          
        })
        .catch(err => {
          //console.log(err);
        });
    },
    listarprovs() {
      if(this.$route.params.id){
        this.idrecupera = this.$route.params.id;
      }else{
        this.idrecupera = 0;
      }
      var url = "/api/abrirproveedorimport/" + this.idrecupera;
      axios
        .get(url)
        .then(res => {
          this.valorproveedores = res.data;
          if(this.valorproveedores.length>=2){
            this.proveedor_tipo=false;
          }
        })
        .catch(err => {
        //console.log(err);
        });
    },
    validarimport() {
      this.error = 0;
      this.errorproveedor=[];
      this.errorproducto=[];
      this.errorperiodo_inicio = [];
      this.errorperiodo_fin = [];
      this.errorcant_ingreso = [];
      this.errorcost_unit_ingreso = [];
      this.errorforma_liquidacion=[];
      if (!this.periodo_inicio) {
        this.errorperiodo_inicio.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      if (!this.periodo_fin) {
        this.errorperiodo_fin.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      if(!this.valorproveedores.length){
        this.errorproveedor.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      if(!this.contenidopr.length){
        this.errorproducto.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      if(!this.forma_liquidacion){
        this.errorforma_liquidacion.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      for (var i = 0; i < this.contenidopr.length; i++) {
        this.contenidopr[i].errorcant_ingreso = [];
        this.contenidopr[i].errorcost_unit_ingreso = [];
        this.contenidopr[i].errorid_bodega = [];
        this.contenidopr[i].errorproyecto = [];
        if (!this.contenidopr[i].proyecto) {
          this.contenidopr[i].errorproyecto.push("Campo obligatorio");
          this.error = 1;
          window.scrollTo(0, 0);
        }
        if (!this.contenidopr[i].cantidad) {
          this.contenidopr[i].errorcant_ingreso.push("Campo obligatorio");
          this.error = 1;
          window.scrollTo(0, 0);
        }
        if (!this.contenidopr[i].precio) {
          this.contenidopr[i].errorcost_unit_ingreso.push("Campo obligatorio");
          this.error = 1;
          window.scrollTo(0, 0);
        }
        
        if(this.contenidopr[i].sector!==2){
          if(this.contenidopr[i].id_bodega==null){
                    this.contenidopr[i].errorid_bodega.push("Campo obligatorio");
                    this.error = 1;
                    window.scrollTo(0, 0);
                    console.log(6.3);
          }
                    
                    
        }
        
      }
      
      return this.error;
    },
    crear() {
      // this.listargrupprov();
      
      // this.getProvincias();
      // this.getCiudades();
      // this.getBancos();
      // this.getGrupo();
      // this.getImpFuente();
      // this.getImpIva();
      // this.getTipoComprob();
      // this.getRetFuente();
      // this.getRetIva();
      
      // this.listarplanctas(1,this.buscarplactas);
       this.popupActive4=true;
            
            // this.crear_cliente = {
            //     codigo: "",
            //     nombre: "",
            //     tipo_identificacion: { label: "Seleccione", value: 0 },
            //     identificacion: "",
            //     grupo_cliente: "",
            //     tipo_cliente: "",
            //     grupo_tributario: "",
            //     direccion: "",
            //     provincia: null,
            //     canton: null,
            //     parroquia: null,
            //     parte_relacionada: "",
            //     e_mail: "",
            //     telefono: "",
            //     contacto: "",
            //     vendedor: null,
            //     estado: null,
            //     descuento: "",
            //     cuenta_contable: "",
            //     id_cuenta_contable: null,
            //     numero_pagos: "",
            //     lista_precios: "",
            //     forma_pago: null,
            //     limite_credito: "",
            //     comentario: ""
            // };
    },
    getProveedor() {
      axios
        .get("/api/traerproveedorimport/" + this.usuario.id_empresa)

        .then(
          function(response) {
            this.proveedors = response.data;
          }.bind(this)
        );
    },
    getOrden() {
      axios
        .get("/api/traerorden/" + this.usuario.id_empresa)

        .then(
          function(response) {
            this.ordens = response.data;
          }.bind(this)
        );
    },
    //funcienes crear proveedores
     listarplanctas(pageplancta, buscarplactas) {
      let me = this;
      var url =
        "/api/cuentas/" +
        this.usuario.id_empresa +
        "?page=" +
        pageplancta +
        "&buscar=" +
        buscarplactas;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.contenidoplanctas = respuesta.recupera;
        })
        .catch(function(error) {
          //console.log(error);
        });
    },
    handleSelected3(tr) {
      /*this.$vs.notify({
        title: `Selected ${tr.codcta}`,
        text: `Email: ${tr.nomcta}`
      })*/
      (this.ctacontable = `${tr.codcta}`),
        (this.idContable = `${tr.id_plan_cuentas}`),
        (this.activePrompt3 = false);
    },
    getImpIva() {
      var por=0;
      if(this.retiva.length>=1){
        if(this.retencionIva != null){
          por=this.retiva[this.retencionIva].porcen_retencion;
        this.retencion_iva=this.retiva[this.retencionIva].descrip_retencion;
        }else{
          por=95;
        }
        
      }
      axios
        .get("/api/traerimpiva", {
          params: {
            porcen_imp: por
          }
        })
        .then(
          function(response) {
            if (response.data) {
              this.impiva = response.data;
            } else {
              this.impiva = 0;
            }
          }.bind(this)
        );
    },
    getImpFuente() {
      var r=0;
      var id_ret;
      if(this.retfuente.length>=1){
        if(this.impstRetencion != null){
          r=this.retfuente[this.impstRetencion].porcen_retencion
        this.retencion_nombre=this.retfuente[this.impstRetencion].descrip_retencion
        }else{
          r=95;
        }
        
        
      }
      axios
        .get("/api/traerimpfuente", {
          params: {
            
            porcen_impret: r
          }
        })
        .then(
          function(response) {
            if (response.data) {
              this.impfuente = response.data;
            } else {
              this.impfuente = 0;
            }
          }.bind(this)
        );
    },
    getProvincias: function() {
      axios.get("/api/traerprovinciaprov").then(
        function(response) {
          this.provincias = response.data;
          this.provs == this.id_provincia;
        }.bind(this)
      );
    },
    getCiudades: function() {
      axios
        .get("/api/traerciudadprov", {
          params: {
            provincia: this.provincia
          }
        })
        .then(
          function(response) {
            this.ciudades = response.data;
          }.bind(this)
        );
    },
    getBancos: function() {
      axios.get("/api/traerbancoprov").then(
        function(response) {
          this.bancos = response.data;
        }.bind(this)
      );
    },
    getGrupo() {
      axios.get("/api/traergruprov").then(
        function(response) {
          this.grupos = response.data;

        }.bind(this)
      );
    },
    getTipoComprob() {
      axios.get("/api/traertipcomprob").then(
        function(response) {
          this.tipcomprob = response.data;

        }.bind(this)
      );
    },
    getRetFuente() {
      axios.get("/api/traerretfuente").then(
        function(response) {
          this.retfuente = response.data;

        }.bind(this)
      );
    },
    getRetIva() {
      axios.get("/api/traerretiva").then(
        function(response) {
          this.retiva = response.data;

        }.bind(this)
      );
    },
    leercodigoprov() {
      if (!this.$route.params.id) {
        axios
          .get("/api/codigo?id=" + this.usuario.id_empresa)
          .then(res => {
            this.codigoprov = res.data;
            if(this.codigoprov=="vacio"){
              this.tipocod=1;
            }else{
              this.tipocod=0;
              this.codigo_proveedor = this.codigoprov;
            }
          });
      }
    },
    validarcedula($event) {
            this.errorcedula = 0;

            this.erroridentificacion2=false;
            this.erroridentificacion = [];
            if(!this.identificacion){
                this.erroridentificacion.push("Campo Obligatorio");
                this.errorcedula = 1;
                this.erroridentificacion2=true;
                
            }else{
                if (this.identificacion.length < 10 || this.identificacion.length > 10) {
                    this.erroridentificacion.push("Cedula invalida");
                    this.errorcedula = 1;
                    this.erroridentificacion2=true;
                
                }
            }
            
            if (
                typeof this.identificacion == "string" &&
                this.identificacion.length == 10 &&
                /^\d+$/.test(this.identificacion)
            ) {
                var digitos = this.identificacion.split("").map(Number);
                var codigo_provincia = digitos[0] * 10 + digitos[1];

                //if (codigo_provincia >= 1 && (codigo_provincia <= 24 || codigo_provincia == 30) && digitos[2] < 6) {

                if (
                    codigo_provincia >= 1 &&
                    (codigo_provincia <= 24 || codigo_provincia == 30)
                ) {
                    var digito_verificador = digitos.pop();

                    var digito_calculado =
                        digitos.reduce(function(
                            valorPrevio,
                            valorActual,
                            indice
                        ) {
                            return (
                                valorPrevio -
                                ((valorActual * (2 - (indice % 2))) % 9) -
                                (valorActual == 9) * 9
                            );
                        },
                        1000) % 10;
                    if (digito_calculado === digito_verificador) {
                        this.erroridentificacion = [];
                    } else {
                        this.erroridentificacion.push("Cédula inválida");
                        this.errorcedula = 1;
                        
                        this.erroridentificacion2=true;
                    }
                } else {
                    this.erroridentificacion.push("Cédula inválida");
                    this.errorcedula = 1;
                    
                    this.erroridentificacion2=true;
                }
            }
            return this.errorcedula;
        },
    validarruc($event) {
            this.errorrucprov = 0;
            this.erroridentificacion = [];
            this.erroridentificacion3=false;
            var numero = this.identificacion;
            var suma = 0;
            var residuo = 0;
            var pri = false;
            var pub = false;
            var nat = false;
            var numeroProvincias = 22;
            var modulo = 11;

            /* Verifico que el campo no contenga letras */
            var ok = 1;
            /*for (var i=0; i<numeroProvincias ;i++){
      alert('El código de la provincia (dos primeros dígitos) es inválido'); return false;
      }*/
            /*
      if (typeof(this.identificacion) == 'string' && this.identificacion.length == 10 && /^\d+$/.test(this.identificacion)) {
      var digitos = numero.split('').map(Number);
          var codigo_provincia = digitos[0] * 10 + digitos[1];
          
          //if (codigo_provincia >= 1 && (codigo_provincia <= 24 || codigo_provincia == 30) && digitos[2] < 6) {
            if(codigo_provincia<24 && codigo_provincia <= 1){
              this.erroridentificacion.push("Ruc inválido");
              this.error=1;
              return ;
            }
      }*/
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
                this.erroridentificacion.push(
                    "El tercer dígito ingresado es inválido"
                );
                this.errorrucprov = 1;

                

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
        if(!this.identificacion){
            this.erroridentificacion.push("Campo Obligatorio");
            this.erroridentificacion3=true;
            this.errorrucprov = 1;
            
        }else{
            if (pub == true) {
                if (digitoVerificador != d9) {
                    //console.log('El ruc de la empresa del sector público es incorrecto.');
                    this.erroridentificacion.push("Ruc invalido ");
                    this.erroridentificacion3=true;
                    this.errorrucprov = 1;
    
                }
                /* El ruc de las empresas del sector publico terminan con 0001*/
                if (numero.substr(9, 4) != "0001") {
                    this.erroridentificacion.push("Ruc invalido");
                    this.erroridentificacion3=true;
                    this.errorrucprov = 1;

                }
            } else if (pri == true) {
                if (digitoVerificador != d10) {
                    this.erroridentificacion.push("Ruc invalido");
                    this.erroridentificacion3=true;
                    this.errorrucprov = 1;

                }
                if (numero.substr(10, 3) != "001") {
                    this.erroridentificacion.push("Ruc invalido");
                    this.erroridentificacion3=true;
                    this.errorrucprov = 1;


                }
            } else if (nat == true) {
                if (digitoVerificador != d10) {
                    //console.log('El número de cédula de la persona natural es incorrecto.');
                    this.erroridentificacion.push("Ruc invalido");
                    this.erroridentificacion3=true;
                    this.errorrucprov = 1;
                    return false;
                }
                if (numero.length < 14 && numero.substr(10, 12) != "001") {
                    //console.log('El ruc de la persona natural debe terminar con 001');
                    this.erroridentificacion.push("Ruc invalido");
                    this.erroridentificacion3=true;
                    this.errorrucprov = 1;

                }
            }
            }
            return this.errorrucprov;
        },
   validarproveedor(){
      this.errorprov= 0;
      this.erroridentificacion= [];
      this.errorcodigo_proveedor= [];
      this.errorgrupo= [];
      this.errornombre= [];
      this.errortipoIdent= [];
      this.errortipo= [];
      this.errorcontribuyente= [];
      this.errorbeneficiario= [];
      this.errordireccion=[];
      this.errorprovincia=[];
      this.errorciudad=[];
      this.errorcontacto=[];
      if(!this.nombre){
        this.errornombre.push("Campo Obligatorio");
        this.errorprov=1;
        
      }
      if(!this.tipoIdent){
        this.errortipoIdent.push("Campo Obligatorio");
        this.errorprov=1;
      }else{
                if(this.tipoIdent=="Cedula"){
                    this.validarcedula();
                    if(this.erroridentificacion2==true){
                        this.validarcedula();
                        this.errorprov=1;
                    }  
                }else{
                    if(this.tipoIdent=="Ruc"){
                        this.validarruc();
                        if(this.erroridentificacion3==true){
                            this.validarruc();
                            this.errorprov=1;
                        }  
                    }
                }
            }
      if(!this.direccion){
        this.errordireccion.push("Campo Obligatorio");
        this.errorprov=1;
        
      }
      if(!this.contacto){
        this.errorcontacto.push("Campo Obligatorio");
        this.errorprov=1;
        
      }
      if(!this.provincia){
        this.errorprovincia.push("Campo Obligatorio");
        this.errorprov=1;
        
      }
      if(!this.ciudad){
        this.errorciudad.push("Campo Obligatorio");
        this.errorprov=1;
        
      }
      return this.errorprov;
   },
   crearproveedor(id){
      var url = "/api/actualizarprovimportacion/"+id;
      axios
        .get(url)
        .then(res => {
          let data = res.data[0];
          this.valorproveedores.push({
          id_importacion:null,
          id_proveedor:data.id_proveedor,
          nombre: data.nombre_proveedor,
          telefono: data.telefono_prov,
          grupo: data.id_grupo_proveedor,
          tipo_identificacion: data.tipo_identificacion,
          identificacion: data.identif_proveedor,
          direccion: data.direccion_prov,
          nombre_grupo:data.nombre_grupoprov
        },);
        })
        .catch(err => {
          //console.log(err);
        });
    },
    //funciones crear proveedor
        crear() {
          // this.listargrupprov();
          
          // this.getProvincias();
          // this.getCiudades();
          // this.getBancos();
          // this.getGrupo();
          // this.getImpFuente();
          // this.getImpIva();
          // this.getTipoComprob();
          // this.getRetFuente();
          // this.getRetIva();
          
          // this.listarplanctas(1,this.buscarplactas);
          this.popupActive4=true;
                
                // this.crear_cliente = {
                //     codigo: "",
                //     nombre: "",
                //     tipo_identificacion: { label: "Seleccione", value: 0 },
                //     identificacion: "",
                //     grupo_cliente: "",
                //     tipo_cliente: "",
                //     grupo_tributario: "",
                //     direccion: "",
                //     provincia: null,
                //     canton: null,
                //     parroquia: null,
                //     parte_relacionada: "",
                //     e_mail: "",
                //     telefono: "",
                //     contacto: "",
                //     vendedor: null,
                //     estado: null,
                //     descuento: "",
                //     cuenta_contable: "",
                //     id_cuenta_contable: null,
                //     numero_pagos: "",
                //     lista_precios: "",
                //     forma_pago: null,
                //     limite_credito: "",
                //     comentario: ""
                // };
        },
        guardar_proveedor(value) {
            console.log(JSON.stringify(value));
            axios
                .post("/api/agregarproveedor", {
                    cod_proveedor: value.cod_proveedor,
                    grupo: value.grupo,
                    nombre_proveedor: value.nombre_proveedor,
                    tipo_identificacion: value.tipo_identificacion,
                    identif_proveedor: value.identif_proveedor,
                    //tipo_proveedor:value.tipo,
                    contribuyente: value.contribuyente,
                    beneficiario: value.beneficiario,
                    //identif_benefic:value.identificacionBenf,
                    contacto: value.contacto,
                    email: value.email,
                    direccion_prov: value.direccion_prov,
                    nrcasa: value.nrcasa,
                    telefono_prov: value.telefono_prov,
                    //estado_prov: value.estado,
                    tipo_cuenta: value.tipo_cuenta,
                    cta_banco: value.cta_banco,
                    id: value.id,
                    //nrcta_interbancaria:value.nrctaInterbancaria,
                    pagos: value.pagos,
                    plazo: value.plazo,
                    dias_pago: value.dias_pago,
                    tip_comprob: value.tip_comprob,
                    serie: value.serie,
                    fvalidez: value.fvalidez,
                    comentario: value.comentario,
                    rangomax: value.rangomax,
                    rangomin: value.rangomin,
                    nrautorizacion: value.nrautorizacion,
                    contribuye_sri: value.contribuye_sri,
                    tip_electronico: value.tip_electronico,
                    imp_retencion: value.imp_retencion,
                    codsri_imp: value.codsri_imp,
                    retencion_iva: value.retencion_iva,
                    codsri_iva: value.codsri_iva,
                    cta_contable: value.cta_contable,
                    id_contable: value.id_contable,
                    id_provincia: value.id_provincia,
                    id_ciudad: value.id_ciudad,
                    id_banco: value.id_banco,
                    id_empresa: this.usuario.id_empresa,
                    tipo_contribuyente: value.tipo_contribuyente,
                    emails: value.emails,
                    factura_compra: 1
                })

                .then(res => {
                    if (res.data == "vacio") {
                        this.$vs.notify({
                            title: "Registro Guardado",
                            text: "Registro Guardado exitosamente",
                            color: "success"
                        });
                    }
                    if (res.data == "mal") {
                        this.$vs.notify({
                            title: "Este Proveedor ya existe",
                            text:
                                "La identificacion de este proveedor ya existe",
                            color: "danger"
                        });
                        return;
                    }

                    this.$vs.notify({
                        title: "Registro Guardado",
                        text: "Registro Guardado exitosamente",
                        color: "success"
                    });
                    // this.cliente.nombre = value.nombre_proveedor;
                    // this.cliente.telefono = value.telefono_prov;
                    // this.cliente.email = value.emails.toString();
                    // this.cliente.tipo_identificacion =
                    //     value.tipo_identificacion;
                    // this.cliente.identificacion = value.identif_proveedor;
                    // this.cliente.direccion = value.direccion_prov;
                    // this.cliente.id_cliente = res.data;
                    // this.cliente.tipo = true;
                    this.valorproveedores.push(
                      {
                          id_importacion:null,
                          id_proveedor:res.data,
                          nombre: value.nombre_proveedor,
                          telefono: value.telefono_prov,
                          grupo: value.grupo,
                          tipo_identificacion: value.tipo_identificacion,
                          identificacion: value.identif_proveedor,
                          direccion: value.direccion_prov,
                          //nombre_grupo:tr.nombre_grupoprov
                      },
                    );
                    this.proveedor_tipo=true;
                    this.busqueda_cliente="";
                    this.popupActive4=false;
                    // console.log(this.cliente.id_cliente);
                })
                .catch(err => {});
        },
        cancelar_proveedor(c) {
            this.cliente.nombre = "";
            this.cliente.telefono = "";
            this.cliente.email = "";
            this.cliente.tipo_identificacion = "";
            this.cliente.identificacion = "";
            this.cliente.direccion = "";
            this.cliente.id_cliente = "";
            this.cliente.tipo = false;
            this.cliente.busqueda = "";
            this.popupActive4=false;
        },

    },
    mounted() {
      //this.getProveedor();
      this.listarprovs();
      this.listariva();
      this.getOrden();
      this.listarimport();
      this.listarprod();
      this.leercodigoprov();
      this.listarproyecto(1, "");
      this.bodegas();
      //this.listarproveedor(1,this.buscarprov);
      
    },
};
</script>
<style lang="scss">
@import "@sass/vuexy/extraComponents/agGridStyleOverride.scss";
.vs-popup {
  width: 1060px !important;
}
.peque .vs-popup {
  width: 600px !important;
}
.sindis .vs-input--input:focus {
  border-top: none !important;
  border-left: none !important;
  border-right: none !important;
}
.valores input {
  text-align:end;
}
.valores .vs-input--placeholder {
  text-align:end;
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
.sindis .vs-input--input {
  border-top: none !important;
  border-left: none !important;
  border-right: none !important;
}
.nover > .icon-select {
  display: none;
}
.hovertrash:hover > .trasher {
  display: block !important;
}
.botonstl{
  height: 100%;
    width: 38px;
    border: 1px solid #635ace;
    background: transparent;
    color: #635ace;
    font-size: 16px;
    cursor: pointer;
}
.elejido{
  background: #635ace!important;
  color:#fff!important;
}
.busqueda_cliente input{
        height: 50px;
        padding-left: 45px!important;
    }
    .busqueda_cliente_icono{
        position: absolute!important;
        top: 11px;
        left: 25px;
    }
    .busqueda_lista{
        position: absolute;
        width: 97%;
        z-index: 9;
    }
    .cabezera_total span{
        float: right;
        margin-right: 25px;
    }
    .cabezera_total>div{
        margin-left: 20px;
        padding: 9px 3px;
    }
    .cabezera_total{
        margin-top:15px;
    }
    .ul_busqueda_lista{
        min-width: 160px;
        margin: -2px 0 0;
        list-style: none;
        font-size: 13.5px;
        text-align: left;
        background-color: #fff;
        border: 1px solid #ccc;
        border: 1px solid rgba(0,0,0,0.15);
        border-radius: 2px;
        -webkit-box-shadow: 0 6px 12px rgba(0,0,0,0.175);
        box-shadow: 0 6px 12px rgba(0,0,0,0.175);
        background-clip: padding-box;
    }
    .ul_busqueda_lista li{
        padding: 10px 16px;
        text-overflow: ellipsis;
        overflow: hidden;
        font-weight: 300;
        display: block;
        clear: both;
        line-height: 1.3;
        color: #333;
        white-space: nowrap;
        font-family: sans-serif;
    }
    .ul_busqueda_lista li:hover{
        background: rgba(16, 22, 58, 0.38);
        cursor: pointer;
        color:#fff;
    }
    .busqueda_cliente .input-span-placeholder{
        padding-left: 50px;
        margin-top: 3px;
    }
    .buscar_otro{
        position: absolute;
        margin-top: -35px;
        margin-left: 14px;
        cursor: pointer;
    }
    .lista_preloader{
        padding: 8px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .preloader {
        width: 50px;
        height: 50px;
        border: 10px solid #eee;
        border-top: 10px solid #666;
        border-radius: 50%;
        animation-name: girar;
        animation-duration: 2s;
        animation-iteration-count: infinite;
        animation-timing-function: linear;
    }
</style>
