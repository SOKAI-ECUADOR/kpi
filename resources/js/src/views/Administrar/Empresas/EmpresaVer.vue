<template>
    <div class="vx-row">
        <div class="vx-col w-full ">
            <vx-card v-if="usuario.id_rol == 1">
                <EmpresaEditable></EmpresaEditable
            ></vx-card>
            <vx-card v-if="usuario.id_rol == 2">
                <EmpresaNoEditable></EmpresaNoEditable
            ></vx-card>
        </div>
    </div>
</template>
<script>
import { AgGridVue } from "ag-grid-vue";
import moment from "moment";
import EmpresaEditable from "./EmpresaEditable.vue";
import EmpresaNoEditable from "./EmpresaNoEditable.vue";
moment.locale("es");
const $ = require("jquery");
const axios = require("axios");
export default {
    components: {
        AgGridVue,
        EmpresaEditable,
        EmpresaNoEditable
    },
    filters: {
        fecha(data) {
            return moment(data).format("LL");
        }
    },
    computed: {
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        }
    },
    data() {
        return {
            datos: []
        };
    },
    methods: {},
    mounted() {}
};
</script>
<style>
input[type="”file”"]#nuestroinput {
    width: 0.1px;
    height: 0.1px;
    opacity: 0;
    overflow: hidden;
    position: absolute;
    z-index: -1;
}
label[for=" nuestroinput"] {
    font-size: 14px;
    font-weight: 600;
    color: #fff;
    background-color: #106ba0;
    display: inline-block;
    transition: all 0.5s;
    cursor: pointer;
    padding: 15px 40px !important;
    text-transform: uppercase;
    width: fit-content;
    text-align: center;
}
.imagenpre {
    max-height: 100%;
    cursor: pointer;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
    max-width: 100%;
}
.centimg {
    height: 225px;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.8) !important;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}

.verimagen {
    overflow: hidden;
    padding: 0px;
    height: 300px;
    height: 300px;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.8) !important;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
    border: 5px solid rgba(0, 0, 0, 0.3);
}

.imagenpre:hover {
    -moz-transform: scale(1.03);
    -webkit-transform: scale(1.03);
    -o-transform: scale(1.03);
    -ms-transform: scale(1.03);
    transform: scale(1.03);
}

.centimg:hover {
    background: rgba(255, 255, 255, 0.6) !important;
    cursor: pointer;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}

.centimg img {
    max-width: 100%;
    max-height: 100px;
    cursor: pointer;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}
.demo-alignment > * {
    margin-right: 1.5rem;
    margin-top: 0.8rem;
}
.text-danger {
    font-size: 13px;
}
.vs-dialog {
    max-width: 900px !important;
}
.estiloborrar {
    cursor: pointer;
    position: absolute;
    bottom: -21px;
    width: 100%;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}
.estiloborrar:hover {
    cursor: pointer;
    position: absolute;
    bottom: -21px;
    width: 100%;
    box-shadow: 0px -20px 20px 20px red;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}
.verimagen:hover .spanborrar {
    margin-bottom: 0px;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}
.spanborrar {
    margin-bottom: -999px;
    position: absolute;
    bottom: 29px;
    color: red;
    font-size: 44px;
    font-weight: bold;
    margin-left: -10px;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}
.estilo-vista {
    color: inherit;
    position: relative;
    padding: 0.4rem;
    border-radius: 5px;
    border: 1px solid rgba(0, 0, 0, 0.2);
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    -webkit-box-shadow: 0 0 0 0 rgba(0, 0, 0, 0.15);
    box-shadow: 0 0 0 0 rgba(0, 0, 0, 0.15);
    -webkit-transition: all 0.3s ease;
    transition: all 0.3s ease;
    width: 100%;
    min-height: 34px;
}
.text-center {
    justify-content: center;
}
</style>
