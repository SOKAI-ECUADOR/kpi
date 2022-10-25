<template>
  <div id="invoice-page">
    <vx-card>

      <vs-divider position="left">
        <h3>Orden</h3>
      </vs-divider>
  
      <div class="vx-col sm:w-full w-full mb-3" v-if="num_prof">
        <h5 style="text-align:center">Número Orden: {{num_prof}}</h5>
      </div>
      <div class="vx-row leading-loose p-base">
        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Fecha Emisión:</h6>
          <flat-pickr :config="configdateTimePicker"
            class="w-full mt-3"
            v-model="date"
            placeholder="Seleccionar"
          />
        </div>
        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Fecha Expiración:</h6>
          <flat-pickr
            :config="configdateTimePicker"
            class="w-full mt-3"
            v-model="datex"
            placeholder="Seleccionar Fecha"
          />
          <div v-show="error" v-if="!datex">
            <div v-for="err in errorfech_validez" :key="err" v-text="err" class="text-danger"></div>
          </div>
        </div>

        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Formas de Pago:</h6>
          <!--<vs-select autocomplete class="selectExample w-full mt-3" v-model="forma_pago">
            <vs-select-item
              :key="index"
              :value="item.value"
              :text="item.text"
              v-for="(item,index) in forma_pago_array"
            />
            
          </vs-select>-->
          <vs-select
              class="selectExample"
              vs-multiple
              autocomplete
              v-model="forma_pago"
              @change="getFormaPago()"
            >
              <vs-select-item
                v-for="(data,index) in forma_pagos"
                :key="index"
                :value="data.id_forma_pagos"
                :text="data.descripcion"
              />
            </vs-select>
            
          <div v-show="error" v-if="!forma_pago">
          <div v-for="err in errorforma_pago" :key="err" v-text="err" class="text-danger"></div>
      </div>
        </div>
      </div>
      <!--Fin encabezado de comprobante-->
      <!--inicion encabezado de Cliente-->
      <vs-divider position="left">
        <h3>Proveedor</h3>
      </vs-divider>
      <!--fin modal-->
      <div class="vx-row leading-loose p-base">
        <div class="vx-col w-full mb-4">
          <div class="dropdown-button-container">
            <vs-dropdown>
              <a class="flex items-center">
                Añadir Proveedor
                <i class="material-icons">expand_more</i>
              </a>
              <div v-show="error" v-if="!id_cliente">
          <div v-for="err in errorproveedor" :key="err" v-text="err" class="text-danger"></div>
      </div>
              <vs-dropdown-menu>
                <vs-dropdown-item
                  class="text-center"
                  divider
                  @click="popupActive2=true,tipomodal=1"
                >Buscar Proveedor</vs-dropdown-item>
                <vs-dropdown-item class="text-center" divider @click="crear()">Crear Proveedor</vs-dropdown-item>
              </vs-dropdown-menu>
            </vs-dropdown>
          </div>
        </div>
        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Nombre:</h6>
          <vs-input disabled class="w-full" v-bind:value="nombre" />
        </div>
        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Teléfono:</h6>
          <vs-input disabled class="w-full" v-bind:value="telefono" />
        </div>

        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Grupo:</h6>
          <!--<vs-input disabled class="w-full" v-bind:value="email" />-->
          <vs-select disabled class="selectExample w-full nover" v-bind:value="email">
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
          <vs-select disabled class="selectExample w-full nover" v-bind:value="tipo_identificacion">
            <vs-select-item
              :key="index"
              :value="item.value"
              :text="item.text"
              v-for="(item,index) in tipo_identificacion_menu"
            />
          </vs-select>
        </div>

        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Identificación:</h6>
          <vs-input disabled class="w-full" v-bind:value="ruc_ci" />
        </div>

        <div class="vx-col sm:w-1/3 w-full mb-2">
          <h6>Dirección:</h6>
          <vs-input disabled class="w-full" v-bind:value="direccion" />
        </div>
      </div>
      <!--dividir-->
      <!--Fin encabezado de Cliente-->
      <!--INICIO DE TABLA DE PRODUCTOS -->
      <vs-divider position="left">
        <h3>Productos</h3>
      </vs-divider>
      <div class="p-base">
        <a class="flex items-center cursor-pointer mb-4" @click="abrirproductos()">Añadir Productos</a>
        <div v-show="error" v-if="contenidopr.length<1">
            <div v-for="err in errorproducto" :key="err" v-text="err" class="text-danger"></div>
          </div>
          
        <vs-table hoverFlat :data="contenidopr" style="font-size: 12px;">
            <template slot="thead">
              <!--<vs-th>CÓDIGO</vs-th>-->
              <vs-th>NOMBRE</vs-th>
              <vs-th>CANTIDAD</vs-th>
              <vs-th>COSTO</vs-th>
              <vs-th>DESCUENTO</vs-th>
              <!--<vs-th>IVA</vs-th>-->
              <vs-th>SUBTOTAL</vs-th>
              <vs-th>ELIMINAR</vs-th>
            </template>
            <template slot-scope="{data}">
              <vs-tr v-for="(tr, index) in data" :key="index">
                
                <!--<vs-td :data="tr.id_detalle_factura_compra>{{ tr.id_detalle_factura_compra }}</vs-td>-->
                <vs-td :data="tr.nombre">{{ tr.nombre }}</vs-td>
                <vs-td :data="tr.cantidad" style="width:150px!important;">
                  <vs-input class="w-full" placeholder="cantidad"  v-model="tr.cantidad" />
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
                    class="w-full"
                    
                    placeholder="$0.00"
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
                <vs-td :data="tr.descuento" style="width:200px!important;">
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
                <!--<vs-td :data="tr.iva" style="width:200px!important;">
                  <vs-select class="selectExample w-full" vs-multiple v-model="tr.iva">
                    <vs-select-item
                      :key="res.id_iva"
                      :value="res.id_iva"
                      :text="res.nombre"
                      v-for="res in contenidoiva"
                    />
                  </vs-select>
                </vs-td>-->
                <vs-td v-if="tr.p_descuento!=1" v-model="tr.sub">{{ (tr.cantidad*tr.precio-(((tr.cantidad*tr.precio)*tr.descuento)/100) )| currency }}</vs-td>
                <vs-td v-else v-model="tr.sub">{{ (tr.cantidad*tr.precio)-tr.descuento | currency }}</vs-td>
                <vs-td :data="tr.dolar">
                  <feather-icon
                    icon="TrashIcon"
                    svgClasses="w-5 h-5 hover:text-danger stroke-current"
                    class="ml-2 cursor-pointer"
                    @click="borrarprd(index)"
                  />
                </vs-td>
              </vs-tr>
            </template>
          </vs-table>
        <div class="vx-row mt-6">
          <div class="vx-col sm:w-1/2 w-full">
            <h6>Observaciones:</h6>
            <vs-textarea class="w-full" v-model="observacion" rows="2" />
          </div>
          <div class="vx-col sm:w-1/2 w-full">
            <vs-table hoverFlat class="w-full" :data="invoiceData">
              <vs-tr>
                <vs-th>SUBTOTAL SIN IMPUESTO</vs-th>
                <vs-td>{{ subtotalpr | currency }}</vs-td>
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
              <!--<vs-tr>
                <vs-th>PROPINA</vs-th>
                <vs-td>
                  <vx-input-group>
                    <vs-input class="w-full" placeholder="$0.00" v-model="propinapr" />
                    <template slot="append">
                      <div class="append-text btn-addon">
                        <vs-button
                          color="primary"
                          type="border"
                          class="is-active"
                          style="padding: 15px!important;height: 37px;line-height: 7px;"
                        >$</vs-button>
                        <vs-button
                          color="primary"
                          type="border"
                          style="padding: 15px!important;height: 37px;line-height: 7px;"
                        >%</vs-button>
                      </div>
                    </template>
                  </vx-input-group>
                </vs-td>
              </vs-tr>-->
              <vs-tr>
                <vs-th>VALOR TOTAL</vs-th>
                <vs-td>{{ totalpr | currency }}</vs-td>
              </vs-tr>
            </vs-table>
          </div>
        </div>
        <!--dividir-->
      </div>

      <div class="vx-col w-full">
        <vs-button color="success" type="filled" @click="guardar()" v-if="!$route.params.id">Guardar</vs-button>
        <vs-button color="success" type="filled" @click="editar()" v-else>Guardar</vs-button>
        <vs-button color="danger" type="filled" to="/compras/orden-compra">Cancelar</vs-button>
      </div>
    </vx-card>
    <!--MODAL LISTAR CLIENTE aqui -->
    <div class="demo-alignment">
      <vs-popup classContent="popup-example" title="Seleccione Proveedor" :active.sync="popupActive2">
        <div class="vx-row">
          <div class="vx-col w-full" v-if="tipomodal==1">
            <vs-input
              class="mb-4 mr-4 w-full"
              v-model="buscar"
              @keyup.enter="listar(1,buscar)"
              v-bind:placeholder="i18nbuscar"
            />
            <vs-table stripe max-items="25" pagination v-model="cuentaarray" @selected="handleSelected" :data="contenido">
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
            <vs-table stripe max-items="25" pagination v-model="cuentaarrayp" @selected="handleSelectedp" :data="contenidop">
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
                  <vs-td v-if="tr.cod_alterno">{{tr.cod_alterno}}</vs-td>
                  <vs-td v-else>{{ tr.cod_principal }}</vs-td>
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
         <div class="vx-row">
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <!--<vs-input  class="w-full" label="Codigo" placeholder="PR010" v-model="codigo_proveedor" />-->
            <vs-input class="w-full" v-if="tipocod" label="Código" v-model="codigo_proveedor" />
            <vs-input class="w-full" v-else label="Código" disabled :value="codigo_proveedor" />
          </div>
         
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <vs-select
              class="selectExample"
              label="Grupo"
              vs-multiple
              autocomplete
              v-model="grupo"
              @change="getGrupo()"
            >
              <vs-select-item
                v-for="(data,index) in grupos"
                :key="index"
                :value="data.id_grupoprov"
                :text="data.nombre_grupoprov"
              />
            </vs-select>
            
          </div>
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input
              type="email"
              class="w-full"
              label="Nombre"
              v-model="nombreprov"
            />
            <div v-show="errorprov" v-if="!nombreprov">
          <div v-for="err in errornombre" :key="err" v-text="err" class="text-danger"></div>
      </div>
          </div>
        </div>
        <div class="vx-row">
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <vs-select
              class="selectExample"
              label="Tipo Identificacion"
              vs-multiple
              autocomplete
              v-model="tipoIdent"
              v-validate="'required'"
              name="tipoid"
            >
              <vs-select-item value="Cedula" text="Cedula" />
              <vs-select-item value="Ruc" text="Ruc" />
              <vs-select-item value="Pasaporte" text="Pasaporte" />
            </vs-select>
            <div v-show="errorprov" v-if="!tipoIdent">
              <div v-for="err in errortipoIdent" :key="err" v-text="err" class="text-danger"></div>
            </div>
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6" v-if="!tipoIdent" hidden>
            <!--<vs-input  class="w-full" label="Identificacion"  v-model="identificacion" />-->
            <vs-input
              class="w-full"
              label="Identificacion Representante"
              v-model="identificacion"
              v-if="tipoIdent=='Cedula'"
              
              @keyup="validarcedula"
              maxlength="10"
            />
            <!--@keypress="solonumeros"-->
            <vs-input
              class="w-full"
              label="Identificacion Representante"
              v-model="identificacion"
              v-else-if="tipoIdent=='Ruc'"
              
              @keyup="validarruc"
              maxlength="13"
            />
            <!--@keypress="solonumeros"-->
            <vs-input
              class="w-full"
              label="Identificacion Representante"
              v-model="identificacion"
              v-else
              maxlength="19"
            />
            <div v-show="errorprov">
               <div v-for="err in erroridentificacion" :key="err" v-text="err" class="text-danger"></div>
            </div>
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-6" v-else>
            <!--<vs-input  class="w-full" label="Identificacion"  v-model="identificacion" />-->
            <vs-input
              class="w-full"
              label="Identificacion Representante"
              v-model="identificacion"
              v-if="tipoIdent=='Cedula'"
              
              @keyup="validarcedula"
              maxlength="10"
            />
            <!--@keypress="solonumeros"-->
            <vs-input
              class="w-full"
              label="Identificacion Representante"
              v-model="identificacion"
              v-else-if="tipoIdent=='Ruc'"
              
              @keyup="validarruc"
              maxlength="13"
            />
            <!--@keypress="solonumeros"-->
            <vs-input
              class="w-full"
              label="Identificacion Representante"
              v-model="identificacion"
              v-else
              maxlength="15"
            />
            <div v-show="errorcedula" v-if="tipoIdent == 'Cedula'">
              <span class="text-danger" v-for="err in erroridentificacion" :key="err" v-text="err"></span>
            </div>
            <div v-show="errorrucprov" v-else>
              <span class="text-danger" v-for="err in erroridentificacion" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <label class="vs-input--label">Contribuyente Especial</label>
            <vs-checkbox v-model="contribuyente" vs-value="1" ></vs-checkbox>
          </div>
         
        </div>
        <div class="vx-row">
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input class="w-full" label="Beneficiario" v-model="beneficiario" />
          </div>
        </div>
        <div class="vx-row">
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input class="w-full" label="Contacto" v-model="contacto" />
            <div v-show="errorprov" v-if="!contacto">
              <span class="text-danger" v-for="err in errorcontacto" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input class="w-full" label="Direccion" v-model="direccionprov" />
             <div v-show="errorprov" v-if="!direccionprov">
              <span class="text-danger" v-for="err in errordireccion" :key="err" v-text="err"></span>
            </div>
          </div>
        </div>

        <div class="vx-row">
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <vs-input class="w-full" label="No Casa" v-model="nrcasa" />
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <vs-select
              placeholder="Seleccione Provincia"
              class="selectExample w-full"
              label="Provincia"
              vs-multiple
              autocomplete
              v-model="provincia"
              v-validate="'required'" name="prov"
              @change="getCiudades()"
            >
              <!--<vs-select-item value=1 text="Pichincha" />-->
              <vs-select-item
                v-for="(data,index) in provincias"
                :key="index"
                :value="data.id_provincia"
                :text="data.nombre"
              />
            </vs-select>
            <div v-show="errorprov" v-if="!provincia">
              <span class="text-danger" v-for="err in errorprovincia" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <vs-select
              placeholder="Seleccione Ciudad"
              class="selectExample w-full"
              label="Ciudad"
              vs-multiple
              autocomplete
              v-model="ciudad"
              v-validate="'required'" name="ciud"
              @selected="getCiudades()"
            >
              <!--<vs-select-item value=1 text="Quito" />-->
              <vs-select-item
                v-for="(data,index) in ciudades"
                :key="index"
                :value="data.id_ciudad"
                :text="data.nombre"
              />
            </vs-select>
            <div v-show="errorprov" v-if="!ciudad">
              <span class="text-danger" v-for="err in errorciudad" :key="err" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <vs-input class="w-full" label="Telefono" v-model="telefonoprov" @keypress="solonumeros($event)"/>
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6" v-if="idrecupera">
            <vs-select
              class="selectExample"
              label="Estado"
              vs-multiple
              autocomplete
              v-model="estado"
              
            >
              <vs-select-item value="1" text="Activo" />
              <vs-select-item value="0" text="Inactivo" />
            </vs-select>
          </div>
        </div>
        <div class="vx-row">
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <vs-select
              placeholder="Seleccione Banco"
              class="selectExample w-full"
              label="Banco"
              vs-multiple
              autocomplete
              v-model="banco"
              @change="getBancos()"
            >
              <!--<vs-select-item value=1 text="Pichincha" />-->
              <vs-select-item
                v-for="(data,index) in bancos"
                :key="index"
                :value="data.id_banco"
                :text="data.nombre_banco"
              />
            </vs-select>
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <vs-select
              class="selectExample"
              label="Tipo Cuenta"
              vs-multiple
              autocomplete
              v-model="tipCuenta"
            >
              <vs-select-item value="1" text="Corriente" />
              <vs-select-item value="2" text="Ahorros" />
              <vs-select-item value="3" text="Virtual" />
            </vs-select>
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <vs-input class="w-full" label="Cuenta Banco" v-model="ctaBanco" />
          </div>
        </div>
        <div class="vx-row">
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <vs-input class="w-full" label="Pagos" v-model="pago" @keypress="solonumeros($event)"/>
          </div>
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <vs-input class="w-full" label="Plazo" v-model="plazo" @keypress="solonumeros($event)"/>
          </div>
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <vs-input class="w-full" label="Dias pago" v-model="dpagos" @keypress="solonumeros($event)"/>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6" >
            <label class="vs-input--label">Cuenta Contable</label>
            <vx-input-group class>
              <vs-input class="w-full" v-model="ctacontable" :value="idContable" disabled/>
              <template slot="append">
                <div class="append-text btn-addon">
                  <!-- -->
                  <vs-button color="primary" @click="activePrompt3 = true">Buscar</vs-button>
                </div>
              </template>
            </vx-input-group>
          </div>
        </div>
        <div>
          <label class="vs-input--label">Comentario</label>
          <vs-textarea v-model="comentario" height="80" />
        </div>
        <vs-divider position="left">Datos Sri</vs-divider>
        <div class="vx-row">
          
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <vs-select
              class="selectExample"
              label="Tipo Comprobante"
              vs-multiple
              autocomplete
              v-model="tcomprobante"
              @change="getTipoComprob()"
            >
              <vs-select-item
                v-for="(data,index) in tipcomprob"
                :key="index"
                :value="data.id_tipcomprobante"
                :text="data.descrip_tipcomprob"
              />
            </vs-select>
            <!--@change="getTipoComprob()"-->
          </div>
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <vs-input
              class="w-full"
              label="Serie"
              v-model="serie"
              
              maxlength="7"
            />
            <!--@keypress="solonumeros"-->
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <label class="vs-input--label">Fecha Validez</label>
            <flat-pickr :config="configdateTimePicker" v-model="fvalidez" />
          </div>
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <vs-input class="w-full" label="Factura Inicial" v-model="rangmin" />
          </div>
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <vs-input class="w-full" label="Factura Final" v-model="ranmax" />
          </div>
        </div>
        <div class="vx-row">
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input class="w-full" label="#Autorizacion" v-model="nroAutorizacion" />
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <label class="vs-input--label">Contribuye Sri</label>
            <vs-checkbox v-model="contribuyeSri" vs-value="1" ></vs-checkbox>
          </div>
          
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <ul class="demo-alignment">
              <li>
                <vs-radio v-model="tipElectronico" vs-value="0">Offline</vs-radio>
              </li>
              <li>
                <vs-radio v-model="tipElectronico" vs-value="1">Online</vs-radio>
              </li>
            </ul>
          </div>
        </div>
        <vs-divider position="left">Retenciones Aplicables</vs-divider>
        <div class="vx-row">
          <div class="vx-col sm:w-3/5 w-full mb-6">
            <vs-select
              class="selectExample w-full"
              label="Impuesto Retencion"
              vs-multiple
              autocomplete
              v-model="impstRetencion"
              @change="getImpFuente()"
            >
            <vs-select-item v-for="(data,index) in retfuente" :key="index" :value="index" :text="data.descrip_retencion"/>
            </vs-select>
           
          </div>
          <div class="vx-col sm:w-1/6 w-full mb-6"></div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <vs-select
              class="selectExample"
              label="Codigo Sri Impuesto"
              vs-multiple
              autocomplete
              v-model="codSriImp"
            >
              <vs-select-item
                v-for="(data,index) in impfuente"
                :key="index"
                :value="data.cod_imp"
                :text="data.cod_imp"
              />
            </vs-select>
            
          </div>
        </div>
        <div class="vx-row">
          <div class="vx-col sm:w-3/5 w-full mb-6">
            <vs-select
              class="selectExample w-full"
              label="Retencion Iva"
              vs-multiple
              autocomplete
              v-model="retencionIva"
              @change="getImpIva()"
            >
              <vs-select-item
                v-for="(data,index) in retiva"
                :key="index"
                :value="index"
                :text="data.descrip_retencion"
              />
            </vs-select>

          </div>
          <div class="vx-col sm:w-1/6 w-full mb-6"></div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <vs-select
              class="selectExample"
              label="Codigo Sri Iva"
              vs-multiple
              autocomplete
              v-model="codSriIva"
            >
              <vs-select-item
                v-for="(data,index) in impiva"
                :key="index"
                :value="data.cod_imp"
                :text="data.cod_imp"
              />
            </vs-select>
          </div>
        </div>
        <vs-divider />
        <div class="vx-col sm:w-1/6 w-full mb-6">
          <vs-input class="w-full" label="Cash Manager" v-model="idbanco" />
        </div>
        <vs-divider />
        <div class="vx-row">
          <div class="vx-col w-full">
            <vs-button color="success" type="filled" @click="guardarproveedor()">GUARDAR</vs-button>
            <vs-button color="warning" type="filled" @click="borrarproveedor()">BORRAR</vs-button>
            <vs-button color="danger" type="filled" @click="popupActive4=false,borrarproveedor()">CANCELAR</vs-button>
          </div>
        </div>
        <vs-popup title="Plan Cuentas" class="peque" :active.sync="activePrompt3">
          <div class="con-exemple-prompt">
            <vs-input
              class="mb-4 md:mb-0 mr-4 w-full"
              v-model="buscarplactas"
              @keyup="listarplanctas(1,buscarplactas)"
              v-bind:placeholder="i18nbuscarplanctas"
            />
            <vs-table stripe v-model="cuentaarray3" @selected="handleSelected3" :data="contenidoplanctas">
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
    </div>
  </div>
