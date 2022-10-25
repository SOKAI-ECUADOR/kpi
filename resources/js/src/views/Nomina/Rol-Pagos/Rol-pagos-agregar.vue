<template>
  <vx-card title="Asignar Rol Pagos">
    
    <div class="vx-row"></div>
    <vs-tabs alignment="fixed" v-model="Tabnav">
      <vs-tab title="BasicInfo" label="Ingresos" icon-pack="feather" icon="icon-book-open">
        <div class="vx-row">
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <label class="vs-input--label">Departamento</label>
            <vx-input-group class>
              <vs-input class="w-full" v-model="departamento" :value="iddepart" disabled />

              <template slot="append">
                <div class="append-text btn-addon">
                  <!-- -->
                  <vs-button color="primary" v-if="!idrecupera" @click="abrirdepart()">Buscar</vs-button>
                </div>
              </template>
              
              <div v-show="error" v-if="!iddepart">
                  <div v-for="err in errordepartamento" :key="err" v-text="err" class="text-danger"></div>
              </div>
            </vx-input-group>
          </div>
          
          <div class="vx-col sm:w-1/5 w-full mb-6">
          <label class="vs-input--label">Fecha</label>
            
            <flat-pickr :config="configdateTimePicker" v-model="fechrol"  />
            <div v-show="error" v-if="!fechrol">
              <div v-for="err in errorfecha" :key="err" v-text="err" class="text-danger"></div>
            </div>
            {{fecha_rol_pago}}
            <!--<vs-select autocomplete class="selectExample w-full mt-3" v-model="fechrol" @change="handleEmpleados()">
            <vs-select-item
              
              :key="index"
              :value="item.value"
              :text="item.text"
              v-for="(item,index) in forma_pago_array"
              
            />
            </vs-select>-->
          </div>
          
          <!--===============================DEPARTAMENTO POPUP============================================-->
          <vs-popup title="Departamento" class="depa" :active.sync="activePrompt4">
            <div class="con-exemple-prompt">
              <vs-table stripe :data="contenidodepartamento" @selected="handleSelected4">
                <template slot="thead">
                  <vs-th>Código</vs-th>
                  <vs-th>Nombre Departamento</vs-th>
                </template>
                <template slot-scope="{data}">
                  <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                    <vs-td :data="data[indextr].id_departamento">{{ data[indextr].id_departamento }}</vs-td>
                    <vs-td :data="data[indextr].dep_nombre">{{ data[indextr].dep_nombre}}</vs-td>
                  </vs-tr>
                </template>
              </vs-table>
            </div>
          </vs-popup>
          <!--======================================FIN=====================================================-->
        </div>
        <vs-divider position="left">

          <h3>Ingreso</h3>
        </vs-divider>
        
          
          <vs-table hoverFlat :data="contenidopr" style="font-size: 12px;width:100%;">
            <template slot="thead">
              <!--<vs-th class="table-header">Nº</vs-th>-->
              <vs-th class="table-header">Listado Personal</vs-th>
              <vs-th class="table-header">Dias Trabajados</vs-th>
              <vs-th class="table-header" style="min-width:160px!important;">Proyecto</vs-th>
              <vs-th class="table-header" style="min-width:110px!important;">Sueldo Basico</vs-th>
              <vs-th class="table-header" v-if="columna1">{{columna1}}</vs-th>
              <vs-th class="table-header" v-if="columna2">{{columna2}}</vs-th>
              <vs-th class="table-header" v-if="columna3">{{columna3}}</vs-th>
              <vs-th class="table-header" v-if="columna4">{{columna4}}</vs-th>
              <vs-th class="table-header" v-if="columna5">{{columna5}}</vs-th>
              <vs-th class="table-header" v-if="columna6">{{columna6}}</vs-th>
              <vs-th class="table-header" v-if="totaldecimo_tercero()!=0" style="min-width:110px!important;">Decimo Tercero</vs-th>
              <vs-th class="table-header" v-if="totaldecimo_cuarto()!=0" style="min-width:110px!important;">Decimo Cuarto</vs-th>
              <vs-th class="table-header" v-if="totalfondo_reserva()!=0" style="min-width:110px!important;">Fondo Reserva</vs-th>
              <vs-th class="table-header" style="min-width:110px!important;">Total Ingresos</vs-th>
            </template>
            <template slot-scope="{data}">
              <vs-tr v-for="(tr, index) in data" :key="index">
                <!--<vs-td v-if="!idrecupera" :data="tr.id_empleado" style="width:100px!important;">{{ tr.id_empleado }}</vs-td>
                <vs-td v-else :data="tr.id_rol_pago" style="width:100px!important;">{{ tr.id_rol_pago }}</vs-td>-->
                <vs-td :data="tr.primer_nombre" style="width:140px!important;">{{ tr.primer_nombre }} {{tr.apellido_paterno}}</vs-td>
                <vs-td :data="tr.cantidad" style="width:50px!important;">
                  <vs-input class="w-full"  v-model="tr.cantidad" @keypress="solonumeros($event)" :value="valor1" name="30" maxlength="2"/>
                  <div v-show="error" v-if="tr.cantidad>30 || tr.cantidad<1">
                    <div
                      v-for="err in tr.errordias"
                      :key="err"
                      v-text="err"
                      class="text-danger"
                    ></div>
                  </div>
                </vs-td>
                <vs-td :data="tr.id_proyecto" style="width:180px!important;">
                  
                  <vs-select
                            
                            placeholder="--Seleccione--"
                            autocomplete
                            class="selectExample w-full"
                            v-model="tr.id_proyecto"
                        >
                        <!--canton = '';
                                parroquia = '';-->
                            <vs-select-item
                                v-for="data in proyectos"
                                :key="data.id_proyecto"
                                :value="data.id_proyecto"
                                :text="data.descripcion"
                            />
                        </vs-select>
                        <div v-show="error" v-if="!tr.id_proyecto">
                    <div
                      v-for="err in tr.errorproyecto"
                      :key="err"
                      v-text="err"
                      class="text-danger"
                    ></div>
                  </div>
                </vs-td>
                
                <vs-td :data="tr.sueldo" style="width:140px!important;text-align:right;" >
                  {{parseFloat(tr.sueldo)/30*(parseFloat(tr.cantidad)) | currency}}
                </vs-td>
                {{valoringreso1}}
                <vs-td :data="tr.ingreso1" style="width:100px!important;text-align:right;" v-if="columna1">
                  <!--<vs-input class="w-full"  v-model="tr.ingreso1" :value="tr.valor_ingreso1" disabled/>-->
                  {{tr.ingreso1 | currency}}
                </vs-td>
                {{valoringreso2}}
                <vs-td :data="tr.ingreso2" style="width:100px!important;text-align:right;" v-if="columna2">
                  <!---<vs-input class="w-full"  v-model="tr.ingreso2" :value="tr.valor_ingreso2" disabled/>-->
                  {{tr.ingreso2 | currency}}
                </vs-td>
                {{valoringreso3}}
                <vs-td :data="tr.ingreso3" style="width:100px!important;text-align:right;" v-if="columna3">
                  {{tr.ingreso3 | currency}}
                  <!--<vs-input class="w-full"  v-model="tr.ingreso3" :value="tr.valor_ingreso3" disabled/>-->
                </vs-td>
                {{valoringreso4}}
                <vs-td :data="tr.ingreso4" style="width:100px!important;text-align:right;" v-if="columna4">
                  {{tr.ingreso4 | currency}}
                  <!--<vs-input class="w-full"  v-model="tr.ingreso4" :value="tr.valor_ingreso4" disabled/>-->
                </vs-td>
                {{valoringreso5}}
                <vs-td :data="tr.ingreso5" style="width:100px!important;text-align:right;" v-if="columna5">
                 {{tr.ingreso5 | currency}}
                 <!--<vs-input class="w-full"  v-model="tr.ingreso5" :value="tr.valor_ingreso5" disabled/>-->
                </vs-td>
                {{valoringreso6}}
                <vs-td :data="tr.ingreso6" style="width:100px!important;text-align:right;" v-if="columna6">
                  {{tr.ingreso6 | currency}}
                  <!--<vs-input class="w-full"  v-model="tr.ingreso6" :value="tr.valor_ingreso6" disabled/>-->
                </vs-td>
                {{valordecimo_tercero}}
                <vs-td :data="tr.decimo_tercero" style="width:100px!important;text-align:right;" v-if="totaldecimo_tercero()!=0">
                  {{tr.decimo_tercero | currency}}
                </vs-td>
                {{valordecimo_cuarto}}
                <vs-td :data="tr.decimo_cuarto" style="width:100px!important;text-align:right;" v-if="totaldecimo_cuarto()!=0">
                  {{tr.decimo_cuarto | currency}}
                </vs-td>
                {{valorfondo_reserva}}
                <vs-td :data="tr.fondo_reserva" style="width:100px!important;text-align:right;" v-if="totalfondo_reserva()!=0">
                  {{tr.fondo_reserva | currency}}
                </vs-td>
                <vs-td :data="tr.totalingreso" style="width:140px!important;text-align:right;">
                  {{(parseFloat(tr.sueldo)/30*(parseFloat(tr.cantidad)))+parseFloat(tr.ingreso1)+parseFloat(tr.ingreso2)+parseFloat(tr.ingreso3)+parseFloat(tr.ingreso4)+parseFloat(tr.ingreso5)+parseFloat(tr.ingreso6)+parseFloat(tr.decimo_tercero)+parseFloat(tr.decimo_cuarto)+parseFloat(tr.fondo_reserva) |currency}}
                </vs-td>
                <!--<vs-td :data="tr.totalingreso" v-else-if="columna1 && columna2 && columna3 && columna4 && columna5">
                  {{parseFloat(tr.sueldo)+parseFloat(tr.ingreso1)+parseFloat(tr.ingreso2)+parseFloat(tr.ingreso3)+parseFloat(tr.ingreso4)+parseFloat(tr.ingreso5) |currency}}
                </vs-td>
                <vs-td :data="tr.totalingreso" v-else-if="columna1 && columna2 && columna3 && columna4">
                  {{parseFloat(tr.sueldo)+parseFloat(tr.ingreso1)+parseFloat(tr.ingreso2)+parseFloat(tr.ingreso3)+parseFloat(tr.ingreso4) |currency}}
                </vs-td>
                <vs-td :data="tr.totalingreso" v-else-if="columna1 && columna2 && columna3">
                  {{parseFloat(tr.sueldo)+parseFloat(tr.ingreso1)+parseFloat(tr.ingreso2)+parseFloat(tr.ingreso3) |currency}}
                </vs-td>
                <vs-td :data="tr.totalingreso" v-else-if="columna1 && columna2 ">
                  {{parseFloat(tr.sueldo)+parseFloat(tr.ingreso1)+parseFloat(tr.ingreso2) |currency}}
                </vs-td>
                <vs-td :data="tr.totalingreso" v-else-if="columna1">
                  {{parseFloat(tr.sueldo)+parseFloat(tr.ingreso1) |currency}}
                </vs-td>
                <vs-td :data="tr.totalingreso" v-else>
                  {{parseFloat(tr.sueldo) |currency}}
                </vs-td>-->
              </vs-tr>
             <vs-tr style="border-top: 1px solid #ddd;font-size: 15px;">
                
                <th><h5>Total</h5></th>
                <th></th>
                <th></th>
                <th style="text-align:right;"><h5>{{sueldototal() | currency}}</h5></th>
                <th v-if="columna1" style="text-align:right;"><h5>{{totalvalorcolum1() | currency}}</h5></th>
                <th v-if="columna2" style="text-align:right;"><h5>{{totalvalorcolum2() | currency}}</h5></th>
                <th v-if="columna3" style="text-align:right;"><h5>{{totalvalorcolum3() | currency}}</h5></th>
                <th v-if="columna4" style="text-align:right;"><h5>{{totalvalorcolum4() | currency}}</h5></th>
                <th v-if="columna5" style="text-align:right;"><h5>{{totalvalorcolum5() | currency}}</h5></th>
                <th v-if="columna6" style="text-align:right;"><h5>{{totalvalorcolum6() | currency}}</h5></th>
                <th v-if="totaldecimo_tercero()!=0" style="text-align:right;"><h5>{{totaldecimo_tercero() | currency}}</h5></th>
                <th v-if="totaldecimo_cuarto()!=0" style="text-align:right;"><h5>{{totaldecimo_cuarto() | currency}}</h5></th>
                <th v-if="totalfondo_reserva()!=0" style="text-align:right;"><h5>{{totalfondo_reserva() | currency}}</h5></th>
                <th style="text-align:right;"><h5>{{totalingreso() | currency}}</h5></th>
              </vs-tr>
            </template>
          </vs-table>
          <br>
          <!--<div class="vx-row" >
            <div class="vx-col sm:w-1/6 w-full mb-6 text-center"  hidden>
              <h5>Total Dias</h5>
              {{totaldias}}
            </div>
            <div class="vx-col sm:w-1/6 w-full mb-6 text-center"  v-if="columna1">
              <label class="vs-input--label">{{columna1}}</label>
              <h1>{{totalcolum1() | currency}}</h1>
            </div>
            <div class="vx-col sm:w-1/6 w-full mb-6 text-center"  v-if="columna2">
              <label class="vs-input--label">{{columna2}}</label>
              <h1>{{totalcolum2() | currency}}</h1>
            </div>
            <div class="vx-col sm:w-1/6 w-full mb-6 text-center"  v-if="columna3">
              <label class="vs-input--label">{{columna3}}</label>
              <h1>{{totalcolum3() | currency}}</h1>
            </div>
            <div class="vx-col sm:w-1/6 w-full mb-6 text-center"  v-if="columna4">
              <label class="vs-input--label">{{columna4}}</label>
              <h1>{{totalcolum4() | currency}}</h1>
            </div>
            <div class="vx-col sm:w-1/6 w-full mb-6 text-center"  v-if="columna5">
              <label class="vs-input--label">{{columna5}}</label>
              <h1>{{totalcolum5() | currency}}</h1>
            </div>
            <div class="vx-col sm:w-1/6 w-full mb-6 text-center"  v-if="columna6">
              <label class="vs-input--label">{{columna6}}</label>
              <h1>{{totalcolum6() | currency}}</h1>
            </div>
            <div class="vx-col sm:w-1/5 w-full mb-6 text-center">
              
              <label class="vs-input--label">Total Ingreso</label>
              <h1>{{totalingreso() | currency}}</h1>
            </div>
          </div>-->
                      <div class="vx-col w-full" hidden>
                <vs-button
                    color="success"
                    type="filled"
                    
                    v-if="$route.params.id"
                    >GUARDAR</vs-button
                >
                <vs-button
                    color="success"
                    type="filled"
                   
                    v-else
                    >GUARDAR</vs-button
                >
                <vs-button
                    color="danger"
                    type="filled"
                    to="/nomina/rol-pagos"
                    >CANCELAR</vs-button
                >
            </div>
            
          <!--dividir-->
        
        
        <div class="vx-row">
          
        <vs-table hoverFlat :data="columnasingreso" style="font-size: 12px;" hidden >
            <template slot="thead">
              <vs-th class="table-header">Descripcion</vs-th>
            </template>
            <template slot-scope="{data}">
              <vs-tr v-for="(tr, index) in data" :key="index">
                <vs-td :data="tr.decripcion" style="width:100px!important;">{{ tr.decripcion }}</vs-td>
              </vs-tr>
            </template>
          </vs-table>
          
          <vs-table hoverFlat :data="contenidoingreso" style="font-size: 12px;" hidden>
            <template slot="thead">
              <vs-th class="table-header">Descripcion</vs-th>
              <vs-th class="table-header">Valor</vs-th>
              <vs-th class="table-header">Id Empleado</vs-th>
            </template>
            <template slot-scope="{data}">
              <vs-tr v-for="(tr, index) in data" :key="index">
                <vs-td :data="tr.decripcion" style="width:100px!important;">{{ tr.decripcion }}</vs-td>
                <vs-td :data="tr.valor" style="width:150px!important;">{{ tr.valor }}</vs-td>
                <vs-td :data="tr.id_empleado" style="width:150px!important;">{{ tr.id_empleado }}</vs-td>
              </vs-tr>
            </template>
          </vs-table>
          </div>
        <!---->
      </vs-tab>
      <vs-tab
        title="JobInfo"
        id="JobInfo"
        label="Egresos"
        icon-pack="feather"
        icon="icon-briefcase"
      >
      
      <vs-table hoverFlat :data="contenidopr" style="font-size: 12px;" >
            <template slot="thead">
              <vs-th class="table-header">Listado Personal</vs-th>
              <vs-th class="table-header" v-if="columnaeg1">{{columnaeg1}}</vs-th>
              <vs-th class="table-header" v-if="columnaeg2">{{columnaeg2}}</vs-th>
              <vs-th class="table-header" v-if="columnaeg3">{{columnaeg3}}</vs-th>
              <vs-th class="table-header" v-if="columnaeg4">{{columnaeg4}}</vs-th>
              <vs-th class="table-header" v-if="columnaeg5">{{columnaeg5}}</vs-th>
              <vs-th class="table-header" v-if="columnaeg6">{{columnaeg6}}</vs-th>
              <vs-th class="table-header" hidden>Sueldo</vs-th>
              <vs-th class="table-header">Aporte Personal</vs-th>
              <vs-th class="table-header">total Egresos</vs-th>
              <vs-th class="table-header">Valor a Recibir</vs-th>
            </template>
            <template slot-scope="{data}">
              <vs-tr v-for="(tr, index) in data" :key="index">
               <!-- <vs-td v-if="!iddepart" :data="tr.id_empleado" style="width:100px!important;">{{ tr.id_empleado }}</vs-td>
                <vs-td v-else :data="tr.id_rol_pago" style="width:100px!important;">{{ tr.id_rol_pago }}</vs-td>-->
                <vs-td :data="tr.primer_nombre" style="width:150px!important;">{{ tr.primer_nombre }} {{tr.apellido_paterno}}</vs-td>
                {{valoregreso1}}
                <vs-td :data="tr.egreso1" style="width:100px!important;text-align:right;" v-if="columnaeg1">
                  <!--<vs-input class="w-full"  v-model="tr.egreso1" disabled/>-->
                  {{tr.egreso1 | currency}}
                </vs-td>
                {{valoregreso2}}
                <vs-td :data="tr.egreso2" style="width:100px!important;text-align:right;" v-if="columnaeg2">
                  {{tr.egreso2 | currency}}
                </vs-td>
                {{valoregreso3}}
                <vs-td :data="tr.egreso3" style="width:100px!important;text-align:right;" v-if="columnaeg3">
                  {{tr.egreso3 | currency}}
                </vs-td>
                {{valoregreso4}}
                <vs-td :data="tr.egreso4" style="width:100px!important;text-align:right;" v-if="columnaeg4">
                  {{tr.egreso4 | currency}}
                </vs-td>
                {{valoregreso5}}
                <vs-td :data="tr.egreso5" style="width:100px!important;text-align:right;" v-if="columnaeg5">
                  {{tr.egreso5 | currency}}
                </vs-td>
                {{valoregreso6}}
                <vs-td :data="tr.egreso6" style="width:100px!important;text-align:right;" v-if="columnaeg6">
                  {{tr.egreso6 | currency}}
                </vs-td>
                <vs-td :data="tr.total_ingreso" style="width:100px!important;text-align:right;" hidden>
                  {{(parseFloat(tr.sueldo)/30*(parseFloat(tr.cantidad)))+parseFloat(tr.ingreso1)+parseFloat(tr.ingreso2)+parseFloat(tr.ingreso3)+parseFloat(tr.ingreso4)+parseFloat(tr.ingreso5)+parseFloat(tr.ingreso6) |currency}}
                </vs-td>
                <vs-td :data="tr.iess" style="width:100px!important;text-align:right;">
                  {{((parseFloat(tr.sueldo)/30*(parseFloat(tr.cantidad)))+parseFloat(tr.valor_ingreso1)+parseFloat(tr.valor_ingreso2)+parseFloat(tr.valor_ingreso3)+parseFloat(tr.valor_ingreso4)+parseFloat(tr.valor_ingreso5)+parseFloat(tr.valor_ingreso6))*(9.45/100) |currency}}
                </vs-td>
                <vs-td :data="tr.totalegreso" style="width:90px!important;text-align:right;">
                  {{parseFloat(tr.egreso1)+(((parseFloat(tr.sueldo)/30*(parseFloat(tr.cantidad)))+parseFloat(tr.valor_ingreso1)+parseFloat(tr.valor_ingreso2)+parseFloat(tr.valor_ingreso3)+parseFloat(tr.valor_ingreso4)+parseFloat(tr.valor_ingreso5)+parseFloat(tr.valor_ingreso6))*(9.45/100))+parseFloat(tr.egreso2)+parseFloat(tr.egreso3)+parseFloat(tr.egreso4)+parseFloat(tr.egreso5)+parseFloat(tr.egreso6) |currency}}
                </vs-td>
                <!--<vs-td :data="tr.totalegreso" style="width:100px!important;" v-else-if="(columnaeg1 && columnaeg2 && columnaeg3 && columnaeg4 && columnaeg5)&&(columna1 && columna2 && columna3 && columna4 && columna5)">
                  {{parseFloat(tr.egreso1)+(parseFloat(tr.total_ingreso)*(9.45/100))+parseFloat(tr.egreso2)+parseFloat(tr.egreso3)+parseFloat(tr.egreso4)+parseFloat(tr.egreso5) |currency}}
                </vs-td>
                <vs-td :data="tr.totalegreso" style="width:100px!important;" v-else-if="(columnaeg1 && columnaeg2 && columnaeg3 && columnaeg4)&&(columna1 && columna2 && columna3 && columna4)">
                  {{parseFloat(tr.egreso1)+(parseFloat(tr.total_ingreso)*(9.45/100))+parseFloat(tr.egreso2)+parseFloat(tr.egreso3)+parseFloat(tr.egreso4) |currency}}
                </vs-td>
                <vs-td :data="tr.totalegreso" style="width:100px!important;" v-else-if="columnaeg1 && columnaeg2 && columnaeg3">
                  {{parseFloat(tr.egreso1)+(parseFloat(tr.total_ingreso)*(9.45/100))+parseFloat(tr.egreso2)+parseFloat(tr.egreso3) |currency}}
                </vs-td>
                <vs-td :data="tr.totalegreso" style="width:100px!important;" v-else-if="columnaeg1 && columnaeg2 ">
                  {{parseFloat(tr.egreso1)+(parseFloat(tr.total_ingreso)*(9.45/100))+parseFloat(tr.egreso2) |currency}}
                </vs-td>
                <vs-td :data="tr.totalegreso" style="width:100px!important;" v-else-if="columnaeg1">
                  {{parseFloat(tr.egreso1)+(parseFloat(tr.total_ingreso)*(9.45/100)) |currency}}
                </vs-td>-->
                <vs-td :data="tr.valor_recibir" style="width:100px!important;text-align:right;">
                  {{((parseFloat(tr.sueldo)/30*(parseFloat(tr.cantidad)))+parseFloat(tr.ingreso1)+parseFloat(tr.ingreso2)+parseFloat(tr.ingreso3)+parseFloat(tr.ingreso4)+parseFloat(tr.ingreso5)+parseFloat(tr.ingreso6)+parseFloat(tr.decimo_tercero)+parseFloat(tr.decimo_cuarto)+parseFloat(tr.fondo_reserva))-(parseFloat(tr.egreso1)+(((parseFloat(tr.sueldo)/30*(parseFloat(tr.cantidad)))+parseFloat(tr.valor_ingreso1)+parseFloat(tr.valor_ingreso2)+parseFloat(tr.valor_ingreso3)+parseFloat(tr.valor_ingreso4)+parseFloat(tr.valor_ingreso5)+parseFloat(tr.valor_ingreso6))*(9.45/100))+parseFloat(tr.egreso2)+parseFloat(tr.egreso3)+parseFloat(tr.egreso4)+parseFloat(tr.egreso5)+parseFloat(tr.egreso6)) |currency}}
                </vs-td>
                <!--<vs-td :data="tr.valor_recibir" v-else-if="columnaeg1 && columnaeg2 && columnaeg3 && columnaeg4 && columnaeg5">
                  {{parseFloat(tr.total_ingreso)-(parseFloat(tr.egreso1)+(parseFloat(tr.total_ingreso)*(9.45/100))+parseFloat(tr.egreso2)+parseFloat(tr.egreso3)+parseFloat(tr.egreso4)+parseFloat(tr.egreso5)) |currency}}
                </vs-td>
                <vs-td :data="tr.valor_recibir" v-else-if="columnaeg1 && columnaeg2 && columnaeg3 && columnaeg4">
                  {{parseFloat(tr.total_ingreso)-(parseFloat(tr.egreso1)+(parseFloat(tr.total_ingreso)*(9.45/100))+parseFloat(tr.egreso2)+parseFloat(tr.egreso3)+parseFloat(tr.egreso4)) |currency}}
                </vs-td>
                <vs-td :data="tr.valor_recibir" v-else-if="columnaeg1 && columnaeg2 && columnaeg3">
                  {{parseFloat(tr.total_ingreso)-(parseFloat(tr.egreso1)+(parseFloat(tr.total_ingreso)*(9.45/100))+parseFloat(tr.egreso2)+parseFloat(tr.egreso3)) |currency}}
                </vs-td>
                <vs-td :data="tr.valor_recibir" v-else-if="columnaeg1 && columnaeg2 ">
                  {{parseFloat(tr.total_ingreso)-(parseFloat(tr.egreso1)+(parseFloat(tr.total_ingreso)*(9.45/100))+parseFloat(tr.egreso2)) |currency}}
                </vs-td>
                <vs-td :data="tr.valor_recibir" v-else-if="columnaeg1">
                  {{parseFloat(tr.total_ingreso)-(parseFloat(tr.egreso1)+(parseFloat(tr.total_ingreso)*(9.45/100))) |currency}}
                </vs-td>-->
              </vs-tr>
              <vs-tr style="border-top: 1px solid #ddd;">
                <th><h5>Total</h5></th>
                <th v-if="columnaeg1" style="text-align:right;"><h5>{{totalcolum1eg() | currency}}</h5></th>
                <th v-if="columnaeg2" style="text-align:right;"><h5>{{totalcolum2eg() | currency}}</h5></th>
                <th v-if="columnaeg3" style="text-align:right;"><h5>{{totalcolum3eg() | currency}}</h5></th>
                <th v-if="columnaeg4" style="text-align:right;"><h5>{{totalcolum4eg() | currency}}</h5></th>
                <th v-if="columnaeg5" style="text-align:right;"><h5>{{totalcolum5eg() | currency}}</h5></th>
                <th v-if="columnaeg6" style="text-align:right;"><h5>{{totalcolum6eg() | currency}}</h5></th>
                <th style="text-align:right;"><h5>{{totaliess() | currency}}</h5></th>
                <th style="text-align:right;"><h5>{{totalegreso() | currency}}</h5></th>
                <th style="text-align:right;"><h5>{{totalrecibir() | currency}}</h5></th>
              </vs-tr>
            </template>
          </vs-table>
          <br>
           <!--<div class="vx-row center" hidden>
            <div class="vx-col sm:w-1/6 w-full mb-6 text-center"  hidden>
              <h5>Total Dias</h5>
              {{totaldiaseg}}
            </div>
            <div class="vx-col sm:w-1/6 w-full mb-6 text-center"  v-if="columnaeg1">
              
              <label class="vs-input--label">{{columnaeg1}}</label>
              <h1>{{totalcolum1eg() | currency}}</h1>
              
            </div>
            <div class="vx-col sm:w-1/5 w-full mb-6 text-center"  v-if="columnaeg2">
              <label class="vs-input--label">{{columnaeg2}}</label>
              <h1>{{totalcolum2eg() | currency}}</h1>
            </div>
            <div class="vx-col sm:w-1/6 w-full mb-6 text-center"  v-if="columnaeg3">
              <label class="vs-input--label">{{columnaeg3}}</label>
              <h1>{{totalcolum3eg() | currency}}</h1>
            </div>
            <div class="vx-col sm:w-1/6 w-full mb-6 text-center"  v-if="columnaeg4">
              <label class="vs-input--label">{{columnaeg4}}</label>
              <h1>{{totalcolum4eg() | currency}}</h1>
            </div>
            <div class="vx-col sm:w-1/6 w-full mb-6 text-center"  v-if="columnaeg5">
              <label class="vs-input--label">{{columnaeg5}}</label>
              <h1>{{totalcolum5eg() | currency}}</h1>
            </div>
            <div class="vx-col sm:w-1/6 w-full mb-6 text-center"  v-if="columnaeg6">
              <label class="vs-input--label">{{columnaeg6}}</label>
              <h1>{{totalcolum6eg() | currency}}</h1>
            </div>
            <div class="vx-col sm:w-1/5 w-full mb-6 text-center">
              <label class="vs-input--label">Total Egreso</label>
              <h1>{{totalegreso() | currency}}</h1>
            </div>
            <div class="vx-col sm:w-1/6 w-full mb-6 text-center" hidden>
              <h6>Total Valor Recibir</h6>
              {{totalrecibir() | currency}}
            </div>
          </div>-->
          <div class="vx-col w-full">
                <vs-button
                    color="success"
                    type="filled"
                    @click="editaregreso()"
                    v-if="$route.params.id"
                    >GUARDAR</vs-button
                >
                <vs-button
                    color="success"
                    type="filled"
                    @click="guardaregreso()" 
                    v-else 
                    >GUARDAR</vs-button
                >
                <vs-button
                    color="danger"
                    type="filled"
                    to="/nomina/rol-pagos"
                    >CANCELAR</vs-button
                >
            </div>
            
          <vs-table hoverFlat :data="contenidoegreso" style="font-size: 12px;" hidden>
            <template slot="thead">
              <vs-th class="table-header">Id</vs-th>
              <vs-th class="table-header">Descripcion</vs-th>
              <vs-th class="table-header">Valor</vs-th>
              <vs-th class="table-header">Tipo</vs-th>
            </template>
            <template slot-scope="{data}">
              <vs-tr v-for="(tr, index) in data" :key="index">
                <vs-td :data="tr.id_ineg" style="width:100px!important;">{{ tr.id_ineg }}</vs-td>
                <vs-td :data="tr.decripcion" style="width:100px!important;">{{ tr.decripcion }}</vs-td>
                <vs-td :data="tr.valor" style="width:150px!important;">{{ tr.valor }}</vs-td>
                <vs-td :data="tr.tipo" style="width:150px!important;">{{ tr.tipo }}</vs-td>
              </vs-tr>
            </template>
          </vs-table>
      </vs-tab>
      
    </vs-tabs>
  </vx-card>
