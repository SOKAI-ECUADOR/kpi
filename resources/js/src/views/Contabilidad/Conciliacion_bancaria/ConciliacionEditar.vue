<template>
    <vx-card>
        <div class="vx-row">
            <div class="vx-col sm:w-1/2 w-full mb-6">
            <label class="vs-input--label">Cuenta Contable</label>
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="cuentaContable"
                                :value="idcuentaContable"
                                :disabled="true"
                            />
                            <!--<template slot="append">
                                <div class="append-text btn-addon">
                                    <vs-button
                                        color="primary"
                                        @click="popupActive=true"
                                        >Buscar</vs-button
                                    >
                                </div>
                            </template>-->
                        </vx-input-group>
            </div>
    
            <div class="vx-col sm:w-1/6 w-full mb-6">
                        <label class="vs-input--label">Fecha:</label>

                        <flat-pickr
                            class="w-full"
                            :config="configdateTimePicker"
                            v-model="fecha"
                            disabled
                            placeholder="Elegir Fecha"
                        />
                        
            </div>
            <div class="vx-col sm:w-1/5 w-full mb-6">
                        <label class="vs-input--label">.</label>

                        <vs-button
                            style="margin: 0px 1em;"
                            color="warning"
                            type="filled"
                            @click="actualizar()"
                            >Actualizar Registro</vs-button
                        >
                        
            </div>
            
        </div>
        <vs-tabs alignment="fixed" v-model="Tabnav">
            <vs-tab title="BasicInfo" label="Movimientos" icon-pack="feather" icon="icon-briefcase">
                <vs-table stripe :data="contenidoconc" style="font-size: 12px;width: 100%;">
                    <template slot="thead">
                            <vs-th class="table-header">Nro</vs-th>
                            <vs-th class="table-header">Fecha Reguistro</vs-th>
                            <vs-th class="table-header">Fecha Pago</vs-th>
                            <vs-th class="table-header">Nro Asiento</vs-th>
                            <vs-th class="table-header">Nro Comprobante</vs-th>
                            <vs-th class="table-header">Descripcion</vs-th>
                            <vs-th class="table-header">Debe</vs-th>
                            <vs-th class="table-header">Haber</vs-th>
                            <vs-th class="table-header">Conciliar <vs-checkbox v-model="conciliacion_todo" @click="conciliar_todo()" vs-value="1"></vs-checkbox></vs-th>
                            <!-- <vs-checkbox v-model="conciliacion_todo" @click="conciliar_todo()" vs-value="1"></vs-checkbox> -->

                    </template>
                    <template slot-scope="{ data }">
                            <vs-tr :key="datos.id_asientos" v-for="(datos, index) in data">
                                <td style="text-align:center!important;" >{{index+1}}</td>
                                <td style="text-align:center!important;" v-if="datos.fecha_conciliacion">{{datos.fecha_conciliacion |fecha}}</td>
                                <td style="text-align:center!important;" v-else>-</td>
                                <td style="text-align:center!important;" v-if="datos.fecha_de_pago">{{datos.fecha_de_pago}}</td>
                                <td style="text-align:center!important;" v-else>-</td>
                                <td style="text-align:center!important;" v-if="datos.codigo_comprobante">{{datos.codigo_comprobante}}</td>
                                <td style="text-align:center!important;" v-else>-</td>
                                <td style="text-align:center!important;" v-if="datos.no_documento">{{datos.no_documento}}</td>
                                <td style="text-align:center!important;" v-else>-</td>
                                <td style="text-align:left!important;" v-if="datos.concepto">{{datos.concepto}}</td>
                                <td style="text-align:center!important;" v-else>-</td>
                                <td style="text-align: right;" v-if="datos.debe">{{datos.debe |currency}}</td>
                                <td style="text-align: right;" v-else>{{0 |currency}}</td>
                                <td style="text-align: right;" v-if="datos.haber">{{datos.haber |currency}}</td>
                                <td style="text-align: right;" v-else>{{0 |currency}}</td>
                                <td style="width:80px!important;"><vs-checkbox v-model="datos.conciliación" vs-value="1" @click="eliminardetalle(index,datos.conciliación,datos.descripcion,datos.codigo_asiento)"></vs-checkbox></td>
                            </vs-tr>     
                    </template>
                </vs-table>
                <!-- {{contenidoconc}} -->
                
            </vs-tab>
            
            
            
            <vs-tab title="BasicInfo" label="Conciliacion" icon-pack="feather" icon="icon-book-open">
                <br>
                <div class="vx-row">
                    <div class="vx-col sm:w-1/2 w-full mb-2 text-center">
                        <h6>{{cuenta_banco}}</h6>
                    </div>
                    <div class="vx-col sm:w-1/2 w-full mb-2 text-center">
                        <h6>{{nombre_banco}}</h6>
                    </div>
                 </div>
                 {{existe}}
                 <br>
                <h5 style="text-align:center!important;" v-if="exist_cheque==true">Cheques girados y no Cobrados</h5>
                <vs-table stripe :data="contenidodetalle"  style="font-size: 12px;width: 100%;" v-if="exist_cheque==true">
                    <template slot="thead">
                            <vs-th class="table-header">Nro</vs-th>
                            <vs-th class="table-header">Fecha Reguistro</vs-th>
                            <vs-th class="table-header">Fecha Pago</vs-th>
                            <vs-th class="table-header">Nro Asiento</vs-th>
                            <vs-th class="table-header">Nro Comprobante</vs-th>
                            <vs-th class="table-header">Descripcion</vs-th>
                            <vs-th class="table-header">Valor</vs-th>
                    </template>
                    
                    <template slot-scope="{ data }">
                            <vs-tr :key="index" v-for="(datos, index) in data">
                                <td style="text-align:center!important;" >{{index+1}}</td>
                                <td style="text-align:center!important;" v-if="datos.fecha">{{datos.fecha |fecha}}</td>
                                <td style="text-align:center!important;" v-else>-</td>
                                <td style="text-align:center!important;" v-if="datos.fecha_de_pago">{{datos.fecha_de_pago}}</td>
                                <td style="text-align:center!important;" v-else>-</td>
                                <td style="text-align:center!important;" v-if="datos.codigo">{{datos.codigo}}</td>
                                <td style="text-align:center!important;" v-else>-</td>
                                <td style="text-align:center!important;" v-if="datos.no_documento">{{datos.no_documento}}</td>
                                <td style="text-align:center!important;" v-else>-</td>
                                <td style="text-align:center!important;" v-if="datos.concepto">{{datos.concepto}}</td>
                                <td style="text-align:center!important;" v-else>-</td>
                                <td style="text-align: right;width:95px!important;" v-if="datos.valor">{{datos.valor | currency}}</td>
                                <td style="text-align: right;width:95px!important;" v-else>-</td>

                            </vs-tr> 
                            <vs-tr style="border-top: 1px solid #ddd;font-size: 15px;">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <th style="text-align: right;"><h5>Total</h5></th>
                                <th style="text-align: right;"><h5>${{total_conc}}</h5></th>
                            </vs-tr>    
                    </template>
                </vs-table>
                <br>
                <h5 style="text-align:center!important;" v-if="exist_nota_debito==true">Notas Debito no reguistrados en Bancos</h5>
                <vs-table stripe :data="contenidonota_debito"  style="font-size: 12px;width: 100%;" v-if="exist_nota_debito==true">
                    <template slot="thead">
                            <vs-th class="table-header">Nro</vs-th>
                            <vs-th class="table-header">Fecha Reguistro</vs-th>
                            <vs-th class="table-header">Fecha Pago</vs-th>
                            <vs-th class="table-header">Nro Asiento</vs-th>
                            <vs-th class="table-header">Nro Comprobante</vs-th>
                            <vs-th class="table-header">Descripcion</vs-th>
                            <vs-th class="table-header">Valor</vs-th>
                    </template>
                    
                    <template slot-scope="{ data }">
                            <vs-tr :key="index" v-for="(datos, index) in data">
                                <td style="text-align:center!important;">{{index+1}}</td>
                                <td style="text-align:center!important;" v-if="datos.fecha">{{datos.fecha |fecha}}</td>
                                <td style="text-align:center!important;" v-else>-</td>
                                <td style="text-align:center!important;" v-if="datos.fecha_de_pago">{{datos.fecha_de_pago}}</td>
                                <td style="text-align:center!important;" v-else>-</td>
                                <td style="text-align:center!important;" v-if="datos.codigo">{{datos.codigo}}</td>
                                <td style="text-align:center!important;" v-else>-</td>
                                <td style="text-align:center!important;" v-if="datos.no_documento">{{datos.no_documento}}</td>
                                <td style="text-align:center!important;" v-else>-</td>
                                <td style="text-align:center!important;" v-if="datos.concepto">{{datos.concepto}}</td>
                                <td style="text-align:center!important;" v-else>-</td>
                                <td style="text-align: right;width:95px!important;" v-if="datos.valor">{{datos.valor | currency}}</td>
                                <td style="text-align: right;width:95px!important;" v-else>-</td>
                            </vs-tr> 
                            <vs-tr style="border-top: 1px solid #ddd;font-size: 15px;">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <th style="text-align: right;"><h5>Total</h5></th>
                                <th style="text-align: right;"><h5>${{total_nota_debito}}</h5></th>
                            </vs-tr>    
                    </template>
                </vs-table>
                 <br>

                <h5 style="text-align:center!important;" v-if="exist_trans==true">Transferencias no reguistradas en Bancos</h5>
                <vs-table stripe :data="contenidotransferencia"  style="font-size: 12px;width: 100%;" v-if="exist_trans==true">
                    <template slot="thead">
                            <vs-th class="table-header">Nro</vs-th>
                            <vs-th class="table-header">Fecha Reguistro</vs-th>
                            <vs-th class="table-header">Fecha Pago</vs-th>
                            <vs-th class="table-header">Nro Asiento</vs-th>
                            <vs-th class="table-header">Nro Comprobante</vs-th>
                            <vs-th class="table-header">Descripcion</vs-th>
                            <vs-th class="table-header">Valor</vs-th>
                    </template>
                    
                    <template slot-scope="{ data }">
                            <vs-tr :key="index" v-for="(datos, index) in data">
                                <td style="text-align:center!important;">{{index+1}}</td>
                                <td style="text-align:center!important;" v-if="datos.fecha">{{datos.fecha |fecha}}</td>
                                <td style="text-align:center!important;" v-else>-</td>
                                <td style="text-align:center!important;" v-if="datos.fecha_de_pago">{{datos.fecha_de_pago}}</td>
                                <td style="text-align:center!important;" v-else>-</td>
                                <td style="text-align:center!important;" v-if="datos.codigo">{{datos.codigo}}</td>
                                <td style="text-align:center!important;" v-else>-</td>
                                <td style="text-align:center!important;" v-if="datos.no_documento">{{datos.no_documento}}</td>
                                <td style="text-align:center!important;" v-else>-</td>
                                <td style="text-align:center!important;" v-if="datos.concepto">{{datos.concepto}}</td>
                                <td style="text-align:center!important;" v-else>-</td>
                                <td style="text-align: right;width:95px!important;" >{{datos.valor | currency}}</td>
                                
                            </vs-tr> 
                            <vs-tr style="border-top: 1px solid #ddd;font-size: 15px;">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <th style="text-align: right;"><h5>Total</h5></th>
                                <th style="text-align: right;"><h5>${{total_transf}}</h5></th>
                            </vs-tr>    
                    </template>
                </vs-table>

                <h5 style="text-align:center!important;" v-if="exist_depo==true">Depositos no reguistrados en Bancos</h5>
                <vs-table stripe :data="contenidodeposito"  style="font-size: 12px;width: 100%;" v-if="exist_depo==true">
                    <template slot="thead">
                            <vs-th class="table-header">Nro</vs-th>
                            <vs-th class="table-header">Fecha Reguistro</vs-th>
                            <vs-th class="table-header">Fecha Pago</vs-th>
                            <vs-th class="table-header">Nro Asiento</vs-th>
                            <vs-th class="table-header">Nro Comprobante</vs-th>
                            <vs-th class="table-header">Descripcion</vs-th>
                            <vs-th class="table-header">Valor</vs-th>
                    </template>
                    
                    <template slot-scope="{ data }">
                            <vs-tr :key="index" v-for="(datos, index) in data">
                                <td style="text-align:center!important;">{{index+1}}</td>
                                <td style="text-align:center!important;" v-if="datos.fecha">{{datos.fecha |fecha}}</td>
                                <td style="text-align:center!important;" v-else>-</td>
                                <td style="text-align:center!important;" v-if="datos.fecha_de_pago">{{datos.fecha_de_pago}}</td>
                                <td style="text-align:center!important;" v-else>-</td>
                                <td style="text-align:center!important;" v-if="datos.codigo">{{datos.codigo}}</td>
                                <td style="text-align:center!important;" v-else>-</td>
                                <td style="text-align:center!important;" v-if="datos.no_documento">{{datos.no_documento}}</td>
                                <td style="text-align:center!important;" v-else>-</td>
                                <td style="text-align:center!important;" v-if="datos.concepto">{{datos.concepto}}</td>
                                <td style="text-align:center!important;" v-else>-</td>
                                <td style="text-align: right;width:95px!important;" v-if="datos.valor">{{datos.valor | currency}}</td>
                                <td style="text-align: right;width:95px!important;" v-else>-</td>
                            </vs-tr> 
                            <vs-tr style="border-top: 1px solid #ddd;font-size: 15px;">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <th style="text-align: right;"><h5>Total</h5></th>
                                <th style="text-align: right;"><h5>${{total_depo}}</h5></th>
                            </vs-tr>    
                    </template>
                </vs-table>
                <br>
                <h5 style="text-align:center!important;" v-if="exist_nota_credito==true">Notas Credito no reguistrados en Bancos</h5>
                <vs-table stripe :data="contenidonota_credito"  style="font-size: 12px;width: 100%;" v-if="exist_nota_credito==true">
                    <template slot="thead">
                            <vs-th class="table-header">Nro</vs-th>
                            <vs-th class="table-header">Fecha Reguistro</vs-th>
                            <vs-th class="table-header">Fecha Pago</vs-th>
                            <vs-th class="table-header">Nro Asiento</vs-th>
                            <vs-th class="table-header">Nro Comprobante</vs-th>
                            <vs-th class="table-header">Descripcion</vs-th>
                            <vs-th class="table-header">Valor</vs-th>
                    </template>
                    
                    <template slot-scope="{ data }">
                            <vs-tr :key="index" v-for="(datos, index) in data">
                                <td style="text-align:center!important;">{{index+1}}</td>
                                <td style="text-align:center!important;" v-if="datos.fecha">{{datos.fecha |fecha}}</td>
                                <td style="text-align:center!important;" v-else>-</td>
                                <td style="text-align:center!important;" v-if="datos.fecha_de_pago">{{datos.fecha_de_pago}}</td>
                                <td style="text-align:center!important;" v-else>-</td>
                                <td style="text-align:center!important;" v-if="datos.codigo">{{datos.codigo}}</td>
                                <td style="text-align:center!important;" v-else>-</td>
                                <td style="text-align:center!important;" v-if="datos.no_documento">{{datos.no_documento}}</td>
                                <td style="text-align:center!important;" v-else>-</td>
                                <td style="text-align:center!important;" v-if="datos.concepto">{{datos.concepto}}</td>
                                <td style="text-align:center!important;" v-else>-</td>
                                <td style="text-align: right;width:95px!important;" v-if="datos.valor">{{datos.valor | currency}}</td>
                                <td style="text-align: right;width:95px!important;" v-else>-</td>
                            </vs-tr> 
                            <vs-tr style="border-top: 1px solid #ddd;font-size: 15px;">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <th style="text-align: right;"><h5>Total</h5></th>
                                <th style="text-align: right;"><h5>${{total_nota_credito}}</h5></th>
                            </vs-tr>    
                    </template>
                </vs-table>
                <br>
                <div class="vx-row">
                    <div class="vx-col sm:w-1/4 w-full mb-2 text-center">
                    <h6>Cheques no reguistrados en Libros</h6>
                    </div>
                    <div class="vx-col sm:w-1/12 w-full mb-2">
                    <vs-checkbox v-model="exist_cheque_libro" ></vs-checkbox>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-2">
                        <h6>Transferencias no reguistradas en Libros</h6>
                    </div>
                    <div class="vx-col sm:w-1/12 w-full mb-2">
                        <vs-checkbox v-model="exist_trans_libro" ></vs-checkbox>
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-2">
                        <h6>Depositos no reguistrados en Libros</h6>
                    </div>
                    <div class="vx-col sm:w-1/12 w-full mb-2">
                        <vs-checkbox v-model="exist_depo_libro" ></vs-checkbox>
                    </div>
                    <!--<div class="vx-col sm:w-6/12 w-full mb-2 text-center">
                    <vs-checkbox v-model="exist_trans_libro" ></vs-checkbox>
                    </div>-->
                </div>
                <h5 style="text-align:center!important;" v-if="exist_cheque_libro==true">Cheques no reguistrados en Libros</h5>
                <vs-table stripe :data="contenidocheque_libro"  style="font-size: 12px;width: 100%;" v-if="exist_cheque_libro==true">
                    <template slot="thead">
                            <vs-th class="table-header">Fecha Reguistro</vs-th>
                            <vs-th class="table-header">Fecha Pago</vs-th>
                            <!--<vs-th class="table-header">Nro Asiento</vs-th>-->
                            <vs-th class="table-header">Nro Comprobante</vs-th>
                            <vs-th class="table-header">Descripcion</vs-th>
                            <vs-th class="table-header">Valor</vs-th>
                            <vs-th class="table-header">Accion</vs-th>
                    </template>
                    
                    <template slot-scope="{ data }">
                            <vs-tr v-for="(tr, index) in data" :key="index">
                                <td :data="tr.fecha_reguistro" style="text-align:center!important;" >
                                    <flat-pickr class="w-full" :config="configdateTimePicker" v-model="tr.fecha_reguistro" placeholder="Elegir Fecha"/>
                                </td>
                                <td :data="tr.fecha_de_pago" style="text-align:center!important;" >
                                    <flat-pickr class="w-full" :config="configdateTimePicker" v-model="tr.fecha_de_pago" placeholder="Elegir Fecha"/>
                                </td>
                                <!--<td :data="tr.codigo_comprobante" style="text-align:center!important;" >
                                    <vs-input class="w-full" v-model="tr.codigo_comprobante"/>
                                </td>-->
                                <td :data="tr.no_documento" style="text-align:center!important;" >
                                    <vs-input class="w-full" v-model="tr.no_documento"/>
                                </td>
                                <td :data="tr.concepto" style="text-align:center!important;" >
                                    <vs-input class="w-full" v-model="tr.concepto"/>
                                </td>
                                <td :data="tr.valor" style="text-align: right;width:95px!important;">
                                    <vs-input class="w-full" v-model="tr.valor"/>
                                </td>
                                <td :data="tr.valor" style="text-align: right;width:95px!important;">
                                    <vs-button
                                        type="filled"
                                        v-if="usuario.id_rol == 1 && contenidocheque_libro.length > 1"
                                        color="danger"
                                        style="height: 1.5em;padding: 0px;width: 1.5em; margin:5px 0px;"
                                        @click="quitarcampoChequeLibro(index)"
                                        >x</vs-button
                                    >
                                    <!-- prettier-ignore -->
                                    <vs-button
                                        type="filled"
                                        style="height: 1.5em;padding: 0px;width: 1.5em; margin:5px 5px;"
                                        v-if="usuario.id_rol == 1 && (contenidocheque_libro.length === index + 1)"
                                        color="primary"
                                        @click="agregarcampoChequeLibro()"
                                        >+</vs-button
                                    >
                                </td>
                            </vs-tr> 
                            <vs-tr style="border-top: 1px solid #ddd;font-size: 15px;">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <th style="text-align: right;"><h5>Total</h5></th>
                                <th style="text-align: right;"><h5>${{total_cheque_libro}}</h5></th>
                                <td></td>
                            </vs-tr>    
                    </template>
                </vs-table>
                <h5 style="text-align:center!important;" v-if="exist_trans_libro==true">Transferencias no reguistradas  en Libros</h5>
                <vs-table stripe :data="contenidotransferencia_libro"  style="font-size: 12px;width: 100%;" v-if="exist_trans_libro==true">
                    <template slot="thead">
                            <vs-th class="table-header">Fecha Reguistro</vs-th>
                            <vs-th class="table-header">Fecha Pago</vs-th>
                            <!--<vs-th class="table-header">Nro Asiento</vs-th>-->
                            <vs-th class="table-header">Nro Comprobante</vs-th>
                            <vs-th class="table-header">Descripcion</vs-th>
                            <vs-th class="table-header">Valor</vs-th>
                            <vs-th class="table-header">Accion</vs-th>
                    </template>
                    
                    <template slot-scope="{ data }">
                            <vs-tr v-for="(tr, index) in data" :key="index">
                                <td :data="tr.fecha_reguistro" style="text-align:center!important;" >
                                    <flat-pickr class="w-full" :config="configdateTimePicker" v-model="tr.fecha_reguistro" placeholder="Elegir Fecha"/>
                                </td>
                                <td :data="tr.fecha_de_pago" style="text-align:center!important;" >
                                    <flat-pickr class="w-full" :config="configdateTimePicker" v-model="tr.fecha_de_pago" placeholder="Elegir Fecha"/>
                                </td>
                                <!--<td :data="tr.codigo_comprobante" style="text-align:center!important;" >
                                    <vs-input class="w-full" v-model="tr.codigo_comprobante"/>
                                </td>-->
                                <td :data="tr.no_documento" style="text-align:center!important;" >
                                    <vs-input class="w-full" v-model="tr.no_documento"/>
                                </td>
                                <td :data="tr.concepto" style="text-align:center!important;" >
                                    <vs-input class="w-full" v-model="tr.concepto"/>
                                </td>
                                <td :data="tr.valor" style="text-align: right;width:95px!important;">
                                    <vs-input class="w-full" v-model="tr.valor"/>
                                </td>
                                <td :data="tr.valor" style="text-align: right;width:95px!important;">
                                    <vs-button
                                        type="filled"
                                        v-if="usuario.id_rol == 1 && contenidotransferencia_libro.length > 1"
                                        color="danger"
                                        style="height: 1.5em;padding: 0px;width: 1.5em; margin:5px 0px;"
                                        @click="quitarcampoTransferenciaLibro(index)"
                                        >x</vs-button
                                    >
                                    <!-- prettier-ignore -->
                                    <vs-button
                                        type="filled"
                                        style="height: 1.5em;padding: 0px;width: 1.5em; margin:5px 5px;"
                                        v-if="usuario.id_rol == 1 && (contenidotransferencia_libro.length === index + 1)"
                                        color="primary"
                                        @click="agregarcampoTransferenciaLibro()"
                                        >+</vs-button
                                    >
                                </td>
                            </vs-tr> 
                            <vs-tr style="border-top: 1px solid #ddd;font-size: 15px;">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <th style="text-align: right;"><h5>Total</h5></th>
                                <th style="text-align: right;"><h5>${{total_transf_libro}}</h5></th>
                                <td></td>
                            </vs-tr>    
                    </template>
                </vs-table>
                <h5 style="text-align:center!important;" v-if="exist_depo_libro==true">Depositos no reguistrados  en Libros</h5>
                <vs-table stripe :data="contenidodeposito_libro"  style="font-size: 12px;width: 100%;" v-if="exist_depo_libro==true">
                    <template slot="thead">
                            <vs-th class="table-header">Fecha Reguistro</vs-th>
                            <vs-th class="table-header">Fecha Pago</vs-th>
                            <!--<vs-th class="table-header">Nro Asiento</vs-th>-->
                            <vs-th class="table-header">Nro Comprobante</vs-th>
                            <vs-th class="table-header">Descripcion</vs-th>
                            <vs-th class="table-header">Valor</vs-th>
                            <vs-th class="table-header">Accion</vs-th>
                    </template>
                    
                    <template slot-scope="{ data }">
                            <vs-tr v-for="(tr, index) in data" :key="index">
                                <td :data="tr.fecha_reguistro" style="text-align:center!important;" >
                                    <flat-pickr class="w-full" :config="configdateTimePicker" v-model="tr.fecha_reguistro" placeholder="Elegir Fecha"/>
                                </td>
                                <td :data="tr.fecha_de_pago" style="text-align:center!important;" >
                                    <flat-pickr class="w-full" :config="configdateTimePicker" v-model="tr.fecha_de_pago" placeholder="Elegir Fecha"/>
                                </td>
                                <!--<td :data="tr.codigo_comprobante" style="text-align:center!important;" >
                                    <vs-input class="w-full" v-model="tr.codigo_comprobante"/>
                                </td>-->
                                <td :data="tr.no_documento" style="text-align:center!important;" >
                                    <vs-input class="w-full" v-model="tr.no_documento"/>
                                </td>
                                <td :data="tr.concepto" style="text-align:center!important;" >
                                    <vs-input class="w-full" v-model="tr.concepto"/>
                                </td>
                                <td :data="tr.valor" style="text-align: right;width:95px!important;">
                                    <vs-input class="w-full" v-model="tr.valor"/>
                                </td>
                                <td :data="tr.valor" style="text-align: right;width:95px!important;">
                                    <vs-button
                                        type="filled"
                                        v-if="usuario.id_rol == 1 && contenidodeposito_libro.length > 1"
                                        color="danger"
                                        style="height: 1.5em;padding: 0px;width: 1.5em; margin:5px 0px;"
                                        @click="quitarcampoDepositoLibro(index)"
                                        >x</vs-button
                                    >
                                    <!-- prettier-ignore -->
                                    <vs-button
                                        type="filled"
                                        style="height: 1.5em;padding: 0px;width: 1.5em; margin:5px 5px;"
                                        v-if="usuario.id_rol == 1 && (contenidodeposito_libro.length === index + 1)"
                                        color="primary"
                                        @click="agregarcampoDepositoLibro()"
                                        >+</vs-button
                                    >
                                </td>
                            </vs-tr> 
                            <vs-tr style="border-top: 1px solid #ddd;font-size: 15px;">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <th style="text-align: right;"><h5>Total</h5></th>
                                <th style="text-align: right;"><h5>${{total_depo_libro}}</h5></th>
                                <td></td>
                            </vs-tr>    
                    </template>
                </vs-table>
                <div class="vx-row">
                    <!--<div class="vx-col sm:w-1/2 w-full">
                        
                    </div>
                    <div class="vx-col sm:w-1/2 w-full">
                        <vs-table hoverFlat class="w-full" :data="invoiceData">
                            <vs-tr>
                                <vs-th>Total</vs-th>
                                <vs-th></vs-th>
                            </vs-tr>
                            <vs-tr>
                                <vs-th>Saldo en Libros</vs-th>
                                <vs-th></vs-th>
                            </vs-tr>
                            <vs-tr>
                                <vs-th>Nuevo Saldo en Libros</vs-th>
                                <vs-th></vs-th>
                            </vs-tr>
                            <vs-tr>
                                <vs-th>Saldo en Banco</vs-th>
                                <vs-th></vs-th>
                            </vs-tr>
                        </vs-table>
                    </div>-->
                    <div class="vx-col sm:w-1/3 w-full mb-2 text-center">
                            <label class="vs-input--label">SALDO EN LIBROS</label>
                            <h1>{{saldo_libro_fixed}}</h1>
                    </div>
                    
                    <div class="vx-col sm:w-1/3 w-full mb-2 text-center">
                            <label class="vs-input--label">SALDO CONCILIADO</label>
                            <h1>{{total_saldo_libro}}</h1>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-2 text-center">
                            <label class="vs-input--label">SALDO EN BANCOS</label>
                            <vs-input class="w-full valores"  v-model="saldo_banco"  @blur="cambiarADecimal()"/>
                    </div>
                    
                    <!--<div class="vx-col sm:w-1/4 w-full mb-2 text-center">
                            <label class="vs-input--label">CHEQUES GIRADOS Y NO COBRADOS</label>
                            <h1>{{valor_select}}</h1>
                    </div>-->
                </div>
                <div>
                    
                    <vs-button
                        style="margin: 0px 1em;"
                        color="success"
                        type="filled"
                        :disabled="disabled_conc"
                        @click="guardar()"
                        >GUARDAR</vs-button
                    >
                    <vs-button
                        class="btnx"
                        color="danger"
                        type="filled"
                        to="/contabilidad/concillacion-bancaria"
                        >CANCELAR</vs-button
                    >
                </div>
                
            </vs-tab>
        </vs-tabs>

        
        

        
        <vs-popup
            title="Seleccione una Cuenta Contable"
            :active.sync="popupActive"
        >
        <div class="con-exemple-prompt">
                <vs-input
                    class="mb-4 md:mb-0 mr-4 w-full"
                    v-model="buscar1"
                    @keypress="listarcuenta(1, buscar1)"
                    v-bind:placeholder="i18nbuscar"
                />
                <vs-table
                    stripe
                    v-model="cuentaarray3"
                    @selected="handleSelected"
                    :data="contenidocuenta"
                >
                    <template slot="thead">
                        <vs-th>No.Cuenta</vs-th>
                        <vs-th>Tipo Cuenta</vs-th>
                    </template>
                    <template slot-scope="{ data }">
                                    <vs-tr
                                        :data="tr"
                                        :key="indextr"
                                        v-for="(tr, indextr) in data"
                                    >
                                        <vs-td :data="data[indextr].codcta">{{
                                            data[indextr].codcta
                                        }}</vs-td>
                                        <vs-td :data="data[indextr].nomcta">{{
                                            data[indextr].nomcta
                                        }}</vs-td>
                                    </vs-tr>
                    </template>
                </vs-table>
            </div>
        </vs-popup>
        <vs-popup title="Eliminar Reguistro" class="peque"  :active.sync="modal_eliminar">
            <h6>Desea Eliminar Este Reguistro</h6> 
            <br>
            <div>
                 <vs-button
                        style="margin: 0px 1em;"
                        color="success"
                        type="filled"
                        @click="eliminarconciliacion(id_conciliacion,Indexeliminar,tipo_libro)"
                        >Aceptar</vs-button
                    >
                    <vs-button
                        class="btnx"
                        color="danger"
                        type="filled"
                        @click="modal_eliminar=false"
                        >Cancelar</vs-button
                    >
            </div>
        </vs-popup>
    </vx-card>
