<template>
    <vx-card>
        <div class="flex flex-wrap justify-between items-center mb-3">
                <!-- ITEMS PER PAGE -->
                <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"></div>
                <div
                    class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"
                >
                    <vs-input
                        class="mb-4 md:mb-0 mr-4"
                        v-model="buscar"
                        @keyup="listar(buscar)"
                        v-bind:placeholder="i18nbuscar"
                    />
                    <div class="dropdown-button-container">
                        <vs-button
                            class="btnx"
                            type="filled"
                            to="/contabilidad/concillacion-bancaria/agregar"
                            >Agregar</vs-button
                        >
                        <vs-dropdown>
                            <vs-button
                                class="btn-drop"
                                type="filled"
                                icon="expand_more"
                            ></vs-button>
                            <vs-dropdown-menu style="width:13em;">
                                <vs-dropdown-item class="text-center" divider
                                    >Importar registros</vs-dropdown-item
                                >
                                <vs-dropdown-item class="text-center"
                                    >Exportar registros</vs-dropdown-item
                                >
                                <vs-dropdown-item class="text-center" divider
                                    >Generar PDF</vs-dropdown-item
                                >
                                <vs-dropdown-item class="text-center"
                                    >Generar XML</vs-dropdown-item
                                >
                            </vs-dropdown-menu>
                        </vs-dropdown>
                    </div>
                </div>
            </div>
            <vs-table stripe :data="contenidopr">
                <template slot="thead">
                    <vs-th>Cuenta</vs-th>
                    <vs-th>Fecha</vs-th>
                    <vs-th>Consolidado</vs-th>
                    <vs-th>Acciones</vs-th>
                </template>
                <template slot-scope="{ data }">
                    <vs-tr :key="datos.id_asientos" v-for="datos in data">
                        <vs-td v-if="datos.cta">{{datos.cta.toUpperCase()}}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.fecha">
                            {{ datos.fecha |fecha |upper}}
                        </vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.suma">{{
                            datos.suma |currency
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        
                        <vs-td class="whitespace-no-wrap">
                            <!-- prettier-ignore -->
                            <feather-icon
                                icon="EditIcon"
                                class="cursor-pointer"
                                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                @click.stop="editar(datos.cod_conciliacion)"
                            />

                            <feather-icon
                                icon="TrashIcon"
                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                class="cursor-pointer"
                                @click.stop="eliminar(datos.cod_conciliacion)"
                            />
                            <feather-icon
                                icon="PrinterIcon"
                                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                class="cursor-pointer"
                                @click="generarPdf(datos.cod_conciliacion)"
                            />
                            <feather-icon
                                icon="MailIcon"
                                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                class="cursor-pointer"
                                @click.stop="AbrirMail(datos.cod_conciliacion,datos.id_plan_cuentas,datos.fecha)"
                            />
                        </vs-td>
                    </vs-tr>
                </template>
            </vs-table>
            <vs-popup title="Enviar Email" :active.sync="modal_email">
                 <div class="vx-row">
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                                    <vs-input 
                                    label="Destinatario:"
                                    class="mb-4 md:mb-0 mr-4"
                                    v-model="dest_email"
                                    />
                                    <!--<div v-show="error" v-if="!empleado_email && general==true">
                                        <div v-for="err in error_email_general" :key="err" v-text="err" class="text-danger"></div>
                                    </div>-->
                    </div>
                                    
                </div>
                <div class="vx-row">
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                                    <vs-input 
                                    label="Email:"
                                    class="mb-4 md:mb-0 mr-4"
                                    v-model="email"
                                    />
                                    <!--<div v-show="error" v-if="!empleado_email && general==true">
                                        <div v-for="err in error_email_general" :key="err" v-text="err" class="text-danger"></div>
                                    </div>-->
                    </div>
                                    
                </div>
                <div>
                    <vs-button color="warning" @click="sendData" type="filled" >Enviar</vs-button>
                </div>
                
            </vs-popup>
    </vx-card>