</template>
<script>
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import vSelect from "vue-select";
import { AgGridVue } from "ag-grid-vue";
import moment from "moment";
const axios = require("axios");
const $ = require("jquery");
import VueSimpleSuggest from 'vue-simple-suggest';
import 'vue-simple-suggest/dist/styles.css'
export default {
  data() {
    return {
      tipomodal: 0,
      cuentaarray: [],
      cuentaarrayp: [],
      identificacion: "",
      contenidoempresa: [],
      //cliente
      nombre: "",
      telefono: "",
      email: "",
      ruc_ci: "",
      direccion: "",
      observacion: "",
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
      item: {},
      items: [
        { id: 1, name: 'Golden Retriever'},
        { id: 2, name: 'Cat'},
        { id: 3, name: 'Squirrel'},
      ],
      
      pagina: 1,
      cantidadp: 10,
      offset: 3,
      paginationp: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
        count: 0
      },
      paginap: 1,
      cantidadpp: 10,
      offsetp: 3,
      paginationpr: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
        count: 0
      },
      paginapr: 1,
      cantidadppr: 10,
      offsetpr: 3,
      //buscador
      criterio: "nombre",
      //otros valores
      gridApi: null,
      buscar: "",
      buscarp: "",
      buscarpr: "",
      i18nbuscar: this.$t("i18nbuscar"),
      contenido: [],
      contenidop: [],
      contenidopr: [],
      value1: "",
      value2: "",
      popupActive2: false,
      popupActive3: false,
      forma_pago: "",
      forma_pago_array: "",
      tipo_identificacion: "",
      tipo_identificacion_array: "",
      num_prof: "",
      date: moment().format("YYYY-M-D"),
      datex: null,
      configdateTimePicker: {
        locale: SpanishLocale
      },
      tipo_identificacion_menu: [
        { text: "Seleccione", value: 0 },
        { text: "Cédula de Identidad", value: 1 },
        { text: "Ruc", value: 2 },
        { text: "Pasaporte", value: 3 },
        { text: "Consumidor Final", value: 4 }
      ],
      grupo_menu: [],
      forma_pago_array: [
        { text: "Seleccione", value: 0 },
        { text: "Efectivo", value: "Efectivo" },
        { text: "Cheque", value: "Cheque" },
        { text: "Tarjeta", value: "Tarjeta" },
        { text: "Crédito", value: "Crédito" }
      ],
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
      id_cliente: null,
      contenidoiva: [],
      contenidoice: [],
      total: null,
      propinapr: null,
      idrecupera: null,
      recupera: null,
      contenidoc: [],

      /*metodo_pago:null, 
      cantidad_pago:null, 
      comentario_compra:null,*/
      
      //errores
      error:0,
      errorproveedor:[],
      errorfech_validez:[],
      errorforma_pago:[],
      errorcant_ingreso:[],
      errorcost_unit_ingreso:[],
      errorproducto:[],
      //modal agregar proveedores
      popupActive4:false,
      activePrompt3:false,
      codigo_proveedor: "",
      grupo: "",
      nombreprov: "",
      tipoIdent: "",
      identificacion: "",
      tipo: "",
      contribuyente: null,
      contribesp_valor:"0",
      contribuye_valor:"0",
      beneficiario: "",
      //identificacionBenf:"",
      contacto: "",
      direccionprov: "",
      nrcasa: "",
      provincia: "",
      ciudad: "",
      telefonoprov: "",
      estado: "",
      banco: "",
      tipCuenta: "",
      ctaBanco: "",
      idbanco: "",
      //nrctaInterbancaria:"",
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
      //traer formas de pago
      forma_pagos:[],
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
      retencion_nombre:"",
      retencion_iva:"",
      //errores proveedor
      errorprov: 0,
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
      errorcedula:0,
      errorrucprov:0,
        //plan cuentas
        cuentaarray3:[],
        contenidoplanctas:[],
        i18nbuscarplanctas: this.$t("i18nbuscar"),
        buscarplactas:"",
      chosen: '',
      item: {},
      items: [
        { id: 1, name: 'Golden Retriever'},
        { id: 2, name: 'Cat'},
        { id: 3, name: 'Squirrel'},
      ],
    //prueba autocomplete
      nombres:[] ,
      autocomplete2:""
    };
  },
  computed: {
    usuario() {
      return this.$store.state.AppActiveUser;
    },
    token() {
      return this.$store.state.Token;
    },
    totalpagado() {
      var valor = 0;
      for (var i = 0; i < this.valorpagos.length; i++) {
        if (this.valorpagos[i].cantidad_pago > 0) {
          valor = valor + parseFloat(this.valorpagos[i].cantidad_pago);
        }
      }
      if (this.retencioniva <= 0) {
        var retiva = 0;
      } else {
        var retiva = this.retencioniva;
      }
      if (this.retencionrenta <= 0) {
        var retrenta = 0;
      } else {
        var retrenta = this.retencionrenta;
      }
      var total = parseFloat(retiva) + parseFloat(retrenta) + parseFloat(valor);
      return total;
    },
    subtotalpr() {
      var total = 0;
      this.contenidopr.forEach(el => {
        if(el.p_descuento==1){
          total += (el.precio * el.cantidad) -  el.descuento;
        }else{
          total +=el.precio * el.cantidad - (el.cantidad * el.precio * el.descuento) / 100;
        }
      });
      return total;
    },
    descuentopr() {
      var total = 0;
      this.contenidopr.forEach(el => {
        if (el.descuento > 0) {
          total += parseFloat(el.descuento);
        }
      });
      return total;
    },
    subtotalivapr12() {
      var total = 0;
      this.contenidopr.forEach(el => {
        if (el.iva == 2) {
          total += el.precio * el.cantidad - el.descuento;
        }
      });
      return total;
    },
    ivapr12() {
      var total = 0;
      this.contenidopr.forEach(el => {
        if (el.iva == 2) {
          total += (el.precio * el.cantidad - el.descuento) * 0.12;
        }
      });
      return total;
    },
    subtotalivapr14() {
      var total = 0;
      this.contenidopr.forEach(el => {
        if (el.iva == 5) {
          total += el.precio * el.cantidad - el.descuento;
        }
      });
      return total;
    },
    ivapr14() {
      var total = 0;
      this.contenidopr.forEach(el => {
        if (el.iva == 5) {
          total += (el.precio * el.cantidad - el.descuento) * 0.14;
        }
      });
      return total;
    },
    subtotalivapr0() {
      var total = 0;
      this.contenidopr.forEach(el => {
        if (el.iva == 1) {
          total += el.precio * el.cantidad - el.descuento;
        }
      });
      return total;
    },
    subtotalivaprno() {
      var total = 0;
      this.contenidopr.forEach(el => {
        if (el.iva == 3) {
          total += el.precio * el.cantidad - el.descuento;
        }
      });
      return total;
    },
    subtotalivaprex() {
      var total = 0;
      this.contenidopr.forEach(el => {
        if (el.iva == 4) {
          total += el.precio * el.cantidad - el.descuento;
        }
      });
      return total;
    },
    ivapr0() {
      var total = 0;
      return total;
    },
    ivaprno() {
      var total = 0;
      return total;
    },
    ivaprex() {
      var total = 0;
      return total;
    },
     
    totalpr() {
      var total = 0;
      var propina = 0;
      /*if (this.iva > 0) {
        propina = this.iva / 100;
      }*/
      this.contenidopr.forEach(el => {
        if(el.p_descuento==1){
          total += el.precio * el.cantidad - el.descuento;
        }else{
          total += (el.precio * el.cantidad) - ((el.precio * el.cantidad) *el.descuento)/100;
        }
      });
      var dato = total + this.ivapr12 + this.ivapr14; //-(total*parseFloat(propina))
      return dato;
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
   
    listproforma() {
      if (this.$route.params.id) {
        this.idrecupera = this.$route.params.id;
        //this.num_prof = this.$route.params.id;
        var url = "/api/abrirorden/"+this.idrecupera;
        axios
          .put(url)
          .then(res => {

            let data = res.data[0];
            this.date = data.fech_emision;
            this.datex = data.fech_validez;
            this.forma_pago = data.id_forma_pagos;
            this.observacion = data.observacion;
            this.num_prof = data.orden_compra;
          })
          .catch(err => {
          });
      } else {
        this.idrecupera = null;
      }
    },
    
    
    listarprov() {
      this.idrecupera = this.$route.params.id;
      var url = "/api/abrirprovorden/" + this.idrecupera;
      axios
        .get(url)
        .then(res => {
          if(res.data.length>0){
            this.cliente.tipo = true;
          }
          //this.contenidopr = res.data;
          this.contenido = res.data[0];
          this.id_cliente = this.contenido.id_proveedor;
          this.nombre = this.contenido.nombre_proveedor;
          this.telefono = this.contenido.telefono_prov;
          this.email = this.contenido.id_grupo;
          this.tipo_identificacion = this.contenido.tipo_identificacion;
          this.ruc_ci = this.contenido.identif_proveedor;
          this.direccion = this.contenido.direccion_prov;
        })
        .catch(err => {
        });
    },
    listargrupprov() {
      this.idrecupera = this.$route.params.id;
      var url = "/api/abrirgrupprovorden";
      axios
        .get(url)
        .then(res => {
          this.grupo_menu = res.data;
        })
        .catch(err => {
        });
    },
    listarprod() {
      if(this.$route.params.id){
        this.idrecupera = this.$route.params.id;
      }else{
        this.idrecupera =0;
      }
      
      var url = "/api/abrirprodorden/" + this.idrecupera;
      axios
        .get(url)
        .then(res => {
          console.log(res);
          if(res.data.length>0){
            this.producto.tipo = true;
          }
          this.contenidopr = res.data;
        })
        .catch(err => {
   
        });
    },
   
    listar(page, buscar) {
      var url =
        "/api/proveedor/" +
        this.usuario.id_empresa +
        "?page=" +
        page +
        "&buscar=" +
        buscar;
      axios.get(url).then(res => {
        var respuesta = res.data;
        this.contenido = respuesta.recupera;
        this.nombres = res.data.nombre_proveedor;
      });
    },
    crear() {
      this.popupActive4=true;
    },
    abrirproductos() {
      this.popupActive2 = true;
      this.tipomodal = 2;
      this.listarp(1, this.buscarp, this.cantidadpp);
    },
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
    handleSelectedp(tr) {
      this.popupActive2 = false;

      this.contenidopr.push({
        id_producto: tr.id_producto,
        id_detalle_factura_compra: null,
        nombre: tr.nombre,
        codigo: tr.cod_principal,
        cantidad: null,
        precio: null,//tr.pvp_precio1,
        descripcion: tr.descripcion,
        descuento: null,//tr.descuento,
        p_descuento:1,
        iva: tr.iva,
        ice:tr.ice
      });
      /*this.id_cliente = tr.id_cliente;
      this.nombre = tr.nombre;
      this.telefono = tr.telefono;
      this.email = tr.email;
      this.tipo_identificacion = tr.tipo_identificacion;
      this.ruc_ci = tr.identificacion;
      this.direccion = tr.direccion;*/
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

        });
    },
    zeroFill(number, width) {
      width -= number.toString().length;
      if (width > 0) {
        return (
          new Array(width + (/\./.test(number) ? 2 : 1)).join("0") + number
        );
      }
      return number + "";
    },
    borrarprd(id) {
      this.contenidopr.splice(id, 1);
    },
    guardarProducto(id_orden){
        axios.post("/api/guardarprodord",{
            id_orden:id_orden,
            productos:this.contenidopr,
        });
    },
    guardar() {
      if(this.validarorden()){
        return;
      }
      axios
        .post("/api/guardarorden", {
        f_emision:this.date,
        f_validez:this.datex,
        forma_pago:this.forma_pago,
        subtotal_sinimp:this.subtotalpr,
        subtotal_iva12:this.subtotalivapr12,
        valor_12:this.ivapr12,
        subtotal_iva0:this.subtotalivapr0,
        sin_imp:this.subtotalivaprno,
        total_descuento:this.descuentopr,
        total_orden:this.totalpr,
        observacion:this.observacion,
        id_proveedor:this.id_cliente,
        id_empresa:this.usuario.id_empresa,
        id_establecimiento:this.usuario.id_establecimiento,
        id_punto_emision:this.usuario.id_punto_emision,
        id_user:this.usuario.id,
        productos: this.contenidopr
        })
        .then(res => {
            this.guardarProducto(res.data);
          this.$vs.notify({
            title: "Orden Guardada",
            text: "Orden agregada con éxito",
            color: "success"
          });
          this.$router.push("/compras/orden-compra");
        }).catch(err =>{

        });
    },
    
    editar() {
      console.log(this.contenidopr);
      axios
        .put("/api/actualizarorden", {
          id: this.$route.params.id,
        f_emision:this.date,
        f_validez:this.datex,
        forma_pago:this.forma_pago,
        subtotal_sinimp:this.subtotalpr,
        subtotal_iva12:this.subtotalivapr12,
        valor_12:this.ivapr12,
        subtotal_iva0:this.subtotalivapr0,
        sin_imp:this.subtotalivaprno,
        total_descuento:this.descuentopr,
        total_orden:this.totalpr,
        observacion:this.observacion,
        id_proveedor:this.id_cliente,
        id_empresa:this.usuario.id_empresa,
        id_establecimiento:this.usuario.id_establecimiento,
        id_punto_emision:this.usuario.id_punto_emision,
        id_user:this.usuario.id,
        productos: this.contenidopr
        })
        .then(res => {
            this.editProductos(res.data);
          this.$vs.notify({
            title: "Orden Editada",
            text: "Orden editada con éxito",
            color: "success"
          });
          this.$router.push("/compras/orden-compra");
        });
    },
    editProductos(id){
        
        axios.put("/api/actualizarprodorden",{
            id_orden:parseInt(id),
            productos: this.contenidopr
        }).then(res=>{

        }).catch(err => {

        });
    },
    validarorden(){
      this.error=0;
      this.errorproveedor=[];
      this.errorfech_validez=[];
      this.errorforma_pago=[];
      this.errorcant_ingreso=[];
      this.errorcost_unit_ingreso=[];
      this.errorproducto=[];
      if(!this.id_cliente){
        this.errorproveedor.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      if(!this.contenidopr.length){
        this.errorproducto.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      if(!this.datex){
        this.errorfech_validez.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      if(!this.forma_pago){
        this.errorforma_pago.push("Campo obligatorio");
        this.error = 1;
        window.scrollTo(0, 0);
      }
      for (var i = 0; i < this.contenidopr.length; i++) {
        this.contenidopr[i].errorcant_ingreso = [];
        this.contenidopr[i].errorcost_unit_ingreso = [];
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
      }
      return this.error;
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
;
        });
    },
    handleSelected3(tr) {
      /*this.$vs.notify({
        title: `Selected ${tr.codcta}`,
        text: `Email: ${tr.nomcta}`
      })*/
      (this.ctacontable = `${tr.nomcta}`),
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
          //this.provs==this.id_provincia
        }.bind(this)
      );
    },
    getGrupo() {
      axios.get("/api/traergruprov").then(
        function(response) {
          this.grupos = response.data;
          //this.provs==this.id_provincia
        }.bind(this)
      );
    },
    getFormaPago() {
      axios.get("/api/abrirformapago").then(
        function(response) {
          this.forma_pagos = response.data;
          //this.provs==this.id_provincia
        }.bind(this)
      );
    },
    
    getTipoComprob() {
      axios.get("/api/traertipcomprob").then(
        function(response) {
          this.tipcomprob = response.data;
          //this.provs==this.id_provincia
        }.bind(this)
      );
    },
    getRetFuente() {
      axios.get("/api/traerretfuente").then(
        function(response) {
          this.retfuente = response.data;
          //this.provs==this.id_provincia
        }.bind(this)
      );
    },
    getRetIva() {
      axios.get("/api/traerretiva").then(
        function(response) {
          this.retiva = response.data;
          //this.provs==this.id_provincia
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
    guardarproveedor(){
      if(this.validarproveedor()){
        
        return;
      }
       axios
            .post("/api/agregarproveedor", {
              cod_proveedor: this.codigo_proveedor,
              grupo: this.grupo,
              nombre_proveedor: this.nombreprov,
              tipo_identificacion: this.tipoIdent,
              identif_proveedor: this.identificacion,
              //tipo_proveedor:this.tipo,
              contribuyente: this.contribuyente,
              beneficiario: this.beneficiario,
              //identif_benefic:this.identificacionBenf,
              contacto: this.contacto,
              direccion_prov: this.direccionprov,
              nrcasa: this.nrcasa,
              telefono_prov: this.telefonoprov,
              //estado_prov: this.estado,
              tipo_cuenta: this.tipCuenta,
              cta_banco: this.ctaBanco,
              id: this.idbanco,
              //nrcta_interbancaria:this.nrctaInterbancaria,
              pagos: this.pago,
              plazo: this.plazo,
              dias_pago: this.dpagos,
              tip_comprob: this.tcomprobante,
              serie: this.serie,
              fvalidez: this.fvalidez,
              comentario: this.comentario,
              rangomax: this.ranmax,
              rangomin: this.rangmin,
              nrautorizacion: this.nroAutorizacion,
              contribuye_sri: this.contribuyeSri,
              tip_electronico: this.tipElectronico,
              imp_retencion: this.impstRetencion,
              codsri_imp: this.codSriImp,
              retencion_iva: this.retencionIva,
              codsri_iva: this.codSriIva,
              cta_contable: this.ctacontable,
              id_contable:this.idContable,
              id_provincia: this.provincia,
              id_ciudad: this.ciudad,
              id_banco: this.banco,
              id_empresa: this.usuario.id_empresa,
            })
            .then(res => {
              this.crearproveedor(res.data);
              if (res.data != "existe") {
                (this.popupActive4 = false),
                this.borrarproveedor();
              } else {
                alert("No se Encuentra Cuenta Contable");
                this.popupActive4 = false
                this.borrarproveedor();
                //console.log(res);
              }
              
            }).catch(err => {});
    },
    borrarproveedor(){
      (this.grupo = ""),
        (this.nombreprov = ""),
        (this.tipoIdent = ""),
        (this.identificacion = ""),
        (this.tipo = ""),
        (this.contribuyente = null),
        (this.beneficiario = ""),
        //this.identificacionBenf="",
        (this.contacto = ""),
        (this.direccionprov = ""),
        (this.nrcasa = ""),
        (this.provincia = ""),
        (this.ciudad = ""),
        (this.telefonoprov = ""),
        (this.estado = ""),
        (this.banco = ""),
        (this.tipCuenta = ""),
        (this.ctaBanco = ""),
        (this.idbanco = ""),
        //this.nrctaInterbancaria="",
        (this.pago = ""),
        (this.plazo = ""),
        (this.dpagos = ""),
        (this.ctacontable = ""),
        (this.idContable = ""),
        (this.comentario = ""),
        (this.tcomprobante = ""),
        (this.serie = ""),
        (this.fvalidez = ""),
        (this.rangmin = ""),
        (this.ranmax = ""),
        (this.nroAutorizacion = ""),
        (this.contribuyeSri = null),
        (this.tipElectronico = "0"),
        (this.impstRetencion = null),
        (this.retencionIva = null),
        
        //(this.retencionIva = ""),
        //(this.retencion_iva = ""),
        (this.codSriImp = ""),
        (this.codSriIva = "");

    },
     validarcedula($event) {
            this.errorcedula = 0;
            //this.error = 0;
            this.erroridentificacion2=false;
            this.erroridentificacion = [];
            if(!this.identificacion){
                this.erroridentificacion.push("Campo Obligatorio");
                this.errorcedula = 1;
                this.erroridentificacion2=true;
                window.scrollTo(0, 0);
            }else{
                if (this.identificacion.length < 10) {
                this.erroridentificacion.push("Cedula invalida");
                this.errorcedula = 1;
                this.erroridentificacion2=true;
                window.scrollTo(0, 0);
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
                    //console.log(digito_calculado);
                    //return digito_calculado === digito_verificador;
                    if (digito_calculado === digito_verificador) {
                        this.erroridentificacion = [];
                    } else {
                        this.erroridentificacion.push("Cédula inválida");
                        this.errorcedula = 1;
                        window.scrollTo(0, 0);
                        this.erroridentificacion2=true;
                    }
                } else {
                    this.erroridentificacion.push("Cédula inválida");
                    this.errorcedula = 1;
                    window.scrollTo(0, 0);
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
                //return false;
                window.scrollTo(0, 0);

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
            window.scrollTo(0, 0);
        }else{
            if (pub == true) {
                if (digitoVerificador != d9) {
                    //console.log('El ruc de la empresa del sector público es incorrecto.');
                    this.erroridentificacion.push("Ruc invalido ");
                    this.erroridentificacion3=true;
                    this.errorrucprov = 1;
                    //return false;
                    window.scrollTo(0, 0);
                    console.log("Error ruc");
                }
                /* El ruc de las empresas del sector publico terminan con 0001*/
                if (numero.substr(9, 4) != "0001") {
                    this.erroridentificacion.push("Ruc invalido");
                    this.erroridentificacion3=true;
                    this.errorrucprov = 1;
                    //return false;
                    window.scrollTo(0, 0);
                    console.log("Error ruc");
                }
            } else if (pri == true) {
                if (digitoVerificador != d10) {
                    this.erroridentificacion.push("Ruc invalido");
                    this.erroridentificacion3=true;
                    this.errorrucprov = 1;
                    //return false;
                    window.scrollTo(0, 0);
                    console.log("Error ruc");
                }
                if (numero.substr(10, 3) != "001") {
                    this.erroridentificacion.push("Ruc invalido");
                    this.erroridentificacion3=true;
                    this.errorrucprov = 1;
                    //return false;
                    window.scrollTo(0, 0);
                    console.log("Error ruc");
                }
            } else if (nat == true) {
                if (digitoVerificador != d10) {
                    //console.log('El número de cédula de la persona natural es incorrecto.');
                    this.erroridentificacion.push("Ruc invalido");
                    this.erroridentificacion3=true;
                    this.errorrucprov = 1;
                    return false;
                    window.scrollTo(0, 0);
                    console.log("Error ruc");
                }
                if (numero.length < 14 && numero.substr(10, 12) != "001") {
                    //console.log('El ruc de la persona natural debe terminar con 001');
                    this.erroridentificacion.push("Ruc invalido");
                    this.erroridentificacion3=true;
                    this.errorrucprov = 1;
                    //return false;
                    window.scrollTo(0, 0);
                    console.log("Error ruc");
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
      if(!this.nombreprov){
        this.errornombre.push("Campo Obligatorio");
        this.errorprov=1;
        $(".vs-popup--content").scrollTop(0);
      }
      if(!this.tipoIdent){
        this.errortipoIdent.push("Campo Obligatorio");
        this.errorprov=1;
        $(".vs-popup--content").scrollTop(0);
      }else{
        if(this.tipoIdent=="Cedula"){
                    this.validarcedula();
                    if(this.erroridentificacion2==true){
                        this.validarcedula();
                        this.errorprov = 1;
                    }  
                }else{
                    if(this.tipoIdent=="Ruc"){
                        this.validarruc();
                        if(this.erroridentificacion3==true){
                            this.validarruc();
                            this.errorprov = 1;
                        }  
                    }
                }
      }
      if(!this.direccionprov){
        this.errordireccion.push("Campo Obligatorio");
        this.errorprov=1;
        $(".vs-popup--content").scrollTop(0);
      }
      if(!this.contacto){
        this.errorcontacto.push("Campo Obligatorio");
        this.errorprov=1;
        $(".vs-popup--content").scrollTop(0);
      }
      if(!this.provincia){
        this.errorprovincia.push("Campo Obligatorio");
        this.errorprov=1;
        $(".vs-popup--content").scrollTop(0);
      }
      if(!this.ciudad){
        this.errorciudad.push("Campo Obligatorio");
        this.errorprov=1;
        $(".vs-popup--content").scrollTop(0);
      }
      return this.errorprov;
   },
   crearproveedor(id){
      var url = "/api/actualizarprovimportacion/"+id;
      axios
        .get(url)
        .then(res => {
          let data = res.data[0];
           this.id_cliente = data.id_proveedor;
           this.nombre = data.nombre_proveedor;
           this.telefono = data.telefono_prov;
           this.email = data.grupo;
           this.tipo_identificacion = data.tipo_identificacion;
           this.ruc_ci = data.identif_proveedor;
           this.direccionprov = data.direccion_prov;
        })
        .catch(err => {
          console.log(err);
        });
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
    this.listproforma();
    this.listarprov();
    this.listargrupprov();
    this.listarprod();
    this.listar(1, this.buscar, this.cantidadp);
    this.listarp(1, this.buscarp, this.cantidadpp);
    this.listariva();
    this.listarplanctas(1,this.buscarplactas);
    this.getFormaPago();
    this.getProvincias();
    this.getCiudades();
    this.getBancos();
    this.getGrupo();
    this.getImpFuente();
    this.getImpIva();
    this.getTipoComprob();
    this.getRetFuente();
    this.getRetIva();
    this.leercodigoprov();
  },
  components: {
    flatPickr,
    "v-select": vSelect,
    VueSimpleSuggest
  }
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
</style>