</template>
<script>
import { FormWizard, TabContent } from "vue-form-wizard";
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
const axios = require("axios");
import moment from "moment";
moment.locale("es");
export default {
    data(){
        return{
            buscar1: "",
            offset1: 3,
            i18nbuscar: this.$t("i18nbuscar"),
            cuentaContable:"",
            idcuentaContable:"",
            contenidocuenta: [],
            popupActive:false,
            cuentaarray3:[],
            fecha:"",
            configdateTimePicker: {
                locale: SpanishLocale
            },
            // desbilita el boton de guardado
            disabled_conc:false,
            ///
            contenidoconc:[],
            saldo_libro:0.00,
            saldo_banco:"",
            Tabnav: 0,
            contenidodetalle:[],
            cuenta_banco:"",
            nombre_banco:"",
            contenidotransferencia:[],
            contenidodeposito:[],
            contenidonota_credito:[],
            contenidonota_debito:[],
            exist_cheque:false,
            exist_depo:false,
            exist_trans:false,
            exist_nota_credito:false,
            exist_nota_debito:false,
            exist_cheque_libro:false,
            exist_depo_libro:false,
            exist_trans_libro:false,
            conciliacion_todo:false,
            contenidocheque_libro:
            [
                {
                    fecha_reguistro:"",
                    fecha_de_pago:"",
                    codigo_comprobante:"",
                    no_documento:"",
                    concepto:"",
                    valor:"",
                    tipo:"Cheque en Libro"
                }
            ],
            contenidotransferencia_libro:
            [
                {
                    fecha_reguistro:"",
                    fecha_de_pago:"",
                    codigo_comprobante:"",
                    no_documento:"",
                    concepto:"",
                    valor:"",
                    tipo:"Transferencia en Libro"
                }
            ],
            contenidodeposito_libro:
            [
                {
                    fecha_reguistro:"",
                    fecha_de_pago:"",
                    codigo_comprobante:"",
                    no_documento:"",
                    concepto:"",
                    valor:"",
                    tipo:"Deposito en Libro"
                }
            ],
            contenidolistar:[],
            Indexeliminar:"",
            modal_eliminar:false,
            id_conciliacion:"",
            tipo_libro:"",
            contenidoactualizado:[],
            //errores
            error:0,
            errorcuentacont:[],
            errorfecha:[],
            errorbanco:[]
        };
    },
    filters:{
        fecha(data){
            
            return moment(data).format("Y-MM-DD");
        },
        upper: function (value) {
            return value.toUpperCase();
        }
    },
    components: {
        flatPickr,
        FormWizard,
        TabContent,
    },
    computed:{
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        },
        conciliacion(){
            if(this.fecha && !this.$route.params.id){
                this.traerConciliacion(this.idcuentaContable,this.fecha);
            }
        },
        valor_select(){
            var total = 0;
            this.contenidoconc.forEach(el => {
                if (el.conciliación!=null) {
                    if(el.debe==null){
                        total+=0+parseFloat(el.haber);
                    }else{
                        total+=parseFloat(el.debe)+0;
                    }
                    
                }
            });
            /*this.valor_real = total.toFixed(2);
            if(total.toFixed(2)<=0){
                this.descuento_porcentaje = "";
                this.descuento_pago = "";
            }*/
            return total.toFixed(2);
        },
        existe(){
            if(this.contenidodetalle.length>0){
                this.exist_cheque=true;
                console.log(this.exist_cheque+"Cheuqe"+this.contenidodetalle.length);
            }else{
                this.exist_cheque=false;
            }
            if(this.contenidotransferencia.length>0){
                this.exist_trans=true;
            }else{
                this.exist_trans=false;
            }
            if(this.contenidodeposito.length>0){
                this.exist_depo=true;
                
            }else{
                this.exist_depo=false;
            }
            if(this.contenidonota_credito.length>0){
                this.exist_nota_credito=true;
                
            }else{
                this.exist_nota_credito=false;
            }
            if(this.contenidonota_debito.length>0){
                this.exist_nota_debito=true;
                
            }else{
                this.exist_nota_debito=false;
            }
            if(this.exist_cheque_libro==true && this.contenidocheque_libro.length<=0){
                this.contenidocheque_libro.push({
                    fecha_reguistro:"",
                    fecha_de_pago:"",
                    //codigo_comprobante:"",
                    no_documento:"",
                    concepto:"",
                    valor:"",
                    tipo:"Cheque en Libro"
                });
            }
            if(this.exist_trans_libro==true && this.contenidotransferencia_libro.length<=0){
                this.contenidotransferencia_libro.push({
                    fecha_reguistro:"",
                    fecha_de_pago:"",
                    //codigo_comprobante:"",
                    no_documento:"",
                    concepto:"",
                    valor:"",
                    tipo:"Transferencia en Libro"
                });
            }
            if(this.exist_depo_libro==true && this.contenidodeposito_libro.length<=0){
                this.contenidodeposito_libro.push({
                    fecha_reguistro:"",
                    fecha_de_pago:"",
                    //codigo_comprobante:"",
                    no_documento:"",
                    concepto:"",
                    valor:"",
                    tipo:"Transferencia en Libro"
                });
            }
        },
        
        total_conc(){
            var total = 0;
        
            this.contenidodetalle.forEach(el => {  
                if(el.valor){
                    total+=parseFloat(el.valor);
                }
            });
            return total.toFixed(2);
        },
        total_depo(){
            var total = 0;
        
            this.contenidodeposito.forEach(el => {  
                if(el.valor){
                    total+=parseFloat(el.valor);
                }
            });
            return total.toFixed(2);
        },
        total_transf(){
            var total = 0;
        
            this.contenidotransferencia.forEach(el => {  
                if(el.valor){
                    total+=parseFloat(el.valor);
                }
            });
            return total.toFixed(2);
        },
        
        total_cheque_libro(){
            var total = 0;
            if(this.contenidocheque_libro.length>0 && this.exist_cheque_libro==true){
                this.contenidocheque_libro.forEach(el => {  
                    if(el.valor){
                        total+=parseFloat(el.valor);
                    }
                });
            }
            
            return total.toFixed(2);
        },
        total_depo_libro(){
            var total = 0;
            if(this.contenidodeposito_libro.length>0 && this.exist_depo_libro==true){
                this.contenidodeposito_libro.forEach(el => {  
                    if(el.valor){
                        total+=parseFloat(el.valor);
                    }
                });
            }
            
            return total.toFixed(2);
        },
        total_transf_libro(){
            var total = 0;
            if(this.contenidotransferencia_libro.length>0 && this.exist_trans_libro==true){
                this.contenidotransferencia_libro.forEach(el => {  
                    if(el.valor){
                        total+=parseFloat(el.valor);
                    }
                });
            }
            
            return total.toFixed(2);
        },
        total_nota_credito(){
            var total = 0;
        
            this.contenidonota_credito.forEach(el => {  
                if(el.valor){
                    total+=parseFloat(el.valor);
                }
            });
            return total.toFixed(2);
        },
        total_nota_debito(){
            var total = 0;
        
            this.contenidonota_debito.forEach(el => {  
                if(el.valor){
                    total+=parseFloat(el.valor);
                }
            });
            return total.toFixed(2);
        },
        total_saldo_libro(){
            var total=0;
            //if(this.saldo_libro){
            //    total=parseFloat(this.saldo_libro_fixed)+parseFloat(this.valor_select);
            //}else{
                total=(parseFloat(this.total_conc)+parseFloat(this.total_depo)+parseFloat(this.total_transf)+parseFloat(this.saldo_libro_fixed)+parseFloat(this.total_nota_credito)+parseFloat(this.total_nota_debito))+(parseFloat(this.total_cheque_libro)+parseFloat(this.total_transf_libro)+parseFloat(this.total_depo_libro));
            //}
            
            return total.toFixed(2);
        },
        saldo_libro_fixed(){
            var total=0;
            if(this.saldo_libro){
                total=parseFloat(this.saldo_libro);
            }else{
                total=0;
            }
            return total.toFixed(2);
        }
    },
    methods:{
        listarcuenta(page1, buscar1) {
            var url =
                "/api/planctas/conciliacion/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidocuenta = respuesta;
            });
        },
        abrirPopupCuentaContable() {
            this.popupActive = true;
        },
        handleSelected(tr){
            this.cuentaContable=tr.codcta+"-"+tr.nomcta;
            this.idcuentaContable=tr.id_plan_cuentas;
            if(tr.bansel=="1"){
                this.cuenta_banco="Cuenta Corriente";
            }else{
                this.cuenta_banco="Cuenta Ahorros";
            }
            this.getBanco(tr.id_banco);
            this.popupActive=false;
            
        },
        getBanco(id){
            var url="/api/conciliacion/banco/"+id;
            axios.get(url).then(res=>{
                this.nombre_banco=res.data[0].nombre_banco;
            }).catch(err=>{
                console.log("Error"+err);
            });
        },
        agregarcampoChequeLibro(){
            this.contenidocheque_libro.push({
                fecha_reguistro:"",
                fecha_de_pago:"",
                //codigo_comprobante:"",
                no_documento:"",
                concepto:"",
                valor:"",
                tipo:"Cheque en Libro"
            });
        },
        quitarcampoChequeLibro(index){
            if(this.contenidocheque_libro[index].id_conciliacion){
                this.Indexeliminar=index;
                this.modal_eliminar=true;
                this.id_conciliacion=this.contenidocheque_libro[index].id_conciliacion;
                this.tipo_libro="Cheque Libro";
                //console.log("eliminar reguistro");
            }else{
                this.contenidocheque_libro.splice(index,1);
            }
            
        },
        agregarcampoTransferenciaLibro(){
            this.contenidotransferencia_libro.push({
                fecha_reguistro:"",
                fecha_de_pago:"",
                //codigo_comprobante:"",
                no_documento:"",
                concepto:"",
                valor:"",
                tipo:"Transferencia en Libro"
            });
        },
        quitarcampoTransferenciaLibro(index){
            if(this.contenidotransferencia_libro[index].id_conciliacion){
                this.Indexeliminar=index;
                this.modal_eliminar=true;
                this.id_conciliacion=this.contenidotransferencia_libro[index].id_conciliacion;
                this.tipo_libro="Transferencia Libro";
            }else{
                this.contenidotransferencia_libro.splice(index,1);
            }
            
        },
        agregarcampoDepositoLibro(){
            this.contenidodeposito_libro.push({
                fecha_reguistro:"",
                fecha_de_pago:"",
                //codigo_comprobante:"",
                no_documento:"",
                concepto:"",
                valor:"",
                tipo:"Deposito en Libro"
            });
        },
        quitarcampoDepositoLibro(index){
            if(this.contenidodeposito_libro[index].id_conciliacion){
                this.Indexeliminar=index;
                this.modal_eliminar=true;
                this.id_conciliacion=this.contenidodeposito_libro[index].id_conciliacion;
                this.tipo_libro="Deposito Libro";
            }else{
                this.contenidodeposito_libro.splice(index,1);
            }
        },
        conciliar_todo(){
            if(this.conciliacion_todo==true){
                this.contenidodetalle=[];
                this.contenidodeposito=[];
                this.contenidotransferencia=[];
                this.contenidonota_credito=[];
                this.contenidonota_debito=[];
                this.contenidoconc.forEach(el => {
                    el.conciliación=null
                });
                var u=0;
                this.contenidoconc.forEach(el => {
                            // var cod=el.codigo.substr(0,1);
                            // var cod_fac=el.codigo.substr(0,2);
                            var cod=el.codigo_asiento
                            //if ((el.conciliacion!="1" && el.descripcion_fp=="CHEQUE") && (cod=="E" || cod=="FV")) {
                            if (el.descripcion=="Cheque") {
                                //if(el.debe==null){
                                //    total+=0-parseFloat(el.haber);
                                //}else{
                                    if(el.haber==null){
                                        this.contenidodetalle.push({fecha:el.fecha_conciliacion,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:el.debe,valor_debe:parseFloat(el.debe),valor_haber:parseFloat(el.haber),index:u++});
                                    }else{
                                        if(el.debe==null){
                                            this.contenidodetalle.push({fecha:el.fecha_conciliacion,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:parseFloat(el.haber),valor_debe:parseFloat(el.debe),valor_haber:parseFloat(el.haber),index:u++});
                                        }else{
                                            if(el.haber>0 && el.debe>0){
                                                this.contenidodetalle.push({fecha:el.fecha_conciliacion,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:parseFloat(el.debe)+parseFloat(el.haber),valor_debe:parseFloat(el.debe),valor_haber:parseFloat(el.haber),index:u++});
                                            }else{
                                                this.contenidodetalle.push({fecha:el.fecha_conciliacion,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:parseFloat(el.debe),valor_debe:parseFloat(el.debe),valor_haber:parseFloat(el.haber),index:u++});
                                            }
                                            
                                        }
                                    }
                                    
                                //}
                                
                            }
                            if(el.descripcion=="Deposito"){
                                
                                if(el.haber==null){
                                        this.contenidodeposito.push({fecha:el.fecha_conciliacion,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:0-el.debe,index:u++});
                                    }else{
                                        if(el.debe==null){
                                            this.contenidodeposito.push({fecha:el.fecha_conciliacion,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:0-el.haber,index:u++});
                                        }else{
                                            if(el.haber>0 && el.debe>0){
                                                this.contenidodeposito.push({fecha:el.fecha_conciliacion,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:0-el.haber-el.debe,index:u++});
                                            }else{
                                                this.contenidodeposito.push({fecha:el.fecha_conciliacion,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:0-el.haber,index:u++});
                                            }
                                            
                                        }
                                    }
                            }
                            if(el.descripcion=="Transferencia"){
                                    if(el.haber==null){
                                        this.contenidotransferencia.push({fecha:el.fecha_conciliacion,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:0-el.debe,index:u++});
                                    }else{
                                        if(el.debe==null){
                                            this.contenidotransferencia.push({fecha:el.fecha_conciliacion,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:el.haber,index:u++});
                                        }else{
                                            if(el.haber>0 && el.debe>0){
                                            this.contenidotransferencia.push({fecha:el.fecha_conciliacion,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:el.haber-el.debe,index:u++}); 
                                            }else{
                                                this.contenidotransferencia.push({fecha:el.fecha_conciliacion,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:el.haber,index:u++});
                                            }
                                        }
                                    }
                                    
                            }
                            if(el.descripcion=="Nota de Debito"){
                                if(el.haber==null){
                                        this.contenidonota_debito.push({fecha:el.fecha_conciliacion,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:el.debe,valor_debe:parseFloat(el.debe),valor_haber:parseFloat(el.haber),index:u++});
                                }else{
                                        if(el.debe==null){
                                            this.contenidonota_debito.push({fecha:el.fecha_conciliacion,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:parseFloat(el.haber),valor_debe:parseFloat(el.debe),valor_haber:parseFloat(el.haber),index:u++});
                                        }else{
                                            if(el.haber>0 && el.debe>0){
                                                this.contenidonota_debito.push({fecha:el.fecha_conciliacion,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:parseFloat(el.debe)+parseFloat(el.haber),valor_debe:parseFloat(el.debe),valor_haber:parseFloat(el.haber),index:u++});
                                            }else{
                                                this.contenidonota_debito.push({fecha:el.fecha_conciliacion,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:parseFloat(el.debe),valor_debe:parseFloat(el.debe),valor_haber:parseFloat(el.haber),index:u++});
                                            }
                                            
                                        }
                                }
                            }
                            if(el.descripcion=="Nota de Credito"){
                                if(el.haber==null){
                                        this.contenidonota_credito.push({fecha:el.fecha_conciliacion,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:0-el.debe,index:u++});
                                    }else{
                                        if(el.debe==null){
                                            this.contenidonota_credito.push({fecha:el.fecha_conciliacion,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:0-el.haber,index:u++});
                                        }else{
                                            if(el.haber>0 && el.debe>0){
                                            this.contenidonota_credito.push({fecha:el.fecha_conciliacion,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:0-el.haber-el.debe,index:u++}); 
                                            }else{
                                                this.contenidonota_credito.push({fecha:el.fecha_conciliacion,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:0-el.haber,index:u++});
                                            }
                                        }
                                    }
                            }
                            
                    });
            }else{
                this.contenidodetalle=[];
                this.contenidodeposito=[];
                this.contenidotransferencia=[];
                this.contenidonota_credito=[];
                this.contenidonota_debito=[];
                this.contenidoconc.forEach(el => {
                    el.conciliación=1;
                });
                    //this.unir_comprobantes_iguales;
                    
            }
            
        },
        eliminardetalle(index,conc,des,cod){
            console.log(cod+"codigo eliminar");
            var codigo=cod;
            if(des=="Cheque"){
                if(conc==null){
                    const resultado1 = this.contenidodetalle.find( fruta => fruta.index === index );
                    
                     var indice1 = this.contenidodetalle.findIndex(fruta => fruta.index === index);
                     this.contenidodetalle.splice(indice1,1);
                    console.log(indice1+"Eliminado"+index);

                }else{
                    this.contenidodetalle.splice(index,0,{fecha:this.contenidoconc[index].fecha_conciliacion,fecha_de_pago:this.contenidoconc[index].fecha_de_pago,codigo:this.contenidoconc[index].codigo_comprobante,no_documento:this.contenidoconc[index].no_documento,concepto:this.contenidoconc[index].concepto,valor:this.contenidoconc[index].haber+this.contenidoconc[index].debe,index:index});
                    console.log(index+"Agregado"+conc);
                }
            }
            if(des=="Deposito"){
                if(conc==null){
                    const resultado2 = this.contenidodeposito.find( fruta => fruta.index === index );
                    
                     var indice2 = this.contenidodeposito.findIndex(fruta => fruta.index === index);
                     this.contenidodeposito.splice(indice2,1);
                    console.log(indice2+"Eliminado2"+index);

                }else{
                    this.contenidodeposito.splice(index,0,{fecha:this.contenidoconc[index].fecha_conciliacion,fecha_de_pago:this.contenidoconc[index].fecha_de_pago,codigo:this.contenidoconc[index].codigo_comprobante,no_documento:this.contenidoconc[index].no_documento,concepto:this.contenidoconc[index].concepto,valor:0-this.contenidoconc[index].debe-this.contenidoconc[index].haber,index:index});
                    console.log(index+"Agregado2"+conc);
                }
            }
            if(des=="Transferencia"){
                if(conc==null){
                    const resultado3 = this.contenidotransferencia.find( fruta => fruta.index === index );
                    
                     var indice3 = this.contenidotransferencia.findIndex(fruta => fruta.index === index);
                     this.contenidotransferencia.splice(indice3,1);
                    console.log(indice3+"Eliminado3"+index);

                }else{
                        if(this.contenidoconc[index].debe>0){
                            this.contenidotransferencia.splice(index,0,{fecha:this.contenidoconc[index].fecha_conciliacion,fecha_de_pago:this.contenidoconc[index].fecha_de_pago,codigo:this.contenidoconc[index].codigo_comprobante,no_documento:this.contenidoconc[index].no_documento,concepto:this.contenidoconc[index].concepto,valor:0-this.contenidoconc[index].debe,index:index});
                            console.log(index+"Agregado3"+conc);
                        }else{
                            this.contenidotransferencia.splice(index,0,{fecha:this.contenidoconc[index].fecha_conciliacion,fecha_de_pago:this.contenidoconc[index].fecha_de_pago,codigo:this.contenidoconc[index].codigo_comprobante,no_documento:this.contenidoconc[index].no_documento,concepto:this.contenidoconc[index].concepto,valor:this.contenidoconc[index].haber,index:index});
                            console.log(index+"Agregado3"+conc);
                        }
                        //this.contenidoconc.push({concepto:el.concepto,debe:el.debe,haber:el.haber,descripcion:el.descripcion,detalle_asiento:el.detalle_asiento,tipo_conciliacion:el.tipo_conciliacion,index:u});
                }
            }
            if(des=="Nota de Debito"){
                if(conc==null){
                    const resultado4 = this.contenidonota_debito.find( fruta => fruta.index === index );
                    
                     var indice4 = this.contenidonota_debito.findIndex(fruta => fruta.index === index);
                     this.contenidonota_debito.splice(indice4,1);
                    console.log(indice4+"Eliminado4"+index);

                }else{
                        if(this.contenidoconc[index].debe>0){
                            this.contenidonota_debito.splice(index,0,{fecha:this.contenidoconc[index].fecha_conciliacion,fecha_de_pago:this.contenidoconc[index].fecha_de_pago,codigo:this.contenidoconc[index].codigo_comprobante,no_documento:this.contenidoconc[index].no_documento,concepto:this.contenidoconc[index].concepto,valor:this.contenidoconc[index].debe,index:index});
                            console.log(index+"Agregado4"+conc);
                        }else{
                            this.contenidonota_debito.splice(index,0,{fecha:this.contenidoconc[index].fecha_conciliacion,fecha_de_pago:this.contenidoconc[index].fecha_de_pago,codigo:this.contenidoconc[index].codigo_comprobante,no_documento:this.contenidoconc[index].no_documento,concepto:this.contenidoconc[index].concepto,valor:this.contenidoconc[index].haber,index:index});
                            console.log(index+"Agregado4"+conc);
                        }
                        //this.contenidoconc.push({concepto:el.concepto,debe:el.debe,haber:el.haber,descripcion:el.descripcion,detalle_asiento:el.detalle_asiento,tipo_conciliacion:el.tipo_conciliacion,index:u});
                }
            }
            if(des=="Nota de Credito"){
                if(conc==null){
                    const resultado5 = this.contenidonota_credito.find( fruta => fruta.index === index );
                    
                     var indice5 = this.contenidonota_credito.findIndex(fruta => fruta.index === index);
                     this.contenidonota_credito.splice(indice5,1);
                    console.log(indice5+"Eliminado5"+index);

                }else{
                        if(this.contenidoconc[index].debe>0){
                            this.contenidonota_credito.splice(index,0,{fecha:this.contenidoconc[index].fecha_conciliacion,fecha_de_pago:this.contenidoconc[index].fecha_de_pago,codigo:this.contenidoconc[index].codigo_comprobante,no_documento:this.contenidoconc[index].no_documento,concepto:this.contenidoconc[index].concepto,valor:0-this.contenidoconc[index].debe,index:index});
                            console.log(index+"Agregado5"+conc);
                        }else{
                            this.contenidonota_credito.splice(index,0,{fecha:this.contenidoconc[index].fecha_conciliacion,fecha_de_pago:this.contenidoconc[index].fecha_de_pago,codigo:this.contenidoconc[index].codigo_comprobante,no_documento:this.contenidoconc[index].no_documento,concepto:this.contenidoconc[index].concepto,valor:0-this.contenidoconc[index].haber,index:index});
                            console.log(index+"Agregado5"+conc);
                        }
                        //this.contenidoconc.push({concepto:el.concepto,debe:el.debe,haber:el.haber,descripcion:el.descripcion,detalle_asiento:el.detalle_asiento,tipo_conciliacion:el.tipo_conciliacion,index:u});
                }
            }
            
        },
        traerConciliacion(id,fecha){
            var url="/api/conciliacion/"+id
            axios.get(url,{params:{
                id_empresa:this.usuario.id_empresa,
                fecha:fecha
            }}).then(res=>{
                if(res.data!=="No"){
                    this.contenidoconc=res.data.conciliacion;
                    this.saldo_libro=res.data.sando_ant;
                    this.contenidoconc.forEach(el => {
                        if (el.conciliacion!="1") {
                            //if(el.debe==null){
                            //    total+=0-parseFloat(el.haber);
                            //}else{
                                this.contenidodetalle.push({concepto:el.concepto,no_documento:el.no_documento,valor:el.haber});
                            //}
                            
                        }
                    });
                }else{
                    this.$vs.notify({
                        title: "Sin Reguistros",
                        text: "No se ha encontrado datos para esa cuenta",
                        color: "warning"
                    });
                    console.log("No subio");
                }
                
            }).catch(err=>{
                console.log(err);
            })
        },
        cambiarADecimal() {
            
            if(this.saldo_banco){
                this.saldo_banco=parseFloat(this.saldo_banco).toFixed(2);
            }
        },
        guardar(){
            this.disabled_conc=true;
            if(this.validar()){
                this.disabled_conc=false;
                return;
            }
            this.editarAsiento().then(value=>{
                axios.put("/api/actualizar/conciliacion",{
                    detalle:this.contenidoconc,
                    id_empresa:this.usuario.id_empresa,
                    id_plan_cuentas:this.idcuentaContable,
                    fecha_conciliacion:this.fecha,
                    saldo_libro:this.saldo_libro_fixed,
                    saldo_cheque:this.valor_select,
                    nuevo_saldo:this.total_saldo_libro,
                    cheque_libro:this.contenidocheque_libro,
                    transferecia_libro:this.contenidotransferencia_libro,
                    deposito_libro:this.contenidodeposito_libro,
                    total_cheque_libro:this.total_cheque_libro,
                    total_transferencia_libro:this.total_transf_libro,
                    total_deposito_libro:this.total_depo_libro,
                    saldo_banco:this.saldo_banco,
                    cod:this.$route.params.id
                }).then(resp=>{
                    this.$vs.notify({
                        title: "Guardado con Exito",
                        text: "La conciliacion se ha guardado con exito",
                        color: "success"
                    });
                    this.$router.push("/contabilidad/concillacion-bancaria");
                }).catch(err=>{
                    this.$vs.notify({
                        title: "Error al Guardar Reguistro",
                        text: "Error al guardar conciliacion",
                        color: "danger"
                    });
                });
            }).catch(error=>{
                console.log(error+"ERROR");
            });
        },
        actualizar(){
            var url="/api/actualizar/registro/"+this.$route.params.id;
            axios.get(url)
            .then(res=>{
                this.contenidolistar=[];
                this.contenidoconc=[];
                this.contenidodetalle=[];
                this.contenidocheque_libro=[];
                this.contenidotransferencia_libro=[];
                this.contenidodeposito_libro=[];
                this.contenidodeposito=[];
                this.contenidotransferencia=[];
                this.contenidonota_debito=[];
                this.contenidonota_credito=[];
                this.saldo_libro=0;
                this.saldo_banco=0;
                this.contenidolistar=res.data.conciliacion;
                var u=-1;
                this.contenidolistar.forEach(el => {
                        // var cod=el.codigo_comprobante.substr(0,1);
                        // var cod_fac=el.codigo.substr(0,2);
                        var cod = el.codigo_asiento;
                        if(el.tipo_conciliacion==null){
                            u++;
                            this.contenidoconc.push({id_conciliacion:el.id_conciliacion,fecha_conciliacion:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo_comprobante:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,debe:el.debe,haber:el.haber,descripcion:el.descripcion,detalle_asiento:el.detalle_asiento,tipo_conciliacion:el.tipo_conciliacion,conciliación:el.conciliación,codigo_asiento:el.codigo_asiento,index:u});
                        }
                        
                        if (el.conciliación==null && el.descripcion=="Cheque" &&  el.tipo_conciliacion==null) {
                            //if(el.debe==null){
                            //    total+=0-parseFloat(el.haber);
                            //}else{
                            
                                
                                if(el.haber==null){
                                    this.contenidodetalle.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:el.debe,codigo_asiento:el.codigo_asiento,index:u});
                                }else{
                                    if(el.debe==null){
                                        this.contenidodetalle.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:el.haber,codigo_asiento:el.codigo_asiento,index:u});
                                    }else{
                                        if(el.haber>0 && el.debe>0){
                                            this.contenidodetalle.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:el.haber+el.debe,codigo_asiento:el.codigo_asiento,index:u});
                                        }else{
                                            this.contenidodetalle.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:el.haber,codigo_asiento:el.codigo_asiento,index:u});
                                        }
                                        
                                    }
                                }
                            //}
                            
                        }
                        if(el.descripcion=="Deposito"  && el.conciliación==null && el.tipo_conciliacion==null){
                            if(el.haber==null){
                                    this.contenidodeposito.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:0-el.debe,codigo_asiento:el.codigo_asiento,index:u});
                                }else{
                                    if(el.debe==null){
                                        this.contenidodeposito.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:0-el.haber,codigo_asiento:el.codigo_asiento,index:u});
                                    }else{
                                        if(el.haber>0 && el.debe>0){
                                            this.contenidodeposito.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:0-el.haber-el.debe,codigo_asiento:el.codigo_asiento,index:u});
                                        }else{
                                            this.contenidodeposito.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:0-el.haber,codigo_asiento:el.codigo_asiento,index:u});
                                        }
                                        
                                    }
                                }
                        }
                        if(el.descripcion=="Transferencia" && (el.conciliación==null && el.tipo_conciliacion==null)){
                                if(el.debe>0){
                                    this.contenidotransferencia.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:0-el.debe,codigo_asiento:el.codigo_asiento,index:u});
                                }else{
                                    this.contenidotransferencia.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:el.haber,codigo_asiento:el.codigo_asiento,index:u});
                                }
                                
                        }
                        if(el.descripcion=="Nota de Debito"  && el.conciliación==null && el.tipo_conciliacion==null){
                            if(el.haber==null){
                                    this.contenidonota_debito.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:el.debe,codigo_asiento:el.codigo_asiento,index:u});
                                }else{
                                    if(el.debe==null){
                                        this.contenidonota_debito.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:el.haber,codigo_asiento:el.codigo_asiento,index:u});
                                    }else{
                                        if(el.haber>0 && el.debe>0){
                                            this.contenidonota_debito.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:el.haber+el.debe,codigo_asiento:el.codigo_asiento,index:u});
                                        }else{
                                            this.contenidonota_debito.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:el.haber,codigo_asiento:el.codigo_asiento,index:u});
                                        }
                                        
                                    }
                                }
                        }
                        if(el.descripcion=="Nota de Credito"  && el.conciliación==null && el.tipo_conciliacion==null){
                            if(el.haber==null){
                                    this.contenidonota_credito.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:0-el.debe,codigo_asiento:el.codigo_asiento,index:u});
                                }else{
                                    if(el.debe==null){
                                        this.contenidonota_credito.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:0-el.haber,codigo_asiento:el.codigo_asiento,index:u});
                                    }else{
                                        if(el.haber>0 && el.debe>0){
                                            this.contenidonota_credito.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:0-el.haber-el.debe,codigo_asiento:el.codigo_asiento,index:u});
                                        }else{
                                            this.contenidonota_credito.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:0-el.haber,codigo_asiento:el.codigo_asiento,index:u});
                                        }
                                        
                                    }
                                }
                        }
                        if(el.tipo_conciliacion=="Cheque en Libro"){
                            this.exist_cheque_libro=true;
                            this.contenidocheque_libro.push({fecha_reguistro:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,no_documento:el.no_documento,concepto:el.concepto,valor:el.haber,tipo:el.tipo_conciliacion,id_conciliacion:el.id_conciliacion,codigo_asiento:el.codigo_asiento,index:u});
                        }
                        if(el.tipo_conciliacion=="Transferencia en Libro"){
                            this.exist_trans_libro=true;
                            this.contenidotransferencia_libro.push({fecha_reguistro:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,no_documento:el.no_documento,concepto:el.concepto,valor:el.haber,tipo:el.tipo_conciliacion,id_conciliacion:el.id_conciliacion,codigo_asiento:el.codigo_asiento,index:u});
                        }
                        if(el.tipo_conciliacion=="Deposito en Libro"){
                            this.exist_depo_libro=true;
                            this.contenidodeposito_libro.push({fecha_reguistro:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,no_documento:el.no_documento,concepto:el.concepto,valor:el.debe,tipo:el.tipo_conciliacion,id_conciliacion:el.id_conciliacion,codigo_asiento:el.codigo_asiento,index:u});
                        }
                });
                this.saldo_libro=res.data.sando_ant;
                this.saldo_banco=res.data.conciliacion[0].saldo_banco;
                this.Tabnav--;
                var todo_conc=0;
                this.contenidoconc.forEach(el=>{
                    todo_conc+=el.conciliación;
                });
                if(todo_conc!==this.contenidoconc.length){
                    this.conciliacion_todo=false;
                }else{
                    this.conciliacion_todo=true;
                }
                //this.contenidoactualizado=response.data.contenido;
            }).catch(err=>{
                console.log("[ERROR] actualizar registros :"+err);
            });
        },
        
        editarAsiento(){
            return new Promise((resolve,reject)=>{
                let url = `/api/update/asiento/detalle/conc`;
                axios
                .put(url, {
                    detalle:this.contenidoconc,
                    id_empresa:this.usuario.id_empresa,
                    cod:this.$route.params.id
                })
                .then(response => {
                     resolve(response.data);
                })
                .catch(err => {
                    reject(err);
                });
            });
        },
        eliminarconciliacion(id,index,tipo){
            var url="/api/eliminar/conciliacion/libro/"+id
            axios.delete(url).then(resp=>{
                this.$vs.notify({
                        title: "Reguistro Eliminado con Exito",
                        text: "El Reguistro seleccionado se ha eliminado con exito",
                        color: "success"
                    });
                    
                    if(tipo=="Cheque Libro"){
                        this.contenidocheque_libro.splice(index,1);
                        this.modal_eliminar=false;
                        this.id_conciliacion="";
                        this.Indexeliminar="";
                        this.tipo_libro="";
                    }else{
                        if(tipo=="Transferencia Libro"){
                            this.contenidotransferencia_libro.splice(index,1);
                            this.modal_eliminar=false;
                            this.id_conciliacion="";
                            this.Indexeliminar="";
                            this.tipo_libro="";
                        }else{
                            if(tipo=="Deposito Libro"){
                                 this.contenidodeposito_libro.splice(index,1);
                                 this.modal_eliminar=false;
                                 this.id_conciliacion="";
                                this.Indexeliminar="";
                                this.tipo_libro="";
                            }
                        }
                    }
            }).catch(err=>{
                this.$vs.notify({
                        title: "Error al Eliminar Reguistro",
                        text: "Error al momento de eliminar Reguistro seleccionado",
                        color: "danger"
                    });
            });
        },
        listarconciliacion(){
            var url="/api/abrir/conciliacion/"+this.$route.params.id;
            axios.get(url).then(resp=>{
                this.contenidolistar=resp.data;
                this.idcuentaContable=resp.data[0].id_plan_cuentas;
                this.cuentaContable=resp.data[0].nomcta;
                this.fecha=resp.data[0].fecha_conciliacion;
                this.contenidocheque_libro=[];
                this.contenidotransferencia_libro=[];
                this.contenidodeposito_libro=[];
                if(resp.data[0].bansel=="1"){
                    this.cuenta_banco="Cuenta Corriente";
                }else{
                    this.cuenta_banco="Cuenta Ahorros";
                }
                this.nombre_banco=resp.data[0].nombre_banco;
                var u=-1;

                this.contenidolistar.forEach(el => {
                        // var cod=el.codigo_comprobante.substr(0,1);
                        // var cod_fac=el.codigo.substr(0,2);
                        var cod = el.codigo_asiento;
                        if(el.tipo_conciliacion==null){
                            u++;
                            this.contenidoconc.push({id_conciliacion:el.id_conciliacion,fecha_conciliacion:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo_comprobante:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,debe:el.debe,haber:el.haber,descripcion:el.descripcion,detalle_asiento:el.detalle_asiento,tipo_conciliacion:el.tipo_conciliacion,conciliación:el.conciliación,codigo_asiento:el.codigo_asiento,index:u});
                        }
                        
                        if (el.conciliación==null && el.descripcion=="Cheque" &&  el.tipo_conciliacion==null) {
                            //if(el.debe==null){
                            //    total+=0-parseFloat(el.haber);
                            //}else{
                            
                                
                                if(el.haber==null){
                                    this.contenidodetalle.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:el.debe,codigo_asiento:el.codigo_asiento,index:u});
                                }else{
                                    if(el.debe==null){
                                        this.contenidodetalle.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:el.haber,codigo_asiento:el.codigo_asiento,index:u});
                                    }else{
                                        if(el.haber>0 && el.debe>0){
                                            this.contenidodetalle.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:el.haber+el.debe,codigo_asiento:el.codigo_asiento,index:u});
                                        }else{
                                            this.contenidodetalle.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:el.haber,codigo_asiento:el.codigo_asiento,index:u});
                                        }
                                        
                                    }
                                }
                            //}
                            
                        }
                        if(el.descripcion=="Deposito"  && el.conciliación==null && el.tipo_conciliacion==null){
                            if(el.haber==null){
                                    this.contenidodeposito.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:0-el.debe,codigo_asiento:el.codigo_asiento,index:u});
                                }else{
                                    if(el.debe==null){
                                        this.contenidodeposito.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:0-el.haber,codigo_asiento:el.codigo_asiento,index:u});
                                    }else{
                                        if(el.haber>0 && el.debe>0){
                                            this.contenidodeposito.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:0-el.haber-el.debe,codigo_asiento:el.codigo_asiento,index:u});
                                        }else{
                                            this.contenidodeposito.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:0-el.haber,codigo_asiento:el.codigo_asiento,index:u});
                                        }
                                        
                                    }
                                }
                        }
                        if(el.descripcion=="Transferencia" && (el.conciliación==null && el.tipo_conciliacion==null)){
                                if(el.debe>0){
                                    this.contenidotransferencia.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:0-el.debe,codigo_asiento:el.codigo_asiento,index:u});
                                }else{
                                    this.contenidotransferencia.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:el.haber,codigo_asiento:el.codigo_asiento,index:u});
                                }
                                
                        }
                        if(el.descripcion=="Nota de Debito"  && el.conciliación==null && el.tipo_conciliacion==null){
                            if(el.haber==null){
                                    this.contenidonota_debito.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:el.debe,codigo_asiento:el.codigo_asiento,index:u});
                                }else{
                                    if(el.debe==null){
                                        this.contenidonota_debito.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:el.haber,codigo_asiento:el.codigo_asiento,index:u});
                                    }else{
                                        if(el.haber>0 && el.debe>0){
                                            this.contenidonota_debito.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:el.haber+el.debe,codigo_asiento:el.codigo_asiento,index:u});
                                        }else{
                                            this.contenidonota_debito.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:el.haber,codigo_asiento:el.codigo_asiento,index:u});
                                        }
                                        
                                    }
                                }
                        }
                        if(el.descripcion=="Nota de Credito"  && el.conciliación==null && el.tipo_conciliacion==null){
                            if(el.haber==null){
                                    this.contenidonota_credito.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:0-el.debe,codigo_asiento:el.codigo_asiento,index:u});
                                }else{
                                    if(el.debe==null){
                                        this.contenidonota_credito.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:0-el.haber,codigo_asiento:el.codigo_asiento,index:u});
                                    }else{
                                        if(el.haber>0 && el.debe>0){
                                            this.contenidonota_credito.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:0-el.haber-el.debe,codigo_asiento:el.codigo_asiento,index:u});
                                        }else{
                                            this.contenidonota_credito.push({fecha:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,codigo:el.codigo_comprobante,no_documento:el.no_documento,concepto:el.concepto,valor:0-el.haber,codigo_asiento:el.codigo_asiento,index:u});
                                        }
                                        
                                    }
                                }
                        }
                        if(el.tipo_conciliacion=="Cheque en Libro"){
                            this.exist_cheque_libro=true;
                            this.contenidocheque_libro.push({fecha_reguistro:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,no_documento:el.no_documento,concepto:el.concepto,valor:el.haber,tipo:el.tipo_conciliacion,id_conciliacion:el.id_conciliacion,codigo_asiento:el.codigo_asiento,index:u});
                        }
                        if(el.tipo_conciliacion=="Transferencia en Libro"){
                            this.exist_trans_libro=true;
                            this.contenidotransferencia_libro.push({fecha_reguistro:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,no_documento:el.no_documento,concepto:el.concepto,valor:el.haber,tipo:el.tipo_conciliacion,id_conciliacion:el.id_conciliacion,codigo_asiento:el.codigo_asiento,index:u});
                        }
                        if(el.tipo_conciliacion=="Deposito en Libro"){
                            this.exist_depo_libro=true;
                            this.contenidodeposito_libro.push({fecha_reguistro:el.fecha_reguistro,fecha_de_pago:el.fecha_de_pago,no_documento:el.no_documento,concepto:el.concepto,valor:el.debe,tipo:el.tipo_conciliacion,id_conciliacion:el.id_conciliacion,codigo_asiento:el.codigo_asiento,index:u});
                        }
                });
                this.saldo_libro=resp.data[0].saldo_libro;
                this.saldo_banco=resp.data[0].saldo_banco;
                this.Tabnav--;
                var todo_conc=0;
                this.contenidoconc.forEach(el=>{
                    todo_conc+=el.conciliación;
                });
                if(todo_conc!==this.contenidoconc.length){
                    this.conciliacion_todo=false;
                }else{
                    this.conciliacion_todo=true;
                }
            })
        },
        validar(){
            this.error=0;
            this.errorcuentacont=[];
            this.errorfecha=[];
            this.errorbanco=[];
            if(!this.idcuentaContable){
                this.errorcuentacont.push("Campo Obligatorio");
                this.error=1;
            }
            if(!this.fecha){
                this.errorfecha.push("Campo Obligatorio");
                this.error=1;
            }
            if(this.total_saldo_libro!==this.saldo_banco){
                this.$vs.notify({
                        title: "Error al Guardar Reguistro",
                        text: "El Saldo Conciliado y Saldo en Bancos no son iguales",
                        color: "danger"
                    });
                this.error=1;
            }
            return this.error;
        },
    },
    mounted() {
        this.listarcuenta(1,this.buscar1);
        this.listarconciliacion();

    }
}
</script>
<style lang="scss">
@import "@sass/vuexy/extraComponents/agGridStyleOverride.scss";
.dropdown-button-container {
  display: flex;
  align-items: center;

  .btnx {
    border-radius: 5px 0px 0px 5px;
  }

  .btn-drop {
    border-radius: 0px 5px 5px 0px;
    border-left: 1px solid rgba(255, 255, 255, 0.2);
  }
}
/*
.vs-dialog {
  max-width: 1024px !important;
}*/
.vs-popup {
  width: 1060px !important;
}
.peque .vs-popup {
  width: 500px !important;
}
.sindis .vs-input--input:focus {
  border-top: none !important;
  border-left: none !important;
  border-right: none !important;
}
.sindis .vs-input--input {
  border-top: none !important;
  border-left: none !important;
  border-right: none !important;
}
.nover > .icon-select {
  display: none;
}
.hovertrash:hover > .trasher {
  display: block !important;
}
.agregado{
  background:#635ace!important;
  color:#fff!important;
}
.botonstl{
  height: 100%;
    width: 38px;
    border: 1px solid #635ace;
    background: transparent;
    color: #635ace;
    font-size: 16px;
    cursor: pointer;
}
.elejido{
  background: #635ace!important;
  color:#fff!important;
}
.flexy>.vs-divider--text{
 display:flex;
}
.slide-fade-enter-active {
  transition: all .5s ease;
}
.slide-fade-leave-active {
  transition: all .5s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}
.slide-fade-enter, .slide-fade-leave-to
/* .slide-fade-leave-active for <2.1.8 */ {
  transform: translateX(10px);
  opacity: 0;
}
.columna-costototal{
padding: 10px 10px;
}
span.vs-divider-border.before {
  display: none;
}
div.vs-component.vs-divider > span.vs-divider--text{
  width: 100%;
  color: rgba(0, 0, 0, 0.8);
  display: flex;
  justify-content: space-between;
}
.table-header {
  text-align: center;
}
.table-header > div {
  width: max-content;
  max-width: 14em;
}
.valores input {
  text-align:end;
}
.valores .vs-input--placeholder {
  text-align:end;
}
.vs-con-table .vs-con-tbody .vs-table--tbody-table .tr-values .vs-table--td {
    padding: 10px 0px;
    text-align: center;
}
.vs-con-table .vs-con-tbody .vs-table--tbody-table .vs-table--thead th {
    padding: 10px 15px;
    text-align: -webkit-center;
}

</style>