</template>
<script>
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
const axios = require("axios");
import moment from "moment";
moment.locale("es");
export default {
    data(){
        return{
            buscar1: "",
            offset1: 3,
            i18nbuscar: this.$t("i18nbuscar"),
            cuentaContable:"",
            idcuentaContable:"",
            contenidocuenta: [],
            popupActive:false,
            cuentaarray3:[],
            fecha:"",
            configdateTimePicker: {
                locale: SpanishLocale
            },
            buscar:"",
            contenidopr:[],
            codigo_conciliacion:"",
            modal_email:false,
            nomb_cta:"",
            fecha_conc:"",
            dest_email:"",
            email:"",
            //errores
            error:0,
            errorDestinatario:[],
            errorEmail:[],
        };
    },
    filters:{
        fecha(data){
            return moment(data).format("MMMM YYYY");
        },
        upper: function (value) {
            return value.toUpperCase();
        }
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
    },
    methods:{
        editar(id){
            this.$router.push(
                `/contabilidad/concillacion-bancaria/${id}/editar`
            );
        },
        eliminar(cd) {
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
        acceptAlert(parameters) {
            axios.delete("/api/eliminar/conciliacion/" + parameters).then(res=>{
                this.$vs.notify({
                    color: "success",
                    title: "Conciliacion Eliminada",
                    text: "Conciliacion eliminada con exito"
                });
                this.listarConciliacion();
            }).catch(err=>{
                this.$vs.notify({
                color: "danger",
                title: "Error al Eliminar",
                text: "Este registro se esta utilizando en otra seccion"
                });
            });
        
        },
        AbrirMail(cod,nomcta,fecha){
            this.codigo_conciliacion=cod;
            this.nomb_cta=nomcta;
            this.fecha_conc=fecha;
            this.generarPdfEmail(cod,nomcta,fecha).then(value=>{
                this.modal_email=true;
                console.log("Correcto pdf");
            }).catch(error=>{
                console.log(error+"Error pdf");
            });
        },
        sendData(){
            if(this.validarEmail()){
                return;
            }
            var url="/api/email/conciliacion"
            axios.post(url,{
                cod:this.codigo_conciliacion,
                nombcta:this.nomb_cta,
                fecha:this.fecha_conc,
                email:this.email,
                destinatario:this.dest_email,
                id_empresa:this.usuario.id_empresa
            }).then(resp=>{
                this.dest_email="";
                this.email="";
                this.modal_email=false;
            }).catch();
        },
        generarPdfEmail(cod,destinatario,email){
            return new Promise((resolve,reject)=>{
                var url="/api/pdf/conciliacion";
                axios.get(url,{
                    params:{
                       cod_conciliacion:cod,
                        id_empresa:this.usuario.id_empresa,
                        destinatario:destinatario,
                        email:email 
                    }
                }).then(response=>{
                     resolve(response.data);
                }).catch(err=>{
                     reject(err);
                });
            });
        },
        generarPdf(cod_rol,destinatario=null,email=null){
            axios({
                    url: "/api/pdf/conciliacion",
                    method: "GET",
                    responseType: "arraybuffer",
                    params:{
                      cod_conciliacion:cod_rol,
                      id_empresa:this.usuario.id_empresa,
                      destinatario:destinatario,
                      email:email
                    }
                
                }).then(resp=>{
                  console.log("ejecutado empleado");
                //this.contenidopr=res.data;
                console.log("resp:"+resp);
                  console.log("resp data:"+resp.data);

                  var decodedString = String.fromCharCode.apply(
                    null,
                    new Uint8Array(resp.data)
                );
                if (decodedString.includes("no-data-report")) {
                    this.$vs.notify({
                      title: "Sin Registros",
                      text: "Los Datos que escogio no tienen registros",
                      color: "danger"
                    });
                }
                
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
                this.modal_proyecto=false;
                this.dep_rol="";
                this.fechrolprov="";
                this.datos_proyecto.selectedproyecto=[];
                }).catch(err=>{
                  console.log("ERROR"+err);
                });
        },
        listarcuenta(page1, buscar1) {
            var url =
                "/api/cuentas/movimiento/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidocuenta = respuesta.recupera;
            });
        },
        abrirPopupCuentaContable() {
            this.popupActive = true;
        },
        handleSelected(tr){
            this.cuentaContable=tr.codcta+"-"+tr.nomcta;
            this.idcuentaContable=tr.id_plan_cuentas;
            this.popupActive=false;
        },
        listarConciliacion(){
            var url="/api/traer/conciliacion/"+this.usuario.id_empresa
            axios.get(url)
            .then(resp=>{
                this.contenidopr=resp.data;
            }).catch(err=>{
                
            });
        },
        validarEmail(){
            this.error=0;
            this.errorDestinatario=[];
            this.errorEmail=[];
            if(!this.email){
                this.errorEmail.push("Campo Obligatorio");
                this.error=1;
            }
            if(!this.dest_email){
                this.errorDestinatario.push("Campo Obligatorio");
                this.error=1;
            }
            return this.error;
        }
    },
    mounted() {
        //this.listarcuenta(1,this.buscar1);
        this.listarConciliacion();
    }
}
</script>