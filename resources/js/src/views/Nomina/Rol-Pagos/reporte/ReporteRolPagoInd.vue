<template>
    <div class="flex flex-wrap">
            <vs-button color="success" @click="sendData" type="filled" hidden
                >Generar</vs-button
            >
        </div>
</template>
<script>
import service from "../components";
export default {
    data(){
        return{
            invoice: {
                all:true
            }
        };
    },
    methods:{
        sendData() {
            let response = {
                all: this.invoice.all
            };
            this.$emit("generateReport", response);
        },
    }
}
</script>