</template>
<script>
import { FormWizard, TabContent } from "vue-form-wizard";
import "vue-form-wizard/dist/vue-form-wizard.min.css";
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import { concat } from "bytebuffer";
import Datepicker from "vuejs-datepicker";
import VueUploadMultipleImage from "vue-upload-multiple-image";

const axios = require("axios");
export default {
  data() {
    return {
      total_factura: [],
      popupActive2: false,
      modofact: 1,
      contenidopr: [],
      contenidoeg:[],
      Tabnav: 0,
      departamento: "",
      iddepart: "",
      forma_pago_array: [
        { text: "Seleccione", value: 0 },
        { text: "Enero", value: "Enero" },
        { text: "Febrero", value: "Febrero" },
        { text: "Marzo", value: "Marzo" },
        { text: "Abril", value: "Abril" },
        { text: "Mayo", value: "Mayo" },
        { text: "Junio", value: "Junio" },
        { text: "Julio", value: "Julio" },
        { text: "Agosto", value: "Agosto" },
        { text: "Septiembre", value: "Septiembre" },
        { text: "Octubre", value: "Octubre" },
        { text: "Noviembre", value: "Noviembre" },
        { text: "Diciembre", value: "Diciembre" },
      ],
      proyectos:[],
      contenidodepartamento: [],
      contenidodepartamento2: [],
      activePrompt4: false,
      contenido2: [],
      filtroNombre: "",
      nrcolumnas:[],
      columna1:"",
      columna2:"",
      columna3:"",
      columna4:"",
      columna5:"",
      columna6:"",
      valor1:0,
      valor2:0,
      valor3:0,
      valor4:0,
      valor5:0,
      valor6:0,
      valoreg1:0,
      valoreg2:0,
      valoreg3:0,
      valoreg4:0,
      valoreg5:0,
      valoreg6:0,
      abonototal1:0,
      abonototal2:0,
      abonototal3:0,
      abonototal4:0,
      abonototal5:0,
      abonototal6:0,
      contenidoingreso:[],
      columnasingreso:[],
      idcolumn1:0,
      idcolumn2:0,
      idcolumn3:0,
      idcolumn4:0,
      idcolumn5:0,
      idcolumn6:0,
    //variables Egreso
      contenidoeg:[],
      departamentoeg: "",
      iddeparteg: "",
      contenidodepartamentoeg: [],
      activePrompt5: false,
      contenido2eg: [],
      filtroNombreeg: "",
      nrcolumnaseg:[],
      columnaeg1:"",
      columnaeg2:"",
      columnaeg3:"",
      columnaeg4:"",
      columnaeg5:"",
      columnaeg6:"",
      idrecupera:null,
      sueldoemple:"",
      contenidoegreso:[],
      abonototal1eg:0,
      abonototal2eg:0,
      abonototal3eg:0,
      abonototal4eg:0,
      abonototal5eg:0,
      abonototal6eg:0,
      fechrol:"",
      configdateTimePicker: {
        locale: SpanishLocale
      },
      idcolumneg1:0,
      idcolumneg2:0,
      idcolumneg3:0,
      idcolumneg4:0,
      idcolumneg5:0,
      idcolumneg6:0,
      //activacion guardar
      activado:false,
      //errores
      error:0,
      errordepartamento:[],
      errorfecha:[]
    };
  },
  
  //importa calendario español
  components: {
    flatPickr,
    FormWizard,
    TabContent,
    Datepicker
  },
  computed: {
    
    fecha_rol_pago(){
      if(this.fechrol && !this.$route.params.id){
        this.handleEmpleados();
        
        
          
      }
    },
    valoringreso1(){
      var total=0;
      var valor=0;
      this.contenidopr.forEach(el => {
        /*this.contenidoingreso.forEach(rt =>{
          if(el.id_empleado==28){
            el.ingreso1 = rt.valor;
            this.abonototal1 = this.abonototal1 + parseFloat(this.valor1);
            console.log("ingresos"+rt.valor);
          }
        })*/
        if(!el.ingreso1){
            el.ingreso1=0;
          }
        if(!el.valor_ingreso1){
            el.valor_ingreso1=0;
          }
      });
    },
    valoringreso2(){
      var total=0;
      this.contenidopr.forEach(el => {
        /*this.contenidoingreso.forEach(rt =>{
          if(el.id_empleado==rt.id_empleado && this.valor2){
          el.ingreso2 = this.valor2;
          this.abonototal2 = this.abonototal2 + parseFloat(this.valor2);
        }else{
          el.ingreso2=0;
          //this.abonototal2 = this.abonototal2 + parseFloat(this.valor2);
        }
        })*/
        if(!el.ingreso2){
            el.ingreso2=0;
          }
        if(!el.valor_ingreso2){
            el.valor_ingreso2=0;
          }
      });
    },
    valoringreso3(){
      var total=0;
      this.contenidopr.forEach(el => {
        /*this.contenidoingreso.forEach(rt =>{
          if(el.id_empleado==rt.id_empleado && this.valor3){
          el.ingreso3 = this.valor3;
          this.abonototal3 = this.abonototal3 + parseFloat(this.valor3);
        }else{
          el.ingreso3=0;
        }
        })*/
        if(!el.ingreso3){
            el.ingreso3=0;
          }
        if(!el.valor_ingreso3){
            el.valor_ingreso3=0;
          }
      });
    },
    valoringreso4(){
      var total=0;
      this.contenidopr.forEach(el => {
        /*this.contenidoingreso.forEach(rt =>{
          if(el.id_empleado==rt.id_empleado && this.valor4){
          el.ingreso4 = this.valor4;
          this.abonototal4 = this.abonototal4 + parseFloat(this.valor4);
        }else{
          el.ingreso4=0;
        }
        })*/
        if(!el.ingreso4){
            el.ingreso4=0;
          }
        if(!el.valor_ingreso4){
            el.valor_ingreso4=0;
          }
      });
    },
    valoringreso5(){
      var total=0;
      this.contenidopr.forEach(el => {
        /*this.contenidoingreso.forEach(rt =>{
          if(el.id_empleado==rt.id_empleado && this.valor5){
          el.ingreso5 = this.valor5;
          this.abonototal5 = this.abonototal5 + parseFloat(this.valor5);
        }else{
          el.ingreso5=0;
        }
        })*/
        if(!el.ingreso5){
            el.ingreso5=0;
          }
        if(!el.valor_ingreso5){
            el.valor_ingreso5=0;
          }
      });
    },
    valoringreso6(){
      var total=0;
      this.contenidopr.forEach(el => {
        /*this.contenidoingreso.forEach(rt =>{
          if(el.id_empleado==rt.id_empleado && this.valor6){
          el.ingreso6 = this.valor6;
          this.abonototal6 = this.abonototal6 + parseFloat(this.valor6);
        }else{
          el.ingreso6=0;
        }
        })*/
        if(!el.ingreso6){
            el.ingreso6=0;
          }
        if(!el.valor_ingreso6){
            el.valor_ingreso6=0;
          }
      });
    },
    valordecimo_cuarto(){
      var total=0;
      this.contenidopr.forEach(el => {
        if(el.decimo_cuarto!=0){
          el.decimo_cuarto=(el.decimo_cuarto_base/360)*(parseFloat(el.cantidad));
        }
      });
    },
    valordecimo_tercero(){
      var total=0;
      this.contenidopr.forEach(el => {
        if(el.decimo_tercero!=0){
            el.decimo_tercero=((parseFloat(el.sueldo)/30*(parseFloat(el.cantidad)))+parseFloat(el.valor_ingreso1)+parseFloat(el.valor_ingreso2)+parseFloat(el.valor_ingreso3)+parseFloat(el.valor_ingreso4)+parseFloat(el.valor_ingreso5)+parseFloat(el.valor_ingreso6))/12;
          }
      });
    },
    valorfondo_reserva(){
      var total=0;
      this.contenidopr.forEach(el => {
        if(el.fondo_reserva!=0){
            el.fondo_reserva=((parseFloat(el.sueldo)/30*(parseFloat(el.cantidad)))+parseFloat(el.valor_ingreso1)+parseFloat(el.valor_ingreso2)+parseFloat(el.valor_ingreso3)+parseFloat(el.valor_ingreso4)+parseFloat(el.valor_ingreso5)+parseFloat(el.valor_ingreso6))/12;
          }
      });
    },
    valoregreso1(){
      var total=0;
      this.contenidopr.forEach(el => {
        /*this.contenidoegreso.forEach(rt =>{
          if(el.id_empleado==rt.id_empleado && this.valoreg1){
          el.egreso1 = this.valoreg1;
          this.abonototal1eg = this.abonototal1eg + parseFloat(this.valoreg1);
        }else{
          el.egreso1=0;
          
        }
        })*/
        if(!el.egreso1){
             el.egreso1=0;
          }
      });
    },
    valoregreso2(){
      var total=0;
      this.contenidopr.forEach(el => {
        /*this.contenidoegreso.forEach(rt =>{
          if(el.id_empleado==rt.id_empleado && this.valoreg2){
          el.egreso2 = this.valoreg2;
          this.abonototal2eg = this.abonototal2eg + parseFloat(this.valoreg2);
        }else{
          el.egreso2=0;
        }
        })*/
        if(!el.egreso2){
             el.egreso2=0;
          }
      });
    },
    valoregreso3(){
      var total=0;
      this.contenidopr.forEach(el => {
        /*this.contenidoegreso.forEach(rt =>{
          if(el.id_empleado==rt.id_empleado && this.valoreg3){
          el.egreso3 = this.valoreg3;
          this.abonototal3eg = this.abonototal3eg + parseFloat(this.valoreg3);
        }else{
          el.egreso3=0;
        }
        })*/
        if(!el.egreso3){
             el.egreso3=0;
          }
      });
    },
    valoregreso4(){
      var total=0;
      this.contenidopr.forEach(el => {
        /*this.contenidoegreso.forEach(rt =>{
          if(el.id_empleado==rt.id_empleado && this.valoreg4){
          el.egreso4 = this.valoreg4;
          this.abonototal4eg = this.abonototal4eg + parseFloat(this.valoreg4);
        }else{
          el.egreso4=0;
        }
        })*/
        if(!el.egreso4){
             el.egreso4=0;
          }
      });
    },
    valoregreso5(){
      var total=0;
      this.contenidopr.forEach(el => {
        /*this.contenidoegreso.forEach(rt =>{
          if(el.id_empleado==rt.id_empleado && this.valoreg5){
          el.egreso5 = this.valoreg5;
          this.abonototal5eg = this.abonototal5eg + parseFloat(this.valoreg5);
        }else{
          el.egreso5=0;
        }
        })*/
        if(!el.egreso5){
             el.egreso5=0;
          }
      });
    },
    valoregreso6(){
      var total=0;
      this.contenidopr.forEach(el => {
        /*this.contenidoegreso.forEach(rt =>{
          if(el.id_empleado==rt.id_empleado && this.valoreg6){
          el.egreso6 = this.valoreg6;
          this.abonototal6eg = this.abonototal6eg + parseFloat(this.valoreg6);
        }else{
          el.egreso6=0;
        }
        })*/
        if(!el.egreso6){
             el.egreso6=0;
          }
      });
    },
    totaldias() {
      var total = 0;
      this.contenidopr.forEach(el => {
        total += parseFloat(el.cantidad);
      });
      this.contenidopr.map(data => {
        return {
          
        }
      })
      if(isNaN(total)){
        total=0;
      }
      return total;
    },
    
    totaldiaseg() {
      var total = 0;
      this.contenidoeg.forEach(el => {
        total += parseFloat(el.cantidad);
      });
      if(isNaN(total)){
        total=0;
      }
      return total;
    },

    
    
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
        res = this.$store.state.Roles[30].crear;
      }
      return res;
    },
    editarrol() {
      var res = 0;
      if (this.usuario.id_rol == 1) {
        res = 1;
      } else {
        res = this.$store.state.Roles[30].editar;
      }
      return res;
    },
    eliminarrol() {
      var res = 0;
      if (this.usuario.id_rol == 1) {
        res = 1;
      } else {
        res = this.$store.state.Roles[30].eliminar;
      }
      return res;
    }
  },
  methods: {
    sueldototal(){
      var total = 0;
      this.contenidopr.forEach(el => {

          total += (parseFloat(el.sueldo)/30*(parseFloat(el.cantidad)));

      });
      return total;
    },
    totalcolum1() {
      var total = 0;
      this.contenidopr.forEach(el => {

          total += parseFloat(el.valor_ingreso1);

      });
      return total;
    },
    totalvalorcolum1() {
      var total = 0;
      this.contenidopr.forEach(el => {

          total += parseFloat(el.ingreso1);

      });
      return total;
    },
    totalcolum2() {
      var total = 0;
      this.contenidopr.forEach(el => {

          total += parseFloat(el.valor_ingreso2);

      });
      return total;
    },
    totalvalorcolum2() {
      var total = 0;
      this.contenidopr.forEach(el => {

          total += parseFloat(el.ingreso2);

      });
      return total;
    },
    totalcolum3() {
      var total = 0;
      this.contenidopr.forEach(el => {

          total += parseFloat(el.valor_ingreso3);

      });
      return total;
    },
    totalvalorcolum3() {
      var total = 0;
      this.contenidopr.forEach(el => {

          total += parseFloat(el.ingreso3);

      });
      return total;
    },
    totalcolum4() {
      var total = 0;
      this.contenidopr.forEach(el => {
          total += parseFloat(el.valor_ingreso4);

      });
      return total;
    },
    totalvalorcolum4() {
      var total = 0;
      this.contenidopr.forEach(el => {
          total += parseFloat(el.ingreso4);

      });
      return total;
    },
    totalcolum5() {
      var total = 0;
      this.contenidopr.forEach(el => {
          total += parseFloat(el.valor_ingreso5);

      });
      return total;
    },
    totalvalorcolum5() {
      var total = 0;
      this.contenidopr.forEach(el => {
          total += parseFloat(el.ingreso5);

      });
      return total;
    },
    totalcolum6() {
      var total = 0;
      this.contenidopr.forEach(el => {
          total += parseFloat(el.valor_ingreso6);
      });
      return total;
    },
    totalvalorcolum6() {
      var total = 0;
      this.contenidopr.forEach(el => {
          total += parseFloat(el.ingreso6);
      });
      return total;
    },

    totaldecimo_tercero(){
      var total = 0;
      this.contenidopr.forEach(el => {
       total += parseFloat(el.decimo_tercero);
      });
      return total;
    },
    totaldecimo_cuarto(){
      var total = 0;
      this.contenidopr.forEach(el => {
       total += parseFloat(el.decimo_cuarto);
      });
      return total;
    },
    totalfondo_reserva(){
      var total = 0;
      this.contenidopr.forEach(el => {
       total += parseFloat(el.fondo_reserva);
      });
      return total;
    },
    totalingreso() {
      var total = 0;
      var sueldo=0
      this.contenidopr.forEach(el => {
       total += parseFloat(el.sueldo);
      });
      sueldo=this.sueldototal()+this.totalvalorcolum1()+this.totalvalorcolum2()+this.totalvalorcolum3()+this.totalvalorcolum4()+this.totalvalorcolum5()+this.totalvalorcolum6()+this.totalfondo_reserva()+this.totaldecimo_cuarto()+this.totaldecimo_tercero();
       //total = sueldo+this.totalsueldo;
      return sueldo;
    },
    totalcolum1eg() {
      var total = 0;
      this.contenidopr.forEach(el => {
        //console.log(el.egreso1)
          total += parseFloat(el.egreso1);
      });
      return total;
    },
    totalcolum2eg() {
      var total = 0;
      this.contenidopr.forEach(el => {
          total += parseFloat(el.egreso2);
      });
      return total;
    },
    totalcolum3eg() {
      var total = 0;
      this.contenidopr.forEach(el => {
          total += parseFloat(el.egreso3);
      });
      return total;
    },
    totalcolum4eg() {
      var total = 0;
      this.contenidopr.forEach(el => {
          total += parseFloat(el.egreso4);
      });
      return total;
    },
    totalcolum5eg() {
      var total = 0;
      this.contenidopr.forEach(el => {

          total += parseFloat(el.egreso5);
      });
      return total;
    },
    totalcolum6eg() {
      var total = 0;
      this.contenidopr.forEach(el => {
          total += parseFloat(el.egreso6);
      });
      return total;
    },
    totaliess(){
      var total = 0;
      this.contenidopr.forEach(el => {
        total += ((parseFloat(el.sueldo)/30*(parseFloat(el.cantidad)))+parseFloat(el.valor_ingreso1)+parseFloat(el.valor_ingreso2)+parseFloat(el.valor_ingreso3)+parseFloat(el.valor_ingreso4)+parseFloat(el.valor_ingreso5)+parseFloat(el.valor_ingreso6))*(9.45/100);
      });
      return total;
    },
    totalegreso() {
      var total = 0;
      var iess =0;
      var totaliess=0;
      this.contenidopr.forEach(el => {
        iess += parseFloat(el.egreso1)+parseFloat(el.egreso2)+parseFloat(el.egreso3)+parseFloat(el.egreso4)+parseFloat(el.egreso5)+parseFloat(el.egreso6);
      });
      //totaliess=(iess+this.abonototal1+this.abonototal2+this.abonototal3+this.abonototal4+this.abonototal5+this.abonototal6)*9.45/100;
      total=iess+this.totaliess()//totaliess+this.totalcolum1eg()+this.totalcolum2eg()+this.totalcolum3eg()+this.totalcolum4eg()+this.totalcolum5eg()+this.totalcolum6eg();
      return total;
    },
    totalrecibir(){
      
      var totalrecibido=0;
      totalrecibido=this.totalingreso()-this.totalegreso()
      return totalrecibido;
     
    },
    abrirdepart() {
      this.activePrompt4 = true;
      this.tipomodal = 2;
      this.listarDepartamento(1, this.buscarp, this.cantidadpp);
    },
    /*
    listarem(page, buscar, cantidadp) {
      let me = this;
      var url =
        "/api/nomina?page=" +
        page +
        "&buscar=" +
        buscar +
        "&cantidadp=" +
        cantidadp;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.contenido2 = respuesta.recupera.data;
          me.pagination = respuesta.pagination;
          if (cantidadp > me.pagination.total) {
            cantidadp = me.pagination.total;
          }
        })
        .catch(function(error) {
          console.log(error);
        });
    },*/
    handleSelected4(tr) {
      
        this.departamento=`${tr.dep_nombre}`;
        this.iddepart = `${tr.id_departamento}`;
        this.activePrompt4 = false;
        
    },
    
   
    handleEmpleados(){
      this.$vs.notify({
                title: "Cargando este Registro",
                text: "Este proceso puede demorar por favor espere..",
                color: "warning"
            });
      this.traerIngresos(this.iddepart)
        .then(value => {
          console.log('traer ingresos',value) // Resolucion de traerIngresos
          return this.traerEgresos(this.iddepart)
        })
        .then(value => {
          console.log('TRAER EGRESOS',value); // resolucion de this.traerEgresos
          
          return this.traerEmpleado(this.iddepart,this.fechrol)
        })
        .then(value => {
          //console.log('traer empleado',data.recupera);
          this.contenidopr=value;// resolucion de this.traerEmpleado
          this.activado=true;
          this.Tabnav--;
          this.$vs.notify({
                title: "Datos Cargados",
                text: "Datos cargados con exito",
                color: "success"
            });
        })
        .catch(error => {
          console.error("[ERROR::]",error)
          this.$vs.notify({
                  text: error,
                  color: "danger"
              });
        })

      // const promesa= new Promise((resolve,reject)=>{
      //  let value=this.traerIngresos(this.iddepart);
      //  //console.log("ingresosss:",value);
      //  if(value){
      //    return resolve(value);
      //  } 
      //  else{
      //    return reject("Error en la conexion");
      //  } 
      // });
      // const promesa2= new Promise((resolve,reject)=>{
      //  let values=this.traerEgresos(this.iddepart);
      //  //console.log("egresosss:",values);
      //  if(values){
      //    return resolve(values);
      //  } 
      //  else{
      //    return reject("Error en la conexion eg");
      //  } 
      // });
      // Promise.all([promesa,promesa2]).then(resp=>{
      //     this.traerEmpleado(this.iddepart);
      //     this.$vs.notify({
      //           title: "Cargando este Registro",
      //           text: "Este proceso puede demorar por favor espere..",
      //           color: "warning"
      //       });
      // }).catch(err=>{
      //         this.$vs.notify({
      //             text: err,
      //             color: "danger"
      //         });
      //     });*/
      /*promesa
      .then((value)=>{
        console.log("egres:",value);
          return this.traerEgresos(this.iddepart);
        })
      .then((ingresos)=>{
        
          console.log("valor ingres:",ingresos);
          return this.traerValoresIngreso(this.iddepart);
        })
      .then((egresos)=>{
          console.log("valor egres:",egresos);
          return this.traervaloresEgreso(this.iddepart);
        })
      .then((empleado)=>{//setTimeout(() => {
          console.log("empleados");
          return  this.traerEmpleado();
        //}, 7900)
        }).catch(err=>{console.log(err)});*/
    },
    traerSueldo(id){
      var url2 = "/api/cargorol/"+id;
        axios
        .get(url2)
        .then(res => {
          
            this.sueldoemple=res.data[0].sueldo;
          
          
        }).catch();
    },
    /*listarSueldo(){
      if(this.$route.params.id){
        this.idrecupera = this.$route.params.id;
      }else{
        this.idrecupera =0;
      }
      var url2 = "/api/cargorol/"+this.idrecupera;
        axios
        .get(url2)
        .then(res => {
          this.sueldoemple=res.data[0].sueldo;
        }).catch();
    },*/
    
    //egresos
  
    traerIngresos(id){
      return new Promise((resolve,reject)=>{
        var url2 = "/api/ingresoRoles/"+id;
        axios
        .get(url2,{
                    params: {
                        fecha: this.fechrol
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
            this.$vs.notify({
              text: "No existe ingresos para este mes",
              color: "danger"
            });
          }else{
            this.columnasingreso=res.data;
            this.nrcolumnas=res.data;
            console.log("columnas ingreso:"+this.nrcolumnas.length);
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
              console.log("1dato:"+this.idcolumn1);
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
              console.log("2dato:"+this.idcolumn2);
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
              console.log("3dato:"+this.idcolumn3);
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
              console.log("4dato:"+this.idcolumn4);
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
              console.log("5dato:"+this.idcolumn5);
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
              console.log("6dato:"+this.idcolumn6);
            }
          }
          resolve(this.idcolumn1,this.idcolumn2,this.idcolumn3,this.idcolumn4,this.idcolumn5,this.idcolumn6)

        })
        .catch(function(error) {
          reject(error)
        });
      }) 
    },
    traerValoresIngreso($id){
      
      if(!this.$route.params.id){
      var url2 = "/api/ingresovalorRoles/"+$id;
        axios
        .get(url2,{
                    params: {
                        fecha: this.fechrol
                    }
                })
        .then(res => {
          
          if(res.data=="vacio"){
            this.valor1=0;
            this.valor2=0;
            this.valor3=0;
            this.valor4=0;
            this.valor5=0;
            this.valor6=0;
          }else{
            this.contenidoingreso=res.data;
            this.nrcolumnas=res.data;

            if(this.nrcolumnas.length==1){
              
              this.valor1=res.data[0].valor;
              this.valor2="";
              this.valor3="";
              this.valor4="";
              this.valor5="";
              this.valor6="";
            }
            if(this.nrcolumnas.length==2){
              
              this.valor1=res.data[0].valor;
              
              this.valor2=res.data[1].valor;
              this.valor3="";
              this.valor4="";
              this.valor5="";
              this.valor6="";
            }
            if(this.nrcolumnas.length==3){
              
              this.valor1=res.data[0].valor;
              
              this.valor2=res.data[1].valor;
              
              this.valor3=res.data[2].valor;
              this.valor4="";
              this.valor5="";
              this.valor6="";
            }
            if(this.nrcolumnas.length==4){
              
              this.valor1=res.data[0].valor;
              
              this.valor2=res.data[1].valor;
              
              this.valor3=res.data[2].valor;
              
              this.valor4=res.data[3].valor;
              this.valor5="";
              this.valor6="";
            }
            if(this.nrcolumnas.length==5){
              
              this.valor1=res.data[0].valor;
              
              this.valor2=res.data[1].valor;
              
              this.valor3=res.data[2].valor;
              
              this.valor4=res.data[3].valor;
              
              this.valor5=res.data[4].valor;
              this.valor6="";
            }
            if(this.nrcolumnas.length==6){
              
              this.valor1=res.data[0].valor;
              
              this.valor2=res.data[1].valor;
              
              this.valor3=res.data[2].valor;
              
              this.valor4=res.data[3].valor;
              
              this.valor5=res.data[4].valor;
              
              this.valor6=res.data[5].valor;
            }
          }
        })
        .catch(function(error) {
          //console.log(error);
        });
    }
    },
    ////////////////////////////Egresos
    traerEgresos(id){
      return new Promise((resolve,reject)=>{
        var url2 = "/api/egresoRoles/"+id;
         axios
         .get(url2,{
                     params: {
                         fecha: this.fechrol
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
             this.$vs.notify({
               text: "No existe egresos para este mes",
               color: "danger"
             });
           }else{
             this.nrcolumnaseg=res.data;
             this.contenidoegreso=res.data;
             console.log("columnas egresos:"+this.nrcolumnaseg.length);
             if(this.nrcolumnaseg.length==1){
               this.columnaeg1=res.data[0].decripcion;
               this.idcolumneg1=res.data[0].id_ineg;
               this.columnaeg2="";
              
               this.columnaeg3="";
              
               this.columnaeg4="";
              
               this.columnaeg5="";
              
               this.columnaeg6="";
              console.log("1datoegreso:"+this.idcolumneg1);
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
               console.log("2datoegreso:"+this.idcolumneg2);
              
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
              console.log("3datoegreso:"+this.idcolumneg3);
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
               console.log("4datoegreso:"+this.idcolumneg4);
              
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
               console.log("5datoegreso:"+this.idcolumneg5);
              
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
               console.log("6datoegreso:"+this.idcolumneg6);
             }
             
           }
           resolve(this.idcolumneg1,this.idcolumneg2,this.idcolumneg3,this.idcolumneg4,this.idcolumneg5,this.idcolumneg6);
         })
         .catch(function(error) {
           reject(error)
         });
      })
    },
    traervaloresEgreso(id){
      if(!this.$route.params.id){
      var url2 = "/api/egresovalorRoles/"+id;
        axios
        .get(url2,{
                    params: {
                        fecha: this.fechrol
                    }
                })
        .then(res => {
          
          if(res.data=="vacio"){
            this.valoreg1=0;
            this.valoreg2=0;
            this.valoreg3=0;
            this.valoreg4=0;
            this.valoreg5=0;
            this.valoreg6=0;
          }else{
            this.nrcolumnaseg=res.data;
            this.contenidoegreso=res.data;
            if(this.nrcolumnaseg.length==1){
              this.valoreg1=res.data[0].valor;
              this.valoreg2="";
              this.valoreg3="";
              this.valoreg4="";
              this.valoreg5="";
              this.valoreg6="";
            }
            if(this.nrcolumnaseg.length==2){
              this.valoreg1=res.data[0].valor;
              this.valoreg2=res.data[1].valor;
              this.valoreg3="";
              this.valoreg4="";
              this.valoreg5="";
              this.valoreg6="";
            }
            if(this.nrcolumnaseg.length==3){
              this.valoreg1=res.data[0].valor;
              this.valoreg2=res.data[1].valor;
              this.valoreg3=res.data[2].valor;
              this.valoreg4="";
              this.valoreg5="";
              this.valoreg6="";
            }
            if(this.nrcolumnaseg.length==4){
              this.valoreg1=res.data[0].valor;
              this.valoreg2=res.data[1].valor;
              this.valoreg3=res.data[2].valor;
              this.valoreg4=res.data[3].valor;
              this.valoreg5="";
              this.valoreg6="";
            }
            if(this.nrcolumnaseg.length==5){
              this.valoreg1=res.data[0].valor;
              this.valoreg2=res.data[1].valor;
              this.valoreg3=res.data[2].valor;
              this.valoreg4=res.data[3].valor;
              this.valoreg5=res.data[4].valor;
              this.valoreg6="";
            }
            if(this.nrcolumnaseg.length==6){
              this.valoreg1=res.data[0].valor;
              this.valoreg2=res.data[1].valor;
              this.valoreg3=res.data[2].valor;
              this.valoreg4=res.data[3].valor;
              this.valoreg5=res.data[4].valor;
              this.valoreg6=res.data[5].valor;
            }
            
          }
        })
        .catch(function(error) {
          //console.log(error);
        });
    }
    },
    guardaregreso(){
      if(this.validar()){
        return;
      }
      this.$vs.notify({
              title: "Guardando este Registro",
              text: "Este proceso puede demorar por favor espere..",
              color: "warning"
          });
      axios.post("/api/guardaregrerol",{
        id_departamento:this.iddepart,
        nrocolum:this.nrcolumnaseg.length,
        productos:this.contenidopr,
        fechrol:this.fechrol,
        id_empresa:this.usuario.id_empresa,
        ucrea:this.usuario.id
      }).then(res =>{
        if(res.data=="existe"){
          this.$vs.notify({
              title: "Ya existe este Rol de Pago",
              text: "Este Rol de Pago ya existe",
              color: "danger"
          });
          this.Tabnav--;
        }else{
          this.$vs.notify({
              title: "Registro Guardado",
              text: "Registro Guardado exitosamente",
              color: "success"
        });
        this.$router.push(`/nomina/rol-pagos`);
        }
      }).catch(err=>{
        this.$vs.notify({
              title: "Error al Guardar",
              text: "Revise bien sus campos antes de guardar",
              color: "danger"
        });
      });
    },
    editaregreso(){
      if(this.validar()){
        return;
      }
      this.$vs.notify({
              title: "Editando este Registro",
              text: "Este proceso puede demorar por favor espere..",
              color: "warning"
          });
      axios.put("/api/editarrolpago",{
        id_departamento:this.iddepart,
        nrocolum:this.nrcolumnaseg.length,
        productos:this.contenidopr,
        fechrol:this.fechrol,
        umodifica:this.usuario.id
      }).then(res =>{
        this.$vs.notify({
              title: "Registro Guardado",
              text: "Registro Guardado exitosamente",
              color: "success"
        });
         this.$router.push(`/nomina/rol-pagos`);
      }).catch(err=>{
        this.$vs.notify({
              title: "Error al Actualizar",
              text: "Revise bien sus campos antes de guardar",
              color: "danger"
        });
      });
    },
    listarDepartamento(page1, buscar1) {
      var url = "/api/departamento/listar/"+this.usuario.id_empresa;
      axios;
      axios
        .get(url)
        .then(res => {
          this.contenidodepartamento = res.data.recupera;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    listarDepartamentoEg() {
      var url = "/api/departamento/listar/"+this.usuario.id_empresa;
      axios;
      axios
        .get(url)
        .then(res => {
          this.contenidodepartamentoeg = res.data.recupera;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    listarrolIngreso(){
       if(this.$route.params.id){
        this.idrecupera = this.$route.params.id;
  
        var url = "/api/abriringresoRolesEditar/" + this.idrecupera;
        axios
          .get(url)
          .then(res => {
            this.fechrol=res.data[0].fechrol;
            this.contenidopr = res.data;
            this.listarIngresosCampos(res.data[0].id_departamento,res.data[0].fechrol);
            this.iddepart=res.data[0].id_departamento;
            this.departamento=res.data[0].departamento;
            this.listarEgresosCampos(res.data[0].id_departamento,res.data[0].fechrol);
          })
          .catch(err => {
    
          });
    }
    },
    listarProyecto(){
      var url="/api/rolpago/traerproyecto/"+this.usuario.id_empresa;
      axios.get(url).then(res => {
        
        this.proyectos=res.data;
      });
    },
    listarIngresosCampos(id,fech){
      if(this.$route.params.id){
        this.idrecupera = this.iddepart;
      
      var url2 = "/api/ingresoRoles/ver/"+id;
        axios
        .get(url2,{
                    params: {
                        fecha: fech
                    }
        })
        .then(res => {
          
          if(res.data=="vacio"){
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
        })
        .catch(function(error) {
          //console.log(error);
        });
      }
    },
    validar(){
      this.error=0;
      this.errordepartamento=[];
      this.errorfecha=[];
      if(!this.iddepart){
        this.errordepartamento.push("Campo Obligatorio");
        this.error=1;
        this.Tabnav--;
      }
      if(!this.fechrol){
        this.errorfecha.push("Campo Obligatorio");
        this.error=1;
        this.Tabnav--;
      }
      for (var i = 0; i < this.contenidopr.length; i++) {
                this.contenidopr[i].errordias = [];
                this.contenidopr[i].errorproyecto = [];
                if (this.contenidopr[i].cantidad>30 || this.contenidopr[i].cantidad<1) {
                    this.contenidopr[i].errordias.push("No puede ser mayor de 30 ni menor a 1");
                    
                    this.error = 1;
                    this.Tabnav--;
                    console.log("Error dias trabajados");
                }
                if (!this.contenidopr[i].id_proyecto) {
                    this.contenidopr[i].errorproyecto.push("Campo Obligatorio");
                    this.error = 1;
                    this.Tabnav--;
                }
            } 
      return this.error;
    },
    async traerEmpleado(id,fecha_asig){      //console.log("ejecutado empleados");
      var url = "/api/empleadoRoles?id_empleado="+id+
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
      "&empresa="+this.usuario.id_empresa+
      "&fecha_asignar="+fecha_asig
      ;

      let {data} = await axios.get(url)
      return data
        //       .then(res => {
        //   this.contenidopr=res.data;
        // })
        //   this.contenidopr=res.data;
        // })
        // .catch(function(error) {
        //   console.log(error);
        // });
    },
    listarEgresosCampos(id,fech){
      if(this.$route.params.id){
        this.idrecupera = this.$route.params.id;
      }else{}
    },
    solonumeros: function($event) {
            //  return /^-?(?:\d+(?:,\d*)?)$/.test($event);
            var num = /^\d*\.?\d*$/;
            if (
                $event.charCode ===0 ||
                num.test(String.fromCharCode($event.charCode))
            ) {
                return true;
            } else {
                $event.preventDefault();
            }
        },
    listarEgresosCampos(id,fech){
      if(this.$route.params.id){
        this.idrecupera = this.$route.params.id;
      }else{
        this.idrecupera =0;
      }
      var url2 = "/api/egresoRoles/ver/"+id;
        axios
        .get(url2,{params:{
          fecha:fech
        }})
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
            this.idcolumneg1=res.data[0].id_ineg;
            if(this.nrcolumnaseg.length==1){
              this.columnaeg1=res.data[0].decripcion;
              
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
        })
        .catch(function(error) {
          //console.log(error);
        });
    },
    listarDepartamentoIngreso() {
      if(this.$route.params.id){
        this.idrecupera = this.iddepart;
        var url = "/api/abrirdepartamento/"+this.idrecupera;
      axios;
      axios
        .get(url)
        .then(res => {
           //if(this.idrecupera !=0){
             this.iddepart = res.data[0].id_departamento;
             this.departamento = res.data[0].dep_nombre;
           /*}else{
             this.iddepart = "";
             this.departamento = "";
           }*/
          
        })
        .catch(function(error) {
          console.log(error);
        });
      }else{
        this.idrecupera =0;
      }
      
    },
    /*
    listarDepartamento2() {
      let me = this;
      var url = "/api/departamento/rol";
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.contenidodepartamento2 = respuesta.recupera.data;
        })
        .catch(function(error) {
          console.log(error);
        });
    }*/
  },
  mounted() {
    this.listarDepartamento(1, this.buscar1);
    this.listarrolIngreso();
    this.listarProyecto();
    /*setTimeout(() => {
            this.listarDepartamentoIngreso();
        }, 5200);*/
    
    //this.listarSueldo();
    //this.listarEgresosCampos();
    //this.traerEmpleado();
    //this.listarDepartamento2();
    //  this.listarem();
  }
};
</script>
<style scoped>
.btn-upload-all {
  display: none;
}
.btn-upload-file {
  display: none;
}
.image-container[data-v-10e59822] {
  width: 100%;
  height: 270px;
  border: 1px dashed #d6d6d6;
  border-radius: 4px;
  background-color: #fff;
}

.centered[data-v-10e59822] {
  width: 100%;
  height: 100%;
}

.show-img[data-v-10e59822] {
  max-width: 100%;
  max-height: 185px;
  width: 100%;
}

.image-overlay[data-v-10e59822] {
  height: 170px;
}

.image-overlay-details[data-v-10e59822] {
  top: 62%;
}

.image-list-container .image-list-item .show-img[data-v-10e59822] {
  max-width: 30px;
  max-height: 33px;
  height: 30px;
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
  height: 225px;
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
  border: 5px solid rgba(0, 0, 0, 0.3);
}

.imagenpre:hover {
  -moz-transform: scale(1.03);
  -webkit-transform: scale(1.03);
  -o-transform: scale(1.03);
  -ms-transform: scale(1.03);
  transform: scale(1.03);
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
.vs-con-table .vs-con-tbody .vs-table--tbody-table {
    font-size: 1rem;
    width: 145%!important;
}
.vs-con-table .vs-con-tbody .vs-table--tbody-table .tr-values .vs-table--td {
    padding: 10px 7px;
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
.btnrl {
  margin-top: 5px;
  margin-bottom: 20px;
}
</style>
