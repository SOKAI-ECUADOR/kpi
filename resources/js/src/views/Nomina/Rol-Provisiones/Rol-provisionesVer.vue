<template>
  <div id="ag-grid-demo">
    <vx-card>
        <div class="vx-row">
        <div class="vx-col sm:w-1/3 w-full mb-6">
            <label class="vs-input--label">Departamento</label>
            <vx-input-group class>
              <vs-input class="w-full" v-model="departamento" :value="iddepart" disabled />

              <template slot="append">
                <div class="append-text btn-addon" v-if="!this.$route.params.id">
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
            <flat-pickr :config="configdateTimePicker" v-model="fechrolprov" disabled/>
            {{fecha_rol_prov}}
            <!--<vs-select autocomplete class="selectExample w-full mt-3" label="Fecha" v-model="fechrolprov" @change="abrirrol()">
            <vs-select-item
              
              :key="index"
              :value="item.value"
              :text="item.text"
              v-for="(item,index) in forma_pago_array"
            />
            </vs-select>-->
            <div v-show="error" v-if="!fechrolprov">
              <div v-for="err in errorfecha" :key="err" v-text="err" class="text-danger"></div>
            </div>
          </div>
          </div>
         
        <vs-table hoverFlat :data="contenidopr" style="font-size: 10px;">
            <template slot="thead">
              <vs-th class="table-header">Listado Personal</vs-th>
              <vs-th class="table-header">Dias Trabajados</vs-th>
              <vs-th class="table-header" style="min-width:160px!important;">Proyecto</vs-th>
              <vs-th class="table-header" style="min-width:110px!important;">Total Ingreso</vs-th>
              <vs-th class="table-header" style="min-width:110px!important;">Aporte Patronal</vs-th>
              <vs-th class="table-header" style="min-width:110px!important;">Fondo Reserva</vs-th>
              <vs-th class="table-header" style="min-width:110px!important;">Decimo Tercero</vs-th>
              <vs-th class="table-header" style="min-width:110px!important;">Decimo Cuarto</vs-th>
              <vs-th class="table-header" style="min-width:110px!important;">Vacaciones</vs-th>
              
              <vs-th class="table-header" style="min-width:110px!important;">Total Provisiones</vs-th>
              <vs-th class="table-header" style="min-width:110px!important;">Total Costo</vs-th>
            </template>
            <template slot-scope="{data}">
              <vs-tr v-for="(tr, index) in data" :key="index">
                <vs-td :data="tr.primer_nombre" style="width:180px!important;">{{ tr.primer_nombre }} {{tr.apellido_paterno}}</vs-td>
                <vs-td :data="tr.cantidad" style="width:40px!important;">
                  <vs-input class="w-full"  v-model="tr.cantidad" @keypress="solonumeros($event)" disabled/>
                  <div v-show="error" v-if="tr.cantidad>30">
                    <div
                      v-for="err in tr.errordias"
                      :key="err"
                      v-text="err"
                      class="text-danger"
                    ></div>
                  </div>
                </vs-td>
                <vs-td :data="tr.id_proyecto" class="proyecto" style="width:190px!important;">
                  <vs-select
                            
                            placeholder="--Seleccione--"
                            autocomplete
                            class="selectExample w-full"
                            v-model="tr.id_proyecto"
                            disabled
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
                </vs-td>
                <vs-td :data="tr.total_ingreso" style="width:100px!important;text-align:right;">
                  {{tr.total_ingreso |currency}}
                </vs-td>
                <vs-td :data="tr.iess_patronal" style="width:100px!important;text-align:right;" >
                  {{tr.total_ingreso*12.15/100 |currency}}
                </vs-td>
                <vs-td :data="tr.fondo_reserva" style="width:100px!important;text-align:right;" >
                  {{tr.fondo_reserva |currency}}
                </vs-td>
                <vs-td :data="tr.decimo_tercero" style="width:100px!important;text-align:right;" >
                  {{tr.decimo_tercero |currency}}
                </vs-td>

                <vs-td :data="tr.decimo_cuarto" style="width:100px!important;text-align:right;" >
                  {{parseFloat(tr.decimo_cuarto)/360*parseFloat(tr.cantidad) |currency}}
                </vs-td>
                <vs-td :data="tr.vacaciones" style="width:100px!important;text-align:right;">
                  {{tr.total_ingreso/24 |currency}}
                </vs-td>
                <vs-td style="width:100px!important;text-align:right;" :data="tr.total_provisiones">
                  {{(parseFloat(tr.total_ingreso)*12.15/100)+(parseFloat(tr.fondo_reserva))+(parseFloat(tr.decimo_cuarto)/360*parseFloat(tr.cantidad))+(parseFloat(tr.decimo_tercero))+(parseFloat(tr.total_ingreso)/24) |currency}}
                </vs-td>
                <vs-td style="width:100px!important;text-align:right;" :data="tr.total_costo">
                  {{parseFloat(tr.total_ingreso)+(parseFloat(tr.total_ingreso)*12.15/100)+(parseFloat(tr.fondo_reserva))+(parseFloat(tr.decimo_cuarto)/360*parseFloat(tr.cantidad))+(parseFloat(tr.decimo_tercero))+(parseFloat(tr.total_ingreso)/24) |currency}}
                </vs-td>
              </vs-tr>
               <vs-tr style="border-top: 1px solid #ddd;text-align:right;">
                 <th><h5>Total</h5></th>
                 <th></th>
                 <th></th>
                 <th style="text-align:right;"><h5>{{totalingresos() |currency}}</h5></th>
                 <th style="text-align:right;"><h5>{{totaliesspatronal() |currency}}</h5></th>
                 <th style="text-align:right;"><h5>{{totalfondo_reserva() |currency}}</h5></th>
                 <th style="text-align:right;"><h5>{{totaldecimo_tercero() |currency}}</h5></th>
                 <th style="text-align:right;"><h5>{{totaldecimo_cuarto() |currency}}</h5></th>
                 <th style="text-align:right;"><h5>{{totalvacaciones() |currency}}</h5></th>
                 <th style="text-align:right;"><h5>{{totalprovisiones() |currency}}</h5></th>
                 <th style="text-align:right;"><h5>{{totalcostos() |currency}}</h5></th>
                 
               </vs-tr>
            </template>
          </vs-table>
          <br>
          <div class="vx-col w-full">
                <!--<vs-button
                    color="success"
                    type="filled"
                    @click="editar()"
                    v-if="$route.params.id"
                    >GUARDAR</vs-button
                >
                <vs-button
                    color="success"
                    type="filled"
                    @click="guardar()"
                    v-else
                    >GUARDAR</vs-button
                >-->
                <vs-button
                    color="danger"
                    type="filled"
                    to="/nomina/rol-pago-provisiones"
                    >CANCELAR</vs-button
                >
            </div>
    </vx-card>
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
  </div>
