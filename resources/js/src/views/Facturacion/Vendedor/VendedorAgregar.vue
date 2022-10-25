<template>
    <div class="vx-row">
        <div class="vx-col w-full mb-base">
            <vx-card title="Información General">
                <div class="vx-row">
                    <div class="vx-col sm:w-1/6 w-full mb-6">
                        <vs-input
                            class="w-full"
                            v-if="tipocod"
                            label="Código"
                            v-model="codigo_vendedor"
                            maxlength="15"
                        />
                        <vs-input
                            class="w-full"
                            v-else
                            label="Código"
                            disabled
                            :value="codigo_vendedor"
                        />
                    </div>
                    <div class="vx-col sm:w-1/2 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Nombre"
                            v-model="nombre_vendedor"
                        />
                        <div v-show="error">
                            <span
                                class="text-danger"
                                v-for="err in errornombre_vendedor"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/2 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Email"
                            v-model="email_vendedor"
                        />
                        <div v-show="error">
                            <span
                                class="text-danger"
                                v-for="err in erroremail"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>

                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <label class="vs-input--label">Usuario</label>
                        <vs-select
                            :disabled="this.usuario.id_rol == 2"
                            placeholder="buscar"
                            autocomplete
                            class="selectExample w-full"
                            v-model="usuarios"
                        >
                            <vs-select-item
                                :key="res.id"
                                :value="res.id"
                                :text="res.nombres"
                                v-for="res in vende_usuario"
                            />
                        </vs-select>
                        <div v-show="error">
                            <span
                                class="text-danger"
                                v-for="err in error_usu_vende"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>

                    <div class="vx-col w-full">
                        <vs-button
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
                        >
                        <vs-button
                            color="warning"
                            type="filled"
                            @click="borrar()"
                            >BORRAR</vs-button
                        >
                        <vs-button
                            color="danger"
                            type="filled"
                            to="/facturacion/vendedor"
                            v-if="1"
                            @click="cancelar(1)"
                            >CANCELAR</vs-button
                        >
                        <vs-button
                            color="danger"
                            type="filled"
                            to="/facturacion/vendedor"
                            v-else
                            >CANCELAR</vs-button
                        >
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
            //codigo automatico
            tipocod: 0,
            codigovend: [],
            //fin
            codigo_vendedor: "",
            nombre_vendedor: "",
            email_vendedor: "",
            usuarios: "",
            contenido: [],
            vende_usuario: [],
            //ERRORES
            error: 0,

            //errorcodigo_vendedor: [],
            errornombre_vendedor: [],
            erroremail: [],
            error_usu_vende: []
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
        leercodigoprov() {
            if (!this.$route.params.id) {
                axios
                    .get("/api/codigovend?id=" + this.usuario.id_empresa)
                    .then(res => {
                        this.codigovend = res.data;
                        if (this.codigovend == "vacio") {
                            this.tipocod = 1;
                        } else {
                            this.tipocod = 0;
                            this.codigo_vendedor = this.codigovend;
                        }
                    });
                this.usuarios = this.usuario.id;
            }
        },
        getUserVendedor() {
            let me = this;
            var url = "/api/grupo_user/" + this.usuario.id_empresa;
            axios
                .get(url)
                .then(function(response) {
                    me.vende_usuario = response.data;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        /*
         * Guardar los datos del formulario
         */
        guardar() {
            if (this.validar()) {
                return;
            }
            axios
                .post("/api/guardarvendedorcliente", {
                    codigo_vendedor: this.codigo_vendedor,
                    nombre_vendedor: this.nombre_vendedor,
                    email_vendedor: this.email_vendedor,
                    usuarios: this.usuarios,
                    empresa: this.usuario.id_empresa
                })
                .then(res => {
                    if (res.data == "error_nombreVendedor") {
                        this.$vs.notify({
                            title: "Error de Registro",
                            text: "El Vendedor ya existe",
                            color: "danger"
                        });
                    } else {
                        this.$vs.notify({
                            title: "Vendedor Guardado",
                            text: "Registro guardado exitosamente",
                            color: "success"
                        });
                        this.$router.push("/facturacion/vendedor");
                    }
                })
                .catch(err => {
                    console.log(err);
                });
        },
        cancelar() {
            this.$router.push("/facturacion/vendedor");
        },
        /*
         * edita los datos del formulario
         */
        editar() {
            if (this.validar()) {
                return;
            }
            axios
                .put("/api/editarvendedorcliente", {
                    id: this.$route.params.id,
                    codigo_vendedor: this.codigo_vendedor,
                    nombre_vendedor: this.nombre_vendedor,
                    email_vendedor: this.email_vendedor,
                    usuarios: this.usuarios,
                    empresa: this.usuario.id_empresa
                })
                .then(res => {
                    if (res.data == "error_nombreVendedor") {
                        this.$vs.notify({
                            title: "Error de Registro",
                            text: "El Vendedor ya existe",
                            color: "danger"
                        });
                    } else {
                        this.$vs.notify({
                            title: "Vendedor Guardado",
                            text: "Registro actualizado exitosamente",
                            color: "success"
                        });
                        this.$router.push("/facturacion/vendedor");
                    }
                })
                .catch(err => {
                    console.log(err);
                });
        },
        /*
         * Borrar los campos de formulario
         */
        borrar() {
            this.codigo_vendedor = "";
            this.nombre_vendedor = "";
            this.email_vendedor = "";
            this.usuarios = "";
        },
        /*
         * Lista los datos del vendedor en una tabla
         */
        listarvendedor(id) {
            axios
                .put("/api/vendedor/vercliente/", { id: id })
                .then(res => {
                    console.log(res.canton);
                    let data = res.data[0];
                    this.codigo_vendedor = data.codigo_vendedor;
                    this.nombre_vendedor = data.nombre_vendedor;
                    this.email_vendedor = data.email_vendedor;
                    this.usuarios = data.id_user;
                })
                .catch(err => {
                    console.log(err);
                });
        },
        /*
         * valida los datos del formulario
         */
        validar() {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            this.error = 0;
            //this.errorcodigo_vendedor = [];
            this.errornombre_vendedor = [];
            this.erroremail = [];

            if (!this.nombre_vendedor) {
                this.errornombre_vendedor.push("Campo obligatorio");
                this.error = 1;
            }

            if (!this.validaremail(this.email_vendedor)) {
                this.erroremail.push("Email no valido");
                this.error = 1;
            }

            if (!this.usuarios) {
                this.error_usu_vende.push("Campo obligatorio");
                this.error = 1;
            }

            return this.error;
        },
        /*
         * método de validación de email
         */
        validaremail(email_vendedor) {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email_vendedor);
        },
        /**
         * validación de solo números, permite solo el
         * ingreso de numeros en los formularios de registro
         */
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
            this.listarvendedor(id);
        }
        this.leercodigoprov();
        this.getUserVendedor();
    }
};
</script>
