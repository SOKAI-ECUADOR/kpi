<!-- =========================================================================================
    File Name: DashboardEcommerce.vue
    Description: Dashboard - Ecommerce
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
      Author: Pixinvent
    Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->

<template>
    <div>
        <div class="vx-row">
            <!----------------------------------------------------------------  GRAFICOS ESTADISTICOS POR EMPRESA  ---------------------------------------------------------------->
            <vs-divider />
            <!-- PIE CLIENTES TOTALES -->
            <div
                class="vx-col md:w-2/5 sm:w-full w-full mt-base mb-0"
                v-if="usuario.id_rol != 2"
            >
                <vx-card title="CLIENTES NUEVOS" style="height: 98%;">
                    <!-- SLOT = ACTIONS -->
                    <template slot="actions">
                        <!--<change-time-duration-dropdown />-->
                    </template>
                    <div slot="no-body">
                        <!-- CHART -->
                        <vue-apex-charts
                            type="donut"
                            height="363"
                            class="mt-1 mb-1"
                            :options="analyticsData.ClientPie.chartOptions"
                            :series="clientData.series"
                        />
                        <!-- CHART DATA 
                        <ul class="mb-1" style="column-count: 2;">
                            <li v-for="customerData in clientData.analyticsData" :key="customerData.customerType" class="flex justify-between py-3 px-6 border d-theme-border-grey-light border-solid border-r-0 border-l-0 border-b-0">
                                <span class="flex items-center">
                                    <span class="inline-block h-3 w-3 rounded-full mr-2" :class="`bg-${customerData.color}`"></span>
                                    <span class="font-semibold">{{ customerData.customerType }}</span>
                                </span>
                                <span>{{ customerData.counts }}</span>
                            </li>
                        </ul>-->
                    </div>
                </vx-card>
            </div>
            <!-- BAR VENTAS TOTALES -->
            <div
                class="vx-col md:w-3/5 sm:w-full w-full mt-base mb-2"
                v-if="usuario.id_rol != 2"
            >
                <vx-card title="VENTAS TOTALES">
                    <div class="flex">
                        <span class="flex items-center ml-5"
                            ><div
                                class="h-3 w-3 rounded-full mr-1 bg-primary"
                            ></div>
                            <span>
                                Total Ventas: {{ ventasTotalesBartotal }}
                            </span></span
                        >
                    </div>
                    <vue-apex-charts
                        type="bar"
                        height="315"
                        :options="analyticsData.VentasTotalesBar.chartOptions"
                        :series="ventasTotalesBar"
                    />
                    <!--<vue-apex-charts type=radialBar height=315 :options="analyticsData.VentasTotalesBar.chartOptions" :series="ventasTotalesBar" />-->
                </vx-card>
            </div>
            <!-- BAR VENTAS POR VENDEDOR -->
            <div
                class="vx-col  md:w-4/5  sm:w-full w-full mb-3"
                v-if="usuario.id_rol != 2"
            >
                <vx-card>
                    <h4>VENTAS POR VENDEDOR</h4>
                    <!-- <div class="flex">
                        <span class="flex items-center"><div class="h-3 w-3 rounded-full mr-1 bg-primary"></div><span>Numero de Ventas</span></span>
                    </div>-->
                    <vue-apex-charts
                        type="bar"
                        height="408"
                        :options="analyticsData.VentasVendedorBar.chartOptions"
                        :series="ventasVendedorBar"
                    />
                </vx-card>
            </div>
            <!-- CARDS CUENTAS- -->
            <div
                class="vx-col  md:w-1/5 sm:w-full w-full mb-3 "
                v-if="usuario.id_rol != 2"
            >
                <!-- CUENTAS POR PAGAR- -->
                <vx-card class="mb-8">
                    <div slot="no-body" v-if="supportTracker.analyticsData">
                        <div class="vx-row text-center">
                            <div
                                class="vx-col w-full lg:w-full md:w-full sm:w-full flex flex-col justify-between mb-0 lg:order-first md:order-last sm:order-first order-last"
                            >
                                <div class="mt-6">
                                    <h5>CUENTAS POR PAGAR</h5>
                                    <h2 class="font-bold text-3xl">
                                        <sup class="text-base mr-1">$</sup
                                        >{{ cuentasPagarRadio.total }}
                                    </h2>
                                </div>
                            </div>
                            <div
                                class="vx-col w-full lg:w-full md:w-full sm:w-full justify-center  mt-0 "
                            >
                                <vue-apex-charts
                                    type="radialBar"
                                    height="190"
                                    :options="
                                        analyticsData.CuentasPagarRadialBar
                                            .chartOptions
                                    "
                                    :series="cuentasPagarRadio.series"
                                />
                            </div>
                        </div>
                    </div>
                </vx-card>
                <!-- CUENTAS POR COBRAR- -->
                <vx-card class="mb-2 ">
                    <div slot="no-body" v-if="supportTracker.analyticsData">
                        <div class="vx-row text-center">
                            <div
                                class="vx-col w-full lg:w-full md:w-full sm:w-full flex flex-col justify-between mb-0 lg:order-first md:order-last sm:order-first order-last"
                            >
                                <div class="mt-6">
                                    <h5>CUENTAS POR COBRAR</h5>
                                    <h2 class="font-bold text-3xl">
                                        <sup class="text-base mr-1">$</sup
                                        >{{ cuentasCobrarRadio.total }}
                                    </h2>
                                </div>
                            </div>
                            <div
                                class="vx-col w-full lg:w-full md:w-full sm:w-full justify-center  mt-0 "
                            >
                                <vue-apex-charts
                                    type="radialBar"
                                    height="190"
                                    :options="
                                        analyticsData.CuentasPagarRadialBar
                                            .chartOptions
                                    "
                                    :series="cuentasCobrarRadio.series"
                                />
                            </div>
                        </div>
                    </div>
                </vx-card>
            </div>
            <!-- UTILIDAD BURTA Y NETA -->
            <div
                class="vx-col md:w-1/2 sm:w-full w-full mb-base"
                v-if="usuario.id_rol != 2"
            >
                <vx-card>
                    <div slot="no-body" class="p-6 pb-0">
                        <div
                            class="flex"
                            v-if="revenueComparisonLine.analyticsData"
                        >
                            <div class="mr-6">
                                <p
                                    class="mb-1 font-semibold"
                                    style="color:#00E396"
                                >
                                    Utilidad Bruta
                                </p>
                                <p class="text-3xl" style="color:#00E396">
                                    <sup class="text-base mr-1">$</sup
                                    >{{ utilidadesLine.utilitad_bruta_total }}
                                </p>
                            </div>
                            <div>
                                <p
                                    class="mb-1 font-semibold"
                                    style="color:#008FFB"
                                >
                                    Utilidad Neta
                                </p>
                                <p class="text-3xl" style="color:#008FFB">
                                    <sup class="text-base mr-1">$</sup
                                    >{{ utilidadesLine.utilitad_neta_total }}
                                </p>
                            </div>
                        </div>
                        <vue-apex-charts
                            type="line"
                            height="266"
                            :options="analyticsData.UtilidadesLine.chartOptions"
                            :series="utilidadesLine.series"
                        />
                    </div>
                </vx-card>
            </div>
            <!-- CHEQUES A SER COBRADOS -->
            <div
                class="vx-col  md:w-1/2 w-full mb-base"
                v-if="usuario.id_rol != 2"
            >
                <vx-card style="height: 100%">
                    <vs-table
                        stripe
                        max-items="5"
                        pagination
                        :data="chequesPagar"
                    >
                        <template slot="header">
                            <h3 style="margin-bottom: 1%;">
                                Cheques Próximos a Pagar
                            </h3>
                        </template>
                        <template slot="thead">
                            <vs-th style="width: 16%;">Emisión</vs-th>
                            <vs-th style="width: 16%;">Vencimiento</vs-th>
                            <vs-th class="text-center" style="width: 40%;"
                                >Beneficiario</vs-th
                            >
                            <vs-th class="text-center" style="width: 28%;"
                                >Valor</vs-th
                            >
                        </template>
                        <template slot-scope="{ data }">
                            <vs-tr :key="datos.index" v-for="datos in data">
                                <vs-td v-if="datos.fecha_creacion_asiento">{{
                                    datos.fecha_creacion_asiento
                                }}</vs-td>
                                <vs-td v-else>-</vs-td>
                                <vs-td v-if="datos.fecha_de_pago">{{
                                    datos.fecha_de_pago
                                }}</vs-td>
                                <vs-td v-else>-</vs-td>
                                <vs-td
                                    v-if="datos.beneficiario"
                                    class="text-center"
                                    >{{ datos.beneficiario }}</vs-td
                                >
                                <vs-td v-else class="text-center">-</vs-td>
                                <vs-td v-if="datos.haber" class="text-center"
                                    >${{ datos.haber }}</vs-td
                                >
                                <vs-td v-else class="text-center">-</vs-td>
                            </vs-tr>
                        </template>
                    </vs-table>
                </vx-card>
            </div>
            <div
                class="vx-col w-full sm:w-full md:w-full mb-base"
                style="text-align: center;"
            >
                <img
                    src="/images/inicio-image.jfif"
                    style="padding: 20px; height: auto; width: 75%;"
                />
            </div>
            <vs-divider />
            <!----------------------------------------------------------------  GRAFICOS ESTADISTICOS POR EMPRESA  ---------------------------------------------------------------->

            <!--
            <div class="vx-col w-full sm:w-1/2 md:w-1/2 lg:w-1/4 xl:w-1/4 mb-base">
                <statistics-card-line
                  v-if="subscribersGained.analyticsData"
                  icon="UsersIcon"
                  :statistic="subscribersGained.analyticsData.subscribers | k_formatter"
                  statisticTitle="Subscribers Gained"
                  :chartData="subscribersGained.series"
                  type='area' />
            </div>
            <div class="vx-col w-full sm:w-1/2 md:w-1/2 lg:w-1/4 xl:w-1/4 mb-base">
                <statistics-card-line
                  v-if="revenueGenerated.analyticsData"
                  icon="DollarSignIcon"
                  :statistic="revenueGenerated.analyticsData.revenue | k_formatter"
                  statisticTitle="Revenue Generated"
                  :chartData="revenueGenerated.series"
                  color='success'
                  type='area' />
            </div>
            <div class="vx-col w-full sm:w-1/2 md:w-1/2 lg:w-1/4 xl:w-1/4 mb-base">
                <statistics-card-line
                  v-if="quarterlySales.analyticsData"
                  icon="ShoppingCartIcon"
                  :statistic="quarterlySales.analyticsData.sales"
                  statisticTitle="Quarterly Sales"
                  :chartData="quarterlySales.series"
                  color='danger'
                  type='area' />
            </div>
            <div class="vx-col w-full sm:w-1/2 md:w-1/2 lg:w-1/4 xl:w-1/4 mb-base">
                <statistics-card-line
                  v-if="ordersRecevied.analyticsData"
                  icon="ShoppingBagIcon"
                  :statistic="ordersRecevied.analyticsData.orders | k_formatter"
                  statisticTitle="Orders Received"
                  :chartData="ordersRecevied.series"
                  color='warning'
                  type='area' />
            </div>-->
        </div>
        <!--<div class="vx-row">
            <div class="vx-col w-full md:w-2/3 mb-base">
                <vx-card title="Revenue">
                    <template slot="actions">
                        <feather-icon icon="SettingsIcon" svgClasses="w-6 h-6 text-grey"></feather-icon>
                    </template>
                    <div slot="no-body" class="p-6 pb-0">
                        <div class="flex" v-if="revenueComparisonLine.analyticsData">
                            <div class="mr-6">
                                <p class="mb-1 font-semibold">This Month</p>
                                <p class="text-3xl text-success"><sup class="text-base mr-1">$</sup>{{ revenueComparisonLine.analyticsData.thisMonth.toLocaleString() }}</p>
                            </div>
                            <div>
                                <p class="mb-1 font-semibold">Last Month</p>
                                <p class="text-3xl"><sup class="text-base mr-1">$</sup>{{ revenueComparisonLine.analyticsData.lastMonth.toLocaleString() }}</p>
                            </div>
                        </div>
                        <vue-apex-charts
                          type=line
                          height=266
                          :options="analyticsData.revenueComparisonLine.chartOptions"
                          :series="revenueComparisonLine.series" />
                    </div>
                </vx-card>
            </div>


            <div class="vx-col w-full md:w-1/3 mb-base">
                <vx-card title="Goal Overview">
                    <template slot="actions">
                        <feather-icon icon="HelpCircleIcon" svgClasses="w-6 h-6 text-grey"></feather-icon>
                    </template>
                    <template slot="no-body">
                        <div class="mt-10">
                            <vue-apex-charts type=radialBar height=240 :options="analyticsData.goalOverviewRadialBar.chartOptions" :series="goalOverview.series" />
                        </div>
                    </template>
                    <div class="flex justify-between text-center" slot="no-body-bottom">
                        <div class="w-1/2 border border-solid d-theme-border-grey-light border-r-0 border-b-0 border-l-0">
                            <p class="mt-4">Completed</p>
                            <p class="mb-4 text-3xl font-semibold">786,617</p>
                        </div>
                        <div class="w-1/2 border border-solid d-theme-border-grey-light border-r-0 border-b-0">
                            <p class="mt-4">In Progress</p>
                            <p class="mb-4 text-3xl font-semibold">13,561</p>
                        </div>
                    </div>
                </vx-card>
            </div>
        </div>

        <div class="vx-row">

            <div class="vx-col w-full md:w-1/3 lg:w-1/3 xl:w-1/3 mb-base">
                <vx-card title="Browser Statistics">
                    <div v-for="(browser, index) in browserStatistics" :key="browser.id" :class="{'mt-4': index}">
                        <div class="flex justify-between">
                            <div class="flex flex-col">
                                <span class="mb-1">{{ browser.name }}</span>
                                <h4>{{ browser.ratio }}%</h4>
                            </div>
                            <div class="flex flex-col text-right">
                                <span class="flex -mr-1">
                                    <span class="mr-1">{{ browser.comparedResult }}</span>
                                    <feather-icon :icon=" browser.comparedResult < 0 ? 'ArrowDownIcon' : 'ArrowUpIcon'" :svgClasses="[browser.comparedResult < 0 ? 'text-danger' : 'text-success'  ,'stroke-current h-4 w-4 mb-1 mr-1']"></feather-icon>
                                </span>
                                <span class="text-grey">{{ browser.time | time(true) }}</span>
                            </div>
                        </div>
                        <vs-progress :percent="browser.ratio"></vs-progress>
                    </div>
                </vx-card>
            </div>

            <div class="vx-col w-full md:w-2/3 mb-base">
                <vx-card title="Client Retention">
                    <div class="flex">
                        <span class="flex items-center"><div class="h-3 w-3 rounded-full mr-1 bg-primary"></div><span>New Clients</span></span>
                        <span class="flex items-center ml-4"><div class="h-3 w-3 rounded-full mr-1 bg-danger"></div><span>Retained Clients</span></span>
                    </div>
                    <vue-apex-charts type=bar height=277 :options="analyticsData.clientRetentionBar.chartOptions" :series="clientRetentionBar.series" />
                </vx-card>
            </div>
        </div>

        <div class="vx-row">
            <div class="vx-col w-full lg:w-1/3 lg:mt-0 mt-base">
                <vx-card title="Sessions By Device">
                    <template slot="actions">
                        <change-time-duration-dropdown />
                    </template>

                    <div slot="no-body">
                        <vue-apex-charts class="mt-6 mb-8" type=donut height=325 :options="analyticsData.sessionsByDeviceDonut.chartOptions" :series="sessionsData.series" />
                    </div>

                    <ul class="mt-6">
                        <li v-for="deviceData in sessionsData.analyticsData" :key="deviceData.device" class="flex mb-3">
                            <feather-icon :icon="deviceData.icon" :svgClasses="[`h-5 w-5 stroke-current text-${deviceData.color}`]"></feather-icon>
                            <span class="ml-2 inline-block font-semibold">{{ deviceData.device }}</span>
                            <span class="mx-2">-</span>
                            <span class="mr-4">{{ deviceData.sessionsPercentage }}%</span>
                            <div class="ml-auto flex -mr-1">
                            <span class="mr-1">{{ deviceData.comparedResultPercentage }}%</span>
                            <feather-icon :icon=" deviceData.comparedResultPercentage < 0 ? 'ArrowDownIcon' : 'ArrowUpIcon'" :svgClasses="[deviceData.comparedResultPercentage < 0 ? 'text-danger' : 'text-success'  ,'stroke-current h-4 w-4 mb-1 mr-1']"></feather-icon>
                            </div>
                        </li>
                    </ul>
                </vx-card>
            </div>

            <div class="vx-col w-full lg:w-1/3 lg:mt-0 mt-base">
                <vx-card title="Chat" class="overflow-hidden">
                    <template slot="no-body">
                        <div class="chat-card-log">
                            <VuePerfectScrollbar ref="chatLogPS" class="scroll-area pt-6 px-6" :settings="settings">
                                <ul ref="chatLog">
                                        <li class="flex items-start" :class="{'flex-row-reverse': msg.isSent, 'mt-4': index}" v-for="(msg, index) in chatLog" :key="index">
                                            <vs-avatar size="40px" class="m-0 flex-shrink-0" :class="msg.isSent ? 'ml-5' : 'mr-5'" :src="msg.senderImg"></vs-avatar>
                                            <div class="msg relative bg-white shadow-md py-3 px-4 mb-2 rounded-lg max-w-md" :class="{'chat-sent-msg bg-primary-gradient text-white': msg.isSent, 'border border-solid d-theme-border-grey-light': !msg.isSent}">
                                                <span>{{ msg.msg }}</span>
                                            </div>
                                        </li>
                                </ul>
                            </VuePerfectScrollbar>
                        </div>
                        <div class="flex bg-white chat-input-container p-6">
                            <vs-input class="mr-3 w-full" v-model="chatMsgInput" @keyup.enter="chatMsgInput = ''" placeholder="Type Your Message" ></vs-input>
                            <vs-button icon-pack="feather" icon="icon-send" @click="chatMsgInput = ''"></vs-button>
                        </div>
                    </template>
                </vx-card>
            </div>



            <div class="vx-col w-full lg:w-1/3 lg:mt-0 mt-base">
                <vx-card title="Customers">
                    <template slot="actions">
                        <change-time-duration-dropdown />
                    </template>

                    <div slot="no-body">
                        <vue-apex-charts type=pie height=345 class="mt-10 mb-10" :options="analyticsData.customersPie.chartOptions" :series="customersData.series" />
                        <ul class="mb-1">
                            <li v-for="customerData in customersData.analyticsData" :key="customerData.customerType" class="flex justify-between py-3 px-6 border d-theme-border-grey-light border-solid border-r-0 border-l-0 border-b-0">
                                <span class="flex items-center">
                                    <span class="inline-block h-3 w-3 rounded-full mr-2" :class="`bg-${customerData.color}`"></span>
                                    <span class="font-semibold">{{ customerData.customerType }}</span>
                                </span>
                                <span>{{ customerData.counts }}</span>
                            </li>
                        </ul>
                    </div>
                </vx-card>
            </div>
            <div class="vx-col w-full md:w-1/2 lg:w-1/2 xl:w-1/2 mb-base">
                <vx-card title="Support Tracker">
                    <template slot="actions">
                        <change-time-duration-dropdown />
                    </template>
                    <div slot="no-body" v-if="supportTracker.analyticsData">
                        <div class="vx-row text-center">
                            <div class="vx-col w-full lg:w-1/5 md:w-full sm:w-1/5 flex flex-col justify-between mb-4 lg:order-first md:order-last sm:order-first order-last">
                                <div class="lg:ml-6 lg:mt-6 md:mt-0 md:ml-0 sm:ml-6 sm:mt-6">
                                    <h1 class="font-bold text-5xl">{{ supportTracker.analyticsData.openTickets }}</h1>
                                    <small>Tickets</small>
                                </div>
                            </div>
                            <div class="vx-col w-full lg:w-4/5 md:w-full sm:w-4/5 justify-center mx-auto lg:mt-0 md:mt-6 sm:mt-0 mt-6">
                                <vue-apex-charts type=radialBar height=385 :options="analyticsData.supportTrackerRadialBar.chartOptions" :series="supportTracker.series" />
                            </div>
                        </div>
                        <div class="flex flex-row justify-between px-8 pb-4">
                            <p class="text-center" v-for="(val, key) in supportTracker.analyticsData.meta" :key="key">
                              <span class="block">{{ key }}</span>
                              <span class="text-2xl font-semibold">{{ val }}</span>
                            </p>
                        </div>
                    </div>
                </vx-card>
            </div>
        </div>-->
    </div>
