<template>
    <form v-on:submit.prevent="loginuser()">
        <div>
            <vs-input
                v-validate="'required|email|min:5'"
                data-vv-validate-on="blur"
                name="email"
                icon-no-border
                icon="icon icon-user"
                icon-pack="feather"
                v-bind:label-placeholder="loginemail"
                v-model="email"
                class="w-full"
                style="display: inline-block"
            />
            <span
                class="text-danger text-sm mt-3"
                v-show="errors.first('email')"
                style="display: block;"
                >Ingrese un Correo válido</span
            >
            <vs-input
                data-vv-validate-on="blur"
                v-validate="'required|min:6'"
                type="password"
                name="password"
                icon-no-border
                icon="icon icon-lock"
                icon-pack="feather"
                v-bind:label-placeholder="loginpass"
                v-model="password"
                class="w-full mt-5 pt-5"
                style="display: inline-block"
            />
            <span
                class="text-danger text-sm mt-3 mb-5"
                v-show="errors.first('password')"
                style="display: block;"
                >La contraseña debe tener almenos 6 dígitos</span
            >
            <vs-alert
                v-show="error"
                color="danger"
                title="ERROR DE AUTENTIFICACIÓN"
                active="true"
                class="text-center mt-5"
                style="height: 75px;"
                >{{ error_message }}</vs-alert
            >
            <div class="mt-5">
                <vs-checkbox v-model="sesion_mantener"
                    >Mantener Sesion</vs-checkbox
                >
            </div>
            <div class="flex flex-wrap justify-between mb-3">
                <vs-button
                    class="mt-5"
                    style="display: flex;background: #ff3300!important;"
                    :disabled="!validateForm"
                    @click="loginuser()"
                    >{{ $t("logininiciar") }}</vs-button
                >
            </div>
        </div>
        <button type="submit" style="display:none"></button>
    </form>
</template>

<script>
export default {
    data() {
        return {
            email: "",
            password: "",
            checkbox_remember_me: false,
            loginemail: this.$t("loginemail"),
            loginpass: this.$t("loginpass"),
            error: false,
            error_message: "CREDENCIALES INCORRECTAS",
            sesion_mantener: true
        };
    },
    computed: {
        validateForm() {
            return this.email != "" && this.password != "";
        }
    },
    methods: {
        loginuser() {
            if (this.errors.any()) {
                return;
            }
            this.error = false;
            this.$vs.loading();
            const payload = {
                checkbox_remember_me: this.checkbox_remember_me,
                userDetails: { email: this.email, password: this.password }
            };
            this.$store
                .dispatch("loginAction", payload)
                .then(res => {
                    this.$vs.loading.close();
                    //this.$router.push("/");
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                })
                .catch(error => {
                    //console.log(error.response.data.error);
                    if (error.response.data.error == "Unauthorized1") {
                        this.error_message = "CREDENCIALES INCORRECTAS";
                    } else if (error.response.data.error == "Unauthorized2") {
                        this.error_message = "EMPRESA INACTIVA";
                        this.$toast.error(
                            "Su empresa se encuentra inactiva, COMUNÍQUESE CON EL ADMINISTRADOR: soporte@sokai.com.ec - 0979092243",
                            {
                                position: "top-right",
                                timeout: false,
                                closeOnClick: true,
                                pauseOnFocusLoss: true,
                                pauseOnHover: true,
                                draggable: true,
                                draggablePercent: 0.6,
                                showCloseButtonOnHover: false,
                                hideProgressBar: false,
                                closeButton: "button",
                                icon: true,
                                rtl: false
                            }
                        );
                    } else if (error.response.data.error == "Unauthorized3") {
                        this.error_message = "USUARIO INACTIVO";
                        this.$toast.error(
                            "El Usuario que intenta ingresar esta inactivo, COMUNÍQUESE CON EL ADMINISTRADOR",
                            {
                                position: "top-right",
                                timeout: 10000,
                                closeOnClick: true,
                                pauseOnFocusLoss: true,
                                pauseOnHover: true,
                                draggable: true,
                                draggablePercent: 0.6,
                                showCloseButtonOnHover: true,
                                hideProgressBar: false,
                                closeButton: "button",
                                icon: true,
                                rtl: false
                            }
                        );
                    }
                    this.$vs.loading.close();
                    this.error = true;
                });
        },
        registerUser() {
            this.$router.push("/registro").catch(() => {});
        }
    }
};
</script>
