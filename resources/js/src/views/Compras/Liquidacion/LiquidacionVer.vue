<template>
  <div class="vx-row">
    <!-- MULTIPLE COLUMNS-->
    <div class="vx-col w-full mb-base">
      <vx-card>
      {{calculo_nuevo_costo}}
         <vs-divider position="left" >
        <h3>Productos</h3>
        <h6>Numero Liquidacion:{{codigo_import}}</h6>
        <div class="vx-col sm:w-1/5 w-full mb-2">
          <h6>Fecha Liquidacion:</h6>
          <flat-pickr
              :config="configdateTimePicker"
              class="w-full"
              placeholder="Seleccione una"
              v-model="fech_liquidacion"
              v-if="estado!='Inicial'"
              disabled
            />
            <flat-pickr
              :config="configdateTimePicker"
              class="w-full"
              placeholder="Seleccione una"
              v-model="fech_liquidacion"
              v-else
            />
            <div v-show="error" v-if="!fech_liquidacion">
            <div v-for="err in errorfech_liquidacion" :key="err" v-text="err" class="text-danger"></div>
          </div>
        </div>
        <!--<div class="bg-grid-color-secondary h-12">
          <h6>Bodega:</h6>
            <vs-select
              class="selectExample"
          
              vs-multiple
              autocomplete
              v-model="id_bodega"
              v-if="estado!='Inicial'"
              disabled
            >
              <vs-select-item
                v-for="(data,index) in bodegas"
                :key="index"
                :value="data.id_bodega"
                :text="data.nombre"
              />
            </vs-select>
            <vs-select
              class="selectExample"
          
              vs-multiple
              autocomplete
              v-model="id_bodega"
              v-else
            >
              <vs-select-item
                v-for="(data,index) in bodegas"
                :key="index"
                :value="data.id_bodega"
                :text="data.nombre"
              />
            </vs-select>
            <div v-show="error" v-if="!id_bodega">
              <span class="text-danger" v-for="err in errorbodega" :key="err" v-text="err"></span>
            </div>
      </div>-->
      </vs-divider>      
      <!--<div>
        <h6>Proyecto:</h6>
        <vs-select
              class="selectExample"
          
              vs-multiple
              autocomplete
              v-model="id_proyecto"
              v-if="estado!='Inicial'"
              disabled
            >
              <vs-select-item
                v-for="(data,index) in proyectos"
                :key="index"
                :value="data.id_proyecto"
                :text="data.descripcion"
              />
            </vs-select>
            <vs-select
              class="selectExample"
          
              vs-multiple
              autocomplete
              v-model="id_proyecto"
              v-else
            >
              <vs-select-item
                v-for="(data,index) in proyectos"
                :key="index"
                :value="data.id_proyecto"
                :text="data.descripcion"
              />
            </vs-select>
      </div>-->
      {{sumar_iguales}}
      <div class="p-base">
        <vs-table  hoverFlat :data="contenidopr" style="font-size: 12px;width: 100%;" >
            <template slot="thead">
              <vs-th>CÓDIGO</vs-th>
              
              <vs-th class="table-header" >NOMBRE</vs-th>
              <vs-th class="table-header" >PROYECTO</vs-th>
              <vs-th class="table-header" >CANTIDAD</vs-th>
              <vs-th class="table-header" >COSTO UNITARIO</vs-th>
              <vs-th class="table-header" >BODEGA</vs-th>
              <vs-th class="table-header" >COSTO TOTAL</vs-th>
              <vs-th class="table-header" v-if="descripcion_factura">{{descripcion_factura | upper}}</vs-th>
              <vs-th class="table-header" v-if="descripcion_factura2" >{{descripcion_factura2 | upper}}</vs-th>
              <vs-th class="table-header" v-if="descripcion_factura3">{{descripcion_factura3 | upper}}</vs-th>
              <vs-th class="table-header" v-if="descripcion_factura4">{{descripcion_factura4 | upper}}</vs-th>
              <vs-th class="table-header" v-if="descripcion_factura5" >{{descripcion_factura5 | upper}}</vs-th>
              <vs-th class="table-header" v-if="descripcion_factura6">{{descripcion_factura6 | upper}}</vs-th>
              <vs-th class="table-header" v-if="descripcion_factura7">{{descripcion_factura7 | upper}}</vs-th>
              <vs-th class="table-header" v-if="descripcion_factura8">{{descripcion_factura8 | upper}}</vs-th>
              <vs-th class="table-header" v-if="descripcion_factura9">{{descripcion_factura9 | upper}}</vs-th>
              <vs-th class="table-header" v-if="descripcion_factura10">{{descripcion_factura10 | upper}}</vs-th>
              <vs-th class="table-header" v-if="descripcion_factura11" >{{descripcion_factura11 | upper}}</vs-th>
              <vs-th class="table-header" v-if="descripcion_factura12">{{descripcion_factura12 | upper}}</vs-th>
              <vs-th class="table-header" v-if="descripcion_factura13">{{descripcion_factura13 | upper}}</vs-th>
              <vs-th class="table-header" v-if="descripcion_factura14">{{descripcion_factura14 | upper}}</vs-th>
              <vs-th class="table-header" v-if="descripcion_factura15">{{descripcion_factura15 | upper}}</vs-th>
              <vs-th class="table-header" v-if="descripcion_factura16" >{{descripcion_factura16 | upper}}</vs-th>
              <vs-th class="table-header" v-if="descripcion_factura17">{{descripcion_factura17 | upper}}</vs-th>
              <vs-th class="table-header" v-if="descripcion_factura18">{{descripcion_factura18 | upper}}</vs-th>
              <vs-th class="table-header" v-if="descripcion_factura19">{{descripcion_factura19 | upper}}</vs-th>
              <vs-th class="table-header" v-if="descripcion_factura20">{{descripcion_factura20 | upper}}</vs-th>
              <vs-th class="table-header" >NUEVO COSTO UNITARIO</vs-th>
              <vs-th class="table-header" >NUEVO COSTO TOTAL</vs-th>
              
            </template>
            <template slot-scope="{data}">
              <vs-tr v-for="(tr, index) in data" :key="index">

                <!--<vs-td :data="tr.id_prodimp>{{ tr.id_prodimp }}</vs-td>-->
                <vs-td :data="tr.codigo">{{ tr.codigo }}</vs-td>
                
                <vs-td :data="tr.nombre" style="width:150px!important;">{{ tr.nombre }}</vs-td>
                <vs-td :data="tr.descripcion" style="width:130px!important;">{{ tr.descripcion }}</vs-td>
                <vs-td :data="tr.cantidad" style="width:80px!important;">
                  {{tr.cantidad}}
                </vs-td>
                <vs-td :data="tr.precio" style="width:130px!important;">
                  {{tr.precio}}
                </vs-td>
                <vs-td :data="tr.nombrebodega" style="width:130px!important;">{{ tr.nombrebodega }}</vs-td>
                <vs-td :data="tr.costototal" style="width:120px!important;" v-if="estado!='Liquidado'">{{ (tr.cantidad*tr.precio)| currency }}</vs-td>
                <vs-td :data="tr.costototal" style="width:120px!important;" v-else>{{ (tr.total)| currency }}</vs-td>
                <vs-td :data="tr.nuevocostoun" style="width:100px!important;"  v-if="descripcion_factura && tipo_calculo=='1'">
                    {{nuevocostounit | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun" style="width:100px!important;"  v-else-if="descripcion_factura && tipo_calculo=='0'">
                    {{(nuevocostounit*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad) | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun2" style="width:100px!important;" v-if="descripcion_factura2 && tipo_calculo=='1'">
                    {{nuevocostounit2 | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun" style="width:100px!important;"  v-else-if="descripcion_factura2 && tipo_calculo=='0'">
                    {{nuevocostounit2*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun3" style="width:100px!important;" v-if="descripcion_factura3 && tipo_calculo=='1'">
                    {{nuevocostounit3 | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun" style="width:100px!important;"  v-else-if="descripcion_factura3 && tipo_calculo=='0'">
                    {{nuevocostounit3*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun" style="width:100px!important;"  v-if="descripcion_factura4 && tipo_calculo=='1'">
                    {{nuevocostounit4 | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun" style="width:100px!important;"  v-else-if="descripcion_factura4 && tipo_calculo=='0'">
                    {{nuevocostounit4*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun2" style="width:100px!important;" v-if="descripcion_factura5 && tipo_calculo=='1'">
                    {{nuevocostounit5 | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun" style="width:100px!important;"  v-else-if="descripcion_factura5 &&tipo_calculo=='0'">
                    {{nuevocostounit5*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun3" style="width:100px!important;" v-if="descripcion_factura6 && tipo_calculo=='1'">
                    {{nuevocostounit6 | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun" style="width:100px!important;"  v-else-if=" descripcion_factura6 && tipo_calculo=='0'">
                    {{nuevocostounit6*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun3" style="width:100px!important;" v-if="descripcion_factura7 && tipo_calculo=='1'">
                    {{nuevocostounit7 | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun" style="width:100px!important;"  v-else-if=" descripcion_factura7 && tipo_calculo=='0'">
                    {{nuevocostounit7*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun3" style="width:100px!important;" v-if="descripcion_factura8 && tipo_calculo=='1'">
                    {{nuevocostounit8 | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun" style="width:100px!important;"  v-else-if=" descripcion_factura8 && tipo_calculo=='0'">
                    {{nuevocostounit8*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun3" style="width:100px!important;" v-if="descripcion_factura9 && tipo_calculo=='1'">
                    {{nuevocostounit9 | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun" style="width:100px!important;"  v-else-if=" descripcion_factura9 && tipo_calculo=='0'">
                    {{nuevocostounit9*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun" style="width:100px!important;"  v-if="descripcion_factura10 && tipo_calculo=='1'">
                    {{nuevocostounit10 | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun" style="width:100px!important;"  v-else-if="descripcion_factura10 && tipo_calculo=='0'">
                    {{nuevocostounit10*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun2" style="width:100px!important;" v-if="descripcion_factura11 && tipo_calculo=='1'">
                    {{nuevocostounit11 | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun" style="width:100px!important;"  v-else-if="descripcion_factura11 &&tipo_calculo=='0'">
                    {{nuevocostounit11*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun3" style="width:100px!important;" v-if="descripcion_factura12 && tipo_calculo=='1'">
                    {{nuevocostounit12 | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun" style="width:100px!important;"  v-else-if=" descripcion_factura12 && tipo_calculo=='0'">
                    {{nuevocostounit12*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun3" style="width:100px!important;" v-if="descripcion_factura13 && tipo_calculo=='1'">
                    {{nuevocostounit13 | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun" style="width:100px!important;"  v-else-if=" descripcion_factura13 && tipo_calculo=='0'">
                    {{nuevocostounit13*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun3" style="width:100px!important;" v-if="descripcion_factura14 && tipo_calculo=='1'">
                    {{nuevocostounit14 | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun" style="width:100px!important;"  v-else-if=" descripcion_factura14 && tipo_calculo=='0'">
                    {{nuevocostounit14*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun3" style="width:100px!important;" v-if="descripcion_factura15 && tipo_calculo=='1'">
                    {{nuevocostounit15 | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun" style="width:100px!important;"  v-else-if=" descripcion_factura15 && tipo_calculo=='0'">
                    {{nuevocostounit15*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun2" style="width:100px!important;" v-if="descripcion_factura16 && tipo_calculo=='1'">
                    {{nuevocostounit16 | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun" style="width:100px!important;"  v-else-if="descripcion_factura16 &&tipo_calculo=='0'">
                    {{nuevocostounit16*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun3" style="width:100px!important;" v-if="descripcion_factura17 && tipo_calculo=='1'">
                    {{nuevocostounit17 | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun" style="width:100px!important;"  v-else-if=" descripcion_factura17 && tipo_calculo=='0'">
                    {{nuevocostounit17*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun3" style="width:100px!important;" v-if="descripcion_factura18 && tipo_calculo=='1'">
                    {{nuevocostounit18 | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun" style="width:100px!important;"  v-else-if=" descripcion_factura18 && tipo_calculo=='0'">
                    {{nuevocostounit18*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun3" style="width:100px!important;" v-if="descripcion_factura19 && tipo_calculo=='1'">
                    {{nuevocostounit19 | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun" style="width:100px!important;"  v-else-if=" descripcion_factura19 && tipo_calculo=='0'">
                    {{nuevocostounit19*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun3" style="width:100px!important;" v-if="descripcion_factura20 && tipo_calculo=='1'">
                    {{nuevocostounit20 | currency}}
                </vs-td>
                <vs-td :data="tr.nuevocostoun" style="width:100px!important;"  v-else-if=" descripcion_factura20 && tipo_calculo=='0'">
                    {{nuevocostounit20*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad | currency}}
                </vs-td>
                <vs-td style="width:150px!important;" v-if="estado!='Liquidado'&& tipo_calculo=='1'">
                    <!-- {{nuevocostounit+nuevocostounit2+nuevocostounit3+nuevocostounit4+nuevocostounit5+nuevocostounit6+nuevocostounit7+nuevocostounit8+nuevocostounit9+nuevocostounit10+nuevocostounit11+nuevocostounit12+nuevocostounit13+nuevocostounit14+nuevocostounit15+nuevocostounit16+nuevocostounit17+nuevocostounit18+nuevocostounit19+nuevocostounit20 | currency}} -->
                    {{tr.nuevo_costo | currency}}
                </vs-td>
                <vs-td style="width:150px!important;" v-else-if="estado!='Liquidado'&& tipo_calculo=='0'">
                    <!-- {{parseFloat(tr.precio)+
                    parseFloat(nuevocostounit*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit2*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit3*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit4*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit5*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit6*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit7*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit8*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit9*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit10*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit11*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit12*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit13*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit14*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit15*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit16*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit17*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit18*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit19*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit20*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad) }}p -->
                    {{tr.nuevo_costo | currency}}
                </vs-td>
                <vs-td style="width:150px!important;" v-else>
                  {{tr.precio_liquidacion | currency}}
                </vs-td>
                <vs-td style="width:150px!important;" v-if="estado!='Liquidado' && tipo_calculo=='1'">
                    <!-- {{parseFloat(tr.cantidad)*(
                    parseFloat(nuevocostounit)+
                    parseFloat(nuevocostounit2)+
                    parseFloat(nuevocostounit3)+
                    parseFloat(nuevocostounit4)+
                    parseFloat(nuevocostounit5)+
                    parseFloat(nuevocostounit6)+
                    parseFloat(nuevocostounit7)+
                    parseFloat(nuevocostounit8)+
                    parseFloat(nuevocostounit9)+
                    parseFloat(nuevocostounit10)+
                    parseFloat(nuevocostounit11)+
                    parseFloat(nuevocostounit12)+
                    parseFloat(nuevocostounit13)+
                    parseFloat(nuevocostounit14)+
                    parseFloat(nuevocostounit15)+
                    parseFloat(nuevocostounit16)+
                    parseFloat(nuevocostounit17)+
                    parseFloat(nuevocostounit18)+
                    parseFloat(nuevocostounit19)+
                    parseFloat(nuevocostounit20)) | currency}} -->
                    {{tr.cantidad*tr.nuevo_costo | currency}}
                </vs-td>
                <vs-td style="width:150px!important;" v-else-if="estado!='Liquidado' && tipo_calculo=='0'">
                    <!-- {{parseFloat(tr.cantidad)*(
                    parseFloat((nuevocostounit*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad))+
                    parseFloat(nuevocostounit2*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit3*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit4*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit5*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit6*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit7*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit8*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit9*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit10*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit11*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit12*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit13*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit14*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit15*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit16*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit17*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit18*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit19*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)+
                    parseFloat(nuevocostounit20*((tr.cantidad*tr.precio)/totalcosto)/tr.cantidad)) | currency}} -->
                    {{tr.cantidad*tr.nuevo_costo | currency}}
                </vs-td>
                <vs-td style="width:150px!important;" v-else>
                  {{tr.total_liquidacion | currency}}
                </vs-td>
                
              </vs-tr>
            </template>
          </vs-table>
          
          <br>
          
               
      </div>
      
<div class="vx-row">
        <div class="vx-col sm:w-1/6 w-full mb-6"  >
        <h6>Cantidad Total</h6>
        {{totalcantidad}}
        </div>
        <div class="vx-col sm:w-1/6 w-full mb-6">
        <h6>Costo Total</h6>
        {{totalcosto | currency}}
        </div>
        <!--<div class="vx-col sm:w-1/6 w-full mb-6">
        <h6>Costo Total</h6>
        {{costounitario | currency}}
        </div>-->
        
        <div class="vx-col sm:w-1/4 w-full mb-6"  v-if="estado!='Liquidado'">
        <h6>Costo Importacion</h6>
        {{totalliquid2 |currency}}
        </div>
        </div>
      
        <vs-alert  color="rgb(231, 154, 23)" active="true" class="mt-5 text-warning" v-if="descripcion_factura ==''">
    La importacion seleccionada no tiene costos adicionales
</vs-alert>
      <vs-divider position="left" v-if="descripcion_factura !=''">
        <h3>Calculos</h3>
      </vs-divider>
      <div class="vx-row"  hidden>
        <div class="vx-col sm:w-1/6 w-full mb-6"  >
        <h5>{{descripcion_factura}}</h5>
        {{nuevocostounit | currency}}
      </div>
      <div class="vx-col sm:w-1/6 w-full mb-6" v-if="descripcion_factura2">
        <h5 >{{descripcion_factura2}}</h5>
        {{nuevocostounit2 | currency}}
        
      </div>
      <div class="vx-col sm:w-1/6 w-full mb-6" v-if="descripcion_factura3">
        <h5 >{{descripcion_factura3}}</h5>
        {{nuevocostounit3 | currency}}
        
      </div> 
      <div class="vx-col sm:w-1/6 w-full mb-6" v-if="descripcion_factura4">
        <h5 >{{descripcion_factura4}}</h5>
        {{nuevocostounit4 | currency}}
        
      </div> 
      <div class="vx-col sm:w-1/6 w-full mb-6" v-if="descripcion_factura5">
        <h5 >{{descripcion_factura5}}</h5>
        {{nuevocostounit5 | currency}}
        
      </div> 
      <div class="vx-col sm:w-1/6 w-full mb-6" v-if="descripcion_factura6">
        <h5 >{{descripcion_factura6}}</h5>
        {{nuevocostounit6 | currency}}
        
      </div> 
      <div class="vx-col sm:w-1/6 w-full mb-6" v-if="descripcion_factura7">
        <h5 >{{descripcion_factura7}}</h5>
        {{nuevocostounit7 | currency}}
        
      </div> 
      <div class="vx-col sm:w-1/6 w-full mb-6" v-if="descripcion_factura8">
        <h5 >{{descripcion_factura8}}</h5>
        {{nuevocostounit8 | currency}}
        
      </div> 
      <div class="vx-col sm:w-1/6 w-full mb-6" v-if="descripcion_factura9">
        <h5 >{{descripcion_factura9}}</h5>
        {{nuevocostounit9 | currency}}
        
      </div>
      <div class="vx-col sm:w-1/6 w-full mb-6" v-if="descripcion_factura10">
        <h5 >{{descripcion_factura10}}</h5>
        {{nuevocostounit10 | currency}}
        
      </div> 
      <div class="vx-col sm:w-1/6 w-full mb-6" v-if="descripcion_factura11">
        <h5 >{{descripcion_factura11}}</h5>
        {{nuevocostounit11 | currency}}
        
      </div> 
      <div class="vx-col sm:w-1/6 w-full mb-6" v-if="descripcion_factura12">
        <h5 >{{descripcion_factura12}}</h5>
        {{nuevocostounit12 | currency}}
        
      </div> 
      <div class="vx-col sm:w-1/6 w-full mb-6" v-if="descripcion_factura13">
        <h5 >{{descripcion_factura13}}</h5>
        {{nuevocostounit13 | currency}}
        
      </div> 
      <div class="vx-col sm:w-1/6 w-full mb-6" v-if="descripcion_factura14">
        <h5 >{{descripcion_factura14}}</h5>
        {{nuevocostounit14 | currency}}
        
      </div> 
      <div class="vx-col sm:w-1/6 w-full mb-6" v-if="descripcion_factura15">
        <h5 >{{descripcion_factura15}}</h5>
        {{nuevocostounit15 | currency}}
        
      </div>  
      <div class="vx-col sm:w-1/6 w-full mb-6" v-if="descripcion_factura16">
        <h5 >{{descripcion_factura16}}</h5>
        {{nuevocostounit16 | currency}}
        
      </div> 
      <div class="vx-col sm:w-1/6 w-full mb-6" v-if="descripcion_factura17">
        <h5 >{{descripcion_factura17}}</h5>
        {{nuevocostounit17 | currency}}
        
      </div> 
      <div class="vx-col sm:w-1/6 w-full mb-6" v-if="descripcion_factura18">
        <h5 >{{descripcion_factura18}}</h5>
        {{nuevocostounit18 | currency}}
        
      </div> 
      <div class="vx-col sm:w-1/6 w-full mb-6" v-if="descripcion_factura19">
        <h5 >{{descripcion_factura19}}</h5>
        {{nuevocostounit19 | currency}}
        
      </div> 
      <div class="vx-col sm:w-1/6 w-full mb-6" v-if="descripcion_factura20">
        <h5 >{{descripcion_factura20}}</h5>
        {{nuevocostounit20 | currency}}
        
      </div> 
      </div>
      <div class="vx-row p-base"  v-if="descripcion_factura !=''">
        <div class="vx-col sm:w-1/3 w-full mb-6 "></div>
          <div class="vx-col sm:w-1/2 w-full mb-6 " >
          <vs-table hoverFlat :data="total_factura" style="font-size: 12px;">
            <template slot="thead" >
              <vs-th>Id</vs-th>
              <vs-th>Descripcion</vs-th>
              <vs-th>Total</vs-th>
            </template>
            <template slot-scope="{data}">
              <vs-tr v-for="(tr, index) in data" :key="index">
    
                <!--<vs-td :data="tr.id_prodimp>{{ tr.id_prodimp }}</vs-td>-->
                <vs-td :data="tr.id_factcompra">{{ tr.id_factcompra }}</vs-td>
                <vs-td :data="tr.nombre">{{ tr.nombre}}</vs-td>
                <vs-td :data="tr.total">{{ tr.total }}</vs-td>
              </vs-tr>
              <vs-tr style="border-top: 1px solid #ddd;">
            <vs-th></vs-th>
            <vs-th>Total Facturas</vs-th>
            <vs-td>{{totalfac | currency}}</vs-td>
          </vs-tr>
            </template>
          </vs-table>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6 "></div>
      </div>
      
      <vs-divider position="left">
        <h3>Total Liquidacion</h3>
      </vs-divider>
      <div class="vx-row">
      <div class="vx-col sm:w-1/3 w-full mb-2 text-center" >
            <label class="vs-input--label">SALDO SIN LIQUIDAR</label>
            <h1>{{ totalcosto | currency }}</h1>
      </div>
      <div class="vx-col sm:w-1/3 w-full mb-2 text-center">
            <label class="vs-input--label">TOTAL PRODUCTOS</label>
            <h1>{{ totalcantidad}}</h1>
      </div>
      <div class="vx-col sm:w-1/3 w-full mb-2 text-center" v-if="estado!='Liquidado'">
            <label class="vs-input--label">TOTAL LIQUIDACION</label>
            <h1>{{ totalliq | currency }}</h1>
      </div>
      <div class="vx-col sm:w-1/3 w-full mb-2 text-center" v-else>
            <label class="vs-input--label">TOTAL LIQUIDACION</label>
            <h1>{{ totalfac | currency }}</h1>
      </div>
      </div>
      
      <div class="vx-col w-full">
        <vs-button color="success" type="filled" @click="liquidar()" v-if="estado=='Inicial' && totalcosto!=totalliq">Liquidar</vs-button>
        <vs-button color="danger" type="filled" v-if="descripcion_factura" to="/importacion/liquidacion">Cancelar</vs-button>
        <vs-button color="danger" type="filled" v-else to="/importacion/registro-importacion">Cancelar</vs-button>
      </div>
      </vx-card>
    </div>
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
    data(){
        return{
            idrecupera:null,
            total_factura:[],
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
            //productos importacion
            datos_filtrado_contenidopr:[],
            contenidopr:[],
          //liquidar a bodega
          nroingreso:"",
          fechingreso:"",
          id_bodega:"",
          comentario:"",
          configdateTimePicker: {
          locale: SpanishLocale
        },
        //traer bodega
        bodegas:[],
        //errores 
        error:0,
        errorbodega:[],
        errorfactura:[],
        errorfech_liquidacion:[],
        //
        buscar2:"",
        proyectos:[],
        id_proyecto:"",
        proveedores:[]
      };
    },
    filters: {
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
      totalcantidad(){
            var total = 0;
            this.contenidopr.forEach(el => {
                total += parseInt(el.cantidad)
            });
            return total;
      },
        totalcosto(){
            var total = 0;
            var total1=0;
            var totalliq=0;
            this.contenidopr.forEach(el => {
              
                total += parseFloat(el.cantidad*el.precio);
                totalliq += parseFloat(el.total);
            });
            if(this.estado!=='Liquidado'){
              total1=total;
            }else{
              total1=total;
            }
            console.log(total1);
            return total1;
        },
        totalfac(){
            var total = 0;
      this.total_factura.forEach(el => {
          total += parseFloat(el.total)
      });
      return total;
        },
        totalliq(){
      var total = 0;
      var totalprod=0;
      totalprod=this.totalfac;//this.totalfac+this.totalcosto;
      return totalprod;
        },
        costounitario(){
          var total = 0;
          this.contenidopr.forEach(el => {
              total += parseFloat(el.precio)
          });
          return total;
        },
        calculo_nuevo_costo(){
          if(this.contenidopr.length>0){
            this.contenidopr.forEach(el => {
              if(this.tipo_calculo==0){
                  el.nuevo_costo += 
                    Number((this.nuevocostounit*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                    Number((this.nuevocostounit2*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                    Number((this.nuevocostounit3*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                    Number((this.nuevocostounit4*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                    Number((this.nuevocostounit5*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                    Number((this.nuevocostounit6*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                    Number((this.nuevocostounit7*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                    Number((this.nuevocostounit8*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                    Number((this.nuevocostounit9*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                    Number((this.nuevocostounit10*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                    Number((this.nuevocostounit11*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                    Number((this.nuevocostounit12*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                    Number((this.nuevocostounit13*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                    Number((this.nuevocostounit14*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                    Number((this.nuevocostounit15*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                    Number((this.nuevocostounit16*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                    Number((this.nuevocostounit17*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                    Number((this.nuevocostounit18*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                    Number((this.nuevocostounit19*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                    Number((this.nuevocostounit20*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))
                    ;
              }else{
                  el.nuevo_costo += 
                    Number((this.nuevocostounit).toFixed(2))+
                    Number((this.nuevocostounit2).toFixed(2))+
                    Number((this.nuevocostounit3).toFixed(2))+
                    Number((this.nuevocostounit4).toFixed(2))+
                    Number((this.nuevocostounit5).toFixed(2))+
                    Number((this.nuevocostounit6).toFixed(2))+
                    Number((this.nuevocostounit7).toFixed(2))+
                    Number((this.nuevocostounit8).toFixed(2))+
                    Number((this.nuevocostounit9).toFixed(2))+
                    Number((this.nuevocostounit10).toFixed(2))+
                    Number((this.nuevocostounit11).toFixed(2))+
                    Number((this.nuevocostounit12).toFixed(2))+
                    Number((this.nuevocostounit13).toFixed(2))+
                    Number((this.nuevocostounit14).toFixed(2))+
                    Number((this.nuevocostounit15).toFixed(2))+
                    Number((this.nuevocostounit16).toFixed(2))+
                    Number((this.nuevocostounit17).toFixed(2))+
                    Number((this.nuevocostounit18).toFixed(2))+
                    Number((this.nuevocostounit19).toFixed(2))+
                    Number((this.nuevocostounit20).toFixed(2))
                  ;
              }
                //el.nuevo_costo += parseFloat(el.precio)
            });
          }
        },

        nuevocostounit(){
            var total=0;
            if(this.tipo_calculo==1){
              total = this.valorfactura1 / this.totalcantidad;
            }else{
              total = this.valorfactura1 ;
            }
          
           return total;
        },
        nuevocostounit2(){
            var total=0;
            if(this.tipo_calculo==1){
              total = this.valorfactura2 / this.totalcantidad;
            }else{
              total = this.valorfactura2;
            }
           return total;
        },
        nuevocostounit3(){
            var total=0;
            if(this.tipo_calculo==1){
              total = this.valorfactura3 / this.totalcantidad;
            }else{
              total = this.valorfactura3;
            }
              return total;
        },
        nuevocostounit4(){
            var total=0;
            if(this.tipo_calculo==1){
              total = this.valorfactura4 / this.totalcantidad;
            }else{
              total = this.valorfactura4;
            }
           return total;
        },
        nuevocostounit5(){
            var total=0;
            if(this.tipo_calculo==1){
              total = this.valorfactura5 / this.totalcantidad;
            }else{
              total = this.valorfactura5;
            }
           return total;
        },
        nuevocostounit6(){
            var total=0;
            if(this.tipo_calculo==1){
              total = this.valorfactura6 / this.totalcantidad;
            }else{
              total = this.valorfactura6;
            }
           return total;
        },
        nuevocostounit7(){
            var total=0;
            if(this.tipo_calculo==1){
              total = this.valorfactura7 / this.totalcantidad;
            }else{
              total = this.valorfactura7;
            }
           return total;
        },
        nuevocostounit8(){
            var total=0;
            if(this.tipo_calculo==1){
              total = this.valorfactura8 / this.totalcantidad;
            }else{
              total = this.valorfactura8;
            }
           return total;
        },
        nuevocostounit9(){
            var total=0;
            if(this.tipo_calculo==1){
              total = this.valorfactura9 / this.totalcantidad;
            }else{
              total = this.valorfactura9;
            }
           return total;
        },
        nuevocostounit10(){
            var total=0;
            if(this.tipo_calculo==1){
              total = this.valorfactura10 / this.totalcantidad;
            }else{
              total = this.valorfactura10;
            }
           return total;
        },
        nuevocostounit11(){
            var total=0;
            if(this.tipo_calculo==1){
              total = this.valorfactura11 / this.totalcantidad;
            }else{
              total = this.valorfactura11;
            }
           return total;
        },
        nuevocostounit12(){
            var total=0;
            if(this.tipo_calculo==1){
              total = this.valorfactura12 / this.totalcantidad;
            }else{
              total = this.valorfactura12;
            }
           return total;
        },
        nuevocostounit13(){
            var total=0;
            if(this.tipo_calculo==1){
              total = this.valorfactura13 / this.totalcantidad;
            }else{
              total = this.valorfactura13;
            }
           return total;
        },
        nuevocostounit14(){
            var total=0;
            if(this.tipo_calculo==1){
              total = this.valorfactura14 / this.totalcantidad;
            }else{
              total = this.valorfactura14;
            }
           return total;
        },
        nuevocostounit15(){
            var total=0;
            if(this.tipo_calculo==1){
              total = this.valorfactura15 / this.totalcantidad;
            }else{
              total = this.valorfactura15;
            }
           return total;
        },
        nuevocostounit16(){
            var total=0;
            if(this.tipo_calculo==1){
              total = this.valorfactura16 / this.totalcantidad;
            }else{
              total = this.valorfactura16;
            }
           return total;
        },
        nuevocostounit17(){
            var total=0;
            if(this.tipo_calculo==1){
              total = this.valorfactura17 / this.totalcantidad;
            }else{
              total = this.valorfactura17;
            }
           return total;
        },
        nuevocostounit18(){
            var total=0;
            if(this.tipo_calculo==1){
              total = this.valorfactura18 / this.totalcantidad;
            }else{
              total = this.valorfactura18;
            }
           return total;
        },
        nuevocostounit19(){
            var total=0;
            if(this.tipo_calculo==1){
              total = this.valorfactura19 / this.totalcantidad;
            }else{
              total = this.valorfactura19;
            }
           return total;
        },
        nuevocostounit20(){
            var total=0;
            if(this.tipo_calculo==1){
              total = this.valorfactura20 / this.totalcantidad;
            }else{
              total = this.valorfactura20;
            }
           return total;
        },
        totalliquid2(){
            var total = 0;
            var total_1=0;
            // if(this.tipo_calculo==0){
            //       el.nuevo_costo += 
            //         Number((this.nuevocostounit*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
            //         Number((this.nuevocostounit2*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
            //         Number((this.nuevocostounit3*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
            //         Number((this.nuevocostounit4*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
            //         Number((this.nuevocostounit5*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
            //         Number((this.nuevocostounit6*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
            //         Number((this.nuevocostounit7*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
            //         Number((this.nuevocostounit8*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
            //         Number((this.nuevocostounit9*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
            //         Number((this.nuevocostounit10*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
            //         Number((this.nuevocostounit11*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
            //         Number((this.nuevocostounit12*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
            //         Number((this.nuevocostounit13*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
            //         Number((this.nuevocostounit14*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
            //         Number((this.nuevocostounit15*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
            //         Number((this.nuevocostounit16*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
            //         Number((this.nuevocostounit17*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
            //         Number((this.nuevocostounit18*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
            //         Number((this.nuevocostounit19*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
            //         Number((this.nuevocostounit20*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))
            //         ;
            //   }else{
            //       el.nuevo_costo += 
            //         Number((this.nuevocostounit).toFixed(2))+
            //         Number((this.nuevocostounit2).toFixed(2))+
            //         Number((this.nuevocostounit3).toFixed(2))+
            //         Number((this.nuevocostounit4).toFixed(2))+
            //         Number((this.nuevocostounit5).toFixed(2))+
            //         Number((this.nuevocostounit6).toFixed(2))+
            //         Number((this.nuevocostounit7).toFixed(2))+
            //         Number((this.nuevocostounit8).toFixed(2))+
            //         Number((this.nuevocostounit9).toFixed(2))+
            //         Number((this.nuevocostounit10).toFixed(2))+
            //         Number((this.nuevocostounit11).toFixed(2))+
            //         Number((this.nuevocostounit12).toFixed(2))+
            //         Number((this.nuevocostounit13).toFixed(2))+
            //         Number((this.nuevocostounit14).toFixed(2))+
            //         Number((this.nuevocostounit15).toFixed(2))+
            //         Number((this.nuevocostounit16).toFixed(2))+
            //         Number((this.nuevocostounit17).toFixed(2))+
            //         Number((this.nuevocostounit18).toFixed(2))+
            //         Number((this.nuevocostounit19).toFixed(2))+
            //         Number((this.nuevocostounit20).toFixed(2))
            //       ;
            //   }
            this.contenidopr.forEach(el => {
              if(this.tipo_calculo=='1'){
                total += parseFloat(el.cantidad)*
                (//parseFloat(el.precio)+
                    Number((this.nuevocostounit).toFixed(2))+
                    Number((this.nuevocostounit2).toFixed(2))+
                    Number((this.nuevocostounit3).toFixed(2))+
                    Number((this.nuevocostounit4).toFixed(2))+
                    Number((this.nuevocostounit5).toFixed(2))+
                    Number((this.nuevocostounit6).toFixed(2))+
                    Number((this.nuevocostounit7).toFixed(2))+
                    Number((this.nuevocostounit8).toFixed(2))+
                    Number((this.nuevocostounit9).toFixed(2))+
                    Number((this.nuevocostounit10).toFixed(2))+
                    Number((this.nuevocostounit11).toFixed(2))+
                    Number((this.nuevocostounit12).toFixed(2))+
                    Number((this.nuevocostounit13).toFixed(2))+
                    Number((this.nuevocostounit14).toFixed(2))+
                    Number((this.nuevocostounit15).toFixed(2))+
                    Number((this.nuevocostounit16).toFixed(2))+
                    Number((this.nuevocostounit17).toFixed(2))+
                    Number((this.nuevocostounit18).toFixed(2))+
                    Number((this.nuevocostounit19).toFixed(2))+
                    Number((this.nuevocostounit20).toFixed(2))
                );
              }else{
                total += parseFloat(el.cantidad)*
                          (//parseFloat(el.precio)+
                              Number((this.nuevocostounit*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit2*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit3*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit4*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit5*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit6*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit7*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit8*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit9*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit10*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit11*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit12*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit13*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit14*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit15*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit16*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit17*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit18*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit19*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit20*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))
                          );
                          total_1+=el.cantidad*(
                              Number((this.nuevocostounit*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit2*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit3*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit4*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit5*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit6*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit7*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit8*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit9*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit10*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit11*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit12*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit13*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit14*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit15*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit16*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit17*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit18*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit19*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))+
                              Number((this.nuevocostounit20*((el.cantidad*el.precio)/this.totalcosto)/el.cantidad).toFixed(2))
                          );
              }
                
            });
            console.log("Total liquidacion1:"+ total_1);
            return total.toFixed(2);
      
        },
        sumar_iguales(){
              if(this.datos_filtrado_contenidopr.length>0){
                  this.datos_filtrado_contenidopr = this.datos_filtrado_contenidopr.reduce((acumulador, valorActual) => {
                  const elementoYaExiste = acumulador.find(elemento => elemento.id_bodega === valorActual.id_bodega);
                  if (elementoYaExiste) {
                      return acumulador.map((elemento) => {
                      if (elemento.id_bodega === valorActual.id_bodega) {
                          return {
                          ...elemento
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
    components: {
    flatPickr,
    
  },
    methods: {
        listarliquid(){
            if (this.$route.params.id) {
        this.idrecupera = this.$route.params.id;
        var url = "/api/verliquid";
        axios
          .put(url, { id: this.idrecupera })
          .then(res => {
            let data = res.data[0];

            //this.codigo_proveedor = "PR0" + data.id_proveedor;
           this.campo=data.total_importacion;
           this.estado=data.estado;
           this.codigo_import=data.cod_importacion;
           this.fech_liquidacion=data.fech_importacion;
           this.id_bodega=data.id_bodega;
           this.tipo_calculo=data.forma_liquidacion;
           this.id_proyecto=data.id_proyecto;
          })
          .catch(err => {
            //console.log(err);
          });
      } else {
        this.idrecupera = null;
      }
        },

        listarFactura(){
            if (this.$route.params.id) {
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
      } else {
        this.idrecupera = null;
      }
        },
        liquidar(){
          if (this.validar()) {
            return;
          }
            axios.put("/api/liquidar",{
                id:this.idrecupera,
                total:this.totalcosto,
                totalfac:this.totalfac,
                id_bodega:this.id_bodega,
                fecha_ingreso:this.fech_liquidacion,
                ucrea:this.usuario.id,
                id_empresa:this.usuario.id_empresa,
                id_proyecto:this.id_proyecto,
                contenidopr:this.contenidopr,
                cantiunitario1:this.nuevocostounit,
                cantunitario2:this.nuevocostounit2,
                cantunitario3:this.nuevocostounit3,
                cantunitario4:this.nuevocostounit4,
                cantunitario5:this.nuevocostounit5,
                cantunitario6:this.nuevocostounit6,
                cantunitario7:this.nuevocostounit7,
                cantunitario8:this.nuevocostounit8,
                cantunitario9:this.nuevocostounit9,
                cantunitario10:this.nuevocostounit10,
                cantunitario11:this.nuevocostounit11,
                cantunitario12:this.nuevocostounit12,
                cantunitario13:this.nuevocostounit13,
                cantunitario14:this.nuevocostounit14,
                cantunitario15:this.nuevocostounit15,
                cantunitario16:this.nuevocostounit16,
                cantunitario17:this.nuevocostounit17,
                cantunitario18:this.nuevocostounit18,
                cantunitario19:this.nuevocostounit19,
                cantunitario20:this.nuevocostounit20,
                tipo_calculo:this.tipo_calculo,
                bodegas:this.datos_filtrado_contenidopr,
                proveedores:this.proveedores
            }).then(res =>{
              console.log("Guardado:"+res.data);
              this.guardarBodegaIngreso(res.data);
                //console.log("LIquidado");
                
            }).catch(err=>{
              console.log("ERROR:"+err);
            });
        },
        guardarBodegaIngreso(id){
          //console.log("desde detallle bodega:"+id);
          axios.post('/api/liquidarbodega',{
            id_bodega_ingreso:id,
            contenidopr:this.contenidopr,
            codigo_import:this.codigo_import,
            cantiunitario1:this.nuevocostounit,
            cantunitario2:this.nuevocostounit2,
            cantunitario3:this.nuevocostounit3,
            cantunitario4:this.nuevocostounit4,
            cantunitario5:this.nuevocostounit5,
            cantunitario6:this.nuevocostounit6,
            cantunitario7:this.nuevocostounit7,
            cantunitario8:this.nuevocostounit8,
            cantunitario9:this.nuevocostounit9,
            cantunitario10:this.nuevocostounit10,
            cantunitario11:this.nuevocostounit11,
            cantunitario12:this.nuevocostounit12,
            cantunitario13:this.nuevocostounit13,
            cantunitario14:this.nuevocostounit14,
            cantunitario15:this.nuevocostounit15,
            cantunitario16:this.nuevocostounit16,
            cantunitario17:this.nuevocostounit17,
            cantunitario18:this.nuevocostounit18,
            cantunitario19:this.nuevocostounit19,
            cantunitario20:this.nuevocostounit20,
            provds:this.id_bodega,
            id_empresa:this.usuario.id_empresa,
            id_bodega:this.id_bodega,
            fecha_ingreso:this.fech_liquidacion,
            //total_costo:this.totalliquid2,
            tipo_calculo:this.tipo_calculo,
            costounitario:this.costounitario,
            total_costo:this.totalcosto,
            totalfac:this.totalfac,
            ucrea:this.usuario.id,
            id_proyecto:this.id_proyecto
          }).then(resp=>{
            console.log("Guardado a Bodega");
                this.$vs.notify({
                    color: "success",
                    title: "Liquidado con exito",
                    text: "Se ha liquidado la importacion con exito",
                });
            this.$router.push("/importacion/liquidacion");
          }).catch(err=>{
            console.log("ERROR Guardar a Bodega:"+err);
          });
    },

        listarprod() {
      this.idrecupera = this.$route.params.id;
      var url = "/api/traerproductoliquid/" + this.idrecupera;
      axios
        .get(url)
        .then(({data}) => {
          this.contenidopr = data.recupera;
          for(const prod of data.recupera){
            this.datos_filtrado_contenidopr.push({
              id_proyecto:prod.proyecto,
              id_bodega:prod.id_bodega
            });
          }
          this.proveedores=data.proveedores;
          console.log(JSON.stringify(this.proveedores)+"hola");
          console.log("xola")
        })
        .catch(err => {
          //console.log(err);
        });
    },
    getBodega() {
      axios
        .get("/api/traerbodliquid/" + this.usuario.id_establecimiento)

        .then(
          function(response) {
            this.bodegas = response.data;
            
          }.bind(this)
        );
    },
    validar(){
      this.error=0;
      this.errorbodega=[];
      this.errorfactura=[];
      this.errorfech_liquidacion=[];

      // if(!this.id_bodega){
      //   this.errorbodega.push("Campo Obligatorio");
      //   this.error=1;
      //   window.scrollTo(0, 0);
      // }
      if(!this.fech_liquidacion){
        this.errorfech_liquidacion.push("Campo Obligatorio");
        this.error=1;
        window.scrollTo(0, 0);
      }
      var error_servicio=0;
      for (var i = 0; i < this.contenidopr.length; i++) {
        if (this.contenidopr[i].sector>1) {
          error_servicio++;
          console.log("ERROR hay un servicio");
        }
      }
      if(error_servicio>0){
        this.$vs.notify({
              text: "No se puede agregar servicios a Bodega",
              color: "danger"
            });
            console.log("ERROR hay un servicio TOTAL");
        this.error=1;
      }
      return this.error;
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
                this.proyectos = respuesta.recupera;
            });
        },
    },
    mounted() {
        this.listarliquid();
        this.listarproyecto(1, this.buscar2);
        this.listarFactura();
        this.listarprod();
        this.getBodega();
    },
}
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
.agregado{
  background:#635ace!important;
  color:#fff!important;
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
.flexy>.vs-divider--text{
 display:flex;
}
.slide-fade-enter-active {
  transition: all .5s ease;
}
.slide-fade-leave-active {
  transition: all .5s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}
.slide-fade-enter, .slide-fade-leave-to
/* .slide-fade-leave-active for <2.1.8 */ {
  transform: translateX(10px);
  opacity: 0;
}
.columna-costototal{
padding: 10px 10px;
}
span.vs-divider-border.before {
  display: none;
}
div.vs-component.vs-divider > span.vs-divider--text{
  width: 100%;
  color: rgba(0, 0, 0, 0.8);
  display: flex;
  justify-content: space-between;
}
.table-header {
  text-align: center;
}
.table-header > div {
  width: max-content;
  max-width: 14em;
}
.vs-con-table .vs-con-tbody .vs-table--tbody-table .tr-values .vs-table--td {
    padding: 10px 0px;
    text-align: center;
}
.vs-con-table .vs-con-tbody .vs-table--tbody-table .vs-table--thead th {
    padding: 10px 15px;
    text-align: -webkit-center;
}

</style>
