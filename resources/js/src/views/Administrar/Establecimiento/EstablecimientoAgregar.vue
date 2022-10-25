<template>
    <div class="vx-row">
        <div class="vx-col w-full mb-base">
            <vx-card title="Información General">
                <!--========================Start form Establecimiento==============================-->
                <div class="vx-row">
                    <div class="vx-col sm:w-1/2 w-full mb-6">
                        <vs-input class="w-full" label="Nombre" v-model="nombre" maxlength="45"/>
                        <div v-show="error">
                            <span class="text-danger" v-for="err in errornombre" :key="err" v-text="err"></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-input class="w-full" label="Serie Establecimiento" placeholder="Eje:001" v-model="codigo" @keypress="solonumeros($event)" maxlength="3"/>
                        <div v-show="error">
                            <span class="text-danger" v-for="err in errorcodigo" :key="err" v-text="err"></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-input class="w-full" label="Urlweb" v-model="urlweb"/>
                    </div>
                    <div class="vx-col sm:w-2/5 w-full mb-6">
                        <vs-input class="w-full" label="Nombre comercial" v-model="nombre_comercial"/>
                        <div v-show="error">
                            <span class="text-danger" v-for="err in errorunombre_comercial" :key="err" v-text="err"></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-2/5 w-full mb-6">
                        <vs-input class="w-full" label="Direccion" v-model="direccion"/>
                        <div v-show="error">
                            <span class="text-danger" v-for="err in errordireccion" :key="err" v-text="err"></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <label class="vs-input--label">Estado</label>
                        <vs-checkbox icon-pack="feather" class="mt-3" icon="icon-check" v-model="radios1">
                            <template v-if="radios1">
                                <label class="vs-input--label" style="font-size: 14px;font-weight: bold;">Si</label>
                            </template>
                            <template v-else>
                                <label class="vs-input--label" style="font-size: 14px;font-weight: bold;">No</label>
                            </template>
                            | Activo
                        </vs-checkbox>
                    </div>
                    <div class="vx-col w-full">
                        <vs-button color="success" type="filled" @click="editar()" v-if="$route.params.id">GUARDAR</vs-button>
                        <vs-button color="success" type="filled" @click="guardar()" v-else>GUARDAR</vs-button>
                        <vs-button color="warning" type="filled" @click="borrar()">BORRAR</vs-button>
                        <vs-button color="danger" type="filled" to="/administrar/establecimiento">CANCELAR</vs-button>
                    </div>
                </div>
            </vx-card>
        </div>
    </div>
</template>

<script>
import { log } from "util";
const axios = require("axios");
export default {
    data() {
        return {
            nombre: "",
            codigo: "",
            urlweb: "",
            nombre_comercial: "",
            direccion: "",
            radios1: true,
            establecimiento: "SOLMOCONTADORES",
            activo: "activo",
            contenidoemisor: [],
            //ERRORES
            error: 0,
            errornombre: [],
            errorcodigo: [],
            errorunombre_comercial: [],
            errordireccion: [],
            errorestablecimiento: [],
            asignar: []
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
    methods: {
        guardar() {
            if (this.validar()) {
                return;
            }
            axios.post("/api/establecimientog", {
                id_empresa: this.usuario.id_empresa,
                nombre: this.nombre,
                codigo: this.codigo,
                urlweb: this.urlweb,
                nombre_comercial: this.nombre_comercial,
                direccion: this.direccion,
                radios1: this.radios1,
                establecimiento: this.establecimiento
            }).then(res => {
                this.$vs.notify({
                    text: "Registro guardado exitosamente",
                    color: "success"
                });
                this.$router.push("/administrar/establecimiento");
            }).catch(err => {
                console.log(err);
            });
        },
        cancelar() {
            this.$router.push("administrar/establecimiento");
        },
        editar() {
            if (this.validar()) {
                return;
            }
            axios.put("/api/establecimientoact", {
                id_empresa: this.usuario.id_empresa,
                id: this.$route.params.id,
                nombre: this.nombre,
                codigo: this.codigo,
                urlweb: this.urlweb,
                nombre_comercial: this.nombre_comercial,
                direccion: this.direccion,
                radios1: this.radios1,
                establecimiento: "SOLMOCONTADORES"
            }).then(res => {
                this.$vs.notify({
                    text: "Registro actualizado exitosamente",
                    color: "primary"
                });
                this.$router.push("/administrar/establecimiento");
            }).catch(err => {
                console.log(err);
            });
        },
        listar() {
            if (this.$route.params.id) {
                this.idrecupera = this.$route.params.id;
                var url = "/api/establecimientoabrir";
                axios.put(url, { id: this.idrecupera }).then(res => {
                    let data = res.data.recupera[0];
                    this.nombre = data.nombre;
                    this.codigo = data.codigo;
                    this.urlweb = data.urlweb;
                    this.nombre_comercial = data.nombre_comercial;
                    this.direccion = data.direccion;
                    if(data.estado==1){
                        this.radios1 = true;
                    }else{
                        this.radios1 = false;
                    }
                }).catch(err => {
                    console.log(err);
                });
            } else {
                this.idrecupera = null;
            }
        },
        borrar() {
            this.nombre = "";
            this.codigo = "";
            this.urlweb = "";
            this.nombre_comercial = "";
            this.direccion = "";
            this.radios1 = "";
        },
        validar() {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            this.error = 0;
            this.errornombre = [];
            this.errorcodigo = [];
            this.errorunombre_comercial = [];
            this.errorudireccion = [];
            this.errorestablecimiento = [];

            if (!this.nombre) {
                this.errornombre.push("Campo obligatorio");
                this.error = 1;
            }

            if (this.codigo.length > 3) {
                this.errorcodigo.push("Campo menor de 3 dígitos");
                this.error = 1;
            }
            if (!this.nombre_comercial) {
                this.errorunombre_comercial.push("Campo obligatorio");
                this.error = 1;
            }
            if (!this.direccion) {
                this.errordireccion.push("Campo obligatorio");
                this.error = 1;
            }
            if (!this.establecimiento) {
                this.errorestablecimiento.push("Campo obligatorio");
                this.error = 1;
            }
            return this.error;
        },
        solonumeros: function($event) {
            var num = /^\d*\.?\d*$/;
            if (
                $event.charCode === 0 ||
                num.test(String.fromCharCode($event.charCode))
            ) {
                return true;
            } else {
                $event.preventDefault();
            }
        }
    },
    mounted() {
        if (this.$route.params.id) {
            var id = this.$route.params.id;
            this.listar();
        }
    }
};
</script>
