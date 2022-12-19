<template>
    <div class="vx-row">
        <div class="vx-col w-full mb-base">

            <vx-card>
                <div class="vx-row">
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                    </div>   
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <div class="centerx">
                            <vs-upload text="Click para subir archivo" limit=1 accept="xls xlsx" action="https://jsonplaceholder.typicode.com/posts/" @on-success="successUpload" />
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                    </div>

                </div>
            </vx-card>
        </div>
    </div>
</template>

<script>
import { log } from "util";
import { AgGridVue } from "ag-grid-vue";
import Datepicker from "vuejs-datepicker";
const $ = require("jquery");
const axios = require("axios");
export default {
    components: {
        AgGridVue,
        Datepicker
    },
    data() {
        return {
            /**
             * mapeo de datos
             */
            tipo_activo: "",
            identificador: "",
            codigo_identificacion: "",
            codigo_anterior: "",
            nombre_bien: "",
            descripcion: "",
            marca: "",
            modelo: "",
            //serie: "",
            color: "",
            color_secundario: "",
            tipo: "",
            //material: "",
            ano_fabricacion: "",
            conservacion: "",
            mantenimiento: "",
            fechacompra: "",
            costoadquisicion: "",
            combustible: "",
            motor: "",
            placa: "",
            chasis: "",
            kilometraje: "",
            vehiculo: "",
            ubicaciongeneral: "",
            ubicacionespecifica: "",
            custodio: "",
            cuentacontable: "",
            observaciones: "",
            id_vehiculo: '',
            contenidotipoactivo: [],
            contenidomarca: [],
            contenidomodelo: [],
            contenidocolor: [],
            //contenidomaterial: [],
            contenidoano: ['2022', '2021', '2020', '2019', '2018', '2017', '2016', '2015', '2014', '2013', '2012', '2011', '2010', '2009', '2008', '2007', '2006', '2005', '2004', '2003', '2002', '2001', '2000'],
            contenidoconservacion: [],
            contenidomantenimiento: [],
            contenidoubicaciongeneral: [],
            contenidoubicacionespecifica: [],
            contenidocustodio: [],
            contenidoidentificador: [],
            contenidotipo: [],
            buscar1: "",
            recuperaimagen: "",
            imagen: "",
            imagenprevisualizar:[],
        };
    },
    computed: {
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        }
    },
    props: { modalactive: false },
    methods: {
        listartipoactivo(page1, buscar1) {
            var url =
                "/api/tipoactivomobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidotipoactivo = respuesta.recupera;
            });
        },
        listarmarca(page1, buscar1) {
            var url =
                "/api/marcamobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidomarca = respuesta.recupera;
            });
        },
        listarmodelo(page1, buscar1) {
            var url =
                "/api/modelomobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidomodelo = respuesta.recupera;
            });
        },
        listarcolor(page1, buscar1) {
            var url =
                "/api/colormobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidocolor = respuesta.recupera;
            });
        },
        /*listarmaterial(page1, buscar1) {
            var url =
                "/api/materialmobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidomaterial = respuesta.recupera;
            });
        },*/
        listarconservacion(page1, buscar1) {
            var url =
                "/api/conservacionmobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidoconservacion = respuesta.recupera;
            });
        },
        listarmantenimiento(page1, buscar1) {
            var url =
                "/api/mantenimientomobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidomantenimiento = respuesta.recupera;
            });
        },
        listarubicaciongeneral(page1, buscar1) {
            var url =
                "/api/ubicaciongeneralmobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidoubicaciongeneral = respuesta.recupera;
            });
        },
        cambiarubicacionespecifica(){
            this.ubicacionespecifica = "";
            this.listarubicacionespecifica(1, "");
        },
        listarubicacionespecifica(page1, buscar1) {
            var url =
                "/api/listarubicacionespecificamobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1 +
                "&ubicaciongeneral=" +
                this.ubicaciongeneral;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidoubicacionespecifica = respuesta.recupera;
            });
        },
        listarcustodio(page1, buscar1) {
            var url =
                "/api/custodiomobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidocustodio = respuesta.recupera;
            });
        },
        listaridentificador(page1, buscar1) {
            var url =
                "/api/identificadormobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidoidentificador = respuesta.recupera;
            });
        },
        listartipo(page1, buscar1) {
            var url =
                "/api/tipomobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidotipo = respuesta.recupera;
            });
        },
        cambiarcuentacontable(){
            let tipoact = this.contenidotipoactivo.find(element => element.id_tipo_activo_mobiliario == this.tipo_activo);
            this.cuentacontable = tipoact.cuenta_contable_tipo_activo;
        },
        buscardatos() {
            var url =
                "/api/buscarvehiculomobiliario/" +
                this.$route.params.id;
            axios.get(url).then(res => {
                var respuesta = res.data.recupera[0];
                this.tipo_activo=respuesta.id_tipo_activo;
                this.identificador=respuesta.id_identificador;
                this.codigo_identificacion=respuesta.codigo_identificacion_vehiculo;
                this.codigo_anterior=respuesta.codigo_anterior_vehiculo;
                this.nombre_bien=respuesta.nombre_bien_vehiculo;
                this.descripcion=respuesta.descripcion_vehiculo;
                this.marca=respuesta.id_marca;
                this.modelo=respuesta.id_modelo;
                //this.serie=respuesta.serie_vehiculo;
                this.color=respuesta.id_color;
                this.color_secundario=respuesta.id_color_secundario;
                this.tipo=respuesta.id_tipo;
                //this.material=respuesta.id_material;
                this.ano_fabricacion=respuesta.ano_fabricacion_vehiculo;
                this.conservacion=respuesta.id_conservacion;
                this.mantenimiento=respuesta.id_mantenimiento;
                this.fechacompra=respuesta.fechacompra_vehiculo;
                this.costoadquisicion=respuesta.costoadquisicion_vehiculo;
                this.combustible=respuesta.combustible_vehiculo;
                this.motor=respuesta.motor_vehiculo
                this.placa=respuesta.placa_vehiculo;
                this.chasis=respuesta.chasis_vehiculo;
                this.kilometraje=respuesta.kilometraje_vehiculo;
                this.vehiculo=respuesta.vehiculo_vehiculo;
                this.ubicaciongeneral=respuesta.id_ubicacion_general;
                this.ubicacionespecifica=respuesta.id_ubicacion_especifica;
                this.custodio=respuesta.id_custodio;
                this.cuentacontable=respuesta.cuentacontable_vehiculo;
                this.observaciones=respuesta.observaciones_vehiculo;
                this.id_vehiculo=respuesta.id_vehiculo;
                this.imagen = respuesta.imagen1;
                if(this.imagen){
                  this.recuperaimagen = 1;
                }
            });
        },
        /*
         * Guardar los datos del formulario
         */
        guardar() {
            /*if (this.validar()) {
                return;
            }*/
            let formData = new FormData();
            formData.append("tipo_activo", this.tipo_activo);
            formData.append("identificador", this.identificador);
            formData.append("codigo_identificacion", this.codigo_identificacion);
            formData.append("codigo_anterior", this.codigo_anterior);
            formData.append("nombre_bien", this.nombre_bien);
            formData.append("descripcion", this.descripcion);
            formData.append("marca", this.marca);
            formData.append("modelo", this.modelo);
            formData.append("color", this.color);
            formData.append("color_secundario", this.color_secundario);
            formData.append("tipo", this.tipo);
            formData.append("ano_fabricacion", this.ano_fabricacion);
            formData.append("conservacion", this.conservacion);
            formData.append("mantenimiento", this.mantenimiento);
            formData.append("fechacompra", this.fechacompra);
            formData.append("costoadquisicion", this.costoadquisicion);
            formData.append("combustible", this.combustible);
            formData.append("motor", this.motor);
            formData.append("placa", this.placa);
            formData.append("chasis", this.chasis);
            formData.append("kilometraje", this.kilometraje);
            formData.append("vehiculo", this.vehiculo);
            formData.append("ubicaciongeneral", this.ubicaciongeneral);
            formData.append("ubicacionespecifica", this.ubicacionespecifica);
            formData.append("custodio", this.custodio);
            formData.append("cuentacontable", this.cuentacontable);
            formData.append("observaciones", this.observaciones);
            formData.append("id_empresa", this.usuario.id_empresa);
            formData.append("file_imagen", this.imagen);
            axios
                .post("/api/guardarvehiculomobiliario", formData)
                .then(res => {
                    
                        this.$vs.notify({
                            title: "Vehiculo Guardado",
                            text: "Registro guardado exitosamente",
                            color: "success"
                        });
                        this.$router.push("/mobiliario/vehiculo");
                        
                    
                })
                .catch(err => {
                    console.log(err);
                });
        },
        editar() {
            /*if (this.validar()) {
                return;
            }*/
            let formData = new FormData();
            formData.append("tipo_activo", this.tipo_activo);
            formData.append("identificador", this.identificador);
            formData.append("codigo_identificacion", this.codigo_identificacion);
            formData.append("codigo_anterior", this.codigo_anterior);
            formData.append("nombre_bien", this.nombre_bien);
            formData.append("descripcion", this.descripcion);
            formData.append("marca", this.marca);
            formData.append("modelo", this.modelo);
            formData.append("color", this.color);
            formData.append("color_secundario", this.color_secundario);
            formData.append("tipo", this.tipo);
            formData.append("ano_fabricacion", this.ano_fabricacion);
            formData.append("conservacion", this.conservacion);
            formData.append("mantenimiento", this.mantenimiento);
            formData.append("fechacompra", this.fechacompra);
            formData.append("costoadquisicion", this.costoadquisicion);
            formData.append("combustible", this.combustible);
            formData.append("motor", this.motor);
            formData.append("placa", this.placa);
            formData.append("chasis", this.chasis);
            formData.append("kilometraje", this.kilometraje);
            formData.append("vehiculo", this.vehiculo);
            formData.append("ubicaciongeneral", this.ubicaciongeneral);
            formData.append("ubicacionespecifica", this.ubicacionespecifica);
            formData.append("custodio", this.custodio);
            formData.append("cuentacontable", this.cuentacontable);
            formData.append("observaciones", this.observaciones);
            formData.append("id_vehiculo", this.id_vehiculo);
            formData.append("id_empresa", this.usuario.id_empresa);
            formData.append("file_imagen", this.imagen);
            axios
                .post("/api/editarvehiculomobiliario", {
                    tipo_activo: this.tipo_activo,
                    identificador: this.identificador,
                    codigo_identificacion: this.codigo_identificacion,
                    codigo_anterior: this.codigo_anterior,
                    nombre_bien: this.nombre_bien,
                    descripcion: this.descripcion,
                    marca: this.marca,
                    modelo: this.modelo,
                    //serie: this.serie,
                    color: this.color,
                    color_secundario: this.color_secundario,
                    tipo: this.tipo,
                    //material: this.material,
                    ano_fabricacion: this.ano_fabricacion,
                    conservacion: this.conservacion,
                    mantenimiento: this.mantenimiento,
                    fechacompra: this.fechacompra,
                    costoadquisicion: this.costoadquisicion,
                    combustible: this.combustible,
                    motor: this.motor,
                    placa: this.placa,
                    chasis: this.chasis,
                    kilometraje: this.kilometraje,
                    vehiculo: this.vehiculo,
                    ubicaciongeneral: this.ubicaciongeneral,
                    ubicacionespecifica: this.ubicacionespecifica,
                    custodio: this.custodio,
                    cuentacontable: this.cuentacontable,
                    observaciones: this.observaciones,
                    id_vehiculo: this.id_vehiculo
                })
                .then(res => {
                    
                        this.$vs.notify({
                            title: "Vehiculo Editado",
                            text: "Registro editado exitosamente",
                            color: "success"
                        });
                        this.$router.push("/mobiliario/vehiculo");
                        
                    
                })
                .catch(err => {
                    console.log(err);
                });
        },
        /*
         * elimina los campos de formulario
         */
        cancelar() {
            if (this.modalactive == true) {
                this.$emit("CloseCLient", this.clientsend);
            } else {
                this.$router.push("/mobiliario/vehiculo");
            }
        },
        /**
         * validación de solo números, permite solo el
         * ingreso de numeros en los formularios de registro
         */
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
        //Archivo de imagen de empresa
        agregarimagen() {
            this.recuperaimagen = 0;
            $(".seleccionarimagen").click();
        },
        seleccionarimagen(event) {
            this.recuperaimagen = 0;
            var allowedExtensions = /(.jpg|.jpeg|.png|.gif|.webp)$/i;
            if (!allowedExtensions.exec($(".seleccionarimagen").val())) {
                this.imagen = "";
                $(".seleccionarimagen").val("");
                this.$vs.notify({
                    time: 8000,
                    color: "danger",
                    title: "Formato inválido ",
                    text: "Solo se acepta archivos imagen"
                });
            } else {
                var tam = parseInt(event.target.files[0].size / 1024);
                if (tam > 1024) {
                    this.imagen = "";
                    $(".seleccionarimagen").val("");
                    this.$vs.notify({
                        time: 8000,
                        color: "danger",
                        title: "Formato inválido",
                        text: "El tamaño de la imagen no puede ser mayor a 1 MB"
                    });
                    return;
                }
                this.imagen = event.target.files[0];
                this.previsualizarimagen(this.imagen);
            }
        },
        eliminarimagen(event) {
            this.recuperaimagen = 0;
            this.imagen = "";
            $(".seleccionarimagen").val("");
        },
        previsualizarimagen(file) {
            this.recuperaimagen = 0;
            let reader = new FileReader();
            reader.onload = e => {
                this.imagenprevisualizar = e.target.result;
            };
            reader.readAsDataURL(file);
        },
    },
    mounted() {

        if(this.$route.params.id){
           //this.buscardatos();
        }
        
        
    }
};
</script>
<style lang="scss">
.txt-center > div > input {
    text-align: center;
}
.verimagen{
      overflow: hidden;
      padding: 0px;
      display: flex;
      justify-content: center;
      align-items: center;
      width: 100%;
      border-radius: 20px;
      background: rgba(255,255,255,.8)!important;
      -webkit-transition: all .3s ease;
    -moz-transition: all .3s ease;
    -ms-transition: all .3s ease;
    -o-transition: all .3s ease;
    transition: all .3s ease;
    border: 5px solid rgba(0,0,0,.3);
  }

  .imagenpre:hover{
    -moz-transform: scale(1.03);
    -webkit-transform: scale(1.03);
    -o-transform: scale(1.03);
    -ms-transform: scale(1.03);
    transform: scale(1.03);
  }
</style>