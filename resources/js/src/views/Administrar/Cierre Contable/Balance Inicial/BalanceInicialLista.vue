<template>
    <div id="ag-grid-demo">
        <vx-card>
            <div class="flex flex-wrap justify-between items-center mb-3">
                <!-- ITEMS PER PAGE -->
                <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"></div>
                <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
                    <vs-input
                        class="mb-4 md:mb-0 mr-4"
                        v-model="buscar"
                        @keyup="listar(1,buscar)"
                        v-bind:placeholder="i18nbuscar"
                    />
                    <div class="dropdown-button-container">
                        <vs-button class="btnx" type="filled" to="/administrar/cierre_contable/balance_inicial/agregar">Agregar</vs-button>
                
                    </div>
                </div>
            </div>
            <vs-table stripe :data="contenido">
                <template slot="thead">
                    <!--<vs-th>#</vs-th>-->
                    <vs-th>Código</vs-th>
                    <!--<vs-th>Proyecto</vs-th>-->
                    <vs-th>Concepto</vs-th>
                    <vs-th>Comprobante</vs-th>
                    <vs-th>Razon social</vs-th>
                    <vs-th>Periodo</vs-th>
                    <vs-th>Acciones</vs-th>
                </template>
                <template slot-scope="{ data }">
                    <vs-tr :key="datos.id_asientos" v-for="(datos) in data">
                        <!--<vs-td >{{index + 1}}</vs-td>-->
                        <vs-td v-if="datos.numero">{{datos.codigo_comprobante.toUpperCase()}}-{{ datos.numero }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <!--<vs-td v-if="datos.descripcion">
                            {{ datos.descripcion }}
                        </vs-td>
                        <vs-td v-else>-</vs-td>-->
                        <vs-td v-if="datos.concepto">{{
                            datos.concepto
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.tipo">{{
                            datos.tipo.toUpperCase()
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.razon_social">{{
                            datos.razon_social
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.periodo">{{ datos.periodo }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td class="whitespace-no-wrap">
                            <feather-icon
                                icon="EyeIcon"
                                class="cursor-pointer"
                                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                @click.stop="editarAsientoContable(datos.id_asientos)"
                            />
                            <feather-icon
                                icon="TrashIcon"
                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                class="cursor-pointer"
                                @click.stop="eliminarAsientoContable(datos.id_asientos)"
                            />
                            <!-- <feather-icon
                                icon="EditIcon"
                                class="cursor-pointer"
                                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                @click.stop="editarAsientoContable(datos.id_asientos)"
                            />

                            <feather-icon
                                icon="TrashIcon"
                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                class="cursor-pointer"
                                @click.stop="eliminarAsientoContable(datos.id_asientos,datos.id_asientos_comprobante,datos.id_proyecto,datos.codigo_rol)"
                            />
                            <feather-icon
                                icon="PrinterIcon"
                                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                class="cursor-pointer"
                                @click="generarPdf(datos.id_asientos)"
                            />
                            <feather-icon
                                v-if="datos.existe_cheque>0"
                                hidden
                                icon="CreditCardIcon"
                                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                class="cursor-pointer"
                                @click="generarCheques(datos.id_asientos,datos.fecha)"
                            /> -->
                        </vs-td>
                    </vs-tr>
                </template>
            </vs-table>
        </vx-card>
    </div> 
</template>
<script>
import { AgGridVue } from "ag-grid-vue";
import moment from "moment";
moment.locale("es");
const $ = require("jquery");
const axios = require("axios");
export default {
    components: {
        AgGridVue
    },
    filters: {
        fecha(data) {
            return moment(data).format("LL");
        }
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
            if(this.usuario.id_rol==1){
                res=1
            }else{
                res = this.$store.state.Roles[68].crear;
            }
            return res;
        },
        editarrol(){
            var res = 0;
            if(this.usuario.id_rol==1){
                res=1
            }else{
                res = this.$store.state.Roles[68].editar;
            }
            return res;
        },
        eliminarrol(){
            var res = 0;
            if(this.usuario.id_rol==1){
                res=1
            }else{
                res = this.$store.state.Roles[68].eliminar;
            }
            return res;
        }
    },
    data() {
        return {
            //buscador
            buscar: "",
            criterio: "secuencial",
            //otros valores
            gridApi: null,
            contenido: [],
            //lenguaje
            i18nbuscar: this.$t("i18nbuscar"),
        };
    },
    methods: {
        listar(page, buscar) {
            var url =
                "/api/balance_inicial/listar" ;
            axios.get(url,{params:{
                id_empresa:this.usuario.id_empresa
            }}).then(res => {
                var respuesta = res.data;
                this.contenido = respuesta.recupera;
            });
        },
        editarAsientoContable(id){
            this.$router.push(`/administrar/cierre_contable/balance_inicial/${id}/editar`);
        },
        eliminarAsientoContable(id) {
            //metodo eliminar
            var url="/api/balance_inicial/eliminar/"+id;
            this.$vs.dialog({
                type: "confirm",
                color: "danger",
                title: `Confirmar`,
                text: "¿Desea Elimnar este registro?",
                
                accept: () => {
                    axios
                        .get(url)
                        .then(respuesta => {
                            this.listar(1,"");
                            this.$vs.notify({
                                color: "success",
                                title: "Asiento Eliminado  ",
                                text: "El asiento selecionado fue eliminado con exito"
                            });
                        }).catch(err=>{
                            this.$vs.notify({
                                color: "danger",
                                title: "Error al Eliminar",
                                text: "Este registro se esta utilizando en otra seccion"
                            });
                        });
                }
            });
        },
    },
    mounted(){
        this.listar(1,"");
    }
}
</script>