</template>

<script>
import VuePerfectScrollbar from "vue-perfect-scrollbar";
import VueApexCharts from "vue-apexcharts";
import StatisticsCardLine from "@/components/statistics-cards/StatisticsCardLine.vue";
import analyticsData from "./ui-elements/card/analyticsData.js";
import ChangeTimeDurationDropdown from "@/components/ChangeTimeDurationDropdown.vue";
const axios = require("axios");
export default {
    data() {
        return {
            //SOKAI data
            clientData: {},
            ventasTotalesBar: [],
            ventasTotalesBartotal: "",
            ventasVendedorBar: [],
            cuentasPagarRadio: [],
            cuentasCobrarRadio: [],
            utilidadesLine: [],
            chequesPagar: [],
            //fin SOKAI data
            subscribersGained: {},
            revenueGenerated: {},
            quarterlySales: {},
            ordersRecevied: {},

            revenueComparisonLine: {},
            goalOverview: {},

            browserStatistics: [],
            clientRetentionBar: {},
            supportTracker: {},

            sessionsData: {},
            chatLog: [],
            chatMsgInput: "",
            customersData: {},

            analyticsData: analyticsData,
            settings: {
                // perfectscrollbar settings
                maxScrollbarLength: 60,
                wheelSpeed: 0.6
            }
        };
    },
    components: {
        VueApexCharts,
        StatisticsCardLine,
        VuePerfectScrollbar,
        ChangeTimeDurationDropdown
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
        GetClientPie() {
            axios
                .get(`/api/graphic/cliente/${this.usuario.id_empresa}`)
                .then(res => {
                    this.clientData = res.data;
                });
        },
        GetVentasTotalesBar() {
            axios
                .get(`/api/graphic/ventasTotalesBar/${this.usuario.id_empresa}`)
                .then(res => {
                    this.ventasTotalesBar = res.data;
                    this.ventasTotalesBartotal = this.ventasTotalesBar[0].total;
                });
        },
        GetVentasVendedorBar() {
            axios
                .get(
                    `/api/graphic/ventasVendedorBar/${this.usuario.id_empresa}`
                )
                .then(res => {
                    this.ventasVendedorBar = res.data;
                });
        },
        GetCuentasPagarRadio() {
            axios
                .get(
                    `/api/graphic/cuentasPagarRadio/${this.usuario.id_empresa}`
                )
                .then(res => {
                    this.cuentasPagarRadio = res.data;
                });
        },
        GetCuentasCobrarRadio() {
            axios
                .get(
                    `/api/graphic/cuentasCobrarRadio/${this.usuario.id_empresa}`
                )
                .then(res => {
                    this.cuentasCobrarRadio = res.data;
                });
        },
        GetUtilidadesLine() {
            axios
                .get(`/api/graphic/utilidadesLine/${this.usuario.id_empresa}`)
                .then(res => {
                    this.utilidadesLine = res.data;
                });
        },
        GetChequesPagar() {
            axios
                .get(`/api/graphic/chequesPagar/${this.usuario.id_empresa}`)
                .then(res => {
                    this.chequesPagar = res.data;
                });
        }
    },
    mounted() {
        //this.$refs.chatLogPS.$el.scrollTop = this.$refs.chatLog.scrollHeight;
        this.GetClientPie();
        this.GetVentasTotalesBar();
        this.GetVentasVendedorBar();
        this.GetCuentasPagarRadio();
        this.GetCuentasCobrarRadio();
        this.GetUtilidadesLine();
        this.GetChequesPagar();
    },

    created() {
        // Subscribers gained - Statistics
        this.$http
            .get("/api/card/card-statistics/subscribers")
            .then(response => {
                this.subscribersGained = response.data;
            })
            .catch(error => {
                console.log(error);
            });

        // Revenue Generated
        this.$http
            .get("/api/card/card-statistics/revenue")
            .then(response => {
                this.revenueGenerated = response.data;
            })
            .catch(error => {
                console.log(error);
            });

        // Sales
        this.$http
            .get("/api/card/card-statistics/sales")
            .then(response => {
                this.quarterlySales = response.data;
            })
            .catch(error => {
                console.log(error);
            });

        // Orders - Statistics
        this.$http
            .get("/api/card/card-statistics/orders")
            .then(response => {
                this.ordersRecevied = response.data;
            })
            .catch(error => {
                console.log(error);
            });

        // Revenue Comparison
        this.$http
            .get("/api/card/card-analytics/revenue-comparison")
            .then(response => {
                this.revenueComparisonLine = response.data;
            })
            .catch(error => {
                console.log(error);
            });

        // Goal Overview
        this.$http
            .get("/api/card/card-analytics/goal-overview")
            .then(response => {
                this.goalOverview = response.data;
            })
            .catch(error => {
                console.log(error);
            });

        // Browser Analytics
        this.$http
            .get("/api/card/card-analytics/browser-analytics")
            .then(response => {
                this.browserStatistics = response.data;
            })
            .catch(error => {
                console.log(error);
            });

        // Client Retention
        this.$http
            .get("/api/card/card-analytics/client-retention")
            .then(response => {
                this.clientRetentionBar = response.data;
            })
            .catch(error => {
                console.log(error);
            });

        // Sessions By Device
        this.$http
            .get("/api/card/card-analytics/session-by-device")
            .then(response => {
                this.sessionsData = response.data;
            })
            .catch(error => {
                console.log(error);
            });

        // Chat Log
        this.$http
            .get("/api/chat/demo-1/log")
            .then(response => {
                this.chatLog = response.data;
            })
            .catch(error => {
                console.log(error);
            });

        // Customers
        this.$http
            .get("/api/card/card-analytics/customers")
            .then(response => {
                this.customersData = response.data;
            })
            .catch(error => {
                console.log(error);
            });

        // Support Tracker
        this.$http
            .get("/api/card/card-analytics/support-tracker")
            .then(response => {
                this.supportTracker = response.data;
            })
            .catch(error => {
                console.log(error);
            });
    }
};
</script>

<style lang="scss">
.chat-card-log {
    height: 400px;

    .chat-sent-msg {
        background-color: red !important;
    }
}
.vs-con-table .vs-table--pagination {
    margin-top: 0;
}
.txt-center > div > input {
    text-align: center;
}
.text-center > .vs-table-text {
    text-align: center !important;
    display: block;
}
</style>
