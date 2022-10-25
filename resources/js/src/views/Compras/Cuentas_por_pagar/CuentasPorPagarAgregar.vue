<template>
    <div id="invoice-page">
    <vx-card>
        <div class="vx-row">
            <div class="vx-col sm:w-1/3 w-full mb-2">
                <label class="vs-input--label">Fecha</label>
          <flat-pickr
              :config="configdateTimePicker"
              class="w-full"
              placeholder="Seleccione una"
              v-model="fech_embarque"
            />
            </div>
            <div class="vx-col sm:w-1/3 w-full mb-2">
                <vs-select
              label="Pago por:"
              class="selectExample w-full"
              autocomplete
              v-model="nro_orden"
            >
              <vs-select-item value="Cancelacion" text="Cancelacion" />
              <vs-select-item value="Anticipo" text="Anticipo" />
              <vs-select-item value="Cuenta" text="Cuenta" />
            </vs-select>
            </div>
            <div class="vx-col sm:w-1/3 w-full mb-2">
                <vs-select
              label="Forma pago:"
              class="selectExample w-full"
              autocomplete
              v-model="nro_orden"
            >
              <vs-select-item value="Cancelacion" text="Efectivo" />
              <vs-select-item value="Anticipo" text="Cheque" />
              <vs-select-item value="Cuenta" text="Cuenta" />
            </vs-select>
            </div>
            
        </div>
        <div class="vx-row">
                <div class="vx-col sm:w-1/4 w-full mb-2"> 
                <vs-input  label="Valor Seleccionado" class="w-full" v-model="valor"/>
                </div>
                <div class="vx-col sm:w-1/4 w-full mb-2"> 
                <vs-input  label="Descuento" class="w-full" v-model="valor"/>
                </div>
                <div class="vx-col sm:w-1/4 w-full mb-2"> 
                <vs-input  label="Valor Descuento" class="w-full" v-model="valor"/>
                </div>
                <div class="vx-col sm:w-1/4 w-full mb-2"> 
                <vs-input  label="Valor a Pagar" class="w-full" v-model="valor"/>
                </div>
        </div>

          <vs-divider position="left">
        <h3>Productos</h3>
      </vs-divider>
      <div class="p-base">
        <vs-table hoverFlat :data="contenidopr" style="font-size: 12px;">
            <template slot="thead">
              <vs-th>NUM FACTURA</vs-th>
              <vs-th>PRODUCTO</vs-th>
              <vs-th>NUM CUOTA</vs-th>
              <vs-th>FECHA</vs-th>
              <vs-th>VALOR CUOTA</vs-th>
              <vs-th>SELECCIONAR</vs-th>
            </template>
            <template slot-scope="{data}">
              <vs-tr v-for="(tr, index) in data" :key="index">
                
                <!--<vs-td :data="tr.id_prodimp>{{ tr.id_prodimp }}</vs-td>-->
                <vs-td :data="tr.codigo">{{ tr.codigo }}</vs-td>
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
               
                <vs-td >{{ (tr.cantidad*tr.precio)| currency }}</vs-td>
                <vs-td :data="tr.dolar">
                  <feather-icon
                    icon="TrashIcon"
                    svgClasses="w-5 h-5 hover:text-danger stroke-current"
                    class="ml-2 cursor-pointer"
                    @click="borrarprov(index)"
                  />
                </vs-td>
              </vs-tr>
              <vs-tr style="border-top: 1px solid #ddd;">
            <vs-td>Total Vencido</vs-td>
            <vs-td>
              <vs-alert color="danger" active="true"></vs-alert>
            </vs-td>
            <vs-td>-</vs-td>
            
            <vs-td>Total</vs-td>
            <vs-td>-</vs-td>
            <vs-td></vs-td>
          </vs-tr>
            </template>
          </vs-table>
          <br>
          
      </div>
      <div class="vx-col w-full">
        <vs-button color="success" type="filled" @click="guardar()" v-if="!$route.params.id">Guardar</vs-button>
        <vs-button color="success" type="filled" @click="editar()" v-else>Guardar</vs-button>
        <vs-button class="vs-con-loading__container" color="danger" type="filled" to="/compras/importacion">Cancelar</vs-button>
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
const axios = require("axios");
export default {
data(){
        return{
            fech_embarque:"",
            contenidopr:[],
            popupActive2:false,
            tipomodal:0,
            contenidop:[],
            buscarp: "",
            configdateTimePicker: {
                locale: SpanishLocale
                },
        }
    },
    components: {
    flatPickr,
    "v-select": vSelect
  },
methods: {
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
    },
mounted(){
    this.listarp(1,this.buscarp);
}
}
</script>