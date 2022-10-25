<!-- =========================================================================================
File Name: RegisterJWT.vue
Description: Register Page for JWT
----------------------------------------------------------------------------------------
Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>
  <form action="">
    <div class="clearfix">
      <vs-input 
        v-validate="'required|min:3'"
        data-vv-validate-on="blur"
        v-bind:label-placeholder = nombre
        name="nombres"
        v-bind:placeholder = nombre
        v-model="nombres"
        class="w-full"/>
      <span class="text-danger text-sm">{{ errors.first('nombres') }}</span>

      <vs-input
        v-validate="'required|min:3'"
        data-vv-validate-on="blur"
        v-bind:label-placeholder = apellido
        name="apellidos"
        v-bind:placeholder = apellido
        v-model="apellidos"
        class="w-full" />
      <span class="text-danger text-sm">{{ errors.first('apellidos') }}</span>

      <vs-input
        v-validate="'required|email'"
        data-vv-validate-on="blur"
        name="email"
        type="email"
        v-bind:label-placeholder= loginemail
        v-bind:placeholder= loginemail
        v-model="email"
        class="w-full mt-6" />
      <span class="text-danger text-sm">{{ errors.first('email') }}</span>

      <vs-input
        ref="password"
        type="password"
        data-vv-validate-on="blur"
        v-validate="'required|min:6|max:10'"
        name="password"
        autocomplete="passsokai"
        v-bind:label-placeholder = loginpass
        v-bind:placeholder= loginpass
        v-model="password"
        class="w-full mt-6" />
      <span class="text-danger text-sm">{{ errors.first('password') }}</span>

      <vs-input
        type="password"
        v-validate="'min:6|max:10|confirmed:password'"
        data-vv-validate-on="blur"
        data-vv-as="password"
        name="confirm_password"
        autocomplete="passsokai"
        v-bind:label-placeholder = loginrepass
        v-bind:placeholder = loginrepass
        v-model="confirm_password" 
        class="w-full mt-6" />
      <span class="text-danger text-sm">{{ errors.first('confirm_password') }}</span>

      <vs-checkbox v-model="isTermsConditionAccepted" class="mt-6">Acepto los términos y condiciones</vs-checkbox>
      <vs-button  type="border" to="/login" class="mt-6">{{ $t('logininiciar') }}</vs-button>
      <vs-button class="float-right mt-6 mb-5" @click="registerUserJWt" :disabled="!validateForm">{{ $t('loginregistrar') }}</vs-button>
    </div>
  </form>
</template>

<script>
export default {
    data() {
        return {
            nombres: '',
            apellidos:'',
            email: '',
            password: '',
            confirm_password: '',
            isTermsConditionAccepted: false,
            loginemail: this.$t("loginemail"),
            loginpass: this.$t("loginpass"),
            loginrepass: this.$t("loginrepass"),
            nombre: this.$t("nombre"),
            apellido: this.$t("apellido"),
        }
    },
    computed: {
        validateForm() {
            return !this.errors.any() && this.nombres != '' && this.apellidos != '' && this.email != '' && this.password != '' && this.confirm_password != '' && this.isTermsConditionAccepted === true;
        }
    },
    methods: {
        checkLogin() {
          // If user is already logged in notify
          if(this.$store.state.auth.isUserLoggedIn()) {

            // Close animation if passed as payload
            // this.$vs.loading.close()

            this.$vs.notify({
              title: 'Login Attempt',
              text: 'You are already logged in!',
              iconPack: 'feather',
              icon: 'icon-alert-circle',
              color: 'warning'
            })

            return false
          }
          return true
        },
        registerUserJWt() {
            // If form is not validated or user is already login return
            if (!this.validateForm || !this.checkLogin()) return
            const payload = {
              userDetails: {
                nombres: this.nombres,
                apellidos: this.apellidos,
                email: this.email,
                password: this.password,
                confirmPassword: this.confirm_password
              },
              notify: this.$vs.notify
            }
            this.$store.dispatch('auth/registerUserJWT', payload)
        }
    }
}
</script> 
