<template>
    <vx-card>
        <div class="flex flex-wrap">
            <div class="sm:w-full md:w-1/3 w-1/3 " v-if="usuario.id_rol!=2">
                <div class="sm:pl-0 md:pl-3 sm:w-full pl-3">
                    <label for="sellers">Vendedor</label>
                    <v-select
                        id="sellers"
                        :options="Vendedor.VendedorList"
                        label="fullname"
                        v-model="Vendedor.selectedVendedor"
                        placeholder="Seleccione el Vendedor"
                    ></v-select>
                </div>
            </div>
        </div>
        <div class="flex flex-wrap mt-6">
            <vs-button color="success" @click="sendData" type="filled"
                >Generar</vs-button
            >
        </div>
    </vx-card>
</template>

<script>
import service from "../service";
import Datepicker from "vuejs-datepicker";
export default {
    name: "vendedor",
    data() {
        return {
            Vendedor: {
                VendedorList: [{ id: 0, fullname: "TODOS" }],
                selectedVendedor: { id: 0, fullname: "TODOS" }
            }
        };
    },
    computed: {
        idEmpresa() {
            let user = this.$store.state.AppActiveUser;
            return user.id_empresa;
        },
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        }
    },
    methods: {
        sendData() {
            let response = {
                vendedor:this.Vendedor.selectedVendedor
            };
            this.$emit("generateReport", response);
        }
    },
    components: {
        Datepicker
    },
    async mounted() {
        try{
            if(this.usuario.id_rol!==2){
                this.Vendedor.VendedorList.push(
                    ...(await service.sellers.getAll({
                        idEmpresa: this.idEmpresa
                    }))
                );
            }else{
                this.Vendedor.VendedorList.push(
                    ...(await service.sellers_usu.getAll({
                        idEmpresa: this.usuario.id
                    }))
                );
            }
        } catch (error) {
            console.error(error.message);
        }
    }
};
</script>