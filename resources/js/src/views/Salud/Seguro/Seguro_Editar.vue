<template>
    <div id="ag-grid-demo">
        <vx-card>
            <div class="vx-row">
                <div class="vx-col sm:w-1/3 w-1/3 mb-6">
                        <vs-input class="w-full" label="Nombre" v-model="nombre_plan" maxlength="254"/>
                        <div v-show="error" v-if="!nombre_plan">
                                    <span
                                        class="text-danger"
                                        v-for="err in errornombre_plan"
                                        :key="err"
                                        v-text="err"
                                    ></span>
                        </div>
                </div>
                <div class="vx-col sm:w-1/3 w-1/3 mb-6">
                        <vs-select
                            label="Seguro"
                            placeholder="buscar"
                            autocomplete
                            class="selectExample w-full"
                            v-model="seguro"
                        >
                            <vs-select-item
                                v-for="(tr, index) in seguros"
                                :key="index"
                                :value="tr.id_seguro"
                                :text="tr.nombre"
                            />
                        </vs-select>
                        <div v-show="error" v-if="!seguro">
                                    <span
                                        class="text-danger"
                                        v-for="err in errorseguro"
                                        :key="err"
                                        v-text="err"
                                    ></span>
                        </div>
                        
                </div>
                <div class="vx-col sm:w-1/3 w-1/3 mb-6">
                        <vs-input class="w-full" label="Descuento %" v-model="dscuento_plan" maxlength="15"/>
                        <div v-show="error" v-if="!dscuento_plan">
                                    <span
                                        class="text-danger"
                                        v-for="err in errordscuento_plan"
                                        :key="err"
                                        v-text="err"
                                    ></span>
                        </div>
                </div>
            </div>
            <vs-table stripe max-items="25" pagination :data="contenido">
                <template slot="thead">
                        <vs-th>Cod Principal</vs-th>
                        <vs-th>Nombre</vs-th>
                        <vs-th>Descricion</vs-th>
                        <vs-th>Agregado<vs-checkbox v-model="agregado_todo" @click="agregar_productos_todo()" vs-value="1"></vs-checkbox></vs-th>
                </template>
                <template slot-scope="{ data }">
                    <vs-tr v-for="(datos, index) in data" :key="index">
                        <vs-td v-if="datos.cod_principal">{{datos.cod_principal}}</vs-td> <vs-td v-else>-</vs-td> 
                        <vs-td v-if="datos.nombre"> {{datos.nombre}}</vs-td> <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.descripcion"> {{datos.descripcion}}</vs-td> <vs-td v-else>-</vs-td>
                        <vs-td style="text-align: right;!important"><vs-checkbox v-model="datos.agregado" vs-value="1" @click="agregar_productos(index,datos)"></vs-checkbox></vs-td>
                    </vs-tr>
                </template>
            </vs-table>
            <div class="vx-col w-full">
                <div v-show="error" v-if="contenido.length<=0">
                                    <span
                                        class="text-danger"
                                        v-for="err in errorcontenido"
                                        :key="err"
                                        v-text="err"
                                    ></span>
                        </div>
            </div>
            <div class="vx-col w-full">
                        <vs-button color="success" type="filled" :disabled="disabled_button" @click="guardar()">GUARDAR</vs-button>
                        
                        <vs-button color="danger"  type="filled" @click="cancelar()">CANCELAR</vs-button>
            </div>
        </vx-card>
    </div>
</template>
<script>
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import { AgGridVue } from "ag-grid-vue";
import moment from "moment";
moment.locale("es");
import $ from "jquery";
const axios = require("axios");
export default {
    components: {
        AgGridVue,
        flatPickr
    },
    filters: {
        fecha(data) {
            return moment(data).format("LL");
        },
        fechayhora(data) {
            return moment(data).format("LLL");
        }
    },
    data(){
        return{
            seguro:"",
            seguros:[],
            dscuento_plan:"",
            contenido:[],
            agregado_todo:false,
            disabled_button:false,
            nombre_plan:"",
            //errores
            error:0,
            errorseguro:[],
            errordscuento_plan:[],
            errorcontenido:[],
            errornombre_plan:[]
        };
    },
    computed:{
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        },
    },
    methods:{
        listarproductos(){
            var url="/api/productos_planes_editar/"+this.$route.params.id;

            axios.get(url,{
                params:{
                    empresa:this.usuario.id_empresa,
                }
            }).then(resp=>{
                this.contenido=resp.data;
            })
        },
        agregar_productos_todo(){
            if(this.contenido.length>0){
                this.contenido.forEach(el=> {
                    if(this.agregado_todo==1){
                        el.agregado=null;
                    }else{
                        el.agregado=1;
                    }
                });
            }
        },
        agregar_productos(index,datos){
            console.log(JSON.stringify(datos));
        },
        listar_seguros(page, buscar) {
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.timeout =  setTimeout(() => {
                this.seguros = [];
                var url = "/api/seguros/"+this.usuario.id_empresa;
                var datos = {
                    page: page,
                    buscar: buscar,
                    datos: this.usuario
                };
                axios.post(url, datos).then(res => {
                    this.seguros=res.data.recupera;
                });
            }, 800);
        },
        guardar(){
            this.disabled_button=true;
            if(this.validar()){
                this.disabled_button=false;
                return;
            }
            this.$vs.notify({
                    text: "Espere mientras se guarda este registro",
                    color: "primary"
            });
            axios.post("/api/editar_planes_seguro",{
                id:this.$route.params.id,
                nombre:this.nombre_plan,
                seguro:this.seguro,
                descuento:this.dscuento_plan,
                productos:this.contenido,
                umodifica:this.usuario.id
            })
            .then(resp=>{
                this.$vs.notify({
                    title: "Registro Editado",
                    text: "Este registro ha sido editado exitosamente",
                    color: "success"
                });
                this.$router.push("/salud/seguro");
            })
            .catch(err=>{
                this.disabled_button=false;
                console.log(err);
            });
        },
        validar(){
            this.error=0;
            this.errorseguro=[];
            this.errordscuento_plan=[];
            this.errorcontenido=[];
            this.errornombre_plan=[];
            if(!this.seguro){
                this.errorseguro.push("Campo Obligatorio");
                this.error=1;
            }
            if(!this.dscuento_plan){
                this.errordscuento_plan.push("Campo Obligatorio");
                this.error=1;
            }
            if(this.contenido.length<=0){
                this.errorcontenido.push("Campo Obligatorio");
                this.error=1;
            }
            if(!this.nombre_plan){
                this.errornombre_plan.push("Campo Obligatorio");
                this.error=1;
            }
            return this.error;
        },
        recupera(){
            var url="/api/pla_seguro/recuperar/"+this.$route.params.id;
            axios.get(url)
            .then(resp=>{
                this.nombre_plan=resp.data[0].nombre;
                this.dscuento_plan=resp.data[0].descuento;
                this.seguro=resp.data[0].id_seguro;
                this.$vs.notify({
                    text: "Espere mientras se cargan todos los datos",
                    color: "primary"
                });
                this.listarproductos();
            })
            .catch(err=>{
                this.$vs.notify({
                    text: "Error recuperar datos",
                    color: "danger"
                });
            })
        },
        
        cancelar(){
            this.$router.push("/salud/seguro");
        },

    },
    mounted(){
        this.listar_seguros(1,"");
        this.recupera();
    }
}
</script>