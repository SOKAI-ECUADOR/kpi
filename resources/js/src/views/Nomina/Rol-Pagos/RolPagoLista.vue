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
          <vs-input
            class="mb-4 md:mb-0 mr-4"
            v-model="buscar"
            @keyup.enter="listar(1,buscar)"
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
          <div class="dropdown-button-container">
            <div class="vx-row">
              <div class="vx-col w-full">
                <div class="vx-col w-full">
                  <vs-button class="btnx" type="filled" to="/nomina/rol-pagos/agregar">Agregar</vs-button>
                </div>
              </div>
            </div>
            <!--<vs-dropdown>
              <vs-button class="btn-drop" type="filled" icon="expand_more"></vs-button>
              <vs-dropdown-menu style="width:13em;">
                <vs-dropdown-item class="text-center" @click="importar=true">Importar registros</vs-dropdown-item>
                <vs-dropdown-item class="text-center" @click="exportar=true">Exportar registros</vs-dropdown-item>
                <vs-dropdown-item class="text-center" divider>Generar PDF</vs-dropdown-item>
                <vs-dropdown-item class="text-center">Generar XML</vs-dropdown-item>
              </vs-dropdown-menu>
            </vs-dropdown>-->
          </div>
        </div>
      </div>

      <vs-table stripe max-items="25" pagination :data="contenido">
        <template slot="thead">
          <vs-th>Mes</vs-th>
          <vs-th>Departamento</vs-th>
          <!--<vs-th>Total Ingresos</vs-th>
          <vs-th>Total Egresos</vs-th>-->
          <vs-th>Acciones</vs-th>
        </template>
        <template slot-scope="{data}">
       <vs-tr :key="datos.cod_rol_pago" v-for="datos in data">
            <vs-td v-if="datos.fechrol">{{datos.fechrol |fecha |upper}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.dep_nombre">{{datos.dep_nombre}}</vs-td>
            <vs-td v-else>-</vs-td>
            <!--<vs-td v-if="datos.total_ingreso">{{datos.total_ingreso}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.total_egreso">{{datos.total_egreso}}</vs-td>
            <vs-td v-else>0.00</vs-td>-->
            <vs-td class="whitespace-no-wrap">
              <!--<feather-icon
              
                icon="EditIcon"
                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                class="cursor-pointer"
                @click.stop="editar(datos.cod_rol_pago)"
              />-->
              <feather-icon
                v-if="datos.cont=='0'"
                icon="EditIcon"
                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                class="cursor-pointer"
                @click.stop="editar(datos.cod_rol_pago)"
              />
              <feather-icon
                v-else
                icon="EyeIcon"
                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                class="cursor-pointer"
                @click.stop="ver(datos.cod_rol_pago)"
              />
              <feather-icon
                
                icon="TrashIcon"
                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                class="cursor-pointer"
                v-if="eliminarroles && datos.cont=='0'"
                @click.stop="eliminarrol(datos.cod_rol_pago)"
              />
              <feather-icon
                icon="MailIcon"
                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                class="cursor-pointer"
                @click.stop="generarPapeletas(datos.id_departamento,datos.fechrol)"
              />
              <!--,enviaremail(datos.id_departamento,datos.fechrol)-->
              <feather-icon
                icon="ClipboardIcon"
                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                class="cursor-pointer"
                @click="IngresosEgresos(datos.id_departamento,datos.fechrol)"
              />
              <feather-icon
                v-if="datos.cont==1"
                icon="SlidersIcon"
                svgClasses="w-5 h-5 fill-current text-success"
                class="cursor-pointer"
                @click="verempleado(datos.cod_rol_pago,datos.cont)"
              />
              <feather-icon
                v-else
                icon="SlidersIcon"
                svgClasses="w-5 h-5 fill-current text-primary"
                class="cursor-pointer"
                @click="verempleado(datos.cod_rol_pago,datos.cont)"
              />
              <feather-icon icon="CheckIcon"  v-if="datos.cont==1" svgClasses="w-5 h-5"/>
              <!--@click="Contabiliidad(datos.cod_rol_pago,datos.fechrol)"-->
              <!--@click="reporte_liquidacion(datos.id_importacion)"-->
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
    </vx-card>
    <!-- Modales-->
    <!--Modal para exportar excel-->
    <vs-popup title="Exportar Excel" :active.sync="exportar">
      <vx-card>
        <div class="vx-col sm:w-full w-full mb-6">
          <div class="vx-row">
            <div class="vx-col sm:w-full w-full mt-5">
              <vs-input
                v-model="nombreexportar"
                placeholder="Nombre del archivo..."
                class="w-full"
              />
              <v-select v-model="tipoformatoexportar" :options="formatoexportar" class="my-4" />
              <div class="flex mb-4">
                <span class="mr-4">Celda con ancho predefinido:</span>
                <vs-switch v-model="cellancho">Ancho de los campos del archivo</vs-switch>
              </div>
              <!--
              <div class="vx-row">
                <div class="vx-col sm:w-1/6 w-full mb-2" v-for="(c,index) in campos" :key="index">
                    <vs-switch vs-icon-on="check" class="w-full" color="success" v-model="indexs" :vs-value="c">
                      <span slot="off"> {{c}} </span>
                      <span slot="on"> {{c}} </span>
                    </vs-switch>
                </div>
              </div>-->
            </div>
            <div class="vx-col sm:w-full w-full mt-5">
              <!--<vs-button color="success" type="filled" @click="exportardatos">Descargar Excel</vs-button>-->
              <vs-button color="danger" type="filled" @click="exportar=false">Cancelar</vs-button>
            </div>
          </div>
        </div>
      </vx-card>
    </vs-popup>
    <!--fin modal de exportar-->
    <!--Modal para importar excel-->
    <vs-popup title="Importar Excel" :active.sync="importar">
      <vx-card>
        <div class="vx-col sm:w-full w-full mb-6">
          <div class="vx-row">
            <!---->
            <div class="vx-col sm:w-full w-full mb-6">
              <label class="vs-input--label">Subir Archivo</label>
              <div class="vx-col md:w-full w-full mb-6">
                <div style="display:none">
                  <input
                    :onSuccess="loadDataInTable"
                    type="file"
                    class="custom-file-input inputexcel"
                    @change="obtenerimagen"
                    accept=".XLSX, .CSV"
                  />
                </div>
                <div class="centimg vx-card input" @click="importarexcel()">
                  <img src="/images/upload.png" />
                  <div style="position:absolute;margin-top:60px;color:#000">Click para subir Archivo</div>
                </div>
              </div>
            </div>
            <vx-card v-if="tableData.length && header.length">
              <vs-table stripe pagination :max-items="10" search :data="tableData">
                <template slot="header">
                  <h4>{{ sheetName }}</h4>
                </template>

                <template slot="thead">
                  <vs-th :sort-key="heading" v-for="heading in header" :key="heading">{{ heading }}</vs-th>
                </template>

                <template slot-scope="{data}" @change="obtenerimagen">
                  <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                    <vs-td :data="col" v-for="col in data[indextr]" :key="col">{{ col }}</vs-td>
                  </vs-tr>
                </template>
              </vs-table>
            </vx-card>

            <div class="vx-col sm:w-full w-full mb-6">
              <vs-button color="success" @click="importardatos()">Subir Archivo</vs-button>
              <vs-button color="danger" type="filled" @click="importar=false">Cancelar</vs-button>
            </div>
          </div>
        </div>
      </vx-card>
    </vs-popup>
    <vs-popup title="Proyecto" class="peque"  :active.sync="modal_empleados">
      <vs-table stripe max-items="25" pagination :data="datos_rol">
        <template slot="thead">
          <!--<vs-th>Mes</vs-th>-->
          <vs-th>Proyecto</vs-th>
          <vs-th>Acciones</vs-th>
        </template>
        <template slot-scope="{data}">
       <vs-tr :key="datos.id_empleado" v-for="datos in data">
            <vs-td v-if="datos.descripcion">{{datos.descripcion}}</vs-td>
            <vs-td v-else>-</vs-td>
            <!--<vs-td v-if="datos.total_ingreso">{{datos.total_ingreso}}</vs-td>
            <vs-td v-else>-</vs-td>
            <vs-td v-if="datos.total_egreso">{{datos.total_egreso}}</vs-td>
            <vs-td v-else>0.00</vs-td>-->
            <vs-td class="whitespace-no-wrap">
              <!--<vx-tooltip v-if="datos.contabilidad!=null"
                                text="Ver Asiento"
                                position="top"
                                style="display: inline-flex;"
                                
                            >
              <feather-icon
                icon="EyeIcon"
                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                class="cursor-pointer"
                @click.stop="verempleado(datos.cod_rol_pago,datos.id_proyecto,datos.contabilidad)"
              />
              </vx-tooltip>
              <vx-tooltip v-else
                                text="Guardar Asiento"
                                position="top"
                                style="display: inline-flex;"
                                
                            >
              <feather-icon
              
                icon="PlusIcon"
                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                class="cursor-pointer"
                @click.stop="verempleado(datos.cod_rol_pago,datos.id_proyecto,datos.contabilidad)"
              />
              </vx-tooltip>-->
              
              <!--@click="reporte_liquidacion(datos.id_importacion)"-->
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
      <vs-popup title="Asiento"  :active.sync="modal_asiento">
        <div class="vx-row">
          <div class="vx-col sm:w-1/5 w-full mb-6">
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
          <div class="vx-col sm:w-1/6 w-full mb-6">
                          <label class="vs-input--label">Ruc:</label>
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
        <vs-divider></vs-divider>
        <div class="vx-row">
            <div class="vx-col xs:w-10/12 sm:w-11/12">
                <div class="vx-row">
                  <div class="vx-col sm:w-2/12 w-full mb-6">
                        
                    </div>
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
        <div
            id="one-row"
            class="vx-row"
            v-for="(add, index1) in dato_debe"
            v-bind:key="index1"
        >
        <div class="vx-col xs:w-10/12 sm:w-11/12">
                <div class="vx-row">
                    <!--CUENTA CONTABLE-->
                    <div class="vx-col sm:w-6/12 w-full mb-6">
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="dato_debe[index1].debe"
                                disabled
                            />
                
                        </vx-input-group>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-6">
                    <vs-input
                                class="w-full"
                                v-model="dato_debe[index1].descripcion"
                                disabled
                            />
                    </div>
                    <!--DEBE-->
                    <!-- prettier-ignore -->
                    <div class="vx-col sm:w-2/12 w-full mb-6">
                        <vs-input
                            class="w-full valores"
                            v-model="dato_debe[index1].valor_debe"
                            disabled
                        />
                    </div>
                    <!--HABER-->
                    <!-- prettier-ignore -->
                    <div class="vx-col sm:w-2/12 w-full mb-6">
                        <vs-input
                            class="w-full"
                            v-model="haber"
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
            v-for="(add2, index2) in dato_haber"
            :key="index2"
        >
        <div class="vx-col xs:w-10/12 sm:w-11/12">
                <div class="vx-row">
                    <!--CUENTA CONTABLE-->
                    <div class="vx-col sm:w-6/12 w-full mb-6">
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="dato_haber[index2].debe"
                                disabled
                            />
                
                        </vx-input-group>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-6">
                    <vs-input
                                class="w-full"
                                v-model="dato_haber[index2].descripcion"
                                disabled
                            />
                    </div>
                    <!--DEBE-->
                    <!-- prettier-ignore -->
                    <div class="vx-col sm:w-2/12 w-full mb-6">
                        <vs-input
                            class="w-full valores"
                            v-model="debe"
                            disabled
                        />
                    </div>
                    <!--HABER-->
                    <!-- prettier-ignore -->
                    <div class="vx-col sm:w-2/12 w-full mb-6">
                        <vs-input
                            class="w-full"
                            v-model="dato_haber[index2].valor_debe"
                            disabled

                        />
                    </div>
                    
                    
                </div>
            </div>
        </div>
        <div
            id="tree-row"
            class="vx-row"
            v-for="(add3, index3) in dato_parametrizacion_debe"
            :key="index3"
        >
        <div class="vx-col xs:w-10/12 sm:w-11/12">
                <div class="vx-row">
                    <!--CUENTA CONTABLE-->
                    <div class="vx-col sm:w-6/12 w-full mb-6" v-if="dato_parametrizacion_debe[index3].paramet=='Aporte Personal' && dato_parametrizacion_debe[index3].iess>0">
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="dato_parametrizacion_debe[index3].nombre_cuenta"
                                disabled
                            />
                
                        </vx-input-group>
                    </div>
                    <div class="vx-col sm:w-6/12 w-full mb-6" v-if="dato_parametrizacion_debe[index3].paramet=='Decimo Cuarto Mensual' && dato_parametrizacion_debe[index3].decimo_cuarto>0">
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="dato_parametrizacion_debe[index3].nombre_cuenta"
                                disabled
                            />
                
                        </vx-input-group>
                    </div>
                    <div class="vx-col sm:w-6/12 w-full mb-6" v-if="dato_parametrizacion_debe[index3].paramet=='Sueldo' && dato_parametrizacion_debe[index3].sueldo>0">
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="dato_parametrizacion_debe[index3].nombre_cuenta"
                                disabled
                            />
                
                        </vx-input-group>
                    </div>
                    <div class="vx-col sm:w-6/12 w-full mb-6" v-if="dato_parametrizacion_debe[index3].paramet=='Decimo Tercero Mensual' && dato_parametrizacion_debe[index3].decimo_tercero>0">
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="dato_parametrizacion_debe[index3].nombre_cuenta"
                                disabled
                            />
                
                        </vx-input-group>
                    </div>
                    <div class="vx-col sm:w-6/12 w-full mb-6" v-if="dato_parametrizacion_debe[index3].paramet=='Fondo Reserva Mensual' && dato_parametrizacion_debe[index3].fondo_reserva>0">
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="dato_parametrizacion_debe[index3].nombre_cuenta"
                                disabled
                            />
                
                        </vx-input-group>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-6" v-if="dato_parametrizacion_debe[index3].paramet=='Aporte Personal' && dato_parametrizacion_debe[index3].iess>0">
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="dato_parametrizacion_debe[index3].descripcion"
                                disabled
                            />
                
                        </vx-input-group>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-6" v-if="dato_parametrizacion_debe[index3].paramet=='Decimo Cuarto Mensual' && dato_parametrizacion_debe[index3].decimo_cuarto>0">
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="dato_parametrizacion_debe[index3].descripcion"
                                disabled
                            />
                
                        </vx-input-group>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-6" v-if="dato_parametrizacion_debe[index3].paramet=='Sueldo' && dato_parametrizacion_debe[index3].sueldo>0">
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="dato_parametrizacion_debe[index3].descripcion"
                                disabled
                            />
                
                        </vx-input-group>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-6" v-if="dato_parametrizacion_debe[index3].paramet=='Decimo Tercero Mensual' && dato_parametrizacion_debe[index3].decimo_tercero>0">
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="dato_parametrizacion_debe[index3].descripcion"
                                disabled
                            />
                
                        </vx-input-group>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-6" v-if="dato_parametrizacion_debe[index3].paramet=='Fondo Reserva Mensual' && dato_parametrizacion_debe[index3].fondo_reserva>0">
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="dato_parametrizacion_debe[index3].descripcion"
                                disabled
                            />
                
                        </vx-input-group>
                    </div>
                    <!--DEBE-->
                    <!-- prettier-ignore -->
                    <div class="vx-col sm:w-2/12 w-full mb-6" v-if="dato_parametrizacion_debe[index3].paramet=='Aporte Personal' && dato_parametrizacion_debe[index3].iess>0">
                        <vs-input
                            class="w-full valores"
                            v-model="dato_parametrizacion_debe[index3].iess"
                            disabled
                        />
                    </div>
                    <div class="vx-col sm:w-2/12 w-full mb-6" v-if="dato_parametrizacion_debe[index3].paramet=='Decimo Cuarto Mensual' && dato_parametrizacion_debe[index3].decimo_cuarto>0">
                        <vs-input
                            class="w-full valores"
                            v-model="dato_parametrizacion_debe[index3].decimo_cuarto"
                            disabled
                        />
                    </div>
                    <div class="vx-col sm:w-2/12 w-full mb-6" v-if="dato_parametrizacion_debe[index3].paramet=='Sueldo' && dato_parametrizacion_debe[index3].sueldo>0">
                        <vs-input
                            class="w-full valores"
                            v-model="dato_parametrizacion_debe[index3].sueldo"
                            disabled
                        />
                    </div>
                    <div class="vx-col sm:w-2/12 w-full mb-6" v-if="dato_parametrizacion_debe[index3].paramet=='Decimo Tercero Mensual' && dato_parametrizacion_debe[index3].decimo_tercero>0">
                        <vs-input
                            class="w-full valores"
                            v-model="dato_parametrizacion_debe[index3].decimo_tercero"
                            disabled
                        />
                    </div>
                    <div class="vx-col sm:w-2/12 w-full mb-6" v-if="dato_parametrizacion_debe[index3].paramet=='Fondo Reserva Mensual' && dato_parametrizacion_debe[index3].fondo_reserva>0">
                        <vs-input
                            class="w-full valores"
                            v-model="dato_parametrizacion_debe[index3].fondo_reserva"
                            disabled
                        />
                    </div>
                    <!--HABER-->
                    <!-- prettier-ignore -->
                    <div class="vx-col sm:w-1/6 w-full mb-6" v-if="dato_parametrizacion_debe[index3].paramet=='Aporte Personal' && dato_parametrizacion_debe[index3].iess>0">
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="haber"
                                disabled
                            />
                
                        </vx-input-group>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-6" v-if="dato_parametrizacion_debe[index3].paramet=='Decimo Cuarto Mensual' && dato_parametrizacion_debe[index3].decimo_cuarto>0">
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="haber"
                                disabled
                            />
                
                        </vx-input-group>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-6" v-if="dato_parametrizacion_debe[index3].paramet=='Sueldo' && dato_parametrizacion_debe[index3].sueldo>0">
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="haber"
                                disabled
                            />
                
                        </vx-input-group>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-6" v-if="dato_parametrizacion_debe[index3].paramet=='Decimo Tercero Mensual' && dato_parametrizacion_debe[index3].decimo_tercero>0">
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="haber"
                                disabled
                            />
                
                        </vx-input-group>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-6" v-if="dato_parametrizacion_debe[index3].paramet=='Fondo Reserva Mensual' && dato_parametrizacion_debe[index3].fondo_reserva>0">
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="haber"
                                disabled
                            />
                
                        </vx-input-group>
                    </div>
                    
                    
                </div>
            </div>
        </div>
        <div
            id="tree-row"
            class="vx-row"
            v-for="(add4, index4) in dato_parametrizacion_haber"
            :key="index4"
        >
        <div class="vx-col xs:w-10/12 sm:w-11/12">
                <div class="vx-row">
                    <!--CUENTA CONTABLE-->
                    <div class="vx-col sm:w-6/12 w-full mb-6" v-if="dato_parametrizacion_haber[index4].parametr=='Aporte Personal' && dato_parametrizacion_haber[index4].iess>0">
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="dato_parametrizacion_haber[index4].nombre_cuenta"
                                disabled
                            />
                
                        </vx-input-group>
                    </div>
                    <div class="vx-col sm:w-6/12 w-full mb-6" v-if="dato_parametrizacion_haber[index4].parametr=='Decimo Cuarto Mensual' && dato_parametrizacion_haber[index4].decimo_cuarto>0">
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="dato_parametrizacion_haber[index4].nombre_cuenta"
                                disabled
                            />
                
                        </vx-input-group>
                    </div>
                    <div class="vx-col sm:w-6/12 w-full mb-6" v-if="dato_parametrizacion_haber[index4].parametr=='Sueldo' && dato_parametrizacion_haber[index4].sueldo>0">
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="dato_parametrizacion_haber[index4].nombre_cuenta"
                                disabled
                            />
                
                        </vx-input-group>
                    </div>
                    <div class="vx-col sm:w-6/12 w-full mb-6" v-if="dato_parametrizacion_haber[index4].parametr=='Decimo Tercero Mensual' && dato_parametrizacion_haber[index4].decimo_tercero>0">
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="dato_parametrizacion_haber[index4].nombre_cuenta"
                                disabled
                            />
                
                        </vx-input-group>
                    </div>
                    <div class="vx-col sm:w-6/12 w-full mb-6" v-if="dato_parametrizacion_haber[index4].parametr=='Fondo Reserva Mensual' && dato_parametrizacion_haber[index4].fondo_reserva>0">
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="dato_parametrizacion_haber[index4].nombre_cuenta"
                                disabled
                            />
                
                        </vx-input-group>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-6" v-if="dato_parametrizacion_haber[index4].parametr=='Aporte Personal' && dato_parametrizacion_haber[index4].iess>0">
                    <vs-input
                                class="w-full"
                                v-model="dato_parametrizacion_haber[index4].descripcion"
                                disabled
                            />
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-6" v-if="dato_parametrizacion_haber[index4].parametr=='Decimo Cuarto Mensual' && dato_parametrizacion_haber[index4].decimo_cuarto>0">
                    <vs-input
                                class="w-full"
                                v-model="dato_parametrizacion_haber[index4].descripcion"
                                disabled
                            />
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-6" v-if="dato_parametrizacion_haber[index4].parametr=='Sueldo' && dato_parametrizacion_haber[index4].sueldo>0">
                    <vs-input
                                class="w-full"
                                v-model="dato_parametrizacion_haber[index4].descripcion"
                                disabled
                            />
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-6" v-if="dato_parametrizacion_haber[index4].parametr=='Decimo Tercero Mensual' && dato_parametrizacion_haber[index4].decimo_tercero>0">
                    <vs-input
                                class="w-full"
                                v-model="dato_parametrizacion_haber[index4].descripcion"
                                disabled
                            />
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-6" v-if="dato_parametrizacion_haber[index4].parametr=='Fondo Reserva Mensual' && dato_parametrizacion_haber[index4].fondo_reserva>0">
                    <vs-input
                                class="w-full"
                                v-model="dato_parametrizacion_haber[index4].descripcion"
                                disabled
                            />
                    </div>
                    
                    <!--DEbe-->
                    <!-- prettier-ignore -->
                    <div class="vx-col sm:w-1/6 w-full mb-6" v-if="dato_parametrizacion_haber[index4].parametr=='Aporte Personal' && dato_parametrizacion_haber[index4].iess>0">
                    <vs-input
                                class="w-full"
                                v-model="haber"
                                disabled
                            />
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-6" v-if="dato_parametrizacion_haber[index4].parametr=='Decimo Cuarto Mensual' && dato_parametrizacion_haber[index4].decimo_cuarto>0">
                    <vs-input
                                class="w-full"
                                v-model="haber"
                                disabled
                            />
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-6" v-if="dato_parametrizacion_haber[index4].parametr=='Sueldo' && dato_parametrizacion_haber[index4].sueldo>0">
                    <vs-input
                                class="w-full"
                                v-model="haber"
                                disabled
                            />
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-6" v-if="dato_parametrizacion_haber[index4].parametr=='Decimo Tercero Mensual' && dato_parametrizacion_haber[index4].decimo_tercero>0">
                    <vs-input
                                class="w-full"
                                v-model="haber"
                                disabled
                            />
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-6" v-if="dato_parametrizacion_haber[index4].parametr=='Fondo Reserva Mensual' && dato_parametrizacion_haber[index4].fondo_reserva>0">
                    <vs-input
                                class="w-full"
                                v-model="haber"
                                disabled
                            />
                    </div>
                    <!--Haber-->
                    <!-- prettier-ignore -->
                    <div class="vx-col sm:w-2/12 w-full mb-6" v-if="dato_parametrizacion_haber[index4].parametr=='Aporte Personal' && dato_parametrizacion_haber[index4].iess>0">
                        <vs-input
                            class="w-full valores"
                            v-model="dato_parametrizacion_haber[index4].iess"
                            disabled
                        />
                    </div>
                    <div class="vx-col sm:w-2/12 w-full mb-6" v-if="dato_parametrizacion_haber[index4].parametr=='Decimo Cuarto Mensual' && dato_parametrizacion_haber[index4].decimo_cuarto>0">
                        <vs-input
                            class="w-full valores"
                            v-model="dato_parametrizacion_haber[index4].decimo_cuarto"
                            disabled
                        />
                    </div>
                    <div class="vx-col sm:w-2/12 w-full mb-6" v-if="dato_parametrizacion_haber[index4].parametr=='Sueldo' && dato_parametrizacion_haber[index4].sueldo>0">
                        <vs-input
                            class="w-full valores"
                            v-model="dato_parametrizacion_haber[index4].sueldo"
                            disabled
                        />
                    </div>
                    <div class="vx-col sm:w-2/12 w-full mb-6" v-if="dato_parametrizacion_haber[index4].parametr=='Decimo Tercero Mensual' && dato_parametrizacion_haber[index4].decimo_tercero>0">
                        <vs-input
                            class="w-full valores"
                            v-model="dato_parametrizacion_haber[index4].decimo_tercero"
                            disabled
                        />
                    </div>
                    <div class="vx-col sm:w-2/12 w-full mb-6" v-if="dato_parametrizacion_haber[index4].parametr=='Fondo Reserva Mensual' && dato_parametrizacion_haber[index4].fondo_reserva>0">
                        <vs-input
                            class="w-full valores"
                            v-model="dato_parametrizacion_haber[index4].fondo_reserva"
                            disabled
                        />
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
         <div class="vx-col xs:w-10/12 sm:w-11/12">
          <div class="vx-row">
         
                    <!--CUENTA CONTABLE-->
                    <div class="vx-col sm:w-1/2 w-full mb-6">
                        
                    </div>
                    <div class="vx-col sm:w-2/12 w-full mb-6">
                        
                    </div>
                    {{totaldebe}}
                    <div class="vx-col sm:w-2/12 w-full mb-6 ">
                        <vs-input
                             
                            class="w-full valores"
                            v-model="total_debe"
                      
                            disabled
                        />
                    </div>
                    {{totalhaber}}
                    <div class="vx-col sm:w-2/12 w-full mb-6">
                        <vs-input
                            
                            class="w-full valores"
                            v-model="total_haber"
                            disabled
                        />
                    </div>
          </div>
        </div>
        
        <div v-if="contabilidad=='1'">
          <h5> Este asiento ya ha sido registrado</h5>
        </div>
        <div v-else>
          <vs-button
                    color="success"
                    type="filled"
                    :disabled="disabled_asiento"
                    @click="crearasiento(cod_rol)" 
                    >GUARDAR</vs-button
                >
        </div>
    </vs-popup>
    </vs-popup>
    
    <!--fin modal de exportar-->
    <!--Modal pdf-->
    <vs-popup title="Rol Pago" class="peque" :active.sync="modal_pdf">
      <div class="vx-row">
        <div class="vx-col sm:w-1/5 w-full">
        <label for="sections" >Rol General</label>
                
        </div>
        <div class="vx-col sm:w-1/6 w-full">
        <vs-switch
                    v-model="general"
                    class="ml-5"
                />
        </div>
        
      </div>
      <br>
      <div class="vx-row" v-if="general==true">
        <div class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-input 
                          label="Email:"
                          class="mb-4 md:mb-0 mr-4"
                          v-model="empleado_email"
                        />
                        <div v-show="error" v-if="!empleado_email && general==true">
                            <div v-for="err in error_email_general" :key="err" v-text="err" class="text-danger"></div>
                        </div>
        </div>
        
                        
      </div>
      <div>
          <div class="vx-col sm:w-1/2 w-full" v-if="general==true">
                        <vs-select
                            placeholder="Seleccione"
                            class="selectExample w-full"
                            label="Proyecto:"
                            vs-multiple
                            autocomplete
                            v-model="datos_proyecto.selectedproyecto"
                        >
                            <vs-select-item value="0" text="Todos"/>
                            <vs-select-item
                                :key="index"
                                :value="item.id_proyecto"
                                :text="item.descripcion"
                                v-for="(item, index) in datos_proyecto.proyectoList"
                            />
                            
                        </vs-select>
                         <!--<vs-select-item :key="index" :value="item.value" :text="item.text" v-for="item,index in options1" />-->
                        <!--<label for="project">Proyecto:</label>
                        <v-select
                            
                            :options="datos_proyecto.proyectoList"
                            label="descripcion"
                            v-model="datos_proyecto.selectedproyecto"
                            placeholder="Seleccione el Proyecto"
                        ></v-select>-->
                        
        </div>
        </div>
      <br>

            <div v-if="general==false">
            
              <vs-select
                            
                            placeholder="--Seleccione--"
                            autocomplete
                            class="selectExample w-full"
                            v-model="id_empleado"
                        >
                        <!--canton = '';
                                parroquia = '';-->
                            <vs-select-item
                                v-for="data in empleados"
                                :key="data.id_empleado"
                                :value="data.id_empleado"
                                :text="data.primer_nombre"
                            />
                        </vs-select>
                        <div v-show="error" v-if="!id_empleado">
                            <div v-for="err in error_email" :key="err" v-text="err" class="text-danger"></div>
                        </div>

            </div>
            <br>
            <div>
              <vs-button color="success" @click="sendData" type="filled">Generar</vs-button>
              <vs-button color="warning" @click="envio_una_papeleta(id_empleado)" type="filled" v-if="general==false">Enviar</vs-button>
              <vs-button color="warning" @click="pdf_rol_general(empleado_email)" type="filled" v-else>Enviar</vs-button>
            </div>
      <!--<ReporteriaRolGeneral hidden
      v-if="general==true"
                    @generateReport="generateReport"
                />
                <ReporteriaRolIndividual hidden
      v-else
                    @generateReport="generateReport"
                />-->
      
    </vs-popup>
  </div>
</template>
<script>
import { AgGridVue } from "ag-grid-vue";
import ImportExcel from "@/components/excel/ImportExcel.vue";
import $ from "jquery";
import service from "./components";
import ReporteriaRolGeneral from "./reporte/ReporteRolPagoGen";
import ReporteriaRolIndividual from "./reporte/ReporteRolPagoInd";
import moment from "moment";
moment.locale("es");
const axios = require("axios");
export default {
  components: {
    AgGridVue,
    ImportExcel,
    ReporteriaRolGeneral,
    ReporteriaRolIndividual,
    
  },
  data() {
    return {
      timeout: null,
      //excel import
      file: "",
      tableData: [],
      header: [],
      sheetName: "",
      general:true,

      //variables modal
      titulomodal: "",
      modal: false,
      tipoaccion: 0,
      tipoaccionmodal: 0,
      titulo: "",
      id: null,
      agregarlinea: false,
      agregartipo: false,
      agregarvendedor: false,
      //errores para email
     
      
      eliminar: false,
      ideliminar: 0,
      tipoeliminar: null,
      invoice: {
                all:true
            },
      //variables para ingresos
      columnasingreso:[],
      nrcolumnas:[],
      columna1:"",
      columna2:"",
      columna3:"",
      columna4:"",
      columna5:"",
      columna6:"",
      idcolumn1:0,
      idcolumn2:0,
      idcolumn3:0,
      idcolumn4:0,
      idcolumn5:0,
      idcolumn6:0,
      //variables egreso
      contenidoegreso:[],
      nrcolumnaseg:[],
      columnaeg1:"",
      columnaeg2:"",
      columnaeg3:"",
      columnaeg4:"",
      columnaeg5:"",
      columnaeg6:"",
      idcolumneg1:0,
      idcolumneg2:0,
      idcolumneg3:0,
      idcolumneg4:0,
      idcolumneg5:0,
      idcolumneg6:0,
      //modal para pdf
      modal_pdf:false,
      modal:false,
      id_departamento:"",
      id_empleado:null,
      empleados:[],
      fecha:"",
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
      pagination1: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
        count: 0
      },
      pagination2: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
        count: 0
      },
      disabled_asiento:false,
      pagination3: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
        count: 0
      },
      empleado_email:"",
      pagina: 1,
      pagina1: 1,
      pagina2: 1,
      pagina3: 1,
      cantidadp1: 1000000,
      cantidadp2: 100000,
      cantidadp3: 100000,
      // cantidadp11: 10,
      offset: 3,
      offset1: 3,
      offset2: 3,
      offset3: 3,
      //buscador
      buscar: "",
      buscar1: "",
      buscar2: "",
      buscar3: "",
      //buscador

      criterio1: "codcta",
      criterio11: "codcta",
      //otros valores
      gridApi: null,
      contenido: [],
      contenidolinea: [],
      contenidotipo: [],
      contenidomarca: [],
      //lenguaje
      i18nbuscar: this.$t("i18nbuscar"),
      //variables linea producto
      nombre_grupo: "",
      cuenta: "",
      cuenta_descuento: "",
      cuenta_anticipo: "",
      cuenta_servicio: "",
      //variables tipo producto
      descripcion_tipo_cliente: "",
      //variables marca
      codigo_vendedor: "",
      nombre_vendedor: "",
      email_vendedor: "",
      //opciones linea de producto para tipo de producto
      optionlinea: [],
      //buscador
      criterio: "id",
      //otros valoreS
      gridApi: null,
      contenido: [],
      //ERRORES
      error: 0,
      error_email: [],
      error_email_general:[],
      //Datos para la importaciond de archivos
      importar: false,
      nombreimportar: "",

      //Datos para la Exportacion de archivos
      exportar: false,
      nombreexportar: "",
      formatoexportar: ["xlsx", "csv", "txt"],
      cellancho: true,
      tipoformatoexportar: "xlsx",
      datos_rol:[],
      nombre_proyecto:"",
      fecha_rol:"",
      ruc_empresa:"",
      razon_social:"",
      concepto:"",
      codigo:"",
      modal_empleados:false,
      modal_asiento:false,
      dato_debe:[],
      debe:"",
      dato_haber:[],
      haber:"",
      dato_parametrizacion_debe:[],
      parametrizacion_dcterc_debe:"",
      parametrizacion_dcct_debe:"",
      parametrizacion_fdrv_debe:"",
      parametrizacion_iess_debe:"",
      parametrizacion_sueldo_debe:"",
      dato_parametrizacion_haber:[],
      parametrizacion_dcterc_haber:"",
      parametrizacion_dcct_haber:"",
      parametrizacion_fdrv_haber:"",
      parametrizacion_iess_haber:"",
      total_debe:"",
      total_haber:"",
      cod_rol:"",
      id_proyecto:"",
      solo_cod:"",
      contabilidad:"",
      proyecto_rol:"",
      datos_proyecto: {
                proyectoList: [
                ],
                selectedproyecto: null
            },
      estado_asiento:"",
      //campos que existen para exportar
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
        fecha(data){
            return moment(data).format("MMMM YYYY");
        },
        upper: function (value) {
            return value.toUpperCase();
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
            // if (this.usuario.id_rol == 1) {
            //     res = 1;
            // } else {
            //     res = this.$store.state.Roles[15].crear;
            // }
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                this.$store.state.Roles.forEach(el => {
                    if(el.nombre == "Rol de Pagos"){
                        res = el.crear;
                        return res;
                    }
                });
            }
            //console.log(res+"Rol");
            return res;
            
        },
        editarrol() {
            var res = 0;
            // if (this.usuario.id_rol == 1) {
            //     res = 1;
            // } else {
            //     res = this.$store.state.Roles[15].editar;
            // }
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                this.$store.state.Roles.forEach(el => {
                    if(el.nombre == "Rol de Pagos"){
                        res = el.editar;
                        return res;
                    }
                });
            }
            return res;
        },
    eliminarroles() {
      var res = 0;
            // if (this.usuario.id_rol == 1) {
            //     res = 1;
            // } else {
            //     res = this.$store.state.Roles[15].eliminar;
            // }
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                this.$store.state.Roles.forEach(el => {
                    if(el.nombre == "Rol de Pagos"){
                        res = el.eliminar;
                        return res;
                    }
                });
            }
            return res;
    },
    totaldebe(){
      var resp=0;
      var debe=0;
      this.dato_debe.forEach(el => {
        debe+=parseFloat(el.valor_debe);
      });
      this.dato_parametrizacion_debe.forEach(el=>{
        if(el.decimo_tercero!=="no"){
          debe+=parseFloat(el.decimo_tercero);
        }
        if(el.decimo_cuarto!=="no"){
          debe+=parseFloat(el.decimo_cuarto);
        }
        if(el.fondo_reserva!=="no"){
          debe+=parseFloat(el.fondo_reserva);
        }
        if(el.iess!=="no"){
          debe+=parseFloat(el.iess);
        }
        if(el.sueldo!=="no"){
          debe+=parseFloat(el.sueldo);
        }
      });
      resp=parseFloat(this.parametrizacion_dcterc_debe)+
        parseFloat(this.parametrizacion_dcct_debe)+
        parseFloat(this.parametrizacion_fdrv_debe)+
        parseFloat(this.parametrizacion_iess_debe)+
        parseFloat(this.parametrizacion_sueldo_debe)+
        debe;
        this.total_debe=debe.toFixed(2);
    },
    totalhaber(){
      var resp=0;
      var debe=0;
      this.dato_haber.forEach(el => {
        debe+=parseFloat(el.valor_debe);
      });
      this.dato_parametrizacion_haber.forEach(el=>{
        if(el.decimo_tercero!=="no"){
          debe+=parseFloat(el.decimo_tercero);
        }
        if(el.decimo_cuarto!=="no"){
          debe+=parseFloat(el.decimo_cuarto);
        }
        if(el.fondo_reserva!=="no"){
          debe+=parseFloat(el.fondo_reserva);
        }
        if(el.iess!=="no"){
          debe+=parseFloat(el.iess);
        }
        if(el.sueldo!=="no"){
          debe+=parseFloat(el.sueldo);
        }
      });
      resp=parseFloat(this.parametrizacion_dcterc_debe)+
        parseFloat(this.parametrizacion_dcct_debe)+
        parseFloat(this.parametrizacion_fdrv_debe)+
        parseFloat(this.parametrizacion_iess_debe)+
        parseFloat(this.parametrizacion_sueldo_debe)+
        debe;
        this.total_haber=debe.toFixed(2);
    }
  },
  methods: {
        filtertabla() {
            var contvar = this.filterstable.contrue;
            if (this.filterstable.filtertab == true) {
                if (this.filterstable.filt_asientos == false) {
                    contvar = contvar.filter(
                        contvar => contvar.cont != 1
                    );
                }
                if (this.filterstable.filt_noasientos == false) {
                    contvar = contvar.filter(
                        contvar => contvar.cont == 1
                    );
                }
            } else {
                this.filterstable.filt_asientos = true;
                this.filterstable.filt_noasientos = true;
            }
            this.contenido = contvar;
        },
    eliminarrol(cd) {
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: `Confirmar`,
        text: `¿Desea Eliminar este registro?`,
        acceptText: "Aceptar",
        cancelText: "Cancelar",
        accept: this.acceptAlert,
        parameters: cd
      });
    },
    Contabiliidad(id,fecha){
      var url = "/api/rolpago/empleado/"+id;
      axios.get(url,{params:{
        id_empresa:this.usuario.id_empresa
      }})
      .then(resp=>{
        this.datos_rol=resp.data;
        this.modal_empleados=true;
        
      }).catch(err=>{
        console.log("EROOR CONTABILIDAD:"+err);
      });
    },
    verempleado(id,conta){
      var url = "/api/rolpago/proyecto/"+id;
      axios.get(url,{
                    params: {
                        id_empresa: this.usuario.id_empresa,
                        id_proyecto:1
                    }
                })
      .then(resp=>{
        this.nombre_proyecto=resp.data.recupera.descripcion;
        this.fecha_rol=resp.data.recupera.fechrol;
        this.ruc_empresa=resp.data.recupera.ruc_empresa;
        this.razon_social=resp.data.recupera.razon_social;
        var fecha=moment(this.fecha_rol).format("MMMM YYYY");
        this.concepto="Rol Pago "+resp.data.recupera.dep_nombre+" "+fecha.toUpperCase();
        if(conta=='1'){
          this.codigo="RP-"+resp.data.codigo_anterior;
        }else{
          this.codigo="RP-"+resp.data.codigo;
        }
        
        this.solo_cod=resp.data.codigo;
        this.contabilidad=conta;
        console.log("DEpartamento:"+resp.data.recupera.id_departamento);
        this.traerDetalle(resp.data.recupera.id_departamento,this.fecha_rol,id);
      }).catch(err=>{
        console.log("EROOR CONTABILIDAD empleado:"+err);
      });
    },
    traerDetalle(id,fecharol,cod){
      var url = "/api/rolpago/detalle/"+id;
      axios.get(url,{
                    params: {
                        fecha: fecharol,
                        //id_proyecto:proyecto,
                        cod:cod
                    }
                })
      .then(resp=>{
        this.dato_debe=resp.data.debe;
        this.dato_haber=resp.data.haber;
        this.dato_parametrizacion_debe=resp.data.cuentas;
        /*this.parametrizacion_dcterc_debe=resp.data.dec_tercero;
        this.parametrizacion_dcct_debe=resp.data.dec_cuarto;
        this.parametrizacion_fdrv_debe=resp.data.fondo_reserva;
        this.parametrizacion_iess_debe=resp.data.iess;
        this.parametrizacion_sueldo_debe=resp.data.sueldo;*/
        this.dato_parametrizacion_haber=resp.data.cuentas_haber;
        this.cod_rol=cod;
        this.id_proyecto=resp.data.id_proyecto;
        this.estado_asiento=resp.data.asiento_permitido;
        console.log("Detalle:"+resp.data.debe);
        this.modal_asiento=true;
      }).catch(err=>{
        console.log("EROOR CONTABILIDAD detalle:"+err);
      });
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
        
      var fecha_hoy=new Date();
      axios.post("/api/rolpago/agregar/asiento",{
        cod_rol:id,
        numero:this.solo_cod,
        codigo:this.codigo,
        fecha:this.fecha_rol+" "+fecha_hoy.getHours()+":"+fecha_hoy.getMinutes()+":"+fecha_hoy.getSeconds(),
        razon_social:this.razon_social,
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
          if(this.dato_debe.length<=0){
            //error++;
            console.log("dato_debe no hay");
          }else{
            this.dato_debe.forEach(el=>{
                    
                        if(el.id_plan_cuentas==null){
                            error++;
                            console.log("dato_debe cuenta_prov");
                        }
                        
                   
                    if(el.id_proyecto==null){
                            error++;
                            console.log("dato_debe proyecto");
                    }
                });
          }
          if(this.dato_haber.length<=0){
            //error++;
            console.log("dato_haber no hay");
          }else{
            this.dato_haber.forEach(el=>{
              if(el.id_plan_cuentas==null){
                error++;
                console.log("dato_haber cuenta");
              }
              if(el.id_proyecto==null){
                error++;
                console.log("dato_haber proyecto");
              }
            });
          }
          if(this.dato_parametrizacion_debe.length<=0){
            error++;
            console.log("dato_parametrizacion_debe no hay");
          }else{
            this.dato_parametrizacion_debe.forEach(el=>{
                  if(el.id_plan_cuentas1==null){
                    error++;
                    console.log("dato_parametrizacion_debe cuenta");
                  }
                  if(el.id_proyecto==null){
                    error++;
                    console.log("dato_parametrizacion_debe proyecto");
                  }
            });
          }
          if(this.dato_parametrizacion_haber.length<=0){
            error++;
            console.log("dato_parametrizacion_haber no hay");
          }else{
            this.dato_parametrizacion_haber.forEach(el=>{
                  if(el.id_plancuenta==null){
                    error++;
                    console.log("dato_parametrizacion_haber cuenta");
                  }
                  if(el.id_proyecto==null){
                    error++;
                    console.log("dato_parametrizacion_haber proyecto");
                  }
            });
          }

          return error;
          
    },
    crearasientoDetalle(id){
      axios.post("/api/rolpago/agregar/asiento_detalle",{
        proyecto:this.nombre_proyecto,
        ingresos_debe:this.dato_debe,
        ingresos_haber:this.dato_haber,
        parametrizacion_debe:this.dato_parametrizacion_debe,
        parametrizacion_haber:this.dato_parametrizacion_haber,
        ucrea:this.usuario.id,
        id_asientos:id,
      }).then(res=>{
        this.$vs.notify({
          color: "success",
          title: "Asiento Agregado",
          text: "Asiento agregado con exito"
        });
        this.modal_empleados=false;
        this.modal_asiento=false;
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
    acceptAlert(parameters) {
      axios.delete("/api/rolpagoeliminar/" + parameters).then(res=>{
        this.$vs.notify({
          color: "success",
          title: "Rol de Pagos Eliminado",
          text: "Rol de Pagos eliminado con exito"
        });
        this.listar(1, this.buscar);
      }).catch(err=>{
        this.$vs.notify({
          color: "danger",
          title: "Error al Eliminar",
          text: "Este registro se esta utilizando en otra seccion"
        });
      });
      
    },
    async generateReport(e) {
            try {
                
                e.ing1= this.idcolumn1;
                e.ing2= this.idcolumn2;
                e.ing3= this.idcolumn3;
                e.ing4= this.idcolumn4;
                e.ing5= this.idcolumn5;
                e.ing6= this.idcolumn6;
                e.egr1= this.idcolumneg1;
                e.egr2= this.idcolumneg2;
                e.egr3= this.idcolumneg3;
                e.egr4= this.idcolumneg4;
                e.egr5= this.idcolumneg5;
                e.egr6= this.idcolumneg6;
                e.date= this.fecha;
                if(this.general==false){
                  e.id_empleado=this.id_empleado;
                }
                e.id_departamento=this.id_departamento;
                //console.log("DATA_QUERY", e);
                let {
                    url,
                    nameFile
                } = await service.generateReport.invoiceReport({ data: e });
                //console.log("URL_NAME::", url, nameFile);
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
            } catch (error) {
                console.error("ERROR::",error)
                this.$vs.notify({
                    time: 5000,
                    ...error
                });
            }
        },
      sendData() {
        if(this.general==true){
          this.id_empleado=null,
          this.proyecto_rol=null
        }
        
        //var url= "/api/generarrolpdfprueba";
            axios({
                    url: "/api/generarrolpdf",
                    method: "GET",
                    responseType: "arraybuffer",
                    params:{
                      ing1: this.idcolumn1,
                      ing2: this.idcolumn2,
                      ing3: this.idcolumn3,
                      ing4: this.idcolumn4,
                      ing5: this.idcolumn5,
                      ing6: this.idcolumn6,
                      egr1: this.idcolumneg1,
                      egr2: this.idcolumneg2,
                      egr3: this.idcolumneg3,
                      egr4: this.idcolumneg4,
                      egr5: this.idcolumneg5,
                      egr6: this.idcolumneg6,
                      date: this.fecha,
                      id_departamento:this.id_departamento,
                      id_empleado:this.id_empleado,
                      id_user:this.usuario.id,
                      id_proyecto:this.datos_proyecto.selectedproyecto
                    }
                
                }).then(resp=>{
                  //console.log("ejecutado empleado");
                //this.contenidopr=res.data;
                //console.log("resp:"+resp);
                  //console.log("resp data:"+resp.data);

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
                //console.log("nombre:"+nameFile+"url:"+url);
                //return({ url: url, nameFile: nameFile });
                //console.log("URL_NAME::", url, nameFile);
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
                this.idcolumn1=0;
                this.idcolumn2=0;
                this.idcolumn3=0;
                this.idcolumn4=0;
                this.idcolumn5=0;
                this.idcolumn6=0;
                this.idcolumneg1=0;
                this.idcolumneg2=0;
                this.idcolumneg3=0;
                this.idcolumneg4=0;
                this.idcolumneg5=0;
                this.idcolumneg6=0;
                this.id_empleado=null;
                this.id_departamento="";
                this.fecha="";
                this.proyecto_rol="";
                this.datos_proyecto.selectedproyecto=[];
                this.general=true;
                this.modal_pdf=false;
                }).catch(err=>{
                  console.log("ERROR"+err);
                });
                    
        },
    agregar(){
      alert("asdasd");
    },
    importardatos() {
      let formData = new FormData();

      formData.append("id_empresa", this.usuario.id_empresa);
      formData.append("file", this.file);
      axios
        .post("/api/importarexcel", formData, {})
        .then(res => {
          this.$vs.notify({
            text: "Archivo Importado con exito",
            color: "success"
          });
          this.importar = false;
        })
        .catch(err => {
          console.log(err);
        });
    },
    obtenerimagen(e) {
      let file = e.target.files[0];
      var allowedExtensions = /(.csv|.xls|.xlsx)$/i;
      if (!allowedExtensions.exec(file.name)) {
        this.$vs.notify({
          title: "Tipo de archivo no compatible",
          text: "Formatos aceptados: .jpg, .jpeg, .png",
          color: "danger"
        });
        return;
      }
      this.file = file;
    },
    //importar archivos
    loadDataInTable({ results, header, meta }) {
      this.header = header;
      this.tableData = results;
      this.sheetName = meta.sheetName;
    },
    IngresosEgresos(id,fecha){
    
      this.modal=true;
      this.$vs.notify({
                        
                        text: "Este proceso puede demorar por favor espere ...",
                        color: "warning"
                    });
      this.id_departamento=id;
      this.fecha=fecha;
      this.traerIngresos(id,fecha)
        .then(value => {
          // Resolucion de traerIngresos
          return this.traerEgresos(id,fecha);
        })
        .then(value => {
           // resolucion de this.traerEgresos
          this.modal_pdf=true;
          return  this.listarempleados();
        }).catch(err=>{
          this.$vs.notify({
                  text: err,
                  color: "danger"
              });
        })

    },
    async PdfPapeletasIndividuales(id,fechrol){
      var url = "/api/rolpago/papeletas?id_empleado="+id+
      "&ing1="+this.idcolumn1+
      "&ing2="+this.idcolumn2+
      "&ing3="+this.idcolumn3+
      "&ing4="+this.idcolumn4+
      "&ing5="+this.idcolumn5+
      "&ing6="+this.idcolumn6+
      "&egr1="+this.idcolumneg1+
      "&egr2="+this.idcolumneg2+
      "&egr3="+this.idcolumneg3+
      "&egr4="+this.idcolumneg4+
      "&egr5="+this.idcolumneg5+
      "&egr6="+this.idcolumneg6+
      "&date="+fechrol+
      "&id_user="+this.usuario.id;
      let {data} = await axios.get(url)
      return data
    },
    async PdfPapeletaIndividual(id,fechrol,empleado){
      var url = "/api/rolpago/papeleta/individual?id_empleado="+id+
      "&ing1="+this.idcolumn1+
      "&ing2="+this.idcolumn2+
      "&ing3="+this.idcolumn3+
      "&ing4="+this.idcolumn4+
      "&ing5="+this.idcolumn5+
      "&ing6="+this.idcolumn6+
      "&egr1="+this.idcolumneg1+
      "&egr2="+this.idcolumneg2+
      "&egr3="+this.idcolumneg3+
      "&egr4="+this.idcolumneg4+
      "&egr5="+this.idcolumneg5+
      "&egr6="+this.idcolumneg6+
      "&date="+fechrol+
      "&id_user="+this.usuario.id+
      "&empleado="+empleado;
      let {data} = await axios.get(url)
      return data
    },
    generarPapeletas(id,fechrol){
      this.modal=false;
      this.$vs.notify({
                        title: "Enviando Papeletas",
                        text: "Este proceso puede demorar por favor espere ...",
                        color: "warning"
                    });
      
      this.traerIngresos(id,fechrol)
          .then(value => {
            // Resolucion de traerIngresos
            return this.traerEgresos(id,fechrol);
          })
          .then(value => {
            // resolucion de this.traerEgresos
            
            return this.PdfPapeletasIndividuales(id,fechrol);
          })
          .then(value => {
            
            this.enviaremail(id,fechrol);// resolucion de this.traerEmpleado
            
          }).then(value=>{
          
            this.$vs.notify({
                          title: "Roles Individuales enviados",
                          text: "Roles Individuales enviados exitosamente",
                          color: "success"
                      });
          })
          .catch(error => {
            console.error("[ERROR::]",error)
            this.$vs.notify({
                    text: err,
                    color: "danger"
                });
          })
       
      /*setTimeout(()=>{
        this.enviaremail(id,fechrol);
      },9500);*/
      
    },
    
    pdf_rol_general(email){
      if(this.validaremail()){
        return;
      }
      this.$vs.notify({
                        title: "Enviando Rol General",
                        text: "Este proceso puede demorar por favor espere ...",
                        color: "warning"
                    });
       var url = "/api/rolpago/rol_general?id_empleado="+this.id_departamento+
      "&ing1="+this.idcolumn1+
      "&ing2="+this.idcolumn2+
      "&ing3="+this.idcolumn3+
      "&ing4="+this.idcolumn4+
      "&ing5="+this.idcolumn5+
      "&ing6="+this.idcolumn6+
      "&egr1="+this.idcolumneg1+
      "&egr2="+this.idcolumneg2+
      "&egr3="+this.idcolumneg3+
      "&egr4="+this.idcolumneg4+
      "&egr5="+this.idcolumneg5+
      "&egr6="+this.idcolumneg6+
      "&date="+this.fecha+
      "&id_user="+this.usuario.id+
      "&id_proyecto="+this.datos_proyecto.selectedproyecto;
      axios.get(url).then(resp=>{
        this.envio_rol_general(email);
      })
    },
    envio_rol_general(id){
      axios
                .post("/api/rolpago/correo/rol_general", {
                   id: this.id_departamento,
                   date: this.fecha,
                   usuario:this.usuario.id,
                   empresa:this.usuario.id_empresa,
                   email:id 
                   }).then(resp=>{
                     this.$vs.notify({
                          title: "Rol General Enviado",
                          text: "Rol General enviado con exito",
                          color: "success"
                      });
                      this.empleado_email="";
                      this.idcolumn1=0;
                      this.idcolumn2=0;
                      this.idcolumn3=0;
                      this.idcolumn4=0;
                      this.idcolumn5=0;
                      this.idcolumn6=0;
                      this.idcolumneg1=0;
                      this.idcolumneg2=0;
                      this.idcolumneg3=0;
                      this.idcolumneg4=0;
                      this.idcolumneg5=0;
                      this.idcolumneg6=0;
                      this.id_empleado=null;
                      this.id_departamento="";
                      this.fecha="";
                      this.general=true;
                      this.modal_pdf=false;
                   }).catch(err=>{
                     this.$vs.notify({
                          title: "Error al enviar Rol General",
                          text: "Revise si su correo es el correcto",
                          color: "danger"
                      });
                   })
    },
    envio_una_papeleta(id){
      if(this.validaremail()){
        return;
      }
        this.modal=false;
      this.$vs.notify({
                        title: "Enviando Papeletas",
                        text: "Este proceso puede demorar por favor espere ...",
                        color: "warning"
                    });
      var url = "/api/rolpago/papeleta/individual?id_empleado="+this.id_departamento+
      "&ing1="+this.idcolumn1+
      "&ing2="+this.idcolumn2+
      "&ing3="+this.idcolumn3+
      "&ing4="+this.idcolumn4+
      "&ing5="+this.idcolumn5+
      "&ing6="+this.idcolumn6+
      "&egr1="+this.idcolumneg1+
      "&egr2="+this.idcolumneg2+
      "&egr3="+this.idcolumneg3+
      "&egr4="+this.idcolumneg4+
      "&egr5="+this.idcolumneg5+
      "&egr6="+this.idcolumneg6+
      "&date="+this.fecha+
      "&id_user="+this.usuario.id+
      "&empleado="+id;
      axios.get(url).then(resp=>{
        this.enviaremail(this.id_departamento,this.fecha);
                this.idcolumn1=0;
                this.idcolumn2=0;
                this.idcolumn3=0;
                this.idcolumn4=0;
                this.idcolumn5=0;
                this.idcolumn6=0;
                this.idcolumneg1=0;
                this.idcolumneg2=0;
                this.idcolumneg3=0;
                this.idcolumneg4=0;
                this.idcolumneg5=0;
                this.idcolumneg6=0;
                this.id_empleado=null;
                this.id_departamento="";
                this.fecha="";
                this.general=true;
                this.modal_pdf=false;
      }).catch(err=>{
        this.$vs.notify({
                  text: err,
                  color: "danger"
              });
      })
      /*this.traerIngresos(this.id_departamento,this.fecha)
        .then(value => {
          // Resolucion de traerIngresos
          return this.traerEgresos(this.id_departamento,this.fecha);
        })
        .then(value => {
          // resolucion de this.traerEgresos
          
          return this.PdfPapeletaIndividual(this.id_departamento,this.fecha,id);
        })
        .then(value => {
          
          this.enviaremail(this.id_departamento,this.fecha);// resolucion de this.traerEmpleado
          
        }).then(value=>{
         
          this.$vs.notify({
                        title: "Rol Individual enviado",
                        text: "Rol Individual enviado exitosamente",
                        color: "success"
                    });
                this.idcolumn1=0;
                this.idcolumn2=0;
                this.idcolumn3=0;
                this.idcolumn4=0;
                this.idcolumn5=0;
                this.idcolumn6=0;
                this.idcolumneg1=0;
                this.idcolumneg2=0;
                this.idcolumneg3=0;
                this.idcolumneg4=0;
                this.idcolumneg5=0;
                this.idcolumneg6=0;
                this.id_empleado=null;
                this.id_departamento="";
                this.fecha="";
                this.general=true;
                this.modal_pdf=false;
        })
        .catch(error => {
          console.error("[ERROR::Envio Papeleta]",error);
          this.$vs.notify({
                  text: error,
                  color: "danger"
              });
        })
      */
      /*axios
                .post("/api/rolpago/correo", {
                   id: id,
                   date: fechrol,
                   usuario:this.usuario.id,
                   empresa:this.usuario.id_empresa 
                   })*/
    },
    async  enviaremail(id,fechrol){
      
        let {data}= await    axios
                .post("/api/rolpago/correo", {
                   id: id,
                   date: fechrol,
                   usuario:this.usuario.id,
                   empresa:this.usuario.id_empresa 
                   })
                   return data
                /*.then(() => {
                    this.$vs.notify({
                        title: "Papeleta Individual enviada",
                        text: "Papeleta Individual enviada exitosamente",
                        color: "success"
                    });
                });*/
    },
    //listar ingresos
    traerIngresos(id,fechrol){
      return new Promise((resolve,reject)=>{
        var url2 = "/api/ingresoRoles/"+id;
        axios
        .get(url2,{
                    params: {
                        fecha: fechrol
                    }
                })
        .then(res => {
          if(res.data==="vacio"){
            this.columna1=null;
            //this.valor1=0;
            this.columna2=null;
            //this.valor2=0;
            this.columna3=null;
            //this.valor3=0;
            this.columna4=null;
            //this.valor4=0;
            this.columna5=null;
            //this.valor5=0;
            this.columna6=null;
            //this.valor6=0;
            
          }else{
            this.columnasingreso=res.data;
            this.nrcolumnas=res.data;
            
            if(this.nrcolumnas.length==1){
              this.columna1=res.data[0].decripcion;
              this.idcolumn1=res.data[0].id_ineg;
              this.columna2="";
              //this.valor2="";
              this.columna3="";
              //this.valor3="";
              this.columna4="";
              //this.valor4="";
              this.columna5="";
              //this.valor5="";
              this.columna6="";
              //this.valor6="";
              
            }
            if(this.nrcolumnas.length==2){
              this.columna1=res.data[0].decripcion;
              this.idcolumn1=res.data[0].id_ineg;
              this.columna2=res.data[1].decripcion;
              this.idcolumn2=res.data[1].id_ineg;
              this.columna3="";
              //this.valor3="";
              this.columna4="";
              //this.valor4="";
              this.columna5="";
              //this.valor5="";
              this.columna6="";
              //this.valor6="";
              
            }
            if(this.nrcolumnas.length==3){
              this.columna1=res.data[0].decripcion;
              this.idcolumn1=res.data[0].id_ineg;
              this.columna2=res.data[1].decripcion;
              this.idcolumn2=res.data[1].id_ineg;
              this.columna3=res.data[2].decripcion;
              this.idcolumn3=res.data[2].id_ineg;
              this.columna4="";
              //this.valor4="";
              this.columna5="";
              //this.valor5="";
              this.columna6="";
              //this.valor6="";
              
            }
            if(this.nrcolumnas.length==4){
              this.columna1=res.data[0].decripcion;
              this.idcolumn1=res.data[0].id_ineg;
              this.columna2=res.data[1].decripcion;
              this.idcolumn2=res.data[1].id_ineg;
              this.columna3=res.data[2].decripcion;
              this.idcolumn3=res.data[2].id_ineg;
              this.columna4=res.data[3].decripcion;
              this.idcolumn4=res.data[3].id_ineg;
              this.columna5="";
              //this.valor5="";
              this.columna6="";
              //this.valor6="";
              
            }
            if(this.nrcolumnas.length==5){
              this.columna1=res.data[0].decripcion;
              this.idcolumn1=res.data[0].id_ineg;
              this.columna2=res.data[1].decripcion;
              this.idcolumn2=res.data[1].id_ineg;
              this.columna3=res.data[2].decripcion;
              this.idcolumn3=res.data[2].id_ineg;
              this.columna4=res.data[3].decripcion;
              this.idcolumn4=res.data[3].id_ineg;
              this.columna5=res.data[4].decripcion;
              this.idcolumn5=res.data[4].id_ineg;
              this.columna6="";
              //this.valor6="";
              
            }
            if(this.nrcolumnas.length==6){
              this.columna1=res.data[0].decripcion;
              this.idcolumn1=res.data[0].id_ineg;
              this.columna2=res.data[1].decripcion;
              this.idcolumn2=res.data[1].id_ineg;
              this.columna3=res.data[2].decripcion;
              this.idcolumn3=res.data[2].id_ineg;
              this.columna4=res.data[3].decripcion;
              this.idcolumn4=res.data[3].id_ineg;
              this.columna5=res.data[4].decripcion;
              this.idcolumn5=res.data[4].id_ineg;
              this.columna6=res.data[5].decripcion;
              this.idcolumn6=res.data[5].id_ineg;
              
            }
          }
          resolve(this.idcolumn1,this.idcolumn2,this.idcolumn3,this.idcolumn4,this.idcolumn5,this.idcolumn6)

        })
        .catch(function(error) {
          reject(error)
        });
      }) 
    },
    //listar egresos
    traerEgresos(id,fechrol){
      return new Promise((resolve,reject)=>{
        var url2 = "/api/egresoRoles/"+id;
         axios
         .get(url2,{
                     params: {
                         fecha:fechrol
                     }
                 })
         .then(res => {
           if(res.data=="vacio"){
             this.columnaeg1=null;
             this.columnaeg2=null;
             this.columnaeg3=null;
             this.columnaeg4=null;
             this.columnaeg5=null;
             this.columnaeg6=null;
             
           }else{
             this.nrcolumnaseg=res.data;
             this.contenidoegreso=res.data;
             
             if(this.nrcolumnaseg.length==1){
               this.columnaeg1=res.data[0].decripcion;
               this.idcolumneg1=res.data[0].id_ineg;
               this.columnaeg2="";
              
               this.columnaeg3="";
              
               this.columnaeg4="";
              
               this.columnaeg5="";
              
               this.columnaeg6="";
              
             }
             if(this.nrcolumnaseg.length==2){
               this.columnaeg1=res.data[0].decripcion;
               this.idcolumneg1=res.data[0].id_ineg;
               this.columnaeg2=res.data[1].decripcion;
               this.idcolumneg2=res.data[1].id_ineg;
 
               this.columnaeg3="";
              
               this.columnaeg4="";
              
               this.columnaeg5="";
              
               this.columnaeg6="";
               
              
             }
             if(this.nrcolumnaseg.length==3){
               this.columnaeg1=res.data[0].decripcion;
               this.idcolumneg1=res.data[0].id_ineg;
               this.columnaeg2=res.data[1].decripcion;
               this.idcolumneg2=res.data[1].id_ineg;
               this.columnaeg3=res.data[2].decripcion;
               this.idcolumneg3=res.data[2].id_ineg;
               this.columnaeg4="";
              
               this.columnaeg5="";
              
               this.columnaeg6="";
              
             }
             if(this.nrcolumnaseg.length==4){
               this.columnaeg1=res.data[0].decripcion;
               this.idcolumneg1=res.data[0].id_ineg;
               this.columnaeg2=res.data[1].decripcion;
               this.idcolumneg2=res.data[1].id_ineg;
               this.columnaeg3=res.data[2].decripcion;
               this.idcolumneg3=res.data[2].id_ineg;
               this.columnaeg4=res.data[3].decripcion;
               this.idcolumneg4=res.data[3].id_ineg;
               this.columnaeg5="";
              
               this.columnaeg6="";
               
              
             }
             if(this.nrcolumnaseg.length==5){
               this.columnaeg1=res.data[0].decripcion;
               this.idcolumneg1=res.data[0].id_ineg;
               this.columnaeg2=res.data[1].decripcion;
               this.idcolumneg2=res.data[1].id_ineg;
               this.columnaeg3=res.data[2].decripcion;
               this.idcolumneg3=res.data[2].id_ineg;
               this.columnaeg4=res.data[3].decripcion;
               this.idcolumneg4=res.data[3].id_ineg;
               this.columnaeg5=res.data[4].decripcion;
               this.idcolumneg5=res.data[4].id_ineg;
               this.columnaeg6="";
               
              
             }
             if(this.nrcolumnaseg.length==6){
               this.columnaeg1=res.data[0].decripcion;
               this.idcolumneg1=res.data[0].id_ineg;
               this.columnaeg2=res.data[1].decripcion;
               this.idcolumneg2=res.data[1].id_ineg;
               this.columnaeg3=res.data[2].decripcion;
               this.idcolumneg3=res.data[2].id_ineg;
               this.columnaeg4=res.data[3].decripcion;
               this.idcolumneg4=res.data[3].id_ineg;
               this.columnaeg5=res.data[4].decripcion;
               this.idcolumneg5=res.data[4].id_ineg;
               this.columnaeg6=res.data[5].decripcion;
               this.idcolumneg6=res.data[5].id_ineg;
               
             }
             
           }
           resolve(this.idcolumneg1,this.idcolumneg2,this.idcolumneg3,this.idcolumneg4,this.idcolumneg5,this.idcolumneg6);
         })
         .catch(function(error) {
           reject(error)
         });
      })
    },
    
    //listan clientes
    listar(page, buscar) {
    if (this.timeout) {
        clearTimeout(this.timeout);
      }
      this.timeout = setTimeout(() => {
      var url =
        "/api/rol/"+this.usuario.id_empresa
      axios.get(url).then(res => {
        this.contenido = res.data;
        this.filterstable.contrue = res.data;
        this.filtertabla();
        //console.log(this.contenido+"Datos_2");
      });
            }, 800);
    },
    
    listarempleados(){
      var url =
        "/api/traerempleadorol"
      axios.get(url,{
        params:{
          id_departamento:this.id_departamento,
          fecha:this.fecha
        }
      }).then(res => {
        this.empleados = res.data;
        this.Proyecto(res.data[0].cod_rol_pago);
      });
    },
    Proyecto(id){
      var url = "/api/rolpago/empleado/"+id;
      axios.get(url,{params:{
        id_empresa:this.usuario.id_empresa
      }})
      .then(resp=>{
        this.datos_proyecto.proyectoList=resp.data; 
      }).catch(err=>{
        console.log("EROOR CONTABILIDAD:"+err);
      });
    },
    

    //exportar archivo
    /*exportardatos() {
      import("../../vendor/Export2Excel").then(excel => {
        const list = this.contenido;
        const data = this.formatJson(this.indexs, list);
        excel.export_json_to_excel({
          header: this.cabezera,
          data,
          filename: this.nombreexportar,
          autoWidth: this.cellancho,
          bookType: this.tipoformatoexportar
        });
      });
    },*/
    formatJson(filterVal, jsonData) {
      return jsonData.map(v =>
        filterVal.map(j => {
          return v[j];
        })
      );
    },
    //importar archivos
    loadDataInTable({ results, header, meta }) {
      this.header = header;
      this.tableData = results;
      this.sheetName = meta.sheetName;
    },
    updateSearchQuery(val) {
      this.gridApi.setQuickFilter(val);
    },
    //editar cliente
    editar(id) {
      this.$router.push(`/nomina/rol-pagos/${id}/editar`);
    },
    //ver cliente
    ver(id) {
      this.$router.push(`/nomina/rol-pagos/${id}/ver`);
    },
    //eliminar cliente
    
    importarexcel() {
      $(".inputexcel").click();
    },
    validaremail(){
      this.error=0;
      this.error_email=[];
      this.error_email_general=[];
      if(!this.id_empleado && this.general==false){
        this.error_email.push("Campo Obligatorio");
        this.error=1;
      }
      if(!this.empleado_email && this.general==true){
        this.error_email_general.push("Campo Obligatorio");
        this.error=1;
      }
      return this.error;
    },
  },
  mounted() {
    this.listar(1, this.buscar);
    //this.listarempleados();
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
  width: 1060px !important;
}
.peque .vs-popup {
  width: 600px !important;
}
input[type="”file”"]#nuestroinput {
  width: 0.1px;
  height: 0.1px;
  opacity: 0;
  overflow: hidden;
  position: absolute;
  z-index: -1;
}
.valores input {
  text-align:end;
}
.valores .vs-input--placeholder {
  text-align:end;
}
.center label {
  text-align:center;
}
.center .vs-input--placeholder {
  text-align:center;
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