</template>
<script>
import "vue-form-wizard/dist/vue-form-wizard.min.css";
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import { concat } from "bytebuffer";
const axios = require("axios");
export default {
    data(){
        return{
            contenidopr:[],
            departamento:"",
            iddepart:"",
            idrecupera:null,
            fechrolprov:"",
            configdateTimePicker: {
                locale: SpanishLocale
            },
            proyectos:[],
            //modal departamento
            activePrompt4:false,
            buscar1:"",
            contenidodepartamento:[],
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
            //errores
            error:0,
            errordepartamento:[],
            errorfecha:[],
            errordias:[]
        };
    },
    components: {
        flatPickr
    },
    computed:{
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
                res = this.$store.state.Roles[33].crear;
            }
            return res;
        },
        editarrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[33].editar;
            }
            return res;
        },
        eliminarrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[33].eliminar;
            }
            return res;
        },
        fecha_rol_prov(){
          if(this.fechrolprov && !this.$route.params.id){
            this.abrirrol();
          }
        },
        
    },
    methods:{
        abrirdepart() {
            this.activePrompt4 = true;
            this.tipomodal = 2;
            this.listarDepartamento(1, this.buscarp);
        },
        listarDepartamento(page1, buscar1) {
            var url = "/api/departamento/listar/"+this.usuario.id_empresa;
            axios.get(url).then(res => {
                this.contenidodepartamento = res.data.recupera;
                })
                .catch(function(error) {
                console.log(error);
            });
        },
        handleSelected4(tr) {
            
            this.departamento=`${tr.dep_nombre}`;
            this.iddepart = `${tr.id_departamento}`;
            this.activePrompt4 = false;
            /*this.traerIngresos(tr.id_departamento);
            this.traerEgresos(tr.id_departamento);
            this.traerSueldo(tr.id_departamento);*/
        },
        abrirrol(){
            var url = "/api/abrirrolpagoprov/"+this.iddepart;
            axios.get(url,{
                    params: {
                        fecha: this.fechrolprov
                    }
                }).then(res => {
                this.contenidopr=res.data;
            }).catch(function(error) {
                console.log(error);
            });
        },
        totalingresos(){
          var total=0;
          this.contenidopr.forEach(el=>{
            total+= parseFloat(el.total_ingreso);
          });
          return total;
        },
        totaliesspatronal(){
          var total=0;
          this.contenidopr.forEach(el=>{
            total+= parseFloat(el.total_ingreso)*12.15/100;
          });
          return total;
        },
        totalfondo_reserva(){
          var total=0;
          this.contenidopr.forEach(el=>{
            total+= parseFloat(el.fondo_reserva);
          });
          return total;
        },
        totaldecimo_tercero(){
          var total=0;
          this.contenidopr.forEach(el=>{
            total+= parseFloat(el.decimo_tercero);
          });
          return total;
        },
        totaldecimo_cuarto(){
          var total=0;
          this.contenidopr.forEach(el=>{
            total+= parseFloat(el.decimo_cuarto)/360*parseFloat(el.cantidad);
          });
          return total;
        },
        totalvacaciones(){
          var total=0;
          this.contenidopr.forEach(el=>{
            total+= parseFloat(el.total_ingreso)/24;
          });
          return total;
        },
        totalprovisiones(){
          var total=0;
          total=this.totaliesspatronal()+this.totalfondo_reserva()+this.totaldecimo_tercero()+this.totaldecimo_cuarto()+this.totalvacaciones();
          return total;
        },
        totalcostos(){
          var total=0;
          total=this.totalprovisiones()+this.totalingresos();
          return total;
        },
        guardar(){
            if(this.validar()){
                return;
            }
            axios.post("/api/guardarrolprov",{
              id_departamento:this.iddepart,
              id_empresa:this.usuario.id_empresa,
              fechrolprov:this.fechrolprov,
              productos:this.contenidopr
            }).then(res =>{
              if(res.data==="existe"){
                this.$vs.notify({
                    title: "Registro ya existe",
                    text: "Este Rol Provisiones ya existe",
                    color: "danger"
                });
              }else{
                this.$vs.notify({
                    title: "Registro Guardado",
                    text: "Registro Guardado exitosamente",
                    color: "success"
                });
                //this.listarrolEgreso(this.iddepart);
                this.$router.push(`/nomina/rol-pago-provisiones`);
              }
            }).catch(err =>{
              this.$vs.notify({
                    title: "Error al guardar",
                    text: "Revise bien sus campos antes de guardar",
                    color: "danger"
              });
            });
        },
        editar(){
          if(this.validar()){
                return;
            }
            axios.put("/api/editarolprov",{
              id_departamento:this.iddepart,
              id_empresa:this.usuario.id_empresa,
              fechrolprov:this.fechrolprov,
              productos:this.contenidopr
            }).then(res =>{
              this.$vs.notify({
                    title: "Registro Actualizado",
                    text: "Registro Actualizado exitosamente",
                    color: "success"
              });
              //this.listarrolEgreso(this.iddepart);
              this.$router.push(`/nomina/rol-pago-provisiones`);
            }).catch(err =>{
              this.$vs.notify({
                    title: "Error al actualizar",
                    text: "Revise bien sus campos antes de guardar",
                    color: "danger"
              });
            });
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
        validar(){
            this.error=0;
            this.errordepartamento=[];
            this.errorfecha=[];
            if(!this.iddepart){
                this.errordepartamento.push("Campo Obligatorio");
                this.error=1;
            }
            if(!this.fechrolprov){
                this.errorfecha.push("Campo Obligatorio");
                this.error=1;
            }
            for (var i = 0; i < this.contenidopr.length; i++) {
                this.contenidopr[i].errordias = [];
                if (this.contenidopr[i].cantidad>30) {
                    this.contenidopr[i].errordias.push("No puede ser mayor de 30");
                    this.error = 1;
                }
            } 
            return this.error;
        },
        listarRol(){
            if(this.$route.params.id){
              var url="/api/abrirrolprov/"+this.$route.params.id;
              axios.get(url)
                .then(res=>{
                  this.contenidopr=res.data;
                  this.departamento=res.data[0].departamento;
                  this.iddepart=res.data[0].id_departamento;
                  this.fechrolprov=res.data[0].fechrolprov;
                  console.log('editado');
                })
                .catch(err=>{

                })
            }
        },
        listarProyecto(){
        var url="/api/rolpago/traerproyecto/"+this.usuario.id_empresa;
        axios.get(url).then(res => {
          
          this.proyectos=res.data;
        });
    },
        
    },
    mounted(){
      this.listarProyecto();
      this.listarRol();
    }
}
</script>
<style scoped>
.vs-con-table .vs-con-tbody .vs-table--tbody-table .proyecto{
    font-size: 1rem;
    width: 145%!important;
}
.vs-con-table .vs-con-tbody .vs-table--tbody-table .tr-values .vs-table--td {
    padding: 10px 7px;
}

                                    
</style>