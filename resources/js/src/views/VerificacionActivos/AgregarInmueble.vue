<template>
    <div class="vx-row">
        <div class="vx-col w-full mb-base">

            <vx-card v-if="$route.params.id">
            <h2><b>INMUEBLE # {{ inmueble_id }}</b> - &nbsp; {{ inmueble_nombre }}</h2>
            <br />
                <vs-tabs >
                    <vs-tab label="INFORMACIÓN GENERAL" icon="description" @click="colorx = '#8B0000'">

                        <vs-collapse accordion >
                            <vs-collapse-item>
                                <div slot="header">
                                    DATOS GENERALES
                                </div>

                                <table style="width:100%;">
                                    <tr style="background-color:#35394e;color:white;">
                                        <th><b><center>DATOS DEL SOLICITANTE</center></b></th>
                                    </tr>
                                    <tr style="border:1px solid #35394e;">
                                        <td>
                                            <div class="vx-row" style="margin:5px">
                                                <div class="vx-col sm:w-1/4 w-full mb-6">
                                                    <label>INSTITUCIÓN:</label><input class="w-full inmueble_institucion vs-input--input normal" :value="inmueble_institucion" />
                                                </div>
                                                <div class="vx-col sm:w-1/4 w-full mb-6">
                                                    <label>FINALIDAD AVALÚO:</label><input class="w-full inmueble_finalidad_avaluo vs-input--input normal" :value="inmueble_finalidad_avaluo" />
                                                </div>
                                                <div class="vx-col sm:w-1/4 w-full mb-6">
                                                    <label>AGENCIA/OFICINA:</label><input class="w-full inmueble_agencia_oficina vs-input--input normal" :value="inmueble_agencia_oficina" />
                                                </div>
                                                <div class="vx-col sm:w-1/4 w-full mb-6">
                                                    <label>NOMBRE DEL CLIENTE:</label><input class="w-full inmueble_nombre_cliente vs-input--input normal" :value="inmueble_nombre_cliente" />
                                                </div>
                                                <div class="vx-col sm:w-1/4 w-full mb-6">
                                                    <label>DIRECCIÓN:</label><input class="w-full inmueble_direccion vs-input--input normal" :value="inmueble_direccion" />
                                                </div>
                                                <div class="vx-col sm:w-1/4 w-full mb-6">
                                                    <label>FECHA DE INSPECCIÓN:</label><input class="w-full inmueble_fecha_inspeccion vs-input--input normal" type="date" :value="inmueble_fecha_inspeccion" /> 
                                                </div>
                                                <div class="vx-col sm:w-1/4 w-full mb-6">
                                                    <div class="vs-component vs-con-input-label vs-input w-full vs-input-primary">
                                                        <label>ESTADO:</label>
                                                        <div class="vs-con-input">
                                                                <select :value="inmueble_estado" class="selectExample w-full inmueble_estado vs-inputx vs-input--input normal" style="border: 1px solid rgba(0, 0, 0, 0.2);" v-on:change="cambiar_estado($event.target.value)">
                                                                    <option value="">Seleccione </option>
                                                                    <option v-for="datos in inmueble_estados" :value="datos.id">{{datos.nombre}}</option>
                                                                </select>
                                                        </div>
                                                    </div>                
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <br />
                                <table style="width:100%;">
                                    <tr style="background-color:#35394e;color:white;">
                                        <th><b><center>TIPO DE BIEN</center></b></th>
                                    </tr>
                                    <tr style="border:1px solid #35394e;">
                                        <td>
                                            <div class="vx-row" style="margin:5px">
                                                <div class="vx-col sm:w-1/2 w-full mb-6">
                                                    <label>DESCRIPCIÓN:</label><input class="w-full inmueble_tipo_bien_descripcion vs-input--input normal" :value="inmueble_tipo_bien_descripcion" />
                                                </div>
                                                <div class="vx-col sm:w-1/2 w-full mb-6">
                                                   <label>DETALLE:</label><textarea class="w-full inmueble_tipo_bien_descripcion_detalle vs-input--input normal" :value="inmueble_tipo_bien_descripcion_detalle" />
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>

                                <table style="width:100%;">
                                    <tr style="background-color:#35394e;color:white;">
                                        <th><b><center>UBICACIÓN</center></b></th>
                                    </tr>
                                    <tr style="border:1px solid #35394e;">
                                        <td>
                                            <div class="vx-row" style="margin:5px">
                                                <div class="vx-col sm:w-1/4 w-full mb-6"> 
                                                    <label>DETALLE:</label><input class="w-full inmueble_ubicacion vs-input--input normal" :value="inmueble_ubicacion" />
                                                </div>
                                                <div class="vx-col sm:w-1/4 w-full mb-6">
                                                    <label>PROVINCIA:</label>
                                                    <div class="vs-con-input">
                                                            <select :value="inmueble_provincia" class="selectExample w-full inmueble_provincia vs-inputx vs-input--input normal" style="border: 1px solid rgba(0, 0, 0, 0.2);" v-on:change="cambiar_provincia($event.target.value)" >
                                                                <option value="">Seleccione </option>
                                                                <option v-for="datos in inmueble_provincias" :value="datos.id">{{datos.nombre}}</option>
                                                            </select>
                                                    </div>         
                                                </div>
                                                <div class="vx-col sm:w-1/4 w-full mb-6">
                                                     <div class="vs-component vs-con-input-label vs-input w-full vs-input-primary">
                                                        <label>CANTÓN:</label>
                                                        <div class="vs-con-input">
                                                                <select :value="inmueble_canton" class="selectExample w-full inmueble_canton vs-inputx vs-input--input normal" style="border: 1px solid rgba(0, 0, 0, 0.2);" v-on:change="cambiar_canton($event.target.value)">
                                                                    <option value="">Seleccione </option>
                                                                    <option v-for="datos in inmueble_cantones" :value="datos.id">{{datos.nombre}}</option>
                                                                </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="vx-col sm:w-1/4 w-full mb-6">
                                                    <div class="vs-component vs-con-input-label vs-input w-full vs-input-primary">
                                                        <label>PARROQUIA:</label>
                                                        <div class="vs-con-input">
                                                                <select :value="inmueble_parroquia" class="selectExample w-full inmueble_parroquia vs-inputx vs-input--input normal" style="border: 1px solid rgba(0, 0, 0, 0.2);" >
                                                                    <option value="">Seleccione </option>
                                                                    <option v-for="datos in inmueble_parroquias" :value="datos.id">{{datos.nombre}}</option>
                                                                </select>
                                                        </div>            
                                                    </div>
                                                </div>
                                                <div class="vx-col sm:w-1/4 w-full mb-6">
                                                    <div class="vs-component vs-con-input-label vs-input w-full vs-input-primary">
                                                        <label>CIUDAD:</label>
                                                        <div class="vs-con-input">
                                                                <select :value="inmueble_ciudad" class="selectExample w-full inmueble_ciudad vs-inputx vs-input--input normal" style="border: 1px solid rgba(0, 0, 0, 0.2);" >
                                                                    <option value="">Seleccione </option>
                                                                    <option v-for="datos in inmueble_ciudades" :value="datos.id">{{datos.nombre}}</option>
                                                                </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="vx-col sm:w-1/4 w-full mb-6">
                                                    <label>BARRIO/URBANIZACIÓN:</label><input class="w-full inmueble_barrio_urbanizacion vs-input--input normal" :value="inmueble_barrio_urbanizacion" />
                                                </div>
                                                <div class="vx-col sm:w-1/4 w-full mb-6">
                                                    <label>MANZANA:</label><input class="w-full inmueble_manzana vs-input--input normal" :value="inmueble_manzana" />
                                                </div>
                                                <div class="vx-col sm:w-1/4 w-full mb-6">
                                                    <label>LOTE:</label><input class="w-full inmueble_lote vs-input--input normal" :value="inmueble_lote" />
                                                </div>
                                                <div class="vx-col sm:w-1/4 w-full mb-6">
                                                    <label>LATITUD:</label><input class="w-full inmueble_latitud vs-input--input normal" :value="inmueble_latitud" type="number" step="0.0000001" maxlength="10" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>
                                                </div>
                                                <div class="vx-col sm:w-1/4 w-full mb-6">
                                                    <label>LONGITUD:</label><input class="w-full inmueble_longitud vs-input--input normal" :value="inmueble_longitud" type="number" step="0.0000001" maxlength="10" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" />
                                                </div>
                                                <div class="vx-col sm:w-1/4 w-full mb-6">
                                                    <label>PREDIO:</label><input class="w-full inmueble_predio vs-input--input normal" :value="inmueble_predio" />
                                                </div>
                                            </div>    
                                        </td>
                                    </tr>
                                </table>
                               <table style="width:100%;">
                                    <tr style="background-color:#35394e;color:white;">
                                        <th><b><center>DATOS MUNICIPALES</center></b></th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="vx-row" style="margin:5px">
                                                <div class="vx-col sm:w-1/4 w-full mb-6">
                                                    <label>DETALLE:</label>
                                                    <input class="w-full inmueble_datos_municipales_detalle_valor vs-input--input normal"  :value="inmueble_datos_municipales_detalle" />
                                                </div>
                                                <div class="vx-col sm:w-1/4 w-full mb-6">
                                                    <label>AÑO PAGO IMP. PREDIAL:</label>
                                                    <input class="w-full inmueble_datos_municipales_ano_impuesto_predial_valor vs-input--input normal" type="number" :value="inmueble_datos_municipales_ano_impuesto_predial" maxlength = "4" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" />
                                                </div>
                                                <div class="vx-col sm:w-1/4 w-full mb-6">
                                                    <label>CLAVE CATASTRAL:</label>
                                                    <input class="w-full inmueble_datos_municipales_clave_catastral_valor vs-input--input normal" :value="inmueble_datos_municipales_clave_catastral" />
                                                </div>
                                                <div class="vx-col sm:w-1/4 w-full mb-6">
                                                    <label>GEO CLAVE:</label>
                                                    <input class="w-full inmueble_datos_municipales_geo_clave_valor vs-input--input normal" :value="inmueble_datos_municipales_geo_clave" />
                                                </div>
                                            </div>    
                                        </td>
                                    </tr>    
                                </table>
                                <center>    
                                    <table style="border: 1px solid gray;width: 400px">
                                        <thead style="border: 1px solid gray">
                                            <tr>
                                                <th>Año</th>
                                                <th>Valor</th>
                                                <th>Construcción</th>
                                                <th>Terreno</th>
                                            </tr>
                                        </thead>
                                        <tbody style="border: 1px solid gray">
                                            <tr>
                                                <td>
                                                    <input class="w-full inmueble_municipio_ano_1_numero vs-input--input normal" :value="inmueble_municipio_ano_1_numero" type="number" maxlength = "4" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>
                                                </td>
                                                <td>
                                                    <input class="w-full inmueble_municipio_ano_1_valor vs-input--input normal"  :value="inmueble_municipio_ano_1_valor" type="number" step="0.01" maxlength="15" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>
                                                </td>
                                                <td>
                                                    <input class="w-full inmueble_municipio_ano_1_construccion vs-input--input normal"  :value="inmueble_municipio_ano_1_construccion" type="number" step="0.01" maxlength="15" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>
                                                </td>
                                                <td>
                                                    <input class="w-full inmueble_municipio_ano_1_terreno vs-input--input normal"  :value="inmueble_municipio_ano_1_terreno" type="number" step="0.01" maxlength="15" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input class="w-full inmueble_municipio_ano_2_numero vs-input--input normal" :value="inmueble_municipio_ano_2_numero" type="number" maxlength="4" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>
                                                </td>
                                                <td>
                                                    <input class="w-full inmueble_municipio_ano_2_valor vs-input--input normal" :value="inmueble_municipio_ano_2_valor" type="number" step="0.01" maxlength="15" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>
                                                </td>
                                                <td>
                                                    <input class="w-full inmueble_municipio_ano_2_construccion vs-input--input normal"  :value="inmueble_municipio_ano_2_construccion" type="number" step="0.01" maxlength="15" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>
                                                </td>
                                                <td>
                                                    <input class="w-full inmueble_municipio_ano_2_terreno vs-input--input normal" :value="inmueble_municipio_ano_2_terreno" type="number" step="0.01" maxlength="15" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>
                                                </td>
                                            </tr>
                                        </tbody>                                            
                                    </table>
                                </center>    

                                <br />
                                <table style="width:100%;">
                                    <tr style="background-color:#35394e;color:white;">
                                        <th><b><center>DATOS DE LAS ESCRITURAS</center></b></th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="vx-row" style="margin:5px">
                                                <div class="vx-col sm:w-1/4 w-full mb-6">
                                                    <label>DETALLE:</label>&nbsp;<input class="w-full inmueble_escritura_detalle_valor vs-input--input normal"  :value="inmueble_escritura_detalle" />
                                                </div>
                                                <div class="vx-col sm:w-1/4 w-full mb-6">
                                                    <label>NOTARÍA:</label>&nbsp;<input class="w-full inmueble_escritura_notaria_valor vs-input--input normal"  :value="inmueble_escritura_notaria" />
                                                </div>
                                                <div class="vx-col sm:w-1/4 w-full mb-6">
                                                    <label for="" class="vs-input--label">CANTÓN:</label>
                                                    <div class="vs-con-input">
                                                            <select :value="inmueble_escritura_canton" class="selectExample w-full inmueble_escritura_canton_valor vs-inputx vs-input--input normal" style="border: 1px solid rgba(0, 0, 0, 0.2);" >
                                                                <option value="">Seleccione </option>
                                                                <option v-for="datos in inmueble_escritura_cantones" :value="datos.id">{{datos.nombre}}</option>
                                                            </select>
                                                    </div>
                                                </div>
                                                <div class="vx-col sm:w-1/4 w-full mb-6">
                                                    <label>ESCRITURACIÓN/REGISTRO:</label><input class="w-full inmueble_escritura_fecha_valor vs-input--input normal" :value="inmueble_escritura_fecha" type="date"  />
                                                </div>
                                                <div class="vx-col sm:w-1/4 w-full mb-6">
                                                    <label>SUPERFICIE (m2):</label><input class="w-full inmueble_escritura_superficie_valor vs-input--input normal" :value="inmueble_escritura_superficie" type="number" maxlength="15" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>
                                                </div>
                                                <div class="vx-col sm:w-1/4 w-full mb-6">
                                                    <label>CUANTÍA:</label><input class="w-full inmueble_escritura_cuantia_valor vs-input--input normal" :value="inmueble_escritura_cuantia" type="number" maxlength="15" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>
                                                </div>
                                            </div>    
                                        </td>
                                    </tr>    
                                </table>
                                <br />
                                <table style="border: 1px solid gray;width: 100%">
                                    <thead style="border: 1px solid gray">
                                        <tr style="background-color:#35394e;color:white;">
                                            <th colspan=4><b><center>RESUMEN DEL AVALÚO</center></b></th>
                                        </tr>
                                        <tr>
                                            <th><center>DESCRIPCIÓN</center></th>
                                            <th><center>V. REPOSICIÓN US$</center></th>
                                            <th><center>V. ACTUAL US$</center></th>
                                            <th><center>V. REALIZACIÓN US$</center></th>
                                        </tr>
                                    </thead>
                                    <tbody style="border: 1px solid gray">
                                        <tr>
                                            <td><center>TERRENO</center></td>
                                            <td><input class="w-full inmueble_avaluo_valor_reposicion_terreno_valor vs-input--input" :value="inmueble_avaluo_valor_reposicion_terreno" type="number" step="0.01" maxlength="15" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/></td>
                                            <td><input class="w-full inmueble_avaluo_valor_actual_terreno_valor vs-input--input" :value="inmueble_avaluo_valor_actual_terreno" type="number" step="0.01" maxlength="15" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/></td>
                                            <td><input class="w-full inmueble_avaluo_valor_realizacion_terreno_valor vs-input--input" :value="inmueble_avaluo_valor_realizacion_terreno" type="number" step="0.01" maxlength="15" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/></td>
                                        </tr>
                                        <tr>
                                            <td><center>CONSTRUCCIÓN</center></td>
                                            <td><input class="w-full inmueble_avaluo_valor_reposicion_construccion_valor vs-input--input" :value="inmueble_avaluo_valor_reposicion_construccion" type="number" step="0.01" maxlength="15" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/></td>
                                            <td><input class="w-full inmueble_avaluo_valor_actual_construccion_valor vs-input--input" :value="inmueble_avaluo_valor_actual_construccion" type="number" step="0.01" maxlength="15" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/></td>
                                            <td><input class="w-full inmueble_avaluo_valor_realizacion_construccion_valor vs-input--input" :value="inmueble_avaluo_valor_realizacion_construccion" type="number" step="0.01" maxlength="15" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/></td>
                                        </tr>
                                        <tr>
                                            <td><center>TOTAL:</center></td>
                                            <td><input class="w-full inmueble_avaluo_valor_total_reposicion_valor vs-input--input" :value="inmueble_avaluo_valor_total_reposicion" type="number" step="0.01" maxlength="15" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/></td>
                                            <td><input class="w-full inmueble_avaluo_valor_total_actual_valor vs-input--input" :value="inmueble_avaluo_valor_total_actual" type="number" step="0.01" maxlength="15" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/></td>
                                            <td><input class="w-full inmueble_avaluo_valor_total_realizacion_valor vs-input--input" :value="inmueble_avaluo_valor_total_realizacion" type="number" step="0.01" maxlength="15" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/></td>
                                        </tr>
                                    </tbody>                                            
                                </table>                        
                            </vs-collapse-item>
                            <vs-collapse-item>
                                <div slot="header">
                                    CARACTERÍSTICAS DEL ENTORNO
                                </div>
                                <div class="vx-row" style="margin:5px">
                                    <div class="vx-col sm:w-1/1 w-full mb-6">
                                        <label>DETALLE:</label>&nbsp;<input class="w-full inmueble_entorno_detalle_valor vs-input--input" :value="inmueble_entorno_detalle"  />
                                    </div>
                                </div>
                                <div class="vx-row" style="margin:10px">
                                    <table style="border: 1px solid gray;width: 100%">
                                        <tbody style="border: 1px solid gray">      
                                            <tr  v-for="(value, name, index) in inmueble_entorno_listado"> 
                                                <td colspan=1 style="width:25%"><input class="w-full inmueble_entorno_listado_clave vs-input--input" :value="name" /></td>
                                                <td colspan=3 style="width:75%"><input class="w-full inmueble_entorno_listado_valor vs-input--input" :value="value" /></td>
                                            </tr>                                        
                                        </tbody>                                            
                                    </table>                            
                                </div>
                                <div class="vx-row" style="margin:5px">
                                    <div class="vx-col sm:w-1/1 w-full mb-6">
                                        <label>SERVICIOS:</label>&nbsp;<input class="w-full inmueble_entorno_servicios_valor vs-input--input" :value="inmueble_entorno_servicios" />
                                    </div>
                                </div>
                                <div class="vx-row" style="margin:5px">
                                    <div class="vx-col sm:w-1/1 w-full mb-6">
                                        <label>IMPACTO AMBIENTAL:</label>&nbsp;<input class="w-full inmueble_entorno_impacto_ambiental_valor vs-input--input" :value="inmueble_entorno_impacto_ambiental" />
                                    </div>
                                </div>
                                <div class="vx-row" style="margin:10px">
                                    <table style="border: 1px solid gray;width: 100%">
                                        <thead style="border: 1px solid gray">
                                            <tr style="background-color:#35394e;color:white;">
                                                <th colspan=4><b><center>EQUIPAMIENTO DE LA ZONA</center></b></th>
                                            </tr>
                                        </thead>
                                        <tbody style="border: 1px solid gray">      
                                            <tr  v-for="(value, name, index) in inmueble_entorno_equipamiento"> 
                                                <td colspan=1 style="width:25%"><input class="w-full inmueble_entorno_equipamiento_clave vs-input--input" :value="name" /></td>
                                                <td colspan=3 style="width:75%"><input class="w-full inmueble_entorno_equipamiento_valor vs-input--input" :value="value" /></td>
                                            </tr>                                        
                                        </tbody>                                            
                                    </table>                            
                                </div>  
                                <div class="vx-row" style="margin:5px">
                                    <div class="vx-col sm:w-1/1 w-full mb-6">
                                        <label>DESCRIPCIÓN DE LA ZONA:</label>&nbsp;<input class="w-full inmueble_entorno_descripcion_zona_valor vs-input--input" :value="inmueble_entorno_descripcion_zona" />
                                    </div>
                                </div>
                                <div class="vx-row" style="margin:5px">
                                    <div class="vx-col sm:w-1/1 w-full mb-6">
                                        <label>OBSERVACIONES DE OCUPACIÓN SEGÚN EL ENTORNO:</label>&nbsp;<input class="w-full inmueble_entorno_observaciones_valor vs-input--input" :value="inmueble_entorno_observaciones" />
                                    </div>
                                </div>  
                            </vs-collapse-item>
                            <vs-collapse-item >
                                <div slot="header">
                                    TERRENO
                                </div>
                                
                            <div class="vx-row" style="margin:10px">
                                <table style="border: 1px solid gray;width: 100%">
                                    <thead style="border: 1px solid gray">
                                        <tr style="background-color:#35394e;color:white;">
                                            <th colspan=4><b><center>LOCALIZACIÓN</center></b></th>
                                        </tr>
                                    </thead>
                                    <tbody style="border: 1px solid gray">      
                                        <tr  v-for="(value, name, index) in inmueble_terreno_localizacion"> 
                                            <td colspan=1 style="width:25%"><input class="w-full inmueble_terreno_localizacion_clave vs-input--input"  :value="name" /></td>
                                            <td colspan=3 style="width:75%"><input class="w-full inmueble_terreno_localizacion_valor vs-input--input"  :value="value" /></td>
                                        </tr>                                        
                                    </tbody>                                            
                                </table>                            
                            </div>
                            <div class="vx-row" style="margin:10px">
                                <table style="border: 1px solid gray;width: 100%">
                                    <thead style="border: 1px solid gray">
                                        <tr style="background-color:#35394e;color:white;">
                                            <th colspan=4><b><center>CARACTERÍSTICAS FÍSICAS</center></b></th>
                                        </tr>
                                    </thead>
                                    <tbody style="border: 1px solid gray">      
                                        <tr  v-for="(value, name, index) in inmueble_terreno_caracteristicas_fisicas"> 
                                            <td colspan=1 style="width:25%"><input class="w-full inmueble_terreno_caracteristicas_fisicas_clave  vs-input--input"  :value="name" /></td>
                                            <td colspan=3 style="width:75%"><input class="w-full inmueble_terreno_caracteristicas_fisicas_valor  vs-input--input"  :value="value" /></td>
                                        </tr>                                        
                                    </tbody>                                            
                                </table>                            
                            </div>    
                            <div class="vx-row" style="margin:5px">
                                <div class="vx-col sm:w-1/1 w-full mb-6">
                                    <label>CERRAMIENTO:</label>&nbsp;<input class="w-full inmueble_terreno_cerramiento vs-input--input" :value="inmueble_terreno_cerramiento" />
                                </div>
                            </div>
                            <div class="vx-row" style="margin:10px">
                                <table style="border: 1px solid gray;width: 100%">
                                    <thead style="border: 1px solid gray">
                                        <tr style="background-color:#35394e;color:white;">
                                            <th colspan=5><b><center>LINDEROS Y DIMENSIONES GENERALES DEL TERRENO</center></b></th>
                                        </tr>
                                        <tr>
                                            <th style="width:20%"><center>LINDEROS</center></th>
                                            <th style="width:20%"><center>COORDENADAS</center></th>
                                            <th style="width:30%"><center>DESCRIPCIÓN</center></th>
                                            <th style="width:15%"><center>ESCRITURA</center></th>
                                            <th style="width:15%"><center>COMPROB. EN SITIO</center></th>
                                        </tr>
                                    </thead>
                                    <tbody style="border: 1px solid gray">      
                                        <tr  v-for="(value, name, index) in inmueble_terreno_linderos_dimensiones"> 
                                            <td style="width:20%"><input class="w-full inmueble_terreno_linderos_dimensiones_col1 vs-input--input"  :value="value.split('|')[0]" /></td>
                                            <td style="width:20%"><input class="w-full inmueble_terreno_linderos_dimensiones_col2 vs-input--input"  :value="value.split('|')[1]" /></td>
                                            <td style="width:30%"><input class="w-full inmueble_terreno_linderos_dimensiones_col3 vs-input--input"  :value="value.split('|')[2]" /></td>
                                            <td style="width:15%"><input class="w-full inmueble_terreno_linderos_dimensiones_col4 vs-input--input"  :value="value.split('|')[3]" type="number" maxlength="15" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" /></td>
                                            <td style="width:15%"><input class="w-full inmueble_terreno_linderos_dimensiones_col5 vs-input--input"  :value="value.split('|')[4]" type="number" maxlength="15" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" /></td>
                                        </tr>                                        
                                        <tr> 
                                            <td colspan=3><center> AREA DEL TERRENO (m2):</center></td> 
                                            <td colspan=1><input class="w-full inmueble_terreno_linderos_dimensiones_area_col1 vs-input--input"  :value="inmueble_terreno_linderos_dimensiones_area_col1" type="number" maxlength="15" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" /></td>
                                            <td colspan=1><input class="w-full inmueble_terreno_linderos_dimensiones_area_col2 vs-input--input"  :value="inmueble_terreno_linderos_dimensiones_area_col2" type="number" maxlength="15" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" /></td>
                                        </tr>
                                    </tbody>                                            
                                </table>
                            </div>

                            </vs-collapse-item>
                            <vs-collapse-item>
                                <div slot="header">
                                    EDIFICACIÓN
                                </div>
                            <div class="vx-row" style="margin:10px">
                                <table style="border: 1px solid gray;width: 100%">
                                    <thead style="border: 1px solid gray">
                                        <tr style="background-color:#35394e;color:white;">
                                            <th colspan=4><b><center>CARACTERÍSTICAS</center></b></th>
                                        </tr>
                                    </thead>
                                    <tbody style="border: 1px solid gray">      
                                        <tr  v-for="(value, name, index) in inmueble_edificacion_caracteristicas"> 
                                            <td colspan=1 style="width:25%"><input class="w-full inmueble_edificacion_caracteristicas_clave vs-input--input"  :value="name" /></td>
                                            <td colspan=3 style="width:75%"><input class="w-full inmueble_edificacion_caracteristicas_valor vs-input--input"  :value="value" /></td>
                                        </tr>
                                    </tbody>                                            
                                </table>                            
                            </div>
                            <div class="vx-row" style="margin:10px">
                                <table style="border: 1px solid gray;width: 100%">
                                    <thead style="border: 1px solid gray">
                                        <tr style="background-color:#35394e;color:white;">
                                            <th colspan=4><b><center>CUADRO DE ÁREAS DE EDIFICACIÓN:</center></b></th>
                                        </tr>
                                        <tr>
                                            <th style="width:50%"><center>DESCRIPCIÓN</center></th>
                                            <th style="width:25%"><center>ÁREA CUBIERTA (m2)</center></th>
                                            <th style="width:25%"><center>ÁREA DESCUBIERTA (m2)</center></th>
                                        </tr>
                                    </thead>
                                    <tbody style="border: 1px solid gray">      
                                        <tr  v-for="(value, name, index) in inmueble_edificacion_areas_edificacion"> 
                                            <td style="width:50%"><input class="w-full inmueble_edificacion_areas_edificacion_col1 vs-input--input"  :value="name" /></td>
                                            <td style="width:25%"><input class="w-full inmueble_edificacion_areas_edificacion_col2 vs-input--input"  :value="value.split('|')[0]" type="number" maxlength="15" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" /></td>
                                            <td style="width:25%"><input class="w-full inmueble_edificacion_areas_edificacion_col3 vs-input--input"  :value="value.split('|')[1]" type="number" maxlength="15" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" /></td>
                                        </tr>
                                        <tr> 
                                            <td style="width:50%"><center>TOTAL m2:</center></td> 
                                            <td style="width:25%"><input class="w-full inmueble_edificacion_areas_edificacion_total_col1 vs-input--input"  :value="inmueble_edificacion_areas_edificacion_total_col1" type="number" maxlength="15" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" /></td>
                                            <td style="width:25%"><input class="w-full inmueble_edificacion_areas_edificacion_total_col2 vs-input--input"  :value="inmueble_edificacion_areas_edificacion_total_col2" type="number" maxlength="15" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" /></td>
                                        </tr>                                        
                                    </tbody>                                            
                                </table>                            
                            </div>
                            <div class="vx-row" style="margin:10px">
                                <table style="border: 1px solid gray;width: 100%">
                                    <thead style="border: 1px solid gray">
                                        <tr style="background-color:#35394e;color:white;">
                                            <th colspan=4><b><center>CUADRO DE ÁREAS OTROS:</center></b></th>
                                        </tr>
                                        <tr>
                                            <th style="width:50%"><center>DESCRIPCIÓN</center></th>
                                            <th style="width:25%"><center>ÁREA CUBIERTA (m2)</center></th>
                                            <th style="width:25%"><center>ÁREA DESCUBIERTA (m2)</center></th>
                                        </tr>
                                    </thead>
                                    <tbody style="border: 1px solid gray">      
                                        <tr  v-for="(value, name, index) in inmueble_edificacion_areas_edificacion_otros"> 
                                            <td style="width:50%"><input class="w-full inmueble_edificacion_areas_edificacion_otros_col1 vs-input--input"  :value="name" /></td>
                                            <td style="width:25%"><input class="w-full inmueble_edificacion_areas_edificacion_otros_col2 vs-input--input"  :value="value.split('|')[0]" type="number" maxlength="15" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" /></td>
                                            <td style="width:25%"><input class="w-full inmueble_edificacion_areas_edificacion_otros_col3 vs-input--input"  :value="value.split('|')[1]" type="number" maxlength="15" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" /></td>
                                        </tr>
                                        <tr> 
                                            <td style="width:50%"><center>TOTAL m2:</center> </td>
                                            <td style="width:25%"><input class="w-full inmueble_edificacion_areas_edificacion_otros_total_col1 vs-input--input"  :value="inmueble_edificacion_areas_edificacion_otros_total_col1" type="number" maxlength="15" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" /></td>
                                            <td style="width:25%"><input class="w-full inmueble_edificacion_areas_edificacion_otros_total_col2 vs-input--input"  :value="inmueble_edificacion_areas_edificacion_otros_total_col2" type="number" maxlength="15" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" /></td>
                                        </tr>                                        
                                    </tbody>                                            
                                </table>                            
                            </div>
                            <div class="vx-row" style="margin:10px">
                                <table style="border: 1px solid gray;width: 100%">
                                    <thead style="border: 1px solid gray">
                                        <tr style="background-color:#35394e;color:white;">
                                            <th colspan=4><b><center>RESUMEN DE INFRAESTRUCTURA:</center></b></th>
                                        </tr>
                                        <tr>
                                            <th style="width:50%"><center>DESCRIPCIÓN</center></th>
                                            <th style="width:25%"><center>UNIDAD</center></th>
                                            <th style="width:25%"><center>CANTIDAD</center></th>
                                        </tr>
                                    </thead>
                                    <tbody style="border: 1px solid gray">      
                                        <tr  v-for="(value, name, index) in inmueble_edificacion_resumen_infraestructura"> 
                                            <td style="width:50%"><input class="w-full inmueble_edificacion_resumen_infraestructura_col1 vs-input--input"  :value="name" /></td>
                                            <td style="width:25%"><input class="w-full inmueble_edificacion_resumen_infraestructura_col2 vs-input--input"  :value="value.split('|')[0]" /></td>
                                            <td style="width:25%"><input class="w-full inmueble_edificacion_resumen_infraestructura_col3 vs-input--input"  :value="value.split('|')[1]" type="number" maxlength="6"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" /></td>
                                        </tr>
                                    </tbody>                                            
                                </table>                            
                            </div>
                            <div class="vx-row" style="margin:5px">
                                <div class="vx-col sm:w-1/1 w-full mb-6">
                                    <label>CONSERVACIÓN Y MANTENIMIENTO:</label>&nbsp;<input class="w-full inmueble_edificacion_conservacion_mantenimiento vs-input--input" :value="inmueble_edificacion_conservacion_mantenimiento" />
                                </div>
                            </div>
                            <div class="vx-row" style="margin:5px">   
                                <div class="vx-col sm:w-1/1 w-full mb-6">
                                    <label>DESCRIPCIÓN FUNCIONAL:</label>&nbsp;<input class="w-full inmueble_edificacion_descripcion_funcional vs-input--input" :value="inmueble_edificacion_descripcion_funcional" />
                                </div>
                            </div>
                        
        
                            </vs-collapse-item>
                            <vs-collapse-item>
                                <div slot="header">
                                    CRITERIOS Y MÉTODOS EMPLEADOS EN LA VALORACIÓN
                                </div>

                                <div class="vx-row" style="margin:10px">
                                    <table style="border: 1px solid gray;width: 100%">
                                        <thead style="border: 1px solid gray">
                                            <tr style="background-color:#35394e;color:white;">
                                                <th colspan=4><b><center>CRITERIOS PARA LA VALORACIÓN</center></b></th>
                                            </tr>
                                        </thead>
                                        <tbody style="border: 1px solid gray">      
                                            <tr  v-for="(value, name, index) in inmueble_criterio_valoracion_listado"> 
                                                <td colspan=1 style="width:25%"><input class="w-full inmueble_criterio_valoracion_listado_clave vs-input--input"  :value="name" /></td>
                                                <td colspan=3 style="width:75%"><input class="w-full inmueble_criterio_valoracion_listado_valor vs-input--input"  :value="value" /></td>
                                            </tr>                                        
                                        </tbody>                                            
                                    </table>                            
                                </div>    
                                <div class="vx-row" style="margin:10px">
                                    <table style="border: 1px solid gray;width: 100%">
                                        <thead style="border: 1px solid gray">
                                            <tr style="background-color:#35394e;color:white;">
                                                <th colspan=4><b><center>CALIFICACIÓN DEL INMUEBLE</center></b></th>
                                            </tr>
                                        </thead>
                                        <tbody style="border: 1px solid gray">      
                                            <tr  v-for="(value, name, index) in inmueble_criterio_valoracion_calificacion_listado"> 
                                                <td colspan=1 style="width:25%"><input class="w-full inmueble_criterio_valoracion_calificacion_listado_clave vs-input--input"  :value="name" /></td>
                                                <td colspan=3 style="width:75%"><input class="w-full inmueble_criterio_valoracion_calificacion_listado_valor vs-input--input"  :value="value" /></td>
                                            </tr>                                        
                                        </tbody>                                            
                                    </table>                            
                                </div>
                               <div class="vx-row" style="margin:5px">
                                <div class="vx-col sm:w-1/1 w-full mb-6">
                                    <label>VALORACIÓN DEL TERRENO:</label>&nbsp;<input class="w-full inmueble_criterio_valoracion_terreno_detalle vs-input--input" :value="inmueble_criterio_valoracion_terreno_detalle" />
                                </div>
                               </div>
                               <div class="vx-row" style="margin:5px"> 
                               <table style="border: 1px solid gray;width: 100%">
                                    <thead style="border: 1px solid gray">
                                        <tr style="background-color:#35394e;color:white;">
                                            <th colspan=10><b><center>HOMOGENIZACIÓN DE MUESTRAS DE MERCADO</center></b></th>
                                        </tr>
                                        <tr style="background-color:#35394e;color:white;">
                                            <th colspan=10><b><center>PLANILLA Y SELECCIÓN DE ANTECEDENTES</center></b></th>
                                        </tr>
                                        <tr style="border: 1px solid gray">
                                            <th colspan=3>&nbsp;</th>
                                            <th colspan=6 style="background-color:#35394e;color:white;" ><center>FACTORES</center></th>
                                            <th colspan=1>&nbsp;</th>
                                        </tr>
                                        <tr>
                                            <th><center>TERRENOS EN VENTA</center></th>
                                            <th><center>ÁREA m2</center></th>
                                            <th><center>VALOR ($/m2)</center></th>
                                            <th><center>FRENTE</center></th>
                                            <th><center>UBICACIÓN</center></th>
                                            <th><center>TAMAÑO</center></th>
                                            <th><center>FORMA</center></th>
                                            <th><center>ADECUACIÓN</center></th>
                                            <th><center>HOMOGENIZADOS</center></th>
                                            <th><center>VALOR UNITARIO</center></th>
                                        </tr>
                                    </thead>
                                    <tbody style="border: 1px solid gray">      
                                        <tr  v-for="(value, name, index) in inmueble_criterio_valoracion_terreno_listado"> 
                                            <td><input class="w-full inmueble_criterio_valoracion_terreno_listado_col1 vs-input--input"  :value="value.split('|')[0]" /></td>
                                            <td><input class="w-full inmueble_criterio_valoracion_terreno_listado_col2 vs-input--input"  :value="value.split('|')[1]" type="number" maxlength="15" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" /></td>
                                            <td><input class="w-full inmueble_criterio_valoracion_terreno_listado_col3 vs-input--input"  :value="value.split('|')[2]" type="number" maxlength="15" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" /></td>
                                            <td><input class="w-full inmueble_criterio_valoracion_terreno_listado_col4 vs-input--input"  :value="value.split('|')[3]" type="number" maxlength="15" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" /></td>
                                            <td><input class="w-full inmueble_criterio_valoracion_terreno_listado_col5 vs-input--input"  :value="value.split('|')[4]" type="number" maxlength="15" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" /></td>
                                            <td><input class="w-full inmueble_criterio_valoracion_terreno_listado_col6 vs-input--input"  :value="value.split('|')[5]" type="number" maxlength="15" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" /></td>
                                            <td><input class="w-full inmueble_criterio_valoracion_terreno_listado_col7 vs-input--input"  :value="value.split('|')[6]" type="number" maxlength="15" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" /></td>
                                            <td><input class="w-full inmueble_criterio_valoracion_terreno_listado_col8 vs-input--input"  :value="value.split('|')[7]" type="number" maxlength="15" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" /></td>
                                            <td><input class="w-full inmueble_criterio_valoracion_terreno_listado_col9 vs-input--input"  :value="value.split('|')[8]" type="number" maxlength="15" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" /></td>
                                            <td><input class="w-full inmueble_criterio_valoracion_terreno_listado_col10 vs-input--input"  :value="value.split('|')[9]" type="number" maxlength="15" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" /></td>
                                        </tr>                                        
                                        <tr> 
                                            <td colspan=9 >&nbsp;</td>
                                            <td colspan=1><input class="w-full inmueble_criterio_valoracion_terreno_total vs-input--input"  :value="inmueble_criterio_valoracion_terreno_total.valoracion_terreno_total" type="number" maxlength="15" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/></td>
                                        </tr>
                                    </tbody>                                            
                            </table>                            
                            </div>

                            <div class="vx-row" style="margin:5px">
                                <div class="vx-col sm:w-1/1 w-full mb-6">
                                    <label>VALORACIÓN DE LAS CONSTRUCCIONES E INFRAESTRUCTURA:</label>&nbsp;<input class="w-full inmueble_criterio_valoracion_construcciones vs-input--input" :value="inmueble_criterio_valoracion_construcciones" />
                                </div>
                            </div>
                            
                            </vs-collapse-item>
                            <vs-collapse-item>
                                <div slot="header">
                                    CUADRO RESUMEN DE VALORACIÓN
                                </div>
                                <div class="vx-row" style="margin:5px"> 
                               <table style="border: 1px solid gray;width: 100%">
                                    <thead style="border: 1px solid gray">
                                        <tr>
                                            <th><center>DESCRIPCIÓN</center></th>
                                            <th><center>ÁREA m2</center></th>
                                            <th><center>VALOR DE REPOSICIÓN US$</center></th>
                                            <th><center>VALOR ACTUAL US$</center></th>
                                            <th><center>VALOR DE REALIZACIÓN US$</center></th>
                                        </tr>
                                    </thead>
                                    <tbody style="border: 1px solid gray">      
                                        <tr  v-for="(value, name, index) in inmueble_resumen_valoracion_tabla"> 
                                            <td><input class="w-full inmueble_resumen_valoracion_tabla_col1 vs-input--input normal"  :value="value.split('|')[0]" /></td>
                                            <td><input class="w-full inmueble_resumen_valoracion_tabla_col2 vs-input--input"  :value="value.split('|')[1]" type="number" maxlength="15" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" /></td>
                                            <td><input class="w-full inmueble_resumen_valoracion_tabla_col3 vs-input--input"  :value="value.split('|')[2]" type="number" maxlength="15" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" /></td>
                                            <td><input class="w-full inmueble_resumen_valoracion_tabla_col4 vs-input--input"  :value="value.split('|')[3]" type="number" maxlength="15" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" /></td>
                                            <td><input class="w-full inmueble_resumen_valoracion_tabla_col5 vs-input--input"  :value="value.split('|')[4]" type="number" maxlength="15" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" /></td>
                                        </tr>                                        
                                        <tr> 
                                            <td colspan=4 >VALOR DE REPOSICIÓN (US$)…........................................................................</td>
                                            <td colspan=1><input class="w-full inmueble_resumen_valoracion_reposicion vs-input--input" :value="inmueble_resumen_valoracion_reposicion" type="number" maxlength="15" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" /></td>
                                        </tr>
                                        <tr> 
                                            <td colspan=4 >VALOR DE MERCADO (US$)…...........................................................................</td>
                                            <td colspan=1><input class="w-full inmueble_resumen_valoracion_mercado vs-input--input" :value="inmueble_resumen_valoracion_mercado" type="number" maxlength="15" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" /></td>
                                        </tr>
                                        <tr> 
                                            <td colspan=4 >VALOR DE REALIZACIÓN (US$)….......................................................................</td>
                                            <td colspan=1><input class="w-full inmueble_resumen_valoracion_realizacion vs-input--input" :value="inmueble_resumen_valoracion_realizacion" type="number" maxlength="15" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" /></td>
                                        </tr>
                                    </tbody>                                            
                            </table>                            
                            </div>
                            </vs-collapse-item>
                        </vs-collapse>
                    </vs-tab>
                    <vs-tab label="UBICACIÓN" icon="account_balance" @click="colorx = '#FFA500'">

                        <l-map style="height: 300px" :zoom="zoom" :center="center">
                            <l-tile-layer :url="url" :attribution="attribution"></l-tile-layer>
                            <l-marker @add="openPopup"  :lat-lng="markerLatLng" :icon="defaultIcon" >
                            <l-popup :options="{ autoClose: false, closeOnClick: false }">Ubicación del Inmueble</l-popup>    
                            </l-marker>
                        </l-map>


                    </vs-tab>
                    <vs-tab label="IMÁGENES" icon="dashboard" @click="colorx = '#551A8B'">
                            <div class="vx-row">

                                <div class="vx-col sm:w-1/4 w-full mb-6">
                                    <vs-input class="w-full" label="TÌTULO DE LA IMAGEN:" v-model="inmueble_imagen_titulo" name ="inmueble_imagen_titulo" />
                                </div>
                                <div class="vx-col sm:w-1/6 w-full mb-6">
                                    <vs-select placeholder="Seleccione" autocomplete class="selectExample w-full" v-model="inmueble_imagen_orden" label="TIPO:">
                                            <vs-select-item
                                                v-for="datos in inmueble_imagenes_tipos" :value="datos.id"
                                                :text="datos.nombre" :key="datos.id" />
                                    </vs-select>
                                </div>
                                <div class="vx-col sm:w-1/3 w-full mb-6">
                                    <vs-input type="file" label="ARCHIVO:" accept="image/png, image/jpeg"  ref="inmueble_imagen_archivo" name="inmueble_imagen_archivo"  @change="handle_file_upload_ingreso( $event )"  style="width:350px" />
                                </div>
                                <div class="vx-col sm:w-1/4 w-full mb-6">
                                    <br />
                                    <center><vs-button color="primary" type="filled" @click="subir_imagen"  >SUBIR IMAGEN</vs-button></center>
                                </div>            
                            </div>

                        <br />
                        <!-- Inicio popup editar imagen -->
                        <vs-popup :title="popup_titulo_editar_imagen" :active.sync="popup_editar_imagen">
                            <div class="vx-col sm:w-full w-full mb-6">
                                <div class="vx-row">

                                    <div class="vx-col sm:w-full w-full">
                                        <center> 
                                            <div class="portrait">
                                                <img :src="inmueble_imagen_binario_editar" style="border: 1px solid gray;" />
                                            </div>
                                        </center>    
                                    </div>

                                    <div class="vx-col sm:w-full w-full">
                                            <vs-input type="hidden" v-model="inmueble_imagen_id_editar" name ="inmueble_imagen_id_editar" />
                                            <vs-input class="w-full" label="TÌTULO DE LA IMAGEN:" v-model="inmueble_imagen_titulo_editar" name ="inmueble_imagen_titulo_editar" />
                                    </div>

                                     <div class="vx-col sm:w-full w-full">
                                        <vs-select placeholder="Seleccione" autocomplete class="selectExample w-full" v-model="inmueble_imagen_orden_editar" label="TIPO:">
                                            <vs-select-item
                                                v-for="datos in inmueble_imagenes_tipos" :value="datos.id"
                                                :text="datos.nombre" :key="datos.id" />
                                        </vs-select>
                                     </div>
                                    
                                    <div class="vx-col sm:w-full w-full">
                                        <vs-input type="file" label="CAMBIAR ARCHIVO:" accept="image/png, image/jpeg"  ref="inmueble_imagen_archivo_editar" name="inmueble_imagen_archivo_editar"  @change="handle_file_upload_editar( $event )"  style="width:400px" />

                                    </div>
                                    <div>&nbsp;</div>
                                    <div class="vx-col sm:w-full w-full">
                                        <center>
                                            <vs-button color="success" type="border" @click="editar_inmueble_imagen()">EDITAR</vs-button>
                                            <vs-button color="danger" type="border" @click="popup_editar_imagen = false">CANCELAR</vs-button>
                                        </center>    
                                    </div>
                                </div>
                            </div>
                        </vs-popup>
                        <!-- Fin popup editar imagen -->
                        <div class="">
                            <vs-table stripe max-items=25 pagination :data="inmueble_imagenes">
                                <template slot="thead">
                                    <vs-th class="text-center">Id</vs-th>
                                    <vs-th class="text-center">Tipo</vs-th>
                                    <vs-th class="text-center">Título de la Imagen</vs-th>
                                    <vs-th class="text-center">Nombre Archivo</vs-th>
                                    <vs-th class="text-center">Fecha de creación</vs-th>
                                    <vs-th class="text-center">Acciones</vs-th>
                                </template>
                                <template slot-scope="{ data }">
                                    <vs-tr :key="datos.id" v-for="datos in data" class="text-center">
                                        <vs-td v-if="datos.id">{{ datos.id}}</vs-td>
                                        <vs-td v-if="datos.orden">{{ datos.orden}}</vs-td>
                                        <vs-td v-if="datos.titulo">{{ datos.titulo }}</vs-td>
                                        <vs-td v-if="datos.nombre">{{ datos.nombre }}</vs-td>
                                        <vs-td v-if="datos.fcrea">{{ datos.fcrea }}</vs-td>
                                        <vs-td class="whitespace-no-wrap">
                                            <feather-icon icon="EditIcon" svgClasses="w-5 h-5 hover:text-primary stroke-current" 
                                                class="cursor-pointer" @click="editar_imagen(datos.id)" />
                                        <feather-icon icon="TrashIcon" svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                                class="ml-2 cursor-pointer" @click.stop="eliminar_imagen(datos.id)" />
                                        </vs-td>
                                    </vs-tr>
                                </template>
                            </vs-table> 
                        </div>
                    </vs-tab>
                    <vs-tab label="CAMBIO DE ESTADOS" icon="list" @click="colorx = '#551A8B'">
                        <vs-row vs-w="12">
                            <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="2" vs-sm="4" vs-xs="12" >
                                
                            </vs-col>
                            <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="8" vs-sm="4" vs-xs="12">
                                <vs-table stripe max-items=25 pagination :data="inmueble_flujo_estados" >
                                    <template slot="thead">
                                        <vs-th class="text-center">Detalle</vs-th>
                                        <vs-th class="text-center">Usuario</vs-th>
                                        <vs-th class="text-center">Fecha de creación</vs-th>
                                    </template>
                                    <template slot-scope="{ data }">
                                        <vs-tr :key="datos.id" v-for="datos in data" class="text-center">    
                                            <vs-td v-if="datos.detalle">{{ datos.detalle}}</vs-td>
                                            <vs-td v-if="datos.nombres_usuario">{{ datos.nombres_usuario }} &nbsp; {{ datos.apellidos_usuario }} </vs-td>
                                            <vs-td v-if="datos.fecha_creacion">{{ datos.fecha_creacion }}</vs-td>
                                        </vs-tr>
                                    </template>
                                </vs-table>
                            </vs-col>
                            <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="2" vs-sm="4" vs-xs="12">
                                
                            </vs-col>
                        </vs-row>
                        
                    </vs-tab>
                </vs-tabs>

                <center>
                    <div class="vx-col w-full">
                            <vs-button color="success" type="filled" @click.prevent="editar()" >EDITAR</vs-button>
                            <vs-button color="danger" type="filled" @click="cancelar()">CANCELAR</vs-button>
                            <vs-button color="primary" type="filled" @click.stop="mostrar_reporte_PDF($route.params.id)">INFORME</vs-button>
                    </div>
                </center>

            </vx-card>
            <vx-card v-else>
                <div class="vx-row" >
                    <div class="vx-col sm:w-1/1 w-full mb-6">
                        <div class="centerx">
                            <ckeditor v-model="editor_inmueble" editorUrl="../../js/ckeditor4-releases-full-4.14.0/ckeditor.js" :config="editor_configuracion" ></ckeditor>
                        </div>
                    </div>
                </div>
                <center>
                    <div class="vx-col w-full">
                            <vs-button color="success" type="filled" @click="editar()" v-if="$route.params.id">EDITAR</vs-button>
                            <vs-button color="success" type="filled" @click="guardar()" v-else>GUARDAR</vs-button>
                            <vs-button color="danger" type="filled" @click="cancelar()">CANCELAR</vs-button>
                            <vs-button color="primary" type="filled" @click="descargar_formulario()">FORMULARIO</vs-button>
                    </div>
                </center>
            </vx-card>
             <!-- Inicio popup Reporte Inmueble -->
            <vs-popup :title="popup_reporte_inmueble_titulo" :active.sync="popup_reporte_inmueble">
                <vs-row>
                    <vs-col vs-type="flex" vs-justify="center" vs-align="center" w="6">
                        <object :data="acta_inmueble_url_reporte" width="900" height="500" type="application/pdf"/>
                    </vs-col>
                </vs-row>
            </vs-popup>
            <!-- Fin popup Reporte Inmueble -->
        </div>
    </div>
