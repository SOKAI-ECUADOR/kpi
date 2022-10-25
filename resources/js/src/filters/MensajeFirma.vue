<template>
    <div>
        La firma electrónica esta pronto a caducarse.
        <span class="text-center block">fecha de expiracion {{fecha_expiracion | fecha}}</span>
    </div>
</template>
<script>
    import moment from 'moment';
    moment.locale("es");
    export default {
        data() {
            return {
                fecha_expiracion:'2021-12-20'
            }
        },
        filters:{
            fecha(data){
                let fecha = moment(data).format("LL");
                return fecha;
            }
        },
        computed: {
            usuario() {
                return this.$store.state.AppActiveUser;
            },
        },
        async created() {
            console.log(this.usuario);
            let fecha = await axios.get(route('api.fecha.firma'));
        }
    }
</script>
<style>
    .block{
        display: block;
    }
</style>

