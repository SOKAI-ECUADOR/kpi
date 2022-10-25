<template>
    <div id="ag-grid-demo">
        <vx-card>
            <div class="flex flex-wrap justify-between items-center">
                <div class="flex flex-wrap justify-between ag-grid-table-actions-left">
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
                <div
                    class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"
                >
                    <vs-input
                        class="mb-4 md:mb-0 mr-4"
                        v-model="buscar"
                        @keyup="listar(1, buscar, '', '')"
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
                    <div class="dropdown-button-container mr-3">
                        <!--@click="abrirModal()"-->
                        <vs-button
                            class="btnx"
                            type="filled"
                            to="/activos-fijos/depreciacion/agregar"
                            >Agregar</vs-button
                        >
                    </div>
                </div>
            </div>
            <br>
            <vs-table stripe max-items="25" pagination :data="contenido">
                <template slot="thead">
                <vs-th>Codigo</vs-th>
                <vs-th>Mes</vs-th>
                
                <!--<vs-th>Total Ingresos</vs-th>
                <vs-th>Total Egresos</vs-th>-->
                <vs-th>Acciones</vs-th>
                </template>
                <template slot-scope="{data}">
                    <vs-tr :key="datos.cod_rol_pago" v-for="datos in data">
                        <vs-td v-if="datos.codigo_depreciacion">{{datos.codigo_depreciacion}}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.fecha_fin">{{datos.fecha_fin |fecha |upper}}</vs-td>
                        <vs-td v-else>-</vs-td>

                        <vs-td class="whitespace-no-wrap">
                            <feather-icon
                            
                                icon="EyeIcon"
                                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                class="cursor-pointer"
                                v-if="editarrol"
                                @click.stop="editar(datos.id_depreciacion)"
                            />
                            <feather-icon
                                icon="PrinterIcon"
                                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                class="cursor-pointer"
                                @click="generarPdf(datos.id_depreciacion)"
                            />
                            <!--<feather-icon hidden
                                
                                icon="TrashIcon"
                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                class="cursor-pointer"
                                v-if="eliminarroles"
                                @click.stop="eliminar(datos.id_depreciacion)"
                            />-->
                            <feather-icon icon="SlidersIcon" class="cursor-pointer" v-if="datos.contabilidad!==null" svgClasses="w-5 h-5 fill-current text-success" @click="Contabilidad(datos.id_depreciacion)"/>
                            <feather-icon icon="SlidersIcon" class="cursor-pointer" v-else svgClasses="w-5 h-5 fill-current text-primary" @click="Contabilidad(datos.id_depreciacion)"/>
                            <feather-icon icon="CheckIcon"  v-if="datos.contabilidad!==null" svgClasses="w-5 h-5"/>
                            <!--
                            <feather-icon
                                v-else
                                icon="EyeIcon"
                                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                class="cursor-pointer"
                                @click.stop="ver(datos.cod_rol_pago)"
                            />
                            
                            <feather-icon
                                icon="MailIcon"
                                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                class="cursor-pointer"
                                @click.stop="generarPapeletas(datos.id_departamento,datos.fechrol)"
                            />
                            
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
                            <feather-icon icon="CheckIcon"  v-if="datos.cont==1" svgClasses="w-5 h-5"/>-->
                            <!--@click="Contabiliidad(datos.cod_rol_pago,datos.fechrol)"-->
                            <!--@click="reporte_liquidacion(datos.id_importacion)"-->
                        </vs-td>
                    </vs-tr>
                </template>
            </vs-table>
            <vs-popup title="Asiento Contable" class="peque2" :active.sync="modalAsiento">
        <div class="vx-row">
            <div class="vx-col sm:w-1/12 w-full mb-6">
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
            <div class="vx-col sm:w-1/5 w-full mb-6">
                            <label class="vs-input--label">Tipo Identificacion:</label>
                            <vx-input-group>
                                <vs-input
                                    class="w-full"
                                    v-model="tipo_identificacion"
                                    disabled
                                />

                            </vx-input-group>

            </div>
            <div class="vx-col sm:w-1/5 w-full mb-6">
                            <label class="vs-input--label">Identificacion:</label>
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
            <div class="vx-row">
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
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

            {{cambioDecimales}}

            {{sumar_iguales}}


            
            <div
                id="two-row"
                class="vx-row"
                v-for="data in depreciacion_debe"
                :key="data.id_detalle"
            >
            <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <!--CUENTA CONTABLE-->
                        <div class="vx-col sm:w-1/3 w-full mb-6" v-if="data.debe>0">
                            <vx-input-group>
                                <!-- prettier-ignore -->
                                <vs-input
                                    class="w-full"
                                    v-model="data.nombre_cuenta"
                                    disabled
                                />

                            </vx-input-group>
                        </div>
                        <div class="vx-col sm:w-1/6 w-full mb-6" v-if="data.debe>0">
                        <vs-input
                                    class="w-full"
                                    v-model="data.descripcion"
                                    disabled
                                />
                        </div>
                        <!--DEBE-->
                        <!-- prettier-ignore -->
                        <div class="vx-col sm:w-2/12 w-full mb-6" v-if="data.debe>0">
                            <vs-input
                                class="w-full valores"
                                v-model="data.debe"
                                disabled
                            />
                        </div>
                        <!--HABER-->
                        <!-- prettier-ignore -->
                        <div class="vx-col sm:w-2/12 w-full mb-6" v-if="data.debe>0">
                            <vs-input
                                class="w-full"
                                v-model="data.haber"
                                disabled

                            />
                        </div>


                    </div>
                </div>
            </div>
            <div
                id="two-row"
                class="vx-row"
                v-for="data in depreciacion_haber"
                :key="data.id_plan_cuenta"
            >
            <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <!--CUENTA CONTABLE-->
                        <div class="vx-col sm:w-1/3 w-full mb-6" v-if="data.haber>0">
                            <vx-input-group>
                                <!-- prettier-ignore -->
                                <vs-input
                                    class="w-full"
                                    v-model="data.nombre_cuenta"
                                    disabled
                                />

                            </vx-input-group>
                        </div>
                        <div class="vx-col sm:w-1/6 w-full mb-6" v-if="data.haber>0">
                        <vs-input
                                    class="w-full"
                                    v-model="data.descripcion"
                                    disabled
                                />
                        </div>
                        <!--DEBE-->
                        <!-- prettier-ignore -->
                        <div class="vx-col sm:w-2/12 w-full mb-6" v-if="data.haber>0">
                            <vs-input
                                class="w-full valores"
                                v-model="data.debe"
                                disabled
                            />
                        </div>
                        <!--HABER-->
                        <!-- prettier-ignore -->
                        <div class="vx-col sm:w-2/12 w-full mb-6" v-if="data.haber>0">
                            <vs-input
                                class="w-full"
                                v-model="data.haber"
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
                            <label class="vs-input--label center">Total</label>
                        </div>
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <label class="vs-input--label center">Total</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="vx-row">
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">

                        <div class="vx-col sm:w-1/2 w-full mb-6">

                        </div>

                        {{suma_debe}}
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <vs-input
                                    class="w-full"
                                    v-model="total_debe"
                                    disabled
                                />
                        </div>
                        {{suma_haber}}
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <vs-input
                                    class="w-full"
                                    v-model="total_haber"
                                    disabled
                                />
                        </div>
                    </div>
                </div>
            </div>
            {{Diferencia}}
            <div class="vx-row">
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">

                        <div class="vx-col sm:w-1/2 w-full mb-6">

                        </div>

                        <div class="vx-col sm:w-2/12 w-full mb-6" v-if="diferencia_debe>0">
                            <label class="vs-input--label center">Diferencia</label>
                        </div>
                        <div class="vx-col sm:w-2/12 w-full mb-6" v-if="diferencia_haber>0">
                            <label class="vs-input--label center">Diferencia</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="vx-row">
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">

                        <div class="vx-col sm:w-1/2 w-full mb-6">

                        </div>


                        <div class="vx-col sm:w-2/12 w-full mb-6" v-if="diferencia_debe>0">
                            <vs-input
                                    class="w-full"
                                    v-model="diferencia_debe"
                                    disabled
                                />
                        </div>

                        <div class="vx-col sm:w-2/12 w-full mb-6" v-if="diferencia_haber>0">
                            <vs-input
                                    class="w-full"
                                    v-model="diferencia_haber"
                                    disabled
                                />
                        </div>
                    </div>
                </div>
            </div>
                <div v-if="contabilizado!==null">
                    <h5> Este asiento ya ha sido registrado</h5>
                </div>
                <div v-else>
                    <vs-button
                            color="success"
                            type="filled"
                            @click="crearasiento(id_factura)"
                            >GUARDAR</vs-button
                        >
                </div>
        </vs-popup>
        </vx-card>
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
moment.locale("es");
const axios = require("axios");
export default {
    components: {
        AgGridVue,
        flatPickr,
        FormWizard,
        TabContent
    },
    data(){
        return{
            buscar:"",
            criterio:"",
            cantidadp:"",
            i18nbuscar: this.$t("i18nbuscar"),
            contenido:[],
            //variables Contabilizar
            modalAsiento:false,
            nombre_proyecto:"",
            fecha_rol:"",
            ruc_empresa:"",
            razon_social:"",
            concepto:"",
            codigo:"",
            depreciacion_debe:[],
            depreciacion_haber:[],
            total_debe:"",
            total_haber:"",
            id_factura:"",
            id_proyecto:"",
            tipo_identificacion:"",
            contabilizado:null,
            modal_conciliacion:false,
            indextipoarreglo:"",
            nombre_pago:"",
            id_pago:"",
            fecha_pago:"",
            nro_documento:"",
            diferencia_debe:0,
            diferencia_haber:0,
            ice:[],
            num_mayor_iva:[],
            num_mayor_renta:[],
            posicion_iva:0,
            posicion_renta:0,
            activos:[],
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
    computed:{
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
                    if(el.nombre == "Depreciacion"){
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
                    if(el.nombre == "Depreciacion"){
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
                        if(el.nombre == "Depreciacion"){
                            res = el.eliminar;
                            return res;
                        }
                    });
                }
                return res;
        },
        //compted asiento
        cambioDecimales(){
            if(this.depreciacion_debe.length>0){
                this.depreciacion_debe.forEach(el => {
                    if(el.debe !==null){
                        el.debe=parseFloat(el.debe).toFixed(2);
                    }

                });
            }
            if(this.depreciacion_haber.length>0){
                this.depreciacion_haber.forEach(el => {
                    if(el.haber !==null){
                        el.haber=parseFloat(el.haber).toFixed(2);
                    }

                });
            }
        },
        sumar_iguales(){
            if(this.depreciacion_debe.length>0){
                this.depreciacion_debe = this.depreciacion_debe.reduce((acumulador, valorActual) => {
                const elementoYaExiste = acumulador.find(elemento => elemento.id_proyecto === valorActual.id_proyecto  && elemento.id_plan_cuenta === valorActual.id_plan_cuenta );
                if (elementoYaExiste) {
                    return acumulador.map((elemento) => {
                    if (elemento.id_proyecto === valorActual.id_proyecto && elemento.id_plan_cuenta === valorActual.id_plan_cuenta) {
                        return {
                        ...elemento,
                        debe: parseFloat(elemento.debe) + parseFloat(valorActual.debe)
                        }
                    }

                    return elemento;
                    });
                }

                return [...acumulador, valorActual];
                }, []);
            }
            if(this.depreciacion_haber.length>0){
                this.depreciacion_haber = this.depreciacion_haber.reduce((acumulador, valorActual) => {
                const elementoYaExiste = acumulador.find(elemento => elemento.id_proyecto === valorActual.id_proyecto  && elemento.id_plan_cuenta === valorActual.id_plan_cuenta );
                if (elementoYaExiste) {
                    return acumulador.map((elemento) => {
                    if (elemento.id_proyecto === valorActual.id_proyecto && elemento.id_plan_cuenta === valorActual.id_plan_cuenta) {
                        return {
                            ...elemento,
                            haber: parseFloat(elemento.haber) + parseFloat(valorActual.haber)
                        }
                    }

                    return elemento;
                    });
                }

                return [...acumulador, valorActual];
                }, []);
            }
        },
        suma_debe(){
            var total=0;
            if(this.depreciacion_debe.length>0){
                this.depreciacion_debe.forEach(el => {
                    total+=parseFloat(el.debe);
                });
            }
            this.total_debe=total.toFixed(2);
        },
        suma_haber(){
            var total=0;
            if(this.depreciacion_haber.length>0){
                this.depreciacion_haber.forEach(el => {
                    total+=parseFloat(el.haber);
                });
            }
            this.total_haber=total.toFixed(2);
        },
        Diferencia(){
            var tot
            if(this.depreciacion_debe.length>0){

            }
        },
    },
    methods:{
        filtertabla() {
            var contvar = this.filterstable.contrue;
            if (this.filterstable.filtertab == true) {
                if (this.filterstable.filt_asientos == false) {
                    contvar = contvar.filter(
                        contvar => contvar.contabilidad == null
                    );
                }
                if (this.filterstable.filt_noasientos == false) {
                    contvar = contvar.filter(
                        contvar => contvar.contabilidad == 1
                    );
                }
            } else {
                this.filterstable.filt_asientos = true;
                this.filterstable.filt_noasientos = true;
            }
            this.contenido = contvar;
        },
        listar(page,buscar,cantidad,otro){
            let me = this;
            var url =
                "/api/depreciacion/" +
                this.usuario.id_empresa +
                "?page=" +
                page +
                "&buscar=" +
                buscar;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.contenido = respuesta.recupera;
                    me.filterstable.contrue = respuesta.recupera;
                    me.filtertabla();                    
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        editar(id){
            this.$router.push(`/activos-fijos/depreciacion/${id}/editar`);
        },
        generarPdf(cod_rol,destinatario=null,email=null){
            axios({
                    url: "/api/pdf/depreciaccion",
                    method: "GET",
                    responseType: "arraybuffer",
                    params:{
                      id_asientos:cod_rol,
                      id_empresa:this.usuario.id_empresa,
                      destinatario:destinatario,
                      email:email
                    }
                
                }).then(resp=>{
                  console.log("ejecutado empleado");
                //this.contenidopr=res.data;
                console.log("resp:"+resp);
                  console.log("resp data:"+resp.data);

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
                /*this.modal_proyecto=false;
                this.dep_rol="";
                this.fechrolprov="";
                this.datos_proyecto.selectedproyecto=[];*/
                }).catch(err=>{
                  console.log("ERROR"+err);
                });
        },
        eliminar(id) {
            this.$vs.dialog({
                type: "confirm",
                color: "danger",
                title: `Confirmar`,
                text: `¿Desea Eliminar este registro?:`,
                acceptText: "Aceptar",
                cancelText: "Cancelar",
                accept: this.acceptAlert,
                parameters: id
            });
        },
        acceptAlert(parameters) {
            //console.log(parameters);
            axios.delete("/api/eliminar/depreciacion/" + parameters).then(res=>{
                this.$vs.notify({
                    color: "success",
                    title: "Reguistro Eliminado  ",
                    text: "El Reguistro selecionado fue eliminado con exito"
                });
            }).catch(err=>{
                this.$vs.notify({
                    color: "danger",
                    title: "Error al eliminar reguistro",
                    text: "Este reguistro esta siendo utilizado en otro modulo"
                });
            });
            
            this.listar(1, this.buscar, this.cantidadp);
        },

        //methods asientos
        Contabilidad(id){
            axios.get('/api/depreciacion/vercontabilidad/' + id+
                        "?id_empresa=" +
                        this.usuario.id_empresa).then( ({data}) => {
                            this.fecha_rol=data.cabecera.fecha_fin;
                            var fecha=moment(this.fecha_rol).format("MMMM YYYY");
                            if(data.cabecera.contabilidad!==null){
                                this.codigo="DP-"+data.codigo_anterior;
                                this.contabilizado=data.cabecera.contabilidad;
                            }else{
                                this.codigo="DP-"+data.codigo;
                                this.contabilizado=null;
                            }
                            this.concepto="Depreciacion "+fecha;
                            this.depreciacion_debe=data.detalle_debe;
                            this.depreciacion_haber=data.detalle_haber;
                            this.razon_social=data.cabecera.nombre_empresa;
                            this.activos=data.activos;
                            this.modalAsiento=true;
                            // setTimeout(() => {
                            //     this.igualar();
                            // },8000);
                            this.id_factura=id;
                            this.id_proyecto=data.id_proyecto;
                        }).catch( error => {
                            console.log(error);
                        });
        },
        crearasiento(id){
                var total=0;
                total=this.total_debe-this.total_haber;
                console.log("total diferencia:"+total);
                if(total!==0){
                    this.$vs.notify({
                        color: "danger",
                        title: "No esta cuadrado el Asiento",
                    });
                    return;
                }
                if(this.validar()){
                    this.$vs.notify({
                        color: "danger",
                        title: "Verifique los datos antes de guardar el Asiento",
                    });
                    return;
                }
                var codigo_asiento = this.codigo.substr(3,this.codigo.length);
                var fecha_hoy=new Date();
                axios.post("/api/depreciacion/agregar/asiento",{
                    cod_rol:id,
                    numero:codigo_asiento,
                    codigo:this.codigo,
                    fecha:this.fecha_rol+" "+fecha_hoy.getHours()+":"+fecha_hoy.getMinutes()+":"+fecha_hoy.getSeconds(),
                    razon_social:this.razon_social,
                    tipo_identificacion:this.tipo_identificacion,
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
                });
        },
        validar(){
            var error=0;
            if(!this.depreciacion_debe){
                error++;
            }else{
                this.depreciacion_debe.forEach(el => {
                    if(!el.debe){
                        error++;
                    }
                    if(!el.id_plan_cuenta){
                        error++;
                    }
                    if(!el.id_proyecto){
                        error++;
                    }
                });
            }
            if(!this.depreciacion_haber){
                error++;
            }else{
                this.depreciacion_haber.forEach(el => {
                    if(!el.haber){
                        error++;
                    }
                    if(!el.id_plan_cuenta){
                        error++;
                    }
                    if(!el.id_proyecto){
                        error++;
                    }
                });
            }
            return error;
        },
        crearasientoDetalle(id){
            axios.post("/api/depreciacion/agregar/asiento_detalle",{
                detalle_debe:this.depreciacion_debe,
                detalle_haber:this.depreciacion_haber,
                activos:this.activos,
                ucrea:this.usuario.id,
                id_asientos:id,
            }).then(res=>{
                this.$vs.notify({
                color: "success",
                title: "Asiento Agregado",
                text: "Asiento agregado con exito"
                });
                this.modalAsiento=false;
                this.listar(1, this.buscar);
            }).catch(err=>{
                this.$vs.notify({
                color: "danger",
                title: "Asiento No Agregado",
                text: err
                });
            });
        }
    },
    mounted(){
        this.listar(1,this.buscar,"","");
    }
    
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
.vs-popup {
  width: 1060px !important;
}
.peque2 .vs-popup {
        width: 1080px !important;
    }
.estilosacciones .vs-button-primary{
        padding: .5rem 1rem!important;
        cursor:pointer;
    }
    .vs-con-dropdown{
        cursor: pointer;
    }
    .feather-icon{
        vertical-align: sub;
    }
</style>