</template>

<script>
import { log } from "util";
import {LMap, LTileLayer, LMarker, LPopup} from 'vue2-leaflet';

const $ = require("jquery");
const axios = require("axios");
export default {
    components: {
        LMap,
        LTileLayer,
        LMarker,
        LPopup,
    },
    data() {
        return {
            /**
             * mapeo de datos
             */
            inmueble_id: "",
            editor_inmueble: '',
            editor_configuracion: {
                toolbar : [
                    { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source' ] },
                    { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
                    { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'SelectAll' ] },
                    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ] },
                    { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
                    { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak' ] },
                    { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                    { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                ],
                toolbarGroups :[
                    { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                    { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                    { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ] },
                    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                    { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align'] },
                    { name: 'insert' },
                    { name: 'styles' },
                    { name: 'colors' },
                ],
                extraPlugins: 'tableresize,autogrow',
                removePlugins: 'elementspath',
                autoGrow_minHeight: 300,
                autoGrow_maxHeight: 1000,
                allowedContent: true,
            },

            inmueble_nombre: "", 
            inmueble_institucion: "",
            inmueble_finalidad_avaluo: "",
            inmueble_agencia_oficina: "",
            inmueble_nombre_cliente: "",
            inmueble_direccion: "",
            inmueble_fecha_inspeccion: "",
            inmueble_tipo_bien_descripcion: "",
            inmueble_tipo_bien_descripcion_detalle: "",
            inmueble_ubicacion: "",
            inmueble_provincia: "",
            inmueble_provincias: [],
            inmueble_canton: "",
            inmueble_cantones: [],
            inmueble_parroquia: "",
            inmueble_parroquias: [],
            inmueble_ciudad: "",
            inmueble_ciudades: [],

            inmueble_barrio_urbanizacion: "",
            inmueble_manzana: "",
            inmueble_lote: "",
            inmueble_latitud: "",
            inmueble_longitud: "",
            inmueble_estado: '',
            inmueble_estados: [],
            inmueble_predio: '',

            inmueble_datos_municipales_detalle: "",
            inmueble_datos_municipales_ano_impuesto_predial: 0,
            inmueble_datos_municipales_clave_catastral: "",
            inmueble_datos_municipales_geo_clave: "",
            inmueble_municipio_ano_1_numero: "",
            inmueble_municipio_ano_1_valor: "",
            inmueble_municipio_ano_1_construccion: "",
            inmueble_municipio_ano_1_terreno: "",
            inmueble_municipio_ano_2_numero: "",
            inmueble_municipio_ano_2_valor: "",
            inmueble_municipio_ano_2_construccion: "",
            inmueble_municipio_ano_2_terreno: "",

            inmueble_escritura_detalle: "",
            inmueble_escritura_notaria: "",
            inmueble_escritura_fecha: "",
            inmueble_escritura_superficie: 0,
            inmueble_escritura_cuantia: 0,
            inmueble_escritura_canton: "",
            inmueble_escritura_cantones: [],
            inmueble_imagenes_tipos: [],

            inmueble_avaluo_valor_reposicion_terreno: 0,
            inmueble_avaluo_valor_actual_terreno: 0,
            inmueble_avaluo_valor_realizacion_terreno: 0,
            inmueble_avaluo_valor_reposicion_construccion: 0,
            inmueble_avaluo_valor_actual_construccion: 0,
            inmueble_avaluo_valor_realizacion_construccion: 0,
            inmueble_avaluo_valor_total_reposicion: 0,
            inmueble_avaluo_valor_total_actual: 0,
            inmueble_avaluo_valor_total_realizacion: 0,

            inmueble_entorno_detalle: '',
            inmueble_entorno_listado: [],
            inmueble_entorno_listado_claves: [],
            inmueble_entorno_listado_valores: [],
            inmueble_entorno_descripcion_zona: '',
            inmueble_entorno_servicios: '',
            inmueble_entorno_impacto_ambiental: '',
            inmueble_entorno_equipamiento: [],
            inmueble_entorno_observaciones: '',

            inmueble_terreno_localizacion: [],
            inmueble_terreno_caracteristicas_fisicas: [],
            inmueble_terreno_cerramiento: '',
            inmueble_terreno_linderos_dimensiones: [],
            inmueble_terreno_linderos_dimensiones_area_col1: '',
            inmueble_terreno_linderos_dimensiones_area_col2: '',
            inmueble_terreno_linderos_dimensiones_area: '',

            inmueble_edificacion_caracteristicas: [],
            inmueble_edificacion_areas_edificacion: [],
            inmueble_edificacion_areas_edificacion_total: '',
            inmueble_edificacion_areas_edificacion_total_col1: '',
            inmueble_edificacion_areas_edificacion_total_col2: '',
            inmueble_edificacion_areas_edificacion_otros: [],
            inmueble_edificacion_areas_edificacion_otros_total: '',
            inmueble_edificacion_areas_edificacion_otros_total_col1: '',
            inmueble_edificacion_areas_edificacion_otros_total_col2: '',
            inmueble_edificacion_resumen_infraestructura: [],
            inmueble_edificacion_conservacion_mantenimiento: '',
            inmueble_edificacion_descripcion_funcional: '',

            inmueble_criterio_valoracion_listado: [],
            inmueble_criterio_valoracion_calificacion_listado: [],
            inmueble_criterio_valoracion_terreno_listado: [],
            inmueble_criterio_valoracion_terreno_detalle: '',
            inmueble_criterio_valoracion_terreno_total: [],
            inmueble_criterio_valoracion_construcciones: '',

            inmueble_resumen_valoracion_tabla: [],
            inmueble_resumen_valoracion_reposicion: '',
            inmueble_resumen_valoracion_mercado: '',
            inmueble_resumen_valoracion_realizacion: '',

            popup_reporte_inmueble: false,
            popup_reporte_inmueble_titulo: 'VISUALIZACION DE REPORTE ',
            acta_inmueble_url_reporte: '',

            inmueble_imagen_titulo: "",
            inmueble_imagen_orden: "",
            inmueble_imagen_archivo: "",

            inmueble_imagen_titulo_editar: "",
            inmueble_imagen_orden_editar: "",
            inmueble_imagen_archivo_editar: "",
            inmueble_imagen_id_editar: "",
            inmueble_imagen_binario_editar: "",

            ciudad_valor: "",
            inmueble_imagenes: [],


            inmueble_flujo_estados: [],

            url: '',
            attribution:'',
            zoom: 15,
            center: [],  
            markerLatLng: [],
            defaultIcon: L.icon({
                iconUrl: '../../../images/icon-blue.png',
                iconSize:     [28, 38],
            }),
            popup_titulo_editar_imagen: "EDITAR IMAGEN",
            popup_editar_imagen: false,
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
    props: { modalactive: false },
    methods: {
       descargar_formulario() {    
            const link = document.createElement('a')
            link.href = '../../documentos/Formato Inmueble.xlsm' 
            link.download = 'Formato Inmueble.xlsm'
            link.target = '_blank'
            link.click()
       },
        buscar_datos() {
            var url = "/api/buscaractainmueble/" + this.$route.params.id;
            axios.get(url).then(res => {
                // ------------------------ DATOS DEL SOLICITANTE ---------------------------------------
                
                var acta_inmueble = res.data.acta_inmueble;
                this.inmueble_id = acta_inmueble.id;
                this.inmueble_nombre = acta_inmueble.nombre;
                this.inmueble_institucion = acta_inmueble.institucion;
                this.inmueble_finalidad_avaluo = acta_inmueble.finalidad_avaluo;
                this.inmueble_agencia_oficina = acta_inmueble.agencia_oficina;
                this.inmueble_nombre_cliente = acta_inmueble.nombre_cliente;
                this.inmueble_direccion = acta_inmueble.direccion;
                this.inmueble_fecha_inspeccion = acta_inmueble.fecha_inspeccion;
                this.listar_estado_inmuebles(1,'');
                this.inmueble_estado = acta_inmueble.acta_estado_id;

                // ------------------------ TIPO DE BIEN ---------------------------------------

                this.inmueble_tipo_bien_descripcion = acta_inmueble.tipo_bien_descripcion;
                this.inmueble_tipo_bien_descripcion_detalle = acta_inmueble.tipo_bien_descripcion_detalle;
                
                // ------------------------ UBICACION ---------------------------------------

                this.listar_provincias();
                this.inmueble_ubicacion = acta_inmueble.ubicacion;
                this.inmueble_provincia = acta_inmueble.ubicacion_provincia_id;
                
                this.listar_cantones_por_provincia(this.inmueble_provincia);
                this.inmueble_canton = acta_inmueble.ubicacion_canton_id;

                this.listar_ciudades_por_provincia(this.inmueble_provincia);
                this.inmueble_ciudad = acta_inmueble.ubicacion_ciudad_id;
                
                this.listar_parroquias_por_canton(this.inmueble_canton);
                this.inmueble_parroquia = acta_inmueble.ubicacion_parroquia_id;


                this.inmueble_barrio_urbanizacion = acta_inmueble.ubicacion_barrio;
                this.inmueble_manzana = acta_inmueble.ubicacion_manzana;
                this.inmueble_lote = acta_inmueble.ubicacion_lote;
                this.inmueble_latitud = acta_inmueble.ubicacion_latitud;
                this.inmueble_longitud = acta_inmueble.ubicacion_longitud;
                this.inmueble_predio = acta_inmueble.ubicacion_predio;

                // ------------------------ DATOS MUNICIPALES ---------------------------------------
                var acta_inmueble_municipio = res.data.acta_inmueble_municipio;
                this.inmueble_datos_municipales_detalle = acta_inmueble_municipio.detalle;
                this.inmueble_datos_municipales_ano_impuesto_predial = acta_inmueble_municipio.ano_impuesto_predial;
                this.inmueble_datos_municipales_clave_catastral = acta_inmueble_municipio.clave_catastral;
                this.inmueble_datos_municipales_geo_clave = acta_inmueble_municipio.geo_clave;
                this.inmueble_municipio_ano_1_numero =  acta_inmueble_municipio.ano_1_numero;
                this.inmueble_municipio_ano_1_valor = acta_inmueble_municipio.ano_1_valor;
                this.inmueble_municipio_ano_1_construccion = acta_inmueble_municipio.ano_1_construccion;
                this.inmueble_municipio_ano_1_terreno =  acta_inmueble_municipio.ano_1_terreno;
                this.inmueble_municipio_ano_2_numero = acta_inmueble_municipio.ano_2_numero;
                this.inmueble_municipio_ano_2_valor = acta_inmueble_municipio.ano_2_valor;
                this.inmueble_municipio_ano_2_construccion = acta_inmueble_municipio.ano_2_construccion;
                this.inmueble_municipio_ano_2_terreno = acta_inmueble_municipio.ano_2_terreno;

                // ------------------------ DATOS DE LAS ESCRITURAS ---------------------------------------
                var acta_inmueble_escritura = res.data.acta_inmueble_escritura;
                this.inmueble_escritura_detalle = acta_inmueble_escritura.detalle;
                this.inmueble_escritura_notaria = acta_inmueble_escritura.notaria;
                this.inmueble_escritura_fecha = acta_inmueble_escritura.fecha_escrituracion_registro;
                this.inmueble_escritura_superficie = acta_inmueble_escritura.superficie;
                this.inmueble_escritura_cuantia = acta_inmueble_escritura.cuantia;
                this.listar_cantones();
                this.inmueble_escritura_canton = acta_inmueble_escritura.acta_canton_id;
                
                // ------------------------ RESUMEN DE AVALUO ---------------------------------------

                var acta_inmueble_avaluo = res.data.acta_inmueble_avaluo;
                this.inmueble_avaluo_valor_reposicion_terreno = acta_inmueble_avaluo.valor_reposicion_terreno;
                this.inmueble_avaluo_valor_actual_terreno = acta_inmueble_avaluo.valor_actual_terreno;
                this.inmueble_avaluo_valor_realizacion_terreno = acta_inmueble_avaluo.valor_realizacion_terreno;

                this.inmueble_avaluo_valor_reposicion_construccion = acta_inmueble_avaluo.valor_reposicion_construccion;
                this.inmueble_avaluo_valor_actual_construccion = acta_inmueble_avaluo.valor_actual_construccion;
                this.inmueble_avaluo_valor_realizacion_construccion = acta_inmueble_avaluo.valor_realizacion_construccion;

                this.inmueble_avaluo_valor_total_reposicion = acta_inmueble_avaluo.valor_total_reposicion;
                this.inmueble_avaluo_valor_total_actual = acta_inmueble_avaluo.valor_total_actual;
                this.inmueble_avaluo_valor_total_realizacion = acta_inmueble_avaluo.valor_total_realizacion;

                // ------------------------ CARACTERISTICAS DEL ENTORNO ---------------------------------------

                var acta_inmueble_entorno = res.data.acta_inmueble_entorno;
                const acta_inmueble_entorno_objeto = JSON.parse(acta_inmueble_entorno.propiedades);
                
                this.inmueble_entorno_detalle = acta_inmueble_entorno_objeto.detalle;
                this.inmueble_entorno_listado = acta_inmueble_entorno_objeto.entorno;
                this.inmueble_entorno_servicios = acta_inmueble_entorno_objeto.servicio;
                this.inmueble_entorno_impacto_ambiental = acta_inmueble_entorno_objeto.impacto_ambiental;
                this.inmueble_entorno_equipamiento = acta_inmueble_entorno_objeto.equipamiento_zona;
                this.inmueble_entorno_descripcion_zona = acta_inmueble_entorno_objeto.descripcion_zona;
                this.inmueble_entorno_observaciones = acta_inmueble_entorno_objeto.observacion_ocupacion;

                // ------------------------ TERRENO ---------------------------------------
                var acta_inmueble_terreno = res.data.acta_inmueble_terreno;
                const acta_inmueble_terreno_objeto = JSON.parse(acta_inmueble_terreno.propiedades);

                this.inmueble_terreno_localizacion = acta_inmueble_terreno_objeto.localizacion;
                this.inmueble_terreno_caracteristicas_fisicas = acta_inmueble_terreno_objeto.caracteristicas_fisicas;
                this.inmueble_terreno_cerramiento = acta_inmueble_terreno_objeto.cerramiento;
                this.inmueble_terreno_linderos_dimensiones = acta_inmueble_terreno_objeto.linderos;
                this.inmueble_terreno_linderos_dimensiones_area_col1 = acta_inmueble_terreno_objeto.area_terreno.split('|')[0];
                this.inmueble_terreno_linderos_dimensiones_area_col2 = acta_inmueble_terreno_objeto.area_terreno.split('|')[1];
                

                // ------------------------ EDIFICACIÓN ---------------------------------------
                var acta_inmueble_edificacion = res.data.acta_inmueble_edificacion;
                const acta_inmueble_edificacion_objeto = JSON.parse(acta_inmueble_edificacion.propiedades);

                this.inmueble_edificacion_caracteristicas = acta_inmueble_edificacion_objeto.caracteristicas;

                this.inmueble_edificacion_areas_edificacion = acta_inmueble_edificacion_objeto.areas_edificacion;
                this.inmueble_edificacion_areas_edificacion_total_col1 = acta_inmueble_edificacion_objeto.areas_edificacion_total.total_area_edificacion.split('|')[0];
                this.inmueble_edificacion_areas_edificacion_total_col2 = acta_inmueble_edificacion_objeto.areas_edificacion_total.total_area_edificacion.split('|')[1];

                this.inmueble_edificacion_areas_edificacion_otros = acta_inmueble_edificacion_objeto.areas_edificacion_otros;
                this.inmueble_edificacion_areas_edificacion_otros_total_col1 = acta_inmueble_edificacion_objeto.areas_edificacion_otros_total.total_area_otros.split('|')[0];
                this.inmueble_edificacion_areas_edificacion_otros_total_col2 = acta_inmueble_edificacion_objeto.areas_edificacion_otros_total.total_area_otros.split('|')[1];


                this.inmueble_edificacion_resumen_infraestructura = acta_inmueble_edificacion_objeto.resumen_infraestructura;


                this.inmueble_edificacion_conservacion_mantenimiento = acta_inmueble_edificacion_objeto.conservacion_mantenimiento;
                this.inmueble_edificacion_descripcion_funcional = acta_inmueble_edificacion_objeto.descripcion_funcional;

                // ------------------------ CRITERIOS PARA LA VALORACION  ---------------------------------------
                var acta_inmueble_criterio_valoracion = res.data.acta_inmueble_criterio_valoracion;
                const acta_inmueble_criterio_valoracion_objeto = JSON.parse(acta_inmueble_criterio_valoracion.propiedades);

                this.inmueble_criterio_valoracion_listado = acta_inmueble_criterio_valoracion_objeto.criterios_valoracion;
                this.inmueble_criterio_valoracion_calificacion_listado = acta_inmueble_criterio_valoracion_objeto.criterios_valoracion_calificacion;
                this.inmueble_criterio_valoracion_terreno_listado = acta_inmueble_criterio_valoracion_objeto.valoracion_terreno;
                this.inmueble_criterio_valoracion_terreno_detalle = acta_inmueble_criterio_valoracion_objeto.valoracion_terreno_detalle;

                var arr_criterio_valoracion_terreno_total = {};
                arr_criterio_valoracion_terreno_total["valoracion_terreno_total"] = acta_inmueble_criterio_valoracion_objeto.valoracion_terreno_total.valoracion_terreno_total.replace('$','');

                this.inmueble_criterio_valoracion_terreno_total = arr_criterio_valoracion_terreno_total;
                this.inmueble_criterio_valoracion_construcciones = acta_inmueble_criterio_valoracion_objeto.valoracion_construcciones;

                // ------------------------ CUADRO RESUMEN DE VALORACIÓN  ---------------------------------------
                var acta_inmueble_resumen_valoracion = res.data.acta_inmueble_resumen_valoracion;
                const acta_inmueble_resumen_valoracion_objeto = JSON.parse(acta_inmueble_resumen_valoracion.propiedades);    

                this.inmueble_resumen_valoracion_tabla = acta_inmueble_resumen_valoracion_objeto.resumen_valoracion;
                this.inmueble_resumen_valoracion_reposicion = acta_inmueble_resumen_valoracion_objeto.resumen_valoracion_reposicion;
                this.inmueble_resumen_valoracion_mercado = acta_inmueble_resumen_valoracion_objeto.resumen_valoracion_mercado;
                this.inmueble_resumen_valoracion_realizacion = acta_inmueble_resumen_valoracion_objeto.resumen_valoracion_realizacion;

                // --------------------------   SECCION MAPA --------------------------------------------------

                this.url = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
                this.center =  [this.inmueble_latitud , this.inmueble_longitud];  
                this.markerLatLng =  [this.inmueble_latitud , this.inmueble_longitud];
                          

                // --------------------------- SECCION IMAGENES -----------------------------------------------
                this.listar_imagenes_tipos();

                // *************************** SECCION CAMBIO DE ESTADOS ***********************************

                var acta_inmueble_flujos = res.data.acta_inmueble_flujos;
                this.inmueble_flujo_estados = acta_inmueble_flujos;
                

            });
        },
        /*
         * Guardar los datos del formulario
         */
        guardar() {
            /*if (this.validar()) {
                return;
            }*/
            let formData = new FormData();

            formData.append("editor_inmueble", this.editor_inmueble);
            formData.append("user_id", this.usuario.id);
            formData.append("empresa_id", this.usuario.id_empresa);
            
            axios.post("/api/guardarinmueble", formData).then(res => {
                    this.inmueble_id = res.data.inmueble_id;
                    this.$vs.notify({ title: "Inmueble Guardado", text: "Registro guardado exitosamente", color: "success" });
                    window.location.href = `/verificacion_activos/agregar_inmueble/${this.inmueble_id}/editar`;
                })
                .catch(err => {
                    console.log(err);
                });
        },
        editar() {
            /*if (this.validar()) {
                return;
            }*/

            /* INICIO DATOS GENERALES */

            this.inmueble_institucion = this.$el.querySelectorAll('.inmueble_institucion')[0].value;
            this.inmueble_finalidad_avaluo = this.$el.querySelectorAll('.inmueble_finalidad_avaluo')[0].value;
            this.inmueble_agencia_oficina = this.$el.querySelectorAll('.inmueble_agencia_oficina')[0].value;
            this.inmueble_nombre_cliente = this.$el.querySelectorAll('.inmueble_nombre_cliente')[0].value;
            this.inmueble_direccion = this.$el.querySelectorAll('.inmueble_direccion')[0].value;
            this.inmueble_fecha_inspeccion = this.$el.querySelectorAll('.inmueble_fecha_inspeccion')[0].value;
            
            this.inmueble_tipo_bien_descripcion = this.$el.querySelectorAll('.inmueble_tipo_bien_descripcion')[0].value;
            this.inmueble_tipo_bien_descripcion_detalle = this.$el.querySelectorAll('.inmueble_tipo_bien_descripcion_detalle')[0].value;

            this.inmueble_ubicacion = this.$el.querySelectorAll('.inmueble_ubicacion')[0].value;
            this.inmueble_provincia = this.$el.querySelectorAll('.inmueble_provincia')[0].value;
            this.inmueble_canton = this.$el.querySelectorAll('.inmueble_canton')[0].value;
            this.inmueble_parroquia = this.$el.querySelectorAll('.inmueble_parroquia')[0].value;
            this.inmueble_ciudad = this.$el.querySelectorAll('.inmueble_ciudad')[0].value;
            this.inmueble_barrio_urbanizacion = this.$el.querySelectorAll('.inmueble_barrio_urbanizacion')[0].value;
            this.inmueble_manzana = this.$el.querySelectorAll('.inmueble_manzana')[0].value;
            this.inmueble_lote = this.$el.querySelectorAll('.inmueble_lote')[0].value;
            this.inmueble_latitud = this.$el.querySelectorAll('.inmueble_latitud')[0].value;
            this.inmueble_longitud = this.$el.querySelectorAll('.inmueble_longitud')[0].value;
            this.inmueble_predio = this.$el.querySelectorAll('.inmueble_predio')[0].value;

            
            this.inmueble_datos_municipales_detalle = this.$el.querySelectorAll('.inmueble_datos_municipales_detalle_valor')[0].value;
            this.inmueble_datos_municipales_ano_impuesto_predial = this.$el.querySelectorAll('.inmueble_datos_municipales_ano_impuesto_predial_valor')[0].value;
            this.inmueble_datos_municipales_clave_catastral = this.$el.querySelectorAll('.inmueble_datos_municipales_clave_catastral_valor')[0].value;
            this.inmueble_datos_municipales_geo_clave = this.$el.querySelectorAll('.inmueble_datos_municipales_geo_clave_valor')[0].value;

            this.inmueble_municipio_ano_1_numero = this.$el.querySelectorAll('.inmueble_municipio_ano_1_numero')[0].value;
            this.inmueble_municipio_ano_1_valor = this.$el.querySelectorAll('.inmueble_municipio_ano_1_valor')[0].value;
            this.inmueble_municipio_ano_1_construccion = this.$el.querySelectorAll('.inmueble_municipio_ano_1_construccion')[0].value;
            this.inmueble_municipio_ano_1_terreno = this.$el.querySelectorAll('.inmueble_municipio_ano_1_terreno')[0].value;

            this.inmueble_municipio_ano_2_numero = this.$el.querySelectorAll('.inmueble_municipio_ano_2_numero')[0].value;
            this.inmueble_municipio_ano_2_valor = this.$el.querySelectorAll('.inmueble_municipio_ano_2_valor')[0].value;
            this.inmueble_municipio_ano_2_construccion = this.$el.querySelectorAll('.inmueble_municipio_ano_2_construccion')[0].value;
            this.inmueble_municipio_ano_2_terreno = this.$el.querySelectorAll('.inmueble_municipio_ano_2_terreno')[0].value;

            this.inmueble_escritura_detalle = this.$el.querySelectorAll('.inmueble_escritura_detalle_valor')[0].value;
            this.inmueble_escritura_notaria = this.$el.querySelectorAll('.inmueble_escritura_notaria_valor')[0].value;
            this.inmueble_escritura_canton = this.$el.querySelectorAll('.inmueble_escritura_canton_valor')[0].value;
            this.inmueble_escritura_fecha = this.$el.querySelectorAll('.inmueble_escritura_fecha_valor')[0].value;
            this.inmueble_escritura_superficie = this.$el.querySelectorAll('.inmueble_escritura_superficie_valor')[0].value;
            this.inmueble_escritura_cuantia = this.$el.querySelectorAll('.inmueble_escritura_cuantia_valor')[0].value;

            this.inmueble_avaluo_valor_reposicion_terreno = this.$el.querySelectorAll('.inmueble_avaluo_valor_reposicion_terreno_valor')[0].value;
            this.inmueble_avaluo_valor_actual_terreno = this.$el.querySelectorAll('.inmueble_avaluo_valor_actual_terreno_valor')[0].value;
            this.inmueble_avaluo_valor_realizacion_terreno = this.$el.querySelectorAll('.inmueble_avaluo_valor_realizacion_terreno_valor')[0].value;

            this.inmueble_avaluo_valor_reposicion_construccion = this.$el.querySelectorAll('.inmueble_avaluo_valor_reposicion_construccion_valor')[0].value;
            this.inmueble_avaluo_valor_actual_construccion = this.$el.querySelectorAll('.inmueble_avaluo_valor_actual_construccion_valor')[0].value;
            this.inmueble_avaluo_valor_realizacion_construccion = this.$el.querySelectorAll('.inmueble_avaluo_valor_realizacion_construccion_valor')[0].value;

            this.inmueble_avaluo_valor_total_reposicion = this.$el.querySelectorAll('.inmueble_avaluo_valor_total_reposicion_valor')[0].value;
            this.inmueble_avaluo_valor_total_actual = this.$el.querySelectorAll('.inmueble_avaluo_valor_total_actual_valor')[0].value;
            this.inmueble_avaluo_valor_total_realizacion = this.$el.querySelectorAll('.inmueble_avaluo_valor_total_realizacion_valor')[0].value;

            /* FIN DATOS GENERALES */

            /* INICIO ENTORNO */            
            const inmueble_entorno_listado_clave_listado = this.$el.querySelectorAll('.inmueble_entorno_listado_clave');
            const inmueble_entorno_listado_valor_listado = this.$el.querySelectorAll('.inmueble_entorno_listado_valor');

            var arr_entorno = {};

            for (let i = 0; i < inmueble_entorno_listado_clave_listado.length; i++) {
                let clave = inmueble_entorno_listado_clave_listado[i].value;
                let valor = inmueble_entorno_listado_valor_listado[i].value;
                arr_entorno[clave] = valor;
            }
            
            const inmueble_entorno_equipamiento_clave_listado = this.$el.querySelectorAll('.inmueble_entorno_equipamiento_clave');
            const inmueble_entorno_equipamiento_valor_listado = this.$el.querySelectorAll('.inmueble_entorno_equipamiento_valor');
            
            var arr_entorno_equipamiento = {};

            for (let i = 0; i < inmueble_entorno_equipamiento_clave_listado.length; i++) {
                let clave = inmueble_entorno_equipamiento_clave_listado[i].value;
                let valor = inmueble_entorno_equipamiento_valor_listado[i].value;
                arr_entorno_equipamiento[clave] = valor;
            }

            this.inmueble_entorno_detalle = this.$el.querySelectorAll('.inmueble_entorno_detalle_valor')[0].value;
            this.inmueble_entorno_servicios = this.$el.querySelectorAll('.inmueble_entorno_servicios_valor')[0].value;
            this.inmueble_entorno_descripcion_zona = this.$el.querySelectorAll('.inmueble_entorno_descripcion_zona_valor')[0].value;
            this.inmueble_entorno_impacto_ambiental = this.$el.querySelectorAll('.inmueble_entorno_impacto_ambiental_valor')[0].value;
            this.inmueble_entorno_observaciones  = this.$el.querySelectorAll('.inmueble_entorno_observaciones_valor')[0].value;
            /* FIN ENTORNO */

            /* INICIO TERRENO */            
            const inmueble_terreno_localizacion_clave_listado = this.$el.querySelectorAll('.inmueble_terreno_localizacion_clave');
            const inmueble_terreno_localizacion_valor_listado = this.$el.querySelectorAll('.inmueble_terreno_localizacion_valor');

            var arr_terreno_localizacion = {};

            for (let i = 0; i < inmueble_terreno_localizacion_clave_listado.length; i++) {
                let clave = inmueble_terreno_localizacion_clave_listado[i].value;
                let valor = inmueble_terreno_localizacion_valor_listado[i].value;
                arr_terreno_localizacion[clave] = valor;
            }


            const inmueble_terreno_caracteristicas_fisicas_clave_listado = this.$el.querySelectorAll('.inmueble_terreno_caracteristicas_fisicas_clave');
            const inmueble_terreno_caracteristicas_fisicas_valor_listado = this.$el.querySelectorAll('.inmueble_terreno_caracteristicas_fisicas_valor');

            var arr_terreno_caracteristicas_fisicas = {};

            for (let i = 0; i < inmueble_terreno_caracteristicas_fisicas_clave_listado.length; i++) {
                let clave = inmueble_terreno_caracteristicas_fisicas_clave_listado[i].value;
                let valor = inmueble_terreno_caracteristicas_fisicas_valor_listado[i].value;
                arr_terreno_caracteristicas_fisicas[clave] = valor;
            }

            const inmueble_terreno_cerramiento_valor = this.$el.querySelectorAll('.inmueble_terreno_cerramiento')[0].value;

            const inmueble_terreno_linderos_dimensiones_col1_listado = this.$el.querySelectorAll('.inmueble_terreno_linderos_dimensiones_col1');
            const inmueble_terreno_linderos_dimensiones_col2_listado = this.$el.querySelectorAll('.inmueble_terreno_linderos_dimensiones_col2');
            const inmueble_terreno_linderos_dimensiones_col3_listado = this.$el.querySelectorAll('.inmueble_terreno_linderos_dimensiones_col3');
            const inmueble_terreno_linderos_dimensiones_col4_listado = this.$el.querySelectorAll('.inmueble_terreno_linderos_dimensiones_col4');
            const inmueble_terreno_linderos_dimensiones_col5_listado = this.$el.querySelectorAll('.inmueble_terreno_linderos_dimensiones_col5');

            var arr_inmueble_terreno_linderos_dimensiones_listado = {};

            for (let i = 0; i < inmueble_terreno_linderos_dimensiones_col1_listado.length; i++) {
                let linderos = inmueble_terreno_linderos_dimensiones_col1_listado[i].value;
                let coordenadas = inmueble_terreno_linderos_dimensiones_col2_listado[i].value;
                let descripcion = inmueble_terreno_linderos_dimensiones_col3_listado[i].value;
                let escritura = inmueble_terreno_linderos_dimensiones_col4_listado[i].value;
                let comprobacion_sitio = inmueble_terreno_linderos_dimensiones_col5_listado[i].value;

                arr_inmueble_terreno_linderos_dimensiones_listado[i+1] = linderos+'|'+coordenadas+'|'+descripcion+'|'+escritura+'|'+comprobacion_sitio;
            }

            const inmueble_terreno_linderos_dimensiones_area_col1 = this.$el.querySelectorAll('.inmueble_terreno_linderos_dimensiones_area_col1')[0].value;
            const inmueble_terreno_linderos_dimensiones_area_col2 = this.$el.querySelectorAll('.inmueble_terreno_linderos_dimensiones_area_col2')[0].value;
            
            this.inmueble_terreno_linderos_dimensiones_area = inmueble_terreno_linderos_dimensiones_area_col1+'|'+inmueble_terreno_linderos_dimensiones_area_col2;


            /* FIN TERRENO */

            /* INICIO EDIFICACION */
            const inmueble_edificacion_caracteristicas_clave_listado = this.$el.querySelectorAll('.inmueble_edificacion_caracteristicas_clave');
            const inmueble_edificacion_caracteristicas_valor_listado = this.$el.querySelectorAll('.inmueble_edificacion_caracteristicas_valor');

            var arr_edificacion_caracteristicas = {};

            for (let i = 0; i < inmueble_edificacion_caracteristicas_clave_listado.length; i++) {
                let clave = inmueble_edificacion_caracteristicas_clave_listado[i].value;
                let valor = inmueble_edificacion_caracteristicas_valor_listado[i].value;
                arr_edificacion_caracteristicas[clave] = valor;
            }


            const inmueble_areas_edificacion_descripcion_listado = this.$el.querySelectorAll('.inmueble_edificacion_areas_edificacion_col1');
            const inmueble_areas_edificacion_area_cubierta_listado = this.$el.querySelectorAll('.inmueble_edificacion_areas_edificacion_col2');
            const inmueble_areas_edificacion_area_descubierta_listado = this.$el.querySelectorAll('.inmueble_edificacion_areas_edificacion_col3');

            var arr_areas_edificacion = {};
            var arr_areas_edificacion_total = {};
            for (let i = 0; i < inmueble_areas_edificacion_descripcion_listado.length; i++) {
                let descripcion = inmueble_areas_edificacion_descripcion_listado[i].value;
                let area_cubierta = inmueble_areas_edificacion_area_cubierta_listado[i].value;
                let area_descubierta = inmueble_areas_edificacion_area_descubierta_listado[i].value;
                arr_areas_edificacion[descripcion] = area_cubierta+'|'+area_descubierta;
            }

            const inmueble_edificacion_areas_edificacion_total_col1 = this.$el.querySelectorAll('.inmueble_edificacion_areas_edificacion_total_col1')[0].value;
            const inmueble_edificacion_areas_edificacion_total_col2 = this.$el.querySelectorAll('.inmueble_edificacion_areas_edificacion_total_col2')[0].value;
        
            arr_areas_edificacion_total["total_area_edificacion"] = inmueble_edificacion_areas_edificacion_total_col1+'|'+inmueble_edificacion_areas_edificacion_total_col2;

            const inmueble_areas_edificacion_otros_descripcion_listado = this.$el.querySelectorAll('.inmueble_edificacion_areas_edificacion_otros_col1');
            const inmueble_areas_edificacion_otros_area_cubierta_listado = this.$el.querySelectorAll('.inmueble_edificacion_areas_edificacion_otros_col2');
            const inmueble_areas_edificacion_otros_area_descubierta_listado = this.$el.querySelectorAll('.inmueble_edificacion_areas_edificacion_otros_col3');

            var arr_areas_edificacion_otros = {};
            var arr_areas_edificacion_otros_total = {};

            for (let i = 0; i < inmueble_areas_edificacion_otros_descripcion_listado.length; i++) {
                let descripcion = inmueble_areas_edificacion_otros_descripcion_listado[i].value;
                let area_cubierta = inmueble_areas_edificacion_otros_area_cubierta_listado[i].value;
                let area_descubierta = inmueble_areas_edificacion_otros_area_descubierta_listado[i].value;
                arr_areas_edificacion_otros[descripcion] = area_cubierta+'|'+area_descubierta;
            }

            const inmueble_edificacion_areas_edificacion_otros_total_col1 = this.$el.querySelectorAll('.inmueble_edificacion_areas_edificacion_otros_total_col1')[0].value;
            const inmueble_edificacion_areas_edificacion_otros_total_col2 = this.$el.querySelectorAll('.inmueble_edificacion_areas_edificacion_otros_total_col2')[0].value;
            
            arr_areas_edificacion_otros_total["total_area_otros"] = inmueble_edificacion_areas_edificacion_otros_total_col1+'|'+inmueble_edificacion_areas_edificacion_otros_total_col2;

            const inmueble_edificacion_resumen_infraestructura_descripcion_listado = this.$el.querySelectorAll('.inmueble_edificacion_resumen_infraestructura_col1');
            const inmueble_edificacion_resumen_infraestructura_unidad_listado = this.$el.querySelectorAll('.inmueble_edificacion_resumen_infraestructura_col2');
            const inmueble_edificacion_resumen_infraestructura_cantidad_listado = this.$el.querySelectorAll('.inmueble_edificacion_resumen_infraestructura_col3');

            var arr_resumen_infraestructura = {};

            for (let i = 0; i < inmueble_edificacion_resumen_infraestructura_descripcion_listado.length; i++) {
                let descripcion = inmueble_edificacion_resumen_infraestructura_descripcion_listado[i].value;
                let unidad = inmueble_edificacion_resumen_infraestructura_unidad_listado[i].value;
                let cantidad = inmueble_edificacion_resumen_infraestructura_cantidad_listado[i].value;
                arr_resumen_infraestructura[descripcion] = unidad+'|'+cantidad;
            }

            this.inmueble_edificacion_conservacion_mantenimiento = this.$el.querySelectorAll('.inmueble_edificacion_conservacion_mantenimiento')[0].value;
            this.inmueble_edificacion_descripcion_funcional = this.$el.querySelectorAll('.inmueble_edificacion_descripcion_funcional')[0].value;

                    
            /* FIN EDIFICACION */

            /* INICIO CRITERIOS Y METODOS EMPLEADOS EN LA VALORACION */

            const inmueble_criterio_valoracion_listado_clave_listado = this.$el.querySelectorAll('.inmueble_criterio_valoracion_listado_clave');
            const inmueble_criterio_valoracion_listado_valor_listado = this.$el.querySelectorAll('.inmueble_criterio_valoracion_listado_valor');

            var arr_criterio_metodos_valoracion = {};

            for (let i = 0; i < inmueble_criterio_valoracion_listado_clave_listado.length; i++) {
                let clave = inmueble_criterio_valoracion_listado_clave_listado[i].value;
                let valor = inmueble_criterio_valoracion_listado_valor_listado[i].value;
                arr_criterio_metodos_valoracion[clave] = valor;
            }


            const inmueble_criterio_valoracion_calificacion_listado_clave = this.$el.querySelectorAll('.inmueble_criterio_valoracion_calificacion_listado_clave');
            const inmueble_criterio_valoracion_calificacion_listado_valor = this.$el.querySelectorAll('.inmueble_criterio_valoracion_calificacion_listado_valor');

            var arr_criterio_valoracion_calificacion = {};

            for (let i = 0; i < inmueble_criterio_valoracion_calificacion_listado_clave.length; i++) {
                let clave = inmueble_criterio_valoracion_calificacion_listado_clave[i].value;
                let valor = inmueble_criterio_valoracion_calificacion_listado_valor[i].value;
                arr_criterio_valoracion_calificacion[clave] = valor;
            }

            this.inmueble_criterio_valoracion_terreno_detalle = this.$el.querySelectorAll('.inmueble_criterio_valoracion_terreno_detalle')[0].value;
 
            const inmueble_criterio_valoracion_terreno_listado_col1 = this.$el.querySelectorAll('.inmueble_criterio_valoracion_terreno_listado_col1');
            const inmueble_criterio_valoracion_terreno_listado_col2 = this.$el.querySelectorAll('.inmueble_criterio_valoracion_terreno_listado_col2');
            const inmueble_criterio_valoracion_terreno_listado_col3 = this.$el.querySelectorAll('.inmueble_criterio_valoracion_terreno_listado_col3');
            const inmueble_criterio_valoracion_terreno_listado_col4 = this.$el.querySelectorAll('.inmueble_criterio_valoracion_terreno_listado_col4');
            const inmueble_criterio_valoracion_terreno_listado_col5 = this.$el.querySelectorAll('.inmueble_criterio_valoracion_terreno_listado_col5');
            const inmueble_criterio_valoracion_terreno_listado_col6 = this.$el.querySelectorAll('.inmueble_criterio_valoracion_terreno_listado_col6');
            const inmueble_criterio_valoracion_terreno_listado_col7 = this.$el.querySelectorAll('.inmueble_criterio_valoracion_terreno_listado_col7');
            const inmueble_criterio_valoracion_terreno_listado_col8 = this.$el.querySelectorAll('.inmueble_criterio_valoracion_terreno_listado_col8');
            const inmueble_criterio_valoracion_terreno_listado_col9 = this.$el.querySelectorAll('.inmueble_criterio_valoracion_terreno_listado_col9');
            const inmueble_criterio_valoracion_terreno_listado_col10 = this.$el.querySelectorAll('.inmueble_criterio_valoracion_terreno_listado_col10');

            var arr_criterio_valoracion_terreno_listado = {};

            for (let i = 0; i < inmueble_criterio_valoracion_terreno_listado_col1.length; i++) {
                let terreno_venta = inmueble_criterio_valoracion_terreno_listado_col1[i].value;
                let area_m2 = inmueble_criterio_valoracion_terreno_listado_col2[i].value;
                let valor_m2 = inmueble_criterio_valoracion_terreno_listado_col3[i].value;
                let frente = inmueble_criterio_valoracion_terreno_listado_col4[i].value;
                let ubicacion = inmueble_criterio_valoracion_terreno_listado_col5[i].value;
                let tamano = inmueble_criterio_valoracion_terreno_listado_col6[i].value;
                let forma = inmueble_criterio_valoracion_terreno_listado_col7[i].value;
                let adecuacion = inmueble_criterio_valoracion_terreno_listado_col8[i].value;
                let homogenizados = inmueble_criterio_valoracion_terreno_listado_col9[i].value;
                let valor_unitario = inmueble_criterio_valoracion_terreno_listado_col10[i].value;

                arr_criterio_valoracion_terreno_listado[i+1] = terreno_venta+'|'+area_m2+'|'+valor_m2+'|'+frente+'|'+ubicacion+'|'+tamano+'|'+forma+'|'+adecuacion+'|'+homogenizados+'|'+valor_unitario;
            }

            var arr_criterio_valoracion_terreno_total = {};
            const inmueble_criterio_valoracion_terreno_total = this.$el.querySelectorAll('.inmueble_criterio_valoracion_terreno_total')[0].value;
            arr_areas_edificacion_otros_total["valoracion_terreno_total"] = inmueble_criterio_valoracion_terreno_total;
            

            this.inmueble_criterio_valoracion_construcciones = this.$el.querySelectorAll('.inmueble_criterio_valoracion_construcciones')[0].value;

            /* FIN CRITERIOS Y METODOS EMPLEADOS EN LA VALORACION */


            /* INICIO CUADRO RESUMEN VALORACION */


            const inmueble_resumen_valoracion_tabla_col1 = this.$el.querySelectorAll('.inmueble_resumen_valoracion_tabla_col1');
            const inmueble_resumen_valoracion_tabla_col2 = this.$el.querySelectorAll('.inmueble_resumen_valoracion_tabla_col2');
            const inmueble_resumen_valoracion_tabla_col3 = this.$el.querySelectorAll('.inmueble_resumen_valoracion_tabla_col3');
            const inmueble_resumen_valoracion_tabla_col4 = this.$el.querySelectorAll('.inmueble_resumen_valoracion_tabla_col4');
            const inmueble_resumen_valoracion_tabla_col5 = this.$el.querySelectorAll('.inmueble_resumen_valoracion_tabla_col5');

            var arr_inmueble_resumen_valoracion_tabla = {};

            for (let i = 0; i < inmueble_resumen_valoracion_tabla_col1.length; i++) {
                let descripcion = inmueble_resumen_valoracion_tabla_col1[i].value;
                let area_m2 = inmueble_resumen_valoracion_tabla_col2[i].value;
                let valor_reposicion = inmueble_resumen_valoracion_tabla_col3[i].value;
                let valor_actual = inmueble_resumen_valoracion_tabla_col4[i].value;
                let valor_realizacion = inmueble_resumen_valoracion_tabla_col5[i].value;
                
                arr_inmueble_resumen_valoracion_tabla[i+1] = descripcion+'|'+area_m2+'|'+valor_reposicion+'|'+valor_actual+'|'+valor_realizacion;
            }

            this.inmueble_resumen_valoracion_reposicion = this.$el.querySelectorAll('.inmueble_resumen_valoracion_reposicion')[0].value;
            this.inmueble_resumen_valoracion_mercado = this.$el.querySelectorAll('.inmueble_resumen_valoracion_mercado')[0].value;
            this.inmueble_resumen_valoracion_realizacion = this.$el.querySelectorAll('.inmueble_resumen_valoracion_realizacion')[0].value;


            /* FIN CUADRO RESUMEN VALORACION */  


            axios.post("/api/editaractainmueble", {
                    id: this.$route.params.id,
                    nombre: this.inmueble_nombre,
                    inmueble_institucion: this.inmueble_institucion,
                    inmueble_finalidad_avaluo: this.inmueble_finalidad_avaluo,
                    inmueble_agencia_oficina: this.inmueble_agencia_oficina,
                    inmueble_nombre_cliente: this.inmueble_nombre_cliente,
                    inmueble_direccion: this.inmueble_direccion,
                    inmueble_fecha_inspeccion: this.inmueble_fecha_inspeccion,
                    inmueble_tipo_bien_descripcion: this.inmueble_tipo_bien_descripcion,
                    inmueble_tipo_bien_descripcion_detalle: this.inmueble_tipo_bien_descripcion_detalle,
                    inmueble_ubicacion: this.inmueble_ubicacion,
                    inmueble_provincia: this.inmueble_provincia,
                    inmueble_canton: this.inmueble_canton,
                    inmueble_parroquia: this.inmueble_parroquia,
                    inmueble_ciudad: this.inmueble_ciudad,
                    inmueble_barrio_urbanizacion: this.inmueble_barrio_urbanizacion,
                    inmueble_manzana: this.inmueble_manzana,
                    inmueble_lote: this.inmueble_lote,
                    inmueble_latitud: this.inmueble_latitud,
                    inmueble_longitud: this.inmueble_longitud,
                    inmueble_estado: this.inmueble_estado,
                    inmueble_predio: this.inmueble_predio,

                    inmueble_datos_municipales_detalle: this.inmueble_datos_municipales_detalle,
                    inmueble_datos_municipales_ano_impuesto_predial: this.inmueble_datos_municipales_ano_impuesto_predial,
                    inmueble_datos_municipales_clave_catastral: this.inmueble_datos_municipales_clave_catastral,
                    inmueble_datos_municipales_geo_clave: this.inmueble_datos_municipales_geo_clave,
                    inmueble_municipio_ano_1_numero: this.inmueble_municipio_ano_1_numero,
                    inmueble_municipio_ano_1_valor: this.inmueble_municipio_ano_1_valor,
                    inmueble_municipio_ano_1_construccion: this.inmueble_municipio_ano_1_construccion,
                    inmueble_municipio_ano_1_terreno: this.inmueble_municipio_ano_1_terreno,
                    inmueble_municipio_ano_2_numero: this.inmueble_municipio_ano_2_numero,
                    inmueble_municipio_ano_2_valor: this.inmueble_municipio_ano_2_valor,
                    inmueble_municipio_ano_2_construccion: this.inmueble_municipio_ano_2_construccion,
                    inmueble_municipio_ano_2_terreno: this.inmueble_municipio_ano_2_terreno,

                    inmueble_escritura_detalle: this.inmueble_escritura_detalle,
                    inmueble_escritura_notaria: this.inmueble_escritura_notaria,
                    inmueble_escritura_canton: this.inmueble_escritura_canton,
                    inmueble_escritura_fecha: this.inmueble_escritura_fecha,
                    inmueble_escritura_superficie: this.inmueble_escritura_superficie,
                    inmueble_escritura_cuantia: this.inmueble_escritura_cuantia,
            
                    inmueble_avaluo_valor_reposicion_terreno: this.inmueble_avaluo_valor_reposicion_terreno,
                    inmueble_avaluo_valor_actual_terreno: this.inmueble_avaluo_valor_actual_terreno,
                    inmueble_avaluo_valor_realizacion_terreno: this.inmueble_avaluo_valor_realizacion_terreno,
                    inmueble_avaluo_valor_reposicion_construccion: this.inmueble_avaluo_valor_reposicion_construccion,
                    inmueble_avaluo_valor_actual_construccion: this.inmueble_avaluo_valor_actual_construccion,
                    inmueble_avaluo_valor_realizacion_construccion: this.inmueble_avaluo_valor_realizacion_construccion,
                    inmueble_avaluo_valor_total_reposicion: this.inmueble_avaluo_valor_total_reposicion,
                    inmueble_avaluo_valor_total_actual: this.inmueble_avaluo_valor_total_actual,
                    inmueble_avaluo_valor_total_realizacion: this.inmueble_avaluo_valor_total_realizacion,

                    inmueble_entorno_detalle: this.inmueble_entorno_detalle,
                    inmueble_entorno_listado: arr_entorno,
                    inmueble_entorno_descripcion_zona: this.inmueble_entorno_descripcion_zona,
                    inmueble_entorno_servicios: this.inmueble_entorno_servicios,
                    inmueble_entorno_impacto_ambiental: this.inmueble_entorno_impacto_ambiental,
                    inmueble_entorno_equipamiento: arr_entorno_equipamiento,
                    inmueble_entorno_observaciones: this.inmueble_entorno_observaciones,

                    inmueble_terreno_localizacion: arr_terreno_localizacion,
                    inmueble_terreno_caracteristicas_fisicas: arr_terreno_caracteristicas_fisicas,
                    inmueble_terreno_cerramiento: inmueble_terreno_cerramiento_valor,
                    inmueble_terreno_linderos_dimensiones: arr_inmueble_terreno_linderos_dimensiones_listado,
                    inmueble_terreno_linderos_dimensiones_area: this.inmueble_terreno_linderos_dimensiones_area,

                    inmueble_edificacion_caracteristicas: arr_edificacion_caracteristicas,
                    inmueble_edificacion_areas_edificacion: arr_areas_edificacion,
                    inmueble_edificacion_areas_edificacion_total: arr_areas_edificacion_total,
                    inmueble_edificacion_areas_edificacion_otros: arr_areas_edificacion_otros,
                    inmueble_edificacion_areas_edificacion_otros_total: arr_areas_edificacion_otros_total,
                    inmueble_edificacion_resumen_infraestructura: arr_resumen_infraestructura,
                    inmueble_edificacion_conservacion_mantenimiento: this.inmueble_edificacion_conservacion_mantenimiento,
                    inmueble_edificacion_descripcion_funcional: this.inmueble_edificacion_descripcion_funcional,

                    
                    inmueble_criterio_valoracion_listado: arr_criterio_metodos_valoracion,
                    inmueble_criterio_valoracion_calificacion_listado: arr_criterio_valoracion_calificacion,
                    inmueble_criterio_valoracion_terreno_listado: arr_criterio_valoracion_terreno_listado,
                    inmueble_criterio_valoracion_terreno_detalle: this.inmueble_criterio_valoracion_terreno_detalle,
                    inmueble_criterio_valoracion_terreno_total: arr_areas_edificacion_otros_total,
                    inmueble_criterio_valoracion_construcciones: this.inmueble_criterio_valoracion_construcciones,

                    inmueble_resumen_valoracion_tabla: arr_inmueble_resumen_valoracion_tabla,
                    inmueble_resumen_valoracion_reposicion: this.inmueble_resumen_valoracion_reposicion,
                    inmueble_resumen_valoracion_mercado: this.inmueble_resumen_valoracion_mercado,
                    inmueble_resumen_valoracion_realizacion: this.inmueble_resumen_valoracion_realizacion, 

                    user_id: this.usuario.id,
                })
                .then(res => {
                    
                        this.$vs.notify({
                            title: "Inmueble Editado",
                            text: "Registro editado exitosamente",
                            color: "success"
                        });
                        window.location.reload();
                })
                .catch(err => {
                    console.log(err);
                });
        },
        handle_file_upload_ingreso( event ){ 
            this.inmueble_imagen_archivo = event.target.files[0];
        },
        handle_file_upload_editar( event ){ 
            this.inmueble_imagen_archivo_editar = event.target.files[0];
        },    
        /*
         * redireccionar a la pagina de inmuebles
         */
        cancelar() {
            if (this.modalactive == true) {
                this.$emit("CloseCLient", this.clientsend);
            } else {
                this.$router.push("/verificacion_activos/inmuebles");
            }
        },
        listar_inmueble_imagenes(acta_inmueble_id) {
            var url = "/api/buscaractainmuebleimagenes/" + acta_inmueble_id;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.inmueble_imagenes = respuesta.recupera;
            });
        },
        subir_imagen() {

            let formData = new FormData();

            const archivo = this.inmueble_imagen_archivo;
            if (archivo.size > 5 * 1024 * 1024) {
                alert('El archivo debe tener un peso menor a 5MB.');
                return null;
            }

            formData.append("inmueble_imagen_titulo", this.inmueble_imagen_titulo);
            formData.append("inmueble_imagen_orden", this.inmueble_imagen_orden);
            formData.append("inmueble_imagen_archivo", archivo);
            formData.append("user_id", this.usuario.id);
            formData.append("empresa_id", this.usuario.id_empresa);
            formData.append("acta_inmueble_id", this.$route.params.id);  

            axios.post("/api/agregaractainmuebleimagen", formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
              }).then(res => {
                this.$vs.notify({ title: "Imagen Guardada", text: "Registro guardado exitosamente", color: "success" });
                this.listar_inmueble_imagenes(this.$route.params.id);  
                this.inmueble_imagen_titulo = "";
                this.inmueble_imagen_orden = "";

            }).catch(err => {
                console.log(err);
            });
        },
        eliminar_imagen(id) {
            this.$vs.dialog({
                type: "confirm",
                color: "danger",
                title: `Confirmar`,
                text: `¿Desea Eliminar este registro?:`,
                acceptText: "Aceptar",
                cancelText: "Cancelar",
                accept: this.aceptar_alert_eliminar_imagen,
                parameters: id
            });
        },
        aceptar_alert_eliminar_imagen(id) {
            axios.delete("/api/eliminaractainmuebleimagen/" + id);
            this.$vs.notify({
                color: "danger",
                title: "Imagen Eliminada  ",
                text: "La Imagen selecionada fue eliminado con exito"
            });
            this.listar_inmueble_imagenes(this.$route.params.id);  
        },
        editar_imagen(id) {
            this.popup_editar_imagen = true;

            var url = "/api/buscaractainmuebleimagen/" + id;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.inmueble_imagen_id_editar = respuesta.recupera.id;
                this.inmueble_imagen_titulo_editar = respuesta.recupera.titulo;
                this.inmueble_imagen_orden_editar = respuesta.recupera.acta_inmueble_imagen_tipo_id;
                this.inmueble_imagen_binario_editar =  "data:image/jpeg;base64,"+ respuesta.recupera.archivo;
            });
            
        },    
        editar_inmueble_imagen(){

           let formData = new FormData();

            formData.append("inmueble_imagen_id_editar", this.inmueble_imagen_id_editar);
            formData.append("inmueble_imagen_titulo_editar", this.inmueble_imagen_titulo_editar);
            formData.append("inmueble_imagen_orden_editar", this.inmueble_imagen_orden_editar);
            formData.append("inmueble_imagen_archivo_editar", this.inmueble_imagen_archivo_editar);

            axios.post("/api/editaractainmuebleimagen", formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
              }).then(res => {
                this.$vs.notify({ title: "Imagen Editada", text: "Registro editado exitosamente", color: "success" });
                this.popup_editar_imagen = false;
                this.listar_inmueble_imagenes(this.$route.params.id);  
                this.inmueble_imagen_titulo_editar = "";
                this.inmueble_imagen_orden_editar = "";
                this.inmueble_imagen_id_editar = "";
                this.inmueble_imagen_archivo_editar = "";

            }).catch(err => {
                console.log(err);
            });     
        },
        cambiar_provincia($event){
            let provincia = this.inmueble_provincias.find(element => element.id == $event);
            this.inmueble_provincia = provincia.id;
            this.listar_cantones_por_provincia(this.inmueble_provincia);
            this.listar_ciudades_por_provincia(this.inmueble_provincia);
        },
        cambiar_canton($event){
            let canton = this.inmueble_cantones.find(element => element.id == $event);
            this.inmueble_canton = canton.id;
            this.listar_parroquias_por_canton(this.inmueble_canton);
        },
        cambiar_canton_escritura($event){
            let canton = this.inmueble_escritura_cantones.find(element => element.id == $event);
            this.inmueble_escritura_canton = canton.id;
        },    
        cambiar_ciudad($event){
            let ciudad = this.inmueble_ciudades.find(element => element.id == $event);
            this.inmueble_ciudad = ciudad.id;
        },
        cambiar_parroquia($event){
            let parroquia = this.inmueble_parroquias.find(element => element.id == $event);
            this.inmueble_parroquia = parroquia.id;
        },
        listar_provincias() {
            var url = "/api/buscaractaprovincias/";
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.inmueble_provincias = respuesta.recupera;
            });
        },
        listar_cantones() {
            var url = "/api/buscaractacantones/";
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.inmueble_escritura_cantones = respuesta.recupera;
            });
        },
        listar_cantones_por_provincia(provincia_id) {
            var url = "/api/buscaractacantonesxprovincia/"+provincia_id;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.inmueble_cantones = respuesta.recupera;
            });
        },
        listar_ciudades_por_provincia(provincia_id) {
            var url = "/api/buscaractaciudadesxprovincia/"+provincia_id;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.inmueble_ciudades = respuesta.recupera;
            });
        },
        listar_parroquias_por_canton(canton_id) {
            var url = "/api/buscaractaparroquiaxcanton/"+canton_id;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.inmueble_parroquias = respuesta.recupera;
            });
        },
        listar_imagenes_tipos() {
            var url = "/api/buscaractaimagenestipos/";
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.inmueble_imagenes_tipos = respuesta.recupera;
            });
        },
        listar_estado_inmuebles(page1, buscar1) {
            var url = "/api/actaestado/INMUEBLE?page=" + page1 + "&buscar=" + buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.inmueble_estados = respuesta.recupera;
            });
        },
        openPopup: function (event) {
            this.$nextTick(() => {
                event.target.openPopup();
            })
		},
        mostrar_reporte_PDF(acta_inmueble_id) {
            this.acta_inmueble_url_reporte = "/api/reporte_inmueble_pdf/"+this.$route.params.id;
            this.popup_reporte_inmueble = true;
        },
        cambiar_estado($event){
            this.inmueble_estado = $event; 
        },
    },
    mounted() {
        if(this.$route.params.id){
           this.buscar_datos();
           this.listar_inmueble_imagenes(this.$route.params.id);
        }
    },
    
};
</script>
<style>
img {
    max-width: 100%;
    max-height: 100%;
}
.portrait {
    width: 400px;
}

</style>